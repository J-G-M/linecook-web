<?php
/**
 * Remove Yoast SEO plugin notifications
 */
add_action('admin_init', function () {

	if (is_plugin_active('wordpress-seo/wp-seo.php') && class_exists('Yoast_Notification_Center') ) :

		remove_action('admin_notices', array(Yoast_Notification_Center::get(), 'display_notifications'));
		remove_action('all_admin_notices', array(Yoast_Notification_Center::get(), 'display_notifications'));
	endif;
});



add_action('after_setup_theme', function () {

	if ( ! current_user_can('manage_options') && !is_admin() )
		show_admin_bar(false);

});



add_action( 'init', function () {

	remove_post_type_support('page', 'editor');
	remove_post_type_support('page', 'thumbnail');
	remove_post_type_support('page', 'comments');
	remove_post_type_support('page', 'trackbacks');
	remove_post_type_support('page', 'custom-fields');

	remove_post_type_support('post', 'excerpt');
	remove_post_type_support('post', 'trackbacks');
	remove_post_type_support('post', 'custom-fields');
	remove_post_type_support('post', 'post-formats');

	remove_post_type_support('product', 'excerpt');
}, 1001 );



/**
 * Remove some post meta
 */
add_action( 'add_meta_boxes', function () {

	remove_meta_box( 'thumbnaildiv', 'page', 'side' );
	remove_meta_box( 'commentsdiv', 'page', 'normal' );
	remove_meta_box( 'commentstatusdiv', 'page', 'normal' );

	remove_meta_box( 'slugdiv', 'post', 'normal' );

	remove_meta_box( 'postexcerpt', 'product', 'normal' );
	remove_meta_box( 'postcustom', 'product', 'normal' );
}, 989);


/**
 * Cleanup wp admin bar
 */
add_action( 'wp_before_admin_bar_render', function () {

	global $wp_admin_bar;

	$wp_admin_bar->remove_menu('wp-logo');
	$wp_admin_bar->remove_menu('about');
	$wp_admin_bar->remove_menu('wporg');
	$wp_admin_bar->remove_menu('documentation');
	$wp_admin_bar->remove_menu('support-forums');
	$wp_admin_bar->remove_menu('feedback');
	$wp_admin_bar->remove_menu('customize');
	$wp_admin_bar->remove_menu('updates');
	$wp_admin_bar->remove_menu('comments');
	// $wp_admin_bar->remove_menu('new-post');
	$wp_admin_bar->remove_menu('new-media');
	$wp_admin_bar->remove_menu('new-user');
	$wp_admin_bar->remove_menu('w3tc');
	$wp_admin_bar->remove_menu('search');
});




/**
 * Enable SVG files upload
 */
add_filter('upload_mimes', function ($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
});




/**
 * Enable SVG uploads in WP > 4.7
 */
add_filter( 'wp_check_filetype_and_ext', function ( $data, $file, $filename, $mimes ) {
	$wp_filetype = wp_check_filetype( $filename, $mimes );

	$ext             = $wp_filetype['ext'];
	$type            = $wp_filetype['type'];
	$proper_filename = $data['proper_filename'];

	return compact( 'ext', 'type', 'proper_filename' );
}, 10, 4 );





/**
 * WP Admin icon
 */
add_action('admin_head', function () {
	ob_start(); ?>

	<style type="text/css">
		#toplevel_page_wpseo_dashboard .update-plugins,
		#toplevel_page_wpseo_dashboard > .wp-submenu > li:last-child {display: none!important}
	</style>

	<?php echo ob_get_clean();
});




/**
 * Change admin footer text
 */
add_filter('admin_footer_text', function () {
	echo get_bloginfo( 'site_title' );
});



/**
 * WP Login Logo URL
 */
add_filter('login_headerurl', function () {
	return get_bloginfo( 'url' );
});





/**
 * WP Login Logo title
 */
add_filter('login_headertitle', function () {
	return get_bloginfo( 'title' );
});





/**
 * Repace WP Login style
 */
add_action( 'login_enqueue_scripts', function () {

	$logo = NF()->get_logo(true); ?>

	<style type="text/css">
		body.login {
			background: #1c1513;
		}

		#loginform {
			box-shadow: 0px 2px 10px 2px rgba(black, 0.15);
			border-radius: 5px;
		}

		.login #nav, .login #backtoblog {
			margin: 10px 0;
			padding: 0
		}

		.login #nav {
			float: right;
		}

		.login #backtoblog {
			display: none;
		}

		body.login div#login h1 a {
			width: 100%;
			height: 120px;
			margin: 0 auto;
			display: block;
			padding: 0;
			padding-bottom: 30px;
			background-size: contain;
			<?php if($logo): ?>
			background-image: url('<?= $logo; ?>');
			<?php endif; ?>
		}
	</style>
<?php });
