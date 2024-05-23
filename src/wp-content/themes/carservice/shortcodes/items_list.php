<?php
//items list
function cs_theme_items_list($atts, $content)
{
	extract(shortcode_atts(array(
		"class" => "",
		"top_margin" => "none"
	), $atts));
	
	$output = '<ul class="list' . ($class!='' ? ' ' . esc_attr($class) : '') . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . '">' . wpb_js_remove_wpautop($content) . '</ul>';
	return $output;
}

//items list
function cs_theme_item($atts, $content)
{
	extract(shortcode_atts(array(
		"icon" => "bullet",
		"class" => "",
		"url" => "",
		"url_target" => "",
		"text_color" => ""
	), $atts));
	
	$output = '<li class="' . ($icon!="" || $class!="" ? ($icon!="" ? 'template-' . esc_attr($icon) . ' ': '') . ($class!="" ? esc_attr($class) . ' ' : '') : '') . '"' . ($text_color!='' ? ' style="' . ($text_color!='' ? 'color:' . esc_attr($text_color) . ';' : '') . '"' : '') . '>' . '<' . ($url!="" ? 'a href="' . esc_url($url) . '"' . ($url_target=='new_window' ? ' target="_blank"' : '') : 'span') . ($text_color!='' ? ' style="color: ' . esc_attr($text_color) . ';"' : '') . '>' . do_shortcode($content) . '</' . ($url!="" ? 'a' : 'span') . '></li>';
	
	return $output;
}

//visual composer
vc_map( array(
	"name" => __("Items list", 'carservice'),
	"base" => "items_list",
	"class" => "",
	"controls" => "full",
	"show_settings_on_create" => true,
	"icon" => "icon-wpb-layer-items-list",
	"category" => __('Carservice', 'carservice'),
	"params" => array(
		/*array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Type", 'carservice'),
			"param_name" => "type",
			"value" => array(__("Items list", 'carservice') => 'items', __("Info list", 'carservice') => 'info', __("Scrolling list", 'carservice') => 'scrolling', __("Simple list", 'carservice') => 'simple',)
		),*/
		array(
			"type" => "textarea_html",
			"holder" => "div",
			"class" => "",
			"heading" => __("Content", 'carservice'),
			"param_name" => "content",
			"value" => ""
		),
		array(
			"type" => "listitem",
			"class" => "",
			"param_name" => "additembutton",
			"value" => "Add list item"
		),
		array(
			"type" => "listitemwindow",
			"class" => "",
			"param_name" => "additemwindow",
			"value" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Top margin", 'carservice'),
			"param_name" => "top_margin",
			"value" => array(__("None", 'carservice') => "none", __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section")
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Extra class name", 'carservice'),
			"param_name" => "class",
			"value" => ""
		)
	)
));
?>