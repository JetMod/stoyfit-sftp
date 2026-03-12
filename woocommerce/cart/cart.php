<?php
/**
 * Cart Page — стилизованный шаблон под тему Стройфит
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @version 7.9.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');
?>

<div class="tm-cart-page">

    <?php wc_print_notices(); ?>

    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

        <?php do_action('woocommerce_before_cart_table'); ?>

        <!-- ── Таблица товаров ───────────────────────────────────────────── -->
        <div class="tm-cart-table-wrap">
            <table class="tm-cart-table shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                <thead>
                    <tr>
                        <th class="product-remove"></th>
                        <th class="product-thumbnail"></th>
                        <th class="product-name">Товар</th>
                        <th class="product-price">Цена</th>
                        <th class="product-quantity">Количество</th>
                        <th class="product-subtotal">Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    <?php do_action('woocommerce_before_cart_contents'); ?>

                    <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) : ?>
                    <?php
                        $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);

                        if (!$_product || !$_product->exists() || $cart_item['quantity'] === 0 || !apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                            continue;
                        }
                    ?>
                    <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

                        <!-- Удалить -->
                        <td class="product-remove">
                            <?php
                            echo apply_filters(
                                'woocommerce_cart_item_remove_link',
                                sprintf(
                                    '<a href="%s" class="tm-cart-remove remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" title="%s">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </a>',
                                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                                    esc_attr__('Удалить этот товар', 'woocommerce'),
                                    esc_attr($product_id),
                                    esc_attr($_product->get_sku()),
                                    esc_attr__('Удалить этот товар', 'woocommerce')
                                ),
                                $cart_item_key
                            );
                            ?>
                        </td>

                        <!-- Изображение -->
                        <td class="product-thumbnail">
                            <?php
                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail', array('class' => 'tm-cart-img')), $cart_item, $cart_item_key);
                            if (!$product_permalink) {
                                echo $thumbnail;
                            } else {
                                printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                            }
                            ?>
                        </td>

                        <!-- Название -->
                        <td class="product-name" data-title="Товар">
                            <?php
                            if (!$product_permalink) {
                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key));
                            } else {
                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a class="tm-cart-product-name" href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                            }

                            do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                            // Мета (вариации и т.д.)
                            echo wc_get_formatted_cart_item_data($cart_item);

                            // Обратная совместимость
                            if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Будет выполнен под заказ', 'woocommerce') . '</p>', $product_id));
                            }
                            ?>
                        </td>

                        <!-- Цена -->
                        <td class="product-price" data-title="Цена">
                            <?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); ?>
                        </td>

                        <!-- Количество -->
                        <td class="product-quantity" data-title="Количество">
                            <?php
                            if ($_product->is_sold_individually()) {
                                $min_quantity = 1;
                                $max_quantity = 1;
                            } else {
                                $min_quantity = 0;
                                $max_quantity = $_product->get_max_purchase_quantity();
                            }

                            $product_quantity = woocommerce_quantity_input(
                                array(
                                    'input_name'   => "cart[{$cart_item_key}][qty]",
                                    'input_value'  => $cart_item['quantity'],
                                    'max_value'    => $max_quantity,
                                    'min_value'    => $min_quantity,
                                    'product_name' => $_product->get_name(),
                                ),
                                $_product,
                                false
                            );
                            echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                            ?>
                        </td>

                        <!-- Подытог -->
                        <td class="product-subtotal" data-title="Сумма">
                            <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                        </td>

                    </tr>
                    <?php endforeach; ?>

                    <?php do_action('woocommerce_cart_contents'); ?>

                    <tr>
                        <td colspan="6" class="actions">
                            <div class="tm-cart-actions">
                                <?php if (wc_coupons_enabled()) : ?>
                                <div class="tm-cart-coupon coupon">
                                    <input
                                        type="text"
                                        name="coupon_code"
                                        class="tm-cart-coupon__input input-text"
                                        id="coupon_code"
                                        value=""
                                        placeholder="<?php esc_attr_e('Промокод', 'woocommerce'); ?>"
                                    />
                                    <button type="submit" class="tm-cart-coupon__btn button" name="apply_coupon" value="<?php esc_attr_e('Применить', 'woocommerce'); ?>">
                                        <?php esc_html_e('Применить', 'woocommerce'); ?>
                                    </button>
                                    <?php do_action('woocommerce_cart_coupon'); ?>
                                </div>
                                <?php endif; ?>

                                <button type="submit" class="tm-cart-update button" name="update_cart" value="<?php esc_attr_e('Обновить корзину', 'woocommerce'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                                    Обновить корзину
                                </button>

                                <?php do_action('woocommerce_cart_actions'); ?>
                                <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                            </div>
                        </td>
                    </tr>

                    <?php do_action('woocommerce_after_cart_contents'); ?>
                </tbody>
            </table>
        </div>

        <?php do_action('woocommerce_after_cart_table'); ?>

    </form>

    <!-- ── Итоги и оформление ────────────────────────────────────────────── -->
    <div class="tm-cart-collaterals">
        <?php do_action('woocommerce_cart_collaterals'); ?>
    </div>

    <div class="tm-cart-continue">
        <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>" class="tm-cart-continue__link">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Продолжить покупки
        </a>
    </div>

</div><!-- /tm-cart-page -->

<?php do_action('woocommerce_after_cart'); ?>
