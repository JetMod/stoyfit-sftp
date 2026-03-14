<?php
/**
 * Mini Cart — стилизованный шаблон с кнопками +/- количества
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_mini_cart');
?>

<?php if (!WC()->cart->is_empty()) : ?>

    <form class="woocommerce-mini-cart-form" method="post">
        <ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr(isset($args['list_class']) ? $args['list_class'] : ''); ?>">
            <?php
            do_action('woocommerce_before_mini_cart_contents');

            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                if ($_product && $_product->exists() && $cart_item['quantity'] > 0
                    && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {

                    $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);

                    if ($_product->is_sold_individually()) {
                        $min_qty = 1;
                        $max_qty = 1;
                    } else {
                        $min_qty = 0;
                        $max_qty = $_product->get_max_purchase_quantity();
                    }
                    $data_max = ($max_qty > 0) ? $max_qty : 9999;
                    ?>
                    <li class="woocommerce-mini-cart-item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">
                        <?php
                        echo apply_filters(
                            'woocommerce_cart_item_remove_link',
                            sprintf(
                                '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
                                esc_url(wc_get_cart_remove_url($cart_item_key)),
                                esc_attr(sprintf(__('Remove &ldquo;%s&rdquo; from your cart', 'woocommerce'), wp_strip_all_tags($product_name))),
                                esc_attr($product_id),
                                esc_attr($cart_item_key),
                                esc_attr($_product->get_sku())
                            ),
                            $cart_item_key
                        );
                        ?>
                        <?php if (empty($product_permalink)) : ?>
                            <?php echo $thumbnail; ?>
                        <?php else : ?>
                            <a href="<?php echo esc_url($product_permalink); ?>"><?php echo $thumbnail; ?></a>
                        <?php endif; ?>

                        <div class="tm-mini-cart-item__body">
                            <?php if (empty($product_permalink)) : ?>
                                <?php echo wp_kses_post($product_name); ?>
                            <?php else : ?>
                                <a href="<?php echo esc_url($product_permalink); ?>"><?php echo wp_kses_post($product_name); ?></a>
                            <?php endif; ?>

                            <?php echo wc_get_formatted_cart_item_data($cart_item); ?>

                            <div class="tm-mini-cart-item__row">
                                <div class="tm-cart-qty tm-cart-qty--mini" data-min="<?php echo esc_attr($min_qty); ?>" data-max="<?php echo esc_attr($data_max); ?>">
                                    <button type="button" class="tm-cart-qty__btn tm-cart-qty__btn--minus" aria-label="<?php esc_attr_e('Уменьшить', 'woocommerce'); ?>">−</button>
                                    <input type="number" class="qty" name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]" value="<?php echo esc_attr($cart_item['quantity']); ?>" min="<?php echo esc_attr($min_qty); ?>" max="<?php echo esc_attr($data_max); ?>" step="1" inputmode="numeric" />
                                    <button type="button" class="tm-cart-qty__btn tm-cart-qty__btn--plus" aria-label="<?php esc_attr_e('Увеличить', 'woocommerce'); ?>">+</button>
                                </div>
                                <span class="tm-mini-cart-item__price"><?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?></span>
                            </div>
                        </div>
                    </li>
                    <?php
                }
            }

            do_action('woocommerce_mini_cart_contents');
            ?>
        </ul>

        <p class="woocommerce-mini-cart__total total">
            <?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>
            <strong><?php esc_html_e('Subtotal', 'woocommerce'); ?>:</strong> <?php wc_cart_totals_subtotal_html(); ?>
        </p>

        <?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

        <p class="woocommerce-mini-cart__buttons buttons">
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="button wc-forward"><?php esc_html_e('View cart', 'woocommerce'); ?></a>
            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="button checkout wc-forward"><?php esc_html_e('Checkout', 'woocommerce'); ?></a>
        </p>
    </form>

<?php else : ?>

    <p class="woocommerce-mini-cart__empty-message"><?php esc_html_e('No products in the cart.', 'woocommerce'); ?></p>

<?php endif; ?>

<?php do_action('woocommerce_after_mini_cart'); ?>
