<?php
//Adds a box to the main column on the Page edit screens
function cs_theme_add_custom_box() 
{
	global $themename;
    add_meta_box( 
        "page-custom-options",
        __("Options", 'carservice'),
        "cs_theme_inner_custom_box",
        "page",
		"normal",
		"core"
    );
}
add_action("add_meta_boxes", "cs_theme_add_custom_box");
//backwards compatible (before WP 3.0)
//add_action("admin_init", "theme_add_custom_box", 1);

// Prints the box content
function cs_theme_inner_custom_box($post)
{
	global $themename;
	//Use nonce for verification
	wp_nonce_field(plugin_basename( __FILE__ ), $themename . "_noncename");
}

//When the post is saved, saves our custom data
function cs_theme_save_postdata($post_id) 
{
	global $themename;
	// verify if this is an auto save routine. 
	// If it is our form has not been submitted, so we dont want to do anything
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return;
		
	//verify this came from the our screen and with proper authorization,
	//because save_post can be triggered at other times
	if((isset($_POST[$themename . '_noncename']) && !wp_verify_nonce($_POST[$themename . '_noncename'], plugin_basename( __FILE__ ))) || !isset($_POST[$themename . '_noncename']))
		return;

	// Check permissions
	if(!current_user_can('edit_post', $post_id))
		return;
		
	//OK, we're authenticated: we need to find and save the data
	//sidebars
	update_post_meta($post_id, "page_sidebar_footer_top", (isset($_POST["page_sidebar_footer_top"]) ? $_POST["page_sidebar_footer_top"] : ''));
	update_post_meta($post_id, "page_sidebar_footer_bottom", (isset($_POST["page_sidebar_footer_bottom"]) ? $_POST["page_sidebar_footer_bottom"] : ''));
	update_post_meta($post_id, $themename . "_page_sidebars", array_values(array_filter(array(
		(!empty($_POST["page_sidebar_footer_top"]) ? $_POST["page_sidebar_footer_top"] : NULL),
		(!empty($_POST["page_sidebar_footer_bottom"]) ? $_POST["page_sidebar_footer_bottom"] : NULL)
	))));
}
add_action("save_post", "cs_theme_save_postdata");
?>