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


		<div class="tm-padding-big">
			<div class="tm-centered-content">
				<div class="tm-grid tm-home-header-content">
					<div class="tm-grid-width-4-10 uk-flex uk-flex-middle">
						<div>
							<h1 class="size45 bold"><?php the_field('home_main_title'); ?></h1>
							<h2 class="tm-h2 medium color-red"><?php the_field('home_after_main_title'); ?></h2>
						</div>
					</div>
					<div class="tm-grid-width-6-10">
						<?php if( have_rows('home_header_content') ): ?>
						<div class="tm-header-content">
							<?php while( have_rows('home_header_content') ): the_row();
							$image = get_sub_field('home_header_content_img');
							$title = get_sub_field('home_header_content_title');
							$link = get_sub_field('home_header_content_link');
							?>
								<div class="tm-header-content-item">
									<div class="tm-header-content-item-inner">
										<div class="tm-header-content-img">
											<a href="<?=$link?>"><img src="<?=$image['url']?>" alt="<?=$image['alt']?>"></a>
										</div>
										<div class="tm-header-content-title"><a href="<?=$link?>"><?=$title?></a></div>
										<div class="tm-header-content-back"></div>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		
            
            	<div class="tm-header-content">
            	    <?php if( get_field('home-trade') ): ?>
            	    <?php while( has_sub_field('home-trade') ): ?>
            	    <div class="tm-header-content-item-1">
            	      <div class="tm-header-content-item-inner"> 
            	              <div class="tm-header-content-img">
            	       <a href="<?php the_sub_field('home-trade-link'); ?>"> <img src="<?php the_sub_field('home-trade-img'); ?>"></a>
            	             </div>
            	        <div class="tm-header-content-title"><a href="<?php the_sub_field('home-trade-link'); ?>"><?php the_sub_field('home-trade-text'); ?></a></div>
            	        <div class="tm-header-content-back"></div>
            	     </div> 
            	    </div>  
            	    <?php endwhile; ?>
            	    <?php endif; ?>
            	</div>
           </div> 	
            	
		<div class="tm-padding tm-home-pr">
			<div class="tm-centered-content">

				<?php if( have_rows('home_pr') ): ?>
				<div class="tm-home-pr-content">
					<div class="tm-grid">
					<?php while( have_rows('home_pr') ): the_row();
					$icon = get_sub_field('home_pr_icon');
					$text = get_sub_field('home_pr_icon_text');
					?>

							<div class="tm-grid-width-1-3 tm-home-pr-item">
								<div class="uk-flex">
									<div class="tm-stroyfit-icon tm-stroyfit-icon-<?=$icon?>"></div>
									<div class="tm-home-pr-item-text"><?=$text?></div>
								</div>
							</div>

					<?php endwhile; ?>
					</div>
				</div>
				<?php endif; ?>


			</div>
		</div>
		
		<div class="tm-padding-big">
			<div class="tm-centered-content">
				<?php the_field('home_content_ploshdki'); ?>
			</div>
		
		
		    <div class="tm-centered-content">
		        <ul class="tm-margin-xlarge-bottom  plosh-block">
		        <?php if( get_field('home-icon-block') ): ?>
            	    <?php while( has_sub_field('home-icon-block') ): ?>
            	    <li class="tm-home-ploshadki-block homepage-plosh">
                        <a href="<?php the_sub_field('home-block-icon-link'); ?>">
                    <div class="uk-flex">
                        <div class="tm-stroyfit-icon tm-stroyfit-icon-<?php the_sub_field('home-icon-class'); ?>"></div>
                        <div class="tm-home-pr-item-text uk-flex uk-flex-middle"><?php the_sub_field('home-block-icon-link-text'); ?></div>
                    </div>
                        </a>
                    </li>
            	    <?php endwhile; ?>
            	    <?php endif; ?>
            	</ul>    
            	<div class="text-center"><a class="more-using-types"></a></div>
                    <div class="text-center medium tm-content-links"><a href="<?php the_field('home_icon_bottom_link'); ?>"><?php the_field('home_icon_bottom_text'); ?></a></div>
		    </div>
		</div>
				<div class="tm-centered-content">
			    <div class="tm-h1 bold uk-margin-large-bottom"><?php the_field('partners_main_title'); ?></div>
			        <div class="slider-container">
    <div class="swiper partners-slider">
        <div class="swiper-wrapper">
            <?php if(get_field('slider-partners')): ?>
            <?php while(has_sub_field('slider-partners')) : ?>
                <div class="swiper-slide">
                    <img src="<?php the_sub_field('slider-partners-img'); ?>" alt="" class="partners__slide-img">

                    <p class="partners__slide-title"><?php the_sub_field('slider-partners-name'); ?></p>
                </div>
            <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="swiper-button-prev partner-slider-btn"></div>
    <div class="swiper-button-next partner-slider-btn"></div>
</div>
			    
			 </div>
			 
		
			<div class="tm-centered-content" id="home-price-widget">
				<?php the_field('home_price_widgets'); ?>
			</div>
			
			    <div class="tm-centered-content">
		        <h2 class="home-faq-title"> <?php the_field('home_faq_title'); ?></h2>
		            <div class="spoiler_wrap">
		                <?php if( get_field('home-faq-block') ): ?>
            	        <?php while( has_sub_field('home-faq-block') ): ?>
                        <div class="spoiler_title" onclick="toggleSpoiler(this)">
                               <?php the_sub_field('home-faq-question'); ?>
                        <div class="plus-minus-toggle collapsed"></div>
                        </div>
                                <div class="spoiler_content">
                                    <p><?php the_sub_field('home-faq-answer'); ?></p>
                                </div>
                         <?php endwhile; ?>
            	    <?php endif; ?>       
                    </div>
		        </div>
		
			<div class="tm-centered-content">
			    <div class="tm-h1 bold uk-margin-large-bottom"><?php the_field('team_main_title'); ?></div>
				<?php the_field('home_team_widget'); ?>
			</div>
		

		<div>
		    
			<div class="tm-centered-content">
				<div class="tm-grid tm-with-us-blocks-home">
					<div class="tm-grid-width-4-10 uk-flex uk-flex-middle">
						<div>
							<div class="tm-h1 bold uk-margin-large-bottom"><?php the_field('home_with_us_title'); ?></div>
							<div class="size25 medium tm-margin-default-bottom"><?php the_field('home_after_with_us_title'); ?></div>
							<div class="tm-margin-default-bottom"><?php the_field('home_with_us_btn'); ?></div>
						</div>
					</div>
					<div class="tm-grid-width-6-10">
						<?php if( have_rows('home_with_us_blocks') ): ?>
						<div class="tm-grid">

							<?php while( have_rows('home_with_us_blocks') ): the_row();
							$icon = get_sub_field('home_with_us_blocks_icon');
							$text = get_sub_field('home_with_us_blocks_text');
							?>
								<div class="tm-grid-width-1-3">
									<div class="tm-home-with-us-item uk-flex uk-flex-middle">
										<div>
											<div class="tm-stroyfit-icon tm-stroyfit-icon-<?=$icon?>"></div>
											<div class="tm-home-with-us-item-text"><?=$text?></div>
										</div>
									</div>
								</div>
							<?php endwhile; ?>

						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="tm-padding-small">
			<div class="tm-centered-content">
				<div class="tm-reviews-header tm-margin-xlarge-bottom">
					<div class="tm-grid uk-flex uk-flex-center uk-flex-middle">
						<div class="tm-h1 bold"><?php the_field('home_reviews_title'); ?></div>
						<div><?php the_field('home_reviews_subtitle'); ?></div>
						<div><?php the_field('home_reviews_btn'); ?></div>
					</div>
				</div>
				<div class="tm-reviews-content">
					<?php the_field('home_reviews_content'); ?>
				</div>
				<div class="video-review">
				    <?php if(get_field('video_review')): ?>
                    <?php while(has_sub_field('video_review')) : ?>
                        <div class="video">
                            <iframe width="100%" height="100%" src="<?php the_sub_field("video_review_item"); ?>" frameborder="0" allowfullscreen></iframe>
                        </div>
                    <?php endwhile; ?>
                    <?php endif; ?>
				</div>
			</div>
		</div>

		<div class="tm-margin-xxxlarge-bottom">
			<div class="tm-centered-content">
				<div class="tm-h1 bold uk-margin-large-bottom tm-projects-title"><?php the_field('home_projects_title'); ?></div>
				<div class="tm-grid">
					<div class="tm-grid-width-7-10 uk-flex uk-flex-middle">

						<?php the_field('home_projects_slide'); ?>

					</div>
					<div class="tm-grid-width-1-4">
						<div class="tm-projects-more-btn">
							<a href="/objects"><span>Посмотреть больше наших работ</span></a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="tm-padding tm-block-costs">
			<div class="tm-centered-content">

					<div class="tm-h1 bold tm-margin-default-bottom color-white"><?php the_field('home_costs_title'); ?></div>
					<div class="tm-margin-xlarge-bottom color-white"><?php the_field('home_after_costs_title'); ?></div>

					<?php if( have_rows('home_costs_blocks') ): ?>
					<div class="tm-costs-blocks tm-grid">

						<?php while( have_rows('home_costs_blocks') ): the_row();
						$title = get_sub_field('home_costs_blocks_title');
						$link = get_sub_field('home_costs_blocks_link');
						$text1 = get_sub_field('home_costs_blocks_text1');
						$text2 = get_sub_field('home_costs_blocks_text2');
						$price = get_sub_field('home_costs_blocks_price');
						?>
							<div class="tm-grid-width-1-4">
								<div class="tm-home-costs-item uk-flex uk-flex-middle">
									<div>
										<div class="tm-costs-title size25 medium tm-margin-default-bottom"><a href="<?=$link?>"><?=$title?></a></div>
										<div class="tm-home-costs-item-text1 color-white"><?=$text1?></div>
										<div class="tm-home-costs-item-text2 color-white"><?=$text2?></div>
										<div class="size25 bold tm-home-costs-item-price color-white"><?=$price?></div>
									</div>
								</div>
							</div>
						<?php endwhile; ?>

					</div>
					<?php endif; ?>

			</div>
		</div>

		<div class="tm-padding tm-home-block-seo">
			<div class="tm-centered-content">
				<div class="tm-home-block-seo-content">
                    <?php

                    $seo_title = get_field('home_seo_title');

                    if( !empty($seo_title) ): ?>

                        <div class="tm-h1 bold tm-margin-default-bottom"><?=$seo_title?></div>

                    <?php endif; ?>
                    

					<div class="tm-home-seo-content">
						<?php the_field('home_seo_content'); ?>
					</div>

					<div class="tm-home-seo-plitka">
						<?php the_field('home_seo_plitka'); ?>
					</div>

				</div>
			</div>
		</div>

		<div class="tm-padding tm-home-block-delivery">
			<div class="tm-centered-content">
				<div class="tm-home-block-delivery-content">
					<div class="tm-h1 soldmedium uk-margin-large-bottom"><?php the_field('home_delivery_title'); ?></div>

					<?php if( have_rows('home_delivery_blocks') ): ?>
					<div class="tm-delivery-blocks uk-margin-large-bottom">

						<?php while( have_rows('home_delivery_blocks') ): the_row();
						$img = get_sub_field('home_delivery_blocks_img');
						?>

								<div class="tm-home-delivery-item uk-flex uk-flex-middle">
									<div>
										<img src="<?=$img['url']?>" alt="<?=$img['alt']; ?>" />
									</div>
								</div>

						<?php endwhile; ?>

					</div>
					<?php endif; ?>

					<div class="tm-home-delivery-title2 size25 medium">
						<?php the_field('home_delivery_title2'); ?>
					</div>

					<div class="tm-content-btn tm-content-btn-big2">
                        <a href="/delivery-pay">Подробнее о доставке</a>
                    </div>

				</div>
			</div>
		</div>
		                <div class="home-popup">
		                    <div class="home-popup-close"><span class="dashicons dashicons-no"></span></div>
	                    	<div class="popup-title"><?php the_field('popup_title'); ?></div>
                            <img class="aligncenter"src="<?php the_field('popup_img'); ?>" width="150" height="150" />
                                    <div class="popup-button">
                                    <div class="text-center tm-content-btn">
                                    <a href="<?php the_field('popup_link'); ?>"><?php the_field('popup_link_text'); ?></a>
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
