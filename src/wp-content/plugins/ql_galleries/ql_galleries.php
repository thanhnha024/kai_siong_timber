<?php
/*
Plugin Name: Theme Galleries
Plugin URI: https://1.envato.market/quanticalabs-portfolio
Description: Theme Galleries Plugin
Author: QuanticaLabs
Author URI: https://1.envato.market/quanticalabs-portfolio
Version: 1.7
Text Domain: ql_galleries
*/

//translation
function ql_galleries_load_textdomain()
{
	load_plugin_textdomain("ql_galleries", false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'ql_galleries_load_textdomain');
//custom post type - galleries
if(is_admin())
{
	function ql_galleries_admin_menu()
	{
		$permalinks_page = add_submenu_page('edit.php?post_type=ql_galleries', __('Permalink', 'ql_galleries'), __('Permalink', 'ql_galleries'), 'manage_options', 'ql_galleries_permalink', 'ql_galleries_permalink');
		add_action("admin_enqueue_scripts", "ql_galleries_admin_enqueue_scripts");
	}
	add_action("admin_menu", "ql_galleries_admin_menu");
	
	function ql_galleries_permalink()
	{
		$message = "";
		if(isset($_POST["action"]) && $_POST["action"]=="save_galleries_permalink")
			$message = __("Options saved!", "ql_galleries");
		$ql_galleries_permalink = array(
			"slug" => 'galleries',
			"label_singular" => __("Gallery", 'ql_galleries'),
			"label_plural" => __("Galleries", 'ql_galleries')
		);
		$ql_galleries_permalink = array_merge($ql_galleries_permalink, (array)get_option("ql_galleries_permalink"));
		
		require_once("admin/admin-page-permalink.php");
	}
	function ql_galleries_admin_enqueue_scripts($hook)
	{
		if($hook=="ql_galleries_page_ql_galleries_permalink")
		{
			wp_enqueue_style("ql_galleries-admin-style", plugins_url('admin/style.css', __FILE__));
		}
	}
}
function ql_galleries_init()
{
	$ql_galleries_permalink = array(
		"slug" => 'galleries',
		"label_singular" => __("Gallery", 'ql_galleries'),
		"label_plural" => __("Galleries", 'ql_galleries')
	);
	if(isset($_POST["action"]) && $_POST["action"]=="save_galleries_permalink")
	{
		$ql_galleries_permalink = array_merge($ql_galleries_permalink, (array)get_option("ql_galleries_permalink"));
		$slug_old = $ql_galleries_permalink["slug"];
		$ql_galleries_permalink = array(
			"slug" => (!empty($_POST["slug"]) ? sanitize_title($_POST["slug"]) : "galleries"),
			"label_singular" => (!empty($_POST["label_singular"]) ? $_POST["label_singular"] : __("Gallery", "ql_galleries")),
			"label_plural" => (!empty($_POST["label_plural"]) ? $_POST["label_plural"] : __("Galleries", "ql_galleries"))
		);
		update_option("ql_galleries_permalink", $ql_galleries_permalink);
		if($slug_old!=$_POST["slug"])
		{
			delete_option('rewrite_rules');
		}
	}
	$ql_galleries_permalink = array_merge($ql_galleries_permalink, (array)get_option("ql_galleries_permalink"));
	$labels = array(
		'name' => $ql_galleries_permalink['label_plural'],
		'singular_name' => $ql_galleries_permalink['label_singular'],
		'add_new' => _x('Add New', $ql_galleries_permalink["slug"], 'ql_galleries'),
		'add_new_item' => sprintf(__('Add New %s' , 'ql_galleries') , $ql_galleries_permalink['label_singular']),
		'edit_item' => sprintf(__('Edit %s', 'ql_galleries'), $ql_galleries_permalink['label_singular']),
		'new_item' => sprintf(__('New %s', 'ql_galleries'), $ql_galleries_permalink['label_singular']),
		'all_items' => sprintf(__('All %s', 'ql_galleries'), $ql_galleries_permalink['label_plural']),
		'view_item' => sprintf(__('View %s', 'ql_galleries'), $ql_galleries_permalink['label_singular']),
		'search_items' => sprintf(__('Search %s', 'ql_galleries'), $ql_galleries_permalink['label_plural']),
		'not_found' =>  sprintf(__('No %s found', 'ql_galleries'), strtolower($ql_galleries_permalink['label_plural'])),
		'not_found_in_trash' => sprintf(__('No %s found in Trash', 'ql_galleries'), strtolower($ql_galleries_permalink['label_plural'])), 
		'parent_item_colon' => '',
		'menu_name' => $ql_galleries_permalink['label_plural']
	);
	
	$args = array(  
		"labels" => $labels, 
		"public" => true,  
		"show_ui" => true,  
		"capability_type" => "post",
		"menu_position" => 20,
		"hierarchical" => false,  
		"rewrite" => array("slug" => $ql_galleries_permalink["slug"]),
		"supports" => array("title", "editor", "excerpt", "thumbnail", "page-attributes") 
	);
	register_post_type("ql_galleries", $args);
	register_taxonomy("ql_galleries_category", array("ql_galleries"), array("label" => __("Categories", 'ql_galleries'), "singular_label" => __("Category", 'ql_galleries'), "rewrite" => true));
}  
add_action("init", "ql_galleries_init"); 

//galleries items list
function ql_galleries_edit_columns($columns)
{
	$new_columns = array(  
		"cb" => "<input type=\"checkbox\" />",  
		"title" => _x('Title', 'post type singular name', 'ql_galleries'),
		"ql_galleries_category" => __('Categories', 'ql_galleries'),
		"order" =>  _x('Order', 'post type singular name', 'ql_galleries'),
		"date" => __('Date', 'ql_galleries')
	);    

	return array_merge($new_columns, $columns);  
}  
add_filter("manage_edit-ql_galleries_columns", "ql_galleries_edit_columns");

function manage_ql_galleries_posts_custom_column($column)
{
	global $post;
	switch($column)
	{
		case "ql_galleries_category":
			$ql_galleries_category_list = (array)get_the_terms($post->ID, "ql_galleries_category");
			foreach($ql_galleries_category_list as $ql_galleries_category)
			{
				if(empty($ql_galleries_category->slug))
					continue;
				echo '<a href="' . esc_url(admin_url("edit.php?post_type=ql_galleries&ql_galleries_category=" . $ql_galleries_category->slug)) . '">' . $ql_galleries_category->name . '</a>' . (end($ql_galleries_category_list)!=$ql_galleries_category ? ", " : "");;
			}
			break;
		case "order":
			echo get_post($post->ID)->menu_order;
			break;
	}
}
add_action("manage_ql_galleries_posts_custom_column", "manage_ql_galleries_posts_custom_column");

// Register the column as sortable
function ql_galleries_sortable_columns($columns) 
{
    $columns = array(
		"title" => "title",
		"order" => "menu_order",
		"date" => "date"
	);

    return $columns;
}
add_filter("manage_edit-ql_galleries_sortable_columns", "ql_galleries_sortable_columns");

function ql_galleries_shortcode($atts)
{
	extract(shortcode_atts(array(
		"items_per_page" => "-1",
		"category" => "",
		"ids" => "",
		"order_by" => "title,menu_order",
		"order" => "ASC",
		"type" => "list",
		"all_label" => __("All Galleries", 'ql_galleries'),
		"click_action" => "single_page",
		"lightbox_type" => "gallery",
		"headers" => 1,
		"read_more" => 1,
		"read_more_label" => __("READ MORE", 'ql_galleries'),
		"top_margin" => "none" 
	), $atts));
	
	$ids = explode(",", $ids);
	if($ids[0]=="-" || $ids[0]=="")
	{
		unset($ids[0]);
		$ids = array_values($ids);
	}
	$category = explode(",", $category);
	if($category[0]=="-" || $category[0]=="")
	{
		unset($category[0]);
		$category = array_values($category);
	}
	query_posts(array(
		'post__in' => $ids,
		'post_type' => 'ql_galleries',
		'posts_per_page' => $items_per_page,
		'post_status' => 'publish',
		'ql_galleries_category' => implode(",", $category),
		'orderby' => implode(" ", explode(",", $order_by)),
		'order' => $order
	));
	
	
	$output = "";
	if(have_posts())
	{
		if($type=="isotope")
		{
			$categories_count = count($category);
			$output .= '<div class="clearfix gray small"><ul class="ui-tabs-nav isotope-filters margin-top-70">';
			if($all_label!="")
				$output .= '<li>
						<a class="selected" href="#filter-*" title="' . ($all_label!='' ? esc_attr($all_label) : '') . '">' . ($all_label!='' ? $all_label : '') . '</a>
					</li>';
			for($i=0; $i<$categories_count; $i++)
			{
				$term = get_term_by('slug', $category[$i], "ql_galleries_category");
				$output .= '<li>
						<a href="#filter-' . trim($category[$i]) . '" title="' . esc_attr($term->name) . '">' . $term->name . '</a>
					</li>';
			}
			$output .= '</ul>';
		}
		$output .= '<ul class="galleries-list clearfix' . ($type=="isotope" ? ' isotope' : '') . ($top_margin!="none" ? ' ' . $top_margin : '') . '">';
		$timestamp = time();
		while(have_posts()): the_post();
			if($type=="isotope")
			{
				$categories = array_filter((array)get_the_terms(get_the_ID(), "ql_galleries_category"));
				$categories_count = count($categories);
				$categories_string = "";
				$i = 0;
				foreach($categories as $category)
				{
					$categories_string .= urldecode($category->slug) . ($i+1<$categories_count ? ' ' : '');
					$i++;
				}
			}
			if($click_action=="lightbox")
			{
				$thumb_id = get_post_thumbnail_id(get_the_ID());
				$attachment_image = wp_get_attachment_image_src($thumb_id, "large");
				$large_image_url = $attachment_image[0];
			}
			$output .= '<li' . ($type=="isotope" ? ' class="' . $categories_string . '"' : '') . '>';
			if($click_action=="lightbox")
				$output .= '<a href="' . esc_url($large_image_url) . '" title="' . esc_attr(get_the_title()) . '" class="prettyPhoto"' . ($lightbox_type=="gallery" ? ' rel="prettyPhoto[' . $timestamp . ']"' : '') . '>';
			else
				$output .= '<a href="' . get_permalink() . '" title="' . esc_attr(get_the_title()) . '">';
			$output .= get_the_post_thumbnail(get_the_ID(), ($type=="isotope" ? "small" : "big") . "-thumb" , array("alt" => get_the_title(), "title" => "")) . '
			</a>';
			if($click_action!="lightbox" && ((int)$headers || (int)$read_more))
			{
				$output .= '<div class="view align-center">
					<div class="vertical-align-table">
						<div class="vertical-align-cell">';
				if((int)$headers)
					$output .= '<p class="description">' . get_the_title() . '</p>';
				if((int)$read_more)
					$output .= '<a class="more simple" href="' . get_permalink() . '" title="' . esc_attr($read_more_label) . '">' . $read_more_label . '</a>';
				$output .= '</div>
					</div>
				</div>';
			}
		endwhile;
		$output .= '</ul>';
		if($type=="isotope")
			$output .= '</div>';
	}
	//Reset Query
	wp_reset_query();
	return $output;
}
add_shortcode("ql_galleries", "ql_galleries_shortcode");

//visual composer
function ql_galleries_vc_init()
{
	if(is_plugin_active("js_composer/js_composer.php") && function_exists('vc_map'))
	{
		//get galleries list
		$galleries_list = get_posts(array(
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'post_type' => 'ql_galleries'
		));
		$galleries_array = array();
		$galleries_array[__("All", 'ql_galleries')] = "-";
		foreach($galleries_list as $gallery)
			$galleries_array[$gallery->post_title . " (id:" . $gallery->ID . ")"] = $gallery->ID;
			
		//get galleries categories list
		$galleries_categories = get_terms("ql_galleries_category");
		$galleries_categories_array = array();
		$galleries_categories_array[__("All", 'ql_galleries')] = "-";
		foreach($galleries_categories as $galleries_category)
			$galleries_categories_array[$galleries_category->name] =  $galleries_category->slug;

		vc_map( array(
			"name" => __("Galleries list", 'ql_galleries'),
			"base" => "ql_galleries",
			"class" => "",
			"controls" => "full",
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-layer-custom-post-type-list",
			"category" => __('Plugins', 'ql_galleries'),
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Items per page/Post count", 'ql_galleries'),
					"param_name" => "items_per_page",
					"value" => -1,
					"description" => __("Set -1 to display all.", 'ql_galleries')
				),
				array(
					"type" => "dropdownmulti",
					"class" => "",
					"heading" => __("Display selected", 'ql_galleries'),
					"param_name" => "ids",
					"value" => $galleries_array
				),
				array(
					"type" => "dropdownmulti",
					"class" => "",
					"heading" => __("Display from Category", 'ql_galleries'),
					"param_name" => "category",
					"value" => $galleries_categories_array
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Order by", 'ql_galleries'),
					"param_name" => "order_by",
					"value" => array(__("Title, menu order", 'ql_galleries') => "title,menu_order", __("Menu order", 'ql_galleries') => "menu_order", __("Date", 'ql_galleries') => "date", __("Random", 'ql_galleries') => "rand")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Order", 'ql_galleries'),
					"param_name" => "order",
					"value" => array(__("ascending", 'ql_galleries') => "ASC", __("descending", 'ql_galleries') => "DESC")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Type", 'ql_galleries'),
					"param_name" => "type",
					"value" => array(__("list", 'ql_galleries') => "list", __("isotope", 'ql_galleries') => "isotope")
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("All filter label", 'ql_galleries'),
					"param_name" => "all_label",
					"value" => __("All Galleries", 'ql_galleries'),
					"dependency" => Array('element' => "type", 'value' => "isotope")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("On click action", 'ql_galleries'),
					"param_name" => "click_action",
					"value" => array(__("Single gallery page", 'ql_galleries') => "single_page", __("Lightbox image preview", 'ql_galleries') => "lightbox")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Lightbox type", 'ql_galleries'),
					"param_name" => "lightbox_type",
					"value" => array(__("Gallery", 'ql_galleries') => "gallery", __("Single previews", 'ql_galleries') => "single"),
					"dependency" => Array('element' => "click_action", 'value' => "lightbox")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Headers", 'ql_galleries'),
					"param_name" => "headers",
					"value" => array(__("Yes", 'ql_galleries') => 1, __("No", 'ql_galleries') => 0),
					"dependency" => Array('element' => "click_action", 'value' => "single_page")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Read more button", 'ql_galleries'),
					"param_name" => "read_more",
					"value" => array(__("Yes", 'ql_galleries') => 1, __("No", 'ql_galleries') => 0),
					"dependency" => Array('element' => "click_action", 'value' => "single_page")
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Read more button label", 'ql_galleries'),
					"param_name" => "read_more_label",
					"value" => __("READ MORE", 'ql_galleries'),
					"dependency" => Array('element' => "click_action", 'value' => "single_page")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Top margin", 'ql_galleries'),
					"param_name" => "top_margin",
					"value" => array(__("None", 'ql_galleries') => "none", __("Page (small)", 'ql_galleries') => "page-margin-top", __("Section (large)", 'ql_galleries') => "page-margin-top-section")
				)
			)
		));
	}
}
add_action("init", "ql_galleries_vc_init"); 
?>