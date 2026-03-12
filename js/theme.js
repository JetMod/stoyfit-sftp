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

            // ── Перехват ссылок и форм внутри фильтра ────────────────────────
            // Ссылки (фильтр-таксономии, сброс)
            $(document).on('click', '.tm-filter a, .tm-filter-reset a', function (e) {
                var href = $(this).attr('href');
                if (!href || href.charAt(0) === '#' || href.indexOf('javascript') === 0) return;
                // Позволяем fe_widget работать с его ссылками, но ловим навигацию
                // через небольшую задержку (после того как плагин может уже обработать)
                var self = this;
                setTimeout(function () {
                    if (!isFiltering && window.location.href !== href) {
                        e.preventDefault();
                        loadFilteredProducts(href);
                    }
                }, 0);
                e.preventDefault();
                loadFilteredProducts(href);
            });

            // Формы (price range и другие GET-формы)
            $(document).on('submit', '.tm-filter form', function (e) {
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
			  
			  
			  