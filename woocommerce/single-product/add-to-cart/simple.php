<?php
/**
 * Simple product add to cart — с кнопками +/- как в корзине
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

global $product;

if (!$product->is_purchasable()) {
    return;
}

echo wc_get_stock_html($product); // WPCS: XSS ok.

if ($product->is_in_stock()) : ?>

<form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data">
    <?php do_action('woocommerce_before_add_to_cart_button'); ?>

    <?php
    do_action('woocommerce_before_add_to_cart_quantity');

    $min_quantity = $product->get_min_purchase_quantity();
    $max_quantity = $product->get_max_purchase_quantity();
    $input_value  = isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(); // WPCS: CSRF ok, input var ok.
    $data_max     = ($max_quantity > 0) ? $max_quantity : 9999;

    $quantity_args = array(
        'min_value'   => $min_quantity,
        'max_value'   => $max_quantity,
        'input_value' => $input_value,
    );
    $product_quantity = woocommerce_quantity_input($quantity_args, $product, false);
    ?>
    <div class="tm-cart-qty tm-product-qty" data-min="<?php echo esc_attr($min_quantity); ?>" data-max="<?php echo esc_attr($data_max); ?>">
        <button type="button" class="tm-cart-qty__btn tm-cart-qty__btn--minus" aria-label="<?php esc_attr_e('Уменьшить', 'woocommerce'); ?>">−</button>
        <?php echo $product_quantity; ?>
        <button type="button" class="tm-cart-qty__btn tm-cart-qty__btn--plus" aria-label="<?php esc_attr_e('Увеличить', 'woocommerce'); ?>">+</button>
    </div>

    <?php do_action('woocommerce_after_add_to_cart_quantity'); ?>

    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>

    <?php do_action('woocommerce_after_add_to_cart_button'); ?>
</form>

<?php endif;
