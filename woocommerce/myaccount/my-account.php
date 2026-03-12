<?php
/**
 * My Account page — переопределение шаблона WooCommerce
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @version 3.5.0
 */

defined('ABSPATH') || exit;
?>

<div class="tm-my-account">

    <?php wc_print_notices(); ?>

    <div class="uk-grid uk-grid-large" data-uk-grid-margin>

        <div class="uk-width-medium-1-4 tm-my-account__sidebar">
            <div class="tm-my-account__nav-wrap">
                <?php do_action('woocommerce_before_account_navigation'); ?>
                <nav class="tm-my-account__nav" aria-label="Навигация аккаунта">
                    <?php do_action('woocommerce_account_navigation'); ?>
                </nav>
                <?php do_action('woocommerce_after_account_navigation'); ?>
            </div>
        </div>

        <div class="uk-width-medium-3-4 tm-my-account__main">
            <?php do_action('woocommerce_before_account_content'); ?>
            <div class="tm-my-account__content">
                <?php do_action('woocommerce_account_content'); ?>
            </div>
            <?php do_action('woocommerce_after_account_content'); ?>
        </div>

    </div>

</div>
