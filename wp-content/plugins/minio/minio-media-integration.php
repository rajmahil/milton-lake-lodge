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
use Aws\Exception\AwsException;

// Helper function to get MinIO configuration (constants first, then env vars)
function get_minio_config($key)
{
    // Try constant first (local dev), then environment variable (production)
    $constant_name = strtoupper($key);
    if (defined($constant_name)) {
        return constant($constant_name);
    }
    return getenv($key);
}

// 1️⃣ MinIO client - Updated with fallback support
function my_minio_client()
{
    static $client;

    if (!$client) {
        // Try constants first (for local dev), then environment variables (for production)
        $minio_access_key = get_minio_config('MINIO_ACCESS_KEY');
        $minio_secret_key = get_minio_config('MINIO_SECRET_KEY');
        $minio_endpoint = get_minio_config('MINIO_ENDPOINT');

        // Check if required values are available
        if (!$minio_access_key || !$minio_secret_key || !$minio_endpoint) {
            error_log('MinIO Plugin Error: Required MinIO configuration is missing.');
            return false;
        }

        try {
            $client = new S3Client([
                'version' => 'latest',
                'region' => 'us-east-1',
                'endpoint' => $minio_endpoint,
                'use_path_style_endpoint' => true,
                'credentials' => [
                    'key' => $minio_access_key,
                    'secret' => $minio_secret_key,
                ],
            ]);
        } catch (Exception $e) {
            error_log('MinIO Plugin Error: Failed to create S3 client - ' . $e->getMessage());
            return false;
        }
    }

    return $client;
}

add_filter('wp_get_attachment_url', function ($url) {
    return str_replace('http://wp-test.local/wp-content/uploads', 'https://bucket-production-599e.up.railway.app/wpmedia', $url);
});

// 2️⃣ Upload hook — push file to MinIO
add_filter('wp_handle_upload', function ($upload) {
    if (isset($upload['error']) && $upload['error']) {
        return $upload;
    }

    $minio_bucket = get_minio_config('MINIO_BUCKET');
    $minio_public_url = get_minio_config('MINIO_PUBLIC_URL');

    if (!$minio_bucket || !$minio_public_url) {
        error_log('MinIO (wp_handle_upload): MINIO_BUCKET or MINIO_PUBLIC_URL not defined.');
        return $upload;
    }

    $bucket = $minio_bucket;
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

        $cdn_base = rtrim($minio_public_url, '/');
        $minio_url = $cdn_base . '/' . $key;
        $upload['url'] = $minio_url;

        error_log("MinIO: Upload successful. Local: {$upload['file']}, CDN: {$minio_url}");
    } catch (AwsException $e) {
        error_log('MinIO Upload Error (wp_handle_upload): ' . $e->getMessage() . ' for key: ' . $key);
    }
    return $upload;
});

// 3️⃣ Upload thumbnails and handle attachment metadata
add_filter(
    'wp_generate_attachment_metadata',
    function ($metadata, $attachment_id) {
        $minio_bucket = get_minio_config('MINIO_BUCKET');
        if (!$minio_bucket) {
            error_log('MinIO (wp_generate_attachment_metadata): MINIO_BUCKET not defined.');
            return $metadata;
        }

        $bucket = $minio_bucket;
        $s3 = my_minio_client();
        if (!$s3) {
            error_log("MinIO (wp_generate_attachment_metadata): S3 client not available for attachment ID {$attachment_id}.");
            return $metadata;
        }

        $upload_dir = wp_upload_dir();
        $base_dir = $upload_dir['basedir'];

        // Main file
        if (isset($metadata['file'])) {
            $main_file_path = $base_dir . '/' . $metadata['file'];
            $main_key = $metadata['file'];

            if (file_exists($main_file_path)) {
                try {
                    $s3->putObject([
                        'Bucket' => $bucket,
                        'Key' => $main_key,
                        'SourceFile' => $main_file_path,
                        'ACL' => 'public-read',
                    ]);
                    unlink($main_file_path);
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
        $minio_public_url = get_minio_config('MINIO_PUBLIC_URL');
        if (!$minio_public_url) {
            return $url;
        }

        $attached_file = get_post_meta($attachment_id, '_wp_attached_file', true);
        if ($attached_file) {
            $cdn_base = rtrim($minio_public_url, '/');
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
        $minio_public_url = get_minio_config('MINIO_PUBLIC_URL');
        if (!$minio_public_url) {
            return $image;
        }

        if (!$image || !is_array($image) || !isset($image[0])) {
            return $image;
        }

        $current_url = $image[0];
        $upload_dir_details = wp_upload_dir();
        $local_base_url = $upload_dir_details['baseurl'];

        if (strpos($current_url, rtrim($minio_public_url, '/')) === 0) {
            return $image; // Already a CDN URL
        }

        if ($local_base_url && strpos($current_url, $local_base_url) === 0) {
            $cdn_base = rtrim($minio_public_url, '/');
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
        $minio_public_url = get_minio_config('MINIO_PUBLIC_URL');
        if (!$minio_public_url) {
            return $response;
        }

        $cdn_base = rtrim($minio_public_url, '/');
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
        // Only process if the WordPress save was successful
        if (is_wp_error($saved)) {
            return $saved;
        }

        $minio_bucket = get_minio_config('MINIO_BUCKET');
        if (!$minio_bucket) {
            return $saved;
        }

        // Get MinIO client
        $s3 = my_minio_client();
        if (!$s3) {
            return $saved;
        }

        // Get the file path
        $file_path = is_array($saved) && isset($saved['path']) ? $saved['path'] : $filename;

        if (!file_exists($file_path)) {
            error_log("MinIO: Edited image file not found: {$file_path}");
            return $saved;
        }

        // Calculate the MinIO key
        $upload_dir = wp_upload_dir();
        $base_dir = $upload_dir['basedir'];

        if (strpos($file_path, $base_dir) === 0) {
            $relative_path = ltrim(str_replace($base_dir, '', $file_path), '/');

            try {
                // Upload to MinIO
                $s3->putObject([
                    'Bucket' => $minio_bucket,
                    'Key' => $relative_path,
                    'SourceFile' => $file_path,
                    'ACL' => 'public-read',
                ]);

                error_log("MinIO: Uploaded edited image - {$relative_path}");
            } catch (Exception $e) {
                error_log('MinIO Upload Error (edited image): ' . $e->getMessage());
            }
        }

        return $saved;
    },
    10,
    4,
);

// Updated post meta action
add_action(
    'updated_post_meta',
    function ($meta_id, $object_id, $meta_key, $_meta_value) {
        if ($meta_key === '_wp_attached_file') {
            $attachment_id = $object_id;
            $minio_bucket = get_minio_config('MINIO_BUCKET');

            if (!$minio_bucket) {
                return;
            }

            error_log('✅ _wp_attached_file updated — regenerating attachment metadata...');

            // Generate new attachment metadata
            $metadata = wp_generate_attachment_metadata($attachment_id, wp_get_original_image_path($attachment_id));

            if ($metadata) {
                update_post_meta($attachment_id, '_wp_attachment_metadata', $metadata);
                error_log('✅ New attachment metadata generated.');

                // Now upload main file + thumbnails to MinIO:
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
                            'Bucket' => $minio_bucket,
                            'Key' => $key,
                            'SourceFile' => $main_file_path,
                            'ACL' => 'public-read',
                        ]);
                        unlink($main_file_path);

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
                                'Bucket' => $minio_bucket,
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

// 8️⃣ Filter content on the frontend
add_filter(
    'the_content',
    function ($content) {
        if (is_admin()) {
            return $content;
        }

        $minio_public_url = get_minio_config('MINIO_PUBLIC_URL');
        if (!$minio_public_url) {
            return $content;
        }

        $upload_dir_details = wp_upload_dir();
        $local_base_url = $upload_dir_details['baseurl'];
        $cdn_base_url = rtrim($minio_public_url, '/');

        if ($local_base_url && $cdn_base_url && $local_base_url !== $cdn_base_url) {
            $content = str_replace($local_base_url, $cdn_base_url, $content);
        }

        return $content;
    },
    99999,
);

// Add this to ensure admin/media library shows CDN URLs
add_filter(
    'wp_get_attachment_image_attributes',
    function ($attr, $attachment, $size) {
        $minio_public_url = get_minio_config('MINIO_PUBLIC_URL');
        if (!$minio_public_url) {
            return $attr;
        }

        if (isset($attr['src'])) {
            $upload_dir = wp_upload_dir();
            $local_base_url = $upload_dir['baseurl'];
            $cdn_base = rtrim($minio_public_url, '/');

            if (strpos($attr['src'], $local_base_url) === 0) {
                $relative_path = str_replace($local_base_url, '', $attr['src']);
                $attr['src'] = $cdn_base . $relative_path;
            }
        }

        return $attr;
    },
    10,
    3,
);

// Enhanced debug function
add_action('admin_init', function () {
    if (isset($_GET['debug_minio_urls']) && current_user_can('manage_options')) {
        header('Content-Type: text/plain');

        echo "=== MinIO URL Debug ===\n\n";

        // Check both constants and environment variables
        echo "Configuration Sources:\n";
        echo 'MINIO_PUBLIC_URL (constant): ' . (defined('MINIO_PUBLIC_URL') ? MINIO_PUBLIC_URL : 'NOT DEFINED') . "\n";
        echo 'MINIO_PUBLIC_URL (env): ' . (getenv('MINIO_PUBLIC_URL') ?: 'NOT SET') . "\n";
        echo 'MINIO_PUBLIC_URL (final): ' . (get_minio_config('MINIO_PUBLIC_URL') ?: 'NOT AVAILABLE') . "\n\n";

        echo 'MINIO_BUCKET (constant): ' . (defined('MINIO_BUCKET') ? MINIO_BUCKET : 'NOT DEFINED') . "\n";
        echo 'MINIO_BUCKET (env): ' . (getenv('MINIO_BUCKET') ?: 'NOT SET') . "\n";
        echo 'MINIO_BUCKET (final): ' . (get_minio_config('MINIO_BUCKET') ?: 'NOT AVAILABLE') . "\n\n";

        // Test MinIO client
        echo "MinIO Client Test:\n";
        $s3 = my_minio_client();
        echo 'Client created: ' . ($s3 ? 'YES' : 'NO') . "\n\n";

        // Check upload directory
        $upload_dir = wp_upload_dir();
        echo "Upload Directory:\n";
        echo 'baseurl: ' . $upload_dir['baseurl'] . "\n";
        echo 'basedir: ' . $upload_dir['basedir'] . "\n\n";

        // Test with a recent attachment
        $attachments = get_posts([
            'post_type' => 'attachment',
            'posts_per_page' => 1,
            'post_status' => 'inherit',
        ]);

        if ($attachments) {
            $attachment = $attachments[0];
            echo 'Testing with attachment ID: ' . $attachment->ID . "\n";

            $original_url = get_post_meta($attachment->ID, '_wp_attached_file', true);
            echo 'Attached file meta: ' . $original_url . "\n";

            $wp_url = wp_get_attachment_url($attachment->ID);
            echo 'wp_get_attachment_url result: ' . $wp_url . "\n";

            $minio_public_url = get_minio_config('MINIO_PUBLIC_URL');
            if ($minio_public_url && $original_url) {
                $expected_cdn_url = rtrim($minio_public_url, '/') . '/' . ltrim($original_url, '/');
                echo 'Expected CDN URL: ' . $expected_cdn_url . "\n";
                echo 'URLs match: ' . ($wp_url === $expected_cdn_url ? 'YES' : 'NO') . "\n";
            }
        }

        exit();
    }
});
