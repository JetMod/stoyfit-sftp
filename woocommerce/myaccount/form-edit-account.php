<?php
/**
 * Edit account form — переопределение шаблона WooCommerce
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @version 10.5.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_edit_account_form');
?>

<div class="tm-edit-account-box">
    <form class="woocommerce-EditAccountForm edit-account tm-edit-account-form" action="" method="post" <?php do_action('woocommerce_edit_account_form_tag'); ?>>

        <?php do_action('woocommerce_edit_account_form_start'); ?>

        <div class="tm-edit-account__row">
            <div class="tm-form-row tm-form-row--half">
                <label for="account_first_name" class="tm-form-label">
                    Имя <span class="required" aria-hidden="true">*</span>
                </label>
                <input type="text" class="tm-form-input woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr($user->first_name); ?>" aria-required="true" />
            </div>
            <div class="tm-form-row tm-form-row--half">
                <label for="account_last_name" class="tm-form-label">
                    Фамилия <span class="required" aria-hidden="true">*</span>
                </label>
                <input type="text" class="tm-form-input woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr($user->last_name); ?>" aria-required="true" />
            </div>
        </div>

        <div class="tm-form-row">
            <label for="account_display_name" class="tm-form-label">
                Отображаемое имя <span class="required" aria-hidden="true">*</span>
            </label>
            <input type="text" class="tm-form-input woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" aria-describedby="account_display_name_description" value="<?php echo esc_attr($user->display_name); ?>" aria-required="true" />
            <p id="account_display_name_description" class="tm-form-hint">Так ваше имя будет отображаться в разделе аккаунта и при публикации отзывов</p>
        </div>

        <div class="tm-form-row">
            <label for="account_email" class="tm-form-label">
                Email <span class="required" aria-hidden="true">*</span>
            </label>
            <input type="email" class="tm-form-input woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr($user->user_email); ?>" aria-required="true" />
        </div>

        <?php do_action('woocommerce_edit_account_form_fields'); ?>

        <fieldset class="tm-edit-account__fieldset">
            <legend class="tm-edit-account__legend">Смена пароля</legend>

            <div class="tm-form-row">
                <label for="password_current" class="tm-form-label">Действующий пароль <span class="tm-form-hint-inline">(не заполняйте, чтобы оставить прежний)</span></label>
                <div class="tm-form-input-wrap">
                    <input type="password" class="tm-form-input woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="current-password" />
                    <!-- <button type="button" class="tm-form-eye" aria-label="Показать пароль" data-target="password_current">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3" fill="currentColor" stroke="none"/></svg>
                    </button> -->
                </div>
            </div>
            <div class="tm-form-row">
                <label for="password_1" class="tm-form-label">Новый пароль <span class="tm-form-hint-inline">(не заполняйте, чтобы оставить прежний)</span></label>
                <div class="tm-form-input-wrap">
                    <input type="password" class="tm-form-input woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="new-password" />
                    <!-- <button type="button" class="tm-form-eye" aria-label="Показать пароль" data-target="password_1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3" fill="currentColor" stroke="none"/></svg>
                    </button> -->
                </div>
            </div>
            <div class="tm-form-row">
                <label for="password_2" class="tm-form-label">Подтвердите новый пароль</label>
                <div class="tm-form-input-wrap">
                    <input type="password" class="tm-form-input woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="new-password" />
                    <!-- <button type="button" class="tm-form-eye" aria-label="Показать пароль" data-target="password_2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3" fill="currentColor" stroke="none"/></svg>
                    </button> -->
                </div>
            </div>
        </fieldset>

        <?php do_action('woocommerce_edit_account_form'); ?>

        <div class="tm-form-row tm-form-row--submit">
            <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
            <button type="submit" class="tm-btn tm-btn--primary" name="save_account_details" value="Сохранить изменения">Сохранить изменения</button>
            <input type="hidden" name="action" value="save_account_details" />
        </div>

        <?php do_action('woocommerce_edit_account_form_end'); ?>
    </form>
</div>

<?php do_action('woocommerce_after_edit_account_form'); ?>
