<article id="item-<?php the_ID(); ?>" class="uk-article" data-permalink="<?php the_permalink(); ?>">
	<div class="tm-artcile-container">
		<?php if (has_post_thumbnail()) : ?>
			<a class="tm-article-img-container" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail(); ?></a>
		<?php endif; ?>

		<div class="uk-article-title"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></div>



	</div>
</article>
