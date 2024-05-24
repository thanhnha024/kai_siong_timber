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



// Remove product cat base
add_filter('term_link', 'devvn_no_term_parents', 1000, 3);
function devvn_no_term_parents($url, $term, $taxonomy) {
    if($taxonomy == 'product_cat'){
        $term_nicename = $term->slug;
        $url = trailingslashit(get_option( 'home' )) . user_trailingslashit( $term_nicename, 'category' );
    }
    return $url;
}
// Add our custom product cat rewrite rules
function devvn_no_product_cat_parents_rewrite_rules($flash = false) {
    $terms = get_terms( array(
        'taxonomy' => 'product_cat',
        'post_type' => 'product',
        'hide_empty' => false,
    ));
    if($terms && !is_wp_error($terms)){
        foreach ($terms as $term){
            $term_slug = $term->slug;
            add_rewrite_rule($term_slug.'/?$', 'index.php?product_cat='.$term_slug,'top');
            add_rewrite_rule($term_slug.'/page/([0-9]{1,})/?$', 'index.php?product_cat='.$term_slug.'&paged=$matches[1]','top');
            add_rewrite_rule($term_slug.'/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?product_cat='.$term_slug.'&feed=$matches[1]','top');
        }
    }
    if ($flash == true)
        flush_rewrite_rules(false);
}
add_action('init', 'devvn_no_product_cat_parents_rewrite_rules');
/*Fix creat taxonomy 404*/
add_action( 'create_term', 'devvn_new_product_cat_edit_success', 10);
add_action( 'edit_terms', 'devvn_new_product_cat_edit_success', 10);
add_action( 'delete_term', 'devvn_new_product_cat_edit_success', 10);
function devvn_new_product_cat_edit_success( ) {
    devvn_no_product_cat_parents_rewrite_rules(true);
}