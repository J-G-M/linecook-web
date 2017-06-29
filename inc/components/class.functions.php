<?php if ( ! defined( 'ABSPATH' ) )
	exit;

class NF_Functions {

	public function __construct() {}




	/**
	 * Logo
	 * Returns HTML for logo set in options
	 */
	public function get_logo($url = false) {

		$src = false;
		$img = get_key('logo');


		if ( is_numeric($img) ) :
			$src = wp_get_attachment_image_src( $img, 'large');
			$src = $src[0];
			$w   = $img[1];
			$h   = $img[2];

		elseif ( is_array($img)) :

			$w   = $img['sizes']['large-width'];
			$h   = $img['sizes']['large-height'];
			$src = $img['sizes']['large'];
		endif;

		if ( ! $src )
			return;

		if ($url)
			return $src; ?>

		<a href="<?= home_url('/'); ?>">

			<?php if ( strpos($src, '.svg') !== false ) : ?>
				<img src="<?= $src; ?>" alt="<?= get_bloginfo('name'); ?>" />
			<?php else : ?>
				<img src="<?= $src; ?>" width="<?= $w; ?>" height="<?= $h; ?>" alt="<?= get_bloginfo('name'); ?>" />
			<?php endif; ?>
		</a>

	<?php }





	/**
	 * Get Selected sidebar for current template
	 */
	public function get_sidebar() {

		if ( ! function_exists('get_fields') )
			return;

		global $post, $nf;

		$page_id = 0;

		if ( is_object($post)) {
			$page_id = $post->ID;
		}

		$sidebar = get_post_meta($page_id, 'select_sidebar', true);

		return $sidebar;
	}







	/**
	 * Social Icons
	 */
	public function social() {

		$social = get_key('social_profiles');

		if ($social) : ?>
			<ul class="social">
				<?php foreach ($social as $s) : ?>
					<li>
						<a href="<?= $s['profile_url']; ?>" target="_blank" class="icon-social-<?= $s['network']; ?>">
							<?php svg_icon($s['network']); ?>
							<span class="sr-only"><?= $s['network']; ?></span>
						</a>
					</li>
				<?php endforeach; ?>
				<li class="label">Connect With Us</li>
			</ul>
		<?php endif;
	}







	/**
	 * Breadcrumbs
	 */
	public function breadcrumbs() {

		if ( is_front_page() )
			return;


		global $post; ?>

		<div class="breadcrumb">
			<ol>
				<li>
					<a href="<?= home_url('/'); ?>">Home</a>
				</li>

				<?php if ( is_page() ) :

					if ($post->post_parent) : ?>
						<li>
							<a href="<?= get_permalink($post->post_parent); ?>">
								<?= get_the_title($post->post_parent); ?>
							</a>
						</li>
					<?php endif; ?>

					<li>
						<span><?php the_title(); ?></span>
					</li>

				<?php elseif ( is_search() ) : ?>
					<li>
						<span>Search Results</span>
					</li>

				<?php elseif ( is_404() ) : ?>
					<li>
						<span>Not Found</span>
					</li>
				<?php endif; ?>
			</ol>
		</div>

		<?php
	}








	/**
	 * Custom pagination
	 */
	public function pagination( $query = null ) {

		global $wp_query;

		$query = $query ? $query : $wp_query;
		$big   = 999999999;

		$paginate = paginate_links( array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'type'      => 'array',
			'total'     => $query->max_num_pages,
			'format'    => '?paged=%#%',
			'current'   => max( 1, get_query_var('paged') ),
			'prev_text' => __('Prev', 'froots'),
			'next_text' => __('Next', 'froots'),
		));

		if ($query->max_num_pages > 1) : ?>
			<ul class="pagination">
				<?php foreach ( $paginate as $page ) : ?>
					<li><?= $page; ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif;
	}
}

function NF() {
	return new NF_Functions();
}