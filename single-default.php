<?php get_header(); ?>

<h1 class="tm-single-article-title uk-article-title"><?php single_post_title(); ?></h1>

<div id="item-<?php the_ID(); ?>" class="category-single-post">

	
	<?php 
		the_content();
	?>
	
</div>



<?php get_footer(); ?>