<?php
/*
Template Name: Акции
*/
 
get_header();
?>

<main class="main tm-post-category">


	<h1 class="uk-article-title"><?php echo $current_category = single_cat_title('', 0); ?></h1>

	<div class="tm-category-container">
	    <?php if (is_category("reviews")) { ?> 
	 <div class="tm-grid uk-margin-large-bottom">   
	   <div class="tm-grid-width-4-10 uk-flex">
		<div style="width:560px;height:800px;overflow:hidden;position:relative;"><iframe style="width:100%;height:100%;border:1px solid #e6e6e6;border-radius:8px;box-sizing:border-box" src="https://yandex.ru/maps-reviews-widget/174603982074?comments"></iframe><a href="https://yandex.ru/maps/org/stroyfit/174603982074/" target="_blank" style="box-sizing:border-box;text-decoration:none;color:#b3b3b3;font-size:10px;font-family:YS Text,sans-serif;padding:0 20px;position:absolute;bottom:8px;width:100%;text-align:center;left:0;overflow:hidden;text-overflow:ellipsis;display:block;max-height:14px;white-space:nowrap;padding:0 16px;box-sizing:border-box">СтройФит на карте Новосибирска — Яндекс Карты</a></div>
       </div>
        <div class="tm-grid-width-6-10 uk-flex">
            <div>
            <h2 class="tm-h1 bold">Больше отзывов</h2> 
            <p>Переходите в наши профили в справочниках, читайте больше отзывов о компании "Стройфит"</p>
            <p class="tm-h1 bold review-cat"><a href="https://www.avito.ru/brands/i158945332/all?sellerId=d6d806318d8ea2f0f3c6401b32d5823e" target="_blank">Отзывы на Avito  <img src="https://xn--h1abohegeo.xn--p1ai/wp-content/uploads/avito.jpg" width="50" height="50"/></a></p>
            <p class="tm-h1 bold review-cat"><a href="https://novosibirsk.flamp.ru/firm/strojjfit_kompaniya_po_proizvodstvu_pokrytijj_iz_rezinovojj_kroshki-70000001044982097#reviews" target="_blank"> Отзывы на Flamp  <img src="https://xn--h1abohegeo.xn--p1ai/wp-content/uploads/flamp.jpg" width="50" height="50"/></a></p>
            </div>
        </div>
       
     </div>    
        <?php }?>
		<div class="tm-category-row">
		<?php  
		if (  have_posts() ) :
		while (have_posts()) :
					the_post();  ?>	 


			<div class="tm-category-column">
				
				<div class="tm-category-column__inner">
					<div class="tm-category-column__img">
						<?php if (has_post_thumbnail()) : ?>
							<?php
							$width = get_option('thumbnail_size_w'); //get the width of the thumbnail setting
							$height = get_option('thumbnail_size_h'); //get the height of the thumbnail setting
							?>
							<a class="category-link-img" <?php if ($special_blog) echo 'class="tm-featured-image uk-panel-teaser uk-margin-large-bottom"' ?> href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
								<?php the_post_thumbnail(array($width, $height), array('class' => '')); ?>
							</a>
						<?php endif; ?>
						
					</div>
				
					<div class="tm-category-content">
						<div class="tm-category-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></div>
					</div>
				</div>

			</div>

		<?php endwhile; ?>

		</div>
		
		<div class="category-pagination"><?php the_posts_pagination( $pagin ); ?></div>

	
		<?php   else : ?>



		<?php endif;    ?>


	</div>


</main>
<?php get_footer(); ?>
