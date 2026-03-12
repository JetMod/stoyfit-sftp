<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

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
                <div class="tm-loop-atc">
                    <?php
                    if ($product->is_purchasable() && $product->is_in_stock()) {
                        $btn_classes = 'tm-loop-atc__btn button add_to_cart_button';
                        if ($product->supports('ajax_add_to_cart')) {
                            $btn_classes .= ' ajax_add_to_cart';
                        }
                        printf(
                            '<a href="%s" data-quantity="1" data-product_id="%s" data-product_sku="%s" aria-label="%s" rel="nofollow" class="%s">%s</a>',
                            esc_url($product->add_to_cart_url()),
                            esc_attr($product->get_id()),
                            esc_attr($product->get_sku()),
                            esc_attr($product->add_to_cart_description()),
                            esc_attr($btn_classes),
                            esc_html($product->add_to_cart_text())
                        );
                    } else {
                        printf(
                            '<a href="%s" class="tm-loop-atc__btn tm-loop-atc__btn--link">Подробнее</a>',
                            esc_url(get_the_permalink())
                        );
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>



</div>