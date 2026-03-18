<?php
/**
 * Checkout Payment Section — стилизованный шаблон под тему Стройфит
 *
 * Переменные, доступные из WooCommerce:
 *   $checkout           — WC_Checkout instance
 *   $available_gateways — массив доступных методов оплаты
 *   $order_button_text  — текст кнопки «Оформить заказ» (уже прошёл через фильтр)
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @version 3.5.0
 */

defined('ABSPATH') || exit;

if (!wp_doing_ajax()) {
    do_action('woocommerce_review_order_before_payment');
}
?>

<div id="payment" class="woocommerce-checkout-payment">

    <?php if (WC()->cart->needs_payment()) : ?>
    <ul class="tm-checkout-payment-methods wc_payment_methods payment_methods methods">
        <?php
        if (!empty($available_gateways)) {
            foreach ($available_gateways as $gateway) :
        ?>
        <li class="tm-checkout-payment-method wc_payment_method payment_method_<?php echo esc_attr($gateway->id); ?>">

            <label for="payment_method_<?php echo esc_attr($gateway->id); ?>" class="tm-checkout-payment-method__label">
                <input
                    id="payment_method_<?php echo esc_attr($gateway->id); ?>"
                    type="radio"
                    class="input-radio"
                    name="payment_method"
                    value="<?php echo esc_attr($gateway->id); ?>"
                    <?php checked($gateway->chosen, true); ?>
                    data-order_button_text="<?php echo esc_attr($gateway->order_button_text); ?>"
                />
                <span class="tm-checkout-payment-method__name"><?php echo esc_html($gateway->get_title()); ?></span>
                <?php if ($gateway->get_icon()) : ?>
                <span class="tm-checkout-payment-method__icon"><?php echo $gateway->get_icon(); ?></span>
                <?php endif; ?>
            </label>

            <div class="tm-checkout-payment-method__desc payment_box payment_method_<?php echo esc_attr($gateway->id); ?>"<?php if (!$gateway->chosen) : ?> style="display:none;"<?php endif; ?>>
                <?php $gateway->payment_fields(); ?>
            </div>

        </li>
        <?php
            endforeach;
        } else {
            echo '<li class="tm-checkout-no-payment"><p>'
                . esc_html(apply_filters(
                    'woocommerce_no_available_payment_methods_message',
                    WC()->customer->get_billing_country()
                        ? __('Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce')
                        : __('Please fill in your details above to see available payment methods.', 'woocommerce')
                ))
                . '</p></li>';
        }
        ?>
    </ul>
    <?php endif; ?>

    <div class="tm-checkout-place-order">

        <?php
        $terms_url = function_exists('tm_get_terms_url') ? tm_get_terms_url() : '';
        $privacy_url = get_privacy_policy_url();
        ?>
        <div class="tm-checkout-consent-wrap">
            <label class="tm-checkout-consent-checkbox<?php echo !empty($_POST['woocommerce_checkout_place_order']) && empty($_POST['tm_consent_checkout']) ? ' tm-form-checkbox--error' : ''; ?>">
                <input type="checkbox" name="tm_consent_checkout" id="tm_consent_checkout" value="1" <?php checked(!empty($_POST['tm_consent_checkout'])); ?> required />
                <span>Я принимаю <?php
                echo $terms_url ? '<a href="' . esc_url($terms_url) . '" target="_blank" rel="noopener">условия пользовательского соглашения</a>' : 'условия пользовательского соглашения';
                ?> и даю согласие на <?php
                echo $privacy_url ? '<a href="' . esc_url($privacy_url) . '" target="_blank" rel="noopener">обработку персональных данных</a>' : 'обработку персональных данных';
                ?> в соответствии с 152-ФЗ.</span>
            </label>
        </div>

        <noscript>
            <p><?php esc_html_e('Пожалуйста, включите JavaScript в браузере для оформления заказа.', 'woocommerce'); ?></p>
            <input type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order_noscript" value="<?php echo esc_attr($order_button_text); ?>" />
        </noscript>

        <?php do_action('woocommerce_review_order_before_submit'); ?>

        <?php
        // wc_get_checkout_button_classes() добавляет классы для обработки загрузки
        $extra_classes = function_exists('wc_get_checkout_button_classes')
            ? ' ' . esc_attr(implode(' ', wc_get_checkout_button_classes()))
            : '';

        echo apply_filters(
            'woocommerce_order_button_html',
            '<button type="submit"'
                . ' class="tm-checkout-submit button alt' . $extra_classes . '"'
                . ' name="woocommerce_checkout_place_order"'
                . ' id="place_order"'
                . ' value="' . esc_attr($order_button_text) . '"'
                . ' data-value="' . esc_attr($order_button_text) . '">'
                . esc_html($order_button_text)
            . '</button>'
        );
        ?>

        <?php do_action('woocommerce_review_order_after_submit'); ?>

        <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>

    </div>

</div><!-- /#payment -->

<?php
if (!wp_doing_ajax()) {
    do_action('woocommerce_review_order_after_payment');
}
?>
