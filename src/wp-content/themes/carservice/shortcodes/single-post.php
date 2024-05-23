<?php
//post
function cs_theme_single_post($atts)
{
	global $themename;
	global $theme_options;
	extract(shortcode_atts(array(
		"featured_image_size" => "default",
		"show_post_title" => 1,
		"show_post_featured_image" => 1,
		"show_post_excerpt" => 1,
		"show_post_categories" => 1,
		"show_post_tags" => 1,
		"show_post_date" => 1,
		"show_post_author" => 1,
		"show_post_views" => 1,
		"show_post_comments" => 1,
		"show_leave_reply_button" => 1,
	), $atts));
	
	$featured_image_size = str_replace("cs_", "", $featured_image_size);
	
	global $post;
	setup_postdata($post);
	
	$output = "";
	$post_classes = get_post_class("post");
	$output .= '<div class="blog clearfix"><div class="single ';
	foreach($post_classes as $key=>$post_class)
		$output .= ($key>0 ? ' ' : '') . esc_attr($post_class);
	$output .= '">';
	if(has_post_thumbnail() && (int)$show_post_featured_image)
	{
		$thumb_id = get_post_thumbnail_id(get_the_ID());
		$attachment_image = wp_get_attachment_image_src($thumb_id, "large");
		$large_image_url = $attachment_image[0];
		$output .= '<a class="post-image prettyPhoto" href="' . esc_url($large_image_url) . '" title="' . esc_attr(get_the_title()) . '">' . get_the_post_thumbnail(get_the_ID(), ($featured_image_size!="default" ? $featured_image_size : "blog-post-thumb"), array("alt" => get_the_title(), "title" => "")) . '</a>';
	}
	if((int)$show_post_date || (int)$show_post_author || (int)$show_post_categories || (int)$show_post_tags || (int)$show_post_views || (int)$show_post_comments)
	{
		$output .= '<div class="post-content-details-container clearfix">';
		if((int)$show_post_date || (int)$show_post_author || (int)$show_post_categories || (int)$show_post_tags)
		{
			$output .= '<ul class="post-content-details">';
			if((int)$show_post_date)
			{
				$output .= '<li>' . date_i18n(get_option('date_format'), get_post_time()) . '</li>';
			}
			if((int)$show_post_author)
			{
				$output .= '<li>' . __("By ", 'carservice') . '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" title="' . esc_attr(get_the_author()) . '">' . get_the_author() . '</a></li>';
			}
			if((int)$show_post_categories)
			{
				$categories = get_the_category();
				$output .= '<li>' . __("In ", 'carservice');
				foreach($categories as $key=>$category)
				{
					$output .= '<a class="category-' . esc_attr($category->term_id) . '" href="' . esc_url(get_category_link($category->term_id)) . '" ';
					if(empty($category->description))
						$output .= 'title="' . sprintf(__('View all posts filed under %s', 'carservice'), esc_attr($category->name)) . '"';
					else
						$output .= 'title="' . esc_attr(strip_tags(apply_filters('category_description', $category->description, $category))) . '"';
					$output .= '>' . $category->name . '</a>' . ($category != end($categories) ? ', ' : '');
				}
				$output .= '</li>';
			}
			if((int)$show_post_tags)
			{
				$tags = get_the_tags();
				if($tags)
				{
					$output .= '<li>' . __("Tags ", 'carservice');
					foreach($tags as $key=>$tag)
					{
						$output .= '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" ';
						if(empty($tag->description))
							$output .= 'title="' . sprintf(__('View all posts filed under %s', 'carservice'), esc_attr($tag->name)) . '"';
						else
							$output .= 'title="' . esc_attr(strip_tags(apply_filters('tag_description', $tag->description, $tag))) . '"';
						$output .= '>' . $tag->name . '</a>' . ($tag != end($tags) ? ', ' : '');
					}
					$output .= '</li>';
				}
			}
			$output .= '</ul>';
		}
		if((int)$show_post_views || (int)$show_post_comments)
		{
			$output .= '<ul class="post-content-details right">';
			if((int)$show_post_views)
			{
				$output .= '<li class="template-eye">' . cs_getPostViews($post->ID) . '</li>';
			}
			if((int)$show_post_comments)
			{
				$comments_count = get_comments_number();
				$output .= '<li class="template-bubble"><a class="scroll-to-comments" href="' . esc_url(get_comments_link()) . '" title="' . esc_attr($comments_count) . ' ' . ($comments_count==1 ? esc_html__('comment', 'carservice') : esc_html__('comments', 'carservice')) . '">' . $comments_count . '</a></li>';
			}
			$output .= '</ul>';
		}
		$output .= '</div>';
	}

	if($show_post_title) 
		$output .= '<h3 class="box-header"><a href="' . esc_url(get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">' . get_the_title() . '</a></h3>';
	if((int)$show_post_excerpt)
		$output .= '<p>' . apply_filters("the_excerpt", get_the_excerpt()) . '</p>';
	if(get_post_type()=="post")
		$output .= (function_exists("wpb_js_remove_wpautop") ? wpb_js_remove_wpautop(apply_filters('the_content', get_the_content())) : apply_filters('the_content', get_the_content()));
	$output .= wp_link_pages(array(
		"before" => '<ul class="pagination post-pagination page-margin-top"><li>',
		"after" => '</li></ul>',
		"link_before" => '<span>',
		"link_after" => '</span>',
		"separator" => '</li><li>',
		"echo" => 0
	));
	if((int)$show_leave_reply_button && comments_open())
	{
		$output .= '<div class="vc_row wpb_row vc_inner vc_row-fluid padding-top-54 padding-bottom-20"><a title="' . esc_attr("LEAVE A REPLY", 'carservice') . '" href="#" class="more scroll-to-comment-form"><span>' . __("LEAVE A REPLY", 'carservice') . '</span></a></div>';
	}
	$output .= '</div></div>';
	/*if((int)$comments)
	{
		ob_start();
		comments_template();
		cs_get_theme_file("/comments-form.php");	
		$output .= ob_get_contents();
		ob_end_clean();
	}*/
	return $output;
}

//visual composer
function cs_theme_single_post_vc_init()
{
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
	$params = array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Featured image size", 'carservice'),
			"param_name" => "featured_image_size",
			"value" => $image_sizes_array
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
			"heading" => __("Show post featured image", 'carservice'),
			"param_name" => "show_post_featured_image",
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
			"heading" => __("Show post date", 'carservice'),
			"param_name" => "show_post_date",
			"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show post views number", 'carservice'),
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
			"heading" => __("Show post author", 'carservice'),
			"param_name" => "show_post_author",
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
			"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show leave reply button", 'carservice'),
			"param_name" => "show_leave_reply_button",
			"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
		)
	);
	vc_map( array(
		"name" => __("Post", 'carservice'),
		"base" => "single_post",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-post",
		"category" => __('Carservice', 'carservice'),
		"params" => $params
	));
}
add_action("init", "cs_theme_single_post_vc_init");
?>
