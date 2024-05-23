<?php
function cs_theme_admin_init()
{
	wp_register_script("theme-colorpicker", get_template_directory_uri() . "/admin/js/colorpicker.js", array("jquery"));
	wp_register_script("theme-admin", get_template_directory_uri() . "/admin/js/theme_admin.js", array("jquery", "theme-colorpicker"));
	wp_register_script("jquery-bqq", get_template_directory_uri() . "/admin/js/jquery.ba-bbq.min.js", array("jquery"));
	wp_register_script("theme-admin-widgets", get_template_directory_uri() . "/admin/js/theme_admin_widgets.js", array("jquery"));
	wp_register_style("theme-colorpicker", get_template_directory_uri() . "/admin/style/colorpicker.css");
	wp_register_style("theme-admin-style", get_template_directory_uri() . "/admin/style/style.css");
	wp_register_style("theme-admin-style-rtl", get_template_directory_uri() . "/admin/style/rtl.css");
}
add_action("admin_init", "cs_theme_admin_init");

function cs_theme_admin_print_scripts()
{
	wp_enqueue_script('jquery-bqq');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('theme-admin');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
	wp_enqueue_style("google-font-open-sans", "//fonts.googleapis.com/css?family=Open+Sans:400,600");
	wp_enqueue_style("cs-social", get_template_directory_uri() ."/fonts/social/styles.css");
	wp_enqueue_style("cs-streamline-small", get_template_directory_uri() ."/fonts/streamline-small/style.css");
	
	$sidebars = array(
		"default" => array(
			array(
				"name" => "footer_top",
				"label" => __("footer top", 'carservice')
			),
			array(
				"name" => "footer_bottom",
				"label" => __("footer bottom", 'carservice')
			)
		),
		"template-blog.php" => array(
			array(
				"name" => "footer_top",
				"label" => __("footer top", 'carservice')
			),
			array(
				"name" => "footer_bottom",
				"label" => __("footer bottom", 'carservice')
			)
		),
		"single.php" => array(
			array(
				"name" => "footer_top",
				"label" => __("footer top", 'carservice')
			),
			array(
				"name" => "footer_bottom",
				"label" => __("footer bottom", 'carservice')
			)
		),
		"single-ql_services.php" => array(
			array(
				"name" => "footer_top",
				"label" => __("footer top", 'carservice')
			),
			array(
				"name" => "footer_bottom",
				"label" => __("footer bottom", 'carservice')
			)
		),
		"single-ql_team.php" => array(
			array(
				"name" => "footer_top",
				"label" => __("footer top", 'carservice')
			),
			array(
				"name" => "footer_bottom",
				"label" => __("footer bottom", 'carservice')
			)
		),
		"single-ql_galleries.php" => array(
			array(
				"name" => "footer_top",
				"label" => __("footer top", 'carservice')
			),
			array(
				"name" => "footer_bottom",
				"label" => __("footer bottom", 'carservice')
			)
		),
		"search.php" => array(
			array(
				"name" => "footer_top",
				"label" => __("footer top", 'carservice')
			),
			array(
				"name" => "footer_bottom",
				"label" => __("footer bottom", 'carservice')
			)
		),
		"template-default-without-breadcrumbs.php" => array(
			array(
				"name" => "footer_top",
				"label" => __("footer top", 'carservice')
			),
			array(
				"name" => "footer_bottom",
				"label" => __("footer bottom", 'carservice')
			)
		),
		"404.php" => array(
			array(
				"name" => "footer_top",
				"label" => __("footer top", 'carservice')
			),
			array(
				"name" => "footer_bottom",
				"label" => __("footer bottom", 'carservice')
			)
		)
	);
	//get theme sidebars
	$theme_sidebars = array();
	$theme_sidebars_array = get_posts(array(
		'post_type' => 'carservice_sidebars',
		'posts_per_page' => '-1',
		'nopaging' => true,
		'post_status' => 'publish',
		'orderby' => 'menu_order',
		'order' => 'ASC'
	));
	$theme_sidebars[0]["id"] = -1;
	$theme_sidebars[0]["title"] = __("None", 'carservice');
	for($i=1; $i<=count($theme_sidebars_array); $i++)
	{
		$theme_sidebars[$i]["id"] = $theme_sidebars_array[$i-1]->ID;
		$theme_sidebars[$i]["title"] = $theme_sidebars_array[$i-1]->post_title;
	}
	
	$data = array(
		'img_url' => esc_url(get_template_directory_uri() . "/images/"),
		'admin_img_url' => esc_url(get_template_directory_uri() . "/admin/images/"),
		'sidebar_label' => __('Sidebar', 'carservice'),
		'sidebars' => $sidebars,
		'theme_sidebars' => $theme_sidebars,
		'page_sidebars' => get_post_meta(get_the_ID(), "carservice_page_sidebars", true),
		'themename' => 'carservice',
		'import_confirmation_message' => __('Please confirm the dummy data import.', 'carservice'),
		'shop_import_confirmation_message' => __('Please confirm the shop dummy data import.', 'carservice'),
		'import_in_progress_message' => __("Please wait and don't reload the page when import is in progress!", 'carservice'),
		'import_error_message' => __('Error during import:', 'carservice')
	);
	//pass data to javascript
	$params = array(
		'l10n_print_after' => 'config = ' . json_encode($data) . ';'
	);
	wp_localize_script("theme-admin", "config", $params);
}

function cs_theme_admin_print_scripts_colorpicker()
{	
	wp_enqueue_script('theme-admin');
	wp_enqueue_script('theme-colorpicker');
	wp_enqueue_style('theme-colorpicker');
}

function cs_theme_admin_print_scripts_widgets()
{	
	wp_enqueue_script('theme-admin-widgets');
}

function cs_theme_admin_print_scripts_all()
{
	global $theme_options;
	wp_enqueue_style('theme-admin-style');
	if((is_rtl() || (isset($theme_options['direction']) && $theme_options["direction"]=='rtl')) && ((isset($_COOKIE["cs_direction"]) && $_COOKIE["cs_direction"]!="LTR") || !isset($_COOKIE["cs_direction"])))
		wp_enqueue_style('theme-admin-style-rtl');
}

function cs_theme_admin_menu_theme_options() 
{
	add_action("admin_print_scripts", "cs_theme_admin_print_scripts_all");
	add_action("admin_print_scripts-post-new.php", "cs_theme_admin_print_scripts");
	add_action("admin_print_scripts-post.php", "cs_theme_admin_print_scripts");
	add_action("admin_print_scripts-appearance_page_ThemeOptions", "cs_theme_admin_print_scripts");
	add_action("admin_print_scripts-appearance_page_ThemeOptions", "cs_theme_admin_print_scripts_colorpicker");
	add_action("admin_print_scripts-widgets.php", "cs_theme_admin_print_scripts_widgets");
}
add_action("admin_menu", "cs_theme_admin_menu_theme_options");
	
//visual composer
//if(function_exists("vc_add_shortcode_param"))
//{
	//dropdownmulti
	function cs_dropdownmultiple_settings_field($settings, $value)
	{
		$value = ($value==null ? array() : $value);
		if(!is_array($value))
			$value = explode(",", $value);
		$output = '<select name="'.esc_attr($settings['param_name']).'" class="wpb_vc_param_value wpb-input wpb-select '.esc_attr($settings['param_name']).' '.esc_attr($settings['type']).'" multiple>';
				foreach ( $settings['value'] as $text_val => $val ) {
					if ( is_numeric($text_val) && is_string($val) || is_numeric($text_val) && is_numeric($val) ) {
						$text_val = $val;
					}
				   // $val = strtolower(str_replace(array(" "), array("_"), $val));
					$selected = '';
					if ( in_array($val,$value) ) $selected = ' selected="selected"';
					$output .= '<option class="'.esc_attr($val).'" value="'.esc_attr($val).'"'.esc_attr($selected).'>'.$text_val.'</option>';
				}
				$output .= '</select>';
		return $output;
	}
	//hidden
	function cs_hidden_settings_field($settings, $value) 
	{
	   return '<input name="'.esc_attr($settings['param_name'])
				 .'" class="wpb_vc_param_value wpb-textinput '
				 .esc_attr($settings['param_name']).' '.esc_attr($settings['type']).'_field" type="hidden" value="'
				 .esc_attr($value).'"/>';
	}
	//readonly
	function cs_readonly_settings_field($settings, $value) 
	{
	   return '<input name="'.esc_attr($settings['param_name'])
				 .'" class="wpb_vc_param_value wpb-textinput '
				 .esc_attr($settings['param_name']).' '.esc_attr($settings['type']).'_field" type="text" readonly="readonly" value="'
				 .esc_attr($value).'"/>';
	}
	//add item button
	function cs_listitem_settings_field($settings, $value)
	{
		$value = explode(",", $value);
		$output = '<input type="button" value="' . esc_attr__('Add list item', 'carservice') . '" name="'.esc_attr($settings['param_name']).'" class="button '.esc_attr($settings['param_name']).' '.esc_attr($settings['type']).'" style="width: auto; padding: 0 10px 1px;"/>';
		return $output;
	}
	//add item window
	function cs_listitemwindow_settings_field($settings, $value)
	{
		$value = explode(",", $value);
		$output = '<div class="listitemwindow vc_panel vc_shortcode-edit-form" name="'.esc_attr($settings['param_name']).'">
			<div class="vc_panel-heading">
				<a class="vc_close" href="#" title="' . esc_attr__("Close panel", 'carservice') . '"><i class="vc_icon"></i></a>
				<h3 class="vc_panel-title">' . __('Add New List Item', 'carservice') . '</h3>
			</div>
			<div class="modal-body wpb-edit-form" style="display: block;min-height: auto;">
				<div class="vc_row-fluid wpb_el_type_textfield">
					<div class="wpb_element_label">' . __("Text", 'carservice') . '</div>
					<div class="edit_form_line">
						<input type="text" value="" class="wpb_vc_param_value wpb-textinput textfield" name="item_content">
					</div>
				</div>
				<div class="vc_row-fluid wpb_el_type_textfield">
					<div class="wpb_element_label">' . __("Url", 'carservice') . '</div>
					<div class="edit_form_line">
						<input type="text" value="" class="wpb_vc_param_value wpb-textinput textfield" name="item_url">
					</div>
				</div>
				<div class="vc_row-fluid wpb_el_type_dropdown">
					<div class="wpb_element_label">' . __("Url target", 'carservice') . '</div>
					<div class="edit_form_line">
						<select class="wpb_vc_param_value wpb-input wpb-select item_url_target dropdown" name="item_url_target">
							<option selected="selected" value="new_window">' . __("new window", 'carservice') . '</option>
							<option value="same_window">' . __("same window", 'carservice') . '</option>
						</select>
					</div>
				</div>
				<div class="vc_row-fluid wpb_el_type_dropdown">
					<div class="wpb_element_label">' . __("Icon", 'carservice') . '</div>
					<div class="edit_form_line">
						<select class="wpb_vc_param_value wpb-input wpb-select item_type dropdown" name="item_icon">
							<option selected="selected" value="">' . __("-", 'carservice') . '</option>
							<option value="bullet">' . __("Bullet", 'carservice') . '</option>
						</select>
					</div>
				</div>
				<div class="wpb_el_type_colorpicker vc_wrapper-param-type-colorpicker vc_shortcode-param vc_column" data-vc-ui-element="panel-shortcode-param" data-vc-shortcode-param-name="item_content_color" data-param_type="colorpicker" data-param_settings="{&quot;type&quot;:&quot;colorpicker&quot;}">
					<div class="wpb_element_label">' . __("Custom text color", 'carservice') . '</div>
					<div class="edit_form_line">
						<div class="color-group">
							<div class="wp-picker-container vc_color-picker">
								<span class="wp-picker-input-wrap">
									<input name="item_content_color" class="wpb_vc_param_value wpb-textinput item_content_color colorpicker_field vc_color-control wp-color-picker" type="text">
									<input class="button button-small hidden wp-picker-clear" value="Clear" type="button">
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="edit_form_actions" style="padding-top: 20px;">
					<a id="add-item-shortcode" class="button-primary" href="#">' . __("Add Item", 'carservice') . '</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="cancel-item-options button" href="#">' . __("Cancel", 'carservice') . '</a>
				</div>
			</div>
		</div>';
		return $output;
	}
//}
?>