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

    <div class="single-product-title text-center">
        <h1>
            <?php the_title(); ?>
        </h1>
    </div>



    <div class="tm-single-product-besh-head">

        <div class="tm-single-product-besh-image">
            <?php
            $main_image_id = get_post_thumbnail_id($product->get_id());
            $main_image_url = wp_get_attachment_image_src($main_image_id, 'woocommerce_single');
            list($main_image_width, $main_image_height) = getimagesize(get_attached_file($main_image_id));

            echo '<a href="' . wp_get_attachment_image_url($main_image_id, 'full') . '" data-pswp-width="' . $main_image_width . '" data-pswp-height="' . $main_image_height . '">';
            echo '<img src="' . esc_url($main_image_url[0]) . '" alt="' . esc_attr($product->get_name()) . '">';
            echo '</a>';
            ?>
        </div>

        <?php if (have_rows('product_besh_parametr')) : ?>
            <div class="tm-product-parametr-besh-container">
                <div class="tm-product-parametr-besh">
                    <?php while (have_rows('product_besh_parametr')) : the_row(); ?>

                        <div class="tm-product-besh-parametr-name"><?php the_sub_field('product_besh_parametr_text'); ?></div>

                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (have_rows('product_links')) : ?>
            <div class="tm-besh-product-links-container">
                <ul class="tm-besh-product-links">
                    <?php while (have_rows('product_links')) : the_row(); ?>
                        <li>
                            <a href="<?php the_sub_field('product_links_link'); ?>"><span class="<?php the_sub_field('product_links_icon'); ?>"></span><?php the_sub_field('product_links_title'); ?></a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endif; ?>

    </div>


    <div class="tm-single-product-besh-price-content">

        <div class="tm-single-product-besh-price-content-line">
            <div class="single-product-price-besh">
                Цена от: <?php woocommerce_template_single_price(); ?>
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

                <div class="single-product-addtocart single-product-addtocart-besh">
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

            </div>

        </div>

        <?php if (have_rows('product_color')) : ?>
            <div class="tm-besh-color uk-flex uk-flex-center text-center">
                <div>
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
                </div>
            </div>
        <?php endif; ?>

        <div class="single-product-besh-content-price">
            <?php the_field('product_price_content'); ?>
        </div>


    </div>


    <div class="tm-main-product-content tm-centered-content">
        <?php the_content(); ?>
    </div>


    <?php
    $product_pr_info = get_field('product_title_pr_info');
    if (!empty($product_pr_info)) : ?>
        <div class="tm-product-hara-teh"><?= $product_pr_info ?></div>
    <?php endif; ?>

    <div class="tm-centered-content">
        <div class="tm-padding">
            <?php
            $product_title_content = get_field('product_title_content');
            $product_title_content_info = get_field('product_title_content_info');
            ?>
            <?php if (!empty($product_title_content)) : ?>
                <div class="tm-h2 medium tm-margin-default-bottom"><?= $product_title_content ?></div>
            <?php endif; ?>
            <?php if (!empty($product_title_content_info)) : ?>
                <div class="tm-product-content-block tm-margin-xlarge-bottom"><?= $product_title_content_info ?></div>
            <?php endif; ?>


            <?php
            $whihc = array();
            if (have_rows('product_other_products')) :

                while (have_rows('product_other_products')) : the_row();

                    $related_product = get_sub_field('product_other_products_number');
                    if ($related_product) {
                        $whihc[] = $related_product->ID;
                    }

                endwhile;

            endif;

            if (!empty($whihc)) :
            ?>
                <div class="tm-product__related tm-margin-xlarge-bottom">
                    <h2 class="tm-h1 tm-margin-standart-bottom">
                        <?php the_field('product_other_product_title', 'option'); ?>
                    </h2>

                    <div class="tm-related-slider swiper">
                        <div class="tm-related-slider__grid swiper-wrapper">
                            <?php

                            $args = array(
                                'post_type' => 'product',
                                'post__in' => $whihc,
                                'post_status' => 'publish',
                                'posts_per_page' => 6,
                                'post__not_in' => array(get_the_ID()),
                                'orderby' => 'rand'
                            );


                            $wc_query = new WP_Query($args);
                            if ($wc_query->have_posts()) {
                                while ($wc_query->have_posts()) {
                                    $wc_query->the_post();

                            ?>
                                    <div class="tm-related-slider__item swiper-slide">
                                        <?php get_template_part('template-parts/product-slider'); ?>

                                    </div>
                            <?php

                                }
                            }
                            wp_reset_postdata();
                            ?>
                        </div>
                        <div class="swiper-button swiper-button-prev"></div>
                        <div class="swiper-button swiper-button-next"></div>
                    </div>

                </div>

            <?php endif; ?>



            <div class="tm-reviews-header tm-margin-xlarge-bottom">
                <div class="tm-grid uk-flex uk-flex-center">
                    <div class="tm-h1 bold"><?php the_field('product_reviews_title'); ?></div>
                    <div><?php the_field('product_reviews_subtitle'); ?></div>
                    <div><?php the_field('product_reviews_btn'); ?></div>
                </div>
            </div>
            <div class="tm-reviews-content">
                <?php the_field('product_reviews_content'); ?>
            </div>


        </div>
    </div>

    <div class="clear-product"></div>

</div>

<?php
// Блок «Похожие товары»
$args = array('limit' => 8, 'title' => 'Похожие товары');
get_template_part('template-parts/related-products', null, $args);
?>

<?php do_action('woocommerce_after_single_product'); ?>