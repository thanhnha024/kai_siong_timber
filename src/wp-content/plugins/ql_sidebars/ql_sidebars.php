<?php
/*
Plugin Name: Theme Sidebars
Plugin URI: https://1.envato.market/quanticalabs-portfolio
Description:  Unlimited Sidebars Plugin
Author: QuanticaLabs
Author URI: https://1.envato.market/quanticalabs-portfolio
Version: 1.1
Text Domain: ql_sidebars
*/

//translation
function ql_sidebars_load_textdomain()
{
	load_plugin_textdomain("ql_sidebars", false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'ql_sidebars_load_textdomain');
global $themename;
//custom post type - sidebars
function ql_sidebars_init()
{
	global $themename;
	$theme_prefix = $themename;
	if($themename=="cleanmate")
		$theme_prefix = "cm";
	$labels = array(
		'name' => _x('Sidebars', 'post type general name', 'ql_sidebars'),
		'singular_name' => _x('Sidebar', 'post type singular name', 'ql_sidebars'),
		'add_new' => _x('Add New', $themename . '_sidebar', 'ql_sidebars'),
		'add_new_item' => __('Add New Sidebar', 'ql_sidebars'),
		'edit_item' => __('Edit Sidebar', 'ql_sidebars'),
		'new_item' => __('New Sidebar', 'ql_sidebars'),
		'all_items' => __('Sidebars', 'ql_sidebars'),
		'view_item' => __('View Sidebar', 'ql_sidebars'),
		'search_items' => __('Search Sidebars', 'ql_sidebars'),
		'not_found' =>  __('No sidebars found', 'ql_sidebars'),
		'not_found_in_trash' => __('No sidebars found in Trash', 'ql_sidebars'), 
		'parent_item_colon' => '',
		'menu_name' => __("Sidebars", 'ql_sidebars')
	);
	$args = array(  
		"labels" => $labels, 
		"public" => true,  
		"show_ui" => true,  
		"capability_type" => "post",
		"show_in_menu" => "themes.php",
		"hierarchical" => false,  
		"rewrite" => true,  
		"supports" => array("title", "page-attributes")
	);
	register_post_type($theme_prefix . "_sidebars", $args);
}  
add_action("init", "ql_sidebars_init"); 

//Adds a box to the main column on the Sidebars edit screens
function ql_add_sidebars_custom_box() 
{
	global $themename;
	$theme_prefix = $themename;
	if($themename=="cleanmate")
		$theme_prefix = "cm";
	add_meta_box( 
        "sidebars_config",
        __("Options", 'ql_sidebars'),
        "ql_inner_sidebars_custom_box_main",
        $theme_prefix . "_sidebars",
		"normal",
		"high"
    );
}
add_action("add_meta_boxes", "ql_add_sidebars_custom_box");
//backwards compatible (before WP 3.0)
//add_action("admin_init", "theme_add_custom_box", 1);

function ql_inner_sidebars_custom_box_main($post)
{
	global $themename;
	//Use nonce for verification
	wp_nonce_field(plugin_basename( __FILE__ ), $themename . "_sidebars_noncename");
	
	//The actual fields for data entry
	$before_widget = esc_attr(get_post_meta($post->ID, "before_widget", true));
	$after_widget = esc_attr(get_post_meta($post->ID, "after_widget", true));
	$before_title = esc_attr(get_post_meta($post->ID, "before_title", true));
	$after_title = esc_attr(get_post_meta($post->ID, "after_title", true));
	$hidden = get_post_meta($post->ID, "hidden", true);
	$hide_on_mobiles = get_post_meta($post->ID, "hide_on_mobiles", true);
	
	echo '
	<table>
		<tr>
			<td>
				<label for="before_widget">' . __('Before widget', 'ql_sidebars') . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="before_widget" name="before_widget" value="' . ($before_widget!='' ? ($before_widget!='empty' ? $before_widget : '') : '&lt;div id=\'%1$s\' class=\'widget %2$s\'&gt;') . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="after_widget">' . __('After widget', 'ql_sidebars') . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="after_widget" name="after_widget" value="' . ($after_widget!='' ? ($after_widget!='empty' ? $after_widget : '') : '&lt;/div&gt;') . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="before_title">' . __('Before title', 'ql_sidebars') . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="before_title" name="before_title" value="' . ($before_title!='' ? ($before_title!='empty' ? $before_title : '') : '&lt;h4 class=\'box-header\'&gt;') . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="after_title">' . __('After title', 'ql_sidebars') . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="after_title" name="after_title" value="' . ($after_title!='' ? ($after_title!='empty' ? $after_title : '') : '&lt;/h4&gt;') . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="hidden">' . __('Hidden', 'ql_sidebars') . ':</label>
			</td>
			<td>
				<select id="hidden" name="hidden">
					<option value="0"' . (!(int)$hidden ? ' selected="selected"' : '') . '>' . __('no', 'ql_sidebars') . '</option>
					<option value="1"' . ((int)$hidden ? ' selected="selected"' : '') . '>' . __('yes', 'ql_sidebars') . '</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="hide_on_mobiles">' . __('Hide on mobiles', 'ql_sidebars') . ':</label>
			</td>
			<td>
				<select id="hide_on_mobiles" name="hide_on_mobiles">
					<option value="0"' . (!(int)$hide_on_mobiles ? ' selected="selected"' : '') . '>' . __('no', 'ql_sidebars') . '</option>
					<option value="1"' . ((int)$hide_on_mobiles ? ' selected="selected"' : '') . '>' . __('yes', 'ql_sidebars') . '</option>
				</select>
			</td>
		</tr>
	</table>';
}

//When the post is saved, saves our custom data
function ql_save_sidebars_postdata($post_id) 
{
	global $themename;
	//verify if this is an auto save routine. 
	//if it is our form has not been submitted, so we dont want to do anything
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return;

	//verify this came from the our screen and with proper authorization,
	//because save_post can be triggered at other times
	if((isset($_POST[$themename . '_sidebars_noncename']) && !wp_verify_nonce($_POST[$themename . '_sidebars_noncename'], plugin_basename( __FILE__ ))) || !isset($_POST[$themename . '_sidebars_noncename']))
		return;


	//Check permissions
	if(!current_user_can('edit_post', $post_id))
		return;

	//OK, we're authenticated: we need to find and save the data
	update_post_meta($post_id, "before_widget", ($_POST["before_widget"]=="" ? "empty" : $_POST["before_widget"]));
	update_post_meta($post_id, "after_widget", ($_POST["after_widget"]=="" ? "empty" : $_POST["after_widget"]));
	update_post_meta($post_id, "before_title", ($_POST["before_title"]=="" ? "empty" : $_POST["before_title"]));
	update_post_meta($post_id, "after_title", ($_POST["after_title"]=="" ? "empty" : $_POST["after_title"]));
	update_post_meta($post_id, "hidden", $_POST["hidden"]);
	update_post_meta($post_id, "hide_on_mobiles", $_POST["hide_on_mobiles"]);
}
add_action("save_post", "ql_save_sidebars_postdata");
?>