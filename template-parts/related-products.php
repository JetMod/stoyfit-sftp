<?php
/**
 * Блок «Похожие товары» — Swiper-слайдер
 *
 * Подключается в конце шаблонов одиночного товара.
 * Использует WooCommerce wc_get_related_products() — связь по тегам и категориям.
 *
 * Параметры (через $args):
 *   'limit'  — максимальное кол-во похожих товаров (default: 8)
 *   'title'  — заголовок секции (default: 'Похожие товары')
 */

defined('ABSPATH') || exit;

global $product;

if (!$product || !($product instanceof WC_Product)) {
    $product = wc_get_product(get_the_ID());
}

if (!$product || !$product->is_visible()) {
    return;
}

$defaults = array(
    'limit' => 8,
    'title' => 'Похожие товары',
);
$atts = wp_parse_args($args ?? array(), $defaults);
$limit = max(1, (int) $atts['limit']);

// Получаем ID похожих товаров через встроенную функцию WooCommerce
// (связь по общим тегам и категориям, текущий товар исключается автоматически)
$related_ids = wc_get_related_products($product->get_id(), $limit, array($product->get_id()));

if (empty($related_ids)) {
    return;
}

// Собираем объекты товаров, фильтруем невидимые
$related_products = array_filter(
    array_map('wc_get_product', $related_ids),
    function ($p) {
        return $p && $p->is_visible();
    }
);

if (empty($related_products)) {
    return;
}

// Уникальный ID для независимой навигации (если несколько слайдеров на странице)
$uid = 'tm-related-' . uniqid();
?>

<section class="tm-related-products" aria-label="<?php echo esc_attr($atts['title']); ?>">

    <div class="tm-related-products__header">
        <h2 class="tm-related-products__title"><?php echo esc_html($atts['title']); ?></h2>
    </div>

    <div class="tm-related-slider swiper" id="<?php echo esc_attr($uid); ?>">
        <div class="swiper-wrapper">

            <?php foreach ($related_products as $related) : ?>
            <?php
                $related_id    = $related->get_id();
                $permalink     = get_permalink($related_id);
                $title         = $related->get_name();
                $thumbnail_url = has_post_thumbnail($related_id)
                    ? get_the_post_thumbnail_url($related_id, 'custom_thumb')
                    : wc_placeholder_img_src('custom_thumb');

                // Кнопка «В корзину» / «Подробнее»
                if ($related->is_purchasable() && $related->is_in_stock()) {
                    $btn_classes = 'tm-featured-card__cart button add_to_cart_button';
                    if ($related->supports('ajax_add_to_cart')) {
                        $btn_classes .= ' ajax_add_to_cart';
                    }
                    $cart_button = sprintf(
                        '<a href="%s" data-quantity="1" data-product_id="%s" data-product_sku="%s" aria-label="%s" rel="nofollow" class="%s">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            В корзину
                        </a>',
                        esc_url($related->add_to_cart_url()),
                        esc_attr($related_id),
                        esc_attr($related->get_sku()),
                        esc_attr($related->add_to_cart_description()),
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
                        <?php if ($related->is_on_sale()) : ?>
                        <span class="tm-featured-card__badge">Акция</span>
                        <?php endif; ?>
                    </a>

                    <div class="tm-featured-card__body">
                        <a href="<?php echo esc_url($permalink); ?>" class="tm-featured-card__name">
                            <?php echo esc_html($title); ?>
                        </a>

                        <div class="tm-featured-card__footer">
                            <div class="tm-featured-card__price">
                                <?php
                                // woocommerce_template_loop_price() требует the_post() в глобальном контексте
                                // используем прямой вывод цены
                                echo $related->get_price_html();
                                ?>
                            </div>
                            <?php echo $cart_button; ?>
                        </div>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>

        </div><!-- /swiper-wrapper -->

        <div class="tm-related-slider__prev swiper-button swiper-button-prev" data-uid="<?php echo esc_attr($uid); ?>"></div>
        <div class="tm-related-slider__next swiper-button swiper-button-next" data-uid="<?php echo esc_attr($uid); ?>"></div>

    </div><!-- /tm-related-slider -->

</section>
