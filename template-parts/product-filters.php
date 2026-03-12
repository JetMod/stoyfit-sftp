<?php
/**
 * Встроенный фильтр товаров
 *
 * Отображает:
 *  - Активные фильтры (чипы с кнопкой удаления)
 *  - Диапазон цены (двойной ползунок)
 *  - Все зарегистрированные атрибуты товаров (аккордеоны с чекбоксами)
 *
 * Работает через стандартные WooCommerce URL-параметры:
 *   min_price, max_price, filter_pa_XXX=slug1,slug2
 */

defined('ABSPATH') || exit;

// ── Данные ───────────────────────────────────────────────────────────────────

$base_url            = tm_filter_base_url();
$active_attrs        = tm_filter_get_active_attrs();

// ID товаров в текущей категории/теге — чтобы показывать
// только те атрибуты и цены, которые РЕАЛЬНО есть в этом разделе.
// null = все товары (главная страница магазина)
$context_product_ids = tm_filter_get_context_product_ids();

// Диапазон цен — только для товаров текущего раздела
$price_range         = tm_filter_get_price_range($context_product_ids);

$price_min_global = $price_range['min'];
$price_max_global = $price_range['max'];

$price_min_cur = isset($_GET['min_price']) && $_GET['min_price'] !== ''
    ? intval($_GET['min_price'])
    : $price_min_global;

$price_max_cur = isset($_GET['max_price']) && $_GET['max_price'] !== ''
    ? intval($_GET['max_price'])
    : $price_max_global;

$has_price_filter = isset($_GET['min_price']) || isset($_GET['max_price']);
$has_active       = $has_price_filter || !empty($active_attrs);

// Атрибуты товаров
$attribute_taxonomies = function_exists('wc_get_attribute_taxonomies')
    ? wc_get_attribute_taxonomies()
    : array();

// ── Разметка ─────────────────────────────────────────────────────────────────
?>

<div class="tm-product-filter">

    <!-- ── Активные фильтры ─────────────────────────────────────────── -->
    <?php if ($has_active) : ?>
    <div class="tm-pf-active">
        <div class="tm-pf-active__chips">

            <?php if ($has_price_filter) : ?>
            <a href="<?php echo esc_url(tm_filter_remove_params(array('min_price', 'max_price'))); ?>"
               class="tm-pf-chip tm-pf-chip--price">
                <?php echo number_format($price_min_cur, 0, '.', ' '); ?> —
                <?php echo number_format($price_max_cur, 0, '.', ' '); ?> руб.
                <span class="tm-pf-chip__x" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </span>
            </a>
            <?php endif; ?>

            <?php foreach ($active_attrs as $taxonomy => $slugs) :
                foreach ($slugs as $slug) :
                    $term = get_term_by('slug', $slug, $taxonomy);
                    if (!$term) continue;
                    $remove_url = tm_filter_toggle_term($taxonomy, $slug);
            ?>
            <a href="<?php echo esc_url($remove_url); ?>" class="tm-pf-chip">
                <?php echo esc_html($term->name); ?>
                <span class="tm-pf-chip__x" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </span>
            </a>
            <?php endforeach; endforeach; ?>

            <a href="<?php echo esc_url($base_url); ?>" class="tm-pf-chip tm-pf-chip--reset">
                Сбросить всё
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── Форма: цена + атрибуты ───────────────────────────────────── -->
    <form id="tm-filter-form" method="get" action="<?php echo esc_url($base_url); ?>">

        <!-- Сохраняем текущие атрибутные фильтры как hidden (при submit цены) -->
        <?php foreach ($active_attrs as $taxonomy => $slugs) : ?>
        <input type="hidden"
               name="filter_<?php echo esc_attr($taxonomy); ?>"
               value="<?php echo esc_attr(implode(',', $slugs)); ?>"
               class="tm-pf-attr-preserve">
        <?php endforeach; ?>
        <?php if (!empty($_GET['orderby'])) : ?>
        <input type="hidden" name="orderby" value="<?php echo esc_attr($_GET['orderby']); ?>">
        <?php endif; ?>

        <!-- ── Цена ─────────────────────────────────────────────── -->
        <?php if ($price_max_global > $price_min_global) : ?>
        <div class="tm-pf-group tm-pf-group--open" id="tm-pf-price-group">

            <button type="button" class="tm-pf-group__head" aria-expanded="true">
                <span class="tm-pf-group__name">Цена</span>
                <?php if ($has_price_filter) : ?>
                <span class="tm-pf-group__badge">●</span>
                <?php endif; ?>
                <svg class="tm-pf-group__arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
            </button>

            <div class="tm-pf-group__body">
                <div class="tm-price-slider" id="tm-price-slider"
                     data-min="<?php echo esc_attr($price_min_global); ?>"
                     data-max="<?php echo esc_attr($price_max_global); ?>">

                    <!-- Двойной range-слайдер -->
                    <div class="tm-price-slider__track">
                        <div class="tm-price-slider__fill" id="tm-price-fill"></div>
                    </div>
                    <input type="range" id="tm-price-min-r" aria-label="Минимальная цена"
                           class="tm-price-slider__range tm-price-slider__range--min"
                           min="<?php echo esc_attr($price_min_global); ?>"
                           max="<?php echo esc_attr($price_max_global); ?>"
                           value="<?php echo esc_attr($price_min_cur); ?>" step="10">
                    <input type="range" id="tm-price-max-r" aria-label="Максимальная цена"
                           class="tm-price-slider__range tm-price-slider__range--max"
                           min="<?php echo esc_attr($price_min_global); ?>"
                           max="<?php echo esc_attr($price_max_global); ?>"
                           value="<?php echo esc_attr($price_max_cur); ?>" step="10">
                </div>

                <div class="tm-price-inputs">
                    <div class="tm-price-input-wrap">
                        <span class="tm-price-input-wrap__label">от</span>
                        <input type="number" name="min_price" id="tm-price-min-n"
                               class="tm-price-input"
                               value="<?php echo esc_attr($price_min_cur); ?>"
                               min="<?php echo esc_attr($price_min_global); ?>"
                               max="<?php echo esc_attr($price_max_global); ?>">
                        <span class="tm-price-input-wrap__unit">руб.</span>
                    </div>
                    <span class="tm-price-inputs__sep">—</span>
                    <div class="tm-price-input-wrap">
                        <span class="tm-price-input-wrap__label">до</span>
                        <input type="number" name="max_price" id="tm-price-max-n"
                               class="tm-price-input"
                               value="<?php echo esc_attr($price_max_cur); ?>"
                               min="<?php echo esc_attr($price_min_global); ?>"
                               max="<?php echo esc_attr($price_max_global); ?>">
                        <span class="tm-price-input-wrap__unit">руб.</span>
                    </div>
                </div>

                <button type="submit" class="tm-price-apply">Применить</button>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── Атрибуты товаров ──────────────────────────────────── -->
        <?php
        $SHOW_LIMIT = 7; // Показывать N опций, остальные скрыты под «Показать ещё»
        $SEARCH_MIN = 10; // Начинать показывать поиск если ≥ N опций
        ?>
        <?php foreach ($attribute_taxonomies as $attr) :
            $taxonomy    = wc_attribute_taxonomy_name($attr->attribute_name);
            $is_color    = tm_is_color_attribute($attr->attribute_label, $attr->attribute_name);

            $terms_args = array(
                'taxonomy'   => $taxonomy,
                'hide_empty' => true,
                'orderby'    => 'name',
                'order'      => 'ASC',
            );
            if (!is_null($context_product_ids)) {
                $terms_args['object_ids'] = $context_product_ids;
            }

            $terms = get_terms($terms_args);
            if (empty($terms) || is_wp_error($terms)) continue;

            // Натуральная сортировка
            usort($terms, function ($a, $b) { return strnatcasecmp($a->name, $b->name); });

            $selected     = $active_attrs[$taxonomy] ?? array();
            $is_open      = !empty($selected);
            $total        = count($terms);
            $has_more     = $total > $SHOW_LIMIT;
            $need_search  = $total >= $SEARCH_MIN;
            $group_id     = 'tm-pf-' . esc_attr($attr->attribute_name);
            $clear_url    = !empty($selected) ? tm_filter_remove_params(array('filter_' . $taxonomy)) : null;
        ?>
        <div class="tm-pf-group<?php echo $is_open ? ' tm-pf-group--open' : ''; ?><?php echo $is_color ? ' tm-pf-group--color' : ''; ?>"
             id="<?php echo $group_id; ?>"
             data-taxonomy="<?php echo esc_attr($taxonomy); ?>">

            <!-- Заголовок группы -->
            <button type="button" class="tm-pf-group__head" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>">
                <span class="tm-pf-group__name"><?php echo esc_html($attr->attribute_label); ?></span>
                <?php if (!empty($selected)) : ?>
                <span class="tm-pf-group__badge"><?php echo count($selected); ?></span>
                <?php endif; ?>
                <svg class="tm-pf-group__arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
            </button>

            <div class="tm-pf-group__body"<?php if (!$is_open) echo ' hidden'; ?>>

                <!-- Очистить группу -->
                <?php if ($clear_url) : ?>
                <a href="<?php echo esc_url($clear_url); ?>" class="tm-pf-group__clear tm-pf-chip"
                   title="Очистить «<?php echo esc_attr($attr->attribute_label); ?>»">
                    Сбросить
                    <span class="tm-pf-chip__x" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </span>
                </a>
                <?php endif; ?>

                <!-- Поиск внутри группы (для больших списков) -->
                <?php if ($need_search) : ?>
                <div class="tm-pf-search">
                    <input type="text"
                           class="tm-pf-search__input"
                           placeholder="Поиск…"
                           aria-label="Поиск в «<?php echo esc_attr($attr->attribute_label); ?>»"
                           autocomplete="off">
                    <svg class="tm-pf-search__icon" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </div>
                <?php endif; ?>

                <!-- Список: ЦВЕТОВЫЕ СВОТЧИ -->
                <?php if ($is_color) : ?>
                <ul class="tm-pf-swatches">
                    <?php foreach ($terms as $i => $term) :
                        $checked    = in_array($term->slug, $selected);
                        $toggle_url = tm_filter_toggle_term($taxonomy, $term->slug);
                        $color_hex  = tm_get_term_color_hex($term->name, $term->term_id);
                        $is_hidden  = $has_more && $i >= $SHOW_LIMIT && !$checked;
                    ?>
                    <li class="tm-pf-swatch-item<?php echo $checked ? ' is-checked' : ''; ?><?php echo $is_hidden ? ' tm-pf-hidden' : ''; ?>">
                        <label class="tm-pf-swatch-label" title="<?php echo esc_attr($term->name); ?> (<?php echo intval($term->count); ?>)">
                            <input type="checkbox"
                                   class="tm-pf-checkbox"
                                   <?php checked($checked); ?>
                                   data-url="<?php echo esc_attr($toggle_url); ?>"
                                   data-search="<?php echo esc_attr(mb_strtolower($term->name, 'UTF-8')); ?>"
                                   aria-label="<?php echo esc_attr($term->name); ?>">
                            <span class="tm-pf-swatch"
                                  <?php if ($color_hex) : ?>style="background: <?php echo esc_attr($color_hex); ?>;"<?php endif; ?>>
                                <?php if (!$color_hex) : ?>
                                <span class="tm-pf-swatch__letter"><?php echo esc_html(mb_substr($term->name, 0, 1, 'UTF-8')); ?></span>
                                <?php endif; ?>
                            </span>
                            <span class="tm-pf-swatch-name"><?php echo esc_html($term->name); ?></span>
                        </label>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Список: ОБЫЧНЫЕ ЧЕКБОКСЫ -->
                <?php else : ?>
                <ul class="tm-pf-list">
                    <?php foreach ($terms as $i => $term) :
                        $checked    = in_array($term->slug, $selected);
                        $toggle_url = tm_filter_toggle_term($taxonomy, $term->slug);
                        $is_hidden  = $has_more && $i >= $SHOW_LIMIT && !$checked;
                    ?>
                    <li class="tm-pf-list__item<?php echo $checked ? ' is-checked' : ''; ?><?php echo $is_hidden ? ' tm-pf-hidden' : ''; ?>">
                        <label class="tm-pf-list__label">
                            <input type="checkbox"
                                   class="tm-pf-checkbox"
                                   <?php checked($checked); ?>
                                   data-url="<?php echo esc_attr($toggle_url); ?>"
                                   data-search="<?php echo esc_attr(mb_strtolower($term->name, 'UTF-8')); ?>"
                                   aria-label="<?php echo esc_attr($term->name); ?>">
                            <span class="tm-pf-list__check" aria-hidden="true"></span>
                            <span class="tm-pf-list__name"><?php echo esc_html($term->name); ?></span>
                            <span class="tm-pf-list__count"><?php echo intval($term->count); ?></span>
                        </label>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <!-- «Показать ещё» -->
                <?php if ($has_more) : ?>
                <button type="button"
                        class="tm-pf-show-more"
                        data-total="<?php echo esc_attr($total); ?>"
                        data-limit="<?php echo esc_attr($SHOW_LIMIT); ?>"
                        aria-expanded="false">
                    <span class="tm-pf-show-more__text">Ещё <?php echo ($total - $SHOW_LIMIT); ?></span>
                    <svg class="tm-pf-show-more__arrow" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <?php endif; ?>

            </div>
        </div>
        <?php endforeach; ?>

    </form>

</div><!-- /tm-product-filter -->
