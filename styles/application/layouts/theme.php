<?php
/**
* @package   Uniq
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// get theme configuration
include($this['path']->path('layouts:theme.config.php'));

?>
<!DOCTYPE HTML>
<html lang="<?php echo $this['config']->get('language'); ?>" dir="<?php echo $this['config']->get('direction'); ?>"  data-config='<?php echo $this['config']->get('body_config','{}'); ?>'>

<head>
<?php echo $this['template']->render('head'); ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-KL38VXMZW1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-KL38VXMZW1');
    </script>
</head>

<body class="<?php echo $this['config']->get('body_classes'); ?>">
    <div id="top" class="tm-page">

        <?php if ($this['widgets']->count('toolbar-l + toolbar-r')) : ?>
        <div id="tm-toolbar" class="tm-toolbar uk-hidden-small">
            <div class="uk-container <?php if ($this['config']->get('fullscreen_container')) echo 'tm-container-full-width'; ?> uk-container-center uk-clearfix">

                <?php if ($this['widgets']->count('toolbar-l')) : ?>
                <div class="uk-float-left"><?php echo $this['widgets']->render('toolbar-l'); ?></div>
                <?php endif; ?>

                <?php if ($this['widgets']->count('toolbar-r')) : ?>
                <div class="uk-float-right"><?php echo $this['widgets']->render('toolbar-r'); ?></div>
                <?php endif; ?>

            </div>
        </div>
        <?php endif; ?>

        <?php if ($this['widgets']->count('logo + logo-small + headerbar + search + menu + offcanvas')) : ?>
            <?php echo $this['template']->render('header.'.$this['config']->get('navigation_style', 'default').''); ?>
        <?php endif; ?>

        <?php if (!$this['config']->get('fixed_navigation')) : ?>
            <?php if ($this['config']->get('totop_scroller', true)) : ?>
                <div class="tm-totop-scroller-fixed" data-uk-smooth-scroll data-uk-sticky="{top:-400, animation: 'uk-animation-slide-top'}">
                    <a href="#"></a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($this['widgets']->count('breadcrumbs')) : ?>
            <div class="tm-centered-content tm-application-bread"><?php echo $this['widgets']->render('breadcrumbs'); ?></div>
        <?php endif; ?>

        <div class="tm-application-header text-center tm-padding" style="background-image: url('<?php $image = get_field('application_image_header'); if( !empty( $image ) ): ?> <?php echo esc_url($image['url']); ?> <?php endif; ?>');">
            <div class="tm-centered-content">
                <h1 class="soldmedium size35 tm-margin-default-bottom color-white">
                    <?php the_field('application_main_title'); ?>
                </h1>
                <div class="medium size35 tm-margin-small-bottom color-white">
                    <?php the_field('application_main_title2'); ?>
                </div>
                <div class="tm-centered-btn tm-content-btn">
                    <?php the_field('application_header_btn'); ?>
                </div>
            </div>
        </div>

        <?php if ($this['widgets']->count('main-top + main-bottom + sidebar-a + sidebar-b') || $this['config']->get('system_output', true)) : ?>
        <div id="tm-main" class="<?php echo $block_classes['main']; ?>">

            <div class="<?php echo $container_class['main']; ?>">

                <div class="uk-grid" data-uk-grid-match data-uk-grid-margin>

                    <?php if ($this['widgets']->count('main-top + main-bottom') || $this['config']->get('system_output', true)) : ?>
                    <div class="<?php echo $columns['main']['class'] ?>">

                        <?php if ($this['widgets']->count('main-top')) : ?>
                        <section id="tm-main-top" class="<?php echo $grid_classes['main-top']; echo $display_classes['main-top']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('main-top', array('layout'=>$this['config']->get('grid.main-top.layout'))); ?></section>
                        <?php endif; ?>

                        <?php if ($this['config']->get('system_output', true)) : ?>

                        <main id="tm-content" class="tm-content">

                            <?php echo $this['template']->render('content'); ?>

                        </main>
                        <?php endif; ?>

                        <?php if ($this['widgets']->count('main-bottom')) : ?>
                        <section id="tm-main-bottom" class="<?php echo $grid_classes['main-bottom']; echo $display_classes['main-bottom']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('main-bottom', array('layout'=>$this['config']->get('grid.main-bottom.layout'))); ?></section>
                        <?php endif; ?>

                    </div>
                    <?php endif; ?>

                    <?php foreach($columns as $name => &$column) : ?>
                    <?php if ($name != 'main' && $this['widgets']->count($name)) : ?>
                    <aside class="<?php echo $column['class'] ?>"><?php echo $this['widgets']->render($name) ?></aside>
                    <?php endif ?>
                    <?php endforeach ?>

                </div>

            </div>

        </div>
        <?php endif; ?>

        <div class="tm-margin-default-bottom">
			<div class="tm-centered-content">
				<div class="tm-h1 bold uk-margin-large-bottom tm-projects-title"><?php the_field('application_projects_title'); ?></div>
				<div class="tm-grid">
					<div class="tm-grid-width-7-10 uk-flex uk-flex-middle">

						<?php the_field('application_projects_slide'); ?>

					</div>
					<div class="tm-grid-width-1-4">
						<div class="tm-projects-more-btn">
							<a href="#"><span>Посмотреть больше наших работ</span></a>
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="tm-padding">
			<div class="tm-centered-content">
				<div class="tm-reviews-header tm-margin-xlarge-bottom">
					<div class="tm-grid uk-flex uk-flex-center">
						<div class="tm-h1 bold"><?php the_field('application_reviews_title'); ?></div>
						<div><?php the_field('application_reviews_subtitle'); ?></div>
						<div><?php the_field('application_reviews_btn'); ?></div>
					</div>
				</div>
				<div class="tm-reviews-content">
					<?php the_field('application_reviews_content'); ?>
				</div>
			</div>
		</div>

        <?php if ($this['widgets']->count('bottom-d')) : ?>
        <div id="tm-bottom-d" class="<?php echo $block_classes['bottom-d']; ?>">
            <div class="<?php echo $container_class['bottom-d']; ?>">
                <section class="<?php echo $grid_classes['bottom-d']; echo $display_classes['bottom-d']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-d', array('layout'=>$this['config']->get('grid.bottom-d.layout'))); ?></section>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($this['widgets']->count('footer + debug') || $this['config']->get('warp_branding', true) || $this['config']->get('totop_scroller', true)) : ?>
        <footer id="tm-footer" class="tm-footer">
            <div class="uk-container <?php if ($this['config']->get('fullscreen_container')) echo 'tm-container-full-width'; ?> uk-container-center">

                <?php if ($this['config']->get('totop_scroller', true)) : ?>
                    <div class="tm-totop-scroller" data-uk-smooth-scroll>
                        <a href="#"></a>
                    </div>
                <?php endif; ?>

                <?php
                    echo $this['widgets']->render('footer');
                    $this->output('warp_branding');
                    echo $this['widgets']->render('debug');
                ?>

            </div>
        </footer>
        <?php endif; ?>

        <?php echo $this->render('footer'); ?>

        <?php if ($this['widgets']->count('offcanvas')) : ?>
        <div id="offcanvas" class="uk-offcanvas">
            <div class="uk-offcanvas-bar"><?php echo $this['widgets']->render('offcanvas'); ?></div>
        </div>
        <?php endif; ?>

    </div>
    
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(74679520, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/74679520" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter --> 
</body>
</html>
