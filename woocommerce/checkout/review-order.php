<?php
/**
 * Review order table — стилизованный шаблон под тему Стройфит
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @version 3.8.0
 */

defined('ABSPATH') || exit;
?>

<div class="tm-checkout-order-review">

    <!-- Список товаров -->
    <div class="tm-checkout-items">
        <?php
        do_action('woocommerce_review_order_before_cart_contents');

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

            if (!$_product || !$_product->exists() || $cart_item['quantity'] === 0
                || !apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                continue;
            }

            $product_permalink = apply_filters(
                'woocommerce_cart_item_permalink',
                $_product->is_visible() ? $_product->get_permalink($cart_item) : '',
                $cart_item,
                $cart_item_key
            );
        ?>
        <div class="tm-checkout-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
            <div class="tm-checkout-item__img">
                <?php
                $thumbnail = apply_filters(
                    'woocommerce_cart_item_thumbnail',
                    $_product->get_image('woocommerce_thumbnail', array('class' => 'tm-checkout-item__photo')),
                    $cart_item,
                    $cart_item_key
                );
                if ($product_permalink) {
                    echo '<a href="' . esc_url($product_permalink) . '">' . $thumbnail . '</a>';
                } else {
                    echo $thumbnail;
                }
                ?>
                <span class="tm-checkout-item__qty"><?php echo esc_html($cart_item['quantity']); ?></span>
            </div>
            <div class="tm-checkout-item__info">
                <span class="tm-checkout-item__name">
                    <?php
                    if ($product_permalink) {
                        echo '<a href="' . esc_url($product_permalink) . '">'
                            . apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)
                            . '</a>';
                    } else {
                        echo apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                    }
                    echo wc_get_formatted_cart_item_data($cart_item);
                    ?>
                </span>
            </div>
            <div class="tm-checkout-item__price">
                <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php do_action('woocommerce_review_order_after_cart_contents'); ?>
    </div>

    <!-- Итоги -->
    <table class="tm-checkout-totals shop_table woocommerce-checkout-review-order-table">
        <tbody>

            <tr class="cart-subtotal">
                <th>Подытог</th>
                <td><?php wc_cart_totals_subtotal_html(); ?></td>
            </tr>

            <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
            <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                <th><?php wc_cart_totals_coupon_label($coupon); ?></th>
                <td><?php wc_cart_totals_coupon_html($coupon); ?></td>
            </tr>
            <?php endforeach; ?>

            <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
            <?php do_action('woocommerce_review_order_before_shipping'); ?>
            <?php wc_cart_totals_shipping_html(); ?>
            <?php do_action('woocommerce_review_order_after_shipping'); ?>
            <?php endif; ?>

            <?php foreach (WC()->cart->get_fees() as $fee) : ?>
            <tr class="fee">
                <th><?php echo esc_html($fee->name); ?></th>
                <td><?php wc_cart_totals_fee_html($fee); ?></td>
            </tr>
            <?php endforeach; ?>

            <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
                <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                    <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                    <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                        <th><?php echo esc_html($tax->label); ?></th>
                        <td><?php echo wp_kses_post($tax->formatted_amount); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                <tr class="tax-total">
                    <th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
                    <td><?php wc_cart_totals_taxes_total_html(); ?></td>
                </tr>
                <?php endif; ?>
            <?php endif; ?>

            <?php do_action('woocommerce_review_order_before_order_total'); ?>

            <tr class="order-total">
                <th>Итого</th>
                <td><?php wc_cart_totals_order_total_html(); ?></td>
            </tr>

            <?php do_action('woocommerce_review_order_after_order_total'); ?>

        </tbody>
    </table>

    <!-- Секция оплаты рендерится отдельно WooCommerce через woocommerce/checkout/payment.php -->

</div><!-- /tm-checkout-order-review -->
