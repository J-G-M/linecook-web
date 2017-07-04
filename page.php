<?php while (have_posts()) : the_post();
	get_template_part( 'templates/content', 'page');
	ACF_Widget::get_widget();
endwhile;