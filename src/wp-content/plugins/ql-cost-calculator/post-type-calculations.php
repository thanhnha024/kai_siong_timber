<?php
//custom post type - services
function cost_calculator_calculations_init()
{
	$labels = array(
		'name' => _x('Calculations', 'post type general name', 'cost-calculator'),
		'singular_name' => _x('Calculation', 'post type singular name', 'cost-calculator'),
		'add_new' => _x('Add New', 'calculations', 'cost-calculator'),
		'add_new_item' => __('Add New Calculation', 'cost-calculator'),
		'edit_item' => __('Edit Calculation', 'cost-calculator'),
		'new_item' => __('New Calculation', 'cost-calculator'),
		'all_items' => __('Calculations', 'cost-calculator'),
		'view_item' => __('View Calculation', 'cost-calculator'),
		'search_items' => __('Search Calculations', 'cost-calculator'),
		'not_found' =>  __('No calculations found', 'cost-calculator'),
		'not_found_in_trash' => __('No calculations found in Trash', 'cost-calculator'), 
		'parent_item_colon' => '',
		'menu_name' => __("Calculations", 'cost-calculator')
	);
	
	$args = array(  
		"labels" => $labels, 
		"public" => false,  
		"show_ui" => true,  
		"capability_type" => "post",
		"menu_position" => 20,
		"menu_icon" => "dashicons-clipboard",
		"hierarchical" => false,
		"rewrite" => array("slug" => "calculations"),
		"supports" => array("title", "editor", "excerpt", "page-attributes") 
	);
	register_post_type("calculations", $args);
}  
add_action("init", "cost_calculator_calculations_init"); 

//custom sidebars items list
function cost_calculator_calculations_edit_columns($columns)
{
	$columns = array(  
		"cb" => "<input type=\"checkbox\">",  
		"title" => _x('Calculation name', 'post type singular name', 'cost-calculator'),
		"order" =>  _x('Order', 'post type singular name', 'cost-calculator'),
		"date" => __('Date', 'cost-calculator')
	);    

	return $columns;  
}  
add_filter("manage_edit-calculations_columns", "cost_calculator_calculations_edit_columns");

function cost_calculator_calculations_posts_custom_column($column)
{
	global $post;
	switch($column)
	{
		case "order":
			echo get_post($post->ID)->menu_order;
			break;
	}
}
add_action("manage_calculations_posts_custom_column", "cost_calculator_calculations_posts_custom_column");
?>