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
				"heading" => __("Column type", 'cost-calculator'),
				"param_name" => "type",
				"value" => array(__("Default", 'cost-calculator') => "", __("Cost calculator form", 'cost-calculator') => "cost-calculator-container")
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
				"description" => __("Select top margin value for your column", "cost-calculator")
			)
		);
		vc_add_params('vc_column', $attributes);
	}
}
else
{
	function cost_calculator_translateColumnWidthToSpan($width) 
	{
		preg_match( '/(\d+)\/(\d+)/', $width, $matches );
		if(!empty($matches))
		{
			$part_x = (int)$matches[1];
			$part_y = (int)$matches[2];
			if($part_x > 0 && $part_y > 0)
			{
				$value = ceil($part_x / $part_y * 12);
				if($value > 0 && $value <= 12)
				{
					$width = 'vc_col-sm-' . $value;
				}
			}
		}

		return $width;
	}
	function cost_calculator_column_shortcode($atts, $content)
	{
		extract(shortcode_atts(array(
			"width" => "1/1",
			"top_margin" => "none",
			"el_class" => ""
		), $atts));
		$width = cost_calculator_translateColumnWidthToSpan($width);
		$output = '<div class="wpb_column vc_column_container cost-calculator-column ' . esc_attr($width) . (!empty($el_class) ? ' ' . esc_attr($el_class) : '') . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . '"><div class="vc_column-inner"><div class="wpb_wrapper">' . do_shortcode(shortcode_unautop($content)) . '</div></div></div>';
		return $output;
	}
	add_shortcode("vc_column", "cost_calculator_column_shortcode");
}
?>