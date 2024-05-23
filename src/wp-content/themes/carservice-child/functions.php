<?php
function child_theme_enqueue_styles()
{
	wp_enqueue_style("parent-style", get_template_directory_uri() . "/style.css", array("reset", "superfish", "prettyPhoto", "jquery-qtip", "odometer", "animations"));
}
add_action("wp_enqueue_scripts", "child_theme_enqueue_styles", 12);
function child_theme_enqueue_rtl_styles()
{
	if (is_rtl())
		wp_enqueue_style("parent-rtl", get_template_directory_uri() . "/rtl.css");
}
add_action("wp_enqueue_scripts", "child_theme_enqueue_rtl_styles", 13);

/*
 * Define Variables
 */
if (!defined('THEME_DIR'))
	define('THEME_DIR', get_template_directory());
if (!defined('THEME_URL'))
	define('THEME_URL', get_template_directory_uri());


/*
 * Include framework files
 */
foreach (glob(THEME_DIR . '-child' . "/includes/*.php") as $file_name) {
	require_once($file_name);
}


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
