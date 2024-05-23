<?php
function cost_calculator_slider_box_shortcode($atts)
{
	extract(shortcode_atts(array(
		"id" => "slider-box",
		"name" => "slider-box",
		"label" => "",
		"hide_label" => 0,
		"default_value" => "6",
		"unit_value" => "1",
		"step" => "1",
		"min" => "0",
		"max" => "10",
		"minmax_label" => 0,
		"currency" => "",
		"currency_after" => "",
		"thousandth_separator" => "",
		"thousands_separator" => "",
		"input_field" => "1",
		"top_margin" => "none",
		"el_class" => ""
	), $atts));
	
	
	$default_value = (isset($_POST[$name]) ? $_POST[$name] : $default_value);
	if(empty($thousands_separator) && !empty($thousandth_separator))
	{
		$thousands_separator = $thousandth_separator;
	}
	$output = '<div class="cost-calculator-box cost-calculator-clearfix' . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . ($el_class!="" ? ' ' . esc_attr($el_class) : '') . '">';
	if($label!="")
	{
		$output .= '<label' . ((int)$hide_label ? ' class="cost-calculator-hidden"' : '') . '>' . $label . '</label>
		<input type="hidden" name="' . esc_attr($name) . '-label" value="' . esc_attr($label) . '">';
	}
	if((int)$minmax_label)
	{
		$output .= '<ul class="cost-calculator-slider-min-max">
			<li>' . ($currency!="" ? str_replace(array('&amp;space;', '&space;'), '&nbsp;', esc_html($currency)) : '') . (!empty($thousands_separator) ? number_format(esc_html($min), 0, ".", $thousands_separator) : esc_html($min)) . ($currency_after!="" ? str_replace('&amp;space;', '&nbsp;', esc_html($currency_after)) : '') . '</li>
			<li>&ndash;</li>
			<li>' . ($currency!="" ? str_replace(array('&amp;space;', '&space;'), '&nbsp;', esc_html($currency)) : '') . (!empty($thousands_separator) ? number_format(esc_html($max), 0, ".", $thousands_separator) : esc_html($max)) . ($currency_after!="" ? str_replace('&amp;space;', '&nbsp;', esc_html($currency_after)) : '') . '</li>
		</ul>';
	}
	$output .= '<div class="cost-slider-container">
			<input id="' . esc_attr($id) . '" class="cost-calculator-cost-slider-input' . (!(int)$input_field ? ' cost-calculator-cost-slider-input-hidden' : '') . '" name="' . esc_attr($name) . '" type="number" value="' . esc_attr($default_value) . '" step="' . esc_attr($step) . '">';
	if(!empty($unit_value))
		$output .= '<input id="' . esc_attr($id) . '-value" type="hidden" value="' . esc_attr($unit_value*$default_value) . '" data-default="' . esc_attr($unit_value*$default_value) . '">';
	$output .= '<div class="cost-calculator-cost-slider" data-value="' . esc_attr($default_value) . '" data-step="' . esc_attr($step) . '" data-min="' . esc_attr($min) . '" data-max="' . esc_attr($max) . '" data-input="' . esc_attr($id) . '"' . (!empty($currency) ? ' data-currencyBefore="' . esc_attr(str_replace(array('&amp;space;', '&space;'), '&nbsp;', $currency)) . '"' : '') . (!empty($currency_after) ? ' data-currencyAfter="' . esc_attr(str_replace(array('&amp;space;', '&space;'), '&nbsp;', $currency_after)) . '"' : '') . (!empty($thousands_separator) ? ' data-thousandsSeparator="' . esc_attr($thousands_separator) . '"' : '') . (!empty($unit_value) ? ' data-value-input="' . esc_attr($id) . '-value" data-price="' . esc_attr($unit_value) : '') . '"></div>
		</div>
	</div>';
	return $output;
}
add_shortcode("cost_calculator_slider_box", "cost_calculator_slider_box_shortcode");

if(is_plugin_active($js_composer_path) && function_exists('vc_map'))
{
	//visual composer
	vc_map( array(
		"name" => __("Cost calculator slider box", 'cost-calculator'),
		"base" => "cost_calculator_slider_box",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-cost-calculator-slider-box",
		"category" => __('Cost Calculator', 'cost-calculator'),
		"params" => array(
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Id", 'cost-calculator'),
				"param_name" => "id",
				"value" => "slider-box",
				"description" => __("Please provide unique id for each 'Cost calculator slider box' on your page.", 'cost-calculator')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Input name", 'cost-calculator'),
				"param_name" => "name",
				"value" => "slider-box"
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
				"heading" => __("Default value", 'cost-calculator'),
				"param_name" => "default_value",
				"value" => "6"
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Unit value", 'cost-calculator'),
				"param_name" => "unit_value",
				"value" => "1"
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Step", 'cost-calculator'),
				"param_name" => "step",
				"value" => "1"
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Min value", 'cost-calculator'),
				"param_name" => "min",
				"value" => "0"
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Max value", 'cost-calculator'),
				"param_name" => "max",
				"value" => "10"
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Show min-max label", 'cost-calculator'),
				"param_name" => "minmax_label",
				"value" => array(__("Yes", 'cost-calculator') => "1",  __("No", 'cost-calculator') => "0"),
				"std" => 0
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Currency sign before value", 'cost-calculator'),
				"param_name" => "currency",
				"value" => ""
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Currency sign afer value", 'cost-calculator'),
				"param_name" => "currency_after",
				"value" => ""
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Thousands separator", 'cost-calculator'),
				"param_name" => "thousands_separator",
				"value" => ""
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Show input field", 'cost-calculator'),
				"param_name" => "input_field",
				"value" => array(__("Yes", 'cost-calculator') => "1",  __("No", 'cost-calculator') => "0")
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
