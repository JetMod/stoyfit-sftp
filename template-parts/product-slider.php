<?php

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<div <?php wc_product_class('', $product); ?>>

    <div class="category-list-product">
        <div class="category-product">
            <a href="<?php the_permalink(); ?>">
                <?php
                $product_id = get_the_ID();

                $product = wc_get_product($product_id);

                if (has_post_thumbnail($product_id)) {
                    $thumbnail_url = get_the_post_thumbnail_url($product_id, 'woocommerce_thumbnail');

                    echo '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr(get_the_title()) . '" />';
                } else {

                    echo '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr(get_the_title()) . '" />';
                }
                ?>
            </a>
            <div class="shop-category-title">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </div>

            <div class="category-row-btns">
                <div class="shop-category-price">
                    <?php woocommerce_template_loop_price(); ?>
                </div>
            </div>
        </div>
    </div>



</div>