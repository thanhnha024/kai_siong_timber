<?php
function cost_calculator_dropdown_box_shortcode($atts)
{
	extract(shortcode_atts(array(
		"id" => "dropdown-box",
		"name" => "dropdown-box",
		"label" => "",
		"hide_label" => 0,
		"options_count" => 1,
		"default_value" => "",
		"show_choose_label" => 1,
		"choose_label" => __("Choose...", 'cost-calculator'),
		"required" => 0,
		"required_message" => __("This field is required", 'cost-calculator'),
		"top_margin" => "none"
	), $atts));

	$default_value = (isset($_POST[$name]) ? $_POST[$name] : $default_value);
	$output = '<div class="cost-calculator-box cost-calculator-clearfix' . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . '"><div class="border-container">';
	if($label!="")
	{
		$output .= '<label class="' . ((int)$hide_label ? 'cost-calculator-hidden' : 'cost-calculator-select-label') . '">' . $label . '</label>
		<input type="hidden" name="' . esc_attr($name) . '-label" value="' . esc_attr($label) . '">';
	}
	$output .= '<select name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" class="cost-calculator-cost-dropdown"' . ((int)$required ? ' data-required="1"' . ($required_message!="" ? ' data-required-message="' . esc_attr($required_message) . '"' : '') : '') . '>';
	if((int)$show_choose_label)
		$output .= '<option value=""' . (empty($default_value) ? ' selected="selected"' : '') . '>' . $choose_label . '</option>';
	for($i=0; $i<$options_count; $i++)
		$output .= '<option value="' . (isset($atts["option_value" . $i]) ? esc_attr($atts["option_value" . $i]) : "") . '"' . (!empty($default_value) && $atts["option_value" . $i]==$default_value ? ' selected="selected"' : '') . '>' . (!empty($atts["option_name" . $i]) ? esc_html($atts["option_name" . $i]) : '') . '</option>';
	$output .= '</select>';
	$default_name = ((int)$show_choose_label ? $choose_label : $atts["option_name0"]);
	if(!empty($default_value))
	{
		for($i=0; $i<$options_count; $i++)
		{
			if($atts["option_value" . $i]==$default_value)
				$default_name = $atts["option_name" . $i];
		}
	}
	$output .= '<input type="hidden" class="' . esc_attr($id) . '" id="' . esc_attr($id) . '-name" name="' . esc_attr($name) . '-name" value="' . esc_attr($default_name) . '">';
	$output .= '</div></div>';
	return $output;
}
add_shortcode("cost_calculator_dropdown_box", "cost_calculator_dropdown_box_shortcode");

if(is_plugin_active($js_composer_path) && function_exists('vc_map'))
{
	//visual composer
	$count = array();
	for($i=1; $i<=30; $i++)
		$count[$i] = $i;
		
	$params = array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Id", 'cost-calculator'),
			"param_name" => "id",
			"value" => "dropdown-box",
			"description" => __("Please provide unique id for each 'Cost calculator dropdown box' on your page.", 'cost-calculator')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Input name", 'cost-calculator'),
			"param_name" => "name",
			"value" => "dropdown-box"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Label", 'cost-calculator'),
			"param_name" => "label",
			"value" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Hide label", 'cost-calculator'),
			"param_name" => "hide_label",
			"value" => array(__("No", 'cost-calculator') => 0,  __("Yes", 'cost-calculator') => 1),
			"description" => __("Set to 'Yes' if you won't like to display label in the frontend but you still like to receive field value via email", 'cost-calculator')
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Number of options", 'cost-calculator'),
			"param_name" => "options_count",
			"value" => $count
		)
	);
	for($i=0; $i<30; $i++)
	{
		$params[] = array(
			"type" => "textfield",
			"edit_field_class" => "vc_col-sm-12 vc_column" . ($i>0 ? " wpb_el_type_hidden" : ""),
			"heading" => __("Option name", 'cost-calculator') . " " . ($i+1),
			"param_name" => "option_name" . $i,
			"value" => ""
		);
		$params[] = array(
			"type" => "textfield",
			"edit_field_class" => "vc_col-sm-12 vc_column" . ($i>0 ? " wpb_el_type_hidden" : ""),
			"heading" => __("Option value", 'cost-calculator') . " " . ($i+1),
			"param_name" => "option_value" . $i,
			"value" => ""
		);
	}
	$params = array_merge($params, array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Default value", 'cost-calculator'),
			"param_name" => "default_value",
			"value" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show 'choose' label", 'cost-calculator'),
			"param_name" => "show_choose_label",
			"value" => array(__("Yes", 'cost-calculator') => 1, __("No", 'cost-calculator') => 0)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Choose label", 'cost-calculator'),
			"param_name" => "choose_label",
			"value" => __("Choose...", 'cost-calculator'),
			"dependency" => Array('element' => "show_choose_label", 'value' => "1")
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Required", 'cost-calculator'),
			"param_name" => "required",
			"value" => array(__("Yes", 'cost-calculator') => 1, __("No", 'cost-calculator') => 0),
			"std" => 0,
			"dependency" => Array('element' => "show_choose_label", 'value' => "1")
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Required field message", 'cost-calculator'),
			"param_name" => "required_message",
			"value" => __("This field is required", 'cost-calculator'),
			"dependency" => Array('element' => "required", 'value' => "1")
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Top margin", 'cost-calculator'),
			"param_name" => "top_margin",
			"value" => array(__("None", 'cost-calculator') => "none",  __("Small", 'cost-calculator') => "page-margin-top", __("Large", 'cost-calculator') => "page-margin-top-section")
		)
	));
	vc_map( array(
		"name" => __("Cost calculator dropdown box", 'cost-calculator'),
		"base" => "cost_calculator_dropdown_box",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-cost-calculator-dropdown-box",
		"category" => __('Cost Calculator', 'cost-calculator'),
		"params" => $params
	));
}
?>
