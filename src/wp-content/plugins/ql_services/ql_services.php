<?php
/*
Plugin Name: Theme Services
Plugin URI: https://1.envato.market/quanticalabs-portfolio
Description: Theme Services Plugin
Author: QuanticaLabs
Author URI: https://1.envato.market/quanticalabs-portfolio
Version: 3.2
Text Domain: ql_services
*/

//translation
function ql_services_load_textdomain()
{
	load_plugin_textdomain("ql_services", false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'ql_services_load_textdomain');
//custom post type - services
if(is_admin())
{
	function ql_services_admin_menu()
	{
		$permalinks_page = add_submenu_page('edit.php?post_type=ql_services', __('Permalink', 'ql_services'), __('Permalink', 'ql_services'), 'manage_options', 'ql_services_permalink', 'ql_services_permalink');
		add_action("admin_enqueue_scripts", "ql_services_admin_enqueue_scripts");
	}
	add_action("admin_menu", "ql_services_admin_menu");
	
	function ql_services_permalink()
	{
		$message = "";
		if(isset($_POST["action"]) && $_POST["action"]=="save_services_permalink")
			$message = __("Options saved!", "ql_services");
		$ql_services_permalink = array(
			"slug" => 'services',
			"label_singular" => __("Service", 'ql_services'),
			"label_plural" => __("Services", 'ql_services')
		);
		$currentTheme = wp_get_theme();
		if(strpos($currentTheme, "Finpeak")!==false)
		{
			$ql_services_permalink["category_slug"] = 'services_category';
		}
		$ql_services_permalink = array_merge($ql_services_permalink, (array)get_option("ql_services_permalink"));
		
		require_once("admin/admin-page-permalink.php");
	}
	function ql_services_admin_enqueue_scripts($hook)
	{
		if($hook=="ql_services_page_ql_services_permalink")
		{
			wp_enqueue_style("ql_services-admin-style", plugins_url('admin/style.css', __FILE__));
		}
	}
}
function ql_services_init()
{
	$currentTheme = wp_get_theme();
	$ql_services_permalink = array(
		"slug" => 'services',
		"label_singular" => __("Service", 'ql_services'),
		"label_plural" => __("Services", 'ql_services')
	);
	if(strpos($currentTheme, "Finpeak")!==false)
	{
		$ql_services_permalink["category_slug"] = 'services_category';
	}
	if(isset($_POST["action"]) && $_POST["action"]=="save_services_permalink")
	{
		$ql_services_permalink = array_merge($ql_services_permalink, (array)get_option("ql_services_permalink"));
		$slug_old = $ql_services_permalink["slug"];
		if(strpos($currentTheme, "Finpeak")!==false)
		{
			$category_slug_old = $ql_services_permalink["category_slug"];
		}
		$ql_services_permalink = array(
			"slug" => (!empty($_POST["slug"]) ? sanitize_title($_POST["slug"]) : "services"),
			"label_singular" => (!empty($_POST["label_singular"]) ? $_POST["label_singular"] : __("Service", "ql_services")),
			"label_plural" => (!empty($_POST["label_plural"]) ? $_POST["label_plural"] : __("Services", "ql_services"))
		);
		if(strpos($currentTheme, "Finpeak")!==false)
		{
			$ql_services_permalink["category_slug"] = (!empty($_POST["category_slug"]) ? sanitize_title($_POST["category_slug"]) : "services_category");
		}
		update_option("ql_services_permalink", $ql_services_permalink);
		if($slug_old!=$_POST["slug"] || (strpos($currentTheme, "Finpeak")!==false && $category_slug_old!=$_POST["category_slug"]))
		{
			delete_option('rewrite_rules');
		}
	}
	$ql_services_permalink = array_merge($ql_services_permalink, (array)get_option("ql_services_permalink"));
	$labels = array(
		'name' => $ql_services_permalink['label_plural'],
		'singular_name' => $ql_services_permalink['label_singular'],
		'add_new' => _x('Add New', $ql_services_permalink["slug"], 'ql_services'),
		'add_new_item' => sprintf(__('Add New %s' , 'ql_services') , $ql_services_permalink['label_singular']),
		'edit_item' => sprintf(__('Edit %s', 'ql_services'), $ql_services_permalink['label_singular']),
		'new_item' => sprintf(__('New %s', 'ql_services'), $ql_services_permalink['label_singular']),
		'all_items' => sprintf(__('All %s', 'ql_services'), $ql_services_permalink['label_plural']),
		'view_item' => sprintf(__('View %s', 'ql_services'), $ql_services_permalink['label_singular']),
		'search_items' => sprintf(__('Search %s', 'ql_services'), $ql_services_permalink['label_plural']),
		'not_found' =>  sprintf(__('No %s found', 'ql_services'), strtolower($ql_services_permalink['label_plural'])),
		'not_found_in_trash' => sprintf(__('No %s found in Trash', 'ql_services'), strtolower($ql_services_permalink['label_plural'])), 
		'parent_item_colon' => '',
		'menu_name' => $ql_services_permalink['label_plural']
	);
	
	$args = array(  
		"labels" => $labels, 
		"public" => true,  
		"show_ui" => true,  
		"capability_type" => "post",
		"menu_position" => 20,
		"hierarchical" => false,  
		"rewrite" => array("slug" => $ql_services_permalink["slug"]),
		"supports" => array("title", "editor", "excerpt", "thumbnail", "page-attributes")
	);
	register_post_type("ql_services", $args);
	if(strpos($currentTheme, "Finpeak")!==false)
	{
		register_taxonomy("ql_services_category", array("ql_services"), array("label" => __("Categories", 'ql_services'), "singular_label" => __("Category", 'ql_services'), "rewrite" => array("slug" => $ql_services_permalink["category_slug"])));
	}
	
	//check for re_services posts
	/*if(!get_option("services_updated"))
	{	
		$services = get_posts(array(
			'post_type' => 're_services',
			'posts_per_page' => -1
		));
		foreach($services as $service)
			set_post_type($service->ID, "ql_services");
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
		add_option("services_updated", 1);
	}*/
}  
add_action("init", "ql_services_init"); 

//custom sidebars items list
function ql_services_services_edit_columns($columns)
{
	$currentTheme = wp_get_theme();
	if(strpos($currentTheme, "Finpeak")===false)
	{
		$new_columns = array(  
			"cb" => "<input type=\"checkbox\" />",  
			"title" => _x('Title', 'post type singular name', 'ql_services'),
			"order" =>  _x('Order', 'post type singular name', 'ql_services'),
			"date" => __('Date', 'ql_services')
		);
	}
	else
	{
		$new_columns = array(  
			"cb" => "<input type=\"checkbox\" />",  
			"title" => _x('Title', 'post type singular name', 'ql_services'),
			"ql_services_category" => __('Categories', 'ql_services'),
			"order" =>  _x('Order', 'post type singular name', 'ql_services'),
			"date" => __('Date', 'ql_services')
		);
	}

	return array_merge($new_columns, $columns);  
}  
add_filter("manage_edit-ql_services_columns", "ql_services_services_edit_columns");

function manage_ql_services_services_posts_custom_column($column)
{
	global $post;
	switch($column)
	{
		case "ql_services_category":
			$ql_services_category_list = (array)get_the_terms($post->ID, "ql_services_category");
			foreach($ql_services_category_list as $ql_services_category)
			{
				if(empty($ql_services_category->slug))
					continue;
				echo '<a href="' . esc_url(admin_url("edit.php?post_type=ql_services&ql_services_category=" . $ql_services_category->slug)) . '">' . $ql_services_category->name . '</a>' . (end($ql_services_category_list)!=$ql_services_category ? ", " : "");;
			}
			break;
		case "order":
			echo get_post($post->ID)->menu_order;
			break;
	}
}
add_action("manage_ql_services_posts_custom_column", "manage_ql_services_services_posts_custom_column");

// Register the column as sortable
function ql_services_services_sortable_columns($columns) 
{
    $columns = array(
		"title" => "title",
		"order" => "menu_order",
		"date" => "date"
	);

    return $columns;
}
add_filter("manage_edit-ql_services_sortable_columns", "ql_services_services_sortable_columns");

function ql_services_shortcode($atts)
{
	$currentTheme = wp_get_theme();
	extract(shortcode_atts(array(
		"type" => "images_list",
		"display" => "list",
		"borders" => 0,
		"featured_image_bg" => 0,
		"items_per_page" => "-1",
		"category" => "",
		"ids" => "",
		"spacing" => 0,
		"order_by" => "title,menu_order",
		"order" => "ASC",
		"headers" => 1,
		"headers_links" => 1,
		"headers_border" => (strpos($currentTheme, "MediCenter")!==false || strpos($currentTheme, "Finpeak")!==false ? 0 : 1),
		"show_categories" => 1,
		"show_excerpt" => 1,
		"excerpt_size" => "small",
		"show_icon" => 1,
		"icons_links" => 1,
		"icon_size" => "small",
		"icon_tick" => 0,
		"show_featured_image" => 1,
		"featured_image_links" => 1,
		"scroll_control" => 1,
		"read_more" => 0,
		"read_more_label" => __("Learn more", 'ql_services'),
		"class" => "",
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
	$args = array(
		'include' => $ids,
		'post_type' => 'ql_services',
		'posts_per_page' => $items_per_page,
		'post_status' => 'publish',
		'ql_services_category' => (get_query_var('ql_services_category')=="" ? implode(",", $category) : get_query_var('ql_services_category')),
		'orderby' => implode(" ", explode(",", $order_by)),
		'order' => $order
	);
	$posts_list = get_posts($args);
	if($display=="carousel" && is_rtl())
		$posts_list = array_reverse($posts_list);
	
	$output = "";
	if(count($posts_list))
	{
		$i=0;
		$divider = ($type=="icons_list" && strpos($currentTheme, "CleanMate")===false ? 2 : 3);
		if($display=="carousel")
			$output .= '<div class="carousel-container clearfix' . (!empty($class) ? ' ' . esc_attr($class) : '') . (!empty($top_margin) && $top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . '">';
		$output .= '<ul class="services-list clearfix' . (!empty($type) && $type=="icons_list" ? ' services-icons' : '') . (!empty($display) && $display=="carousel" ? ' horizontal-carousel' : '') . ((int)$borders ? ' services-items-border' : '') . (!empty($spacing) && (int)$spacing ? ' services-spacing' : '') . (!empty($class) && $display!="carousel" ? ' ' . esc_attr($class) : '') . (!empty($top_margin) && $top_margin!="none" && $display!="carousel" ? ' ' . esc_attr($top_margin) : '') . '">';
		global $post;
		$currentPost = $post;
		foreach($posts_list as $post)
		{
			setup_postdata($post);
			if(($i==0 || $i%$divider==0) && $display!="carousel")
			{
				if($i%$divider==0 && $i>0)
				{
					$output .= '</ul></li>';
				}
				$output .= '<li class="vc_row wpb_row vc_row-fluid"><ul>';
			}
			$output .= '<li' . (($type=="icons_list" && (int)$featured_image_bg && has_post_thumbnail(get_the_ID())) || ((int)$show_excerpt && has_excerpt() && $excerpt_size=="big") ? ' class="' : '') . ($type=="icons_list" && (int)$featured_image_bg && has_post_thumbnail(get_the_ID()) ? 'cm-background cm-overlay' : '') . ((int)$show_excerpt && has_excerpt() && $excerpt_size=="big" ? ' excerpt-big-size' : '') . (($type=="icons_list" && (int)$featured_image_bg && has_post_thumbnail(get_the_ID())) || ((int)$show_excerpt && has_excerpt() && $excerpt_size=="big") ? '"' : '') . ($type=="icons_list" && (int)$featured_image_bg && has_post_thumbnail(get_the_ID()) ? ' style="background-image: url(' . esc_url(get_the_post_thumbnail_url(get_the_ID(), (strpos($currentTheme, "Finpeak")!==false ? "finpeak-" : "") . "medium-square-thumb")) . ')"' : '') .'>';
			$custom_url = get_post_meta(get_the_ID(), "ql_services_custom_url", true);
			if($type=="icons_list")
			{
				if((int)$show_icon)
				{
					$icon = "";
					$icon = get_post_meta(get_the_ID(), "icon", true);
					if(!empty($icon))
					{
						if(strpos($currentTheme, "Carservice")!==false)
							$output .= '<div class="hexagon small">';
						if((int)$icons_links)
							$output .= '<a href="' . (!empty($custom_url) ? esc_url($custom_url) : get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">';
						$output .= '<span class="service-icon ' . (substr($icon, 0, 9)=="sl-small-" || substr($icon, 0, 9)=="features-" ? '' : 'sl-small-') . esc_attr($icon) . ($icon_size=="big" ? ' big' : '') . ((int)$icon_tick==1 ? ' tick' : ''). '"></span>';
						if((int)$icons_links)
							$output .= '</a>';
						if(strpos($currentTheme, "Carservice")!==false)
							$output .= '</div>';
						$output .= '<div class="service-content">';
					}
				}
			}
			else if((int)$show_featured_image)
			{
				if((int)$featured_image_links)
					$output .= '<a href="' . (!empty($custom_url) ? esc_url($custom_url) : get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">';
				$output .= get_the_post_thumbnail(get_the_ID(), (strpos($currentTheme, "Finpeak")!==false ? "finpeak-" : "") . "medium-thumb" , array("alt" => get_the_title(), "title" => ""));
				if((int)$featured_image_links)
					$output .= '</a>';
			}
			if((strpos($currentTheme, "MediCenter")!==false || strpos($currentTheme, "Finpeak")!==false) && $type!="icons_list" && ((int)$headers || ((int)$show_excerpt && has_excerpt()) || (int)$read_more))
				$output .= '<div class="service-details">';
			if(strpos($currentTheme, "Finpeak")!==false && (int)$show_categories)
			{
				$output .= '<ul class="services-categories">';
				$categories = array_filter((array)get_the_terms(get_the_ID(), "ql_services_category"));
				foreach($categories as $category)
				{
					$output .= '<li><a href="' . esc_url(get_term_link($category)) . '" title="' . esc_attr($category->name) . '">' . $category->name . '</a></li>';
				}
				$output .= '</ul>';
			}
			if((int)$headers)
				$output .= '<h' . (strpos($currentTheme, "Finpeak")!==false ? '3' : '4') . ((int)$headers_border ? ' class="box-header"' : '') . '>' . ((int)$headers_links ? '<a href="' . (!empty($custom_url) ? esc_url($custom_url) : get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">' : '') . get_the_title() .  ((int)$headers_links ? '</a>' : '') . '</h' . (strpos($currentTheme, "Finpeak")!==false ? '3' : '4') . '>';
			if((int)$show_excerpt && has_excerpt())
				$output .= apply_filters('the_excerpt', do_shortcode(get_the_excerpt()));
			if((int)$read_more)
				$output .= (strpos($currentTheme, "Finpeak")===false ? '<div class="align-center margin-top-42 padding-bottom-16">' : '') . '<a class="more" href="' . (!empty($custom_url) ? esc_url($custom_url) : get_permalink()) . '" title="' . esc_attr($read_more_label) . '">' . $read_more_label . '</a>' . (strpos($currentTheme, "Finpeak")===false ? '</div>' : '');
			if((strpos($currentTheme, "MediCenter")!==false || strpos($currentTheme, "Finpeak")!==false) && $type!="icons_list" && ((int)$headers || ((int)$show_excerpt && has_excerpt()) || (int)$read_more))
				$output .= '</div>';
			if($type=="icons_list" && (int)$show_icon && !empty($icon))
				$output .= '</div>';
			$output .= '</li>';
			$i++;
		}
		if($display!="carousel")
			$output .= '</ul></li>';
		$output .= '</ul>';
		$post = $currentPost;
		if($display=="carousel")
		{
			if((int)$scroll_control)
				$output .= '<div class="' . (strpos($currentTheme, "Finpeak")!==false ? 'finpeak-' : 'cm-') . 'carousel-pagination"></div>';
			$output .= '</div>';
		}
	}
	return $output;
}
add_shortcode("ql_services", "ql_services_shortcode");
add_shortcode("re_services", "ql_services_shortcode");

//visual composer
function ql_services_vc_init()
{
	if(is_plugin_active("js_composer/js_composer.php") && function_exists('vc_map'))
	{
		$currentTheme = wp_get_theme();
		//get services list
		$services_list = get_posts(array(
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'post_type' => 'ql_services'
		));
		$services_array = array();
		$services_array[__("All", 'ql_services')] = "-";
		foreach($services_list as $service)
			$services_array[$service->post_title . " (id:" . $service->ID . ")"] = $service->ID;
		
		if(strpos($currentTheme, "Finpeak")!==false)
		{
			//get services categories list
			$services_categories = get_terms("ql_services_category");
			$services_categories_array = array();
			$services_categories_array[__("All", 'ql_services')] = "-";
			foreach($services_categories as $services_category)
				$services_categories_array[$services_category->name] =  $services_category->slug;
		}
			
		$params = array(
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Type", 'ql_services'),
				"param_name" => "type",
				"value" => array(__("List with images", 'ql_services') => "images_list", __("List with icons", 'ql_services') => "icons_list")
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Borders on list items", 'ql_services'),
				"param_name" => "borders",
				"value" => array(__("No", 'ql_services') => 0, __("Yes", 'ql_services') => 1),
				"dependency" => array('element' => "type", 'value' => 'icons_list')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Items per page/Post count", 'ql_services'),
				"param_name" => "items_per_page",
				"value" => -1,
				"description" => __("Set -1 to display all.", 'ql_services')
			),
			array(
				"type" => "dropdownmulti",
				"class" => "",
				"heading" => __("Display selected", 'ql_services'),
				"param_name" => "ids",
				"value" => $services_array
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Order by", 'ql_services'),
				"param_name" => "order_by",
				"value" => array(__("Title, menu order", 'ql_services') => "title,menu_order", __("Menu order", 'ql_services') => "menu_order", __("Date", 'ql_services') => "date", __("Random", 'ql_services') => "rand")
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Order", 'ql_services'),
				"param_name" => "order",
				"value" => array(__("ascending", 'ql_services') => "ASC", __("descending", 'ql_services') => "DESC")
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Headers", 'ql_services'),
				"param_name" => "headers",
				"value" => array(__("Yes", 'ql_services') => 1, __("No", 'ql_services') => 0)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Headers links", 'ql_services'),
				"param_name" => "headers_links",
				"value" => array(__("Yes", 'ql_services') => 1, __("No", 'ql_services') => 0)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Headers border", 'ql_services'),
				"param_name" => "headers_border",
				"value" => array(__("Yes", 'ql_services') => 1, __("No", 'ql_services') => 0),
				"std" => (strpos(wp_get_theme(), "MediCenter")!==false ? 0 : 1)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Show excerpt", 'ql_services'),
				"param_name" => "show_excerpt",
				"value" => array(__("Yes", 'ql_services') => 1, __("No", 'ql_services') => 0)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Show icon", 'ql_services'),
				"param_name" => "show_icon",
				"value" => array(__("Yes", 'ql_services') => 1, __("No", 'ql_services') => 0),
				"dependency" => array('element' => "type", 'value' => 'icons_list')
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Icons links", 'ql_services'),
				"param_name" => "icons_links",
				"value" => array(__("Yes", 'ql_services') => 1, __("No", 'ql_services') => 0),
				"dependency" => array('element' => "show_icon", 'value' => '1')
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Show featured image", 'ql_services'),
				"param_name" => "show_featured_image",
				"value" => array(__("Yes", 'ql_services') => 1, __("No", 'ql_services') => 0),
				"dependency" => array('element' => "type", 'value' => array('images_list'))
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Featured image links", 'ql_services'),
				"param_name" => "featured_image_links",
				"value" => array(__("Yes", 'ql_services') => 1, __("No", 'ql_services') => 0),
				"dependency" => array('element' => "show_featured_image", 'value' => '1')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Extra class name", 'ql_services'),
				"param_name" => "class",
				"value" => ""
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Top margin", 'ql_services'),
				"param_name" => "top_margin",
				"value" => (strpos($currentTheme, "Finpeak")!==false ? array(__("None", 'ql_services') => "none", __("Page (small)", 'ql_services') => "page-margin-top", __("Section (medium)", 'ql_services') => "page-margin-top-section", __("Section large (large)", 'ql_services') => "page-margin-top-section-large") : array(__("None", 'ql_services') => "none", __("Page (small)", 'ql_services') => "page-margin-top", __("Section (large)", 'ql_services') => "page-margin-top-section"))
			)
		);
		if(strpos(wp_get_theme(), "CleanMate")!==false)
		{
			$new_params = array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Display as", 'ql_services'),
					"param_name" => "display",
					"value" => array(__("List", 'ql_services') => "list", __("Carousel", 'ql_services') => "carousel")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Scroll control (pagination)", 'ql_services'),
					"param_name" => "scroll_control",
					"value" => array(__("Yes", 'ql_services') => 1, __("No", 'ql_services') => 0),
					"dependency" => array('element' => "display", 'value' => 'carousel')
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Featured image as background", 'ql_services'),
					"param_name" => "featured_image_bg",
					"value" => array(__("No", 'ql_services') => 0, __("Yes", 'ql_services') => 1),
					"dependency" => array('element' => "type", 'value' => 'icons_list')
				)
			);
			array_splice($params, 1, 0, $new_params);
			$new_params = array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Read more button", 'ql_services'),
					"param_name" => "read_more",
					"value" => array(__("No", 'ql_services') => 0, __("Yes", 'ql_services') => 1)
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Read more button label", 'ql_services'),
					"param_name" => "read_more_label",
					"value" => __("Learn more", 'ql_services'),
					"dependency" => Array('element' => "read_more", 'value' => "1")
				)
			);
			array_splice($params, -2, 0, $new_params);
			$new_params = array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Excerpt font size", 'ql_services'),
					"param_name" => "excerpt_size",
					"value" => array(__("Small", 'ql_services') => "small", __("Big", 'ql_services') => "big"),
					"dependency" => array('element' => "show_excerpt", 'value' => '1')
				)
			);
			array_splice($params, -8, 0, $new_params);
			$new_params = array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Icon size", 'ql_services'),
					"param_name" => "icon_size",
					"value" => array(__("Small", 'ql_services') => "small", __("Big", 'ql_services') => "big"),
					"dependency" => array('element' => "show_icon", 'value' => '1')
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Icon tick", 'ql_services'),
					"param_name" => "icon_tick",
					"value" => array(__("No", 'ql_services') => 0, __("Yes", 'ql_services') => 1),
					"dependency" => array('element' => "show_icon", 'value' => '1')
				)
			);
			array_splice($params, -6, 0, $new_params);
		}
		else if(strpos(wp_get_theme(), "MediCenter")!==false)
		{
			$new_params = array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Items spacing", 'ql_services'),
					"param_name" => "spacing",
					"value" => array(__("No", 'ql_services') => 0, __("Yes", 'ql_services') => 1),
					"dependency" => array('element' => "type", 'value' => 'images_list')
				)
			);
			array_splice($params, 4, 0, $new_params);
		}
		else if(strpos(wp_get_theme(), "Finpeak")!==false)
		{
			$new_params = array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Display as", 'ql_services'),
					"param_name" => "display",
					"value" => array(__("List", 'ql_services') => "list", __("Carousel", 'ql_services') => "carousel")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Scroll control (pagination)", 'ql_services'),
					"param_name" => "scroll_control",
					"value" => array(__("Yes", 'ql_services') => 1, __("No", 'ql_services') => 0),
					"dependency" => array('element' => "display", 'value' => 'carousel')
				)
			);
			array_splice($params, 0, 1, $new_params);
			$new_params = array(
				array(
						"type" => "dropdownmulti",
						"class" => "",
						"heading" => __("Display from Category", 'ql_services'),
						"param_name" => "category",
						"value" => $services_categories_array
				)
			);
			array_splice($params, 2, 0, $new_params);
			$new_params = array(
				array(
						"type" => "dropdown",
						"class" => "",
						"heading" => __("Show categories", 'ql_services'),
						"param_name" => "show_categories",
						"value" => array(__("Yes", 'ql_services') => 1, __("No", 'ql_services') => 0)
				)
			);
			array_splice($params, 9, 0, $new_params);
			$new_params = array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Read more button", 'ql_services'),
					"param_name" => "read_more",
					"value" => array(__("No", 'ql_services') => 0, __("Yes", 'ql_services') => 1)
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Read more button label", 'ql_services'),
					"param_name" => "read_more_label",
					"value" => __("Learn more", 'ql_services'),
					"dependency" => Array('element' => "read_more", 'value' => "1")
				)
			);
			array_splice($params, -2, 0, $new_params);
			unset($params[3], $params[11], $params[13], $params[14]);
		}

		vc_map( array(
			"name" => __("Services list", 'ql_services'),
			"base" => "ql_services",
			"class" => "",
			"controls" => "full",
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-layer-custom-post-type-list",
			"category" => __('Plugins', 'ql_services'),
			"params" => $params
		));
	}
}
add_action("init", "ql_services_vc_init"); 
?>