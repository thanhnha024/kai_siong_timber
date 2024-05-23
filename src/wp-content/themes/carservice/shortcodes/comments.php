<?php
//comments component
function cs_theme_comments($atts, $content)
{
	global $terms_checkbox;
	global $terms_message;
	global $top_margin;
	extract(shortcode_atts(array(
		"show_comments_form" => 1,
		"show_comments_list" => 1,
		"terms_checkbox" => 0,
		"terms_message" => "UGxlYXNlJTIwYWNjZXB0JTIwdGVybXMlMjBhbmQlMjBjb25kaXRpb25z",
		"top_margin" => "none"
	), $atts));
	
	$output = "";
	if((int)$show_comments_form)
	{
		ob_start();
		cs_get_theme_file("/comments-form.php");
		$output .= ob_get_contents();
		ob_end_clean();
	}
	if((int)$show_comments_list)
	{
		$output .= '<div class="comments-list-container clearfix' . ($top_margin!='none' ? ' ' . esc_attr($top_margin) : '') . '">';
		ob_start();
		comments_template();
		$output .= ob_get_contents();
		ob_end_clean();
		$output .= '</div>';
	}
	return $output;
}

//visual composer
function cs_theme_comments_vc_init()
{
	global $theme_options;
	vc_map( array(
		"name" => __("Comments", 'carservice'),
		"base" => "comments",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-comments",
		"category" => __('Carservice', 'carservice'),
		"params" => array(
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Show comments form", 'carservice'),
				"param_name" => "show_comments_form",
				"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Terms and conditions checkbox", 'carservice'),
				"param_name" => "terms_checkbox",
				"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0),
				"std" => 0,
				"dependency" => Array('element' => "show_comments_form", 'value' => "1")
			),
			array(
				"type" => "textarea_raw_html",
				"class" => "",
				"heading" => __("Terms and conditions message", 'carservice'),
				"param_name" => "terms_message",
				"value" => "UGxlYXNlJTIwYWNjZXB0JTIwdGVybXMlMjBhbmQlMjBjb25kaXRpb25z",
				"dependency" => Array('element' => "terms_checkbox", 'value' => "1")
			),
			array(
				"type" => "readonly",
				"class" => "",
				"heading" => __("reCaptcha", 'carservice'),
				"param_name" => "recaptcha",
				"value" => ((int)$theme_options["google_recaptcha_comments"] ? __("Yes", 'carservice') : __("No", 'carservice')),
				"description" => sprintf(__("You can change this setting under <a href='%s' title='Theme Options'>Theme Options</a>", 'carservice'), esc_url(admin_url("themes.php?page=ThemeOptions"))),
				"dependency" => Array('element' => "show_comments_form", 'value' => "1")
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Show comments list", 'carservice'),
				"param_name" => "show_comments_list",
				"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Top margin", 'carservice'),
				"param_name" => "top_margin",
				"value" => array(__("None", 'carservice') => "none", __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section")
			)
		)
	));
}
add_action("init", "cs_theme_comments_vc_init");