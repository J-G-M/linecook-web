<?php if ( ! defined( 'ABSPATH' ) )
	exit;

global $widget, $wpdb;


$type = get_key('wgt_type');
$date = NF()->get_week_menu_dates( get_key('max_weeks') );

$start = $date['start']->format('Y-m-d');
$end   = $date['end']->format('Y-m-d');

$sql  = "SELECT $wpdb->posts.*";
$sql .= " FROM $wpdb->posts";
$sql .= " LEFT JOIN {$wpdb->prefix}menu_availibility AS mt1 ON ( {$wpdb->posts}.ID = mt1.product_id )";
$sql .= " WHERE $wpdb->posts.post_status = 'publish'";
$sql .= " AND $wpdb->posts.post_type = 'product'";
$sql .= " AND ( mt1.item_start <= '$start' AND mt1.item_end >= '$end' )";
$sql .= " ORDER BY $wpdb->posts.post_date DESC";


if ( $type == 'dynamic' ) :
	$res = $wpdb->get_results($sql, OBJECT);
else :
	$res = get_posts([
		'post_type' => 'product',
		'posts_per_page' => -1
	]);
endif;

if ( $res ) :
	global $post; ?>

	<section class="wgt wgt-hero bg-image" <?= NF()->get_bg_image(); ?>>
		<div class="container">
			<?php if ( $type == 'dynamic' ) : ?>
				<h1 class="h-72 main-title">Weekly menu <?= NF()->get_week_menu_title(); ?></h1>
			<?php else :
				the_key('title', 'h1', 'h-72 main-title');
			endif;

				the_key('lead', 'h5', 'lead');
			?>
		</div>
	</section>

	<section class="wgt-menu">
		<div class="container">
			<?php if ( $type == 'dynamic' ) : ?>
				<ul class="nav-weeks">
					<li>
						<?php if ( get_key('nav_prev', $date)) : ?>
							<a href="<?= $date['nav_prev']; ?>" class="prev"><span>Prev</span></a>
						<?php endif; ?>
					</li>
					<li class="h-18"><?= $date['title']; ?></li>

					<li>
						<?php if ( get_key('nav_next', $date)) : ?>
							<a href="<?= $date['nav_next']; ?>" class="next"><span>Next</span></a>
						<?php endif; ?>
					</li>
				</ul>
			<?php endif; ?>

			<ul class="row">
				<?php foreach ($res as $post):
					setup_postdata( $post ); ?>
					<li class="span-4">
						<?php get_template_part('templates/content', 'product'); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

<?php endif; $widget = null;