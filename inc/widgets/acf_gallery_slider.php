<?php

/**
 * Block Name: Gallery slider
 *
 */

$acf_block_banner_css = get_field('acf_block_banner_css');

$images = get_field('gallery_slider');
?>
<div class="tm-gallery-slider-wrapper<?php if( !empty( $acf_block_banner_css ) ): ?> <?= $acf_block_banner_css ?><?php endif; ?>">

    <div class="tm-gallery-slider swiper">

        <div class="swiper-wrapper">
            <?php foreach ($images as $image) : ?>
                <div class="swiper-slide">
                    <div class="tm-gallery-slider__image">
                        <a href="<?php echo esc_url($image['url']); ?>" data-pswp-width="<?php echo esc_attr($image['width']); ?>" data-pswp-height="<?php echo esc_attr($image['height']); ?>">
                            <img src="<?php echo esc_url($image['sizes']['custom_thumb']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="tm-gallery-slider_prev swiper-button swiper-button-prev"></div>
        <div class="tm-gallery-slider_next swiper-button swiper-button-next"></div>
    </div>

    <div class="tm-gallery-slider-thumbs-wrap">

        <div class="tm-gallery-slider-thumbs swiper">
            <div class="swiper-wrapper">
                <?php foreach ($images as $thumb) : ?>
                    <div class="swiper-slide">
                        <div class="tm-gallery-slider-thumbs__image">
                            <img src="<?php echo esc_url($thumb['sizes']['custom_prev']); ?>" alt="<?php echo esc_attr($thumb['alt']); ?>" />
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</div>