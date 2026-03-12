jQuery(function ($) {
    $(document).ready(function () {

        // ── Мини-корзина ──────────────────────────────────────────────────────
        var $cartWrapper  = $('.tm-cart-wrapper');
        var $cartToggle   = $('.tm-cart-toggle');
        var $miniCart     = $('.tm-mini-cart');

        function openCart() {
            $miniCart.addClass('is-open');
            $cartToggle.attr('aria-expanded', 'true');
            $miniCart.attr('aria-hidden', 'false');
        }

        function closeCart() {
            $miniCart.removeClass('is-open');
            $cartToggle.attr('aria-expanded', 'false');
            $miniCart.attr('aria-hidden', 'true');
        }

        $cartToggle.on('click', function (e) {
            e.stopPropagation();
            if ($miniCart.hasClass('is-open')) {
                closeCart();
            } else {
                openCart();
            }
        });

        // Клик внутри мини-корзины не закрывает её
        $miniCart.on('click', function (e) {
            e.stopPropagation();
        });

        // Клик вне — закрывает
        $(document).on('click', function () {
            closeCart();
        });

        // Esc — закрывает
        $(document).on('keydown', function (e) {
            if (e.key === 'Escape') {
                closeCart();
            }
        });

        // После обновления корзины через AJAX — открываем мини-корзину
        $(document.body).on('added_to_cart', function () {
            openCart();
        });
        // ─────────────────────────────────────────────────────────────────────

        // ── Дропдаун аккаунта ─────────────────────────────────────────────────
        var $accountWrapper  = $('.tm-account-wrapper.is-logged-in');
        var $accountToggle   = $accountWrapper.find('.tm-account-toggle');
        var $accountDropdown = $accountWrapper.find('.tm-account-dropdown');

        function openAccount() {
            $accountDropdown.addClass('is-open');
            $accountToggle.attr('aria-expanded', 'true');
            $accountDropdown.attr('aria-hidden', 'false');
            // Закрываем корзину если открыта
            closeCart();
        }

        function closeAccount() {
            $accountDropdown.removeClass('is-open');
            $accountToggle.attr('aria-expanded', 'false');
            $accountDropdown.attr('aria-hidden', 'true');
        }

        $accountToggle.on('click', function (e) {
            e.stopPropagation();
            if ($accountDropdown.hasClass('is-open')) {
                closeAccount();
            } else {
                openAccount();
            }
        });

        $accountDropdown.on('click', function (e) {
            e.stopPropagation();
        });

        // Закрываем аккаунт-дропдаун при клике вне
        $(document).on('click', function () {
            closeAccount();
        });

        $(document).on('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAccount();
            }
        });

        // При открытии корзины — закрываем аккаунт
        $cartToggle.on('click.closeAccount', function () {
            closeAccount();
        });
        // ─────────────────────────────────────────────────────────────────────

        // Показать/скрыть пароль в форме входа
        $(document).on('click', '.tm-form-eye', function () {
            var targetId = $(this).data('target');
            var $input   = $('#' + targetId);
            var isPass   = $input.attr('type') === 'password';
            $input.attr('type', isPass ? 'text' : 'password');
            $(this).toggleClass('is-visible');
        });
        // ─────────────────────────────────────────────────────────────────────

        $('.qib-button .screen-reader-text').before('<div class=\"tm-product-metr\">Площадь, м2:</div>');
        $('.woocommerce-cart .woocommerce-Price-amount').before('<span class=\"tm-ot\">от:</span>');

        // ── Подкатегории-слайдер (Slick) ─────────────────────────────────────
        if ($('.multiple-items').length && typeof $.fn.slick !== 'undefined') {
            var slickConfig = {
                dots: false,
                arrows: true,
                infinite: true,
                autoplay: true,
                variableWidth: true,
                centerMode: true,
                slidesToShow: 3,
                responsive: [{
                    breakpoint: 767,
                    settings: { slidesToShow: 1, slidesToScroll: 1 }
                }]
            };

            $('.multiple-items').slick(slickConfig);

            $('.subcategories .open').on('click', function () {
                $(this).hide();
                $('.subcategories .close').show();
                $('.subcategories .multiple-items').addClass('open');
                $('.tag-slider').slick('unslick');
            });

            $('.subcategories .close').on('click', function () {
                $(this).hide();
                $('.subcategories .open').show();
                $('.subcategories .multiple-items').removeClass('open');
                if (!$('.tag-slider').hasClass('slick-initialized')) {
                    $('.tag-slider').slick(slickConfig);
                }
            });
        }
        // ─────────────────────────────────────────────────────────────────────

        // ── AJAX-фильтрация товаров ───────────────────────────────────────────
        if (typeof tmFilterData !== 'undefined' && tmFilterData.isArchive) {

            var $filterSidebar  = $('#tm-filter-sidebar');
            var $filterOverlay  = $('#tm-filter-overlay');
            var $filterOpenBtn  = $('#tm-filter-open');
            var $filterCloseBtn = $('#tm-filter-close');
            var $filterApplyBtn = $('#tm-filter-apply');
            var $productsWrap   = $('#tm-products-container');
            var $filterWidget   = $('#tm-filter-widget');
            var isFiltering     = false;
            var filterXHR       = null;

            // ── Мобильный дравер ─────────────────────────────────────────────
            function openFilterDrawer() {
                $filterSidebar.addClass('is-open');
                $filterOverlay.addClass('is-visible');
                $filterOpenBtn.attr('aria-expanded', 'true');
                $('body').addClass('tm-filter-open');
            }

            function closeFilterDrawer() {
                $filterSidebar.removeClass('is-open');
                $filterOverlay.removeClass('is-visible');
                $filterOpenBtn.attr('aria-expanded', 'false');
                $('body').removeClass('tm-filter-open');
            }

            $filterOpenBtn.on('click', openFilterDrawer);
            $filterCloseBtn.on('click', closeFilterDrawer);
            $filterOverlay.on('click', closeFilterDrawer);
            $filterApplyBtn.on('click', closeFilterDrawer);

            $(document).on('keydown', function (e) {
                if (e.key === 'Escape') closeFilterDrawer();
            });

            // ── AJAX загрузка продуктов ───────────────────────────────────────
            function loadFilteredProducts(url, pushState) {
                if (isFiltering) {
                    if (filterXHR) filterXHR.abort();
                }

                isFiltering = true;
                $productsWrap.addClass('tm-filtering');

                // Прокрутить к продуктам
                var scrollTarget = $('.tm-catalog-layout').offset();
                if (scrollTarget) {
                    $('html, body').animate({ scrollTop: scrollTarget.top - 30 }, 300);
                }

                filterXHR = $.ajax({
                    url:     url,
                    method:  'GET',
                    success: function (data) {
                        var $doc     = $($.parseHTML(data, document, true));
                        var $newWrap = $doc.find('#tm-products-container');
                        var $newFilt = $doc.find('#tm-filter-widget');
                        var $newBar  = $doc.find('.tm-filter-bar .tm-catalog-found-mobile');

                        if ($newWrap.length) {
                            $productsWrap.html($newWrap.html());
                        }

                        // Обновить виджет фильтра (счётчики товаров в пунктах)
                        if ($newFilt.length) {
                            $filterWidget.html($newFilt.html());
                        }

                        // Обновить счётчик найденных
                        var $newCount = $doc.find('#tm-found-count');
                        if ($newCount.length) {
                            $('#tm-found-count').text($newCount.text());
                        }

                        if (pushState !== false) {
                            history.pushState({ filteredUrl: url }, document.title, url);
                        }

                        // Реинициализировать WC AJAX-корзину на новых кнопках
                        if (typeof wc_add_to_cart_params !== 'undefined') {
                            $(document.body).trigger('wc_fragment_refresh');
                        }
                    },
                    error: function (xhr) {
                        if (xhr.statusText !== 'abort') {
                            // Фолбэк: обычная навигация
                            window.location.href = url;
                        }
                    },
                    complete: function () {
                        isFiltering = false;
                        $productsWrap.removeClass('tm-filtering');
                    }
                });
            }

            // ── Встроенный фильтр: чекбоксы атрибутов ───────────────────────
            $(document).on('change', '.tm-pf-checkbox', function () {
                var url = $(this).data('url');
                if (url) {
                    loadFilteredProducts(url);
                }
            });

            // ── Встроенный фильтр: форма цены ────────────────────────────────
            $(document).on('submit', '#tm-filter-form', function (e) {
                e.preventDefault();
                var $form   = $(this);
                var action  = $form.attr('action') || window.location.pathname;
                var minVal  = $('#tm-price-min-n').val();
                var maxVal  = $('#tm-price-max-n').val();

                // Берём текущие параметры URL (атрибуты, сортировка)
                var params = new URLSearchParams(window.location.search);

                // Обновляем/добавляем цену
                if (minVal) { params.set('min_price', minVal); }
                if (maxVal) { params.set('max_price', maxVal); }

                // Убираем пагинацию
                params.delete('paged');

                // Применяем hidden inputs из формы (сохранённые атрибуты)
                $form.find('input.tm-pf-attr-preserve').each(function () {
                    params.set($(this).attr('name'), $(this).val());
                });
                if ($form.find('[name="orderby"]').length) {
                    params.set('orderby', $form.find('[name="orderby"]').val());
                }

                loadFilteredProducts(action + '?' + params.toString());
            });

            // ── Перехват ссылок (чипы активных фильтров, сброс) ──────────────
            $(document).on('click', '.tm-pf-chip, .tm-pf-active a', function (e) {
                var href = $(this).attr('href');
                if (!href || href.charAt(0) === '#') return;
                e.preventDefault();
                loadFilteredProducts(href);
            });

            // ── Старый фильтр (fe_widget) — ссылки и формы ───────────────────
            $(document).on('click', '.tm-filter a:not(.tm-pf-chip):not(.tm-pf-active a)', function (e) {
                var href = $(this).attr('href');
                if (!href || href.charAt(0) === '#' || href.indexOf('javascript') === 0) return;
                e.preventDefault();
                loadFilteredProducts(href);
            });

            $(document).on('submit', '.tm-filter form:not(#tm-filter-form)', function (e) {
                e.preventDefault();
                var action = $(this).attr('action') || window.location.pathname;
                var params = $(this).serialize();
                var url    = action + (action.indexOf('?') > -1 ? '&' : '?') + params;
                loadFilteredProducts(url);
            });

            // Пагинация — AJAX без перезагрузки
            $(document).on('click', '#tm-products-container .woocommerce-pagination a, #tm-products-container .page-numbers a', function (e) {
                e.preventDefault();
                loadFilteredProducts($(this).attr('href'));
            });

            // Браузер назад/вперёд
            $(window).on('popstate', function (e) {
                var state = e.originalEvent.state;
                var url   = (state && state.filteredUrl) ? state.filteredUrl : window.location.href;
                loadFilteredProducts(url, false);
            });

            // Сохраняем начальное состояние в history
            if (!history.state || !history.state.filteredUrl) {
                history.replaceState({ filteredUrl: window.location.href }, document.title, window.location.href);
            }
        }
        // ─────────────────────────────────────────────────────────────────────

    });

    // accordion
    $('.tm-content-accordion-list__text').hide();
    $('.tm-content-accordion-list > div').click(function () {
        if ($(this).hasClass("active")) {
            $(this).removeClass("active").find(".tm-content-accordion-list__text").slideUp();
        } else {
            $(".tm-content-accordion-list > div.active .tm-content-accordion-list__text").slideUp();
            $(".tm-content-accordion-list > div.active").removeClass("active");
            $(this).addClass("active").find(".tm-content-accordion-list__text").slideDown();
        }
        return false;
    });

    // ── Слайдер «Похожие товары» (tm-related-slider) ─────────────────────────
    // forEach + скопированные nav-элементы: каждый экземпляр работает независимо
    document.querySelectorAll('.tm-related-slider').forEach(function (el) {
        var prevEl = el.querySelector('.tm-related-slider__prev, .swiper-button-prev');
        var nextEl = el.querySelector('.tm-related-slider__next, .swiper-button-next');

        new Swiper(el, {
            grabCursor:        true,
            waitForTransition: false,
            spaceBetween:      20,
            navigation: {
                prevEl: prevEl,
                nextEl: nextEl,
            },
            breakpoints: {
                320:  { slidesPerView: 1.2 },
                480:  { slidesPerView: 2 },
                768:  { slidesPerView: 3 },
                1180: { slidesPerView: 4 }
            },
            on: {
                init: function (swiper) {
                    if (swiper.slides.length <= swiper.params.slidesPerView) {
                        el.classList.add('tm-featured-slider--no-nav');
                    }
                }
            }
        });
    });
    // ─────────────────────────────────────────────────────────────────────────

    // ── Слайдер «Рекомендуем посмотреть» [featured_products_block] ───────────
    // Используем forEach чтобы каждый экземпляр на странице работал независимо
    document.querySelectorAll('.tm-featured-slider').forEach(function (el) {
        var uid     = el.id;
        var prevEl  = el.querySelector('.tm-featured-slider__prev');
        var nextEl  = el.querySelector('.tm-featured-slider__next');

        new Swiper(el, {
            grabCursor:        true,
            waitForTransition: false,
            spaceBetween:      20,
            navigation: {
                prevEl: prevEl,
                nextEl: nextEl,
            },
            breakpoints: {
                320:  { slidesPerView: 1.2 },
                480:  { slidesPerView: 2 },
                768:  { slidesPerView: 3 },
                1180: { slidesPerView: 4 }
            },
            // Не инициализировать если слайд только один
            on: {
                init: function (swiper) {
                    if (swiper.slides.length <= swiper.params.slidesPerView) {
                        el.classList.add('tm-featured-slider--no-nav');
                    }
                }
            }
        });
    });
    // ─────────────────────────────────────────────────────────────────────────

    new Swiper('.tm-works-slider', {
        loop: true,
        slidesToScroll: 1,
        grabCursor: true,
        speed: 300,
        waitForTransition: false,
        slidesPerView: 1,
        spaceBetween: 20,
        //effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        navigation: {
            nextEl: '.tm-works-slider_next',
            prevEl: '.tm-works-slider_prev',
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            769: {
                slidesPerView: 2,
            },
            980: {
                slidesPerView: 3,
            }
        }
    });

    const gallerySliderThumbs = new Swiper('.tm-gallery-slider-thumbs', {
        loop: true,
        direction: 'horizontal',
        freeMode: true,
        spaceBetween: 12,
        breakpoints: {
            0: {
                slidesPerView: 2.2,
            },
            769: {
                slidesPerView: 3,
            },
            980: {
                slidesPerView: 4,
            },
            1280: {
                slidesPerView: 5,
            }
        }
    });

    const gallerySliderImages = new Swiper('.tm-gallery-slider', {
        loop: true,
        direction: 'vertical',
        slidesPerView: 1,
        spaceBetween: 32,
        mousewheel: true,
        grabCursor: true,
        direction: 'horizontal',
        autoplay: {
            delay: 3000,
        },
        thumbs: {
            swiper: gallerySliderThumbs
        },
        navigation: {
            nextEl: '.tm-gallery-slider_next',
            prevEl: '.tm-gallery-slider_prev',
        },
    });


    const productSliderThumbs = new Swiper('.tm-product__images-thumbs .swiper-container', {
        direction: 'horizontal',
        freeMode: true,
        spaceBetween: 10,
        breakpoints: {
            0: {
                slidesPerView: 2.2,
            },
            769: {
                slidesPerView: 3,
            }
        }
    });

    const productSliderImages = new Swiper('.tm-product__images-main .swiper-container', {
        direction: 'vertical',
        slidesPerView: 1,
        spaceBetween: 32,
        mousewheel: true,
        grabCursor: true,
        direction: 'horizontal',
        thumbs: {
            swiper: productSliderThumbs
        },
        navigation: {
            nextEl: '.tm-product__images-right',
            prevEl: '.tm-product__images-left',
        },
    });

});

document.addEventListener('DOMContentLoaded', function () {

    var postsImages = document.querySelectorAll(".tm-gallery-slider, .tm-works-slider, .tm-grid-galllery, .tm-product__images-main, .tm-single-product-besh-image");

    const lightboxGalleries = new PhotoSwipeLightbox({
        gallery: postsImages,
        children: 'a',
        pswpModule: PhotoSwipe
    });

    lightboxGalleries.init();


});

// Slider Partners
     const partnersSlider = new Swiper('.partners-slider', {
        direction: 'horizontal',
        slidesPerView: 1,
        rewind: true,
        spaceBetween: 30,

        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        breakpoints: {
            769: {
                slidesPerView: 4,
            },
            481: {
                slidesPerView: 2,
            }
        }
    });
    
 // Hide/Show Homepage icon block
       $(document).ready(function() {
    $('.homepage-plosh:gt(3)').css('display', 'none');
    $('.more-using-types').click(function() {
      $('.homepage-plosh:gt(3)').slideToggle();
    });
});

// Home FAQ block
function toggleSpoiler(spoiler) {
				var content = spoiler.nextElementSibling;
				content.classList.toggle('active');
				spoiler.querySelector('.plus-minus-toggle').classList.toggle('collapsed');
			  }
// Home Popup
$(document).on("scroll", window, function () {
	if($('body').width()<800){
		if ($(window).scrollTop()>5000) 
		{
        $(".home-popup").css('transform','translatey(200px)');
    }
    else
    {
        $(".home-popup").css('transform','translatey(-400px)');
    }
}
	if($('body').width()>800){
    if ($(window).scrollTop()>2500) 
    {
        $(".home-popup").css('transform','translatey(200px)');
    }
    else
    {
        $(".home-popup").css('transform','translatey(-400px)');
    }
}
    });



$( ".home-popup-close" ).on( "click", function() {
	$('.home-popup').fadeOut()
	$('.home-popup').css('right','-1000px')
});


// =============================================================================
// Встроенный фильтр товаров: слайдер цены + аккордеоны
// =============================================================================

(function () {
    'use strict';

    // ── Слайдер цены ─────────────────────────────────────────────────────────
    function initPriceSlider() {
        var slider = document.getElementById('tm-price-slider');
        if (!slider) return;

        var minR = document.getElementById('tm-price-min-r');
        var maxR = document.getElementById('tm-price-max-r');
        var minN = document.getElementById('tm-price-min-n');
        var maxN = document.getElementById('tm-price-max-n');
        var fill = document.getElementById('tm-price-fill');
        if (!minR || !maxR || !minN || !maxN || !fill) return;

        // Флаг — уже инициализирован, не навешивать повторно
        if (slider.dataset.inited) return;
        slider.dataset.inited = '1';

        var rangeMin = parseFloat(slider.dataset.min) || 0;
        var rangeMax = parseFloat(slider.dataset.max) || 100000;
        var span     = rangeMax - rangeMin || 1;

        function clamp(val, lo, hi) {
            return Math.max(lo, Math.min(hi, isNaN(val) ? lo : val));
        }

        function updateFill() {
            var lo = (parseFloat(minR.value) - rangeMin) / span * 100;
            var hi = (parseFloat(maxR.value) - rangeMin) / span * 100;
            fill.style.left  = lo + '%';
            fill.style.width = Math.max(0, hi - lo) + '%';
            // Z-index: если min достиг max — поднимаем min наверх чтобы
            // можно было тащить его влево (иначе он под max-слайдером)
            if (parseFloat(minR.value) >= parseFloat(maxR.value) - (rangeMax - rangeMin) * 0.05) {
                minR.style.zIndex = 3;
                maxR.style.zIndex = 2;
            } else {
                minR.style.zIndex = 2;
                maxR.style.zIndex = 3;
            }
        }

        minR.addEventListener('input', function () {
            var v = clamp(parseFloat(this.value), rangeMin, parseFloat(maxR.value));
            this.value = v;
            minN.value = v;
            updateFill();
        });

        maxR.addEventListener('input', function () {
            var v = clamp(parseFloat(this.value), parseFloat(minR.value), rangeMax);
            this.value = v;
            maxN.value = v;
            updateFill();
        });

        minN.addEventListener('change', function () {
            var v = clamp(parseFloat(this.value), rangeMin, parseFloat(maxN.value));
            this.value = v;
            minR.value = v;
            updateFill();
        });

        maxN.addEventListener('change', function () {
            var v = clamp(parseFloat(this.value), parseFloat(minN.value), rangeMax);
            this.value = v;
            maxR.value = v;
            updateFill();
        });

        // Первичная прорисовка заливки
        updateFill();
    }

    // ── Аккордеоны групп фильтра ──────────────────────────────────────────────
    // Используем делегирование (один обработчик на весь фильтр),
    // а не forEach + addEventListener на каждую кнопку, чтобы избежать
    // дублирования при реинициализации после AJAX
    function initFilterAccordions() {
        var container = document.getElementById('tm-filter-widget');
        if (!container || container.dataset.accordionInited) return;
        container.dataset.accordionInited = '1';

        container.addEventListener('click', function (e) {
            var btn = e.target.closest('.tm-pf-group__head');
            if (!btn) return;

            var group  = btn.closest('.tm-pf-group');
            var body   = group.querySelector('.tm-pf-group__body');
            var isOpen = group.classList.contains('tm-pf-group--open');

            if (isOpen) {
                group.classList.remove('tm-pf-group--open');
                body.hidden = true;
                btn.setAttribute('aria-expanded', 'false');
            } else {
                group.classList.add('tm-pf-group--open');
                body.hidden = false;
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    }

    // ── «Показать ещё» / «Свернуть» ─────────────────────────────────────────
    function initShowMore() {
        var container = document.getElementById('tm-filter-widget');
        if (!container || container.dataset.showMoreInited) return;
        container.dataset.showMoreInited = '1';
        container.addEventListener('click', function (e) {
            var btn = e.target.closest('.tm-pf-show-more');
            if (!btn) return;
            var group  = btn.closest('.tm-pf-group');
            var isOpen = btn.getAttribute('aria-expanded') === 'true';
            var limit  = parseInt(btn.dataset.limit, 10);
            var total  = parseInt(btn.dataset.total, 10);
            if (isOpen) {
                var idx = 0;
                group.querySelectorAll('.tm-pf-list__item:not(.is-checked), .tm-pf-swatch-item:not(.is-checked)').forEach(function (li) {
                    if (idx >= limit) li.classList.add('tm-pf-hidden');
                    idx++;
                });
                btn.setAttribute('aria-expanded', 'false');
                btn.querySelector('.tm-pf-show-more__text').textContent = 'Ещё ' + (total - limit);
                btn.querySelector('.tm-pf-show-more__arrow').style.transform = '';
            } else {
                group.querySelectorAll('.tm-pf-hidden').forEach(function (el) { el.classList.remove('tm-pf-hidden'); });
                btn.setAttribute('aria-expanded', 'true');
                btn.querySelector('.tm-pf-show-more__text').textContent = 'Свернуть';
                btn.querySelector('.tm-pf-show-more__arrow').style.transform = 'rotate(180deg)';
            }
        });
    }

    // ── Поиск внутри группы ──────────────────────────────────────────────────
    function initGroupSearch() {
        var container = document.getElementById('tm-filter-widget');
        if (!container || container.dataset.searchInited) return;
        container.dataset.searchInited = '1';
        container.addEventListener('input', function (e) {
            if (!e.target.classList.contains('tm-pf-search__input')) return;
            var query = e.target.value.toLowerCase();
            var group = e.target.closest('.tm-pf-group');
            var moreBtn = group.querySelector('.tm-pf-show-more');
            group.querySelectorAll('.tm-pf-list__item, .tm-pf-swatch-item').forEach(function (li) {
                var inp = li.querySelector('[data-search]');
                var val = inp ? inp.dataset.search : li.textContent.toLowerCase();
                li.style.display = (!query || val.indexOf(query) !== -1) ? '' : 'none';
            });
            if (moreBtn) moreBtn.style.display = query ? 'none' : '';
        });
        container.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && e.target.classList.contains('tm-pf-search__input')) {
                e.target.value = '';
                e.target.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    }

    // ── Сортировка ───────────────────────────────────────────────────────────
    function initSortSelect() {
        var sel = document.getElementById('tm-sort-select');
        if (!sel || sel.dataset.inited) return;
        sel.dataset.inited = '1';
        sel.addEventListener('change', function () {
            var params = new URLSearchParams(window.location.search);
            params.set('orderby', this.value);
            params.delete('paged');
            var url = window.location.pathname + '?' + params.toString();
            if (typeof loadFilteredProducts === 'function') {
                loadFilteredProducts(url);
            } else {
                window.location.href = url;
            }
        });
    }

    // ── Инициализация при загрузке страницы ─────────────────────────────────
    initPriceSlider();
    initFilterAccordions();
    initShowMore();
    initGroupSearch();
    initSortSelect();

    // Реинициализация после AJAX-обновления фильтра
    document.addEventListener('tm_filter_updated', function () {
        initPriceSlider();
        initFilterAccordions();
        initShowMore();
        initGroupSearch();
        initSortSelect();
    });

    // MutationObserver: сбрасываем флаги когда AJAX заменяет содержимое
    var filterWidget = document.getElementById('tm-filter-widget');
    if (filterWidget) {
        var observer = new MutationObserver(function () {
            var w = document.getElementById('tm-filter-widget');
            if (w) {
                delete w.dataset.accordionInited;
                delete w.dataset.showMoreInited;
                delete w.dataset.searchInited;
            }
            var s = document.getElementById('tm-price-slider');
            if (s) delete s.dataset.inited;

            initPriceSlider();
            initFilterAccordions();
            initShowMore();
            initGroupSearch();
            // Сортировка — не в фильтре, не нужно реинитить
        });
        observer.observe(filterWidget, { childList: true, subtree: false });
    }

}());
