<?php if ( ! defined( 'ABSPATH' ) )
	exit;
/**
 * NoFramework Environemnt class
 *
 *
 * @author  Vlado Bosnjak
 * @link    https://www.bobz.co
 * @version 1.1
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */
class NF {

	public static function init() {
		$class = __CLASS__;
		new $class;
	}

	/**
	 * Constructor on WP init hook
	 */
	public function __construct() {

		global $nf;


		/**
		 * Load Settings
		 */
		if ( function_exists('get_fields') ) :

			$this->load_options();
			$this->load_sidebars();
		endif;
	}





	/**
	 * Load Theme Options
	 *
	 * This function loads theme options and saves it as a transient.
	 * Every time Theme options are saved, cache is flushed and re-generated to get new data
	 * This will prevent to many DB Queries made by ACF
	 */
	private function load_options() {

		global $nf;

		/**
		 * Delete Options Transient when Options page is saved
		 */
		add_action('acf/save_post', function ($post_id) {

			if ( $post_id == 'options' )
				delete_transient( 'nf_options' );

		}, 1);




		/**
		 * Load Options as transient
		 */
		$nf = get_transient('nf_options');

		if ( $nf === false ) {

			$nf = get_fields('options');
			set_transient('nf_options', $nf, 3600 * 24);
		}
	}






	/**
	 * Load Sidebars from options page
	 */
	private function load_sidebars() {

		global $nf;

		if ( get_key('sidebars', $nf) ) :
			foreach ( $nf['sidebars'] as $sidebar ) :

				register_sidebar([
					'name'          => $sidebar['sidebar_name'],
					'id'            => sanitize_title( $sidebar['sidebar_name'] ),
					'before_widget' => '<section class="widget %s %s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="entry-title">',
					'after_title'   => '</h2>',
				]);

			endforeach;
		endif;
	}













	/**
	 * Favicon in head
	 */
	public static function set_favicon() {

		$src = false;
		$img = get_key('favicon');


		if ( is_numeric($img) ) :
			$src = wp_get_attachment_image_src( $img, 'full');
			$src = $src[0];

		elseif ( get_key('url', $img)) :
			$src = $img['url'];
		endif;


		if ( ! $src )
			return; ?>

		<link rel="icon" type="image/png" href="<?= $src; ?>"/>

	<?php }
}

add_action('init', ['NF', 'init']);
add_action('wp_head', ['NF', 'set_favicon']);