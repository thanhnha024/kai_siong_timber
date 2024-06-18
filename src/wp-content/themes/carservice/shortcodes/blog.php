<?php
//blog
function cs_theme_blog($atts)
{
	extract(shortcode_atts(array(
		"cs_pagination" => 0,
		"items_per_page" => 4,
		"offset" => 0,
		"featured_image_size" => "default",
		"ids" => "",
		"category" => "",
		"author" => "",
		"order_by" => "date",
		"order" => "DESC",
		"show_post_title" => 1,
		"show_post_excerpt" => 1,
		"read_more" => 1,
		"show_post_categories" => 1,
		"show_post_tags" => 0,
		"show_post_author" => 1,
		"show_post_date" => 1,
		"show_post_views" => 1,
		"show_post_comments" => 1,
		"is_search_results" => 0,
		"top_margin" => "none",
		"el_class" => ""
	), $atts));
	
	$featured_image_size = str_replace("cs_", "", $featured_image_size);
	
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

	global $paged;
	$paged = (get_query_var((is_front_page() && !is_home() ? 'page' : 'paged')) && (int)$cs_pagination ? get_query_var((is_front_page() && !is_home() ? 'page' : 'paged')) : 1);
	if(in_array("current", (array)$author))
	{
		$author = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
		$author = array($author->ID);
	}
	$args = array( 
		'post__in' => $ids,
		'post_type' => 'post',
		'post_status' => 'publish',
		'paged' => $paged,
		'posts_per_page' => $items_per_page,
		'offset' => (!(int)$cs_pagination ? (int)$offset : ""),
		'cat' => (get_query_var('cat')!="" ? get_query_var('cat') : ''),
		'category_name' => (get_query_var('cat')=="" ? implode(",", $category) : ''),
		'tag' => get_query_var('tag'),
		'author__in' => $author,
		'orderby' => ($order_by=="views" ? 'meta_value_num' : implode(" ", explode(",", $order_by))), 
		'order' => $order
	);
	if($order_by=="views")
		$args['meta_key'] = 'post_views_count';
	if((int)$is_search_results)
		$args['s'] = get_query_var('s');
	if(!is_single())
	{
		$args['monthnum'] = get_query_var('monthnum');
		$args['day'] = get_query_var('day');
		$args['year'] = get_query_var('year');
		$args['w'] = get_query_var('week');
	}
	if(get_query_var('cat')!="")
	{
		$tmpCategory = get_category(get_query_var('cat'));
		$category = array($tmpCategory->slug);
	}
	$blog_query = new WP_Query($args);
	$post_count = $blog_query->found_posts;
	
	$output = '';
	if($blog_query->have_posts())
	{
		$output .= '<div class="vc_row wpb_row vc_row-fluid">';
		$output .= '<ul class="blog clearfix' . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . ($el_class!="" ? ' ' . esc_attr($el_class) : '') . '">';
		$i = 0;
		cs_get_theme_file("/shortcodes/class/Post.php");
		while ($blog_query->have_posts()) : $blog_query->the_post();
			$post = new Cs_Post("blog", $featured_image_size, (int)$show_post_date, (int)$show_post_views, (int)$show_post_comments, (int)$show_post_categories, (int)$show_post_tags, (int)$show_post_excerpt, (int)$show_post_author, $i);
			$output .= $post->getLiCssClass();
			$output .= $post->getThumbnail("blog-post-thumb");
			$output .= $post->getPostDetails();
			if((int)$show_post_title)
			{
				$output .= '<h3 class="box-header"><a href="' . esc_url(get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">' . get_the_title() . '</a></h3>';
			}
			if((int)$show_post_excerpt)
			{
				$output .= apply_filters('the_excerpt', get_the_excerpt());
			}
			if((int)$read_more)
			{
				$output .= '<div class="vc_row wpb_row vc_row-fluid padding-top-54 padding-bottom-20"><a title="' . esc_html__('READ MORE', 'carservice') . '" href="' . esc_url(get_permalink()) . '" class="more"><span>' . __('READ MORE', 'carservice') . '</span></a></div>';
			}
			$output .= '</li>';
			$i++;
		endwhile;
		$output .= '</ul>';
		$output .= '</div>';
	}
	else if(is_search())
	{
		$output .= '<div class="vc_row wpb_row vc_row-fluid">' . sprintf(__('No results found for %s', 'carservice'), esc_attr(get_query_var('s'))) . '</div>';
	}
	if($cs_pagination)
	{
		cs_get_theme_file("/pagination.php");
		$output .= kriesi_pagination(false, '', 2, false, false, '', 'page-margin-top', $blog_query);
	}
	//Reset Postdata
	wp_reset_postdata();
	return $output;
}

//visual composer
function cs_theme_blog_vc_init()
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
		$image_sizes_array[$s . " (" . $width . "x" . $height . ")"] = "cs_" . $s;
	}
	vc_map( array(
		"name" => __("Blog", 'carservice'),
		"base" => "blog",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-blog",
		"category" => __('Carservice', 'carservice'),
		"params" => array(
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Pagination", 'carservice'),
				"param_name" => "cs_pagination",
				"value" => array(__("No", 'carservice') => 0, __("Yes", 'carservice') => 1)
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Items per page/Post count", 'carservice'),
				"param_name" => "items_per_page",
				"value" => 4,
				"description" => __("Items per page if pagination is set to 'yes' or post count otherwise. Set -1 to display all.", 'carservice')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Offset", 'carservice'),
				"param_name" => "offset",
				"value" => 0,
				"description" => __("Number of post to displace or pass over.", 'carservice'),
				"dependency" => Array('element' => "cs_pagination", 'value' => "0")
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
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Read more button", 'carservice'),
				"param_name" => "read_more",
				"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Show post categories", 'carservice'),
				"param_name" => "show_post_categories",
				"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
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
				"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
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
				"heading" => __("Is search results component", 'carservice'),
				"param_name" => "is_search_results",
				"value" => array(__("No", 'carservice') => 0, __("Yes", 'carservice') => 1)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Top margin", 'carservice'),
				"param_name" => "top_margin",
				"value" => array(__("None", 'carservice') => "none", __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section")
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'carservice' ),
				'param_name' => 'el_class',
				'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'carservice' )
			)
		)
	));
}
add_action("init", "cs_theme_blog_vc_init");
?>
