<?php if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Option pages
 */
if ( function_exists('acf_add_options_page') ) {

	acf_add_options_page([
		'page_title' => 'Theme Options',
		'menu_title' => 'Theme Options',
		'menu_slug'  => 'theme-options',
		'capability' => 'manage_options',
		'redirect'   => true
	]);

	acf_add_options_sub_page(array(
		'title'  => 'Settings',
		'parent' => 'theme-options',
	));

	acf_add_options_sub_page(array(
		'title'  => 'Email',
		'parent' => 'theme-options',
	));


	if ( current_theme_supports('nf_sidebars') ) {

		acf_add_options_sub_page(array(
			'title'  => 'Sidebars',
			'parent' => 'theme-options',
		));
	}
}



/**
 * Google Maps ACF API
 */
add_action('acf/init', function () {

	$key = get_key('google_maps_api');

	if ( ! $key )
		$key = 'AIzaSyABrpl6oLFxNhGRmlt9zOVjCCZgiKVba1Q';


	acf_update_setting('google_api_key', $key);
});




/**
 * Save / Load fields from JSON
 */
add_filter('acf/settings/save_json', function ( $path ) {

	return get_stylesheet_directory() . '/inc/acf-json';
});

add_filter('acf/settings/load_json', function ( $paths ) {

	unset($paths[0]);

	$paths[] = get_stylesheet_directory() . '/inc/acf-json';

	return $paths;
});





/**
 * Populate Gravity Forms 'Select Form' ACF custom field
 */
add_filter('acf/load_field/name=gform_id', function ( $field ) {

	global $wpdb;

	$sql   = "SELECT * FROM {$wpdb->prefix}rg_form";
	$forms = $wpdb->get_results( $sql );

	if ( ! $forms )
		return $field;

	$field['choices'] = [];

	foreach ( $forms as $k => $v ) {
		$field['choices'][ $v->id ] = apply_filters('the_title', $v->title);
	}

	return $field;
});




/**
 * Populate 'Select Sidebar'
 */
add_filter('acf/load_field/name=select_sidebar', function ( $field ) {

	if ( ! get_key('custom_sidebars') )
		return  $field;

	$field['choices'] = [];

	foreach ( get_key('custom_sidebars') as $sidebar ) :

		$id = sanitize_title( $sidebar['sidebar_name'] );
		$field['choices'][$id] = $sidebar['sidebar_name'];

	endforeach;

	return $field;
});


/**
 * Select Social Network in Theme Options
 */
add_filter('acf/load_field/name=network', function ( $field ) {

	$networks         = ['Facebook', 'Instagram', 'Twitter'];
	$field['choices'] = [];

	if ( $networks ) :
		foreach ( $networks as $net ) {
			$field['choices'][ sanitize_title($net) ] = apply_filters('the_title', $net);
		}
	endif;

	return $field;
});