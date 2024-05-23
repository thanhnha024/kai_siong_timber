<?php
function cost_calculator_switch_box_shortcode($atts)
{
	extract(shortcode_atts(array(
		"id" => "switch-box",
		"name" => "switch-box",
		"label" => "",
		"hide_label" => 0,
		"yes_text" => __("Yes", 'cost-calculator'),
		"no_text" => __("No", 'cost-calculator'),
		"default_value" => "1",
		"checked" => "1",
		"top_margin" => "none",
		"el_class" => ""
	), $atts));
	
	$checked = (isset($_POST[$name]) && $_POST[$name]==$default_value ? 1 : $checked);
	$input_value = (isset($_POST[$name]) ? $_POST[$name] : $default_value);
	$output = '<div class="cost-calculator-box cost-calculator-clearfix' . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . ($el_class!="" ? ' ' . esc_attr($el_class) : '') . '">';
	if($label!="")
		$output .= '<label' . ((int)$hide_label ? ' class="cost-calculator-hidden"' : '') . '>' . $label . '</label>';
	$output .= '<label for="' . esc_attr($id) . '" class="cost-calculator-switch">
					<input type="hidden" name="' . esc_attr($name) . '-label" value="' . esc_attr($label) . '">
					<input id="' . esc_attr($id) . '" type="checkbox" class="cost-calculator-cost-slider-input type-checkbox" name="' . esc_attr($name) . '"' . ((int)$checked ? ' checked="checked"' : '') . ' value="' . (!(int)$checked ? 0 : esc_attr($input_value)) . '" data-value="' . esc_attr($default_value) . '">
					<span class="cost-calculator-switch-slider" data-yes="' . esc_attr($yes_text) . '" data-no="' . esc_attr($no_text) . '"></span>
					<input type="hidden" class="' . esc_attr($id) . '" name="' . esc_attr($name) . '-name" value="' . ((int)$checked ? esc_attr($yes_text) : esc_attr($no_text)) . '" data-yes="' . esc_attr($yes_text) . '" data-no="' . esc_attr($no_text) . '">
				</label>
			</div>';
	return $output;
}
add_shortcode("cost_calculator_switch_box", "cost_calculator_switch_box_shortcode");

if(is_plugin_active($js_composer_path) && function_exists('vc_map'))
{
	//visual composer
	vc_map( array(
		"name" => __("Cost calculator switch box", 'cost-calculator'),
		"base" => "cost_calculator_switch_box",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-cost-calculator-switch-box",
		"category" => __('Cost Calculator', 'cost-calculator'),
		"params" => array(
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Id", 'cost-calculator'),
				"param_name" => "id",
				"value" => "switch-box",
				"description" => __("Please provide unique id for each 'Cost calculator input box' on your page.", 'cost-calculator')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Input name", 'cost-calculator'),
				"param_name" => "name",
				"value" => "switch-box"
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
				"type" => "textfield",
				"class" => "",
				"heading" => __("'Yes' text", 'cost-calculator'),
				"param_name" => "yes_text",
				"value" => __("Yes", 'cost-calculator')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("'No' text", 'cost-calculator'),
				"param_name" => "no_text",
				"value" => __("No", 'cost-calculator')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Default value", 'cost-calculator'),
				"param_name" => "default_value",
				"value" => "1"
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Is checked", 'cost-calculator'),
				"param_name" => "checked",
				"value" => array(__("yes", 'cost-calculator') => "1", __("no", 'cost-calculator') => "0")
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Top margin", 'cost-calculator'),
				"param_name" => "top_margin",
				"value" => array(__("None", 'cost-calculator') => "none",  __("Small", 'cost-calculator') => "page-margin-top", __("Large", 'cost-calculator') => "page-margin-top-section")
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'cost-calculator' ),
				'param_name' => 'el_class',
				'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'cost-calculator' )
			)
		)
	));
}
?>
