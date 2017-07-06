<?php if ( ! defined( 'ABSPATH' ) )
	exit;

global $widget;

$args = [
	'post_type'      => 'product',
	'posts_per_page' => get_key('qty'),
	'paged'          => get_page_num()
];
$qry  = new WP_Query($args);

if ($qry->have_posts() ) : ?>
<section class="<?= $widget['class']; ?>"<?= NF()->get_bg_image(); ?><?= NF()->get_section_id(); ?>>
	<div class="container">
		<ul class="row">
			<?php while ( $qry->have_posts() ) : $qry->the_post(); ?>
				<li class="span-4">
					<?php get_template_part('templates/content', 'product'); ?>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>
</section>

<?php endif; $widget = null;