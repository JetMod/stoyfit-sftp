<?php
/**
 * Single variation cart button — с кнопками +/- как в корзине
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

global $product;

$min_quantity = $product->get_min_purchase_quantity();
$max_quantity = $product->get_max_purchase_quantity();
$input_value  = isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(); // WPCS: CSRF ok, input var ok.
$data_max     = ($max_quantity > 0) ? $max_quantity : 9999;

$quantity_args = array(
    'min_value'   => apply_filters('woocommerce_quantity_input_min', $min_quantity, $product),
    'max_value'   => apply_filters('woocommerce_quantity_input_max', $max_quantity, $product),
    'input_value' => $input_value,
);
?>
<div class="woocommerce-variation-add-to-cart variations_button">
    <?php do_action('woocommerce_before_add_to_cart_button'); ?>

    <?php do_action('woocommerce_before_add_to_cart_quantity'); ?>

    <div class="tm-cart-qty tm-product-qty" data-min="<?php echo esc_attr($min_quantity); ?>" data-max="<?php echo esc_attr($data_max); ?>">
        <button type="button" class="tm-cart-qty__btn tm-cart-qty__btn--minus" aria-label="<?php esc_attr_e('Уменьшить', 'woocommerce'); ?>">−</button>
        <?php woocommerce_quantity_input($quantity_args, $product, false); ?>
        <button type="button" class="tm-cart-qty__btn tm-cart-qty__btn--plus" aria-label="<?php esc_attr_e('Увеличить', 'woocommerce'); ?>">+</button>
    </div>

    <?php do_action('woocommerce_after_add_to_cart_quantity'); ?>

    <button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>

    <?php do_action('woocommerce_after_add_to_cart_button'); ?>

    <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>" />
    <input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>" />
    <input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>
