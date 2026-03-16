<?php
/**
 * Форма «Забыли пароль» — переопределение шаблона WooCommerce
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_lost_password_form');
?>

<div class="tm-lost-password-page">
    <div class="tm-login-box tm-login-box--lost-password">
        <h2 class="tm-login-box__title">Забыли пароль</h2>
        <p class="tm-login-box__sub">Укажите email или имя пользователя. Ссылку на создание нового пароля вы получите по электронной почте.</p>

        <form method="post" class="woocommerce-ResetPassword lost_reset_password">

            <?php do_action('woocommerce_lostpassword_form'); ?>

            <div class="tm-form-row">
                <label for="user_login" class="tm-form-label">
                    Имя пользователя или Email <span class="required" aria-hidden="true">*</span>
                </label>
                <input
                    class="tm-form-input woocommerce-Input woocommerce-Input--text input-text"
                    type="text"
                    name="user_login"
                    id="user_login"
                    autocomplete="username"
                    required
                    aria-required="true"
                    value="<?php echo !empty($_POST['user_login']) ? esc_attr(wp_unslash($_POST['user_login'])) : ''; ?>"
                />
            </div>

            <input type="hidden" name="wc_reset_password" value="true" />
            <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>

            <button type="submit" class="tm-btn tm-btn--primary tm-btn--full woocommerce-Button button" name="reset_password" value="Сброс пароля">
                Сброс пароля
            </button>

        </form>
    </div>
</div>

<?php
do_action('woocommerce_after_lost_password_form');
