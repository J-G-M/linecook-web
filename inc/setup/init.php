<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;

if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {

	load_theme_textdomain('nof', get_template_directory() . '/lang');

	// Enable plugins to manage the document title
	// http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
	add_theme_support('title-tag');

	// Register wp_nav_menu() menus
	// http://codex.wordpress.org/Function_Reference/register_nav_menus
	register_nav_menus([
		'nav_main_left'  => __('Primary Navigation (Left)', 'nof'),
		'nav_main_right' => __('Primary Navigation (Right)', 'nof'),
		'nav_mobile'     => __('Mobile Navigation', 'nof'),
	]);

	// Enable post thumbnails
	// http://codex.wordpress.org/Post_Thumbnails
	// http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
	// http://codex.wordpress.org/Function_Reference/add_image_size
	add_theme_support('post-thumbnails');

	// Enable HTML5 markup support
	// http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
	add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);


	// Soli Cleanup plugin
	add_theme_support('soil-clean-up');
	add_theme_support('soil-disable-asset-versioning');
	add_theme_support('soil-disable-trackbacks');
	add_theme_support('soil-jquery-cdn');
	// add_theme_support('soil-js-to-footer');
	add_theme_support('soil-nav-walker');
	add_theme_support('soil-nice-search');
	add_theme_support('soil-relative-urls');

	// Theme functions
	add_theme_support( 'woocommerce' );
	// add_theme_support('nf_sidebars');

});


/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {

	/**
	 * Include Google Fonts
	 */
	$family = [
		'family' => 'Raleway:200,300,400,500,600'
	];
	wp_enqueue_style( 'nf/font', add_query_arg( $family, "//fonts.googleapis.com/css" ), [], null );


	/**
	 * Typekit
	 */
	// wp_enqueue_script( 'nf/typekit', 'https://use.typekit.net/rsi2adb.js', [], null );
   	// wp_add_inline_script( 'nf/typekit', 'try{Typekit.load({ async: true });}catch(e){}' );


	// Theme CSS
	wp_enqueue_style('nf/css', Assets\asset_path('styles/main.css'), false, null);


	// Scripts
	if (is_single() && comments_open() && get_option('thread_comments'))
		wp_enqueue_script('comment-reply');


	$src_map = '//maps.googleapis.com/maps/api/js?key=' . NF_GMAPS_API;

	wp_register_script( 'js/gmaps-map', $src_map, ['jquery'], null, true );
	wp_enqueue_script('nf/js', Assets\asset_path('scripts/main.js'), ['jquery', 'js/gmaps-map'], null, true);


	/**
	 * Conditionally include JS based on ACF widget used on page
	 */
	$meta = get_post_meta( get_the_ID(), 'page_widgets', true);

	if ( is_array($meta) ) :

		if ( in_array('wgt_form', $meta) ) {
			wp_enqueue_script('js/forms');
		}
	endif;


	wp_localize_script( 'nf/js', 'nf', [
		'nonce'    => wp_create_nonce('nonce'),
		'ajax_url' => admin_url('admin-ajax.php'),
		'assets'   => get_stylesheet_directory_uri() . '/dist/',
	]);

}, 100);



/**
 * Admin Scripts / Styles
 */
add_action( 'admin_enqueue_scripts', function () {

	if ( file_exists(Assets\asset_path('scripts/main.js')) )
		wp_enqueue_script('nf/wp-js', Assets\asset_path('scripts/main.js'), ['jquery'], null, true);

	if ( file_exists(Assets\asset_path('styles/admin.css')) )
    	wp_enqueue_style('nf/wp-css', Assets\asset_path('styles/admin.css'), false, null);
});