<?php if ( ! defined( 'ABSPATH' ) )
	exit;

class NF_Functions {

	public function __construct() {}



	public function get_pickup_time( $post_id ) {

		$times = get_field('pickup_times', $post_id);

		if ( ! $times )
			return;


		foreach ($times as $key => $data) {

			if ( $data['active'] == 1 ) {

				$schedule['days'][] = $data['day'];
				$schedule[$data['day']] = array_map( 'trim', explode(',', $data['hour']) );
			}

		}


		return $schedule;
	}




	/**
	 * Get Background Image from widget
	 */
	public function get_bg_image( $wgt = false ) {

		global $widget;

		$src = false;
		$img = get_key('bg_image');

		if ( ! $img )
			return;

		if ( is_numeric($img) )
			$src = wp_get_attachment_image_src( $img, 'full' );


		$style = ' style="background-image: url(' . $src[0] . ')"';

		return $style;
	}




	/**
	 * Get Background Image from widget
	 */
	public function get_section_id( $wgt = false ) {

		global $widget;

		$section_id = get_key('section_id');

		if ( ! $section_id )
			return;

		$id = ' id="' . sanitize_title( $section_id ) . '"';

		return $id;
	}








	/**
	 * Get Image from
	 * @param  boolean $wgt [description]
	 * @return [type]       [description]
	 */
	public function get_acf_image( $image = [], $size = 'thumbnail', $class = 'img-rwd' ) {


		if ( is_numeric($image) )
			return wp_get_attachment_image( $image, $size );


		$w = $image['width'];
		$h = $image['height'];
		$s = $image['url'];
		$a = get_key('alt', $image) ? get_key('alt', $image) : get_key('title', $image);
		$c = $class;

		if ( get_key($size, $image['sizes']) ) {

			$w = $image['sizes'][$size . '-width'];
			$h = $image['sizes'][$size . '-height'];
			$s = $image['sizes'][$size];
		}

		$img  = '<img src="'.$s.'" alt="'.$a.'" width="'.$w.'" height="'.$h.'" class="'.$c.'" />';

		return $img;
	}








	/**
	 * Get Button from widget
	 */
	public function get_button( $button, $return_url = false ) {

		if ( ! is_array($button) || get_key('btn_type', $button) == 1 )
			return;

		$id = 'elem-' . rand(99,99999);

		/**
		 * Button Type
		 */
		switch ($button['btn_type']) :

			case 2:

				if ( is_object($button['btn_obj'])) {
					$url = get_permalink( $button['btn_obj'] );
				}
				else {
					$url = '#';
				}
				$open = '_top';
			break;

			case 3:
				$url  = $button['btn_url'];
				$open = '_blank';
			break;

			case 4:

				if ( is_object($button['btn_file'])) :

					$url  = wp_get_attachment_url( $button['btn_file'] );

				elseif ( get_key($button['btn_file'], 'url')) :

					$url  = $button['btn_file']['url'];

				else :
					$url  = $button['btn_file'];
				endif;

				$open = '_blank';
			break;


			case 5:
				$url  = get_term_link( $button['btn_cat'], 'category' );
				$open = '_top';
			break;

			case 6:
				$url  = '#modal-window';
				$open = '_top';
			break;

			default:
				return false;
			break;
		endswitch;


		/**
		 * Return only URL
		 */
		if ($return_url) {
			return $url;
		}




		switch ( $button['btn_style']) {
			case 1:
				$class = 'btn-transparent';
			break;

			case 2:
				$class = 'btn-white';
			break;

			case 3:
				$class = 'btn-brown';
			break;

			default:
				$class = 'btn-brown';
			break;
		}


		$btn  = '<p class="button-wrap">';
		$btn .= '<a href="' . $url . '" target="'. $open.'" class="'. $class .'"';

		if ( $button['btn_type'] == 4 ) {

			$btn .= ' download="download"';
		}

		if ( $button['btn_type'] == 6 ) {
			$btn .= ' data-modal="' . $id . '"';
		}

		$btn .= '>';
		$btn .= '<span>' . $button['btn_title'] . '</span>';
		$btn .= '</a></p>';

		if ( $button['btn_type'] == 6 ) {
			$btn .= $this->modal($button['modal_title'], $button['modal_content'], $id);
		}

		return $btn;
	}








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
	 * Modal Window
	 * @param  boolean $title   [description]
	 * @param  boolean $content [description]
	 * @return [type]           [description]
	 */
	public function modal( $title = false, $content = false, $id = 'modal' ) {

		if ( ! $title && ! $content )
			return;


		ob_start(); ?>

			<div id="<?= $id; ?>" class="modal">
				<div class="modal-wrap">
					<header>
						<?php if ( $title ) echo '<h2>' . $title . '</h2>'; ?>
						<a href="#close" class="close" data-modal="<?= $id; ?>">Close</a>
					</header>
					<main>
						<?php if ( $content ) echo do_shortcode($content); ?>
					</main>
				</div>
			</div>

		<?php

		return ob_get_clean();
	}






	/**
	 * Get SVG Icon
	 */
	public function svg_icon( $icon = false, $return = true ) {

		if ( ! $icon ) {
			$icon = get_key('select_icon');
		}

		if ( is_array($icon) ) {
			$icon = $icon['select_icon'];
		}

		if ( strpos($icon, '.png') !== false ) :

			$re  = '<span class="png-icon">';
			$re .= '<img src="' . get_stylesheet_directory_uri() . '/dist/icons/'. $icon .'" alt="'. preg_replace('/\\.[^.\\s]{3,4}$/', '', $icon) .'" />';
			$re .= '</span>';

		else :

			$re  = '<span class="svg-icon">';
			$re .= '<svg class="svg-'. $icon .'">';
			$re .= '<use xlink:href="' . get_stylesheet_directory_uri() . '/dist/images/sprite.svg#'. $icon .'"></use>';
			$re .= '</svg>';
			$re .= '</span>';

		endif;

		if ( $return )
			return $re;

		echo $re;
	}







	/**
	 * Social Icons
	 */
	public function social() {

		$social = get_key('social_networks');

		if ($social) : ?>
			<ul class="social">
				<?php foreach ($social as $s) : ?>
					<li>
						<a href="<?= $s['profile_url']; ?>" target="_blank" class="icon-social-<?= $s['network']; ?>">
							<?= $this->svg_icon($s['network']); ?>
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