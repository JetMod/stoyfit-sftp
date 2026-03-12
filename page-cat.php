<?php /*
Template Name: Каталог Шаблон
*/
get_header(); ?>

<div class="tm-padding-big">
			<div class="tm-centered-content">
				<div class="tm-grid tm-home-header-content">
                    <h1 class="uk-article-title"><?php the_field('main_title'); ?></h1>
				</div>
		<div class="tm-header-content">		
				<?php if(get_field('link_block-1')): ?>
                <?php while(has_sub_field('link_block-1')) : ?>
				<div class="tm-header-content-item-2">
			<div class="tm-header-content-item-inner">
				<div class="tm-header-content-img"><a href="<?php the_sub_field('block_link'); ?>"><img src="<?php the_sub_field('block_img'); ?>" alt=""></a></div>
				<div class="tm-header-content-title"><a href="<?php the_sub_field('block_link'); ?>"><?php the_sub_field('block_text'); ?></a></div>
				<div class="tm-header-content-back"></div>
			</div>
		</div>
		<?php endwhile; ?>
			<?php endif; ?>
			</div>
		</div>	
</div>
        <div class="tm-centered-content">     
             <div class="tm-header-content">
        <?php if(get_field('links_block-1')): ?>
        <?php while(has_sub_field('links_block-1')) : ?>         	
		        <div class="tm-header-catalog-recomend"><a href="<?php the_sub_field('item_link'); ?>"><?php the_sub_field('item_text'); ?></a></div>
		 <?php endwhile; ?>
		 <?php endif; ?>
            </div> 
        </div>   

<!-- <div class="tm-padding-big">
			<div class="tm-centered-content">
		<div class="tm-header-content">		
				<?php if(get_field('link_block-2')): ?>
                <?php while(has_sub_field('link_block-2')) : ?>
				<div class="tm-header-content-item-2">
			<div class="tm-header-content-item-inner">
				<div class="tm-header-content-img"><a href="<?php the_sub_field('block_link'); ?>"><img src="<?php the_sub_field('block_img'); ?>" alt=""></a></div>
				<div class="tm-header-content-title"><a href="<?php the_sub_field('block_link'); ?>"><?php the_sub_field('block_text'); ?></a></div>
				<div class="tm-header-content-back"></div>
			</div>
		</div>
		<?php endwhile; ?>
			<?php endif; ?>
			</div>
		</div>	
</div>       -->

 <div class="tm-centered-content">     
             <div class="tm-header-content">
        <?php if(get_field('links_block-2')): ?>
        <?php while(has_sub_field('links_block-2')) : ?>         	
		        <div class="tm-header-catalog-recomend"><a href="<?php the_sub_field('item_link'); ?>"><?php the_sub_field('item_text'); ?></a></div>
		 <?php endwhile; ?>
		 <?php endif; ?>
            </div> 
        </div>   
       
<div class="tm-grid tm-margin-xlarge-bottom tm-with-us-blocks-grid tm-margin-xlarge-top">
<div class="tm-grid-width-1-4 uk-flex uk-flex-middle">
<div>
<div class="tm-h1 bold uk-margin-large-bottom"><?php the_field('adv_title'); ?></div>
</div>
</div>
<div class="tm-grid-width-7-10">
<div class="tm-grid">
<?php if(get_field('adv_block')): ?>
<?php while(has_sub_field('adv_block')) : ?>    
<div class="tm-grid-width-1-4">
<div class="tm-home-with-us-item uk-flex uk-flex-middle"><div>
<div class="tm-stroyfit-icon tm-stroyfit-icon-check"></div>
<div class="tm-home-with-us-item-text"><?php the_sub_field('adv_item'); ?></div>
</div>
</div>
</div>
<?php endwhile; ?>
<?php endif; ?>
</div>
</div>
</div>   

<?php the_field('slider'); ?>
<?php get_footer(); ?>