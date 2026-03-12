<?php
/**
 * Шаблон блока «Рекомендуем посмотреть» — Swiper-слайдер товаров
 *
 * Используется через шорткод [featured_products_block] или прямым include.
 * Параметры передаются в $args (из shortcode_atts).
 *
 * @var array $args  Параметры слайдера
 */

defined('ABSPATH') || exit;

// Дефолтные параметры
$defaults = array(
    'title'    => 'Рекомендуем посмотреть',
    'link'     => '',
    'link_text'=> 'Смотреть все',
    'tag'      => 'recommended',
    'category' => '',
    'ids'      => '',
    'acf'      => '',
    'limit'    => 10,
    'orderby'  => 'rand',
    'order'    => 'DESC',
    'class'    => '',
);

$atts = wp_parse_args($args ?? array(), $defaults);

// ── Формируем WP_Query ────────────────────────────────────────────────────

$query_args = array(
    'post_type'           => 'product',
    'posts_per_page'      => max(1, (int) $atts['limit']),
    'post_status'         => 'publish',
    'orderby'             => sanitize_key($atts['orderby']),
    'order'               => in_array(strtoupper($atts['order']), array('ASC', 'DESC')) ? strtoupper($atts['order']) : 'DESC',
    'ignore_sticky_posts' => true,
);

// Конкретные ID
if (!empty($atts['ids'])) {
    $ids = array_map('absint', explode(',', $atts['ids']));
    $query_args['post__in'] = $ids;
    $query_args['orderby']  = 'post__in'; // сохраняем порядок из атрибута ids

// ACF-флаг «Рекомендуемый»
} elseif (!empty($atts['acf'])) {
    $query_args['meta_query'] = array(
        array(
            'key'     => sanitize_key($atts['acf']),
            'value'   => '1',
            'compare' => '=',
        ),
    );

// Категория товара
} elseif (!empty($atts['category'])) {
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => array_map('sanitize_title', explode(',', $atts['category'])),
            'operator' => 'IN',
        ),
    );

// Тег товара (умолчание — «recommended»)
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

// Уникальный ID для независимых экземпляров Swiper
$uid = 'tm-featured-' . uniqid();
?>

<section class="tm-featured-products<?php echo !empty($atts['class']) ? ' ' . esc_attr($atts['class']) : ''; ?>" aria-label="<?php echo esc_attr($atts['title']); ?>">

    <?php if (!empty($atts['title'])) : ?>
    <div class="tm-featured-products__header">
        <h2 class="tm-featured-products__title"><?php echo esc_html($atts['title']); ?></h2>
        <?php if (!empty($atts['link'])) : ?>
        <a href="<?php echo esc_url($atts['link']); ?>" class="tm-featured-products__all">
            <?php echo esc_html($atts['link_text']); ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="tm-featured-slider swiper" id="<?php echo esc_attr($uid); ?>">
        <div class="swiper-wrapper">

            <?php
            while ($products->have_posts()) :
                $products->the_post();

                global $product;
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

                // Строим кнопку «В корзину» / «Подробнее»
                if ($product->is_purchasable() && $product->is_in_stock()) {
                    $btn_classes = 'tm-featured-card__cart button add_to_cart_button';
                    if ($product->supports('ajax_add_to_cart')) {
                        $btn_classes .= ' ajax_add_to_cart';
                    }
                    $cart_button = sprintf(
                        '<a href="%s" data-quantity="1" data-product_id="%s" data-product_sku="%s" aria-label="%s" rel="nofollow" class="%s">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            В корзину
                        </a>',
                        esc_url($product->add_to_cart_url()),
                        esc_attr($product_id),
                        esc_attr($product->get_sku()),
                        esc_attr($product->add_to_cart_description()),
                        esc_attr($btn_classes)
                    );
                } else {
                    $cart_button = sprintf(
                        '<a href="%s" class="tm-featured-card__cart tm-featured-card__cart--link">Подробнее</a>',
                        esc_url($permalink)
                    );
                }
            ?>

            <div class="swiper-slide">
                <div class="tm-featured-card">

                    <a href="<?php echo esc_url($permalink); ?>" class="tm-featured-card__img-wrap" tabindex="-1">
                        <img
                            src="<?php echo esc_url($thumbnail_url); ?>"
                            alt="<?php echo esc_attr($title); ?>"
                            class="tm-featured-card__img"
                            loading="lazy"
                            decoding="async"
                        />
                        <?php if ($product->is_on_sale()) : ?>
                        <span class="tm-featured-card__badge">Акция</span>
                        <?php endif; ?>
                    </a>

                    <div class="tm-featured-card__body">
                        <a href="<?php echo esc_url($permalink); ?>" class="tm-featured-card__name">
                            <?php echo esc_html($title); ?>
                        </a>

                        <div class="tm-featured-card__footer">
                            <div class="tm-featured-card__price">
                                <?php woocommerce_template_loop_price(); ?>
                            </div>
                            <?php echo $cart_button; ?>
                        </div>
                    </div>

                </div>
            </div>

            <?php endwhile; ?>

        </div><!-- /swiper-wrapper -->

        <!-- Навигация — уникальный uid чтобы не конфликтовали несколько слайдеров -->
        <div class="tm-featured-slider__prev swiper-button swiper-button-prev" data-uid="<?php echo esc_attr($uid); ?>"></div>
        <div class="tm-featured-slider__next swiper-button swiper-button-next" data-uid="<?php echo esc_attr($uid); ?>"></div>

    </div><!-- /tm-featured-slider -->

</section>

<?php wp_reset_postdata(); ?>
