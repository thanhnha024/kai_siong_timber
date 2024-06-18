<?php
//post
function cs_theme_timeline_item($atts, $content)
{
	extract(shortcode_atts(array(
		"label" => "",
		"label_position" => "0",
		"title" => "",
		"subtitle" => "",
		"animations" => "1",
		"top_margin" => "none",
		"el_class" => ""
	), $atts));
	
	$output = "";
	$output .= '<div class="timeline-item vertical-align-table' . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . ($el_class!="" ? ' ' . esc_attr($el_class) : '') . '">';
	if($label!="")
	{
		$output .= '<div class="timeline-left vertical-align-cell"><div class="label-container"' . ((int)$label_position!=0 ? ' style="top:' . esc_attr($label_position) . 'px;"' : '') . '>' . ((int)$animations ? '<div class="animated-element animation-slideRight25">' : '') . '<span class="label-triangle"></span><label>' . $label . '</label>' . ((int)$animations ? '</div>' : '') . '<span class="timeline-circle' . ((int)$animations ? ' animated-element animation-scale' : '') . '"></span></div></div>';
	}
	if($title!="" || $subtitle!="" || $content!="")
	{
		$output .= '<div class="timeline-content vertical-align-cell">';
		if($title!="" || $subtitle!="")
			$output .= '<h4 class="clearfix">' . ($title!="" ? '<span class="timeline-title">' . $title . '</span>' : '') . ($subtitle!="" ? '<span class="timeline-subtitle">' . $subtitle . '</span>' : ''). '</h4>';
		if($content!="")
			$output .= '<p>' . $content . '</p>';
		$output .= '</div>';
	}
	$output .= '</div>';
	return $output;
}

//visual composer
function cs_theme_timeline_item_vc_init()
{
	$params = array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Label", 'carservice'),
			"param_name" => "label",
			"value" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Label position", 'carservice'),
			"param_name" => "label_position",
			"value" => "0",
			"description" => "Default: 0 - label centered. To move it bottom please set positive value (for example: 10). To move it top, please set negative value (for example: -10)."
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Title", 'carservice'),
			"param_name" => "title",
			"value" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Subtitle", 'carservice'),
			"param_name" => "subtitle",
			"value" => ""
		),
		array(
			"type" => "textarea_html",
			"holder" => "div",
			"class" => "",
			"heading" => __("Text", 'carservice'),
			"param_name" => "content",
			"value" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Animations", 'carservice'),
			"param_name" => "animations",
			"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
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
	);
	
	vc_map( array(
		"name" => __("Timeline Item", 'carservice'),
		"base" => "timeline_item",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-timeline-item",
		"category" => __('Carservice', 'carservice'),
		"params" => $params
	));
}
add_action("init", "cs_theme_timeline_item_vc_init");
?>
