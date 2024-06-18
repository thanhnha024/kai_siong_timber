<?php
global $carservice_posts_array;
$carservice_posts_array = array();
$count_posts = wp_count_posts();
if($count_posts->publish<100)
{
	$carservice_posts_list = get_posts(array(
		'posts_per_page' => -1,
		'nopaging' => true,
		'orderby' => 'title',
		'order' => 'ASC',
		'post_type' => 'post'
	));
	$carservice_posts_array[__("All", 'carservice')] = "-";
	foreach($carservice_posts_list as $post)
		$carservice_posts_array[$post->post_title . " (id:" . $post->ID . ")"] = $post->ID;
}

global $carservice_pages_array;
$carservice_pages_array = array();
$count_pages = wp_count_posts('page');
if($count_pages->publish<100)
{
	$pages_list = get_posts(array(
		'posts_per_page' => -1,
		'orderby' => 'title',
		'order' => 'ASC',
		'post_type' => 'page'
	));
	$carservice_pages_array = array();
	$carservice_pages_array[__("none", 'carservice')] = "-";
	foreach($pages_list as $single_page)
		$carservice_pages_array[$single_page->post_title . " (id:" . $single_page->ID . ")"] = $single_page->ID;
}

//blog 1 column
cs_get_theme_file("/shortcodes/blog.php");
//blog 2 columns
cs_get_theme_file("/shortcodes/blog_2_columns.php");
//blog 3 columns
cs_get_theme_file("/shortcodes/blog_3_columns.php");
//blog small
cs_get_theme_file("/shortcodes/blog_small.php");
//post carousel
cs_get_theme_file("/shortcodes/post_carousel.php");
//post
cs_get_theme_file("/shortcodes/single-post.php");
//comments
cs_get_theme_file("/shortcodes/comments.php");
//items_list
cs_get_theme_file("/shortcodes/items_list.php");
//map
cs_get_theme_file("/shortcodes/map.php");
if(is_plugin_active('ql_services/ql_services.php'))
{
	//service single
	cs_get_theme_file("/shortcodes/single-service.php");
}
if(is_plugin_active('ql_team/ql_team.php'))
{
	//team single
	cs_get_theme_file("/shortcodes/single-team.php");
	cs_get_theme_file("/shortcodes/team_member_box.php");
}
if(is_plugin_active('ql_galleries/ql_galleries.php'))
{
	//gallery single
	cs_get_theme_file("/shortcodes/single-gallery.php");
}
//about box
cs_get_theme_file("/shortcodes/call_to_action_box.php");
//featured item
cs_get_theme_file("/shortcodes/featured_item.php");
//timeline item
cs_get_theme_file("/shortcodes/timeline_item.php");
//announcement box
cs_get_theme_file("/shortcodes/announcement_box.php");
//pricing table
if(is_plugin_active('css3_web_pricing_tables_grids/css3_web_pricing_tables_grids.php'))
	cs_get_theme_file("/shortcodes/pricing_table.php");
//testimonials
cs_get_theme_file("/shortcodes/testimonials.php");
//our clients carousel
cs_get_theme_file("/shortcodes/our_clients_carousel.php");

//progress bar
$attributes = array(
	array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Top margin", 'carservice'),
		"param_name" => "top_margin",
		"value" => array(__("None", 'carservice') => "none",  __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section"),
		"description" => __("Select top margin value for your row", "carservice")
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Color', 'carservice' ),
		'param_name' => 'bgcolor',
		'value' => array(
				__( 'Default', 'carservice' ) => '',
				__( 'Classic Grey', 'carservice' ) => 'bar_grey',
				__( 'Classic Blue', 'carservice' ) => 'bar_blue',
				__( 'Classic Turquoise', 'carservice' ) => 'bar_turquoise',
				__( 'Classic Green', 'carservice' ) => 'bar_green',
				__( 'Classic Orange', 'carservice' ) => 'bar_orange',
				__( 'Classic Red', 'carservice' ) => 'bar_red',
				__( 'Classic Black', 'carservice' ) => 'bar_black',
			) + (function_exists("vc_get_shared") ? vc_get_shared( 'colors-dashed' ) : array()) + array(
				__( 'Custom Color', 'carservice' ) => 'custom',
			),
		'description' => __( 'Select bar background color.', 'carservice' ),
		'admin_label' => true,
		'param_holder_class' => 'vc_colored-dropdown',
	)
);
vc_add_params('vc_progress_bar', $attributes);
//row inner
$attributes = array(
	array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Type", 'carservice'),
		"param_name" => "type",
		"value" => array(__("Default", 'carservice') => "",  __("Full width", 'carservice') => "full-width",  __("Paralax background", 'carservice') => "full-width cs-parallax", __("Cost calculator form", 'carservice') => "cost-calculator-container"),
		"description" => __("Select row type", "carservice")
	),
	array(
		"type" => "textfield",
		"heading" => __("Form action url", 'carservice'),
		"param_name" => "action",
		"dependency" => Array('element' => "type", 'value' => "cost-calculator-container")
	),
	array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Top margin", 'carservice'),
		"param_name" => "top_margin",
		"value" => array(__("None", 'carservice') => "none",  __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section"),
		"description" => __("Select top margin value for your row", "carservice")
	)
);
vc_add_params('vc_row_inner', $attributes);
//row
vc_map( array(
	'name' => __( 'Row', 'carservice' ),
	'base' => 'vc_row',
	'is_container' => true,
	'icon' => 'icon-wpb-row',
	'show_settings_on_create' => false,
	'category' => __( 'Content', 'carservice' ),
	'class' => 'vc_main-sortable-element',
	'description' => __( 'Place content elements inside the row', 'carservice' ),
	'params' => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Type", 'carservice'),
			"param_name" => "type",
			"value" => array(__("Default", 'carservice') => "",  __("Full width", 'carservice') => "full-width",  __("Paralax background", 'carservice') => "full-width cs-parallax", __("Cost calculator form", 'carservice') => "cost-calculator-container"),
			"description" => __("Select row type", "carservice")
		),
		array(
			"type" => "textfield",
			"heading" => __("Form action url", 'carservice'),
			"param_name" => "action",
			"dependency" => Array('element' => "type", 'value' => "cost-calculator-container")
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Top margin", 'carservice'),
			"param_name" => "top_margin",
			"value" => array(__("None", 'carservice') => "none",  __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section"),
			"description" => __("Select top margin value for your row", "carservice")
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Row stretch', 'carservice' ),
			'param_name' => 'full_width',
			'value' => array(
				__( 'Default', 'carservice' ) => '',
				__( 'Stretch row', 'carservice' ) => 'stretch_row',
				__( 'Stretch row and content', 'carservice' ) => 'stretch_row_content',
				__( 'Stretch row and content (no paddings)', 'carservice' ) => 'stretch_row_content_no_spaces',
			),
			'description' => __( 'Select stretching options for row and content (Note: stretched may not work properly if parent container has "overflow: hidden" CSS property).', 'carservice' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns gap', 'carservice' ),
			'param_name' => 'gap',
			'value' => array(
				'0px' => '0',
				'1px' => '1',
				'2px' => '2',
				'3px' => '3',
				'4px' => '4',
				'5px' => '5',
				'10px' => '10',
				'15px' => '15',
				'20px' => '20',
				'25px' => '25',
				'30px' => '30',
				'35px' => '35',
			),
			'std' => '0',
			'description' => __( 'Select gap between columns in row.', 'carservice' ),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Full height row?', 'carservice' ),
			'param_name' => 'full_height',
			'description' => __( 'If checked row will be set to full height.', 'carservice' ),
			'value' => array( __( 'Yes', 'carservice' ) => 'yes' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns position', 'carservice' ),
			'param_name' => 'columns_placement',
			'value' => array(
				__( 'Middle', 'carservice' ) => 'middle',
				__( 'Top', 'carservice' ) => 'top',
				__( 'Bottom', 'carservice' ) => 'bottom',
				__( 'Stretch', 'carservice' ) => 'stretch',
			),
			'description' => __( 'Select columns position within row.', 'carservice' ),
			'dependency' => array(
				'element' => 'full_height',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Equal height', 'carservice' ),
			'param_name' => 'equal_height',
			'description' => __( 'If checked columns will be set to equal height.', 'carservice' ),
			'value' => array( __( 'Yes', 'carservice' ) => 'yes' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Content position', 'carservice' ),
			'param_name' => 'content_placement',
			'value' => array(
				__( 'Default', 'carservice' ) => '',
				__( 'Top', 'carservice' ) => 'top',
				__( 'Middle', 'carservice' ) => 'middle',
				__( 'Bottom', 'carservice' ) => 'bottom',
			),
			'description' => __( 'Select content position within columns.', 'carservice' ),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Use video background?', 'carservice' ),
			'param_name' => 'video_bg',
			'description' => __( 'If checked, video will be used as row background.', 'carservice' ),
			'value' => array( __( 'Yes', 'carservice' ) => 'yes' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'YouTube link', 'carservice' ),
			'param_name' => 'video_bg_url',
			'value' => 'https://www.youtube.com/watch?v=lMJXxhRFO1k',
			// default video url
			'description' => __( 'Add YouTube link.', 'carservice' ),
			'dependency' => array(
				'element' => 'video_bg',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Parallax', 'carservice' ),
			'param_name' => 'video_bg_parallax',
			'value' => array(
				__( 'None', 'carservice' ) => '',
				__( 'Simple', 'carservice' ) => 'content-moving',
				__( 'With fade', 'carservice' ) => 'content-moving-fade',
			),
			'description' => __( 'Add parallax type background for row.', 'carservice' ),
			'dependency' => array(
				'element' => 'video_bg',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Parallax', 'carservice' ),
			'param_name' => 'parallax',
			'value' => array(
				__( 'None', 'carservice' ) => '',
				__( 'Simple', 'carservice' ) => 'content-moving',
				__( 'With fade', 'carservice' ) => 'content-moving-fade',
			),
			'description' => __( 'Add parallax type background for row (Note: If no image is specified, parallax will use background image from Design Options).', 'carservice' ),
			'dependency' => array(
				'element' => 'video_bg',
				'is_empty' => true,
			),
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Image', 'carservice' ),
			'param_name' => 'parallax_image',
			'value' => '',
			'description' => __( 'Select image from media library.', 'carservice' ),
			'dependency' => array(
				'element' => 'parallax',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Parallax speed', 'carservice' ),
			'param_name' => 'parallax_speed_video',
			'value' => '1.5',
			'description' => __( 'Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)', 'carservice' ),
			'dependency' => array(
				'element' => 'video_bg_parallax',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Parallax speed', 'carservice' ),
			'param_name' => 'parallax_speed_bg',
			'value' => '1.5',
			'description' => __( 'Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)', 'carservice' ),
			'dependency' => array(
				'element' => 'parallax',
				'not_empty' => true,
			),
		),
		vc_map_add_css_animation( false ),
		array(
			'type' => 'el_id',
			'heading' => __( 'Row ID', 'carservice' ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter row ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'carservice' ), 'https://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Disable row', 'carservice' ),
			'param_name' => 'disable_element',
			// Inner param name.
			'description' => __( 'If checked the row won\'t be visible on the public side of your website. You can switch it back any time.', 'carservice' ),
			'value' => array( __( 'Yes', 'carservice' ) => 'yes' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'carservice' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'carservice' ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'carservice' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'carservice' ),
		)
	),
	'js_view' => 'VcRowView'
) );

//column
$vc_column_width_list = array(
	__('1 column - 1/12', 'carservice') => '1/12',
	__('2 columns - 1/6', 'carservice') => '1/6',
	__('3 columns - 1/4', 'carservice') => '1/4',
	__('4 columns - 1/3', 'carservice') => '1/3',
	__('5 columns - 5/12', 'carservice') => '5/12',
	__('6 columns - 1/2', 'carservice') => '1/2',
	__('7 columns - 7/12', 'carservice') => '7/12',
	__('8 columns - 2/3', 'carservice') => '2/3',
	__('9 columns - 3/4', 'carservice') => '3/4',
	__('10 columns - 5/6', 'carservice') => '5/6',
	__('11 columns - 11/12', 'carservice') => '11/12',
	__('12 columns - 1/1', 'carservice') => '1/1'
);
vc_map( array(
	'name' => __( 'Column', 'carservice' ),
	'base' => 'vc_column',
	'icon' => 'icon-wpb-row',
	'is_container' => true,
	'content_element' => false,
	'description' => __( 'Place content elements inside the column', 'carservice' ),
	'params' => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Column type", 'carservice'),
			"param_name" => "type",
			"value" => array(__("Default", 'carservice') => "",  __("Smart (sticky)", 'carservice') => "cs-smart-column"),
			"dependency" => Array('element' => "width", 'value' => array_map('strval', array_values((array_slice($vc_column_width_list, 0, -1)))))
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Top margin", 'carservice'),
			"param_name" => "top_margin",
			"value" => array(__("None", 'carservice') => "none",  __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section"),
			"description" => __("Select top margin value for your column", "carservice")
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Use video background?', 'carservice' ),
			'param_name' => 'video_bg',
			'description' => __( 'If checked, video will be used as row background.', 'carservice' ),
			'value' => array( __( 'Yes', 'carservice' ) => 'yes' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'YouTube link', 'carservice' ),
			'param_name' => 'video_bg_url',
			'value' => 'https://www.youtube.com/watch?v=lMJXxhRFO1k',
			// default video url
			'description' => __( 'Add YouTube link.', 'carservice' ),
			'dependency' => array(
				'element' => 'video_bg',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Parallax', 'carservice' ),
			'param_name' => 'video_bg_parallax',
			'value' => array(
				__( 'None', 'carservice' ) => '',
				__( 'Simple', 'carservice' ) => 'content-moving',
				__( 'With fade', 'carservice' ) => 'content-moving-fade',
			),
			'description' => __( 'Add parallax type background for row.', 'carservice' ),
			'dependency' => array(
				'element' => 'video_bg',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Parallax', 'carservice' ),
			'param_name' => 'parallax',
			'value' => array(
				__( 'None', 'carservice' ) => '',
				__( 'Simple', 'carservice' ) => 'content-moving',
				__( 'With fade', 'carservice' ) => 'content-moving-fade',
			),
			'description' => __( 'Add parallax type background for row (Note: If no image is specified, parallax will use background image from Design Options).', 'carservice' ),
			'dependency' => array(
				'element' => 'video_bg',
				'is_empty' => true,
			),
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Image', 'carservice' ),
			'param_name' => 'parallax_image',
			'value' => '',
			'description' => __( 'Select image from media library.', 'carservice' ),
			'dependency' => array(
				'element' => 'parallax',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Parallax speed', 'carservice' ),
			'param_name' => 'parallax_speed_video',
			'value' => '1.5',
			'description' => __( 'Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)', 'carservice' ),
			'dependency' => array(
				'element' => 'video_bg_parallax',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Parallax speed', 'carservice' ),
			'param_name' => 'parallax_speed_bg',
			'value' => '1.5',
			'description' => __( 'Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)', 'carservice' ),
			'dependency' => array(
				'element' => 'parallax',
				'not_empty' => true,
			),
		),
		vc_map_add_css_animation( false ),
		array(
			'type' => 'el_id',
			'heading' => __( 'Element ID', 'carservice' ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'carservice' ), 'https://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'carservice' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'carservice' ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'carservice' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'carservice' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Width', 'carservice' ),
			'param_name' => 'width',
			'value' => $vc_column_width_list,
			'group' => __( 'Responsive Options', 'carservice' ),
			'description' => __( 'Select column width.', 'carservice' ),
			'std' => '1/1',
		),
		array(
			'type' => 'column_offset',
			'heading' => __( 'Responsiveness', 'carservice' ),
			'param_name' => 'offset',
			'group' => __( 'Responsive Options', 'carservice' ),
			'description' => __( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'carservice' ),
		)
	),
	'js_view' => 'VcColumnView'
) );

//widgetised sidebar
vc_map( array(
	'name' => __( 'Widgetised Sidebar', 'carservice' ),
	'base' => 'vc_widget_sidebar',
	'class' => 'wpb_widget_sidebar_widget',
	'icon' => 'icon-wpb-layout_sidebar',
	'category' => __( 'Structure', 'carservice' ),
	'description' => __( 'WordPress widgetised sidebar', 'carservice' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'carservice' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'carservice' )
		),
		array(
			'type' => 'widgetised_sidebars',
			'heading' => __( 'Sidebar', 'carservice' ),
			'param_name' => 'sidebar_id',
			'description' => __( 'Select widget area to display.', 'carservice' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'carservice' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'carservice' )
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Top margin", 'carservice'),
			"param_name" => "top_margin",
			"value" => array(__("None", 'carservice') => "none",  __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section"),
			"description" => __("Select top margin value for your sidebar", "carservice")
		)
	)
) );

$vc_is_wp_version_3_6_more = version_compare( preg_replace( '/^([\d\.]+)(\-.*$)/', '$1', get_bloginfo( 'version' ) ), '3.6' ) >= 0;
//tab
vc_map( array(
	'name' => __( 'Tab', 'carservice' ),
	'base' => 'vc_tab',
	"as_parent" => array('except' => 'vc_tabs, vc_accordion'),
	"allowed_container_element" => array('vc_row', 'vc_nested_tabs', 'vc_nested_accordion'),
	'is_container' => true,
	'content_element' => false,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'carservice' ),
			'param_name' => 'title',
			'description' => __( 'Tab title.', 'carservice' )
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Icon", 'carservice'),
			"param_name" => "icon",
			"value" => array(
				__("none", 'carservice') => "none",
				__("air-conditioning", 'carservice') => "sl-small-air-conditioning",
				__("alarm", 'carservice') => "sl-small-alarm",
				__("camper", 'carservice') => "sl-small-camper",
				__("car", 'carservice') => "sl-small-car",
				__("car-2", 'carservice') => "sl-small-car-2",
				__("car-3", 'carservice') => "sl-small-car-3",
				__("car-audio", 'carservice') => "sl-small-car-audio",
				__("car-battery", 'carservice') => "sl-small-car-battery",
				__("car-check", 'carservice') => "sl-small-car-check",
				__("car-checklist", 'carservice') => "sl-small-car-checklist",
				__("car-fix", 'carservice') => "sl-small-car-fix",
				__("car-fire", 'carservice') => "sl-small-car-fire",
				__("car-key", 'carservice') => "sl-small-car-key",
				__("car-lock", 'carservice') => "sl-small-car-lock",
				__("car-music", 'carservice') => "sl-small-car-music",
				__("car-oil", 'carservice') => "sl-small-car-oil",
				__("car-setting", 'carservice') => "sl-small-car-setting",
				__("car-wash", 'carservice') => "sl-small-car-wash",
				__("car-wheel", 'carservice') => "sl-small-car-wheel",
				__("caution-fence", 'carservice') => "sl-small-caution-fence",
				__("certificate", 'carservice') => "sl-small-certificate",
				__("check", 'carservice') => "sl-small-check",
				__("check-2", 'carservice') => "sl-small-check-2",
				__("check-shield", 'carservice') => "sl-small-check-shield",
				__("checklist", 'carservice') => "sl-small-checklist",
				__("clock", 'carservice') => "sl-small-clock",
				__("coffee", 'carservice') => "sl-small-coffee",
				__("cog-double", 'carservice') => "sl-small-cog-double",
				__("eco-car", 'carservice') => "sl-small-eco-car",
				__("eco-fuel", 'carservice') => "sl-small-eco-fuel",
				__("eco-fuel-barrel", 'carservice') => "sl-small-eco-fuel-barrel",
				__("eco-globe", 'carservice') => "sl-small-eco-globe",
				__("eco-nature", 'carservice') => "sl-small-eco-nature",
				__("electric-wrench", 'carservice') => "sl-small-electric-wrench",
				__("email", 'carservice') => "sl-small-email",
				__("engine-belt", 'carservice') => "sl-small-engine-belt",
				__("engine-belt-2", 'carservice') => "sl-small-engine-belt-2",
				__("facebook", 'carservice') => "sl-small-facebook",
				__("faq", 'carservice') => "sl-small-faq",
				__("fax", 'carservice') => "sl-small-fax",
				__("fax-2", 'carservice') => "sl-small-fax-2",
				__("garage", 'carservice') => "sl-small-garage",
				__("gauge", 'carservice') => "sl-small-gauge",
				__("gearbox", 'carservice') => "sl-small-gearbox",
				__("google-plus", 'carservice') => "sl-small-google-plus",
				__("gps", 'carservice') => "sl-small-gps",
				__("headlight", 'carservice') => "sl-small-headlight",
				__("heating", 'carservice') => "sl-small-heating",
				__("image", 'carservice') => "sl-small-image",
				__("images", 'carservice') => "sl-small-images",
				__("inflator-pump", 'carservice') => "sl-small-inflator-pump",
				__("lightbulb", 'carservice') => "sl-small-lightbulb",
				__("location-map", 'carservice') => "sl-small-location-map",
				__("oil-can", 'carservice') => "sl-small-oil-can",
				__("oil-gauge", 'carservice') => "sl-small-oil-gauge",
				__("oil-station", 'carservice') => "sl-small-oil-station",
				__("parking-sensor", 'carservice') => "sl-small-parking-sensor",
				__("payment", 'carservice') => "sl-small-payment",
				__("pen", 'carservice') => "sl-small-pen",
				__("percent", 'carservice') => "sl-small-percent",
				__("person", 'carservice') => "sl-small-person",
				__("phone", 'carservice') => "sl-small-phone",
				__("phone-call", 'carservice') => "sl-small-phone-call",
				__("phone-call-24h", 'carservice') => "sl-small-phone-call-24h",
				__("phone-circle", 'carservice') => "sl-small-phone-circle",
				__("piggy-bank", 'carservice') => "sl-small-piggy-bank",
				__("quote", 'carservice') => "sl-small-quote",
				__("road", 'carservice') => "sl-small-road",
				__("screwdriver", 'carservice') => "sl-small-screwdriver",
				__("seatbelt-lock", 'carservice') => "sl-small-seatbelt-lock",
				__("service-24h", 'carservice') => "sl-small-service-24h",
				__("share-time", 'carservice') => "sl-small-share-time",
				__("shopping-cart", 'carservice') => "sl-small-shopping-cart",
				__("signal-warning", 'carservice') => "sl-small-signal-warning",
				__("sign-zigzag", 'carservice') => "sl-small-sign-zigzag",
				__("snow-crystal", 'carservice') => "sl-small-snow-crystal",
				__("speed-gauge", 'carservice') => "sl-small-speed-gauge",
				__("steering-wheel", 'carservice') => "sl-small-steering-wheel",
				__("team", 'carservice') => "sl-small-team",
				__("testimonials", 'carservice') => "sl-small-testimonials",
				__("toolbox", 'carservice') => "sl-small-toolbox",
				__("toolbox-2", 'carservice') => "sl-small-toolbox-2",
				__("truck", 'carservice') => "sl-small-truck",
				__("truck-tow", 'carservice') => "sl-small-truck-tow",
				__("tunning", 'carservice') => "sl-small-tunning",
				__("twitter", 'carservice') => "sl-small-twitter",
				__("user-chat", 'carservice') => "sl-small-user-chat",
				__("video", 'carservice') => "sl-small-video",
				__("wallet", 'carservice') => "sl-small-wallet",
				__("wedding-car", 'carservice') => "sl-small-wedding-car",
				__("windshield", 'carservice') => "sl-small-windshield",
				__("wrench", 'carservice') => "sl-small-wrench",
				__("wrench-double", 'carservice') => "sl-small-wrench-double",
				__("wrench-screwdriver", 'carservice') => "sl-small-wrench-screwdriver",
				__("youtube", 'carservice') => "sl-small-youtube"
			)
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Tab ID', 'carservice' ),
			'param_name' => "tab_id"
		)
	),
	'js_view' => $vc_is_wp_version_3_6_more ? 'VcTabView' : 'VcTabView35'
) );

//box_header
function cs_theme_box_header($atts)
{
	extract(shortcode_atts(array(
		"title" => "Sample Header",
		"type" => "h4",
		"class" => "",
		"bottom_border" => 1,
		"top_margin" => "none"
	), $atts));
	
	return '<' . esc_attr($type) . ((int)$bottom_border || $class!="" || $top_margin!="none" ? ' class="' : '') . ((int)$bottom_border ? ' box-header' : '') . ($class!="" ? ' ' . esc_attr($class) : '') . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . ((int)$bottom_border || $class!="" || $top_margin!="none" ? '"' : '') . '>' . do_shortcode($title) . '</' . esc_attr($type) . '>';
}

//visual composer
vc_map( array(
	"name" => __("Box header", 'carservice'),
	"base" => "box_header",
	"class" => "",
	"controls" => "full",
	"show_settings_on_create" => true,
	"icon" => "icon-wpb-layer-box-header",
	"category" => __('Carservice', 'carservice'),
	"params" => array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Title", 'carservice'),
			"param_name" => "title",
			"value" => "Sample Header"
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Type", 'carservice'),
			"param_name" => "type",
			"value" => array(__("H4", 'carservice') => "h4", __("H1", 'carservice') => "h1", __("H2", 'carservice') => "h2", __("H3", 'carservice') => "h3", __("H5", 'carservice') => "h5", __("H6", 'carservice') => "h6", __("Label", 'carservice') => "label")
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Bottom border", 'carservice'),
			"param_name" => "bottom_border",
			"value" => array(__("yes", 'carservice') => 1,  __("no", 'carservice') => 0)
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Extra class name", 'carservice'),
			"param_name" => "class",
			"value" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Top margin", 'carservice'),
			"param_name" => "top_margin",
			"value" => array(__("None", 'carservice') => "none", __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section")
		)
	)
));

//read more
function cs_theme_button($atts)
{
	extract(shortcode_atts(array(
		"type" => "read_more",
		"icon" => "none",
		"url" => "",
		"title" => __("READ MORE", 'carservice'),
		"label" => "",
		"target" => "",
		"extraclass" => "",
		"top_margin" => "none"
	), $atts));

	$output = (is_rtl() ?  (($label!="" ? '<h3>' : '') . '<a class="' . ($type=="read_more" ? 'more' : 'cs-action-button') . ($type=="action" && !empty($icon) && $icon!="none" ? ' template-' . esc_attr($icon) : '') . (!empty($extraclass) ? ' ' . esc_attr($extraclass) : '') . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . '" href="' . esc_url($url) . '"'  . (!empty($target) ? ' target="' . esc_attr($target) . '"' : '') . ' title="' . esc_attr($title) . '"><span>' . $title . '</span></a>' . ($label!="" ? '<span class="button-label">' . $label . '</span></h3>' : '')) : (($label!="" ? '<h3><span class="button-label">' . $label . '</span>' : '') . '<a class="' . ($type=="read_more" ? 'more' : 'cs-action-button') . ($type=="action" && !empty($icon) && $icon!="none" ? ' template-' . esc_attr($icon) : '') . (!empty($extraclass) ? ' ' . esc_attr($extraclass) : '') . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . '" href="' . esc_url($url) . '"'  . (!empty($target) ? ' target="' . esc_attr($target) . '"' : '') . ' title="' . esc_attr($title) . '"><span>' . $title . '</span></a>' . ($label!="" ? '</h3>' : '')));
	return $output;	
}

//visual composer
vc_map( array(
	"name" => __("Button", 'carservice'),
	"base" => "vc_btn",
	"class" => "",
	"controls" => "full",
	"show_settings_on_create" => true,
	"icon" => "icon-wpb-ui-button",
	"category" => __('Carservice', 'carservice'),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Type", 'carservice'),
			"param_name" => "type",
			"value" => array(__("Read more button", 'carservice') => "read_more", __("Action button", 'carservice') => "action")
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Icon", 'carservice'),
			"param_name" => "icon",
			"value" => array(
				__("none", 'carservice') => "none",
				__("arrow-circle-down", 'carservice') => "arrow-circle-down",
				__("arrow-circle-right", 'carservice') => "arrow-circle-right",
				__("arrow-dropdown", 'carservice') => "arrow-dropdown",
				__("arrow-left-1", 'carservice') => "arrow-left-1",
				__("arrow-left-2", 'carservice') => "arrow-left-2",
				__("arrow-right-1", 'carservice') => "arrow-right-1",
				__("arrow-right-2", 'carservice') => "arrow-right-2",
				__("arrow-menu", 'carservice') => "arrow-menu",
				__("arrow-up", 'carservice') => "arrow-up",
				__("bubble", 'carservice') => "bubble",
				__("bullet", 'carservice') => "bullet",
				__("calendar", 'carservice') => "calendar",
				__("clock", 'carservice') => "clock",
				__("location", 'carservice') => "location",
				__("eye", 'carservice') => "eye",
				__("mail", 'carservice') => "mail",
				__("map-marker", 'carservice') => "map-marker",
				__("phone", 'carservice') => "phone",
				__("search", 'carservice') => "search",
				__("shopping-cart", 'carservice') => "shopping-cart"
			),
			"dependency" => Array('element' => "type", 'value' => "action")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Title", 'carservice'),
			"param_name" => "title",
			"value" => __("READ MORE", 'carservice')
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Label", 'carservice'),
			"param_name" => "label",
			"value" => ""
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Url", 'carservice'),
			"param_name" => "url",
			"value" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Button target", 'carservice'),
			"param_name" => "target",
			"value" => array(__("Same window", 'carservice') => "", __("New window", 'carservice') => "_blank")
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Top margin", 'carservice'),
			"param_name" => "top_margin",
			"value" => array(__("None", 'carservice') => "none", __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Extra class name", 'carservice'),
			"param_name" => "extraclass",
			"value" => ""
		),
	)
));
?>