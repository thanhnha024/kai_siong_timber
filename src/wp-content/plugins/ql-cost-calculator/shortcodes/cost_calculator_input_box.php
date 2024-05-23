<?php
function cost_calculator_input_box_shortcode($atts)
{
	extract(shortcode_atts(array(
		"id" => "input-box",
		"name" => "input-box",
		"label" => "",
		"hide_label" => 0,
		"default_value" => "",
		"type" => "text",
		"checked" => "1",
		"checkbox_type" => "type-button",
		"checkbox_yes" => __("checked", 'cost-calculator'),
		"checkbox_no" => __("not checked", 'cost-calculator'),
		"group_label" => "",
		"placeholder" => "",
		"required" => 0,
		"required_message" => __("This field is required", 'cost-calculator'),
		"after_pseudo" => "",
		"top_margin" => "none",
		"el_class" => ""
	), $atts));
	
	$checked = (isset($_POST[$name]) && $_POST[$name]==$default_value ? 1 : $checked);
	$input_value = (isset($_POST[$name]) ? $_POST[$name] : $default_value);
	$output = "";
	if($type!="hidden" && $type!="submit")
		$output .= '<div class="cost-calculator-box cost-calculator-clearfix' . ($type=="radio" || ($type=="checkbox" && $checkbox_type=="type-button") ? ' cost-calculator-float' . ($type=="radio" ? ' cost-calculator-radio-box' : '') : '') . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . ($el_class!="" && $type!="checkbox" ? ' ' . esc_attr($el_class) : '') . '">';
	if($label!="" && ($type!="checkbox" || ($type=="checkbox" && $checkbox_type=="default")) && $type!="hidden" && $type!="submit")
		$output .= '<label for="' . esc_attr($id) . '"' . ((int)$hide_label || $type=="radio" ? ' class="cost-calculator-hidden"' : '') . '>' . $label . '</label>';
	if($label!="" && $type!="submit")
		$output .= '<input type="hidden" name="' . esc_attr($name) . '-label" value="' . ($type=="radio" && !empty($group_label) ? esc_attr($group_label) : esc_attr($label)) . '">';
	if($type=="date")
		$output .= '<div class="cost-calculator-datepicker-container"><span class="ui-icon cc-template-arrow-vertical-3"></span>';
	if($type=="submit")
		$output .= '<div class="cost-calculator-submit-container' . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . ($el_class!="" ? ' ' . esc_attr($el_class) : '') . '">';
	$output .= '<input id="' . esc_attr($id) . '" class="cost-calculator-cost-slider-input type-' . esc_attr($type) . ($type!="hidden" && $type!="submit" ? ' cost-calculator-big' : '') . ($type=="hidden" ? ' ' . esc_attr($name) . '-hidden' : '') . ($type=="submit" ? ' cost-calculator-more cost-calculator-gray' . ($el_class!="" ? ' ' . $el_class : '') : '') . '" name="' . esc_attr($name) . '" type="' . ($type=="date" ? "type-date" : esc_attr($type)) . '"' . (($type=="checkbox" || $type=="radio") && (int)$checked ? ' checked="checked"' : '') . ' value="' . (($type=="checkbox" || $type=="radio") && !(int)$checked ? 0 : esc_attr($input_value)) . '" data-value="' . esc_attr($default_value) . '"' . ($placeholder!="" && ($type=="text" || $type=="number" || $type=="date" || $type=="email") ? ' placeholder="' . esc_attr($placeholder) . '"' : '') . ((int)$required ? ' data-required="1"' . ($required_message!="" ? ' data-required-message="' . esc_attr($required_message) . '"' : '') : '') . '>
		' . ($type=="checkbox" ? '<label class="cost-calculator-checkbox-label' . ($checkbox_type=="default" ? ' cost-calculator-checkbox-default' : '') . ($el_class!="" ? ' ' . esc_attr($el_class) : '') . '" for="' . esc_attr($id) . '"><span class="checkbox-box"></span>' . ($checkbox_type=="type-button" ? $label : '') . '</label>' : '')
		. ($type=="radio" ? '<label class="cost-calculator-radio-label' . ($el_class!="" ? ' ' . esc_attr($el_class) : '') . '" for="' . esc_attr($id) . '"><span class="radio-box"></span>' . $label . '</label>' : '');
	if($type=="checkbox")
		$output .= '<input type="hidden" class="' . esc_attr($id) . '" name="' . esc_attr($name) . '-name" value="' . ((int)$checked ? esc_attr($checkbox_yes) : esc_attr($checkbox_no)) . '" data-yes="' . esc_attr($checkbox_yes) . '" data-no="' . esc_attr($checkbox_no) . '">';
	if($type=="radio" && $label!="")
		$output .= '<input type="hidden" class="' . esc_attr($id) . '" name="' . esc_attr($name) . '-name" value="' . esc_attr($label) . '">';
	if(!empty($after_pseudo))
	{
		$output .= '<span class="' . esc_attr($after_pseudo) . '"></span>';
	}
	if($type=="date" || $type=="submit")
		$output .= '</div>';
	if($type!="hidden" && $type!="submit")
		$output .= '</div>';
	return $output;
}
add_shortcode("cost_calculator_input_box", "cost_calculator_input_box_shortcode");

if(is_plugin_active($js_composer_path) && function_exists('vc_map'))
{
	//visual composer
	vc_map( array(
		"name" => __("Cost calculator input box", 'cost-calculator'),
		"base" => "cost_calculator_input_box",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-cost-calculator-input-box",
		"category" => __('Cost Calculator', 'cost-calculator'),
		"params" => array(
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Id", 'cost-calculator'),
				"param_name" => "id",
				"value" => "input-box",
				"description" => __("Please provide unique id for each 'Cost calculator input box' on your page.", 'cost-calculator')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Input name", 'cost-calculator'),
				"param_name" => "name",
				"value" => "input-box"
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Type", 'cost-calculator'),
				"param_name" => "type",
				"value" => array(__("text", 'cost-calculator') => "text", __("number", 'cost-calculator') => "number", __("date", 'cost-calculator') => "date", __("email", 'cost-calculator') => "email", __("checkbox", 'cost-calculator') => "checkbox", __("radio", 'cost-calculator') => "radio", __("hidden", 'cost-calculator') => "hidden", __("submit", 'cost-calculator') => "submit")
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
				"description" => __("Set to 'Yes' if you won't like to display label in the frontend but you still like to receive field value via email", 'cost-calculator'),
				"dependency" => Array('element' => "type", 'value' => array("text", "number", "date", "email"))
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Group label", 'cost-calculator'),
				"param_name" => "group_label",
				"value" => "",
				"dependency" => Array('element' => "type", 'value' => "radio")
			),
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
				"heading" => __("Is checked", 'cost-calculator'),
				"param_name" => "checked",
				"value" => array(__("yes", 'cost-calculator') => "1", __("no", 'cost-calculator') => "0"),
				"dependency" => Array('element' => "type", 'value' => array("checkbox", "radio")),
				"std" => 0
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Checkbox type", 'cost-calculator'),
				"param_name" => "checkbox_type",
				"value" => array(__("Button", 'cost-calculator') => "type-button", __("Default", 'cost-calculator') => "default"),
				"dependency" => Array('element' => "type", 'value' => "checkbox")
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Checked hidden text", 'cost-calculator'),
				"param_name" => "checkbox_yes",
				"value" => __("checked", 'cost-calculator'),
				"dependency" => Array('element' => "type", 'value' => "checkbox")
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Not checked hidden text", 'cost-calculator'),
				"param_name" => "checkbox_no",
				"value" => __("not checked", 'cost-calculator'),
				"dependency" => Array('element' => "type", 'value' => "checkbox")
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Placeholder", 'cost-calculator'),
				"param_name" => "placeholder",
				"value" => ""
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Required", 'cost-calculator'),
				"param_name" => "required",
				"value" => array(__("Yes", 'cost-calculator') => 1, __("No", 'cost-calculator') => 0),
				"std" => 0,
				"dependency" => Array('element' => "type", 'value' => array("text", "number", "date", "email", "checkbox"))
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
				"type" => "textfield",
				"class" => "",
				"heading" => __("After pseudo element css class", 'cost-calculator'),
				"param_name" => "after_pseudo",
				"value" => ""
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
