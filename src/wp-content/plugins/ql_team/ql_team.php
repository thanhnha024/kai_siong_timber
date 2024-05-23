<?php
/*
Plugin Name: Theme Team
Plugin URI: https://1.envato.market/quanticalabs-portfolio
Description: Theme Team Plugin
Author: QuanticaLabs
Author URI: https://1.envato.market/quanticalabs-portfolio
Version: 2.2
Text Domain: ql_team
*/

//translation
function ql_team_load_textdomain()
{
	load_plugin_textdomain("ql_team", false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'ql_team_load_textdomain');
//custom post type - team
if(is_admin())
{
	function ql_team_admin_menu()
	{
		$permalinks_page = add_submenu_page('edit.php?post_type=ql_team', __('Permalink', 'ql_team'), __('Permalink', 'ql_team'), 'manage_options', 'ql_team_permalink', 'ql_team_permalink');
		add_action("admin_enqueue_scripts", "ql_team_admin_enqueue_scripts");
	}
	add_action("admin_menu", "ql_team_admin_menu");
	
	function ql_team_permalink()
	{
		$message = "";
		if(isset($_POST["action"]) && $_POST["action"]=="save_team_permalink")
			$message = __("Options saved!", "ql_team");
		$ql_team_permalink = array(
			"slug" => 'team',
			"label_singular" => __("Team Member", 'ql_team'),
			"label_plural" => __("Team", 'ql_team')
		);
		$ql_team_permalink = array_merge($ql_team_permalink, (array)get_option("ql_team_permalink"));
		
		require_once("admin/admin-page-permalink.php");
	}
	function ql_team_admin_enqueue_scripts($hook)
	{
		if($hook=="ql_team_page_ql_team_permalink")
		{
			wp_enqueue_style("ql_team-admin-style", plugins_url('admin/style.css', __FILE__));
		}
	}
}
function ql_team_init()
{
	$ql_team_permalink = array(
		"slug" => 'team',
		"label_singular" => __("Team Member", 'ql_team'),
		"label_plural" => __("Team", 'ql_team')
	);
	if(isset($_POST["action"]) && $_POST["action"]=="save_team_permalink")
	{
		$ql_team_permalink = array_merge($ql_team_permalink, (array)get_option("ql_team_permalink"));
		$slug_old = $ql_team_permalink["slug"];
		$ql_team_permalink = array(
			"slug" => (!empty($_POST["slug"]) ? sanitize_title($_POST["slug"]) : "team"),
			"label_singular" => (!empty($_POST["label_singular"]) ? $_POST["label_singular"] : __("Team Member", "ql_team")),
			"label_plural" => (!empty($_POST["label_plural"]) ? $_POST["label_plural"] : __("Team", "ql_team"))
		);
		update_option("ql_team_permalink", $ql_team_permalink);
		if($slug_old!=$_POST["slug"])
		{
			delete_option('rewrite_rules');
		}
	}
	$ql_team_permalink = array_merge($ql_team_permalink, (array)get_option("ql_team_permalink"));
	$labels = array(
		'name' => $ql_team_permalink['label_plural'],
		'singular_name' => $ql_team_permalink['label_singular'],
		'add_new' => _x('Add New', $ql_team_permalink["slug"], 'ql_team'),
		'add_new_item' => sprintf(__('Add New %s' , 'ql_team') , $ql_team_permalink['label_singular']),
		'edit_item' => sprintf(__('Edit %s', 'ql_team'), $ql_team_permalink['label_singular']),
		'new_item' => sprintf(__('New %s', 'ql_team'), $ql_team_permalink['label_singular']),
		'all_items' => $ql_team_permalink['label_plural'],
		'view_item' => sprintf(__('View %s', 'ql_team'), $ql_team_permalink['label_singular']),
		'search_items' => sprintf(__('Search %s', 'ql_team'), $ql_team_permalink['label_plural']),
		'not_found' =>  sprintf(__('No %s found', 'ql_team'), strtolower($ql_team_permalink['label_singular'])),
		'not_found_in_trash' => sprintf(__('No %s found in Trash', 'ql_team'), strtolower($ql_team_permalink['label_singular'])), 
		'parent_item_colon' => '',
		'menu_name' => $ql_team_permalink['label_plural']
	);
	
	$args = array(  
		"labels" => $labels, 
		"public" => true,  
		"show_ui" => true,  
		"capability_type" => "post",
		"menu_position" => 20,
		"hierarchical" => false,  
		"rewrite" => array("slug" =>  $ql_team_permalink["slug"]),
		"supports" => array("title", "editor", "excerpt", "thumbnail", "page-attributes") 
	);
	register_post_type("ql_team", $args);
}  
add_action("init", "ql_team_init"); 

//Adds a box to the right column and to the main column on the Team edit screens
function ql_team_add_custom_box() 
{
	add_meta_box( 
        "ql_team_config",
        __("Options", 'ql_team'),
        "ql_team_inner_custom_box_main",
        "ql_team",
		"normal",
		"high"
    );
}
add_action("add_meta_boxes", "ql_team_add_custom_box");

function ql_team_inner_custom_box_main($post)
{
	//Use nonce for verification
	wp_nonce_field(plugin_basename( __FILE__ ), "ql_team_noncename");
	
	//The actual fields for data entry
	$external_url_target = get_post_meta($post->ID, "external_url_target", true);
	$icon_type = (array)get_post_meta($post->ID, "social_icon_type", true);
	$icon_url = get_post_meta($post->ID, "social_icon_url", true);
	$icon_target = get_post_meta($post->ID, "social_icon_target", true);
	
	$icons = array(
		"angies-list",
		"behance",
		"deviantart",
		"dribbble",
		"email",
		"envato",
		"facebook",
		"flickr",
		"foursquare",
		"github",
		"google-plus",
		"houzz",
		"instagram",
		"linkedin",
		"location",
		"mobile",
		"paypal",
		"pinterest",
		"reddit",
		"rss",
		"skype",
		"soundcloud",
		"spotify",
		"stumbleupon",
		"tumblr",
		"twitter",
		"weibo",
		"whatsapp",
		"vimeo",
		"vine",
		"vk",
		"xing",
		"yelp",
		"youtube"
	);
	
	echo '
	<table>
		<tr>
			<td>
				<label for="ql_team_subtitle">' . __('Subtitle', 'ql_team') . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="ql_team_subtitle" name="ql_team_subtitle" value="' . esc_attr(get_post_meta($post->ID, "subtitle", true)) . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="ql_team_external_url">' . __('External URL (optional)', 'ql_team') . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="ql_team_external_url" name="ql_team_external_url" value="' . esc_attr(get_post_meta($post->ID, "external_url", true)) . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="ql_team_external_url_target">' . __('External URL target', 'ql_team') . ':</label>
			</td>
			<td>
				<select id="ql_team_external_url_target" name="ql_team_external_url_target">
					<option value="same_window"' . (isset($external_url_target) && $external_url_target=="same_window" ? ' selected="selected"' : '') . '>' . __('same window', 'ql_team') . '</option>
					<option value="new_window"' . (isset($external_url_target) && $external_url_target=="new_window" ? ' selected="selected"' : '') . '>' . __('new window', 'ql_team') . '</option>
				</select>
			</td>
		</tr>
	</table>
	<div class="clearfix">
		<table>
			<tr valign="top">
				<th scope="row" style="font-weight: bold;">
					' . __('Social icons', 'ql_team') . '
				</th>
			</tr>';
			for($i=0; $i<(count($icon_type)<4 ? 4 : count($icon_type)); $i++)
			{
			echo '
			<tr class="repeated_row_id_1 repeated_row_' . ($i+1) . '">
				<td colspan="2">
					<table>
						<tr>
							<td>
								<label>' . __('Icon type', 'ql_team') . " " . ($i+1) . ':</label>
							</td>
							<td>
								<select id="icon_type_' . ($i+1) . '" name="icon_type[]">
									<option value="">-</option>';
									for($j=0; $j<count($icons); $j++)
									{
									echo '<option value="' . esc_attr($icons[$j]) . '"' . (isset($icon_type[$i]) && $icons[$j]==$icon_type[$i] ? " selected='selected'" : "") . '>' . $icons[$j] . '</option>';
									}
							echo '</select>
							</td>
						</tr>
						<tr>
							<td>
								<label>' . __('Icon url', 'ql_team') . " " . ($i+1) . ':</label>
							</td>
							<td>
								<input type="text" class="regular-text" value="' . esc_attr($icon_url[$i]) . '" name="icon_url[]">
							</td>
						</tr>
						<tr>
							<td>
								<label>' . __('Icon target', 'ql_team') . " " . ($i+1) . ':</label>
							</td>
							<td>
								<select name="icon_target[]">
									<option value="same_window"' . ($icon_target[$i]=="same_window" ? " selected='selected'" : "") . '>' . __('same window', 'ql_team') . '</option>
									<option value="new_window"' . ($icon_target[$i]=="new_window" ? " selected='selected'" : "") . '>' . __('new window', 'ql_team') . '</option>
								</select>
							</td>
						</tr>
					</table>
					<br />
				</td>
			</tr>';
			}
			echo '
			<tr>
				<td colspan="2">
					<input type="button" class="button add_new_repeated_row" name="add_new_repeated_row" id="repeated_row_id_1" value="' . __('Add icon', 'ql_team') . '" />
				</td>
			</tr>
		</table>
	</div>';
}

//When the post is saved, saves our custom data
function ql_team_save_postdata($post_id) 
{
	//verify if this is an auto save routine. 
	//if it is our form has not been submitted, so we dont want to do anything
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return;

	//verify this came from the our screen and with proper authorization,
	//because save_post can be triggered at other times
	if (!isset($_POST['ql_team_noncename']) || !wp_verify_nonce($_POST['ql_team_noncename'], plugin_basename( __FILE__ )))
		return;


	//Check permissions
	if(!current_user_can('edit_post', $post_id))
		return;

	//OK, we're authenticated: we need to find and save the data
	update_post_meta($post_id, "subtitle", $_POST["ql_team_subtitle"]);
	update_post_meta($post_id, "external_url", $_POST["ql_team_external_url"]);
	update_post_meta($post_id, "external_url_target", $_POST["ql_team_external_url_target"]);
	$icon_type = (array)$_POST["icon_type"];
	while(end($icon_type)==="")
		array_pop($icon_type);
	update_post_meta($post_id, "social_icon_type", $icon_type);
	update_post_meta($post_id, "social_icon_url", $_POST["icon_url"]);
	update_post_meta($post_id, "social_icon_target", $_POST["icon_target"]);
}
add_action("save_post", "ql_team_save_postdata");

//custom sidebars items list
function ql_team_edit_columns($columns)
{
	$new_columns = array(  
		"cb" => "<input type=\"checkbox\" />",  
		"title" => _x('Title', 'post type singular name', 'ql_team'),
		"order" =>  _x('Order', 'post type singular name', 'ql_team'),
		"date" => __('Date', 'ql_team')
	);    

	return array_merge($new_columns, $columns);  
}  
add_filter("manage_edit-ql_team_columns", "ql_team_edit_columns");

function manage_ql_team_posts_custom_column($column)
{
	global $post;
	switch($column)
	{
		case "order":
			echo get_post($post->ID)->menu_order;
			break;
	}
}
add_action("manage_ql_team_posts_custom_column", "manage_ql_team_posts_custom_column");

// Register the column as sortable
function ql_team_sortable_columns($columns) 
{
    $columns = array(
		"title" => "title",
		"order" => "menu_order",
		"date" => "date"
	);

    return $columns;
}
add_filter("manage_edit-ql_team_sortable_columns", "ql_team_sortable_columns");

function ql_team_shortcode($atts)
{
	$currentTheme = wp_get_theme();
	extract(shortcode_atts(array(
		"items_per_page" => "-1",
		"ids" => "",
		"order_by" => "title,menu_order",
		"order" => "ASC",
		"headers" => 1,
		"headers_links" => 1,
		"headers_border" => 1,
		"show_subtitle" => 1,
		"show_excerpt" => 1,
		"show_social_icons" => 1,
		"show_featured_image" => 1,
		"featured_image_links" => 1,
		"class" => "",
		"top_margin" => "none" 
	), $atts));
	
	$ids = explode(",", $ids);
	if($ids[0]=="-" || $ids[0]=="")
	{
		unset($ids[0]);
		$ids = array_values($ids);
	}
	query_posts(array(
		'post__in' => $ids,
		'post_type' => 'ql_team',
		'posts_per_page' => $items_per_page,
		'post_status' => 'publish',
		'orderby' => implode(" ", explode(",", $order_by)),
		'order' => $order
	));
	
	
	$output = "";
	if(have_posts())
	{
		$i=0;
		$divider = (strpos($currentTheme, "Finpeak")===false ? 3 : 4);
		$output .= '<ul class="team-list clearfix' . (!empty($class) ? ' ' . esc_attr($class) : '') . (!empty($top_margin) && $top_margin!="none" ? ' ' . $top_margin : '') . '">';
		while(have_posts()): the_post();
			if($i==0 || $i%$divider==0)
			{
				if($i%$divider==0 && $i>0)
				{
					$output .= '</ul></li>';
				}
				$output .= '<li class="vc_row wpb_row vc_row-fluid"><ul>';
			}
			$output .= '<li class="team-box' . (strpos($currentTheme, "Finpeak")!==false ? ' wpb_column vc_column_container vc_col-sm-3' : '') . '">';
			if(strpos($currentTheme, "Finpeak")!==false)
			{
				$output .= '<div class="team-member-container">';
			}
			$external_url = get_post_meta(get_the_ID(), "external_url", true);
			$external_url_target = get_post_meta(get_the_ID(), "external_url_target", true);
			if((int)$show_featured_image)
			{
				if((int)$featured_image_links)
				{
					$output .= '<a' . ($external_url!="" && $external_url_target=="new_window" ? ' target="_blank"' : '') . ' href="' . ($external_url!="" ? esc_url($external_url) : get_permalink()) . '" title="' . esc_attr(get_the_title()) . '"' . (strpos($currentTheme, "Finpeak")!==false ? ' class="image-box"' : '') . '>';
				}
				else if(strpos($currentTheme, "Finpeak")!==false)
				{
					$output .= '<div class="image-box">';
				}
				$output .= get_the_post_thumbnail(get_the_ID(), (strpos($currentTheme, "Finpeak")!==false ? "finpeak-" : "") . "medium-" . (strpos($currentTheme, "Finpeak")!==false ? "rectangle-" : "") . "thumb" , array("alt" => get_the_title(), "title" => ""));
				if((int)$featured_image_links)
				{
					$output .= '</a>';
				}
				else if(strpos($currentTheme, "Finpeak")!==false)
				{
					$output .= '</div>';
				}
			}
			$arrayEmpty = true;
			if((int)$show_social_icons)
			{
				$icon_type = get_post_meta(get_the_ID(), "social_icon_type", true);	
				for($j=0; $j<count($icon_type); $j++)
				{
					if($icon_type[$j]!="")
						$arrayEmpty = false;
				}
			}
			if((int)$show_subtitle)
				$subtitle = get_post_meta(get_the_ID(), "subtitle", true);
			if((int)$headers || ((int)$show_subtitle && !empty($subtitle)) || ((int)$show_excerpt && has_excerpt()))
				$output .= '<div class="team-content' . (!(int)$headers && (!(int)$show_subtitle || empty($subtitle)) ? ' padding-top-0' : '') . (!$arrayEmpty ? ' with-social-icons' : '') . '">';
			if((int)$headers || ((int)$show_subtitle && !empty($subtitle)))
				$output .= '<h4' . ((int)$headers_border && strpos($currentTheme, "Finpeak")===false ? ' class="box-header"' : '') . '>' . ((int)$headers ? ((int)$headers_links ? '<a' . ($external_url!="" && $external_url_target=="new_window" ? ' target="_blank"' : '') . ' href="' . ($external_url!="" ? esc_url($external_url) : get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">' : '') . get_the_title() .  ((int)$headers_links ? '</a>' : '') : '') . ((int)$show_subtitle && !empty($subtitle) && strpos($currentTheme, "Finpeak")===false ? '<span>' . $subtitle . '</span>' : '') . '</h4>';
			if(strpos($currentTheme, "Finpeak")!==false && (int)$show_subtitle && !empty($subtitle))
			{
				$output .= '<label>' . ((int)$headers_links ? '<a' . ($external_url!="" && $external_url_target=="new_window" ? ' target="_blank"' : '') . ' href="' . ($external_url!="" ? esc_url($external_url) : get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">' : '') . $subtitle . ((int)$headers_links ? '</a>' : '') . '</label>';
			}
			if((int)$show_excerpt && has_excerpt())
				$output .= apply_filters('the_excerpt', get_the_excerpt());
			if((int)$headers || ((int)$show_subtitle && !empty($subtitle)) || ((int)$show_excerpt && has_excerpt()))
				$output .= '</div>';
			if(!$arrayEmpty)
			{
				$icon_url = get_post_meta(get_the_ID(), "social_icon_url", true);
				$icon_target = get_post_meta(get_the_ID(), "social_icon_target", true);
				$output .= '<ul class="social-icons' . (!(int)$show_featured_image ? ' social-static clearfix' : '') . '">';
				for($j=0; $j<count($icon_type); $j++)
				{
					if($icon_type[$j]!="")
						$output .= '<li><a class="social-' . esc_attr($icon_type[$j]) . '" href="' . esc_url($icon_url[$j]) . '"' . ($icon_target[$j]=='new_window' ? ' target="_blank"' : '') . ' title="">' . (strpos($currentTheme, "Finpeak")!==false ? '' : '&nbsp;') . '</a></li>';
				}
				$output .= '</ul>';
			}
			if(strpos($currentTheme, "Finpeak")!==false)
			{
				$output .= '</div>';
			}
			$output .= '</li>';
			$i++;
		endwhile;
		$output .= '</ul></li></ul>';
	}
	//Reset Query
	wp_reset_query();
	return $output;
}
add_shortcode("ql_team", "ql_team_shortcode");

//visual composer
function ql_team_vc_init()
{
	if(is_plugin_active("js_composer/js_composer.php") && function_exists('vc_map'))
	{
		$currentTheme = wp_get_theme();
		//get team members list
		$team_list = get_posts(array(
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'post_type' => 'ql_team'
		));
		$team_array = array();
		$team_array[__("All", 'ql_team')] = "-";
		foreach($team_list as $team)
			$team_array[$team->post_title . " (id:" . $team->ID . ")"] = $team->ID;

		$params = array(
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Items per page/Post count", 'ql_team'),
				"param_name" => "items_per_page",
				"value" => -1,
				"description" => __("Set -1 to display all.", 'ql_team')
			),
			array(
				"type" => "dropdownmulti",
				"class" => "",
				"heading" => __("Display selected", 'ql_team'),
				"param_name" => "ids",
				"value" => $team_array
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Order by", 'ql_team'),
				"param_name" => "order_by",
				"value" => array(__("Title, menu order", 'ql_team') => "title,menu_order", __("Menu order", 'ql_team') => "menu_order", __("Date", 'ql_team') => "date", __("Random", 'ql_team') => "rand")
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Order", 'ql_team'),
				"param_name" => "order",
				"value" => array(__("ascending", 'ql_team') => "ASC", __("descending", 'ql_team') => "DESC")
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Headers", 'ql_team'),
				"param_name" => "headers",
				"value" => array(__("Yes", 'ql_team') => 1, __("No", 'ql_team') => 0)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Headers links", 'ql_team'),
				"param_name" => "headers_links",
				"value" => array(__("Yes", 'ql_team') => 1, __("No", 'ql_team') => 0)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Headers border", 'ql_team'),
				"param_name" => "headers_border",
				"value" => array(__("Yes", 'ql_team') => 1, __("No", 'ql_team') => 0)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Show subtitle", 'ql_team'),
				"param_name" => "show_subtitle",
				"value" => array(__("Yes", 'ql_team') => 1, __("No", 'ql_team') => 0)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Show excerpt", 'ql_team'),
				"param_name" => "show_excerpt",
				"value" => array(__("Yes", 'ql_team') => 1, __("No", 'ql_team') => 0)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Show social icons", 'ql_team'),
				"param_name" => "show_social_icons",
				"value" => array(__("Yes", 'ql_team') => 1, __("No", 'ql_team') => 0)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Show featured image", 'ql_team'),
				"param_name" => "show_featured_image",
				"value" => array(__("Yes", 'ql_team') => 1, __("No", 'ql_team') => 0)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Featured image links", 'ql_team'),
				"param_name" => "featured_image_links",
				"value" => array(__("Yes", 'ql_team') => 1, __("No", 'ql_team') => 0),
				"dependency" => Array('element' => "show_featured_image", 'value' => '1')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Extra class name", 'ql_team'),
				"param_name" => "class",
				"value" => ""
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Top margin", 'ql_team'),
				"param_name" => "top_margin",
				"value" => array(__("None", 'ql_team') => "none", __("Page (small)", 'ql_team') => "page-margin-top", __("Section (large)", 'ql_team') => "page-margin-top-section")
			)
		);
		if(strpos($currentTheme, "Finpeak")!==false)
		{
			unset($params[6]);
		}
		vc_map( array(
			"name" => __("Team list", 'ql_team'),
			"base" => "ql_team",
			"class" => "",
			"controls" => "full",
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-layer-custom-post-type-list",
			"category" => __('Plugins', 'ql_team'),
			"params" => $params
		));
	}
}
add_action("init", "ql_team_vc_init"); 
?>