<div class="container">
	<?php get_template_part('templates/partials/page', 'header'); ?>

	<?php if (!have_posts()) : ?>
		<div class="alert alert-warning">
	    	<?php _e('Sorry, no results were found.', 'froots'); ?>
	  	</div>
	 	<?php get_search_form(); ?>
	<?php endif;

	while (have_posts()) : the_post(); ?>
		<?php get_template_part('templates/content', get_post_type() != 'post' ? get_post_type() : get_post_format()); ?>
	<?php endwhile; ?>

	<?php NF()->pagination(); ?>
</div>