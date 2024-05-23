<?php
function cost_calculator_summary_box_shortcode($atts)
{
	extract(shortcode_atts(array(
		"id" => "cost",
		"name" => "total_cost",
		"formula" => "",
		"currency" => "$",
		"currency_after" => "",
		"currency_size" => "default",
		"currency_align" => "top",
		"currency_after_align" => "bottom",
		"thousandth_separator" => ",",
		"thousands_separator" => ",",
		"decimal_separator" => ".",
		"decimal_places" => 2,
		"math_function" => "",
		"not_number" => 1,
		"negative" => 0,
		"description" => "",
		"icon" => "",
		"label" => __("Total cost: ", 'cost-calculator'),
		"el_class" => "",
		"class" => ""
	), $atts));
	
	if(empty($thousands_separator) && !empty($thousandth_separator))
	{
		$thousands_separator = $thousandth_separator;
	}
	if($class!="")
		$el_class .= ($el_class!="" ? ' ' : '') . $class;
	$output = '<div class="cost-calculator-box cost-calculator-summary-box cost-calculator-clearfix' . ($el_class!="" ? ' ' . esc_attr($el_class) : '') . (!empty($icon) && $icon!="none" ? ' ' . esc_attr($icon) : '') . '">
		<span class="cost-calculator-summary-price' . ($currency_size=="small" ? ' cost-calculator-small-currency' . (!empty($currency_align) ? ' cost-calculator-align-' . esc_attr($currency_align) : '') . (!empty($currency_after_align) ? ' cost-calculator-after-align-' . esc_attr($currency_after_align) : '') : '') . '" id="' . esc_attr($id) . '">' . ($currency!="" ? '<span class="cost-calculator-currency">' . str_replace(array('&amp;space;', '&space;'), '&nbsp;', $currency) . "</span>" : '') . '0.00' . ($currency_after!="" ? '<span class="cost-calculator-currency">' . str_replace('&amp;space;', '&nbsp;', $currency_after) . '</span>' : '') . '</span>';
	if(!empty($description))
		$output .= '<p class="cost-calculator-price-description">' . $description . '</p>';
	$output .= '<input type="hidden" id="' . esc_attr($id) . '-total" name="' . esc_attr($name) . '" value="' . ($currency!="" ? str_replace(array('&amp;space;', '&space;'), '&nbsp;', esc_attr($currency)) : '') . '0.00' . ($currency_after!="" ? str_replace(array('&amp;space;', '&space;'), '&nbsp;', esc_attr($currency_after)) : '') . '">
	<input type="hidden" id="' . esc_attr($id) . '-total-value" name="' . esc_attr($name) . '-value" value="0">' . 
	($math_function=="ceil" || $math_function=="floor" ? '<input type="hidden" id="' . esc_attr($id) . '-total-plain" name="' . esc_attr($name) . '-plain" value="0">' : '') . '
	<input type="hidden" name="' . esc_attr($name) . '-summarylabel" value="' . esc_attr($label) . '">
	</div>';
	$script = 'jQuery(document).ready(function($){
		$("#' . esc_attr($id) . '").costCalculator({
			formula: "' . $formula . '",
			currency: "' . str_replace(array('&amp;space;', '&space;'), '&nbsp;', $currency) . '",
			currencyAfter: "' . str_replace(array('&amp;space;', '&space;'), '&nbsp;', $currency_after) . '",
			thousandsSeparator: "' . $thousands_separator . '",
			decimalSeparator: "' . $decimal_separator . '",
			decimalPlaces: "' . $decimal_places . '",
			mathFunction: "' . $math_function . '",
			notNumber: "' . $not_number . '",
			negative: "' . $negative . '",
			updateHidden: $("#' . esc_attr($id) . '-total")
		});
	});';
	wp_add_inline_script("jquery-costCalculator", $script);
	return $output;
}
add_shortcode("cost_calculator_summary_box", "cost_calculator_summary_box_shortcode");

if(is_plugin_active($js_composer_path) && function_exists('vc_map'))
{
	//visual composer
	vc_map( array(
		"name" => __("Cost calculator summary box", 'cost-calculator'),
		"base" => "cost_calculator_summary_box",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-cost-calculator-summary-box",
		"category" => __('Cost Calculator', 'cost-calculator'),
		"params" => array(
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Id", 'cost-calculator'),
				"param_name" => "id",
				"value" => "cost",
				"description" => __("Please provide unique id for each 'Cost calculator summary box' on your page.", 'cost-calculator')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Input name", 'cost-calculator'),
				"param_name" => "name",
				"value" => "total_cost"
			),
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Summary formula", 'cost-calculator'),
				"param_name" => "formula",
				"value" => "",
				"description" => __("Please put here the calculation formula for your form using the form elements ids. Available operators: + {-} * / ( ) {powerstart} ^ {powerend}. Example: square-feet*walls+square-feet*floors{-}20", 'cost-calculator')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Currency sign before value", 'cost-calculator'),
				"param_name" => "currency",
				"value" => "$"
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Currency sign afer value", 'cost-calculator'),
				"param_name" => "currency_after",
				"value" => ""
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Currency size", 'cost-calculator'),
				"param_name" => "currency_size",
				"value" => array(__("Default", 'cost-calculator') => 'default', __("Small", 'cost-calculator') => 'small')
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Currency before vertical align", 'cost-calculator'),
				"param_name" => "currency_align",
				"value" => array(__("Top", 'cost-calculator') => 'top', __("Bottom", 'cost-calculator') => 'bottom'),
				"dependency" => Array('element' => "currency_size", 'value' => array('small'))
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Currency after vertical align", 'cost-calculator'),
				"param_name" => "currency_after_align",
				"value" => array(__("Top", 'cost-calculator') => 'top', __("Bottom", 'cost-calculator') => 'bottom'),
				"dependency" => Array('element' => "currency_size", 'value' => array('small')),
				"std" => 'bottom'
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Thousands separator", 'cost-calculator'),
				"param_name" => "thousands_separator",
				"value" => ","
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Decimal separator", 'cost-calculator'),
				"param_name" => "decimal_separator",
				"value" => "."
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Decimal places", 'cost-calculator'),
				"param_name" => "decimal_places",
				"value" => 2
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Additional math function", 'cost-calculator'),
				"param_name" => "math_function",
				"value" => array(__("None", 'cost-calculator') => "", __("Ceil", 'cost-calculator') => "ceil", __("Floor", 'cost-calculator') => "floor")
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Display not number result as 0", 'cost-calculator'),
				"param_name" => "not_number",
				"value" => array(__("Yes", 'cost-calculator') => 1, __("No", 'cost-calculator') => 0)
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Display negative result as 0", 'cost-calculator'),
				"param_name" => "negative",
				"value" => array(__("Yes", 'cost-calculator') => 1, __("No", 'cost-calculator') => 0),
				"std" => 0
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Description", 'cost-calculator'),
				"param_name" => "description",
				"value" => ""
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Icon", 'cost-calculator'),
				"param_name" => "icon",
				"value" => array(__("none", 'cost-calculator') => '', __("calculation", 'cost-calculator') => 'cc-template-calculation', __("credit card", 'cost-calculator') => 'cc-template-card', __("wallet", 'cost-calculator') => 'cc-template-wallet')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Label", 'cost-calculator'),
				"param_name" => "label",
				"value" => __("Total cost: ", 'cost-calculator'),
				"description" => __("Label for summary value displayed in the email message", 'cost-calculator')
			),
			array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __("Extra class name", 'cost-calculator'),
				"param_name" => "el_class",
				"value" => ""
			)
		)
	));
}
?>
