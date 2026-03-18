<?php
/**
 * Блок товаров в стиле tm-application-goods — данные из WooCommerce
 *
 * Шорткод [application_products] — замена Widgetkit с ручным HTML.
 * Товары и цены подтягиваются из каталога WooCommerce.
 *
 * Параметры: ids, category, tag, limit, title, link, link_text, orderby, order
 */

defined('ABSPATH') || exit;

$defaults = array(
    'title'     => '',
    'link'      => '',
    'link_text' => 'Смотреть все',
    'ids'       => '',
    'category'  => '',
    'tag'       => '',
    'limit'     => 10,
    'orderby'   => 'menu_order',
    'order'     => 'ASC',
    'class'     => '',
);

$atts = wp_parse_args($args ?? array(), $defaults);

$query_args = array(
    'post_type'           => 'product',
    'posts_per_page'      => max(1, (int) $atts['limit']),
    'post_status'         => 'publish',
    'orderby'             => sanitize_key($atts['orderby']),
    'order'               => in_array(strtoupper($atts['order']), array('ASC', 'DESC')) ? strtoupper($atts['order']) : 'ASC',
    'ignore_sticky_posts' => true,
);

if (!empty($atts['ids'])) {
    $ids = array_filter(array_map('absint', array_map('trim', explode(',', $atts['ids']))));
    if (empty($ids)) {
        wp_reset_postdata();
        return;
    }
    $query_args['post__in'] = $ids;
    $query_args['orderby']  = 'post__in';
} elseif (!empty($atts['category'])) {
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => array_map('sanitize_title', explode(',', $atts['category'])),
            'operator' => 'IN',
        ),
    );
} elseif (!empty($atts['tag'])) {
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => 'product_tag',
            'field'    => 'slug',
            'terms'    => array_map('sanitize_title', explode(',', $atts['tag'])),
            'operator' => 'IN',
        ),
    );
}

$products = new WP_Query($query_args);

if (!$products->have_posts()) {
    wp_reset_postdata();
    return;
}

$uid = 'tm-application-' . uniqid();
?>

<section class="tm-application-products<?php echo !empty($atts['class']) ? ' ' . esc_attr($atts['class']) : ''; ?>" aria-label="<?php echo esc_attr($atts['title']); ?>">

    <?php if (!empty($atts['title'])) : ?>
    <div class="tm-application-products__header tm-margin-xlarge-bottom">
        <h2 class="tm-application-products__title tm-h1 bold"><?php echo esc_html($atts['title']); ?></h2>
        <?php if (!empty($atts['link'])) : ?>
        <a href="<?php echo esc_url($atts['link']); ?>" class="tm-application-products__all"><?php echo esc_html($atts['link_text']); ?></a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="tm-application-slider tm-projects-slide tm-margin-xlarge-bottom swiper" id="<?php echo esc_attr($uid); ?>">
        <div class="swiper-wrapper">

            <?php
            while ($products->have_posts()) :
                $products->the_post();
                $product = wc_get_product(get_the_ID());

                if (!$product || !$product->is_visible()) {
                    continue;
                }

                $product_id    = $product->get_id();
                $permalink     = get_permalink($product_id);
                $title         = $product->get_name();
                $thumbnail_url = has_post_thumbnail($product_id)
                    ? get_the_post_thumbnail_url($product_id, 'custom_thumb')
                    : wc_placeholder_img_src('custom_thumb');

                // Кнопка: «В корзину» для простых товаров (AJAX), «Купить» для вариативных
                if ($product->is_purchasable() && $product->is_in_stock()) {
                    if ($product->is_type('simple') && $product->supports('ajax_add_to_cart')) {
                        $btn_classes = 'button add_to_cart_button ajax_add_to_cart';
                        $btn_href    = $product->add_to_cart_url();
                        $btn_text    = 'В корзину';
                        $btn_attrs   = ' data-quantity="1" data-product_id="' . esc_attr($product_id) . '" data-product_sku="' . esc_attr($product->get_sku()) . '" rel="nofollow"';
                    } else {
                        $btn_classes = '';
                        $btn_href    = $permalink;
                        $btn_text    = 'Купить';
                        $btn_attrs   = '';
                    }
                } else {
                    $btn_classes = '';
                    $btn_href    = $permalink;
                    $btn_text    = 'Подробнее';
                    $btn_attrs   = '';
                }
            ?>

            <div class="swiper-slide">
                <div class="tm-application-goods">
                    <div class="tm-application-goods-img">
                        <a href="<?php echo esc_url($permalink); ?>">
                            <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($title); ?>" class="attachment-custom_thumb size-custom_thumb" loading="lazy" decoding="async">
                        </a>
                    </div>
                    <div class="tm-application-goods-title">
                        <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
                    </div>
                    <div class="tm-application-goods-price">
                        <?php woocommerce_template_loop_price(); ?>
                    </div>
                    <div class="tm-content-btn">
                        <a href="<?php echo esc_url($btn_href); ?>" class="<?php echo esc_attr($btn_classes); ?>"<?php echo $btn_attrs; ?>><?php echo esc_html($btn_text); ?></a>
                    </div>
                </div>
            </div>

            <?php endwhile; ?>

        </div>
        <div class="tm-application-slider__prev swiper-button swiper-button-prev" data-uid="<?php echo esc_attr($uid); ?>"></div>
        <div class="tm-application-slider__next swiper-button swiper-button-next" data-uid="<?php echo esc_attr($uid); ?>"></div>
    </div>

</section>

<?php wp_reset_postdata(); ?>
