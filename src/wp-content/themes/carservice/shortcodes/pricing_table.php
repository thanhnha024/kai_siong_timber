<?php
//visual composer
function cs_theme_pricing_table_vc_init()
{
	global $wpdb;
	//get pricing tables list
	$query = "SELECT option_name FROM {$wpdb->options}
			WHERE 
			option_name LIKE 'css3_grid_shortcode_settings%'
			ORDER BY option_name";
	$pricing_tables_list = $wpdb->get_results($query);
	$css3GridAllShortcodeIds = array();
	$css3GridAllShortcodeIds["none"] = "none";
	foreach($pricing_tables_list as $pricing_table)
		$css3GridAllShortcodeIds[substr($pricing_table->option_name, 29)] = substr($pricing_table->option_name, 29);
	
	vc_map( array(
		"name" => __("Pricing table", 'carservice'),
		"base" => "css3_grid",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-pricing-table",
		"category" => __('Carservice', 'carservice'),
		"params" => array(
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Id", 'carservice'),
				"param_name" => "id",
				"value" => $css3GridAllShortcodeIds
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Top margin", 'carservice'),
				"param_name" => "top_margin",
				"value" => array(__("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section", __("None", 'carservice') => "none")
			)
		)
	));
}
add_action("init", "cs_theme_pricing_table_vc_init"); 
?>