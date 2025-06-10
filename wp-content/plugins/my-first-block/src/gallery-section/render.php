<?php
/**
 * Gallery Section Block - PHP Render Version with Alpine.js Dialog
 * Now with image counter functionality and responsive navigation positioning
 */

$heading = $attributes['heading'] ?? '';
$images = $attributes['images'] ?? [];

$groups = array_chunk($images, 10);
$totalImages = count($images);
?>

<section class="plugin-custom-block not-prose section-padding w-full static-background">
    <div 
        class="max-w-container mx-auto flex flex-col gap-16"
        x-data="{
            imageGalleryOpened: false,
            imageGalleryActiveUrl: null,
            imageGalleryImageIndex: 0,
            totalImages: <?php echo $totalImages; ?>,
            imageGalleryOpen(event) {
                this.imageGalleryImageIndex = parseInt(event.target.dataset.index);
                this.imageGalleryActiveUrl = event.target.dataset.fullUrl;
                this.imageGalleryOpened = true;
                // Prevent background scrolling
                document.body.style.overflow = 'hidden';
                document.documentElement.style.overflow = 'hidden';
            },
            imageGalleryClose() {
                this.imageGalleryOpened = false;
                // Restore background scrolling
                document.body.style.overflow = '';
                document.documentElement.style.overflow = '';
                setTimeout(() => {
                    this.imageGalleryActiveUrl = null;
                }, 200); 
            },
            imageGalleryNext(){
                this.imageGalleryImageIndex = (this.imageGalleryImageIndex >= this.totalImages - 1) ? 0 : (this.imageGalleryImageIndex + 1);
                this.updateActiveImage();
            },
            imageGalleryPrev() {
                this.imageGalleryImageIndex = (this.imageGalleryImageIndex <= 0) ? this.totalImages - 1 : (this.imageGalleryImageIndex - 1);
                this.updateActiveImage();
            },
            updateActiveImage() {
                const images = this.$refs.gallery.querySelectorAll('img[data-index]');
                this.imageGalleryActiveUrl = images[this.imageGalleryImageIndex].dataset.fullUrl;
            }
        }" 
        @keyup.right.window="imageGalleryNext()" 
        @keyup.left.window="imageGalleryPrev()"
    >
        <?php if (!empty($heading)) : ?>
            <h2 class="!my-0 heading-two font-semibold text-center">
                <?php echo esc_html($heading); ?>
            </h2>
        <?php endif; ?>

        <?php if (!empty($images)) : ?>
            <div class="flex flex-col gap-2 sm:gap-4" x-ref="gallery">
                <?php 
                $globalIndex = 0;
                foreach ($groups as $groupIndex => $groupImages) : ?>
                    <div class="flex flex-col gap-2 sm:gap-4">
                        <div class="flex flex-col md:flex-row gap-2 sm:gap-4">
                            <?php if (!empty($groupImages[0])) : 
                                $image = $groupImages[0];
                                $image_id = $image['id'] ?? 0;
                                $image_alt = $image['alt'] ?? '';
                            ?>
                                <div class="flex-1 aspect-[3/2] relative overflow-hidden rounded-xl">
                                    <?php echo wp_get_attachment_image($image_id, 'large', false, [
                                        'class' => 'w-full h-full object-cover transition-transform duration-500 hover:scale-110 cursor-zoom-in select-none',
                                        'loading' => $globalIndex < 5 ? 'eager' : 'lazy',
                                        'fetchpriority' => $globalIndex < 5 ? 'high' : 'auto',
                                        'decoding' => 'async',
                                        'alt' => $image_alt,
                                        'data-index' => $globalIndex,
                                        'data-full-url' => esc_url($image['url']),
                                        '@click' => 'imageGalleryOpen'
                                    ]); ?>
                                </div>
                                <?php $globalIndex++; ?>
                            <?php endif; ?>

                            <div class="flex-1 grid grid-cols-2 gap-2 sm:gap-4">
                                <?php for ($i = 1; $i <= 4; $i++) :
                                    if (!empty($groupImages[$i])) : 
                                        $image = $groupImages[$i];
                                        $image_id = $image['id'] ?? 0;
                                        $image_alt = $image['alt'] ?? '';
                                    ?>
                                        <div class="aspect-[3/2] relative overflow-hidden rounded-xl">
                                            <?php echo wp_get_attachment_image($image_id, 'large', false, [
                                                'class' => 'w-full h-full object-cover transition-transform duration-500 hover:scale-110 cursor-zoom-in select-none',
                                                'loading' => $globalIndex < 5 ? 'eager' : 'lazy',
                                                'fetchpriority' => $globalIndex < 5 ? 'high' : 'auto',
                                                'decoding' => 'async',
                                                'alt' => $image_alt,
                                                'data-index' => $globalIndex,
                                                'data-full-url' => esc_url($image['url']),
                                                '@click' => 'imageGalleryOpen'
                                            ]); ?>
                                        </div>
                                        <?php $globalIndex++; ?>
                                    <?php endif;
                                endfor; ?>
          </div>
        </div>

                        <?php if (count($groupImages) > 5) : ?>
                            <div class="flex flex-col md:flex-row gap-2 sm:gap-4">
                                <div class="flex-1 grid grid-cols-2 gap-2 sm:gap-4">
                                    <?php for ($i = 5; $i <= 8; $i++) :
                                        if (!empty($groupImages[$i])) : 
                                            $image = $groupImages[$i];
                                            $image_id = $image['id'] ?? 0;
                                            $image_alt = $image['alt'] ?? '';
                                        ?>
                                            <div class="aspect-[3/2] relative overflow-hidden rounded-xl">
                                                <?php echo wp_get_attachment_image($image_id, 'large', false, [
                                                    'class' => 'w-full h-full object-cover transition-transform duration-500 hover:scale-110 cursor-zoom-in select-none',
                                                    'loading' => $globalIndex < 5 ? 'eager' : 'lazy',
                                                    'fetchpriority' => $globalIndex < 5 ? 'high' : 'auto',
                                                    'decoding' => 'async',
                                                    'alt' => $image_alt,
                                                    'data-index' => $globalIndex,
                                                    'data-full-url' => esc_url($image['url']),
                                                    '@click' => 'imageGalleryOpen'
                                                ]); ?>
                                            </div>
                                            <?php $globalIndex++; ?>
                                        <?php endif;
                                    endfor; ?>
          </div>

                                <?php if (!empty($groupImages[9])) : 
                                    $image = $groupImages[9];
                                    $image_id = $image['id'] ?? 0;
                                    $image_alt = $image['alt'] ?? '';
                                ?>
                                    <div class="flex-1 aspect-[3/2] relative overflow-hidden rounded-xl">
                                        <?php echo wp_get_attachment_image($image_id, 'large', false, [
                                            'class' => 'w-full h-full object-cover transition-transform duration-500 hover:scale-110 cursor-zoom-in select-none',
                                            'loading' => $globalIndex < 5 ? 'eager' : 'lazy',
                                            'fetchpriority' => $globalIndex < 5 ? 'high' : 'auto',
                                            'decoding' => 'async',
                                            'alt' => $image_alt,
                                            'data-index' => $globalIndex,
                                            'data-full-url' => esc_url($image['url']),
                                            '@click' => 'imageGalleryOpen'
                                        ]); ?>
                                    </div>
                                    <?php $globalIndex++; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <template x-teleport="body">
            <div 
                x-show="imageGalleryOpened" 
                x-transition:enter="transition ease-in-out duration-300" 
                x-transition:enter-start="opacity-0" 
                x-transition:leave="transition ease-in-in duration-300" 
                x-transition:leave-end="opacity-0" 
                @click="imageGalleryClose" 
                @keydown.window.escape="imageGalleryClose" 
                @touchmove.prevent
                @wheel.prevent
                @scroll.prevent
                x-trap.inert.noscroll="imageGalleryOpened"
                class="fixed inset-0 !z-[1000] w-screen h-screen bg-black/90 select-none cursor-zoom-out overflow-hidden touch-none" 
                x-cloak
                style="overscroll-behavior: none;"
            >
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="relative w-full h-full max-w-[90vw] max-h-[90vh] pointer-events-auto"> 
                    <div class="relative w-full h-full max-w-[90vw] max-h-[90vh] pointer-events-auto">
                        <div 
                            @click="$event.stopPropagation(); imageGalleryPrev()" 
                            class="absolute !z-[1001] left-4 top-1/2 transform -translate-y-1/2 hidden xl:flex items-center justify-center text-white rounded-full cursor-pointer bg-white/10 w-14 h-14 hover:bg-white/20 transition-colors duration-200"
                        >
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </div>
                        
                        <div class="relative flex items-center justify-center w-full h-full">
                            <img 
                                x-show="imageGalleryOpened" 
                                x-transition:enter="transition ease-in-out duration-300" 
                                x-transition:enter-start="opacity-0 transform scale-50" 
                                x-transition:leave="transition ease-in-in duration-300" 
                                x-transition:leave-end="opacity-0 transform scale-50" 
                                class="object-contain object-center w-full h-full max-w-[90vw] max-h-[85vh] select-none cursor-zoom-out" 
                                :src="imageGalleryActiveUrl" 
                                alt=""
                            >
                            
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-black/90 text-white py-1 px-4 rounded-full text-sm font-medium xl:block hidden">
                                <span x-text="imageGalleryImageIndex + 1"></span>
                                <span>/</span>
                                <span x-text="totalImages"></span>
                            </div>

                            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex items-center gap-4 xl:hidden">
                                <div 
                                    @click="$event.stopPropagation(); imageGalleryPrev()" 
                                    class="flex items-center justify-center text-white rounded-full cursor-pointer bg-white/10 w-10 sm:w-14 h-10 m:h-14  hover:bg-white/20 transition-colors duration-200"
                                >
                                    <svg class="w-5 sm:w-6 h-5 sm:h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                    </svg>
                                </div>
                                
                                <div class="bg-black/90 text-white flex items-center py-2 px-4 rounded-full text-sm font-medium">
                                    <span x-text="imageGalleryImageIndex + 1"></span>
                                    <span>/</span>
                                    <span x-text="totalImages"></span>
                                </div>
                                
                                <div 
                                    @click="$event.stopPropagation(); imageGalleryNext()" 
                                    class="flex items-center justify-center text-white rounded-full cursor-pointer bg-white/10 w-10 sm:w-14 h-10 sm:h-14 hover:bg-white/20 transition-colors duration-200"
                                >
                                    <svg class="w-5 sm:w-6 h-5 sm:h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div 
                            @click="$event.stopPropagation(); imageGalleryNext()" 
                            class="absolute !z-[1001] right-4 top-1/2 transform -translate-y-1/2 hidden xl:flex items-center justify-center text-white rounded-full cursor-pointer bg-white/10 w-14 h-14 hover:bg-white/20 transition-colors duration-200"
                        >
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>

                        <div 
                            @click="imageGalleryClose" 
                            class="absolute top-4 right-4 flex items-center justify-center text-white bg-white/10 w-12 h-12 rounded-full cursor-pointer hover:bg-white/20 transition-colors duration-200"
                        >
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</section>
