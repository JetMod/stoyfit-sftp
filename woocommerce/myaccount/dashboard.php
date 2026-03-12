<?php
/**
 * My Account dashboard — переопределение шаблона WooCommerce
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @version 4.4.0
 */

defined('ABSPATH') || exit;

$current_user  = wp_get_current_user();
$orders        = wc_get_orders(array('customer' => get_current_user_id(), 'limit' => 3, 'status' => array('wc-completed', 'wc-processing', 'wc-on-hold')));
?>

<div class="tm-account-dashboard">

    <div class="tm-account-dashboard__greeting">
        <h2 class="tm-account-dashboard__title">
            Здравствуйте, <strong><?php echo esc_html($current_user->display_name); ?></strong>!
        </h2>
        <p class="tm-account-dashboard__subtitle">
            В личном кабинете вы можете просматривать
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>">свои заказы</a>,
            управлять <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-address')); ?>">адресами доставки</a>
            и редактировать <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>">данные аккаунта</a>.
        </p>
    </div>

    <div class="tm-account-dashboard__cards uk-grid uk-grid-small" data-uk-grid-margin>

        <div class="uk-width-medium-1-3">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="tm-account-card">
                <div class="tm-account-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <div class="tm-account-card__text">
                    <span class="tm-account-card__label">Мои заказы</span>
                    <span class="tm-account-card__sub">История покупок</span>
                </div>
                <svg class="tm-account-card__arrow" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>

        <div class="uk-width-medium-1-3">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-address')); ?>" class="tm-account-card">
                <div class="tm-account-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div class="tm-account-card__text">
                    <span class="tm-account-card__label">Адреса</span>
                    <span class="tm-account-card__sub">Адреса доставки</span>
                </div>
                <svg class="tm-account-card__arrow" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>

        <div class="uk-width-medium-1-3">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>" class="tm-account-card">
                <div class="tm-account-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div class="tm-account-card__text">
                    <span class="tm-account-card__label">Аккаунт</span>
                    <span class="tm-account-card__sub">Данные профиля</span>
                </div>
                <svg class="tm-account-card__arrow" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>

    </div>

    <?php if ($orders) : ?>
    <div class="tm-account-recent-orders">
        <h3 class="tm-account-recent-orders__title">Последние заказы</h3>
        <table class="tm-account-orders-table">
            <thead>
                <tr>
                    <th>Заказ</th>
                    <th>Дата</th>
                    <th>Статус</th>
                    <th>Сумма</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) : ?>
                <tr>
                    <td>#<?php echo $order->get_order_number(); ?></td>
                    <td><?php echo wc_format_datetime($order->get_date_created()); ?></td>
                    <td><span class="tm-order-status tm-order-status--<?php echo esc_attr($order->get_status()); ?>"><?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></span></td>
                    <td><?php echo $order->get_formatted_order_total(); ?></td>
                    <td><a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="tm-account-orders-table__btn">Просмотр</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="tm-account-all-orders">Все заказы →</a>
    </div>
    <?php endif; ?>

    <?php
    /**
     * Hook: woocommerce_account_dashboard — сохраняем для совместимости с плагинами
     */
    do_action('woocommerce_account_dashboard');
    ?>

</div>
