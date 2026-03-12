<?php

/**
 * Block Name: Grid gallery
 *
 */

$acf_block_grid_css = get_field('acf_block_grid_css');

$images = get_field('grid_gallery');
?>
<div class="tm-grid-galllery<?php if (!empty($acf_block_grid_css)) : ?> <?= $acf_block_grid_css ?><?php endif; ?>">

    <div class="tm-grid">
        <?php foreach ($images as $image) : ?>
            <div class="tm-grid-width-1-3">
                <div class="tm-grid-galllery__image">
                    <a href="<?php echo esc_url($image['url']); ?>" data-pswp-width="<?php echo esc_attr($image['width']); ?>" data-pswp-height="<?php echo esc_attr($image['height']); ?>">
                        <p class="gallery_image-desc"><?php echo esc_attr($image['alt']); ?></p>
                        <img src="<?php echo esc_url($image['sizes']['custom_thumb']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>