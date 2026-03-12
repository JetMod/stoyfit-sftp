<?php
/**
 * The Template for displaying product archives
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 * @hooked woocommerce_output_content_wrapper - 10
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');
?>

<header class="woocommerce-products-header">
    <?php
    /**
     * Hook: woocommerce_archive_description.
     * @hooked woocommerce_taxonomy_archive_description - 10
     * @hooked woocommerce_product_archive_description - 10
     */
    do_action('woocommerce_archive_description');
    ?>
</header>

<?php
// ── Собираем контент фильтра ──────────────────────────────────────────────
// Считаем активные атрибуты/цену, чтобы понять есть ли что показать
$tm_active_attrs = tm_filter_get_active_attrs();
$tm_price_range  = tm_filter_get_price_range();
$tm_has_attrs    = !empty(wc_get_attribute_taxonomies());
$tm_has_price    = $tm_price_range['max'] > $tm_price_range['min'];
$tm_has_filters  = $tm_has_attrs || $tm_has_price;

// Количество активных фильтров для бейджа
$tm_active_count = count($tm_active_attrs);
foreach ($tm_active_attrs as $terms) {
    $tm_active_count += count($terms) - 1;
}
if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
    $tm_active_count++;
}
?>

<div class="tm-catalog-layout<?php echo !$tm_has_filters ? ' tm-catalog-no-filters' : ''; ?>">

    <?php if ($tm_has_filters) : ?>
    <!-- ── Мобильная кнопка "Фильтры" ─────────────────────────────────── -->
    <div class="tm-filter-bar">
        <button class="tm-filter-open-btn" id="tm-filter-open" aria-label="Открыть фильтры" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
            Фильтры
            <?php if ($tm_active_count > 0) : ?>
            <span class="tm-filter-active-count" id="tm-filter-count"><?php echo $tm_active_count; ?></span>
            <?php else : ?>
            <span class="tm-filter-active-count" id="tm-filter-count" hidden>0</span>
            <?php endif; ?>
        </button>
        <div class="tm-catalog-found-mobile">
            <?php
            global $wp_query;
            $total = $wp_query->found_posts ?? 0;
            echo '<span id="tm-found-count">' . $total . '</span> '
                . esc_html(_n('товар', 'товаров', $total, 'woocommerce'));
            ?>
        </div>
    </div>

    <!-- ── Оверлей для мобильного дравера ─────────────────────────────── -->
    <div class="tm-filter-overlay" id="tm-filter-overlay" aria-hidden="true"></div>

    <!-- ── Боковая панель фильтров ─────────────────────────────────────── -->
    <aside class="tm-filter-sidebar" id="tm-filter-sidebar" aria-label="Фильтры товаров">

        <div class="tm-filter-sidebar__header">
            <span class="tm-filter-sidebar__title">Фильтры</span>
            <button class="tm-filter-close-btn" id="tm-filter-close" aria-label="Закрыть фильтры">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="tm-filter-sidebar__body">
            <div class="tm-filter" id="tm-filter-widget">
                <?php get_template_part('template-parts/product-filters'); ?>
            </div>
        </div>

        <div class="tm-filter-sidebar__footer">
            <button class="tm-filter-apply-btn" id="tm-filter-apply">
                Показать результаты
            </button>
        </div>

    </aside>
    <?php endif; ?>

    <!-- ── Правая область: товары ──────────────────────────────────────── -->
    <div class="tm-catalog-main">

        <!-- ── Тулбар: счётчик + сортировка ──────────────────────── -->
        <div class="tm-catalog-toolbar" id="tm-catalog-toolbar">
            <div class="tm-catalog-toolbar__count">
                <?php
                global $wp_query;
                $found = $wp_query->found_posts ?? 0;
                echo '<span id="tm-found-count-main">' . esc_html($found) . '</span> '
                    . esc_html(_n('товар', 'товаров', $found, 'woocommerce'));
                ?>
            </div>
            <div class="tm-catalog-toolbar__sort">
                <label for="tm-sort-select" class="tm-catalog-toolbar__sort-label">Сортировка:</label>
                <div class="tm-sort-wrap">
                    <?php
                    $current_sort = isset($_GET['orderby']) ? sanitize_key($_GET['orderby']) : get_option('woocommerce_default_catalog_orderby', 'menu_order');
                    $sort_options = tm_get_sort_options();
                    ?>
                    <select id="tm-sort-select" class="tm-sort-select" aria-label="Сортировка товаров">
                        <?php foreach ($sort_options as $val => $label) : ?>
                        <option value="<?php echo esc_attr($val); ?>"<?php selected($current_sort, $val); ?>>
                            <?php echo esc_html($label); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <svg class="tm-sort-arrow" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
            </div>
        </div>

        <?php if (woocommerce_product_loop()) : ?>

        <?php do_action('woocommerce_before_shop_loop'); ?>

        <!-- AJAX-обновляемый контейнер ─────────────────────────── -->
        <div class="tm-woo-product-wrap" id="tm-products-container">
            <?php
            woocommerce_product_loop_start();

            if (wc_get_loop_prop('total')) {
                while (have_posts()) {
                    the_post();
                    do_action('woocommerce_shop_loop');
                    wc_get_template_part('content', 'product');
                }
            }

            woocommerce_product_loop_end();

            /**
             * Hook: woocommerce_after_shop_loop.
             * @hooked woocommerce_pagination - 10
             */
            do_action('woocommerce_after_shop_loop');
            ?>
        </div>

        <?php else : ?>

        <div class="tm-no-products" id="tm-products-container">
            <?php do_action('woocommerce_no_products_found'); ?>
        </div>

        <?php endif; ?>

    </div><!-- /tm-catalog-main -->

</div><!-- /tm-catalog-layout -->

<?php
/**
 * Hook: woocommerce_after_main_content.
 * @hooked woocommerce_output_content_wrapper_end - 10
 */
do_action('woocommerce_after_main_content');

/**
 * Hook: woocommerce_sidebar.
 * @hooked woocommerce_get_sidebar - 10
 */
do_action('woocommerce_sidebar');

get_footer('shop');
?>
