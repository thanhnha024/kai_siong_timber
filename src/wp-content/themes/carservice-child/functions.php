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



function display_product_categories() {
    // Define the args for getting top-level product categories
    $args = array(
        'taxonomy'   => 'product_cat',
        'parent'     => 0,
        'hide_empty' => false,
        'exclude'    => array(get_term_by('slug', 'uncategorized', 'product_cat')->term_id),
    );

    // Get the product categories
    $product_categories = get_terms($args);

    if ($product_categories) {
        $output = '<ul class="product-categories">';

        foreach ($product_categories as $category) {
            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
            $image_url = ($thumbnail_id) ? wp_get_attachment_url($thumbnail_id) : '/wp-content/uploads/woocommerce-placeholder.png';

            $output .= '<li class="product-category">';
            
            $output .= '<a href="' . get_term_link($category) . '">';
            $output .= '<img src="' . $image_url . '" alt="' . $category->name . '">';
            $output .= '<span class="title-category">' . $category->name . '</span>';
            $output .= '</a>';
            $output .= '</li>';
        }

        $output .= '</ul>';

        return $output;
    }
}

// Register the shortcode
add_shortcode('product_categories1', 'display_product_categories');



