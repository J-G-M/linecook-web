<?php while (have_posts()) : the_post();
	ACF_Widget::get_widget();
endwhile;