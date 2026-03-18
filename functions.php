<?php

/**
 * @package   Uniq
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// check compatibility
if (version_compare(PHP_VERSION, '5.3', '>=')) {

    // bootstrap warp
    require(__DIR__ . '/warp.php');
}

function yoo_theme_scripts()
{
    wp_enqueue_style('yoo-theme-photoswipe', get_stylesheet_directory_uri() . '/css/photoswipe.css');
    wp_enqueue_style('yoo-theme-swiper', get_stylesheet_directory_uri() . '/css/swiper-bundle.min.css');

    wp_enqueue_script('yoo-theme-photoswipe-lightbox', get_template_directory_uri() . '/js/photoswipe-lightbox.umd.min.js', array(), '', true);
    wp_enqueue_script('yoo-theme-photoswipe', get_template_directory_uri() . '/js/photoswipe.umd.min.js', array(), '', true);
    wp_enqueue_script('yoo-theme-swiper', get_template_directory_uri() . '/js/swiper-bundle.min.js', array(), '', true);
    wp_enqueue_script('yoo-theme-jquery', get_template_directory_uri() . '/js/jquery-3.7.1.min.js', array(), '', true);
    wp_enqueue_script('yoo-theme-main', get_template_directory_uri() . '/js/theme.js', array(), '', true);

    // Slick — только на страницах каталога (архив категорий)
    if (is_shop() || is_product_category() || is_product_tag()) {
        wp_enqueue_style('yoo-theme-slick', get_stylesheet_directory_uri() . '/css/slick.css', array(), '', 'all');
        wp_enqueue_script('yoo-theme-slick', get_template_directory_uri() . '/js/slick.min.js', array('yoo-theme-jquery'), '', true);
    }

    // Данные для AJAX-корзины (страница корзины + мини-корзина в шапке)
    if (function_exists('WC')) {
        wp_localize_script('yoo-theme-main', 'tmCartAjax', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('tm_cart_update'),
            'cartUrl' => function_exists('wc_get_cart_url') ? wc_get_cart_url() : '',
        ));
    }

    // Данные для AJAX-фильтра (только в каталоге)
    if (is_shop() || is_product_category() || is_product_tag()) {
        wp_localize_script('yoo-theme-main', 'tmFilterData', array(
            'ajaxUrl'     => admin_url('admin-ajax.php'),
            'nonce'       => wp_create_nonce('tm_filter_nonce'),
            'loadingText' => 'Загружаем…',
            'noResults'   => 'Ничего не найдено',
            'isArchive'   => true,
        ));
    }
}
add_action('wp_enqueue_scripts', 'yoo_theme_scripts');

/**
 * blocks gutenberg
 */
require get_template_directory() . '/inc/block-gutenberg.php';


// acf
if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title'    => 'Theme General Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Works',
        'menu_title'    => 'Works',
        'parent_slug'   => 'theme-general-settings',
    ));
}


//регистрация сайдбаров
function register_headerhour()
{
    register_sidebar(array(
        'name'          => "Header hour",
        'id'            => "headerhour",
        'class'         => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s headerhour">',
        'after_widget'  => "</div>\n",
    ));
}
add_action('widgets_init', 'register_headerhour');




// Remove breadcrumbs from shop & categories
add_filter('woocommerce_before_main_content', 'remove_breadcrumbs');
function remove_breadcrumbs()
{
    if (!is_product()) {
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
    }
}

// удаляем лишние изображения
function true_unset_image_sizes($sizes)
{
    unset($sizes['medium_large']);
    unset($sizes['thumbnail']);
    unset($sizes['medium']);
    unset($sizes['large']);
    unset($sizes['1536x1536']);
    unset($sizes['2048x2048']);
    return $sizes;
}

add_filter('intermediate_image_sizes_advanced', 'true_unset_image_sizes');

// add thumbnails size
add_image_size('custom_thumb', 585, 465, true);
add_image_size('custom_prev', 100, 100, true);
add_image_size('rev_img', 360, 170, true);

// function true_full_unset_image_sizes($sizes)
// {
//     return array();
// }

// add_filter('intermediate_image_sizes_advanced', 'true_full_unset_image_sizes');

// отключаем ревизии
function my_revisions_to_keep($revisions)
{
    return 0;
}
add_filter('wp_revisions_to_keep', 'my_revisions_to_keep');


// удаляем крошки
// add_action( 'init', 'true_woo_no_breadcrumbs' );
// function true_woo_no_breadcrumbs() {

// 	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// }

// change breadcrumb home
add_filter('woocommerce_breadcrumb_defaults', 'true_woo_breadcrumbs_delimiter');

function true_woo_breadcrumbs_delimiter($defaults)
{

    $defaults['delimiter'] = ' > ';
    $defaults['home'] = 'Стройфит';

    return $defaults;
}

// включаем лайтбокс фоток товаров
//add_theme_support( 'wc-product-gallery-lightbox' );

// change thumb for products
add_filter('woocommerce_get_image_size_thumbnail', function ($size) {
    return array(
        'width' => 345,
        'height' => 272,
        'crop' => 1,
    );
});


// Кнопка «В корзину» в loop отключена — вывод кнопки реализован напрямую в content-product.php
// (стандартный хук не нужен, т.к. шаблон кастомный)

// удаляем сортировку
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);


// Текст кнопки WooCommerce «В корзину»
add_filter('woocommerce_product_single_add_to_cart_text', 'tb_woo_custom_cart_button_text');
add_filter('woocommerce_product_add_to_cart_text', 'tb_woo_custom_cart_button_text');
function tb_woo_custom_cart_button_text()
{
    return __('В корзину', 'woocommerce');
}

// После добавления в корзину (редирект) — добавляем параметр для показа уведомления на странице
add_filter('woocommerce_add_to_cart_redirect', 'tm_add_to_cart_redirect_with_param');
function tm_add_to_cart_redirect_with_param($redirect_url)
{
    return add_query_arg('added_to_cart', '1', $redirect_url);
}

// Скрыть стандартное уведомление WooCommerce «Вы отложили X × "..." в свою корзину» — используем свою всплывашку
add_filter('woocommerce_add_to_cart_message', 'tm_remove_add_to_cart_notice');
function tm_remove_add_to_cart_notice($message)
{
    return '';
}

// выводим класс body категории товара в карточке товара
add_filter('body_class', 'new_prodcats');

function new_prodcats($classes)
{
    if (is_singular('product')) {
        $custom_terms = get_the_terms(0, 'product_cat');
        if ($custom_terms) {
            foreach ($custom_terms as $custom_term) {
                $classes[] = 'product_cat_' . $custom_term->slug;
            }
        }
    }
    return $classes;
}

// pagination
$pagin = array(
    'show_all'     => false, // показаны все страницы участвующие в пагинации
    'end_size'     => 1,     // количество страниц на концах
    'mid_size'     => 1,     // количество страниц вокруг текущей
    'prev_next'    => true,  // выводить ли боковые ссылки "предыдущая/следующая страница".
    'prev_text'    => __('«'),
    'next_text'    => __('»'),
    'add_args'     => false, // Массив аргументов (переменных запроса), которые нужно добавить к ссылкам.
    'add_fragment' => '',     // Текст который добавиться ко всем ссылкам.

);
// удаяем h2 из пагинации
add_filter('navigation_markup_template', 'my_navigation_template', 10, 2);
function my_navigation_template($template, $class)
{

    return '
	<div class="navigation %1$s" role="navigation">
		<div class="nav-links">%3$s</div>
	</div>
	';
}



// подкатегории товаров
function mynew_product_subcategories($args = array())
{
    $parentid = get_queried_object_id();
    $args = array(
        'parent' => $parentid,
        'hide_empty' => false
    );
    $terms = get_terms('product_cat', $args);
    if ($terms) {
        echo '<div class="subcategories">
                <div class="tag-slider multiple-items">';
        foreach ($terms as $term) {
            echo '<div>';
            echo '<a href="' .  esc_url(get_term_link($term)) . '" class="subcategory-item">';
            echo $term->name;
            echo '</a>';
            echo '</div>';
        }
        echo '</div>
                <div class="navi">
                    <span class="open">Показать все</span>
                    <span hidden="" class="close">Свернуть</span>
                </div>
            </div>';
    }
}
add_action('woocommerce_before_shop_loop', 'mynew_product_subcategories', 50);


// удаляем лишнее
remove_action('wp_head', 'wp_generator');

// убираем feed
function fb_disable_feed()
{
    wp_redirect(get_option('siteurl'));
}
add_action('do_feed', 'fb_disable_feed', 1);
add_action('do_feed_rdf', 'fb_disable_feed', 1);
add_action('do_feed_rss', 'fb_disable_feed', 1);
add_action('do_feed_rss2', 'fb_disable_feed', 1);
add_action('do_feed_atom', 'fb_disable_feed', 1);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rsd_link');


remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);


/** Disable All WooCommerce  Styles and Scripts Except Shop Pages*/
add_action('wp_enqueue_scripts', 'dequeue_woocommerce_styles_scripts', 99);
function dequeue_woocommerce_styles_scripts()
{
    if (function_exists('is_woocommerce')) {
        if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page() && !is_front_page()) {
            # Styles
            wp_dequeue_style('woocommerce-general');
            wp_dequeue_style('woocommerce-layout');
            wp_dequeue_style('woocommerce-smallscreen');
            wp_dequeue_style('woocommerce_frontend_styles');
            wp_dequeue_style('woocommerce_fancybox_styles');
            wp_dequeue_style('woocommerce_chosen_styles');
            wp_dequeue_style('woocommerce_prettyPhoto_css');
            wp_dequeue_style('wc-blocks-style');
            wp_dequeue_style('wc-blocks-vendors-style');

            # Scripts — wc-cart-fragments оставляем везде для работы мини-корзины
            wp_dequeue_script('wc_price_slider');
            wp_dequeue_script('wc-single-product');
            wp_dequeue_script('wc-add-to-cart');
            wp_dequeue_script('wc-checkout');
            wp_dequeue_script('wc-add-to-cart-variation');
            wp_dequeue_script('wc-cart');
            wp_dequeue_script('wc-chosen');
            wp_dequeue_script('woocommerce');
            wp_dequeue_script('prettyPhoto');
            wp_dequeue_script('prettyPhoto-init');
            wp_dequeue_script('jquery-blockui');
            wp_dequeue_script('jquery-placeholder');
            wp_dequeue_script('fancybox');
            wp_dequeue_script('jqueryui');
        }
    }
}

// ── Согласие на обработку данных (152-ФЗ) ────────────────────────────────────
// Ссылка на пользовательское соглашение (задайте ID страницы или URL)
if (!function_exists('tm_get_terms_url')) {
    function tm_get_terms_url()
    {
        $page_id = get_option('woocommerce_terms_page_id');
        if ($page_id) {
            return get_permalink($page_id);
        }
        return get_privacy_policy_url() ?: '';
    }
}

// Валидация: галочка согласия при входе
add_filter('woocommerce_process_login_errors', 'tm_validate_login_consent', 10, 3);
function tm_validate_login_consent($validation_error, $username, $password)
{
    if (!empty($_POST['login']) && empty($_POST['tm_consent_login'])) {
        if (is_wp_error($validation_error)) {
            $validation_error->add('consent_required', __('Необходимо принять политику конфиденциальности.', 'woocommerce'));
        } else {
            $validation_error = new WP_Error('consent_required', __('Необходимо принять политику конфиденциальности.', 'woocommerce'));
        }
    }
    return $validation_error;
}

// Валидация: галочка согласия при регистрации
add_filter('woocommerce_process_registration_errors', 'tm_validate_registration_consent', 10, 4);
function tm_validate_registration_consent($errors, $username, $password, $email)
{
    if (empty($_POST['tm_consent_register'])) {
        $errors->add('consent_required', __('Необходимо принять условия пользовательского соглашения и дать согласие на обработку персональных данных.', 'woocommerce'));
    }
    return $errors;
}

// Валидация: галочка согласия при оформлении заказа
add_action('woocommerce_checkout_process', 'tm_validate_checkout_consent');
function tm_validate_checkout_consent()
{
    if (empty($_POST['tm_consent_checkout'])) {
        wc_add_notice(__('Необходимо принять условия пользовательского соглашения и дать согласие на обработку персональных данных.', 'woocommerce'), 'error');
    }
}
// ─────────────────────────────────────────────────────────────────────────────

// ── Чекаут: поля, лейблы, локализация ────────────────────────────────────────

// Убираем лишние поля и русифицируем чекаут
add_filter('woocommerce_checkout_fields', 'tm_customize_checkout_fields');
function tm_customize_checkout_fields($fields)
{
    // Billing — убираем неактуальное для России
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_country']);

    // Имя
    $fields['billing']['billing_first_name']['label']       = 'Имя';
    $fields['billing']['billing_first_name']['placeholder'] = 'Ваше имя';
    $fields['billing']['billing_first_name']['class']       = array('form-row-first');
    $fields['billing']['billing_first_name']['priority']    = 10;

    // Фамилия
    $fields['billing']['billing_last_name']['label']       = 'Фамилия';
    $fields['billing']['billing_last_name']['placeholder'] = 'Ваша фамилия';
    $fields['billing']['billing_last_name']['class']       = array('form-row-last');
    $fields['billing']['billing_last_name']['required']    = false;
    $fields['billing']['billing_last_name']['priority']    = 20;

    // Телефон
    $fields['billing']['billing_phone']['label']       = 'Телефон';
    $fields['billing']['billing_phone']['placeholder'] = '+7 (___) ___-__-__';
    $fields['billing']['billing_phone']['class']       = array('form-row-first');
    $fields['billing']['billing_phone']['required']    = true;
    $fields['billing']['billing_phone']['priority']    = 25;

    // Email
    $fields['billing']['billing_email']['label']       = 'Email';
    $fields['billing']['billing_email']['placeholder'] = 'mail@example.com';
    $fields['billing']['billing_email']['class']       = array('form-row-last');
    $fields['billing']['billing_email']['priority']    = 30;

    // Адрес доставки
    $fields['billing']['billing_address_1']['label']       = 'Адрес доставки';
    $fields['billing']['billing_address_1']['placeholder'] = 'Улица, дом, квартира';
    $fields['billing']['billing_address_1']['class']       = array('form-row-wide');
    $fields['billing']['billing_address_1']['required']    = false;
    $fields['billing']['billing_address_1']['priority']    = 40;

    // Город
    $fields['billing']['billing_city']['label']       = 'Город';
    $fields['billing']['billing_city']['placeholder'] = 'Ваш город';
    $fields['billing']['billing_city']['class']       = array('form-row-wide');
    $fields['billing']['billing_city']['required']    = false;
    $fields['billing']['billing_city']['priority']    = 45;

    // Комментарий к заказу
    if (isset($fields['order']['order_comments'])) {
        $fields['order']['order_comments']['label']       = 'Комментарий к заказу';
        $fields['order']['order_comments']['placeholder'] = 'Пожелания по доставке, удобное время, дополнительная информация…';
        $fields['order']['order_comments']['class']       = array('notes');
        $fields['order']['order_comments']['rows']        = 3;
    }

    return $fields;
}

// Страна по умолчанию — Россия
add_filter('default_checkout_billing_country', function () { return 'RU'; });
add_filter('default_checkout_shipping_country', function () { return 'RU'; });

// Кнопка «Оформить заказ»
add_filter('woocommerce_order_button_text', function () { return 'Оформить заказ'; });

// Лейблы методов доставки (дополнение к настройкам в админке)
add_filter('woocommerce_cart_shipping_method_full_label', 'tm_shipping_method_label', 10, 2);
function tm_shipping_method_label($label, $method)
{
    return $label;
}

// Локализация WooCommerce-лейблов (только для домена woocommerce)
// Не используем is_checkout() — gettext-фильтр срабатывает до инициализации WC
add_filter('gettext', 'tm_checkout_gettext', 20, 3);
function tm_checkout_gettext($translated, $text, $domain)
{
    // Быстрый выход для любого другого домена
    if ('woocommerce' !== $domain) {
        return $translated;
    }
    static $strings = null;
    if (null === $strings) {
        $strings = array(
            'Billing details'         => 'Ваши данные',
            'Additional information'  => 'Дополнительно',
            'Your order'              => 'Ваш заказ',
            'Place order'             => 'Оформить заказ',
            'Payment'                 => 'Способ оплаты',
            'Product'                 => 'Товар',
            'Subtotal'                => 'Подытог',
            'Total'                   => 'Итого',
            'Shipping'                => 'Доставка',
            'Cash on delivery'        => 'Оплата при получении',
            'Direct bank transfer'    => 'Банковский перевод',
            'I have read and agree to the website %s' => 'Я прочитал(а) и согласен(на) с %s',
            'terms and conditions'    => 'условиями использования',
            'required'                => 'обязательно',
        );
    }
    return isset($strings[$text]) ? $strings[$text] : $translated;
}

// ─────────────────────────────────────────────────────────────────────────────

// Русские метки навигации в личном кабинете (пункт «Загрузки» скрыт — для цифровых товаров не используется)
add_filter('woocommerce_account_menu_items', 'tm_account_menu_items');
function tm_account_menu_items($items)
{
    return array(
        'dashboard'       => 'Главная',
        'orders'          => 'Мои заказы',
        'edit-address'    => 'Адреса доставки',
        'edit-account'    => 'Данные аккаунта',
        'customer-logout' => 'Выйти',
    );
}

// Заголовки эндпоинтов аккаунта на русском
add_filter('woocommerce_endpoint_orders_title', function() { return 'Мои заказы'; });
add_filter('woocommerce_endpoint_edit-address_title', function() { return 'Адреса доставки'; });
add_filter('woocommerce_endpoint_edit-account_title', function() { return 'Данные аккаунта'; });
add_filter('woocommerce_endpoint_customer-logout_title', function() { return 'Выйти'; });

// Регистрация: показывать поле пароля, чтобы пользователь задал его сам (а не по email)
add_filter('option_woocommerce_registration_generate_password', function() { return 'no'; });

// AJAX-обновление корзины без перезагрузки
add_action('wp_ajax_tm_update_cart', 'tm_ajax_update_cart');
add_action('wp_ajax_nopriv_tm_update_cart', 'tm_ajax_update_cart');
function tm_ajax_update_cart()
{
    check_ajax_referer('tm_cart_update', 'nonce');
    if (!function_exists('WC') || !WC()->cart) {
        wp_send_json_error(array('message' => 'Корзина недоступна'));
    }
    if (!empty($_POST['cart']) && is_array($_POST['cart'])) {
        foreach ($_POST['cart'] as $cart_item_key => $values) {
            $qty = isset($values['qty']) ? wc_stock_amount(wp_unslash($values['qty'])) : 0;
            WC()->cart->set_quantity($cart_item_key, $qty, true);
        }
        WC()->cart->calculate_totals();
    }
    ob_start();
    wc_print_notices();
    $notices = ob_get_clean();
    ob_start();
    echo do_shortcode('[woocommerce_cart]');
    $cart_html = ob_get_clean();
    $count     = WC()->cart->get_cart_contents_count();
    ob_start();
    woocommerce_mini_cart();
    $mini_cart = ob_get_clean();
    wp_send_json_success(array(
        'cartHtml'   => $cart_html,
        'notices'    => $notices,
        'count'      => $count,
        'countHtml'  => $count > 0 ? (string) $count : '',
        'miniCartHtml' => $mini_cart,
    ));
}

// AJAX: изменение количества одного товара в мини-корзине (+/-)
add_action('wp_ajax_tm_update_mini_cart_item', 'tm_ajax_update_mini_cart_item');
add_action('wp_ajax_nopriv_tm_update_mini_cart_item', 'tm_ajax_update_mini_cart_item');
function tm_ajax_update_mini_cart_item()
{
    check_ajax_referer('tm_cart_update', 'nonce');
    if (!function_exists('WC') || !WC()->cart) {
        wp_send_json_error(array('message' => 'Корзина недоступна'));
    }
    $cart_item_key = isset($_POST['cart_item_key']) ? sanitize_text_field(wp_unslash($_POST['cart_item_key'])) : '';
    $qty = isset($_POST['qty']) ? wc_stock_amount(wp_unslash($_POST['qty'])) : 0;
    if ($cart_item_key === '' || !isset(WC()->cart->get_cart()[ $cart_item_key ])) {
        wp_send_json_error(array('message' => 'Товар не найден в корзине'));
    }
    WC()->cart->set_quantity($cart_item_key, $qty, true);
    WC()->cart->calculate_totals();
    $count = WC()->cart->get_cart_contents_count();
    ob_start();
    woocommerce_mini_cart();
    $mini_cart = ob_get_clean();
    wp_send_json_success(array(
        'miniCartHtml' => $mini_cart,
        'count'        => $count,
        'countHtml'    => $count > 0 ? (string) $count : '',
    ));
}

// Фикс дублирования товаров в review-order на странице чекаута.
// WooCommerce по умолчанию заменяет только <table.woocommerce-checkout-review-order-table>,
// но наш шаблон выводит товары ВНЕ этой таблицы (в .tm-checkout-items).
// Из-за этого при AJAX-обновлении чекаута таблица заменялась на весь шаблон (товары + таблица),
// и товары появлялись дважды. Решение — заменять весь внешний контейнер .tm-checkout-order-review.
add_filter('woocommerce_update_order_review_fragments', 'tm_fix_order_review_fragments');
function tm_fix_order_review_fragments($fragments)
{
    ob_start();
    woocommerce_order_review();
    $html = ob_get_clean();

    unset($fragments['.woocommerce-checkout-review-order-table']);
    $fragments['.tm-checkout-order-review'] = $html;

    return $fragments;
}

// AJAX-фрагменты для счётчика и мини-корзины в шапке
add_filter('woocommerce_add_to_cart_fragments', 'tm_cart_fragments');
function tm_cart_fragments($fragments)
{
    $count = WC()->cart->get_cart_contents_count();

    $fragments['span.tm-cart-count'] = '<span class="tm-cart-count" data-count="' . $count . '">'
        . ($count > 0 ? $count : '') . '</span>';

    ob_start();
    woocommerce_mini_cart();
    $fragments['div.tm-mini-cart__content'] = '<div class="tm-mini-cart__content">' . ob_get_clean() . '</div>';

    return $fragments;
}


// work gallery
function works_gallery()
{
    ob_start();

    $works_gallery = get_field('works_gallery', 'option');
?>

    <div class="tm-works-slider-wrapper">

        <div class="tm-works-slider swiper">

            <div class="swiper-wrapper">
                <?php foreach ($works_gallery as $image) : ?>
                    <div class="swiper-slide">
                        <div class="tm-works-slider__image">
                            <a href="<?php echo esc_url($image['url']); ?>" data-pswp-width="<?php echo esc_attr($image['width']); ?>" data-pswp-height="<?php echo esc_attr($image['height']); ?>">
                                <img src="<?php echo esc_url($image['sizes']['custom_thumb']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="tm-works-slider_prev swiper-button swiper-button-prev"></div>
            <div class="tm-works-slider_next swiper-button swiper-button-next"></div>
        </div>

    </div>

<?php
    return ob_get_clean();
}
add_shortcode('works_gallery', 'works_gallery');


// delete scripts and style
function remove_assets()
{
    wp_deregister_style('classic-theme-styles');
    wp_dequeue_style('classic-theme-styles');

    wp_deregister_style('photoswipe');
	wp_dequeue_style('photoswipe');
    wp_deregister_style('photoswipe-default-skin');
	wp_dequeue_style('photoswipe-default-skin');


	wp_deregister_script('photoswipe');
	wp_dequeue_script('photoswipe');
    wp_deregister_script('photoswipe-ui-default');
	wp_dequeue_script('photoswipe-ui-default');

}
add_action('wp_enqueue_scripts', 'remove_assets', 11);




// shortcode for custom small image review
function custom_image_shortcode_rev($atts)
{
    extract( shortcode_atts( array (
        'id'=> '',
        'alt' => '',
    ), $atts ) );

    $alt_text = esc_attr($alt);

    $image = wp_get_attachment_image($id, 'rev_img', false, array('alt' => $alt_text));

    return $image;
}
add_shortcode('custom_rev_image', 'custom_image_shortcode_rev');


// shortcode for custom small image projects
function custom_image_shortcode_projects($atts)
{
    extract( shortcode_atts( array (
        'id'=> '',
        'alt' => '',
    ), $atts ) );

    $alt_text = esc_attr($alt);
   
    $image = wp_get_attachment_image($id, 'custom_thumb', false, array('alt' => $alt_text));

    return $image;
}
add_shortcode('custom_projects_image', 'custom_image_shortcode_projects');


// ── Встроенный фильтр товаров: вспомогательные функции ───────────────────────

/**
 * Возвращает ЧИСТЫЙ URL текущей страницы БЕЗ каких-либо GET-параметров.
 *
 * ВАЖНО: нельзя использовать wc_get_current_product_page_url() /
 * get_pagenum_link() — они сохраняют существующие query-params,
 * что приводит к двойному '?' при построении URL фильтра.
 */
function tm_filter_base_url()
{
    // Страница магазина
    if (function_exists('is_shop') && is_shop()) {
        return wc_get_page_permalink('shop');
    }

    // Категория товаров
    if (function_exists('is_product_category') && is_product_category()) {
        $obj = get_queried_object();
        if ($obj && !is_wp_error($obj)) {
            $url = get_term_link($obj);
            return is_wp_error($url) ? home_url('/') : $url;
        }
    }

    // Тег товаров
    if (function_exists('is_product_tag') && is_product_tag()) {
        $obj = get_queried_object();
        if ($obj && !is_wp_error($obj)) {
            $url = get_term_link($obj);
            return is_wp_error($url) ? home_url('/') : $url;
        }
    }

    // Fallback: берём путь из запроса, без query-строки
    global $wp;
    return home_url(trailingslashit($wp->request));
}

/**
 * Строит URL, убирая из текущего запроса указанные параметры.
 */
function tm_filter_remove_params(array $remove_keys)
{
    $params = $_GET;
    foreach ($remove_keys as $k) {
        unset($params[$k]);
    }
    $base = tm_filter_base_url();
    return $base . (!empty($params) ? '?' . http_build_query($params) : '');
}

/**
 * Строит URL, переключая один терм атрибута (добавляет если нет, убирает если есть).
 * Сохраняет диапазон цен и остальные атрибуты.
 */
function tm_filter_toggle_term($taxonomy, $term_slug)
{
    $params     = $_GET;
    $filter_key = 'filter_' . $taxonomy;
    $current    = isset($params[$filter_key]) && $params[$filter_key] !== ''
        ? array_map('sanitize_title', explode(',', $params[$filter_key]))
        : array();

    if (in_array($term_slug, $current)) {
        $current = array_values(array_filter($current, function ($t) use ($term_slug) {
            return $t !== $term_slug;
        }));
    } else {
        $current[] = $term_slug;
    }

    if (empty($current)) {
        unset($params[$filter_key]);
    } else {
        $params[$filter_key] = implode(',', $current);
    }

    // Убираем пагинацию при смене фильтра
    unset($params['paged']);

    $base = tm_filter_base_url();
    return $base . (!empty($params) ? '?' . http_build_query($params) : '');
}

/**
 * Получает активные атрибутные фильтры из URL.
 * Возвращает: array[ 'taxonomy' => ['slug1', 'slug2'], ... ]
 */
function tm_filter_get_active_attrs()
{
    $active = array();
    foreach ($_GET as $key => $value) {
        if (strpos($key, 'filter_') === 0 && !empty($value)) {
            $taxonomy          = substr($key, 7); // убираем 'filter_'
            $active[$taxonomy] = array_map('sanitize_title', explode(',', $value));
        }
    }
    return $active;
}

/**
 * Возвращает IDs опубликованных товаров для текущего контекста страницы.
 *
 * - На странице категории: только товары из этой категории
 * - На странице тега: только товары с этим тегом
 * - На главной странице магазина: null (все товары)
 *
 * Результат кэшируется на 1 час.
 */
function tm_filter_get_context_product_ids()
{
    // На главной странице магазина — контекст не нужен
    if (function_exists('is_shop') && is_shop()) {
        return null;
    }

    $obj = get_queried_object();
    if (!$obj || !isset($obj->term_id, $obj->taxonomy)) {
        return null;
    }

    $cache_key = 'tm_ctx_ids_' . $obj->term_id;
    $cached    = wp_cache_get($cache_key, 'tm_product_filters');
    if (false !== $cached) {
        return $cached;
    }

    global $wpdb;

    // Получаем IDs товаров в текущей категории/теге через прямой SQL (быстро)
    $ids = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT DISTINCT tr.object_id
             FROM {$wpdb->term_relationships} tr
             INNER JOIN {$wpdb->term_taxonomy} tt
                 ON tr.term_taxonomy_id = tt.term_taxonomy_id
             INNER JOIN {$wpdb->posts} p
                 ON tr.object_id = p.ID
             WHERE tt.term_id  = %d
               AND tt.taxonomy = %s
               AND p.post_status = 'publish'
               AND p.post_type   = 'product'",
            $obj->term_id,
            $obj->taxonomy
        )
    );

    // Если категория пуста — пустой массив (но не null, чтобы не показывать ничего)
    $result = !empty($ids) ? array_map('intval', $ids) : array(0);

    wp_cache_set($cache_key, $result, 'tm_product_filters', HOUR_IN_SECONDS);

    return $result;
}

/**
 * Получает диапазон цен для опубликованных товаров.
 * Если переданы $product_ids — только для этих товаров (контекст категории).
 * Возвращает ['min' => float, 'max' => float].
 */
function tm_filter_get_price_range($product_ids = null)
{
    global $wpdb;

    $where_ids = '';
    if (!empty($product_ids) && is_array($product_ids)) {
        $ids_list  = implode(',', array_map('intval', $product_ids));
        $where_ids = " AND p.ID IN ({$ids_list})";
    }

    $row = $wpdb->get_row(
        "SELECT
            MIN(CAST(pm.meta_value AS DECIMAL(10,2))) AS min_price,
            MAX(CAST(pm.meta_value AS DECIMAL(10,2))) AS max_price
         FROM {$wpdb->postmeta} pm
         INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE pm.meta_key = '_price'
           AND pm.meta_value != ''
           AND p.post_status = 'publish'
           AND p.post_type   = 'product'
           {$where_ids}"
    );

    return array(
        'min' => $row && $row->min_price !== null ? floor(floatval($row->min_price)) : 0,
        'max' => $row && $row->max_price !== null ? ceil(floatval($row->max_price))  : 100000,
    );
}
// ─────────────────────────────────────────────────────────────────────────────

// ── Фильтр: цвет и сортировка ─────────────────────────────────────────────────

/**
 * Определяет, является ли атрибут «цветовым» по его названию.
 */
function tm_is_color_attribute($label, $slug)
{
    $s = mb_strtolower($label . ' ' . $slug, 'UTF-8');
    foreach (array('цвет', 'color', 'colour', 'краска', 'оттен') as $kw) {
        if (mb_strpos($s, $kw, 0, 'UTF-8') !== false) return true;
    }
    return false;
}

/**
 * Возвращает HEX-цвет для термина.
 * Сначала term_meta 'tm_color', затем словарь русских названий.
 */
function tm_get_term_color_hex($term_name, $term_id = 0)
{
    if ($term_id) {
        $custom = get_term_meta($term_id, 'tm_color', true);
        if ($custom) return $custom;
    }
    static $map = null;
    if (null === $map) {
        $map = array(
            'белый' => '#f5f5f5', 'бел' => '#f5f5f5',
            'черный' => '#1c1c1c', 'черн' => '#1c1c1c',
            'красный' => '#cc2222', 'красн' => '#cc2222',
            'зеленый' => '#4CAF50', 'зелен' => '#4CAF50',
            'синий' => '#1565C0', 'голубой' => '#29B6F6',
            'желтый' => '#FDD835', 'желт' => '#FDD835',
            'оранжевый' => '#FF6F00', 'оранж' => '#FF6F00',
            'серый' => '#9E9E9E', 'антрацит' => '#455A64',
            'темно-серый' => '#424242', 'светло-серый' => '#BDBDBD',
            'коричневый' => '#795548', 'коричн' => '#795548',
            'каштан' => '#954535',
            'бежевый' => '#D2B48C', 'беж' => '#D2B48C',
            'песок' => '#C2B280', 'терракот' => '#CC4E14',
            'кирпич' => '#B94A2C', 'розовый' => '#EC407A',
            'фиолетовый' => '#7B1FA2', 'лайм' => '#C5E01E',
            'лимон' => '#F9FF26', 'мокрый асфальт' => '#607D8B',
        );
    }
    $n = mb_strtolower(trim($term_name), 'UTF-8');
    foreach ($map as $kw => $hex) {
        if (mb_strpos($n, $kw, 0, 'UTF-8') !== false) return $hex;
    }
    return null;
}

/**
 * Опции сортировки каталога.
 */
function tm_get_sort_options()
{
    return array(
        'menu_order' => 'По умолчанию',
        'popularity' => 'По популярности',
        'date'       => 'Сначала новые',
        'price'      => 'Цена ↑',
        'price-desc' => 'Цена ↓',
    );
}
// ──────────────────────────────────────────────────────────────────────────────

// ── Принудительное применение фильтров к WooCommerce-запросу ─────────────────
//
// WooCommerce обрабатывает filter_pa_XXX только когда активен LayeredNav-виджет.
// Этот хук применяет фильтры явно — как независимый уровень поверх WC.
//
add_action('pre_get_posts', 'tm_apply_product_filters', 30);
function tm_apply_product_filters($query)
{
    if (
        is_admin()
        || !$query->is_main_query()
        || !(
            (function_exists('is_shop')             && is_shop())             ||
            (function_exists('is_product_category') && is_product_category()) ||
            (function_exists('is_product_tag')      && is_product_tag())
        )
    ) {
        return;
    }

    // ── Атрибутные фильтры: filter_pa_XXX=slug1,slug2 ───────────────────────
    $new_tax = array();

    foreach ($_GET as $key => $raw_value) {
        if (strpos($key, 'filter_') !== 0 || '' === trim($raw_value)) {
            continue;
        }

        $taxonomy = substr($key, 7);

        if (!taxonomy_exists($taxonomy)) {
            continue;
        }

        $terms = array_values(array_filter(
            array_map('sanitize_title', explode(',', $raw_value))
        ));

        if (empty($terms)) {
            continue;
        }

        $query_type = isset($_GET['query_type_' . $taxonomy])
            ? sanitize_key($_GET['query_type_' . $taxonomy])
            : 'or';

        $new_tax[] = array(
            'taxonomy' => $taxonomy,
            'field'    => 'slug',
            'terms'    => $terms,
            'operator' => 'and' === $query_type ? 'AND' : 'IN',
        );
    }

    if (!empty($new_tax)) {
        $existing = $query->get('tax_query');
        if (!is_array($existing)) {
            $existing = array();
        }
        // relation AND — все выбранные атрибуты должны совпасть
        $combined               = array_merge($existing, $new_tax);
        $combined['relation']   = 'AND';
        $query->set('tax_query', $combined);
    }

    // ── Фильтр цены: min_price / max_price ──────────────────────────────────
    $min = isset($_GET['min_price']) && is_numeric($_GET['min_price'])
        ? floatval($_GET['min_price']) : null;
    $max = isset($_GET['max_price']) && is_numeric($_GET['max_price'])
        ? floatval($_GET['max_price']) : null;

    if ($min !== null || $max !== null) {
        $meta_q = $query->get('meta_query') ?: array();

        if ($min !== null && $max !== null) {
            $meta_q[] = array(
                'key'     => '_price',
                'value'   => array($min, $max),
                'compare' => 'BETWEEN',
                'type'    => 'DECIMAL(10,2)',
            );
        } elseif ($min !== null) {
            $meta_q[] = array('key' => '_price', 'value' => $min, 'compare' => '>=', 'type' => 'DECIMAL(10,2)');
        } else {
            $meta_q[] = array('key' => '_price', 'value' => $max, 'compare' => '<=', 'type' => 'DECIMAL(10,2)');
        }

        $query->set('meta_query', $meta_q);
    }
}
// ─────────────────────────────────────────────────────────────────────────────


// ── Шорткод [featured_products_block] ────────────────────────────────────────
//
// Примеры использования:
//   [featured_products_block]
//   [featured_products_block tag="recommended" title="Рекомендуем" limit="8"]
//   [featured_products_block category="nalivnoj-pol" title="Наливные полы" link="/catalog/nalivnoj-pol/"]
//   [featured_products_block acf="is_recommended" title="Лучшие товары"]
//   [featured_products_block ids="12,34,56" title="Хиты продаж" orderby="post__in"]
//   [featured_products_block tag="sale" title="Акции" link="/shop/" link_text="Все акции"]
//
// ── Шорткод [application_products] — товары из WooCommerce в стиле tm-application-goods ──
// Замена Widgetkit с ручным HTML. Товары и цены подтягиваются из каталога.
// Примеры: [application_products ids="12,34,56" title="Детские площадки"]
//          [application_products category="besshovnoe-pokrytie" limit="5" title="Бесшовные покрытия"]
//          [application_products tag="kids" limit="8"]
add_shortcode('application_products', 'tm_application_products_shortcode');
function tm_application_products_shortcode($atts)
{
    if (is_admin() && !wp_doing_ajax()) {
        return '';
    }
    if (!function_exists('wc_get_product')) {
        return '';
    }
    $atts = shortcode_atts(
        array(
            'title'     => '',
            'link'      => '',
            'link_text' => 'Смотреть все',
            'ids'       => '',
            'category'  => '',
            'tag'       => '',
            'limit'     => 10,
            'orderby'   => 'menu_order',
            'order'     => 'ASC',
            'class'     => '',
        ),
        $atts,
        'application_products'
    );
    ob_start();
    $args = $atts; // передаём параметры в шаблон
    include get_template_directory() . '/template-parts/application-products.php';
    return ob_get_clean();
}
// ─────────────────────────────────────────────────────────────────────────────

add_shortcode('featured_products_block', 'tm_featured_products_shortcode');
function tm_featured_products_shortcode($atts)
{
    // Не выводим в админке (для Gutenberg-редактора)
    if (is_admin() && !wp_doing_ajax()) {
        return '';
    }

    $atts = shortcode_atts(
        array(
            'title'     => 'Рекомендуем посмотреть',
            'link'      => '',
            'link_text' => 'Смотреть все',
            'tag'       => 'recommended',
            'category'  => '',
            'ids'       => '',
            'acf'       => '',
            'limit'     => 10,
            'orderby'   => 'rand',
            'order'     => 'DESC',
            'class'     => '',
        ),
        $atts,
        'featured_products_block'
    );

    // Нужен WooCommerce
    if (!function_exists('wc_get_product')) {
        return '';
    }

    ob_start();
    $args = $atts;
    include get_template_directory() . '/template-parts/featured-products.php';
    return ob_get_clean();
}
// ─────────────────────────────────────────────────────────────────────────────

// Создаём тег «recommended» для блока «Рекомендуем посмотреть», если его ещё нет
add_action('init', 'tm_ensure_recommended_tag', 20);
function tm_ensure_recommended_tag()
{
    if (!taxonomy_exists('product_tag')) {
        return;
    }
    $slug = 'recommended';
    if (term_exists($slug, 'product_tag')) {
        return;
    }
    wp_insert_term(
        'Рекомендуем',
        'product_tag',
        array('slug' => $slug)
    );
}
// ─────────────────────────────────────────────────────────────────────────────

// adding new canonical for categories
function replace_canonical_link() {
    if (is_product_category()) {
        global $wp_query;
        
        if (is_product_category() && $wp_query->max_num_pages > 1) {
           
            add_action('wp_head', 'custom_canonical_link', 999); 
        }
    }
}

function custom_canonical_link() {
    echo '<link rel="canonical" href="' . get_pagenum_link(1) . '" />' . "\n";
}

add_action('wp_head', 'replace_canonical_link');

// Disable Yoast's canonical link for pages starting from the second page
function modify_yoast_canonical($canonical) {
    global $wp_query;

    if (is_product_category() && $wp_query->max_num_pages > 1) {
        
        return '';
    }

    return $canonical;
}

add_filter('wpseo_canonical', 'modify_yoast_canonical');

add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);
function change_existing_currency_symbol( $currency_symbol, $currency ) {
     switch( $currency ) {
          case 'RUB': $currency_symbol = ' руб.'; break;
     }
     return $currency_symbol;
}
add_filter( 'woocommerce_get_price_html', 'custom_price_html', 100, 2 );
function custom_price_html( $price, $product ){
    $meta_values = get_post_meta( get_the_ID(), 'unit',true );
 
  $price = $price .'/'. $meta_values;

    return apply_filters( 'woocommerce_get_price', $price );
}

// ── Всплывающее уведомление о cookies ─────────────────────────────────────────
add_action('wp_footer', 'tm_cookie_consent_banner');
function tm_cookie_consent_banner()
{
    if (is_admin()) {
        return;
    }
    $privacy_url = get_privacy_policy_url();
    $privacy_link = $privacy_url
        ? '<a href="' . esc_url($privacy_url) . '" class="tm-cookie-banner__link">политикой конфиденциальности</a>'
        : 'политикой конфиденциальности';
    ?>
    <div id="tm-cookie-banner" class="tm-cookie-banner" role="dialog" aria-label="Уведомление о cookies" aria-hidden="true">
        <div class="tm-cookie-banner__inner">
            <p class="tm-cookie-banner__text">
                Мы используем файлы cookie для улучшения работы сайта и анализа трафика. Продолжая использовать сайт, вы соглашаетесь с нашей <?php echo $privacy_link; ?>.
            </p>
            <button type="button" class="tm-cookie-banner__btn" id="tm-cookie-accept" aria-label="Принять">Принять</button>
        </div>
    </div>
    <script>
    (function() {
        var key = 'tm_cookie_consent';
        if (localStorage.getItem(key) === 'accepted') return;
        var banner = document.getElementById('tm-cookie-banner');
        if (!banner) return;
        banner.classList.add('tm-cookie-banner--visible');
        banner.setAttribute('aria-hidden', 'false');
        document.getElementById('tm-cookie-accept').addEventListener('click', function() {
            localStorage.setItem(key, 'accepted');
            banner.classList.remove('tm-cookie-banner--visible');
            banner.setAttribute('aria-hidden', 'true');
        });
    })();
    </script>
    <?php
}




