<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}

?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

    <div class="single-product-title">
        <h1>
            <?php the_title(); ?>
        </h1>
    </div>

    <div class="top-block-single-product-standart">

        <div class="tm-single-product-standart-image">
            <div class="tm-product__images-container">

                <div class="tm-product__images-main">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <?php
                            // Get the product gallery images
                            $attachment_ids = $product->get_gallery_image_ids();
                            $main_image_id = get_post_thumbnail_id($product->get_id());
                            $main_image_url = wp_get_attachment_image_src($main_image_id, 'woocommerce_single');
                            list($main_image_width, $main_image_height) = getimagesize(get_attached_file($main_image_id));

                            // Display the main product image
                            echo '<div class="swiper-slide"><div class="slider__image">';
                            echo '<a href="' . wp_get_attachment_image_url($main_image_id, 'full') . '" data-pswp-width="' . $main_image_width . '" data-pswp-height="' . $main_image_height . '">';
                            echo '<img src="' . esc_url($main_image_url[0]) . '" alt="' . esc_attr($product->get_name()) . '">';
                            echo '</a>';
                            echo '</div></div>';

                            // Display the product gallery images
                            if ($attachment_ids) {
                                foreach ($attachment_ids as $attachment_id) {
                                    $image_url = wp_get_attachment_image_url($attachment_id, 'full'); // Get the URL of the gallery image
                                    list($image_width, $image_height) = getimagesize(get_attached_file($attachment_id));
                                    echo '<div class="swiper-slide"><div class="slider__image">';
                                    echo '<a href="' . $image_url . '" data-pswp-width="' . $image_width . '" data-pswp-height="' . $image_height . '">';
                                    echo wp_get_attachment_image($attachment_id, 'woocommerce_single', false, array('class' => 'swiper-slide-image'));
                                    echo '</a>';
                                    echo '</div></div>';
                                }
                            }
                            ?>
                        </div>

                    </div>
                    <?php
                    $attachment_ids = $product->get_gallery_image_ids();
                    $main_image_count = count($attachment_ids) + 1;
                    if ($main_image_count > 1) :
                    ?>
                        <div class="tm-product__images-left swiper-button swiper-button-prev"></div>
                        <div class="tm-product__images-right swiper-button swiper-button-next"></div>
                    <?php endif; ?>
                </div>

                <?php
                $attachment_ids = $product->get_gallery_image_ids();
                $main_image_count = count($attachment_ids) + 1;
                if ($main_image_count > 1) :
                ?>
                    <div class="tm-product__images-column">

                        <div class="tm-product__images-thumbs">
                            <div class="swiper-container">

                                <div class="swiper-wrapper">
                                    <?php
                                    // Display the product gallery thumbnails
                                    echo '<div class="swiper-slide"><div class="slider__image"><div>';
                                    $gallery_image_url = wp_get_attachment_image_url(get_post_thumbnail_id(), 'gallery-product');
                                    echo '<img src="' . esc_url($gallery_image_url) . '" alt="' . esc_attr($product->get_name()) . '">';
                                    echo '</div></div></div>';

                                    if ($attachment_ids) {
                                        foreach ($attachment_ids as $attachment_id) {
                                            echo '<div class="swiper-slide"><div class="slider__image"><div>';
                                            echo wp_get_attachment_image($attachment_id, 'gallery-product', false, array('class' => 'swiper-slide-thumbnail'));
                                            echo '</div></div></div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endif; ?>

            </div>
        </div>


        <div class="tm-single-product-standart-image-right-content">

            <div class="tm-single-product-standart-image-right-content-inner">
                <?php if (have_rows('product_parametr')) : ?>
                    <ul class="tm-product-parametr">
                        <?php while (have_rows('product_parametr')) : the_row(); ?>
                            <li>
                                <div class="tm-product-parametr-name"><?php the_sub_field('product_parametr_name'); ?>:</div>
                                <div class="tm-product-parametr-value"><?php the_sub_field('product_parametr_value'); ?></div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>

                <div class="single-product-price">
                    Цена: <?php woocommerce_template_single_price(); ?>
                </div>

                <?php if (have_rows('product_color')) : ?>
                    <div class="tm-product-title-color"><a href="https://xn--h1abohegeo.xn--p1ai/dobavte-krasok-i-uzor/"><?php the_field('product_title_color'); ?></a></div>
                    <ul class="tm-product-color-list">
                        <?php while (have_rows('product_color')) : the_row();

                        ?>
                            <li>
                                <a href="<?php the_sub_field('product_color_link'); ?>">
                                    <div class="<?php the_sub_field('product_color_color'); ?>"></div>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="product-content-btns">
                <?php
                /**
                 * Hook: woocommerce_single_product_summary.
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked WC_Structured_Data::generate_product_data() - 60
                 */
                //do_action( 'woocommerce_single_product_summary' );

                ?>

                <div class="single-product-addtocart">
                    <div class="single-product-addtocart-btn">
                        <?php $get_title_product = get_the_title(); ?>
                        <?php $get_title_product = str_replace(['&#171;', '&#187;'], ['', ''], $get_title_product); ?>
                        <?php $get_title_product_url = str_replace(' ', '%20', $get_title_product); ?>
                        <div class="tm-single-btns">
                            <div class="tm-content-btn tm-btn-order">
                                <a href="https://max.ru/u/f9LHodD0cOLeuast85D27hxdSjXish-v79No4X8Mc2H7XQFKDumSvC7nhUY" target="_blank" rel="noopener">заказать</a>
                            </div>
                            <div class="tm-single-atc">
                                <?php woocommerce_template_single_add_to_cart(); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (have_rows('product_links')) : ?>
                    <ul class="tm-product-links">
                        <?php while (have_rows('product_links')) : the_row();

                        ?>
                            <li>
                                <a href="<?php the_sub_field('product_links_link'); ?>"><span class="<?php the_sub_field('product_links_icon'); ?>"></span><?php the_sub_field('product_links_title'); ?></a>
                            </li>
                        <?php endwhile; ?>
                         <?php echo do_shortcode('[widgetkit id="56"]'); ?>
                    </ul>
                <?php endif; ?>
            </div>

           
        </div>
    </div>

    <div class="tm-main-product-content">

        <div class="single-product-content">
            <?php the_content(); ?>
        </div>

        <?php
        $product_title_hara = get_field('product_title_hara');
        if (!empty($product_title_hara)) : ?>
            <div class="tm-h2 medium tm-margin-default-bottom"><?= $product_title_hara ?></div>
        <?php endif; ?>
        <div class="tm-product-hara-line">
            <?php if (have_rows('product_hara')) : ?>
                <ul class="product_hara_fileds">
                    <?php while (have_rows('product_hara')) : the_row();

                    ?>
                        <li>
                            <div><?php the_sub_field('product_hara_name'); ?>:</div>
                            <div><?php the_sub_field('product_hara_value'); ?></div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>

            <?php if (have_rows('product_hara2')) : ?>
                <ul class="product_hara_fileds">
                    <?php while (have_rows('product_hara2')) : the_row();

                    ?>
                        <li>
                            <div><?php the_sub_field('product_hara_name'); ?>:</div>
                            <div><?php the_sub_field('product_hara_value'); ?></div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>
        </div>


        <?php
        $product_teh_info = get_field('product_title_hara_teh_info');
        if (!empty($product_teh_info)) : ?>
            <div class="tm-h2 medium tm-margin-default-bottom"><?php the_field('product_title_hara_teh'); ?></div>
            <div class="tm-product-hara-teh"><?= $product_teh_info ?></div>
        <?php endif; ?>

        <?php
        $product_oblast_primeneniya = get_field('product_oblast_primeneniya');
        if (!empty($product_oblast_primeneniya)) : ?>
            <div class="tm-h2 medium tm-margin-default-bottom"><?= $product_oblast_primeneniya ?></div>

            <div class="tm-product-oblast-columns">
                <div class="tm-product-oblast-column1">
                    <?php the_field('product_oblast_primeneniya_widg'); ?>
                </div>
                <div class="tm-product-oblast-column2">
                    <?php the_field('product_oblast_primeneniya_slide'); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php
        $product_pr_info = get_field('product_pr_with_us');
        if (!empty($product_pr_info)) : ?>
            <div class="tm-h2 medium tm-margin-default-bottom"><?php the_field('product_title_pr_with_us'); ?></div>
            <div class="tm-product-hara-teh"><?= $product_pr_info ?></div>
        <?php endif; ?>

        <?php
        $product_other_products = get_field('product_other_products');
        if (!empty($product_other_products)) : ?>
            <div class="tm-h2 medium tm-margin-default-bottom"><?php the_field('product_other_products_title'); ?></div>
            <div class="tm-product-hara-teh"><?= $product_other_products ?></div>
        <?php endif; ?>

    </div>


    <div class="clear-product"></div>

</div>

<?php
// Блок «Похожие товары»
$args = array('limit' => 8, 'title' => 'Похожие товары');
get_template_part('template-parts/related-products', null, $args);

// Блок «Рекомендуем посмотреть»
$args_featured = array('title' => 'Рекомендуем посмотреть', 'tag' => 'recommended', 'limit' => 8);
get_template_part('template-parts/featured-products', null, $args_featured);
?>

<?php do_action('woocommerce_after_single_product'); ?>