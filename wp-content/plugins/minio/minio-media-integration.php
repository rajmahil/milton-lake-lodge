<?php

/*
Plugin Name: MinIO Media Integration
Description: Custom integration to upload WordPress media files to MinIO
Version: 1.0
Author: Your Name
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit();
}

require __DIR__ . '/vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException; // Good to use for specific AWS SDK exceptions

// 1️⃣ MinIO client
function my_minio_client()
{
    static $client;

    if (!$client) {
        $required_constants = ['MINIO_ACCESS_KEY', 'MINIO_SECRET_KEY', 'MINIO_ENDPOINT', 'MINIO_BUCKET', 'MINIO_PUBLIC_URL'];
        foreach ($required_constants as $constant) {
            if (!defined($constant)) {
                error_log("MinIO Plugin Error: Constant {$constant} not defined in wp-config.php. This may cause issues.");
            }
        }

        if (!defined('MINIO_ACCESS_KEY') || !defined('MINIO_SECRET_KEY') || !defined('MINIO_ENDPOINT')) {
            error_log('MinIO Plugin Error: Critical constants for S3Client initialization are missing (MINIO_ACCESS_KEY, MINIO_SECRET_KEY, or MINIO_ENDPOINT).');
            return false;
        }

        try {
            $client = new S3Client([
                'version' => 'latest',
                'region' => 'us-east-1', // Or your MinIO region
                'endpoint' => MINIO_ENDPOINT,
                'use_path_style_endpoint' => true,
                'credentials' => [
                    'key' => MINIO_ACCESS_KEY,
                    'secret' => MINIO_SECRET_KEY,
                ],
            ]);
        } catch (Exception $e) {
            // Catch generic exceptions during client init
            error_log('MinIO Plugin Error: Failed to create S3 client - ' . $e->getMessage());
            return false;
        }
    }

    return $client;
}

// 2️⃣ Upload hook — push file to MinIO
add_filter('wp_handle_upload', function ($upload) {
    if (isset($upload['error']) && $upload['error']) {
        return $upload;
    }
    if (!defined('MINIO_BUCKET') || !defined('MINIO_PUBLIC_URL')) {
        error_log('MinIO (wp_handle_upload): MINIO_BUCKET or MINIO_PUBLIC_URL not defined.');
        return $upload;
    }

    $bucket = MINIO_BUCKET;
    $file_path = $upload['file'];

    $upload_dir_details = wp_upload_dir();
    $wp_subdir = ltrim($upload_dir_details['subdir'], '/');
    $key_filename = wp_basename($file_path);
    $key = !empty($wp_subdir) ? $wp_subdir . '/' . $key_filename : $key_filename;

    $s3 = my_minio_client();
    if (!$s3) {
        error_log("MinIO (wp_handle_upload): S3 client not available for key {$key}.");
        return $upload;
    }

    try {
        $s3->putObject([
            'Bucket' => $bucket,
            'Key' => $key,
            'SourceFile' => $file_path,
            'ACL' => 'public-read',
        ]);

        $cdn_base = rtrim(MINIO_PUBLIC_URL, '/');
        $minio_url = $cdn_base . '/' . $key;
        $upload['url'] = $minio_url;
    } catch (AwsException $e) {
        error_log('MinIO Upload Error (wp_handle_upload): ' . $e->getMessage() . ' for key: ' . $key);
    }
    return $upload;
});

// 3️⃣ Upload thumbnails and handle attachment metadata
add_filter(
    'wp_generate_attachment_metadata',
    function ($metadata, $attachment_id) {
        if (!defined('MINIO_BUCKET')) {
            error_log('MinIO (wp_generate_attachment_metadata): MINIO_BUCKET not defined.');
            return $metadata;
        }
        $bucket = MINIO_BUCKET;
        $s3 = my_minio_client();
        if (!$s3) {
            error_log("MinIO (wp_generate_attachment_metadata): S3 client not available for attachment ID {$attachment_id}.");
            return $metadata;
        }

        $upload_dir = wp_upload_dir();
        $base_dir = $upload_dir['basedir'];

        // Main file
        if (isset($metadata['file'])) {
            // $metadata['file'] is like '2025/05/image.jpg'
            $main_file_path = $base_dir . '/' . $metadata['file'];
            $main_key = $metadata['file']; // This is already relative to uploads dir.

            if (file_exists($main_file_path)) {
                try {
                    $s3->putObject([
                        'Bucket' => $bucket,
                        'Key' => $main_key,
                        'SourceFile' => $main_file_path,
                        'ACL' => 'public-read',
                    ]);
                } catch (AwsException $e) {
                    error_log('MinIO Upload Error (wp_generate_attachment_metadata - main): ' . $e->getMessage() . ' for key: ' . $main_key);
                }
            }
        }

        // Sizes (thumbnails)
        if (isset($metadata['sizes']) && is_array($metadata['sizes'])) {
            $file_dir = dirname($metadata['file']);
            $basepath_for_thumbs = $file_dir === '.' || empty($file_dir) ? '' : trailingslashit($file_dir);

            foreach ($metadata['sizes'] as $size => $size_data) {
                $thumb_file_path = $base_dir . '/' . $basepath_for_thumbs . $size_data['file'];
                $thumb_key = $basepath_for_thumbs . $size_data['file'];

                if (file_exists($thumb_file_path)) {
                    try {
                        $s3->putObject([
                            'Bucket' => $bucket,
                            'Key' => $thumb_key,
                            'SourceFile' => $thumb_file_path,
                            'ACL' => 'public-read',
                        ]);
                    } catch (AwsException $e) {
                        error_log("MinIO Upload Error (wp_generate_attachment_metadata - thumb {$size}): " . $e->getMessage() . ' for key: ' . $thumb_key);
                    }
                }
            }
        }
        return $metadata;
    },
    10,
    2,
);

// 4️⃣ Filter the main attachment URL
add_filter(
    'wp_get_attachment_url',
    function ($url, $attachment_id) {
        if (!defined('MINIO_PUBLIC_URL')) {
            return $url;
        }
        $attached_file = get_post_meta($attachment_id, '_wp_attached_file', true);
        if ($attached_file) {
            $cdn_base = rtrim(MINIO_PUBLIC_URL, '/');
            return $cdn_base . '/' . ltrim($attached_file, '/');
        }
        return $url;
    },
    10,
    2,
);

// 5️⃣ Filter URLs for specific image sizes (CRUCIAL for srcset)
add_filter(
    'wp_get_attachment_image_src',
    function ($image, $attachment_id, $size, $icon) {
        if (!defined('MINIO_PUBLIC_URL')) {
            return $image;
        }
        if (!$image || !is_array($image) || !isset($image[0])) {
            return $image;
        }

        $current_url = $image[0];
        $upload_dir_details = wp_upload_dir();
        $local_base_url = $upload_dir_details['baseurl'];

        if (strpos($current_url, rtrim(MINIO_PUBLIC_URL, '/')) === 0) {
            return $image; // Already a CDN URL
        }

        if ($local_base_url && strpos($current_url, $local_base_url) === 0) {
            $cdn_base = rtrim(MINIO_PUBLIC_URL, '/');
            $relative_path = str_replace($local_base_url, '', $current_url);
            $image[0] = $cdn_base . ltrim($relative_path, '/');
        }
        return $image;
    },
    10,
    4,
);

// 6️⃣ Prepare attachment data for JavaScript (Media Library, Gutenberg)
add_filter(
    'wp_prepare_attachment_for_js',
    function ($response, $attachment, $meta) {
        if (!defined('MINIO_PUBLIC_URL')) {
            return $response;
        }

        $cdn_base = rtrim(MINIO_PUBLIC_URL, '/');
        $attached_file_path = get_post_meta($attachment->ID, '_wp_attached_file', true);

        if ($attached_file_path) {
            if (isset($response['url'])) {
                $response['url'] = $cdn_base . '/' . ltrim($attached_file_path, '/');
            }

            if (isset($response['sizes']) && is_array($response['sizes']) && isset($meta['sizes']) && is_array($meta['sizes'])) {
                $file_dirname = dirname($attached_file_path);
                $basepath_for_thumbs = $file_dirname === '.' || empty($file_dirname) ? '' : trailingslashit($file_dirname);

                foreach ($response['sizes'] as $size_name => &$size_info) {
                    if (isset($meta['sizes'][$size_name]['file'])) {
                        $thumb_filename = $meta['sizes'][$size_name]['file'];
                        $size_info['url'] = $cdn_base . '/' . $basepath_for_thumbs . $thumb_filename;
                    }
                }
                unset($size_info);
            }
        }
        return $response;
    },
    10,
    3,
);

// 7️⃣ Handle edited images (crops, rotations, etc.)
add_filter(
    'wp_save_image_editor_file',
    function ($saved, $filename, $image_editor, $mime_type) {
        error_log('✅ wp_save_image_editor_file called:');
        error_log('→ saved: ' . print_r($saved, true));
        error_log("→ filename: $filename");
        error_log("→ mime_type: $mime_type");
        error_log('→ image_editor class: ' . get_class($image_editor));

        return $saved;
    },
    10,
    4,
);

add_action(
    'updated_post_meta',
    function ($meta_id, $object_id, $meta_key, $_meta_value) {
        if ($meta_key === '_wp_attached_file') {
            $attachment_id = $object_id;

            error_log('✅ _wp_attached_file updated — regenerating attachment metadata...');

            // Generate new attachment metadata
            $metadata = wp_generate_attachment_metadata($attachment_id, wp_get_original_image_path($attachment_id));

            if ($metadata) {
                update_post_meta($attachment_id, '_wp_attachment_metadata', $metadata);

                error_log('✅ New attachment metadata generated.');

                // Now upload main file + thumbnails to MinIO:
                $bucket = MINIO_BUCKET;
                $s3 = my_minio_client();

                $upload_dir = wp_upload_dir();
                $base_dir = $upload_dir['basedir'];

                // Upload main file
                if (isset($metadata['file'])) {
                    $main_file_path = $base_dir . '/' . $metadata['file'];
                    $relative_path = ltrim(str_replace($base_dir, '', $main_file_path), '/');
                    $key = $relative_path;

                    if (file_exists($main_file_path)) {
                        $s3->putObject([
                            'Bucket' => $bucket,
                            'Key' => $key,
                            'SourceFile' => $main_file_path,
                            'ACL' => 'public-read',
                        ]);

                        error_log("✅ Uploaded main file to MinIO: $key");
                    }
                }

                // Upload thumbnails
                if (isset($metadata['sizes']) && is_array($metadata['sizes'])) {
                    $basepath = wp_basename(dirname($metadata['file'])) . '/';

                    foreach ($metadata['sizes'] as $size => $size_data) {
                        $thumb_file_path = $base_dir . '/' . $basepath . $size_data['file'];
                        $thumb_key = $basepath . $size_data['file'];

                        if (file_exists($thumb_file_path)) {
                            $s3->putObject([
                                'Bucket' => $bucket,
                                'Key' => $thumb_key,
                                'SourceFile' => $thumb_file_path,
                                'ACL' => 'public-read',
                            ]);

                            error_log("✅ Uploaded thumbnail '$size' to MinIO: $thumb_key");
                        } else {
                            error_log("⚠️ Thumbnail '$size' not found: $thumb_file_path");
                        }
                    }
                }
            } else {
                error_log('⚠️ Failed to generate attachment metadata.');
            }
        }
    },
    10,
    4,
);

// 8️⃣ Filter content on the frontend (with frontend/admin distinction and late priority)
add_filter(
    'the_content',
    function ($content) {
        if (is_admin()) {
            return $content;
        }

        if (!defined('MINIO_PUBLIC_URL')) {
            error_log('MinIO (the_content filter - FRONTEND): MINIO_PUBLIC_URL not defined. Exiting.');
            return $content;
        }

        $upload_dir_details = wp_upload_dir();
        $local_base_url = $upload_dir_details['baseurl'];
        $cdn_base_url = rtrim(MINIO_PUBLIC_URL, '/');

        if ($local_base_url && $cdn_base_url && $local_base_url !== $cdn_base_url) {
            $content_before_replace = $content;
            $content = str_replace($local_base_url, $cdn_base_url, $content);

            if ($content !== $content_before_replace) {
                // error_log('MinIO (the_content filter - FRONTEND): URLs WERE REPLACED. Content changed.'); // Optional: for debugging
            } else {
                // error_log("MinIO (the_content filter - FRONTEND): URLs were NOT replaced. str_replace found no matches for '{$local_base_url}'."); // Optional
            }
        } else {
            // error_log("MinIO (the_content filter - FRONTEND): Conditions for replacement not met or URLs are the same. Local: '{$local_base_url}', CDN: '{$cdn_base_url}'."); // Optional
        }
        return $content;
    },
    99999,
); // Late priority

// 9️⃣ Admin notice for configuration check
add_action('plugins_loaded', function () {
    if (!defined('MINIO_ACCESS_KEY') || !defined('MINIO_SECRET_KEY') || !defined('MINIO_ENDPOINT') || !defined('MINIO_BUCKET') || !defined('MINIO_PUBLIC_URL')) {
        error_log('MinIO Media Integration: Missing required constants in wp-config.php: MINIO_ACCESS_KEY, MINIO_SECRET_KEY, MINIO_ENDPOINT, MINIO_BUCKET, MINIO_PUBLIC_URL');
        return;
    }

    // Now safe to initialize your MinIO client, hooks, etc.
});
