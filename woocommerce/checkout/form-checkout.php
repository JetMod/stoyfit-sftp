<?php
/**
 * Checkout Form — стилизованный шаблон под тему Стройфит
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @version 3.5.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_checkout_form', $checkout);

// Без JS нельзя оформить заказ
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}
?>

<div class="tm-checkout-page">

    <?php wc_print_notices(); ?>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

        <div class="tm-checkout-layout uk-grid uk-grid-large" data-uk-grid-margin>

            <!-- ── Левая колонка: данные покупателя ─────────────────────── -->
            <div class="uk-width-medium-7-10 tm-checkout-left">

                <?php if ($checkout->get_checkout_fields()) : ?>
                <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                <!-- Ваши данные -->
                <div class="tm-checkout-section" id="customer_details">
                    <div class="tm-checkout-section__header">
                        <span class="tm-checkout-section__num">1</span>
                        <h3 class="tm-checkout-section__title">Ваши данные</h3>
                    </div>
                    <div class="tm-checkout-section__body">
                        <?php do_action('woocommerce_checkout_billing'); ?>
                    </div>
                </div>

                <!-- Доставка (если нужна отдельная) -->
                <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                <div class="tm-checkout-section" id="shipping_details">
                    <div class="tm-checkout-section__header">
                        <span class="tm-checkout-section__num">2</span>
                        <h3 class="tm-checkout-section__title">Доставка</h3>
                    </div>
                    <div class="tm-checkout-section__body">
                        <?php do_action('woocommerce_checkout_shipping'); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Дополнительно (комментарий) -->
                <?php if (isset($checkout->get_checkout_fields()['order']) && $checkout->get_checkout_fields()['order']) : ?>
                <div class="tm-checkout-section">
                    <div class="tm-checkout-section__header">
                        <span class="tm-checkout-section__num"><?php echo WC()->cart->needs_shipping() && WC()->cart->show_shipping() ? '3' : '2'; ?></span>
                        <h3 class="tm-checkout-section__title">Дополнительно</h3>
                    </div>
                    <div class="tm-checkout-section__body">
                        <?php foreach ($checkout->get_checkout_fields('order') as $key => $field) : ?>
                            <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                <?php endif; ?>

            </div>

            <!-- ── Правая колонка: заказ + оплата ───────────────────────── -->
            <div class="uk-width-medium-3-10 tm-checkout-right">
                <div class="tm-checkout-summary">

                    <!-- Заголовок -->
                    <div class="tm-checkout-summary__header">
                        <h3 class="tm-checkout-summary__title">Ваш заказ</h3>
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="tm-checkout-summary__edit">Изменить</a>
                    </div>

                    <!-- Таблица товаров и итогов -->
                    <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php do_action('woocommerce_checkout_order_review'); ?>
                    </div>

                </div>
            </div>

        </div><!-- /tm-checkout-layout -->

    </form>

</div><!-- /tm-checkout-page -->

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
