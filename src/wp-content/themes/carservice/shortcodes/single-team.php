<?php
//author
function cs_theme_single_team($atts)
{
	extract(shortcode_atts(array(
		"top_margin" => "none"
	), $atts));
	
	global $post;
	setup_postdata($post);
	
	$output = "";
	if(!empty($top_margin) && $top_margin!="none")
		$output .= '<div class="' . esc_attr($top_margin) . '">';
	if(get_post_type()=="ql_team")
		$output .= (function_exists("wpb_js_remove_wpautop") ? wpb_js_remove_wpautop(apply_filters('the_content', get_the_content())) : apply_filters('the_content', get_the_content()));
	if(!empty($top_margin) && $top_margin!="none")
		$output .= '</div>';
		
	return $output;
}

//visual composer
function cs_theme_single_team_vc_init()
{
	$params = array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Top margin", 'carservice'),
			"param_name" => "top_margin",
			"value" => array(__("None", 'carservice') => "none", __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section")
		)
	);
	
	vc_map( array(
		"name" => __("Team Member", 'carservice'),
		"base" => "single_team",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-custom-post-type",
		"category" => __('Carservice', 'carservice'),
		"params" => $params
	));
}
add_action("init", "cs_theme_single_team_vc_init");
?>
