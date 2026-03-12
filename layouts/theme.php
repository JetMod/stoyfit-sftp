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

        <?php if ($this['widgets']->count('top-a')) : ?>
        <div id="tm-top-a" class="<?php echo $block_classes['top-a']; ?>">
            <div class="<?php echo $container_class['top-a']; ?>">
                <section class="<?php echo $grid_classes['top-a']; echo $display_classes['top-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-a', array('layout'=>$this['config']->get('grid.top-a.layout'))); ?></section>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($this['widgets']->count('top-b')) : ?>
        <div id="tm-top-b" class="<?php echo $block_classes['top-b']; ?>">
            <div class="<?php echo $container_class['top-b']; ?>">
                <section class="<?php echo $grid_classes['top-b']; echo $display_classes['top-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-b', array('layout'=>$this['config']->get('grid.top-b.layout'))); ?></section>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($this['widgets']->count('top-c')) : ?>
        <div id="tm-top-c" class="<?php echo $block_classes['top-c']; ?>">
            <div class="<?php echo $container_class['top-c']; ?>">
                <section class="<?php echo $grid_classes['top-c']; echo $display_classes['top-c']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-c', array('layout'=>$this['config']->get('grid.top-c.layout'))); ?></section>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($this['widgets']->count('top-d')) : ?>
        <div id="tm-top-d" class="<?php echo $block_classes['top-d']; ?>">
            <div class="<?php echo $container_class['top-d']; ?>">
                <section class="<?php echo $grid_classes['top-d']; echo $display_classes['top-d']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-d', array('layout'=>$this['config']->get('grid.top-d.layout'))); ?></section>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($this['widgets']->count('main-top + main-bottom + sidebar-a + sidebar-b') || $this['config']->get('system_output', true)) : ?>
        <div id="tm-main" class="<?php echo $block_classes['main']; ?>">

            <div class="<?php echo $container_class['main']; ?>">

                <?php if ($this['widgets']->count('breadcrumbs')) : ?>
                    <?php echo $this['widgets']->render('breadcrumbs'); ?>
                <?php endif; ?>

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

        <?php if ($this['widgets']->count('bottom-a')) : ?>
        <div id="tm-bottom-a" class="<?php echo $block_classes['bottom-a']; ?>">
            <div class="<?php echo $container_class['bottom-a']; ?>">
                <section class="<?php echo $grid_classes['bottom-a']; echo $display_classes['bottom-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-a', array('layout'=>$this['config']->get('grid.bottom-a.layout'))); ?></section>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($this['widgets']->count('bottom-b')) : ?>
        <div id="tm-bottom-b" class="<?php echo $block_classes['bottom-b']; ?>">
            <div class="<?php echo $container_class['bottom-b']; ?>">
                <section class="<?php echo $grid_classes['bottom-b']; echo $display_classes['bottom-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-b', array('layout'=>$this['config']->get('grid.bottom-b.layout'))); ?></section>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($this['widgets']->count('bottom-c')) : ?>
        <div id="tm-bottom-c" class="<?php echo $block_classes['bottom-c']; ?>">
            <div class="<?php echo $container_class['bottom-c']; ?>">
                <section class="<?php echo $grid_classes['bottom-c']; echo $display_classes['bottom-c']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-c', array('layout'=>$this['config']->get('grid.bottom-c.layout'))); ?></section>
            </div>
        </div>
        <?php endif; ?>

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
