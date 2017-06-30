<?php

use PostTypes\PostType;

if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * @link https://github.com/jjgrainger/PostTypes
 */
$opt = [
	'supports'            => ['editor', 'title'],
	'has_archive'         => false,
	'exclude_from_search' => true,
	'publicly_queryable'  => false,
	'show_in_nav_menus'   => false,
	'show_in_admin_bar'   => false
];
$locations = new PostType('location', $opt);
$locations->icon('dashicons-location');