<?php if (!defined('ABSPATH')) {
    exit;
}

if ( ! defined('WP_ENV')) {
    define('WP_ENV', 'production');
}

/**
 * ACF includes
 */
if ( ! class_exists('acf') && locate_template('/lib/acf/acf.php') ):

    // Load ACF from theme folder
    require_once locate_template('/lib/acf/acf.php');

    // Tweak paths
    add_filter('acf/settings/dir', function ($dir) {
        return get_template_directory_uri() . '/lib/acf/';
    });

    add_filter('acf/settings/path', function ($path) {
        return get_theme_root() . '/' . get_template() . '/lib/acf/';
    });

    // Disable ACF menu
    if (WP_ENV !== 'development') {
        add_filter('acf/settings/show_admin', '__return_false');
    }

    /**
     * ACF For Nav Menu support
     */
    // require_once locate_template('/lib/acf-nav/acf-location-nav-menu.php');

endif;

/**
 * Composer includes
 */
$composer = [
	'PostType' => '/lib/cpt/PostType.php',
	'Taxonomy' => '/lib/cpt/Taxonomy.php',
	'Columns'  => '/lib/cpt/Columns.php',
	'Options'  => '/lib/soil/soil.php',
];

foreach ($composer as $class => $path):

    if (!class_exists($class) && locate_template($path)) {
        require_once locate_template($path);
    }

endforeach;

/**
 * Production only Composer includes
 */
if (WP_ENV !== 'development'):

    $composer = [
        'ApacheServerConfig' => '/lib/h5bp/wp-h5bp-htaccess.php',
    ];

    foreach ($composer as $class => $path):

        if (!class_exists($class) && locate_template($path)) {
            require_once locate_template($path);
        }

    endforeach;
endif;



/**
 * Theme includes
 */
$includes = [
	'utils/assets.php',
	'utils/wrapper.php',
	'common/cleanup.php',
	'common/helpers.php',
	'common/wp-admin.php',
	'setup/class.env.php',
	'setup/acf-options.php',
	'setup/acf-widget.php',
	'setup/post-types.php',
	'setup/init.php',
	'components/class.functions.php',
	'components/class.ajax.php',
	'components/class.user.php',
];


if ( function_exists('is_woocommerce') ) {
	$includes[] = 'woo/tweaks.php';
}


foreach ( $includes as $file ) :

 	require_once locate_template('inc/' . $file);
endforeach;