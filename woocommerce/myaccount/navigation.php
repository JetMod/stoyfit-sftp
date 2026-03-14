<?php
/**
 * My Account navigation — переопределение шаблона WooCommerce
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @version 2.6.0
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();

$nav_icons = array(
    'dashboard'       => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>',
    'orders'          => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
    'edit-address'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
    'edit-account'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    'customer-logout' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
);
?>

<div class="tm-account-user">
    <div class="tm-account-user__avatar">
        <?php echo get_avatar($current_user->user_email, 56, '', '', array('class' => 'tm-account-user__img')); ?>
    </div>
    <div class="tm-account-user__info">
        <span class="tm-account-user__name"><?php echo esc_html($current_user->display_name); ?></span>
        <span class="tm-account-user__email"><?php echo esc_html($current_user->user_email); ?></span>
    </div>
</div>

<ul class="tm-account-nav-list">
    <?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
        <li class="tm-account-nav-list__item <?php echo esc_attr(wc_get_account_menu_item_classes($endpoint)); ?>">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>">
                <?php if (isset($nav_icons[$endpoint])) echo $nav_icons[$endpoint]; ?>
                <span><?php echo esc_html($label); ?></span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
