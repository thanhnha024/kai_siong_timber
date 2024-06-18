<?php
function cs_theme_post_carousel_shortcode($atts)
{
	global $theme_options;
	extract(shortcode_atts(array(
		"items_per_page" => 6,
		"offset" => 0,
		"featured_image_size" => "default",
		"ids" => "",
		"category" => "",
		"author" => "",
		"order_by" => "date",
		"order" => "DESC",
		"visible" => 3,
		"show_post_title" => 1,
		"show_post_excerpt" => 1,
		"excerpt_length" => 0,
		"read_more" => 0,
		"show_post_categories" => 0,
		"show_post_tags" => 0,
		"show_post_author" => 0,
		"show_post_date" => 1,
		"show_post_views" => 1,
		"show_post_comments" => 1,
		"autoplay" => 0,
		"pause_on_hover" => 1,
		"scroll" => 1,
		"scroll_control" => 1,
		"effect" => "scroll",
		"easing" => "swing",
		"duration" => 500,
		"ontouch" => 0,
		"onmouse" => 0,
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
	$author = explode(",", $author);
	if($author[0]=="-" || $author[0]=="")
	{
		unset($author[0]);
		$author = array_values($author);
	}
	if(in_array("current", (array)$author))
	{
		$author = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
		$author = array($author->ID);
	}
	$args = array( 
		'include' => $ids,
		'post_type' => 'post',
		'posts_per_page' => $items_per_page,
		'offset' => (int)$offset,
		//'nopaging' => true,
		'post_status' => 'publish',
		'cat' => ((get_query_var('cat')!="" && empty($category)) ? get_query_var('cat') : ''),
		'category_name' => (!empty($category) ? implode(",", $category) : ''),
		'author__in' => $author,
		'orderby' => ($order_by=="views" || $order_by=="rate" ? 'meta_value_num' : implode(" ", explode(",", $order_by))),
		'order' => $order
	);
	if($order_by=="views")
		$args['meta_key'] = 'post_views_count';
	
	$posts_list = get_posts($args);
	if(is_rtl())
		$posts_list = array_reverse($posts_list);
	$output = '';
	if(count($posts_list))
		$output .= '<div class="carousel-container clearfix' . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . '"><ul class="blog horizontal-carousel three-columns visible-' . (int)esc_attr($visible) . ' autoplay-' . (int)esc_attr($autoplay) . ' pause_on_hover-' . (int)esc_attr($pause_on_hover) . ' scroll-' . (int)esc_attr($scroll) . '">';
	$i=0;
	global $post;
	$currentPost = $post;
	$category_filter_array = $category;
	foreach($posts_list as $post) 
	{
		setup_postdata($post);
		require_once(locate_template("shortcodes/class/Post.php"));
		$carousel_post = new Cs_Post("blog", $featured_image_size, (int)$show_post_date, (int)$show_post_views, (int)$show_post_comments, (int)$show_post_categories, (int)$show_post_tags, (int)$show_post_excerpt, (int)$show_post_author, $i);
		$output .= $carousel_post->getLiCssClass("vc_col-sm-4 wpb_column vc_column_container");
		$output .= $carousel_post->getThumbnail("big-thumb");
		$output .= $carousel_post->getPostDetails();
		if((int)$show_post_title)
		{
			$output .= '<h4><a href="' . esc_url(get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">' . get_the_title() . '</a></h4>';
		}
		if((int)$show_post_excerpt)
		{
			$output .= apply_filters('the_excerpt', (!has_excerpt() && (int)$excerpt_length && strlen(get_the_excerpt())>=(int)$excerpt_length && strpos(get_the_excerpt(), ' ', (int)$excerpt_length) ? substr(get_the_excerpt(), 0, strpos(get_the_excerpt(), ' ', (int)$excerpt_length)) . "..." : get_the_excerpt()));
		}
		if((int)$read_more)
		{
			$output .= '<div class="vc_row wpb_row vc_row-fluid padding-top-54 padding-bottom-17"><a title="' . esc_attr__('READ MORE', 'carservice') . '" href="' . esc_url(get_permalink()) . '" class="more">' . __('READ MORE', 'carservice') . '</a></div>';
		}
		$output .= '</li>';
		$i++;
	}
	$post = $currentPost;
	
	if(count($posts_list))
	{
		$output .= '</ul>';
		if((int)$scroll_control)
			$output .= '<div class="cs-carousel-pagination"></div>';
		$output .= '</div>';
	}
	return $output;
}

//visual composer
function cs_theme_post_carousel_vc_init()
{
	//get posts list
	global $carservice_posts_array;

	//get categories
	$post_categories = get_terms("category");
	$post_categories_array = array();
	$post_categories_array[__("All", 'carservice')] = "-";
	foreach($post_categories as $post_category)
		$post_categories_array[$post_category->name] =  $post_category->slug;
	
	//get authors list
	$authors_args = array(
		"capability" => array("edit_posts")
	);
	//Capability queries were only introduced in WP 5.9.
	if(version_compare($GLOBALS['wp_version'], "5.9", "<"))
	{
		$authors_args["who"] = "authors";
		unset($authors_args["capability"]);
	}
	$authors_list = get_users($authors_args);
	$authors_array = array();
	$authors_array[__("All", 'carservice')] = "-";
	$authors_array[__("Current author (applies on author single page)", 'carservice')] = "current";
	foreach($authors_list as $author)
		$authors_array[$author->display_name . " (id:" . $author->ID . ")"] = $author->ID;
	
	//image sizes
	$image_sizes_array = array();
	$image_sizes_array[__("Default", 'carservice')] = "default";
	global $_wp_additional_image_sizes;
	foreach(get_intermediate_image_sizes() as $s) 
	{
		if(isset($_wp_additional_image_sizes[$s])) 
		{
			$width = intval($_wp_additional_image_sizes[$s]['width']);
			$height = intval($_wp_additional_image_sizes[$s]['height']);
		}
		else
		{
			$width = get_option($s.'_size_w');
			$height = get_option($s.'_size_h');
		}
		$image_sizes_array[$s . " (" . $width . "x" . $height . ")"] = $s;
	}
	$params = array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Post count", 'carservice'),
			"param_name" => "items_per_page",
			"value" => 6,
			"description" => __("Set -1 to display all.", 'carservice')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Offset", 'carservice'),
			"param_name" => "offset",
			"value" => 0,
			"description" => __("Number of post to displace or pass over.", 'carservice')
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Featured image size", 'carservice'),
			"param_name" => "featured_image_size",
			"value" => $image_sizes_array
		),
		array(
			"type" => (count($carservice_posts_array) ? "dropdownmulti" : "textfield"),
			"class" => "",
			"heading" => __("Display selected", 'carservice'),
			"param_name" => "ids",
			"value" => (count($carservice_posts_array) ? $carservice_posts_array : ''),
			"description" => (count($carservice_posts_array) ? '' : __("Please provide post ids separated with commas, to display only selected posts", 'carservice'))
		),
		array(
			"type" => "dropdownmulti",
			"class" => "",
			"heading" => __("Display from Category", 'carservice'),
			"param_name" => "category",
			"value" => $post_categories_array
		),
		array(
			"type" => "dropdownmulti",
			"class" => "",
			"heading" => __("Display by author", 'carservice'),
			"param_name" => "author",
			"value" => $authors_array
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order by", 'carservice'),
			"param_name" => "order_by",
			"value" => array(__("Date", 'carservice') => "date", __("Title, menu order", 'carservice') => "title menu_order", __("Menu order", 'carservice') => "menu_order", __("Post views", 'carservice') => "views", __("Comment count", 'carservice') => "comment_count", __("Random", 'carservice') => "rand")
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order", 'carservice'),
			"param_name" => "order",
			"value" => array( __("descending", 'carservice') => "DESC", __("ascending", 'carservice') => "ASC")
		),
		/*array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Visible items", 'carservice'),
			"param_name" => "visible",
			"value" => 3,
			"description" => __("Number of visible items in carousel", 'carservice')
		),*/
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show post title", 'carservice'),
			"param_name" => "show_post_title",
			"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show post excerpt", 'carservice'),
			"param_name" => "show_post_excerpt",
			"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Excerpt length", 'carservice'),
			"param_name" => "excerpt_length",
			"value" => 0,
			"description" => __("The excerpt length. Set 0 to use default WordPress excerpt length.", 'carservice'),
			"dependency" => Array('element' => "show_post_excerpt", 'value' => "1")
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Read more button", 'carservice'),
			"param_name" => "read_more",
			"value" => array(__("No", 'carservice') => 0, __("Yes", 'carservice') => 1)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show post categories", 'carservice'),
			"param_name" => "show_post_categories",
			"value" => array(__("No", 'carservice') => 0, __("Yes", 'carservice') => 1)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show post tags", 'carservice'),
			"param_name" => "show_post_tags",
			"value" => array(__("No", 'carservice') => 0, __("Yes", 'carservice') => 1)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show post author", 'carservice'),
			"param_name" => "show_post_author",
			"value" => array(__("No", 'carservice') => 0, __("Yes", 'carservice') => 1)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show post date", 'carservice'),
			"param_name" => "show_post_date",
			"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show post views", 'carservice'),
			"param_name" => "show_post_views",
			"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show comments number", 'carservice'),
			"param_name" => "show_post_comments",
			"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Autoplay", 'carservice'),
			"param_name" => "autoplay",
			"value" => array(__("No", 'carservice') => 0, __("Yes", 'carservice') => 1)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Pause on hover", 'carservice'),
			"param_name" => "pause_on_hover",
			"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0),
			"dependency" => Array('element' => "autoplay", 'value' => '1')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Scroll", 'carservice'),
			"param_name" => "scroll",
			"value" => 1,
			"description" => __("Number of items to scroll in one step (max: 3)", 'carservice')
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Scroll control (pagination)", 'carservice'),
			"param_name" => "scroll_control",
			"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Top margin", 'carservice'),
			"param_name" => "top_margin",
			"value" => array(__("None", 'carservice') => "none", __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section")
		)
	);
	vc_map( array(
		"name" => __("Post Carousel", 'carservice'),
		"base" => "cs_post_carousel",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-carousel",
		"category" => __('Carservice', 'carservice'),
		"params" => $params
	));
}
add_action("init", "cs_theme_post_carousel_vc_init");
?>
