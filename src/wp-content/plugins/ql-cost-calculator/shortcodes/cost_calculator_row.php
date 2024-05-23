<?php
if(is_plugin_active($js_composer_path) && function_exists('vc_map'))
{
	if((empty($cost_calculator_global_form_options["wpbakery_noconflict"]) || (int)$cost_calculator_global_form_options["wpbakery_noconflict"]==1) && function_exists("vc_shortcodes_theme_templates_dir") && vc_shortcodes_theme_templates_dir("vc_row.php")=="" && vc_shortcodes_theme_templates_dir("vc_row_inner.php")=="" && vc_shortcodes_theme_templates_dir("vc_column.php")=="" && vc_shortcodes_theme_templates_dir("vc_column_inner.php")=="")
	{
		//visual composer
		$attributes = array(
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Type", 'cost-calculator'),
				"param_name" => "type",
				"value" => array(__("Default", 'cost-calculator') => "",  __("Full width", 'cost-calculator') => "full-width",  __("Paralax background", 'cost-calculator') => "full-width cm-parallax", __("Paralax background with overlay", 'cost-calculator') => "full-width cm-parallax cm-overlay", __("Cost calculator form", 'cost-calculator') => "cost-calculator-container"),
				"description" => __("Select row type", "cost-calculator")
			),
			array(
				"type" => "textfield",
				"heading" => __("Form action url", 'cost-calculator'),
				"param_name" => "action",
				"dependency" => Array('element' => "type", 'value' => "cost-calculator-container")
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Top margin", 'cost-calculator'),
				"param_name" => "top_margin",
				"value" => array(__("None", 'cost-calculator') => "none",  __("Small", 'cost-calculator') => "page-margin-top", __("Large", 'cost-calculator') => "page-margin-top-section"),
				"description" => __("Select top margin value for your row", "cost-calculator")
			)
		);
		vc_add_params('vc_row', $attributes);
	}
}
else
{
	function cost_calculator_row_shortcode($atts, $content)
	{
		extract(shortcode_atts(array(
			"top_margin" => "none",
			"el_class" => ""
		), $atts));
		
		$output = '<div class="vc_row wpb_row vc_row-fluid cost-calculator-row' . (!empty($el_class) ? ' ' . esc_attr($el_class) : '') . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . '">' . do_shortcode(shortcode_unautop($content)) . '</div>';
		return $output;
	}
	add_shortcode("vc_row", "cost_calculator_row_shortcode");
}
?>