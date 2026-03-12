<div class="tm-headerbar-background">
    <div class="tm-headerbar tm-headerbar-default <?php if (!$this['config']->get('fullscreen_container')) echo 'tm-headerbar-container'; ?>">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">

            <?php if ($this['widgets']->count('logo')) : ?>
            <a class="tm-logo" href="<?php echo $this['config']->get('site_url'); ?>"><?php echo $this['widgets']->render('logo'); ?></a>
            <?php endif; ?>
			
			<?php
				if ( function_exists('dynamic_sidebar') )
					dynamic_sidebar('headerhour');
			?>
			
            <?php if ($this['widgets']->count('headerbar + search + offcanvas')) : ?>
            <div class="uk-flex uk-flex-middle">

                <?php if ($this['widgets']->count('search')) : ?>
                <div class="uk-hidden-small">
                    <?php echo $this['widgets']->render('search'); ?>
                </div>
                <?php endif; ?>

                <?php if ($this['widgets']->count('headerbar')) : ?>
                    <div class="tm-header-right"><?php echo $this['widgets']->render('headerbar'); ?></div>
                <?php endif; ?>

                <?php if (function_exists('WC')) : ?>
                <div class="tm-account-wrapper<?php echo is_user_logged_in() ? ' is-logged-in' : ''; ?>">
                    <?php if (is_user_logged_in()) : ?>
                        <?php $tm_user = wp_get_current_user(); ?>
                        <button class="tm-account-toggle" aria-label="Личный кабинет" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span class="tm-account-name uk-hidden-small"><?php echo esc_html($tm_user->display_name); ?></span>
                        </button>
                        <div class="tm-account-dropdown" aria-hidden="true">
                            <ul class="tm-account-dropdown__list">
                                <?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
                                <li class="<?php echo 'customer-logout' === $endpoint ? 'is-logout' : ''; ?>">
                                    <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"><?php echo esc_html($label); ?></a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="tm-account-toggle" aria-label="Войти">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span class="tm-account-label uk-hidden-small">Войти</span>
                        </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if (function_exists('WC')) : ?>
                <div class="tm-cart-wrapper">
                    <button class="tm-cart-toggle" aria-label="Корзина" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                        <?php $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
                        <span class="tm-cart-count" data-count="<?php echo $count; ?>"><?php echo $count > 0 ? $count : ''; ?></span>
                    </button>
                    <div class="tm-mini-cart" aria-hidden="true">
                        <div class="tm-mini-cart__content">
                            <?php woocommerce_mini_cart(); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($this['widgets']->count('offcanvas')) : ?>
                    <a href="#offcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>
                <?php endif; ?>

            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<div <?php if ($this['config']->get('fixed_navigation')) echo 'data-uk-sticky'; ?>><?php wp_nav_menu( array( 'theme_location' => 'main_menu' ) ); ?></div>


