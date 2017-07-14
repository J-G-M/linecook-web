<?php while (have_posts()) : the_post();
	get_template_part( 'templates/content', 'page');
	ACF_Widget::get_widget();
endwhile;


$args = [
	'post_type' => 'shop_order',
	'posts_per_page' => -1,
	'suppress_filters' => true,
	'post_status' => 'wc-completed'
];
$qry  = new WP_Query($args);

printaj($qry);