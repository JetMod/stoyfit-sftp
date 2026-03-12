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

<div class="tm-catalog-layout">

    <!-- ── Мобильная кнопка "Фильтры" ─────────────────────────────────── -->
    <div class="tm-filter-bar uk-hidden-large">
        <button class="tm-filter-open-btn" id="tm-filter-open" aria-label="Открыть фильтры" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
            Фильтры
            <span class="tm-filter-active-count" id="tm-filter-count" hidden>0</span>
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

        <div class="tm-filter-sidebar__header uk-hidden-large">
            <span class="tm-filter-sidebar__title">Фильтры</span>
            <button class="tm-filter-close-btn" id="tm-filter-close" aria-label="Закрыть фильтры">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="tm-filter-sidebar__body">
            <div class="tm-filter" id="tm-filter-widget">
                <?php echo do_shortcode('[fe_widget id="8433"]'); ?>
            </div>

            <?php if (!empty($_GET) && array_filter($_GET, function($k) { return strpos($k, 'filter_') === 0 || in_array($k, array('min_price', 'max_price', 'orderby')); }, ARRAY_FILTER_USE_KEY)) : ?>
            <?php
            $reset_url = function_exists('wc_get_current_product_page_url')
                ? wc_get_current_product_page_url()
                : get_permalink(wc_get_page_id('shop'));
            ?>
            <div class="tm-filter-reset">
                <a href="<?php echo esc_url($reset_url); ?>" class="tm-filter-reset__link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                    Сбросить фильтры
                </a>
            </div>
            <?php endif; ?>
        </div>

        <div class="tm-filter-sidebar__footer uk-hidden-large">
            <button class="tm-filter-apply-btn" id="tm-filter-apply">
                Показать результаты
            </button>
        </div>

    </aside>

    <!-- ── Правая область: товары ──────────────────────────────────────── -->
    <div class="tm-catalog-main">

        <?php if (woocommerce_product_loop()) : ?>

        <?php
        /**
         * Hook: woocommerce_before_shop_loop.
         * @hooked woocommerce_output_all_notices - 10
         * @hooked woocommerce_result_count - 20 (скрыт через functions.php)
         * @hooked woocommerce_catalog_ordering - 30 (скрыт через functions.php)
         */
        do_action('woocommerce_before_shop_loop');
        ?>

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
