<?php
/**
 * Login/Register form — переопределение шаблона WooCommerce
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @version 9.2.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_customer_login_form');
?>

<div class="tm-login-page">

    <div class="uk-grid uk-grid-large" data-uk-grid-margin>

        <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>

        <!-- ── Вход ─────────────────────────────────────── -->
        <div class="uk-width-medium-1-2">
        <?php endif; ?>

            <div class="tm-login-box">
                <h2 class="tm-login-box__title">Войти в аккаунт</h2>
                <p class="tm-login-box__sub">Введите email и пароль для входа в личный кабинет</p>

                <form class="woocommerce-form woocommerce-form-login login" method="post">

                    <?php do_action('woocommerce_login_form_start'); ?>

                    <div class="tm-form-row">
                        <label for="username" class="tm-form-label">
                            Email или имя пользователя <span class="required" aria-hidden="true">*</span>
                        </label>
                        <input
                            type="text"
                            class="tm-form-input woocommerce-Input woocommerce-Input--text input-text"
                            name="username"
                            id="username"
                            autocomplete="username"
                            value="<?php echo !empty($_POST['username']) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"
                            required
                        />
                    </div>

                    <div class="tm-form-row">
                        <label for="password" class="tm-form-label">
                            Пароль <span class="required" aria-hidden="true">*</span>
                        </label>
                        <div class="tm-form-input-wrap">
                            <input
                                type="password"
                                class="tm-form-input woocommerce-Input woocommerce-Input--text input-text"
                                name="password"
                                id="password"
                                autocomplete="current-password"
                                required
                            />
                            <!-- <button type="button" class="tm-form-eye" aria-label="Показать пароль" data-target="password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3" fill="currentColor" stroke="none"/></svg>
                            </button> -->
                        </div>
                    </div>

                    <?php do_action('woocommerce_login_form'); ?>

                    <div class="tm-form-row tm-form-row--consent">
                        <label class="tm-form-checkbox tm-form-checkbox--consent<?php echo !empty($_POST['login']) && empty($_POST['tm_consent_login']) ? ' tm-form-checkbox--error' : ''; ?>">
                            <input type="checkbox" name="tm_consent_login" id="tm_consent_login" value="1" <?php checked(!empty($_POST['tm_consent_login'])); ?> required />
                            <span>Я соглашаюсь с <?php
                            $privacy_url = get_privacy_policy_url();
                            echo $privacy_url ? '<a href="' . esc_url($privacy_url) . '" target="_blank" rel="noopener">политикой конфиденциальности</a>' : 'политикой конфиденциальности';
                            ?>.</span>
                        </label>
                    </div>

                    <div class="tm-form-row tm-form-row--footer">
                        <label class="tm-form-checkbox">
                            <input type="checkbox" class="woocommerce-form__input-checkbox" name="rememberme" id="rememberme" value="forever" />
                            <span>Запомнить меня</span>
                        </label>
                        <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="tm-form-forgot">Забыли пароль?</a>
                    </div>

                    <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>

                    <button type="submit" class="tm-btn tm-btn--primary tm-btn--full" name="login" value="Войти">
                        Войти
                    </button>

                    <?php do_action('woocommerce_login_form_end'); ?>

                </form>
            </div>

        <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>
        </div>

        <!-- ── Регистрация ───────────────────────────────── -->
        <div class="uk-width-medium-1-2">

            <div class="tm-login-box tm-login-box--register">
                <h2 class="tm-login-box__title">Создать аккаунт</h2>
                <p class="tm-login-box__sub">Зарегистрируйтесь, чтобы отслеживать заказы и управлять покупками</p>

                <form method="post" class="woocommerce-form woocommerce-form-register register">

                    <?php do_action('woocommerce_register_form_start'); ?>

                    <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                    <div class="tm-form-row">
                        <label for="reg_username" class="tm-form-label">
                            Имя пользователя <span class="required" aria-hidden="true">*</span>
                        </label>
                        <input
                            type="text"
                            class="tm-form-input woocommerce-Input woocommerce-Input--text input-text"
                            name="username"
                            id="reg_username"
                            autocomplete="username"
                            value="<?php echo !empty($_POST['username']) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"
                        />
                    </div>
                    <?php endif; ?>

                    <div class="tm-form-row">
                        <label for="reg_email" class="tm-form-label">
                            Email <span class="required" aria-hidden="true">*</span>
                        </label>
                        <input
                            type="email"
                            class="tm-form-input woocommerce-Input woocommerce-Input--text input-text"
                            name="email"
                            id="reg_email"
                            autocomplete="email"
                            value="<?php echo !empty($_POST['email']) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>"
                            required
                        />
                    </div>

                    <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
                    <div class="tm-form-row">
                        <label for="reg_password" class="tm-form-label">
                            Пароль <span class="required" aria-hidden="true">*</span>
                        </label>
                        <div class="tm-form-input-wrap">
                            <input
                                type="password"
                                class="tm-form-input woocommerce-Input woocommerce-Input--text input-text"
                                name="password"
                                id="reg_password"
                                autocomplete="new-password"
                            />
                            <!-- <button type="button" class="tm-form-eye" aria-label="Показать пароль" data-target="reg_password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3" fill="currentColor" stroke="none"/></svg>
                            </button> -->
                        </div>
                    </div>
                    <?php else : ?>
                    <p class="tm-form-hint">Пароль будет отправлен на указанный email.</p>
                    <?php endif; ?>

                    <?php do_action('woocommerce_register_form'); ?>

                    <div class="tm-form-row tm-form-row--consent">
                        <label class="tm-form-checkbox tm-form-checkbox--consent<?php echo !empty($_POST['register']) && empty($_POST['tm_consent_register']) ? ' tm-form-checkbox--error' : ''; ?>">
                            <input type="checkbox" name="tm_consent_register" id="tm_consent_register" value="1" <?php checked(!empty($_POST['tm_consent_register'])); ?> required />
                            <span>Я принимаю <?php
                            $terms_url = function_exists('tm_get_terms_url') ? tm_get_terms_url() : '';
                            $privacy_url = get_privacy_policy_url();
                            echo $terms_url ? '<a href="' . esc_url($terms_url) . '" target="_blank" rel="noopener">условия пользовательского соглашения</a>' : 'условия пользовательского соглашения';
                            ?> и даю согласие на <?php
                            echo $privacy_url ? '<a href="' . esc_url($privacy_url) . '" target="_blank" rel="noopener">обработку персональных данных</a>' : 'обработку персональных данных';
                            ?> в соответствии с 152-ФЗ.</span>
                        </label>
                    </div>

                    <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>

                    <button type="submit" class="tm-btn tm-btn--outline tm-btn--full" name="register" value="Зарегистрироваться">
                        Создать аккаунт
                    </button>

                    <?php do_action('woocommerce_register_form_end'); ?>

                </form>
            </div>

        </div>

        </div><!-- /uk-grid -->

        <?php else : ?>
        </div><!-- /uk-grid -->
        <?php endif; ?>

</div><!-- /tm-login-page -->

<?php do_action('woocommerce_after_customer_login_form'); ?>
