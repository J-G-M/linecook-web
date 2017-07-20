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

		global $nf, $zone;

		$zone = new DateTimeZone('America/New_York');

		if ( ! defined('NF_GMAPS_API')) {
			define('NF_GMAPS_API', 'AIzaSyDoVervtRaPVCC276PsdPF5flnqrwzkcC4');
		}

		add_filter('template_redirect', [$this, 'template_redirect']);
		add_filter('walker_nav_menu_start_el', [$this, 'scrollTo_menu'], 9999, 4);



		/**
		 * Load Settings
		 */
		if ( function_exists('get_fields') ) :

			$this->load_options();
			$this->load_sidebars();
		endif;



		/**
		 * Custom table / save_post
		 */
		$this->create_menu_table();
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
	 * Add Icon to WP Nav menu
	 */
	public function scrollTo_menu ($item_output, $item, $depth, $args) {

		$section = trim(get_post_meta($item->ID, 'scroll_to', true));


		if ( $section ) {
			$output  = '<a href="#'. sanitize_title($section) .'" data-scroll-to="'. sanitize_title($section) .'">';
		}
		else {
			$output  = '<a href="'. get_permalink( $item->object_id ) .'">';
		}

		$output .= $item->title;
		$output .= '</a>';

		return $output;
	}




	public function template_redirect() {

		if ( is_shop() || is_archive('product') || is_tax(['product_cat', 'product_tag']) ) {
			$page = get_key('page_menu');

			if ( $page ) {
				wp_redirect( get_permalink($page->ID, 302) );
				exit;
			}
		}
	}







	/**
	 *
	 *
	 * Create availability menu items table
	 * This table stores data for each item on what date is available
	 * This is needed so we can preform per week query for weekly menu since date ranges are defined with ACF
	 * repeater field this is not possible using standard WP_Query
	 *
	 *
	 */
	public function create_menu_table() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'menu_availibility';
		$charset    = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			product_id mediumint(9) NULL,
			item_start DATE DEFAULT '0000-00-00' NOT NULL,
			item_end DATE DEFAULT '0000-00-00' NOT NULL,
			PRIMARY KEY  (id)
		) $charset;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}






	/**
	 * Save ACF values to custom table
	 */
	public function save_menu_avialibility( $post_id, $post, $update ) {



		global $wpdb, $zone;

		if ( wp_is_post_revision( $post_id ) || 'product' != get_post_type($post_id) )
			return;

		$table = $wpdb->prefix . 'menu_availibility';
		$range = isset($_POST['acf']['field_5968ba6c0a09f']) ? $_POST['acf']['field_5968ba6c0a09f'] : false;

		// Delete old entries
		$wpdb->query($wpdb->prepare("DELETE FROM $table WHERE product_id = %d", $post_id));

		if ( $range ) :
			foreach ( $range as $date ) {

				$from = new DateTime($date['field_5968ba820a0a0']);
				$to   = new DateTime($date['field_5968ba890a0a1']);

				$wpdb->insert(
					$table,
					array(
						'product_id' => $post_id,
						'item_start' => $from->format('Y-m-d'),
						'item_end'   => $to->format('Y-m-d'),
					),
					array(
						'%d',
						'%s',
						'%s',
					)
				);
			}
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


add_action( 'save_post', ['NF', 'save_menu_avialibility'], 1001, 3 );