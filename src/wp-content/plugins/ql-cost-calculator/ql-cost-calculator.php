<?php
/*
Plugin Name: Cost Calculator for WordPress
Plugin URI: https://1.envato.market/cost-calculator-for-wordpress
Description: Cost Calculator plugin is a unique tool which allows you to easily create price estimation forms to give your client idea of the cost of your service.
Author: QuanticaLabs
Author URI: https://1.envato.market/quanticalabs-portfolio-codecanyon
Version: 5.4
Text Domain: cost-calculator
*/

//translation
function cost_calculator_load_textdomain()
{
	load_plugin_textdomain("cost-calculator", false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'cost_calculator_load_textdomain');

//documentation link
function cost_calculator_documentation_link($links)
{
	$documentation_link = sprintf(__("<a href='%s' title='Documentation'>Documentation</a>", 'cost-calculator'), plugins_url('documentation/index.html', __FILE__)); 
	array_unshift($links, $documentation_link); 
	return $links;
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'cost_calculator_documentation_link');

//settings link
function cost_calculator_settings_link($links) 
{ 
	$settings_link = '<a href="admin.php?page=cost_calculator" title="Settings">Settings</a>'; 
	array_unshift($links, $settings_link); 
	return $links;
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'cost_calculator_settings_link');

function cost_calculator_enqueue_scripts()
{
	wp_enqueue_script("jquery-ui-core");
	wp_enqueue_script("jquery-ui-datepicker", false, array("jquery"), false, true);
	wp_enqueue_script("jquery-ui-selectmenu", false, array("jquery"), false, true);
	wp_enqueue_script("jquery-ui-slider", false, array("jquery"), false, true);
	wp_enqueue_script("jquery-ui-touch-punch", plugins_url('js/jquery.ui.touch-punch.min.js', __FILE__), array("jquery"), false, true);
	wp_enqueue_script("jquery-costCalculator", plugins_url('js/jquery.costCalculator.min.js', __FILE__), array("jquery"), false, true);
	wp_enqueue_script("jquery-qtip", plugins_url('js/jquery.qtip.min.js', __FILE__), array("jquery"), false, true);
	wp_enqueue_script("jquery-block-ui", plugins_url('js/jquery.blockUI.min.js', __FILE__), array("jquery"), false, true);
	if(function_exists("is_customize_preview") && !is_customize_preview())
		wp_enqueue_script('cost_calculator_main', plugins_url('js/cost_calculator.js', __FILE__), array("jquery"), false, true);
	wp_register_script("google-recaptcha-v2", "https://google.com/recaptcha/api.js", array(), false, true);
	wp_enqueue_style("jquery-qtip", plugins_url('style/jquery.qtip.css', __FILE__));
	wp_enqueue_style("cc-template", plugins_url('/fonts/template/style.css', __FILE__));
	$cost_calculator_global_form_options = get_option("cost_calculator_global_form_options");
	if((int)$cost_calculator_global_form_options["google_recaptcha"]==3 && $cost_calculator_global_form_options["recaptcha_site_key"]!="" && $cost_calculator_global_form_options["recaptcha_secret_key"]!="")
	{
		wp_enqueue_script("google-recaptcha-v3", "https://google.com/recaptcha/api.js?render=" . esc_attr($cost_calculator_global_form_options["recaptcha_site_key"]), array(), false, true);
	}
	if($cost_calculator_global_form_options["primary_font"]!="" && $cost_calculator_global_form_options["primary_font_custom"]=="")
		wp_enqueue_style("cc-google-font-primary", "//fonts.googleapis.com/css?family=" . urlencode($cost_calculator_global_form_options["primary_font"]) . (!empty($cost_calculator_global_form_options["primary_font_variant"]) ? ":" . implode(",", $cost_calculator_global_form_options["primary_font_variant"]) : "") . (!empty($cost_calculator_global_form_options["primary_font_subset"]) ? "&subset=" . implode(",", $cost_calculator_global_form_options["primary_font_subset"]) : ""));
	else if($cost_calculator_global_form_options["primary_font_custom"]=="")
		wp_enqueue_style("cc-google-font-raleway", "//fonts.googleapis.com/css?family=Raleway:400&amp;subset=latin-ext");
	if($cost_calculator_global_form_options["secondary_font"]!="" && $cost_calculator_global_form_options["secondary_font_custom"]=="")
		wp_enqueue_style("cc-google-font-secondary", "//fonts.googleapis.com/css?family=" . urlencode($cost_calculator_global_form_options["secondary_font"]) . (!empty($cost_calculator_global_form_options["secondary_font_variant"]) ? ":" . implode(",", $cost_calculator_global_form_options["secondary_font_variant"]) : "") . (!empty($cost_calculator_global_form_options["secondary_font_subset"]) ? "&subset=" . implode(",", $cost_calculator_global_form_options["secondary_font_subset"]) : ""));
	else if($cost_calculator_global_form_options["secondary_font_custom"]=="")
		wp_enqueue_style("cc-google-font-lato", "//fonts.googleapis.com/css?family=Lato:300,400&amp;subset=latin-ext");
	wp_enqueue_style("cost_calculator_style", plugins_url('style/style.css', __FILE__));
	wp_enqueue_style("cost_calculator_style_responsive", plugins_url('style/responsive.css', __FILE__));
	$cookie_is_rtl = (isset($_COOKIE['cm_direction']) ? $_COOKIE['cm_direction'] : (isset($_COOKIE['cs_direction']) ? $_COOKIE['cs_direction'] : (isset($_COOKIE['re_direction']) ? $_COOKIE['re_direction'] : '')));
	$cost_calculator_is_rtl = (is_rtl() && (($cookie_is_rtl!='' && $cookie_is_rtl!="LTR") || $cookie_is_rtl=='')) || ($cookie_is_rtl!='' && $cookie_is_rtl=="RTL") ? 1 : 0;
	if($cost_calculator_is_rtl)
		wp_enqueue_style("cost_calculator_style_rtl", plugins_url('style/rtl.css', __FILE__));
	ob_start();
	require_once("custom_colors.php");
	$custom_colors_css = ob_get_clean();
	wp_add_inline_style("cost_calculator_style", $custom_colors_css);
	
	$data = array();
	$data["ajaxurl"] = admin_url("admin-ajax.php");
	$data["is_rtl"] = $cost_calculator_is_rtl;
	$data["recaptcha"] = (int)$cost_calculator_global_form_options["google_recaptcha"];
	$data["recaptcha_site_key"] = $cost_calculator_global_form_options["recaptcha_site_key"];
	
	//pass data to javascript
	$params = array(
		'l10n_print_after' => 'cost_calculator_config = ' . json_encode($data) . ';'
	);
	wp_localize_script("cost_calculator_main", "cost_calculator_config", $params);
}
add_action('wp_enqueue_scripts', 'cost_calculator_enqueue_scripts', 11);

//admin
if(is_admin())
{
	function cost_calculator_admin_menu()
	{
		$page = add_menu_page(__("Cost Calculator", 'cost-calculator'), __("Cost Calculator", 'cost-calculator'), 'manage_options', 'cost_calculator', 'cost_calculator_admin_page', 'dashicons-welcome-widgets-menus', 20);
		$global_config_page = add_submenu_page('cost_calculator', 'Global config', 'Global Config', 'manage_options', 'cost_calculator_admin_page_global_config', 'cost_calculator_admin_page_global_config');
		$email_config_page = add_submenu_page('cost_calculator', 'Template config', 'Template Config', 'manage_options', 'cost_calculator_admin_page_email_config', 'cost_calculator_admin_page_email_config');
		$import_dummy_data_page = add_submenu_page('cost_calculator', 'Import dummy data', 'Import Dummy Data', 'manage_options', 'cost_calculator_admin_page_import_dummy_data', 'cost_calculator_admin_page_import_dummy_data');
		add_action("admin_enqueue_scripts", "cost_calculator_admin_enqueue_scripts");
	}
	add_action('admin_menu', 'cost_calculator_admin_menu');
	
	function cost_calculator_admin_init()
	{
		wp_register_script("cost-calculator-colorpicker",  plugins_url('admin/js/colorpicker.js', __FILE__), array("jquery"));
		//wp_deregister_script("jquery-ui-selectmenu");
		//wp_enqueue_script("jquery-ui-selectmenu", plugins_url('admin/js/selectmenu_fix.js', __FILE__), array("jquery"), false, true);
		wp_register_script("cost-calculator-admin", plugins_url('admin/js/cost-calculator-admin.js', __FILE__), array("jquery", "jquery-ui-core", "jquery-ui-selectmenu", "jquery-ui-sortable", "jquery-ui-dialog", "cost-calculator-colorpicker", "shortcode"));
		if(function_exists("register_block_type"))
		{
			global $pagenow;
			$blockScriptDeps = array("wp-blocks", "wp-components", "wp-element", "wp-i18n", "wp-editor");
			if($pagenow == "widgets.php")
			{
				$blockScriptDeps = array("wp-blocks", "wp-components", "wp-element", "wp-i18n");
			}
			wp_register_script("cost-calculator-gutenberg-block", plugins_url('admin/js/block.build.js', __FILE__ ), $blockScriptDeps);
			wp_set_script_translations("cost-calculator-gutenberg-block", 'cost-calculator', plugin_dir_path( __FILE__) . 'languages');
		}
		wp_enqueue_style("cc-google-font-open-sans", '//fonts.googleapis.com/css?family=Open+Sans:400,400i&amp;subset=latin-ext');
		wp_register_style("cost-calculator-colorpicker", plugins_url('admin/style/colorpicker.css', __FILE__));
		wp_enqueue_style("cc-plugin", plugins_url('/fonts/template-admin/style.css', __FILE__));
		wp_register_style("jquery-ui-dialog", includes_url('css/jquery-ui-dialog.min.css', __FILE__));
		wp_register_style("cost-calculator-admin-style", plugins_url('admin/style/style.css', __FILE__), array("cost-calculator-colorpicker"));
		$cost_calculator_contact_form_options = get_option("cost_calculator_contact_form_options");
		if(!$cost_calculator_contact_form_options)
		{
			$cost_calculator_contact_form_options = array(
				"admin_name" => get_option("admin_email"),
				"admin_email" => get_option("admin_email"),
				"admin_name_from" => "",
				"admin_email_from" => "",
				"smtp_host" => "",
				"smtp_username" => "",
				"smtp_password" => "",
				"smtp_port" => "",
				"smtp_secure" => "",
				"email_subject" => __("Calculation from: [name]", 'cost-calculator'),
				"calculation_details_header" => __("Calculation details", 'cost-calculator'),
				"template" => "<html>
	<head>
	</head>
	<body>
		<table style='border: 1px solid black; border-collapse: collapse;'>
<tbody>
<tr style='border: 1px solid black;'>
<td style='border: 1px solid black;' colspan='2'><b>" . __("Contact details", 'cost-calculator') . "</b></td>
</tr>
<tr style='border: 1px solid black;'>
<td style='border: 1px solid black;'>Name</td>
<td style='border: 1px solid black;'>[name]</td>
</tr>
<tr style='border: 1px solid black;'>
<td style='border: 1px solid black;'>E-mail</td>
<td style='border: 1px solid black;'>[email]</td>
</tr>
<tr style='border: 1px solid black;'>
<td style='border: 1px solid black;'>Phone</td>
<td style='border: 1px solid black;'>[phone]</td>
</tr>
<tr style='border: 1px solid black;'>
<td style='border: 1px solid black;'>Message</td>
<td style='border: 1px solid black;'>[message]</td>
</tr>
</tbody>
</table>
<br><br>
[form_data]
	</body>
</html>",
				"name_message" => __("Please enter your name.", 'cost-calculator'),
				"email_message" => __("Please enter valid e-mail.", 'cost-calculator'),
				"phone_message" => __("Please enter your phone number.", 'cost-calculator'),
				"message_message" => __("Please enter your message.", 'cost-calculator'),
				"recaptcha_message" => __("reCAPTCHA verification failed.", 'cost-calculator'),
				"terms_message" => __("Checkbox is required.", 'cost-calculator'),
				"thankyou_message" => __("Thank you for contacting us", 'cost-calculator'),
				"error_message" => __("Sorry, we can't send this message", 'cost-calculator')
			);
			add_option("cost_calculator_contact_form_options", $cost_calculator_contact_form_options);
		}
		else if(!get_option("cost_calculator_contact_form_options_updated"))
		{
			//update cost calculator contact form options
			$cost_calculator_contact_form_options["recaptcha_message"] = __("reCAPTCHA verification failed.", 'cost-calculator');
			$cost_calculator_contact_form_options["terms_message"] = __("Checkbox is required.", 'cost-calculator');
			update_option("cost_calculator_contact_form_options", $cost_calculator_contact_form_options);
			add_option("cost_calculator_contact_form_options_updated", 1);
		}
		$cost_calculator_global_form_options = get_option("cost_calculator_global_form_options");
		if(!$cost_calculator_global_form_options)
		{
			$cost_calculator_global_form_options = array(
				"calculator_skin" => "default",
				"main_color" => "",
				"box_color" => "",
				"text_color" => "",
				"border_color" => "",
				"label_color" => "",
				"dropdowncheckbox_label_color" => "",
				"form_label_color" => "",
				"inactive_color" => "",
				"tooltip_background_color" => "",
				"primary_font_custom" => "",
				"primary_font" => "",
				"primary_font_variant" => "",
				"primary_font_subset" => "",
				"secondary_font_custom" => "",
				"secondary_font" => "",
				"secondary_font_variant" => "",
				"secondary_font_subset" => "",
				"send_email" => 1,
				"send_email_client" => 0,
				"save_calculation" => 1,
				"calculation_status" => "draft",
				"google_recaptcha" => 0,
				"recaptcha_site_key" => "",
				"recaptcha_secret_key" => "",
				"wpbakery_noconflict" => 0
			);
			add_option("cost_calculator_global_form_options", $cost_calculator_global_form_options);
		}
	}
	add_action('admin_init', 'cost_calculator_admin_init');
	
	function cost_calculator_block()
	{
		if(function_exists("register_block_type"))
		{
			register_block_type("ql-cost-calculator/block", array(
				"editor_script" => "cost-calculator-gutenberg-block"
			));
			add_filter("block_categories_all", "cost_calculator_create_block_category", 10, 2);
		}
	}
	add_action('init', 'cost_calculator_block');
	
	function cost_calculator_create_block_category($categories, $post)
	{
		return array_merge(
			$categories,
			array(
				array(
					'slug' => 'ql-cost-calculator',
					'title' => __("Cost Calculator", 'cost-calculator'),
				),
			)
		);
	}

	function cost_calculator_admin_enqueue_scripts($hook)
	{
		if($hook=="cost-calculator_page_cost_calculator_admin_page_email_config")
		{
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-tabs');
		}
		//if($hook=="toplevel_page_cost_calculator")
		//{
			wp_enqueue_script('cost-calculator-admin');
		//}
		wp_enqueue_style('jquery-ui-dialog');
		wp_enqueue_style('cost-calculator-admin-style');
		$data = array(
			'message_wrong_id' => __("Shortcode ID field accepts only the following characters: letters, numbers, hyphen(-) and underscore(_)", 'cost-calculator'),
			'message_content_area' => __("Please make sure that cost calculator content area isn't empty.", 'cost-calculator'),
			'message_shortcode_saved' => __("Cost Calculator shortcode saved.", 'cost-calculator'),
			'message_shortcode_delete' => __("Click OK to delete selected shortcode.", 'cost-calculator'),
			'message_shortcode_deleted' => __("Cost Calculator shortcode deleted.", 'cost-calculator'),
			'message_shortcode_exists' => __("Shortcode with given id already exists. Click OK to overwrite.", 'cost-calculator'),
			'message_row_delete' => __("Click OK to delete selected row.", 'cost-calculator'),
			'shortcode_id_label_new' => __("Create new shortcode id *", "cost-calculator"),
			'shortcode_id_label_edit' => __("Current shortcode id *", "cost-calculator"),
			'show_advanced_text' => __("Show advanced settings...", "cost-calculator"),
			'hide_advanced_text' => __("Hide advanced settings...", "cost-calculator"),
			'message_shortcode_id_description' => __("Unique identifier for cost calculator shortcode.", 'cost-calculator'),
			'message_shortcode_id_example' => __("Shortcode:", 'cost-calculator'),
			'message_import_in_progress' => __("Please wait and don't reload the page when import is in progress!", 'cost-calculator'),
			'message_import_error' => __("Error during import:", 'cost-calculator')
		);
		$cost_calculator_shortcodes_list = get_option("cost_calculator_shortcodes_list");
		$cost_calculator_shortcodes_array = array();
		if(!empty($cost_calculator_shortcodes_list))
		{
			foreach($cost_calculator_shortcodes_list as $key=>$val)
				$cost_calculator_shortcodes_array[] = $key;
		}
		$data['cost_calculator_shortcodes_array'] = $cost_calculator_shortcodes_array;
		//pass data to javascript
		$params = array(
			'l10n_print_after' => 'cost_calculator_config = ' . json_encode($data) . ';'
		);
		wp_localize_script("cost-calculator-admin", "cost_calculator_config", $params);
	}
	
	function cost_calculator_admin_page()
	{
		$cost_calculator_global_form_options = array(
			"main_color" => '',
			"box_color" => '',
			"text_color" => '',
			"border_color" => '',
			"label_color" => '',
			"dropdowncheckbox_label_color" => '',
			"form_label_color" => '',
			"inactive_color" => '',
			"tooltip_background_color" => '',
			"primary_font_custom" => '',
			"primary_font" => '',
			"primary_font_variant" => "",
			"primary_font_subset" => '',
			"secondary_font_custom" => '',
			"secondary_font" => '',
			"secondary_font_variant" => "",
			"secondary_font_subset" => '',
			"send_email" => '',
			"send_email_client" => '',
			"save_calculation" => '',
			"calculation_status" => '',
			"google_recaptcha" => '',
			"recaptcha_site_key" => '',
			"recaptcha_secret_key" => '',
			"wpbakery_noconflict" => '',
		);
		$cost_calculator_global_form_options = cost_calculator_stripslashes_deep(array_merge($cost_calculator_global_form_options, (array)get_option("cost_calculator_global_form_options")));
		require_once("admin/admin-page.php");
	}
	
	function cost_calculator_admin_page_email_config()
	{
		$message = "";
		if(isset($_POST["action"]) && $_POST["action"]=="save")
		{
			$cost_calculator_contact_form_options = array(
				"admin_name" => $_POST["admin_name"],
				"admin_email" => $_POST["admin_email"],
				"admin_name_from" => $_POST["admin_name_from"],
				"admin_email_from" => $_POST["admin_email_from"],
				"smtp_host" => $_POST["smtp_host"],
				"smtp_username" => $_POST["smtp_username"],
				"smtp_password" => $_POST["smtp_password"],
				"smtp_port" => $_POST["smtp_port"],
				"smtp_secure" => $_POST["smtp_secure"],
				"email_subject" => $_POST["email_subject"],
				"calculation_details_header" => $_POST["calculation_details_header"],
				"template" => $_POST["template"],
				"name_message" => $_POST["name_message"],
				"email_message" => $_POST["email_message"],
				"recaptcha_message" => $_POST["recaptcha_message"],
				"terms_message" => $_POST["terms_message"],
				"phone_message" => $_POST["phone_message"],
				"message_message" => $_POST["message_message"],
				"thankyou_message" => $_POST["thankyou_message"],
				"error_message" => $_POST["error_message"]
			);
			update_option("cost_calculator_contact_form_options", $cost_calculator_contact_form_options);
			$message = __("Options saved!", "cost-calculator");
		}
		$cost_calculator_contact_form_options = array(
			"admin_name" => '',
			"admin_email" => '',
			"admin_name_from" => '',
			"admin_email_from" => '',
			"smtp_host" => '',
			"smtp_username" => '',
			"smtp_password" => '',
			"smtp_port" => '',
			"smtp_secure" => '',
			"email_subject" => '',
			"calculation_details_header" => '',
			"template" => '',
			"name_message" => '',
			"email_message" => '',
			"phone_message" => '',
			"message_message" => '',
			"recaptcha_message" => '',
			"terms_message" => '',
			"thankyou_message" => '',
			"error_message" => ''
		);
		global $theme_options;
		if(function_exists("finpeak_theme_after_setup_theme"))
		{
			global $finpeak_theme_options;
			$theme_options = $finpeak_theme_options;
		}
		$smtp = (isset($theme_options["cf_smtp_host"]) ? $theme_options["cf_smtp_host"] : "");
		$cost_calculator_contact_form_options = cost_calculator_stripslashes_deep(array_merge($cost_calculator_contact_form_options, (array)get_option("cost_calculator_contact_form_options")));
		require_once("admin/admin-page-email-config.php");
	}
	
	function cost_calculator_admin_page_global_config()
	{	
		$message = "";
		if(isset($_POST["action"]) && $_POST["action"]=="save")
		{
			$cost_calculator_global_form_options = array(
				"calculator_skin" => $_POST["calculator_skin"],
				"main_color" => $_POST["main_color"],
				"box_color" => $_POST["box_color"],
				"text_color" => $_POST["text_color"],
				"border_color" => $_POST["border_color"],
				"label_color" => $_POST["label_color"],
				"dropdowncheckbox_label_color" => $_POST["dropdowncheckbox_label_color"],
				"form_label_color" => $_POST["form_label_color"],
				"inactive_color" => $_POST["inactive_color"],
				"tooltip_background_color" => $_POST["tooltip_background_color"],
				"primary_font_custom" => $_POST["primary_font_custom"],
				"primary_font" => $_POST["primary_font"],
				"primary_font_variant" => (isset($_POST["primary_font_variant"]) ? $_POST["primary_font_variant"] : ""),
				"primary_font_subset" => (isset($_POST["primary_font_subset"]) ? $_POST["primary_font_subset"] : ""),
				"secondary_font_custom" => $_POST["secondary_font_custom"],
				"secondary_font" => $_POST["secondary_font"],
				"secondary_font_variant" => (isset($_POST["secondary_font_variant"]) ? $_POST["secondary_font_variant"] : ""),
				"secondary_font_subset" => (isset($_POST["secondary_font_subset"]) ? $_POST["secondary_font_subset"] : ""),
				"send_email" => $_POST["send_email"],
				"send_email_client" => $_POST["send_email_client"],
				"save_calculation" => $_POST["save_calculation"],
				"calculation_status" => $_POST["calculation_status"],
				"google_recaptcha" => $_POST["google_recaptcha"],
				"recaptcha_site_key" => $_POST["recaptcha_site_key"],
				"recaptcha_secret_key" => $_POST["recaptcha_secret_key"],
				"wpbakery_noconflict" => $_POST["wpbakery_noconflict"]
			);
			update_option("cost_calculator_global_form_options", $cost_calculator_global_form_options);
			$message = __("Options saved!", "cost-calculator");
		}
		$cost_calculator_global_form_options = array(
			"calculator_skin" => '',
			"main_color" => '',
			"box_color" => '',
			"text_color" => '',
			"border_color" => '',
			"label_color" => '',
			"dropdowncheckbox_label_color" => '',
			"form_label_color" => '',
			"inactive_color" => '',
			"tooltip_background_color" => '',
			"primary_font_custom" => '',
			"primary_font" => '',
			"primary_font_variant" => '',
			"primary_font_subset" => '',
			"secondary_font_custom" => '',
			"secondary_font" => '',
			"secondary_font_variant" => '',
			"secondary_font_subset" => '',
			"send_email" => '',
			"send_email_client" => '',
			"save_calculation" => '',
			"calculation_status" => '',
			"google_recaptcha" => '',
			"recaptcha_site_key" => '',
			"recaptcha_secret_key" => '',
			"wpbakery_noconflict" => ''
		);
		$cost_calculator_global_form_options = cost_calculator_stripslashes_deep(array_merge($cost_calculator_global_form_options, (array)get_option("cost_calculator_global_form_options")));
		require_once("admin/admin-page-global-config.php");
	}
	
	function cost_calculator_admin_page_import_dummy_data()
	{
		require_once("admin/admin-page-import-dummy-data.php");
	}
	require_once("post-type-calculations.php");	
}
function cost_calculator_ajax_save_shortcode()
{	
	$content = (!empty($_POST["cost_calculator_content"]) ? stripslashes($_POST["cost_calculator_content"]) : "");
	$shortcode_id = (!empty($_POST["cost_calculator_shortcode_id"]) ? $_POST["cost_calculator_shortcode_id"] : "");
	
	if($shortcode_id!=="" && $content!=="")
	{
		$cost_calculator_shortcodes_list = get_option("cost_calculator_shortcodes_list");
		if($cost_calculator_shortcodes_list===false)
			$cost_calculator_shortcodes_list = array();
		$cost_calculator_shortcodes_list[$shortcode_id] = $content;
		ksort($cost_calculator_shortcodes_list);
		$advanced_settings = array(
			"calculator_skin" => $_POST["calculator_skin"],
			"main_color" => $_POST["main_color"],
			"box_color" => $_POST["box_color"],
			"text_color" => $_POST["text_color"],
			"border_color" => $_POST["border_color"],
			"label_color" => $_POST["label_color"],
			"dropdowncheckbox_label_color" => $_POST["dropdowncheckbox_label_color"],
			"form_label_color" => $_POST["form_label_color"],
			"inactive_color" => $_POST["inactive_color"],
			"tooltip_background_color" => $_POST["tooltip_background_color"],
			"primary_font_custom" => $_POST["primary_font_custom"],
			"primary_font" => $_POST["primary_font"],
			"primary_font_variant" => (isset($_POST["primary_font_variant"]) ? $_POST["primary_font_variant"] : ""),
			"primary_font_subset" => (isset($_POST["primary_font_subset"]) ? $_POST["primary_font_subset"] : ""),
			"secondary_font_custom" => $_POST["secondary_font_custom"],
			"secondary_font" => $_POST["secondary_font"],
			"secondary_font_variant" => (isset($_POST["secondary_font_variant"]) ? $_POST["secondary_font_variant"] : ""),
			"secondary_font_subset" => (isset($_POST["secondary_font_subset"]) ? $_POST["secondary_font_subset"] : ""),
			"border_color" => $_POST["border_color"],
			"form_display" => $_POST["form_display"],
			"form_action_url" => $_POST["form_action_url"],
			"thank_you_page_url" => $_POST["thank_you_page_url"]
		);
		
		if($advanced_settings!="")
			update_option("cost_calculator_advanced_settings_" . $shortcode_id, $advanced_settings);
		if(update_option("cost_calculator_shortcodes_list", $cost_calculator_shortcodes_list))
		{
			echo "calculator_start" . $shortcode_id . "calculator_end";
		}
		else
			echo 0;		
	}
	exit();
}
add_action('wp_ajax_cost_calculator_save_shortcode', 'cost_calculator_ajax_save_shortcode');

function cost_calculator_ajax_delete_shortcode()
{
	if(!empty($_POST["cost_calculator_shortcode_id"]))
	{
		$shortcode_id = $_POST["cost_calculator_shortcode_id"];
		$cost_calculator_shortcodes_list = get_option("cost_calculator_shortcodes_list");
		if($cost_calculator_shortcodes_list!==false && !empty($cost_calculator_shortcodes_list[$shortcode_id]))
		{
			unset($cost_calculator_shortcodes_list[$shortcode_id]);
			if(update_option("cost_calculator_shortcodes_list", $cost_calculator_shortcodes_list))
			{
				echo 1;
				exit();
			}
		}
	}
	echo 0;
	exit();
}
add_action('wp_ajax_cost_calculator_delete_shortcode', 'cost_calculator_ajax_delete_shortcode');

function cost_calculator_ajax_get_shortcode()
{
	if(!empty($_POST["cost_calculator_shortcode_id"]))
	{
		$shortcode_id = $_POST["cost_calculator_shortcode_id"];
		$cost_calculator_shortcodes_list = get_option("cost_calculator_shortcodes_list");
		if($cost_calculator_shortcodes_list!==false && !empty($cost_calculator_shortcodes_list[$shortcode_id]))
		{
			$result = array();
			$result["content"] = html_entity_decode($cost_calculator_shortcodes_list[$shortcode_id]);
			//get advanced settings
			$result["advanced_settings"] = cost_calculator_stripslashes_deep(get_option("cost_calculator_advanced_settings_" . $shortcode_id));
			echo "calculator_start" . json_encode($result) . "calculator_end";
			exit();
		}
	}
	echo 0;
	exit();
}
add_action('wp_ajax_cost_calculator_get_shortcode', 'cost_calculator_ajax_get_shortcode');

//add new mimes for upload dummy content files (code can be removed after dummy content import)
function cost_calculator_custom_upload_files($mimes) 
{
	$mimes = array_merge($mimes, array('xml' => 'application/xml'), array('json' => 'application/json'));
	return $mimes;
}
add_filter('upload_mimes', 'cost_calculator_custom_upload_files');

function cost_calculator_get_page_by_title($title, $post_type = "page")
{
	$posts = get_posts(array(
		'post_type' => $post_type,
		'title' => $title,
		'post_status' => 'all',
		'numberposts' => 1
	));
	if(!empty($posts))
	{
		return $posts[0];
	}
	else 
	{
		return null;
	}
}

function cost_calculator_download_import_file($file)
{	
	$url = "https://quanticalabs.com/wp_plugins/cost-calculator-for-wordpress/files/2018/03/" . $file["name"] . "." . $file["extension"];
	$attachment = cost_calculator_get_page_by_title($file["name"], "attachment");
	if($attachment!=null)
		$id = $attachment->ID;
	else
	{
		$tmp = download_url($url);
		$file_array = array(
			'name' => basename($url),
			'tmp_name' => $tmp
		);

		// Check for download errors
		if(is_wp_error($tmp)) 
		{
			@unlink($file_array['tmp_name']);
			return $tmp;
		}

		$id = media_handle_sideload($file_array, 0);
		// Check for handle sideload errors.
		if(is_wp_error($id))
		{
			@unlink($file_array['tmp_name']);
			return $id;
		}
	}
	return get_attached_file($id);
}

function cost_calculator_import_dummy()
{
	$result = array("info" => "");
	//import dummy content
	$fetch_attachments = true;
	$file = cost_calculator_download_import_file(array(
		"name" => "dummy-cost-calculator",
		"extension" => "xml"
	));
	if(is_wp_error($file))
	{
		$file = plugin_dir_path( __FILE__ ) . "dummy_content_files/dummy-cost-calculator.xml";
	}
	if(is_file($file))
		require_once 'importer/importer.php';
	else
	{
		$result["info"] .= __("Import file: dummy-cost-calculator.xml not found! Please upload import file manually into Media library. You can find this file inside zip archive downloaded from CodeCanyon.", 'cost-calculator');
		exit();
	}
	//insert shortcodes from live preview
	$cost_calculator_shortcodes_live_preview = array(
		"carservice" => '[vc_row row-layout="columns_3_1-3_1-3_1-3" top_margin="page-margin-top" el_class=""][vc_column width="1/3"][cost_calculator_slider_box id="vehicle-year" name="vehicle-year" label="VEHICLE YEAR" default_value="2008" unit_value="1" step="1" min="1990" max="2018" top_margin="none" el_class=""][/vc_column][vc_column width="1/3"][cost_calculator_dropdown_box id="vehicle-make" name="vehicle-make" label="VEHICLE MAKE" default_value="" options_count="15" option_name0="General Motors" option_value0="General Motors" option_name1="Land Rover" option_value1="Land Rover" option_name2="Lexus" option_value2="Lexus" option_name3="Lincoln" option_value3="Lincoln" option_name4="Mazda" option_value4="Mazda" option_name5="Mercedes - Benz" option_value5="Mercedes - Benz" option_name6="Mercury" option_value6="Mercury" option_name7="Mitsubishi" option_value7="Mitsubishi" option_name8="Nissan" option_value8="Nissan" option_name9="Renault" option_value9="Renault" option_name10="Plymouth" option_value10="Plymouth" option_name11="Pontiac Porsche" option_value11="Pontiac Porsche" option_name12="Rover" option_value12="Rover" option_name13="Saab" option_value13="Saab" option_name14="Saleen" option_value14="Saleen" show_choose_label="1" choose_label="Choose..." top_margin="none"][/vc_column][vc_column width="1/3"][cost_calculator_input_box id="vehicle-mileage" name="vehicle-mileage" label="VEHICLE MILEAGE" default_value="" type="number" checked="1" checkbox_type="button" placeholder="Vehicle Mileage" top_margin="none" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_2_1-2_1-2" top_margin="page-margin-top" el_class=""][vc_column width="1/2"][cost_calculator_input_box id="appointment-date" name="appointment-date" label="APPOINTMENT DATE" default_value="" type="date" checked="1" checkbox_type="button" placeholder="Preffered Date of Appointment" top_margin="none" el_class=""]
[cost_calculator_dropdown_box id="time-frame" name="time-frame" label="PREFFERED TIME FRAME" default_value="" options_count="9" option_name0="09:00 AM - 10:00 AM" option_value0="09:00 AM - 10:00 AM" option_name1="10:00 AM - 11:00 AM" option_value1="10:00 AM - 11:00 AM" option_name2="11:00 AM - 12:00 PM" option_value2="11:00 AM - 12:00 PM" option_name3="12:00 PM - 01:00 PM" option_value3="12:00 PM - 01:00 PM" option_name4="01:00 PM - 02:00 PM" option_value4="01:00 PM - 02:00 PM" option_name5="02:00 PM - 03:00 PM" option_value5="02:00 PM - 03:00 PM" option_name6="03:00 PM - 04:00 PM" option_value6="03:00 PM - 04:00 PM" option_name7="04:00 PM - 05:00 PM" option_value7="04:00 PM - 05:00 PM" option_name8="05:00 PM - 06:00 PM" option_value8="05:00 PM - 06:00 PM" show_choose_label="1" choose_label="Choose..." top_margin="page-margin-top"]<div class="page-margin-top margin-bottom-20 cost-calculator-box"><label>SELECT SERVICES NEEDED</label></div>[cost_calculator_input_box id="air-conditioning" name="air-conditioning" label="Air Conditioning" default_value="1" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="brakes-repair" name="brakes-repair" label="Brakes Repair" default_value="1" type="checkbox" checked="1" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""][cost_calculator_input_box id="engine-diagnostics" name="engine-diagnostics" label="Engine Diagnostics" default_value="1" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="heating-cooling" name="heating-cooling" label="Heating&amp;Cooling" default_value="1" type="checkbox" checked="1" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="oil-lube-filters" name="oil-lube-filters" label="Oil, Lube &amp; Filters" default_value="1" type="checkbox" checked="1" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="steering-suspension" name="steering-suspension" label="Steering&amp;Suspension" default_value="1" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="transmission-repair" name="transmission-repair" label="Transmission Repair" default_value="1" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""][cost_calculator_input_box id="wheel-alignment" name="wheel-alignment" label="Wheel Alignment" default_value="1" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""][/vc_column][vc_column width="1/2"][cost_calculator_contact_box label="CONTACT DETAILS" submit_label="SUBMIT NOW" name_label="Your Name *" name_placeholder="Your Name *" name_required="1" email_label="Your Email *" email_placeholder="Your Email *" email_required="1" phone_label="Your Phone" phone_placeholder="Your Phone" phone_required="0" message_label="Additional Questions or Comments" message_placeholder="Additional Questions or Comments" message_required="0" type="" labels_style="placeholder" description="" el_class=""][/vc_column][/vc_row]',
		"cleanmate" => '[vc_row row-layout="columns_2_1-2_1-2" top_margin="page-margin-top" el_class=""][vc_column width="1/2"][cost_calculator_slider_box id="clean-area" name="clean-area" label="Total area to be cleaned in square feet:" default_value="1200" unit_value="1" step="1" min="1" max="2000" currency_after="&space;ft²" top_margin="none" el_class=""]
[cost_calculator_slider_box id="bathrooms" name="bathrooms" label="Number of bathrooms:" default_value="2" unit_value="1" step="1" min="0" max="5" top_margin="none" el_class="margin-top-30"][/vc_column][vc_column width="1/2"][cost_calculator_slider_box id="bedrooms" name="bedrooms" label="Number of bedrooms:" default_value="4" unit_value="1" step="1" min="0" max="8" top_margin="none" el_class=""]
[cost_calculator_slider_box id="livingrooms" name="livingrooms" label="Number of living rooms:" default_value="1" unit_value="1" step="1" min="0" max="3" top_margin="none" el_class="margin-top-30"][/vc_column][/vc_row][vc_row row-layout="columns_3_1-3_1-3_1-3" top_margin="none" el_class="margin-top-30"][vc_column width="1/3"][cost_calculator_dropdown_box id="kitchen-size" name="kitchen-size" label="Size of your kitchen:" default_value="" options_count="3" option_name0="Small (0 - 150 ft2)" option_value0="15" option_name1="Medium (151 - 250 ft2)" option_value1="20" option_name2="Large (&gt;250 ft2)" option_value2="25" show_choose_label="1" choose_label="Choose..." top_margin="none"][/vc_column][vc_column width="1/3"][cost_calculator_dropdown_box id="bathroom-includes" name="bathroom-includes" label="Master bathroom includes:" default_value="" options_count="4" option_name0="Shower only" option_value0="10" option_name1="Tub only" option_value1="13" option_name2="Separete shower and tub" option_value2="15" option_name3="No appliances" option_value3="0" show_choose_label="1" choose_label="Choose..." top_margin="none"][/vc_column][vc_column width="1/3"][cost_calculator_switch_box id="pets" name="pets" label="Do you have pets?" default_value="30" checked="0" top_margin="none" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="page-margin-top" el_class=""][vc_column width="1/1"]<h3 class="cost-calculator-align-center no-border">Select your service &amp; extras</h3>[/vc_column][/vc_row][vc_row row-layout="columns_2_1-2_1-2" top_margin="none" el_class="margin-top-40"][vc_column width="1/2"][cost_calculator_dropdown_box id="cleaning-supplies" name="cleaning-supplies" label="Choose your cleaning supplies:" default_value="500" options_count="3" option_name0="Green cleaning" option_value0="500" option_name1="Company\'s supplies" option_value1="300" option_name2="Client\'s supplies" option_value2="0" show_choose_label="1" choose_label="Choose..." top_margin="none"][/vc_column][vc_column width="1/2"][cost_calculator_dropdown_box id="cleaning-frequency" name="cleaning-frequency" label="Cleaning frequency:" default_value="0.2" options_count="6" option_name0="Weekly Service" option_value0="0.4" option_name1="Bi-Weekly Service" option_value1="0.8" option_name2="Tri-Weekly Service" option_value2="1.2" option_name3="Quarter Weekly Service" option_value3="1.6" option_name4="Monthly Service" option_value4="0.1" option_name5="One Time Service" option_value5="0.2" show_choose_label="1" choose_label="Choose..." top_margin="none"][/vc_column][/vc_row][vc_row row-layout="columns_2_2-3_1-3" top_margin="none" el_class="margin-top-30"][vc_column width="2/3"]<div class="cost-calculator-box cost-calculator-clearfix"><div class="margin-bottom-6"><label>Additional rooms you would like us to clean:</label></div>[cost_calculator_input_box id="dining-room" name="dining-room" label="Dining room" default_value="10" type="checkbox" checked="0" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="play-room" name="play-room" label="Play room" default_value="15" type="checkbox" checked="0" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="laundry" name="laundry" label="Laundry" default_value="14" type="checkbox" checked="0" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="gym" name="gym" label="Gym" default_value="17" type="checkbox" checked="0" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="garage" name="garage" label="Garage" default_value="20" type="checkbox" checked="0" placeholder="" top_margin="none" el_class=""]</div>[/vc_column][vc_column width="1/3"]<div class="cost-calculator-box cost-calculator-clearfix"><div class="margin-bottom-6"><label>Clean inside the refrigerator?</label></div>[cost_calculator_input_box id="refrigerator-clean1" name="refrigerator-clean" label="No" hide_label="0" group_label="Clean inside the refrigerator?" default_value="0" type="radio" checked="1"]
[cost_calculator_input_box id="refrigerator-clean2" name="refrigerator-clean" label="Yes" hide_label="0" group_label="Clean inside the refrigerator?" default_value="20" type="radio" checked="0"]</div>[/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="page-margin-top" el_class="cost-calculator-align-center"][vc_column width="1/1"]<h3>Final cost</h3>[cost_calculator_summary_box id="cost" name="total_cost" formula="cleaning-frequency*clean-area+cleaning-frequency*cleaning-supplies+cleaning-frequency*bedrooms*20+cleaning-frequency*bathrooms*20+cleaning-frequency*livingrooms*30+cleaning-frequency*kitchen-size+cleaning-frequency*bathroom-includes+cleaning-frequency*pets+cleaning-frequency*dining-room+cleaning-frequency*play-room+cleaning-frequency*laundry+cleaning-frequency*gym+cleaning-frequency*garage+cleaning-frequency*refrigerator-clean" currency="$" currency_after="" currency_size="default" thousandth_separator="," decimal_separator="." description="" icon="" el_class="margin-top-15 cost-calculator-after-border cost-calculator-transparent"]<p class="page-margin-top">Enter your contact details. We will give you a call to finish up.</p>[/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="none" el_class="margin-top-10"][vc_column width="1/1"][cost_calculator_contact_box submit_label="Submit now" name_label="YOUR NAME" name_required="1" email_label="YOUR EMAIL" email_required="1" phone_label="YOUR PHONE" phone_required="0" message_label="QUESTIONS OR COMMENTS" message_required="0" labels_style="default" type=""terms_checkbox="1" terms_message="UGxlYXNlIGFjY2VwdCA8YSBocmVmPScjJz50ZXJtcyBhbmQgY29uZGl0aW9uczwvYT4=" description="" el_class="cost-calculator-gray"][/vc_column][/vc_row]',
		"renovate" => '[vc_row row-layout="columns_1_1-1" top_margin="page-margin-top" el_class=""][vc_column width="1/1"][cost_calculator_slider_box id="ir-square-feet" name="square-feet" label="Area to be Renovated in Square Feet:" default_value="300" unit_value="2" step="10" min="10" max="3000" currency_after="&space;ft²" top_margin="none" el_class=""]
[cost_calculator_dropdown_box id="ir-walls" name="walls" label="Walls &amp; Ceilings:" default_value="" options_count="6" option_name0="Painting" option_value0="2" option_name1="Painting + Minor Repairs" option_value1="2.3" option_name2="Painting + Decorative Stone" option_value2="2.5" option_name3="Tiling" option_value3="3" option_name4="Painting + Tiling" option_value4="5" option_name5="Hanging Lining Paper" option_value5="2" show_choose_label="1" choose_label="Choose..." top_margin="none"]
[cost_calculator_dropdown_box id="ir-floors" name="floors" label="Floors:" default_value="" options_count="6" option_name0="Hardwood Flooring" option_value0="1.5" option_name1="Bamboo Flooring" option_value1="2.5" option_name2="Vinyl Tile Flooring" option_value2="2.6" option_name3="Parquet Flooring" option_value3="3.25" option_name4="Wall-to-wall Carpet" option_value4="3.5" option_name5="Ceramic Tile Flooring" option_value5="12" show_choose_label="1" choose_label="Choose..." top_margin="none"]
[cost_calculator_slider_box id="ir-doors" name="doors" label="Interior Doors to Replace:" default_value="6" unit_value="250" step="1" min="0" max="10" top_margin="none" el_class=""]
[cost_calculator_slider_box id="ir-windows" name="windows" label="Windows to Replace:" default_value="4" unit_value="200" step="1" min="0" max="10" top_margin="none" el_class=""]
[cost_calculator_input_box id="ir-cleaning" name="ir-cleaning" label="After Renovation Cleaning" default_value="50" type="checkbox" checked="1" checkbox_type="default" placeholder="" top_margin="none" el_class=""]
[cost_calculator_summary_box id="interior-renovation-cost" name="total_cost" formula="ir-square-feet-value*ir-walls+ir-square-feet*ir-floors+ir-doors-value+ir-windows-value+ir-cleaning" currency="£" currency_after="" currency_size="default" thousandth_separator="," decimal_separator="." description="Approximate Project Cost" icon="cc-template-wallet"]
[cost_calculator_contact_box label="Contact Details" submit_label="SUBMIT NOW" name_label="Your Name *" name_label="Your Name *" name_required="1" email_label="Your Email *" email_placeholder="Your Email *" email_required="1" phone_label="Your Phone" phone_placeholder="Your Phone" phone_required="0" message_label="Message *" message_placeholder="Message *" message_required="1" type="Interior Renovation" labels_style="placeholder" description="We will contact you within one business day." el_class="cost-calculator-box margin-top-10"][/vc_column][/vc_row]',
		"lpg-calculator" => '[vc_row row-layout="columns_2_1-2_1-2" top_margin="page-margin-top" el_class=""][vc_column width="1/2"][cost_calculator_slider_box id="annual-distance" name="annual-distance" label="Annual distance travelled" default_value="10000" unit_value="1" step="500" min="1000" max="100000" thousands_separator="," input_field="1" top_margin="none" el_class=""][/vc_column][vc_column width="1/2"][cost_calculator_slider_box id="gasoline-consumption" name="gasoline-consumption" label="Gasoline consumption" default_value="6" unit_value="1" step="1" min="3" max="30" input_field="1" top_margin="none" el_class=""][/vc_column][/vc_row]
[vc_row row-layout="columns_2_1-2_1-2" top_margin="none" el_class="margin-top-30"][vc_column width="1/2"][cost_calculator_slider_box id="gasoline-price" name="gasoline-price" label="Gasoline price (per liter)" default_value="0.86" unit_value="1" step="0.01" min="0.5" max="1.5" currency="$" input_field="1" top_margin="none" el_class=""][/vc_column][vc_column width="1/2"][cost_calculator_slider_box id="lpg-price" name="lpg-price" label="LPG price (per liter)" default_value="0.53" unit_value="1" step="0.01" min="0.3" max="1.5" currency="$" input_field="1" top_margin="none" el_class=""][/vc_column][/vc_row]
[vc_row row-layout="columns_1_1-1" top_margin="none" el_class="margin-top-30"][vc_column width="1/1"][cost_calculator_dropdown_box id="fuel-consumption-increase" name="fuel-consumption-increase" label="Estimated fuel consumption increase with LPG installation" default_value="20" options_count="5" option_name0="10%" option_value0="10" option_name1="15%" option_value1="15" option_name2="20%" option_value2="20" option_name3="25%" option_value3="25" option_name4="30%" option_value4="30" show_choose_label="0" choose_label="Choose..." top_margin="none"][/vc_column][/vc_row]
[vc_row row-layout="columns_3_1-3_1-3_1-3" top_margin="none" el_class="margin-top-30"][vc_column width="1/3"][cost_calculator_summary_box id="gasoline-cost" name="gasoline-cost" formula="annual-distance/100*gasoline-consumption*gasoline-price" currency="$" currency_after="" currency_size="default" thousandth_separator="," decimal_separator="." description="Annual gasoline cost" icon="" el_class="cost-calculator-align-center"][/vc_column][vc_column width="1/3"][cost_calculator_summary_box id="lpg-cost" name="lpg-cost" formula="annual-distance/100*gasoline-consumption*(100+fuel-consumption-increase)/100*lpg-price" currency="$" currency_after="" currency_size="default" thousandth_separator="," decimal_separator="." description="Annual LPG cost" icon="" el_class="cost-calculator-align-center"][/vc_column][vc_column width="1/3"][cost_calculator_summary_box id="fuel-savings" name="fuel-savings" formula="gasoline-cost-total-value{-}lpg-cost-total-value" currency="$" currency_after="" currency_size="default" thousandth_separator="," decimal_separator="." description="Annual savings" icon="" el_class="cost-calculator-align-center"][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="none" el_class="margin-top-30"][vc_column width="1/1"][cost_calculator_summary_box id="fuel-percentage-savings" name="fuel-percentage-savings" formula="(gasoline-cost-total-value{-}lpg-cost-total-value)/gasoline-cost-total-value*100" currency="" currency_after="%" currency_size="default" thousandth_separator="," decimal_separator="." description="Annual savings %" icon="" el_class="cost-calculator-align-center"][/vc_column][/vc_row]',
		"mortgage" => '[vc_row row-layout="columns_1_1-1" top_margin="page-margin-top" el_class=""][vc_column width="1/1"][cost_calculator_input_box id="loan-amount" name="loan-amount" label="Loan Amount" default_value="5000" type="text" checked="1" checkbox_type="type-button" checkbox_yes="checked" checkbox_no="not checked" placeholder="" top_margin="none" el_class=""]
[cost_calculator_slider_box id="interest-rate" name="interest-rate" label="Annual interest rate (%)" default_value="6" unit_value="1" step="0.1" min="0.1" max="20" currency_after="%" input_field="1" top_margin="none" el_class=""]
[cost_calculator_slider_box id="loan-months" name="loan-months" label="Months" default_value="6" unit_value="1" step="1" min="1" max="36" input_field="1" top_margin="none" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="none" el_class="margin-top-20"][vc_column width="1/1"][cost_calculator_summary_box id="monthly-payment" name="monthly-payment" formula="loan-amount*{powerstart}1+interest-rate/100/12^loan-months{powerend}*(1+interest-rate/100/12{-}1)/({powerstart}1+interest-rate/100/12^loan-months{powerend}{-}1)" currency="£" currency_after="" currency_size="default" thousandth_separator="," decimal_separator="." description="Monthly Payment" icon="" el_class="cost-calculator-align-center"][/vc_column][/vc_row][vc_row row-layout="columns_2_1-2_1-2" top_margin="none" el_class="cost-calculator-columns-no-margin margin-top-20"][vc_column width="1/2"][cost_calculator_summary_box id="repayment-amount" name="repayment-amount" formula="monthly-payment-total-value*loan-months" currency="£" currency_after="" currency_size="default" thousandth_separator="," decimal_separator="." description="Total Repayment Amount " icon="cc-template-card" el_class="margin-top-30"][/vc_column][vc_column width="1/2"][cost_calculator_summary_box id="total-interest" name="total-interest" formula="monthly-payment-total-value*loan-months{-}loan-amount" currency="£" currency_after="" currency_size="default" thousandth_separator="," decimal_separator="." description="Total Interest" icon="cc-template-calculation"][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="none" el_class="margin-top-30"][vc_column width="1/1"][cost_calculator_contact_box label="Contact Details" submit_label="SUBMIT NOW" name_label="Your Name *" name_placeholder="Your Name *" name_required="1" email_label="Your Email *" email_placeholder="Your Email *"  email_required="1" phone_label="Your Phone" phone_placeholder="Your Phone" phone_required="0" message_label="Message" message_placeholder="Message" message_required="0" type="" labels_style="placeholder" terms_checkbox="1" terms_message="UGxlYXNlIGFjY2VwdCA8YSBocmVmPScjJz50ZXJtcyBhbmQgY29uZGl0aW9ucw==" description="" el_class="cost-calculator-box"][/vc_column][/vc_row]',
		"bmi" => '[vc_row row-layout="columns_1_1-1" top_margin="page-margin-top" el_class=""][vc_column width="1/1"][cost_calculator_slider_box id="user-height" name="user-height" label="Your Height (cm)" default_value="170" unit_value="1" step="1" min="40" max="225" currency_after="&space;cm" input_field="1" top_margin="none" el_class=""]
[cost_calculator_slider_box id="user-weight" name="user-weight" label="Your Weight (kg)" default_value="70" unit_value="1" step="1" min="1" max="150" currency_after="&space;kg" input_field="1" top_margin="none" el_class="margin-top-30"][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="none" el_class="cost-calculator-align-center margin-top-30"][vc_column width="1/1"][cost_calculator_summary_box id="bmi-index" name="bmi-index" formula="user-weight/{powerstart}user-height/100^2{powerend}" currency="" currency_after="" currency_size="default" thousandth_separator="," decimal_separator="." description="Body Mass Index (BMI)" icon="cc-template-calculation" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="page-margin-top" el_class=""][vc_column width="1/1"]<h3 class="cost-calculator-align-center no-border">Your proper weight range</h3>[/vc_column][/vc_row][vc_row row-layout="columns_2_1-2_1-2" top_margin="page-margin-top" el_class=""][vc_column width="1/2"][cost_calculator_summary_box id="ideal-kg-from" name="ideal-kg-from" formula="18.5*{powerstart}user-height/100^2{powerend}" currency_after=" kg" currency_size="default" thousandth_separator="," decimal_separator="." description="Your Minumum Proper Weight" icon="" el_class="cost-calculator-align-center"][/vc_column][vc_column width="1/2"][cost_calculator_summary_box id="ideal-kg-to" name="ideal-kg-to" formula="25*{powerstart}user-height/100^2{powerend}" currency="" currency_after=" kg" currency_size="default" thousandth_separator="," decimal_separator="." description="Your Maximum Proper Weight" icon="" el_class="cost-calculator-align-center"][/vc_column][/vc_row]',
		"hosting" => '[vc_row row-layout="columns_3_1-3_1-3_1-3" top_margin="page-margin-top" el_class=""][vc_column width="1/3"][cost_calculator_dropdown_box id="operating-system" name="operating-system" label="Operating System" default_value="" options_count="3" option_name0="Unix Free" option_value0="0" option_name1="Unix Premium" option_value1="10" option_name2="Windows" option_value2="20" show_choose_label="1" choose_label="Choose..." top_margin="none"][/vc_column][vc_column width="1/3"][cost_calculator_dropdown_box id="database-type" name="database-type" label="Database" default_value="" options_count="4" option_name0="MySQL" option_value0="2" option_name1="Oracle" option_value1="3" option_name2="MSSQL" option_value2="3" option_name3="MariaDB" option_value3="2" show_choose_label="1" choose_label="No database" top_margin="none"][/vc_column][vc_column width="1/3"][cost_calculator_switch_box id="automatic-backup" name="automatic-backup" label="Automatic Backup" yes_text="Yes" no_text="No" default_value="2" checked="1" top_margin="none" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_2_1-2_1-2" top_margin="none" el_class="margin-top-30"][vc_column width="1/2"]<div class="cost-calculator-box cost-calculator-clearfix"><div class="margin-bottom-6"><label>CPU Number</label></div>[cost_calculator_input_box id="cpu-number1" name="cpu-number" label="1 Cpu" hide_label="0" group_label="CPU Number" default_value="1" type="radio" checked="0"]
[cost_calculator_input_box id="cpu-number2" name="cpu-number" label="2 Cpu" hide_label="0" group_label="CPU Number" default_value="2" type="radio" checked="0"]
[cost_calculator_input_box id="cpu-number3" name="cpu-number" label="3 Cpu" hide_label="0" group_label="CPU Number" default_value="3" type="radio" checked="0"]
[cost_calculator_input_box id="cpu-number4 name="cpu-number" label="4 Cpu" hide_label="0" group_label="CPU Number" default_value="4" type="radio" checked="0"]</div>[/vc_column][vc_column width="1/2"][cost_calculator_slider_box id="core-number" name="core-number" label="CPU Cores" default_value="4" unit_value="1" step="2" min="2" max="16" input_field="1" top_margin="none" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="page-margin-top" el_class=""][vc_column width="1/1"]<h3 class="cost-calculator-align-center no-border">Specify server resources</h3>[/vc_column][/vc_row][vc_row row-layout="columns_2_1-2_1-2" top_margin="page-margin-top" el_class=""][vc_column width="1/2"][cost_calculator_dropdown_box id="memory-amount" name="memory-amount" label="RAM Memory (GB)" default_value="" options_count="9" option_name0="1GB" option_value0="1" option_name1="2GB" option_value1="2" option_name2="3GB" option_value2="3" option_name3="4GB" option_value3="4" option_name4="5GB" option_value4="5" option_name5="6GB" option_value5="6" option_name6="8GB" option_value6="8" option_name7="12GB" option_value7="12" option_name8="16GB" option_value8="16" show_choose_label="1" choose_label="Choose..." top_margin="none"][/vc_column][vc_column width="1/2"][cost_calculator_slider_box id="bandwidth-value" name="bandwidth-value" label="Bandwidth (TB)" default_value="2" unit_value="1" step="0.5" min="0.5" max="5" currency_after="&space;TB" input_field="1" top_margin="none" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_2_1-2_1-2" top_margin="none" el_class="margin-top-30"][vc_column width="1/2"][cost_calculator_slider_box id="storage-hdd" name="storage-hdd" label="Storage HDD (GB)" default_value="250" unit_value="1" step="250" min="0" max="1000" currency_after="&space;GB" input_field="0" top_margin="none" el_class=""][/vc_column][vc_column width="1/2"][cost_calculator_slider_box id="storage-ssd" name="storage-ssd" label="Storage SSD (GB)" default_value="50" unit_value="1" step="25" min="0" max="250" input_field="0" currency_after="&space;GB" top_margin="none" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="page-margin-top" el_class=""][vc_column width="1/1"]<h3 class="cost-calculator-align-center no-border">Select extra services</h3>[/vc_column][/vc_row][vc_row row-layout="columns_2_3-4_1-4" top_margin="page-margin-top" el_class=""][vc_column width="3/4"]<div class="cost-calculator-box cost-calculator-clearfix"><div class="margin-bottom-6"><label>Applications</label></div>[cost_calculator_input_box id="magento" name="magento" label="Magento" default_value="1" type="checkbox" checked="0" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="jigoshop" name="jigoshop" label="Jigoshop" default_value="1" type="checkbox" checked="0" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="prestashop" name="prestashop" label="Prestashop" default_value="1" type="checkbox" checked="0" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="shopify" name="shopify" label="Shopify" default_value="1" type="checkbox" checked="0" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="zencart" name="zencart" label="Zen Cart" default_value="1" type="checkbox" checked="0" placeholder="" top_margin="none" el_class=""]</div>[/vc_column][vc_column width="1/4"][cost_calculator_switch_box id="ssh-access" name="ssh-access" label="SSH Access" yes_text="Yes" no_text="No" default_value="3" checked="0" top_margin="none" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_2_2-3_1-3" top_margin="none" el_class="margin-top-30"][vc_column width="2/3"]<div class="cost-calculator-box cost-calculator-clearfix"><div class="margin-bottom-6"><label>CMS</label></div>[cost_calculator_input_box id="wordpress" name="wordpress" label="WordPress" default_value="1" type="checkbox" checked="0" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="joomla" name="joomla" label="Joomla" default_value="1" type="checkbox" checked="0" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="drupal" name="drupal" label="Drupal" default_value="1" type="checkbox" checked="0" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="concrete" name="concrete" label="Concrete" default_value="1" type="checkbox" checked="0" placeholder="" top_margin="none" el_class=""]</div>[/vc_column][vc_column width="1/3"][cost_calculator_dropdown_box id="ssl-certificate" name="ssl-certificate" label="SSL Certificate" default_value="" options_count="3" option_name0="Standard SSL" option_value0="5" option_name1="Wildcard SSL" option_value1="8" option_name2="Multidomain SSL" option_value2="10" show_choose_label="1" choose_label="No SSL" top_margin="none"][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="page-margin-top" el_class="cost-calculator-align-center"][vc_column width="1/1"]<h3>Monthly cost</h3>[cost_calculator_summary_box id="hosting-cost" name="total_cost" formula="operating-system+cpu-number*core-number+memory-amount*2+bandwidth-value+storage-hdd/100+storage-ssd/5+magento+jigoshop+prestashop+shopify+zencart+wordpress+joomla+drupal+concrete+automatic-backup+database-type+ssh-access+ssl-certificate" currency="$" currency_after="" currency_size="default" thousandth_separator="," decimal_separator="." description="" icon="" el_class="margin-top-15 cost-calculator-after-border cost-calculator-transparent"]<p class="page-margin-top">Enter your contact details. We will give you a call to finish up.</p>[/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="none" el_class="margin-top-10"][vc_column width="1/1"][cost_calculator_contact_box submit_label="Submit now" name_label="YOUR NAME" name_required="1" email_label="YOUR EMAIL" email_required="1" phone_label="YOUR PHONE" phone_required="0" message_label="QUESTIONS OR COMMENTS" message_required="0" labels_style="default" type="" terms_checkbox="1" terms_message="UGxlYXNlIGFjY2VwdCA8YSBocmVmPScjJz50ZXJtcyBhbmQgY29uZGl0aW9uczwvYT4=" description="" el_class="cost-calculator-gray"][/vc_column][/vc_row]',
		"web-design" => '[vc_row row-layout="columns_2_1-2_1-2" top_margin="page-margin-top" el_class=""][vc_column width="1/2"][cost_calculator_dropdown_box id="website-type" name="dropdown-box" label="WEBSITE TYPE" default_value="" options_count="3" option_name0="Static HTML" option_value0="300" option_name1="CMS (WordPress. Joomla)" option_value1="800" option_name2="eCommerce Website" option_value2="1000" show_choose_label="1" choose_label="Choose..." top_margin="none"][/vc_column][vc_column width="1/2"][cost_calculator_dropdown_box id="design-complexity" name="design-complexity" label="DESIGN COMPLEXITY" default_value="" options_count="6" option_name0="Standard" option_value0="10" option_name1="Advanced" option_value1="12" option_name2="Deluxe" option_value2="15" option_name3="Corporate" option_value3="14" option_name4="Enterprise" option_value4="20" option_name5="Custom design provided by client" option_value5="20" show_choose_label="1" choose_label="Choose..." top_margin="none"][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="page-margin-top" el_class=""][vc_column width="1/1"][cost_calculator_slider_box id="mockups-amount" name="mockups-amount" label="NUMBER OF MOCKUPS YOU WANT TO SEE" default_value="2" unit_value="50" step="1" min="1" max="5" input_field="1" top_margin="none" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="page-margin-top" el_class=""][vc_column width="1/1"][cost_calculator_slider_box id="pages-number" name="pages-number" label="NUMBER OF PAGES" default_value="5" unit_value="1" step="1" min="1" max="50" input_field="1" top_margin="none" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_2_1-2_1-2" top_margin="page-margin-top" el_class=""][vc_column width="1/2"][cost_calculator_dropdown_box id="seo-type" name="seo-type" label="SEO TYPE" default_value="" options_count="2" option_name0="Basic SEO" option_value0="50" option_name1="Advanced SEO" option_value1="200" show_choose_label="1" choose_label="No SEO" top_margin=""][/vc_column][vc_column width="1/2"]<div class="cost-calculator-box cost-calculator-clearfix"><div class="margin-bottom-21"><label>LAYOUT</label></div>[cost_calculator_input_box id="layout1" name="layout" label="Responsive" hide_label="0" group_label="LAYOUT" default_value="50" type="radio" checked="1"]
[cost_calculator_input_box id="layout2" name="layout" label="Liquid" hide_label="0" group_label="LAYOUT" default_value="35" type="radio" checked="0"]
[cost_calculator_input_box id="layout3" name="layout" label="Fixed" hide_label="0" group_label="LAYOUT" default_value="0" type="radio" checked="0"]</div>[/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="page-margin-top" el_class=""][vc_column width="1/1"]<div class="margin-bottom-20 cost-calculator-box"><label>ADDONS</label></div>[cost_calculator_input_box id="custom-logo-design" name="custom-logo-design" label="Custom Logo Design" default_value="100" type="checkbox" checked="1" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="image-slider" name="image-slider" label="Image Slider" default_value="50" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""][cost_calculator_input_box id="contact-form-design" name="contact-form-design" label="Contact Form" default_value="35" type="checkbox" checked="1" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="event-calendar" name="event-calendar" label="Event Calendar" default_value="50" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="pricing-table" name="pricing-table" label="Pricing Table" default_value="30" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="image-gallery" name="image-gallery" label="Image Gallery" default_value="40" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""]
[cost_calculator_input_box id="twitter-feed" name="twitter-feed" label="Twitter Feed" default_value="30" type="checkbox" checked="1" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""][cost_calculator_input_box id="newsletter" name="newsletter" label="Newsletter" default_value="45" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""][cost_calculator_input_box id="live-chat" name="live-chat" label="Live Chat" default_value="70" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""][cost_calculator_input_box id="google-map" name="google-map" label="Google Map" default_value="10" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""][cost_calculator_input_box id="accordion-faq" name="accordion-faq" label="Accordion FAQ" default_value="25" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""][cost_calculator_input_box id="classifieds" name="classifieds" label="Classifieds" default_value="250" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""][cost_calculator_input_box id="booking" name="booking" label="Booking System" default_value="200" type="checkbox" checked="0" checkbox_type="type-button" placeholder="" top_margin="none" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="page-margin-top" el_class=""][vc_column width="1/1"]<div class="margin-bottom-20 cost-calculator-box"><label>APPROXIMATE COST</label></div>[cost_calculator_summary_box id="cost" name="total_cost" formula="website-type+mockups-amount-value+layout+design-complexity*pages-number+seo-type+custom-logo-design+image-slider+contact-form-design+event-calendar+pricing-table+image-gallery+twitter-feed+newsletter+live-chat+google-map+accordion-faq+classifieds+booking" currency="$" currency_after="" currency_size="default" thousandth_separator="," decimal_separator="." description="" icon="cc-template-wallet" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="page-margin-top" el_class=""][vc_column width="1/1"][cost_calculator_contact_box label="CONTACT DETAILS" submit_label="SUBMIT NOW" name_label="Your Name *" name_placeholder="Your Name *" name_required="1" email_label="Your Email *" email_placeholder="Your Email *" email_required="1" phone_label="Your Phone" phone_placeholder="Your Phone" phone_required="0" message_label="Additional Questions or Comments" message_placeholder="Additional Questions or Comments" message_required="0" type="" labels_style="placeholder" terms_checkbox="0" terms_message="" description="" el_class=""][/vc_column][/vc_row]',
		"bookkeeping-calculator" => '[vc_row row-layout="columns_2_1-2_1-2" top_margin="page-margin-top" el_class=""][vc_column width="1/2"][cost_calculator_slider_box id="sales-per-month" name="sales-per-month" label="No. of sales invoices per month" hide_label="0" default_value="430" currency="" currency_after="" thousandth_separator="" unit_value="0.1" step="5" min="5" max="1000" minmax_label="1" input_field="0" top_margin="none" el_class=""][/vc_column][vc_column width="1/2"][cost_calculator_slider_box id="purchase-per-month" name="purchase-per-month" label="No. of purchase invoices per month" hide_label="0" default_value="360" unit_value="0.1" step="5" min="5" max="1000" minmax_label="1" input_field="0"][/vc_column][/vc_row][vc_row row-layout="columns_3_1-3_1-3_1-3" top_margin="none" el_class="margin-top-30"][vc_column width="1/3"][cost_calculator_dropdown_box id="business-type" name="business-type" label="Select type of business" hide_label="0" options_count="4" option_name0="Sole Trader" option_value0="1" option_name1="Partnership" option_value1="2" option_name2="Limited Liability Company" option_value2="3" option_name3="Corporation" option_value3="5" default_value="1" show_choose_label="0"][/vc_column][vc_column width="1/3"][cost_calculator_dropdown_box id="employees-number" name="employees-number" label="Select number of employees" hide_label="0" options_count="11" option_name0="No employees" option_value0="0" option_name1="1 employee" option_value1="1" option_name2="2 employees" option_value2="2" option_name3="3 employees" option_value3="3" option_name4="4 employees" option_value4="4" option_name5="5 employees" option_value5="5" option_name6="6 employees" option_value6="6" option_name7="7 employees" option_value7="7" option_name8="8 employees" option_value8="8" option_name9="9 employees" option_value9="9" option_name10="10 employees" option_value10="10" show_choose_label="1" required="0"][/vc_column][vc_column width="1/3"][cost_calculator_switch_box id="foreign-currency" name="foreign-currency" label="Are you handle foreign currency?" hide_label="0" default_value="2" checked="0"][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="none" el_class="margin-top-30"][vc_column width="1/1"]<div class="cost-calculator-box cost-calculator-clearfix"><div class="margin-bottom-9"><label>Add-on services</label></div>[cost_calculator_input_box id="annual-tax" name="annual-tax" type="checkbox" label="Annual tax return filling" default_value="50" checked="0" required="0"]
[cost_calculator_input_box id="invoicing-customers" name="invoicing-customers" type="checkbox" label="Invoicing for customers" default_value="1" checked="0" required="0"]
[cost_calculator_input_box id="tax-advisor" name="tax-advisor" type="checkbox" label="Tax advisor" default_value="150" checked="0" required="0"]
[cost_calculator_input_box id="tax-optimization" name="tax-optimization" type="checkbox" label="Tax optimization" default_value="100" checked="0" required="0"]
[cost_calculator_input_box id="contracts-preparation" name="contracts-preparation" type="checkbox" label="Preparation of contracts" default_value="500" checked="0" required="0"]</div>[/vc_column][/vc_row][vc_row row-layout="columns_3_1-3_1-3_1-3" top_margin="page-margin-top" el_class="cost-calculator-flex-box"][vc_column width="1/3" el_class="margin-top-40"]<div class="cost-calculator-summary-box"><div class="cost-calculator-box"><div class="margin-bottom-9"><label>Startup</label></div>[cost_calculator_summary_box id="startup" name="startup-total" formula="sales-per-month-value*business-type+purchase-per-month-value+employees-number*30+foreign-currency*sales-per-month-value+employees-number*annual-tax+sales-per-month-value*invoicing-customers+tax-advisor+tax-optimization+contracts-preparation" currency_after="/ month" currency_size="small" decimal_places="0" not_number="1" negative="0" label="Startup" el_class="margin-top-15"]<p class="cost-calculator-top-border margin-top-30 padding-top-35">Ideal if you are just starting out. Monthly account closing with ECI filing. Free xero subscription is included.</p><h5 class="cost-calculator-main-color margin-top-15 margin-bottom-15">Features included:</h5><ul class="cost-calculator-list"><li class="cc-template-bullet"><span>Monthly account closing</span></li><li class="cc-template-bullet"><span>Xero subscription</span></li><li class="cc-template-bullet"><span>ECI filing</span></li></ul>[cost_calculator_input_box id="submit-startup" name="startup" type="submit" default_value="Choose plan"]</div></div>[/vc_column][vc_column width="1/3"]<div class="cost-calculator-summary-box"><div class="cost-calculator-ribbon-container"><p>Most Popular</p></div><div class="cost-calculator-box"><div class="margin-bottom-9"><label>Timesaver</label></div>[cost_calculator_summary_box id="timesaver" name="timesaver-total" formula="sales-per-month-value*(business-type+business-type*1.85)+purchase-per-month-value+employees-number*35+foreign-currency*sales-per-month-value*1.5+employees-number*annual-tax+sales-per-month-value*invoicing-customers+tax-advisor+tax-optimization+contracts-preparation" currency_after="/ month" currency_size="small" decimal_places="0" not_number="1" negative="0" label="Timesaver" el_class="margin-top-15"]<p class="cost-calculator-top-border margin-top-30 padding-top-35">Everything you need to focus your business. Monthly account closking with ECI filing and submission. Company tax planning.</p><h5 class="cost-calculator-main-color margin-top-15 margin-bottom-15">Startup plan plus:</h5><ul class="cost-calculator-list"><li class="cc-template-bullet"><span>ECI submission</span></li><li class="cc-template-bullet"><span>Company tax planning</span></li><li class="cc-template-bullet"><span>Monthly reports</span></li></ul>[cost_calculator_input_box id="submit-timesaver" name="timesaver" type="submit" default_value="Choose plan"]</div></div>[/vc_column][vc_column width="1/3"]<div class="cost-calculator-summary-box"><div class="cost-calculator-ribbon-container"><p>For Business Sharks</p></div><div class="cost-calculator-box"><div class="margin-bottom-9"><label>Enterprise</label></div>[cost_calculator_summary_box id="enterprise" name="enterprise-total" formula="sales-per-month-value*(business-type+business-type*5.65)+purchase-per-month-value*1.2+employees-number*45+foreign-currency*sales-per-month-value*2+employees-number*annual-tax+sales-per-month-value*invoicing-customers+tax-advisor+tax-optimization+contracts-preparation" currency_after="/ month" currency_size="small" decimal_places="0" not_number="1" negative="0" label="Enterprise" el_class="margin-top-15"]<p class="cost-calculator-top-border margin-top-30 padding-top-35">Dedicated account manager with face-to-face meetings. Revenue recognition with advanced monthly reports.</p><h5 class="cost-calculator-main-color margin-top-15 margin-bottom-15">Timesaver plan plus:</h5><ul class="cost-calculator-list"><li class="cc-template-bullet"><span>Digital archiving</span></li><li class="cc-template-bullet"><span>Advanced monthly reports</span></li><li class="cc-template-bullet"><span>Revenue recognition</span></li><li class="cc-template-bullet"><span>Dedicated account manager</span></li></ul>[cost_calculator_input_box id="submit-enterprise" name="enterprise" type="submit" default_value="Choose plan"]</div></div>[/vc_column][/vc_row]',
		"cost-calculator-hidden-form" => '[vc_row row-layout="columns_1_1-1" top_margin="none" el_class="cost-calculator-align-center padding-top-70"][vc_column width="1/1"]<h3>Simply complete the form below and we\'ll get back to you shortly</h3><p>Please complete the form below and a member or our staff will contact you promptly.<br>You will receive a copy of this calculation to your e-mail address.</p>[/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="none" el_class="margin-top-20"][vc_column width="1/1" el_class="cost-calculator-centered-column"][cost_calculator_contact_box name_label="Name *" name_placeholder="Your Name" name_required="1" email_label="Email *" email_placeholder="your@email.com" email_required="1" phone_label="Phone" phone_placeholder="(123) 456" phone_required="0" message_label="Questions or comments *" message_placeholder="Hi there..." message_required="1" labels_style="labelplaceholder" terms_checkbox="0" terms_message="UGxlYXNlJTIwYWNjZXB0JTIwdGVybXM=" append="bookkeeping-calculator"][cost_calculator_input_box id="plan" name="plan" type="hidden" label="Selected plan"][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="none" el_class="cost-calculator-align-center margin-top-30"][vc_column width="1/1"]<p>Didn’t find what you’re looking for? Please<a title="Contact us" href="#" class="cost-calculator-space">contact us</a>for custom setups.</p>[/vc_column][/vc_row]',
		"loan" => '[vc_row row-layout="columns_2_1-2_1-2" top_margin="page-margin-top" el_class=""][vc_column width="1/2"][cost_calculator_slider_box id="loan-amount" name="loan-amount" label="Select loan amount" hide_label="0" default_value="15000" currency="$" currency_after="" thousandth_separator="," unit_value="1" step="500" min="1000" max="30000" minmax_label="1" input_field="0" top_margin="none" el_class=""][/vc_column][vc_column width="1/2"][cost_calculator_slider_box id="loan-term" name="loan-term" label="Select loan term (months)" hide_label="0" default_value="12" currency="" currency_after="&amp;space;mo." thousandth_separator="" unit_value="1" step="1" min="3" max="24" minmax_label="1" input_field="0" top_margin="none" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_3_1-3_1-3_1-3" top_margin="page-margin-top" el_class="cost-calculator-flex-box"][vc_column width="1/3" el_class="margin-top-40"]<div class="cost-calculator-summary-box"><div class="cost-calculator-box"><div class="margin-bottom-9"><label>Monthly repayments</label></div>[cost_calculator_summary_box id="monthly-repayments" name="monthly-repayments-total" formula="loan-amount*{powerstart}1+rate-of-interest-total-value/100/12^loan-term{powerend}*(1+rate-of-interest-total-value/100/12{-}1)/({powerstart}1+rate-of-interest-total-value/100/12^loan-term{powerend}{-}1)" currency_after="/ month" currency_size="small" decimal_places="0" not_number="1" negative="0" label="Monthly repayments" el_class="margin-top-15"]<h5 class="cost-calculator-main-color cost-calculator-top-border margin-top-30 margin-bottom-15 padding-top-35">Loan details:</h5>[cost_calculator_summary_box id="loan-amount-summary" name="loan-amount-summary-total" label="" formula="loan-amount" currency="$" currency_after="" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Loan amount" icon="" el_class="cost-calculator-cost-list margin-top-15"]

[cost_calculator_summary_box id="loan-term-summary" name="loan-term-summary-total" label="" formula="loan-term" currency="" currency_after="&amp;space;mo." currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Loan term" icon="" el_class="cost-calculator-cost-list"]

[cost_calculator_summary_box id="interest-amount-summary" name="interest-amount-total" label="" formula="monthly-repayments-total-value*loan-term{-}loan-amount" currency="$" currency_after="" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Interest amount" icon="" el_class="cost-calculator-cost-list"]

[cost_calculator_summary_box id="total-repeyable-summary" name="total-repeyable-summary-total" label="" formula="monthly-repayments-total-value*loan-term" currency="$" currency_after="" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Total repeyable" icon="" el_class="cost-calculator-cost-list"]

[cost_calculator_summary_box id="rate-of-interest" name="rate-of-interest-total" label="" formula="22" currency="" currency_after="%" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Rate of interest" icon="" el_class="cost-calculator-cost-list"]

[cost_calculator_input_box id="submit-monthly" name="monthly" label="" hide_label="0" default_value="Get a loan" type="submit" checked="1" checkbox_type="type-button" checkbox_yes="checked" checkbox_no="not checked" required="0" required_message="This field is required" placeholder="" after_pseudo="" top_margin="none" el_class=""]</div></div>[/vc_column][vc_column width="1/3"]<div class="cost-calculator-summary-box"><div class="cost-calculator-ribbon-container"><p>Larger Amount?</p></div><div class="cost-calculator-box"><div class="margin-bottom-9"><label>Larger loan amount</label></div>[cost_calculator_summary_box id="larger-loan" name="larger-loan-total" label="Larger loan amount" formula="2*loan-amount*{powerstart}1+larger-rate-of-interest-total-value/100/12^loan-term{powerend}*(1+larger-rate-of-interest-total-value/100/12{-}1)/({powerstart}1+larger-rate-of-interest-total-value/100/12^loan-term{powerend}{-}1)" currency="$" currency_after="/ month" currency_size="small" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="" icon="" el_class="margin-top-15"]<h5 class="cost-calculator-main-color cost-calculator-top-border margin-top-30 margin-bottom-15 padding-top-35">Loan details:</h5>[cost_calculator_summary_box id="larger-loan-amount-summary" name="larger-loan-amount-summary-total" label="" formula="2*loan-amount" currency="$" currency_after="" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Loan amount" icon="" el_class="cost-calculator-cost-list margin-top-15"]

[cost_calculator_summary_box id="larger-loan-term-summary" name="larger-loan-term-summary-total" label="" formula="loan-term" currency="" currency_after="&amp;space;mo." currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Loan term" icon="" el_class="cost-calculator-cost-list"]

[cost_calculator_summary_box id="larger-interest-amount-summary" name="larger-interest-amount-total" label="" formula="larger-loan-total-value*loan-term{-}2*loan-amount" currency="$" currency_after="" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Interest amount" icon="" el_class="cost-calculator-cost-list"]

[cost_calculator_summary_box id="larger-total-repeyable-summary" name="larger-total-repeyable-summary-total" label="" formula="larger-loan-total-value*loan-term" currency="$" currency_after="" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Total repeyable" icon="" el_class="cost-calculator-cost-list"]

[cost_calculator_summary_box id="larger-rate-of-interest" name="larger-rate-of-interest-total" label="" formula="22" currency="" currency_after="%" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Rate of interest" icon="" el_class="cost-calculator-cost-list"]

[cost_calculator_input_box id="submit-larger" name="larger" label="" hide_label="0" default_value="Get a loan" type="submit" checked="1" checkbox_type="type-button" checkbox_yes="checked" checkbox_no="not checked" required="0" required_message="This field is required" placeholder="" after_pseudo="" top_margin="none" el_class=""]</div></div>[/vc_column][vc_column width="1/3"]<div class="cost-calculator-summary-box"><div class="cost-calculator-ribbon-container"><p>Longer Repayment Period?</p></div><div class="cost-calculator-box"><div class="margin-bottom-9"><label>Longer loan amount</label></div>[cost_calculator_summary_box id="longer-loan" name="longer-loan-total" label="Longer repayment period" formula="loan-amount*{powerstart}1+longer-rate-of-interest-total-value/100/12^(2*loan-term){powerend}*(1+longer-rate-of-interest-total-value/100/12{-}1)/({powerstart}1+longer-rate-of-interest-total-value/100/12^(2*loan-term){powerend}{-}1)" currency="$" currency_after="/ month" currency_size="small" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="" icon="" el_class="margin-top-15"]<h5 class="cost-calculator-main-color cost-calculator-top-border margin-top-30 margin-bottom-15 padding-top-35">Loan details:</h5>[cost_calculator_summary_box id="longer-loan-amount-summary" name="longer-loan-amount-summary-total" label="" formula="loan-amount" currency="$" currency_after="" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Loan amount" icon="" el_class="cost-calculator-cost-list margin-top-15"]

[cost_calculator_summary_box id="longer-loan-term-summary" name="longer-loan-term-summary-total" label="" formula="2*loan-term" currency="" currency_after="&amp;space;mo." currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Loan term" icon="" el_class="cost-calculator-cost-list"]

[cost_calculator_summary_box id="longer-interest-amount-summary" name="longer-interest-amount-total" label="" formula="longer-loan-total-value*2*loan-term{-}loan-amount" currency="$" currency_after="" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Interest amount" icon="" el_class="cost-calculator-cost-list"]

[cost_calculator_summary_box id="longer-total-repeyable-summary" name="longer-total-repeyable-summary-total" label="" formula="longer-loan-total-value*2*loan-term" currency="$" currency_after="" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Total repeyable" icon="" el_class="cost-calculator-cost-list"]

[cost_calculator_summary_box id="longer-rate-of-interest" name="longer-rate-of-interest-total" label="" formula="22" currency="" currency_after="%" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="," decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Rate of interest" icon="" el_class="cost-calculator-cost-list"]

[cost_calculator_input_box id="submit-longer" name="longer" label="" hide_label="0" default_value="Get a loan" type="submit" checked="1" checkbox_type="type-button" checkbox_yes="checked" checkbox_no="not checked" required="0" required_message="This field is required" placeholder="" after_pseudo="" top_margin="none" el_class=""]</div></div>[/vc_column][/vc_row]',
		"cost-calculator-hidden-form-loan" => '[vc_row row-layout="columns_1_1-1" top_margin="none" el_class="cost-calculator-align-center padding-top-70"][vc_column width="1/1"]<h3>Simply complete the form below and we\'ll get back to you shortly</h3><p>Please complete the form below and a member or our staff will contact you promptly.<br>You will receive a copy of this calculation to your e-mail address.</p>[/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="none" el_class="margin-top-20"][vc_column width="1/1" el_class="cost-calculator-centered-column"][cost_calculator_contact_box name_label="Name *" name_placeholder="Your Name" name_required="1" email_label="Email *" email_placeholder="your@email.com" email_required="1" phone_label="Phone" phone_placeholder="(123) 456" phone_required="0" message_label="Questions or comments *" message_placeholder="Hi there..." message_required="1" labels_style="labelplaceholder" terms_checkbox="0" append="loan"][cost_calculator_input_box id="plan" name="plan" type="hidden" label="Selected plan"][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="none" el_class="cost-calculator-align-center margin-top-30"][vc_column width="1/1"]<p>Didn’t find what you’re looking for? Please<a title="Contact us" href="#" class="cost-calculator-space">contact us</a>for custom setups.</p>[/vc_column][/vc_row]',
		"daily-calorie-needs" => '[vc_row row-layout="columns_2_1-2_1-2" top_margin="page-margin-top" el_class="cost-calculator-border-columns"][vc_column width="1/2"][cost_calculator_slider_box id="user-age" name="user-age" label="Your Age (years)" hide_label="0" default_value="40" currency="" currency_after="" thousandth_separator="" unit_value="1" step="1" min="5" max="120" minmax_label="0" input_field="1" top_margin="none" el_class=""][/vc_column][vc_column width="1/2"]<div class="cost-calculator-box cost-calculator-clearfix"><div class="margin-bottom-6"><label>Gender</label></div>[cost_calculator_input_box id="user-gender1" name="user-gender" label="Male" hide_label="0" group_label="Gender" default_value="5" type="radio" checked="1"]
[cost_calculator_input_box id="user-gender2" name="user-gender" label="Female" hide_label="0" group_label="Gender" default_value="-161" type="radio" checked="0"]</div>[/vc_column][/vc_row][vc_row row-layout="columns_2_1-2_1-2" top_margin="none" el_class="cost-calculator-border-columns"][vc_column width="1/2"][cost_calculator_slider_box id="user-weight" name="user-weight" label="Your Weight (kg)" hide_label="0" default_value="70" currency="" currency_after="" thousandth_separator="" unit_value="1" step="1" min="1" max="150" minmax_label="0" input_field="1" top_margin="none" el_class=""][/vc_column][vc_column width="1/2"][cost_calculator_slider_box id="user-height" name="user-height" label="Your Height (cm)" hide_label="0" default_value="170" currency="" currency_after="" thousandth_separator="" unit_value="1" step="1" min="40" max="225" minmax_label="0" input_field="1" top_margin="none" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="none" el_class="cost-calculator-border-columns cost-calculator-align-center"][vc_column width="1/1"][cost_calculator_summary_box id="brm" name="brm" label="Basal Metabolic Rate (BMR):" formula="10*user-weight+6.25*user-height{-}5*user-age+user-gender" currency="" currency_after="" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="" decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Your BRM (calories/day)" icon="" el_class=""][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1" top_margin="none" el_class="cost-calculator-border-columns"][vc_column width="1/1"][cost_calculator_dropdown_box id="user-activity-level" name="user-activity-level" label="Your Activity Level" hide_label="0" default_value="1" options_count="6" option_name0="Sitting / Lying all day" option_value0="1" option_name1="Seated Work, No Exercise" option_value1="1.2" option_name2="Seated Work, Light Exercise" option_value2="1.375" option_name3="Physical Work, No Exercise" option_value3="1.55" option_name4="Physical Work, Light Exercise" option_value4="1.725" option_name5="Physical Work, Heavy Exercise" option_value5="1.9" show_choose_label="0" choose_label="Choose..." required="0" required_message="This field is required" top_margin="none"][/vc_column][/vc_row][vc_row row-layout="columns_2_1-2_1-2" top_margin="none" el_class="cost-calculator-border-columns last"][vc_column width="1/2"][cost_calculator_summary_box id="daily-calorie-needs-summary" name="daily-calorie-needs-summary" label="Daily Calorie Needs:" formula="brm-total-value*user-activity-level" currency="" currency_after="" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="" decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Daily Calorie Needs" icon="" el_class="cost-calculator-align-center"][/vc_column][vc_column width="1/2"][cost_calculator_summary_box id="fat-loss" name="fat-loss" label="Fat loss (calories/day):" formula="daily-calorie-needs-summary-total-value{-}500" currency="" currency_after="" currency_size="default" currency_align="top" currency_after_align="bottom" thousandth_separator="" decimal_separator="." decimal_places="0" not_number="1" negative="0" description="Fat Loss (calories/day)" icon="" el_class="cost-calculator-align-center"][/vc_column][/vc_row][vc_row row-layout="columns_1_1-1"][vc_column width="1/1"]<h2 class="cost-calculator-align-center page-margin-top">Make Your Training Plan</h2><p class="cost-calculator-align-center">Please provide your details and submit the form and we will create<br>entire program tailored to your specific needs</p>[cost_calculator_contact_box label="" submit_label="SUBMIT NOW" name_label="YOUR NAME" name_placeholder="YOUR NAME" name_required="1" email_label="YOUR EMAIL" email_placeholder="YOUR EMAIL" email_required="1" phone_label="YOUR PHONE" phone_placeholder="YOUR PHONE" phone_required="0" message_label="QUESTIONS OR COMMENTS" message_placeholder="QUESTIONS OR COMMENTS" message_required="0" append="" type="" labels_style="default" terms_checkbox="0" terms_message="" description="" el_class="margin-top-20"][/vc_column][/vc_row]'
	);
	$cost_calculator_shortcodes_list = get_option("cost_calculator_shortcodes_list");
	if($cost_calculator_shortcodes_list===false)
		$cost_calculator_shortcodes_list = array();
	foreach($cost_calculator_shortcodes_live_preview as $key=>$val)
	{
		if(!array_key_exists($key, $cost_calculator_shortcodes_list))
			$cost_calculator_shortcodes_list[$key] = $val;
	}
	ksort($cost_calculator_shortcodes_list);
	update_option("cost_calculator_shortcodes_list", $cost_calculator_shortcodes_list);
	
	update_option("cost_calculator_advanced_settings_carservice", array(
		"calculator_skin" => "carservice",
		"main_color" => "1E69B8",
		"box_color" => "",
		"text_color" => "777777",
		"border_color" => "E2E6E7",
		"label_color" => "333333",
		"dropdowncheckbox_label_color" => "333333",
		"form_label_color" => "",
		"inactive_color" => "E2E6E7",
		"tooltip_background_color" => "",
		"primary_font_custom" => "",
		"primary_font" => "Open Sans",
		"primary_font_variant" => array("regular"),
		"primary_font_subset" => array("latin", "latin-ext"),
		"secondary_font_custom" => "",
		"secondary_font" => "",
		"secondary_font_variant" => "",
		"secondary_font_subset" => "",
		"form_display" => "",
		"form_action_url" => "#",
		"thank_you_page_url" => ""
	));
	
	update_option("cost_calculator_advanced_settings_cleanmate", array(
		"calculator_skin" => "default",
		"main_color" => "",
		"box_color" => "FFFFFF",
		"text_color" => "",
		"border_color" => "",
		"label_color" => "",
		"dropdowncheckbox_label_color" => "",
		"form_label_color" => "",
		"inactive_color" => "",
		"tooltip_background_color" => "",
		"primary_font_custom" => "",
		"primary_font" => "",
		"primary_font_variant" => "",
		"primary_font_subset" => "",
		"secondary_font_custom" => "",
		"secondary_font" => "",
		"secondary_font_variant" => "",
		"secondary_font_subset" => "",
		"form_display" => "",
		"form_action_url" => "#",
		"thank_you_page_url" => ""
	));
	
	update_option("cost_calculator_advanced_settings_renovate", array(
		"calculator_skin" => "renovate",
		"main_color" => "D5EBD4",
		"box_color" => "F5F5F5",
		"text_color" => "444444",
		"border_color" => "E2E6E7",
		"label_color" => "25282A",
		"dropdowncheckbox_label_color" => "25282A",
		"form_label_color" => "",
		"inactive_color" => "E2E6E7",
		"tooltip_background_color" => "",
		"primary_font_custom" => "",
		"primary_font" => "",
		"primary_font_variant" => "",
		"primary_font_subset" => "",
		"secondary_font_custom" => "",
		"secondary_font" => "Raleway",
		"secondary_font_variant" => array("300"),
		"secondary_font_subset" => array("latin", "latin-ext"),
		"form_display" => "",
		"form_action_url" => "#",
		"thank_you_page_url" => ""
	));
	
	update_option("cost_calculator_advanced_settings_lpg-calculator", array(
		"calculator_skin" => "default",
		"main_color" => "",
		"box_color" => "FFFFFF",
		"text_color" => "",
		"border_color" => "",
		"label_color" => "",
		"dropdowncheckbox_label_color" => "",
		"form_label_color" => "",
		"inactive_color" => "",
		"tooltip_background_color" => "",
		"primary_font_custom" => "",
		"primary_font" => "",
		"primary_font_variant" => "",
		"primary_font_subset" => "",
		"secondary_font_custom" => "",
		"secondary_font" => "",
		"secondary_font_variant" => "",
		"secondary_font_subset" => "",
		"form_display" => "",
		"form_action_url" => "#",
		"thank_you_page_url" => ""
	));
	
	update_option("cost_calculator_advanced_settings_mortgage", array(
		"calculator_skin" => "renovate",
		"main_color" => "00B6CC",
		"box_color" => "F5F5F5",
		"text_color" => "444444",
		"border_color" => "E2E6E7",
		"label_color" => "25282A",
		"dropdowncheckbox_label_color" => "25282A",
		"form_label_color" => "",
		"inactive_color" => "E2E6E7",
		"tooltip_background_color" => "",
		"primary_font_custom" => "",
		"primary_font" => "",
		"primary_font_variant" => "",
		"primary_font_subset" => "",
		"secondary_font_custom" => "",
		"secondary_font" => "Raleway",
		"secondary_font_variant" => array("300"),
		"secondary_font_subset" => array("latin", "latin-ext"),
		"form_display" => "",
		"form_action_url" => "#",
		"thank_you_page_url" => ""
	));
	
	update_option("cost_calculator_advanced_settings_bmi", array(
		"calculator_skin" => "default",
		"main_color" => "9187C4",
		"box_color" => "FFFFFF",
		"text_color" => "",
		"border_color" => "",
		"label_color" => "",
		"dropdowncheckbox_label_color" => "",
		"form_label_color" => "",
		"inactive_color" => "",
		"tooltip_background_color" => "",
		"primary_font_custom" => "",
		"primary_font" => "",
		"primary_font_variant" => "",
		"primary_font_subset" => "",
		"secondary_font_custom" => "",
		"secondary_font" => "",
		"secondary_font_variant" => "",
		"secondary_font_subset" => "",
		"form_display" => "",
		"form_action_url" => "#",
		"thank_you_page_url" => ""
	));
	
	update_option("cost_calculator_advanced_settings_hosting", array(
		"calculator_skin" => "default",
		"main_color" => "F37548",
		"box_color" => "FFFFFF",
		"text_color" => "",
		"border_color" => "",
		"label_color" => "",
		"dropdowncheckbox_label_color" => "",
		"form_label_color" => "",
		"inactive_color" => "",
		"tooltip_background_color" => "",
		"primary_font_custom" => "",
		"primary_font" => "",
		"primary_font_variant" => "",
		"primary_font_subset" => "",
		"secondary_font_custom" => "",
		"secondary_font" => "",
		"secondary_font_variant" => "",
		"secondary_font_subset" => "",
		"form_display" => "",
		"form_action_url" => "#",
		"thank_you_page_url" => ""
	));
	
	update_option("cost_calculator_advanced_settings_web-design", array(
		"calculator_skin" => "carservice",
		"main_color" => "5FC7AE",
		"box_color" => "",
		"text_color" => "777777",
		"border_color" => "E2E6E7",
		"label_color" => "333333",
		"dropdowncheckbox_label_color" => "333333",
		"form_label_color" => "",
		"inactive_color" => "E2E6E7",
		"tooltip_background_color" => "",
		"primary_font_custom" => "",
		"primary_font" => "Open Sans",
		"primary_font_variant" => array("regular"),
		"primary_font_subset" => array("latin", "latin-ext"),
		"secondary_font_custom" => "",
		"secondary_font" => "",
		"secondary_font_variant" => "",
		"secondary_font_subset" => "",
		"form_display" => "",
		"form_action_url" => "#",
		"thank_you_page_url" => ""
	));
	
	update_option("cost_calculator_advanced_settings_bookkeeping-calculator", array(
		"calculator_skin" => "finpeak",
		"main_color" => "377EF9",
		"box_color" => "FFFFFF",
		"text_color" => "252634",
		"border_color" => "E6E8ED",
		"label_color" => "252634",
		"dropdowncheckbox_label_color" => "868F9E",
		"form_label_color" => "868F9E",
		"inactive_color" => "E6E8ED",
		"tooltip_background_color" => "1B2E59",
		"primary_font_custom" => "",
		"primary_font" => "Nunito Sans",
		"primary_font_variant" => array("300", "regular"),
		"primary_font_subset" => array("latin", "latin-ext"),
		"secondary_font_custom" => "",
		"secondary_font" => "Montserrat",
		"secondary_font_variant" => array("500", "600"),
		"secondary_font_subset" => array("latin", "latin-ext"),
		"form_display" => "",
		"form_action_url" => "#cost-calculator-hidden-form",
		"thank_you_page_url" => ""
	));
	
	update_option("cost_calculator_advanced_settings_cost-calculator-hidden-form", array(
		"calculator_skin" => "finpeak",
		"main_color" => "377EF9",
		"box_color" => "",
		"text_color" => "252634",
		"border_color" => "E6E8ED",
		"label_color" => "252634",
		"dropdowncheckbox_label_color" => "868F9E",
		"form_label_color" => "868F9E",
		"inactive_color" => "E6E8ED",
		"tooltip_background_color" => "1B2E59",
		"primary_font_custom" => "",
		"primary_font" => "Nunito Sans",
		"primary_font_variant" => array("300", "regular"),
		"primary_font_subset" => array("latin", "latin-ext"),
		"secondary_font_custom" => "",
		"secondary_font" => "Montserrat",
		"secondary_font_variant" => array("500", "600"),
		"secondary_font_subset" => array("latin", "latin-ext"),
		"form_display" => "hidden",
		"form_action_url" => "#",
		"thank_you_page_url" => ""
	));
	
	update_option("cost_calculator_advanced_settings_loan", array(
		"calculator_skin" => "finpeak",
		"main_color" => "377EF9",
		"box_color" => "FFFFFF",
		"text_color" => "505563",
		"border_color" => "E6E8ED",
		"label_color" => "252634",
		"dropdowncheckbox_label_color" => "868F9E",
		"form_label_color" => "868F9E",
		"inactive_color" => "E6E8ED",
		"tooltip_background_color" => "1B2E59",
		"primary_font_custom" => "",
		"primary_font" => "Nunito Sans",
		"primary_font_variant" => array("300", "regular"),
		"primary_font_subset" => array("latin", "latin-ext"),
		"secondary_font_custom" => "",
		"secondary_font" => "Montserrat",
		"secondary_font_variant" => array("500", "600"),
		"secondary_font_subset" => array("latin", "latin-ext"),
		"form_display" => "",
		"form_action_url" => "#cost-calculator-hidden-form-loan",
		"thank_you_page_url" => ""
	));
	
	update_option("cost_calculator_advanced_settings_cost-calculator-hidden-form-loan", array(
		"calculator_skin" => "finpeak",
		"main_color" => "377EF9",
		"box_color" => "",
		"text_color" => "252634",
		"border_color" => "E6E8ED",
		"label_color" => "252634",
		"dropdowncheckbox_label_color" => "868F9E",
		"form_label_color" => "868F9E",
		"inactive_color" => "E6E8ED",
		"tooltip_background_color" => "1B2E59",
		"primary_font_custom" => "",
		"primary_font" => "Nunito Sans",
		"primary_font_variant" => array("300", "regular"),
		"primary_font_subset" => array("latin", "latin-ext"),
		"secondary_font_custom" => "",
		"secondary_font" => "Montserrat",
		"secondary_font_variant" => array("500", "600"),
		"secondary_font_subset" => array("latin", "latin-ext"),
		"form_display" => "hidden",
		"form_action_url" => "#",
		"thank_you_page_url" => ""
	));
	
	update_option("cost_calculator_advanced_settings_daily-calorie-needs", array(
		"calculator_skin" => "gymbase",
		"main_color" => "409915",
		"box_color" => "222224",
		"text_color" => "FFFFFF",
		"border_color" => "515151",
		"label_color" => "FFFFFF",
		"dropdowncheckbox_label_color" => "FFFFFF",
		"form_label_color" => "999999",
		"inactive_color" => "343436",
		"tooltip_background_color" => "222224",
		"primary_font_custom" => "",
		"primary_font" => "Raleway",
		"primary_font_variant" => array("regular", "600"),
		"primary_font_subset" => array("latin", "latin-ext"),
		"secondary_font_custom" => "",
		"secondary_font" => "",
		"secondary_font_variant" => "",
		"secondary_font_subset" => "",
		"form_display" => "",
		"form_action_url" => "#",
		"thank_you_page_url" => ""
	));
	
	if($result["info"]=="")
		$result["info"] = __("Dummy content has been imported successfully!", 'cost-calculator');
	echo "dummy_import_start" . json_encode($result) . "dummy_import_end";
	exit();
}
add_action('wp_ajax_cost_calculator_import_dummy', 'cost_calculator_import_dummy');

function cost_calculator($atts)
{
	$output = "";
	if(!empty($atts["id"]))
	{
		$id = $atts["id"];
		$cost_calculator_shortcodes_list = get_option("cost_calculator_shortcodes_list");
		if($cost_calculator_shortcodes_list!==false && !empty($cost_calculator_shortcodes_list[$id]))
		{
			$shortcode = html_entity_decode($cost_calculator_shortcodes_list[$id]);
			wp_register_style("cost_calculator_inline_style", false);
			wp_enqueue_style("cost_calculator_inline_style");
			$advanced_settings = cost_calculator_stripslashes_deep(get_option("cost_calculator_advanced_settings_" . $id));
			if($advanced_settings["primary_font"]!="" && $advanced_settings["primary_font_custom"]=="")
				wp_enqueue_style("cc-google-font-primary-" . $id, "//fonts.googleapis.com/css?family=" . urlencode($advanced_settings["primary_font"]) . (!empty($advanced_settings["primary_font_variant"]) ? ":" . implode(",", $advanced_settings["primary_font_variant"]) : "") . (!empty($advanced_settings["primary_font_subset"]) ? "&subset=" . implode(",", $advanced_settings["primary_font_subset"]) : ""));
			if($advanced_settings["secondary_font"]!="" && $advanced_settings["secondary_font_custom"]=="")
				wp_enqueue_style("cc-google-font-secondary-" . $id, "//fonts.googleapis.com/css?family=" . urlencode($advanced_settings["secondary_font"]) . (!empty($advanced_settings["secondary_font_variant"]) ? ":" . implode(",", $advanced_settings["secondary_font_variant"]) : "") . (!empty($advanced_settings["secondary_font_subset"]) ? "&subset=" . implode(",", $advanced_settings["secondary_font_subset"]) : ""));
			ob_start();
			require("custom_colors.php");
			$custom_colors_css = ob_get_clean();
			wp_add_inline_style("cost_calculator_inline_style", $custom_colors_css);
			$output = '<form id="' . esc_attr($id) . '" action="' . (isset($advanced_settings["form_action_url"]) && $advanced_settings["form_action_url"]!="" && $advanced_settings["form_action_url"]!="#" ? esc_attr($advanced_settings["form_action_url"]) : "#") . '" method="post" class="cost-calculator-container cost-calculator-form' . (isset($advanced_settings["form_display"]) && $advanced_settings["form_display"]=="hidden" ? ' cost-calculator-hidden' : '') . '"' . (isset($advanced_settings["thank_you_page_url"]) && $advanced_settings["thank_you_page_url"]!="" ? 'data-thankyoupageurl="' . esc_url($advanced_settings["thank_you_page_url"]) . '"' : '') . '>' . do_shortcode($shortcode) . '</form>';
		}
		else
			$output = __("Cost Calculator with given id doesn't exists.", 'cost-calculator');
	}
	else
		$output = __("Id parameter missing in the [cost_calculator] shortcode.", 'cost-calculator');
	return $output;
}
add_shortcode("cost_calculator", "cost_calculator");

//shortcodes
function cost_calculator_init()
{
	global $theme_options;
	if(function_exists("finpeak_theme_after_setup_theme"))
	{
		global $finpeak_theme_options;
		$theme_options = $finpeak_theme_options;
	}
	$smtp = (isset($theme_options["cf_smtp_host"]) ? $theme_options["cf_smtp_host"] : "");
	if(empty($smtp))
	{
		//phpMailer
		add_action('phpmailer_init', 'cost_calculator_phpmailer_init');
	}
	include_once(ABSPATH . 'wp-admin/includes/plugin.php');
	$active_sitewide_plugins = get_site_option('active_sitewide_plugins');
	$js_composer_path_array = array_merge(preg_grep("/js_composer/", (array)get_option('active_plugins')), preg_grep("/js_composer/", (is_array($active_sitewide_plugins) ? array_flip($active_sitewide_plugins) : array())));
	$js_composer_path = (count($js_composer_path_array) ? $js_composer_path_array[0] : "js_composer/js_composer.php");
	$cost_calculator_global_form_options = get_option("cost_calculator_global_form_options");
	require_once("shortcodes/cost_calculator_row.php");
	require_once("shortcodes/cost_calculator_column.php");
	require_once("shortcodes/cost_calculator_dropdown_box.php");
	require_once("shortcodes/cost_calculator_slider_box.php");
	require_once("shortcodes/cost_calculator_input_box.php");
	require_once("shortcodes/cost_calculator_switch_box.php");
	require_once("shortcodes/cost_calculator_summary_box.php");
	require_once("shortcodes/cost_calculator_contact_box.php");
	
	if(is_plugin_active($js_composer_path) && function_exists('vc_map'))
	{
		global $wpdb;
		$cost_calculator_shortcodes_list = get_option("cost_calculator_shortcodes_list");
		$cost_calculator_shortcodes_array = array(__("choose...", "cost-calculator") => "-1");
		if(!empty($cost_calculator_shortcodes_list))
		{
			foreach($cost_calculator_shortcodes_list as $key=>$val)
				$cost_calculator_shortcodes_array[$key] = $key;
		}
		
		vc_map( array(
			"name" => __("Cost Calculator", 'cost-calculator'),
			"base" => "cost_calculator",
			"class" => "",
			"controls" => "full",
			"show_settings_on_create" => true,
			"icon" => "icon-wpb-layer-cost-calculator",
			"category" => __('Cost Calculator', 'cost-calculator'),
			"params" => array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Id", 'css3_grid'),
					"param_name" => "id",
					"value" => $cost_calculator_shortcodes_array
				)
			)
		));
		if(empty($cost_calculator_global_form_options["wpbakery_noconflict"]) && function_exists("vc_shortcodes_theme_templates_dir") && vc_shortcodes_theme_templates_dir("vc_row.php")=="" && vc_shortcodes_theme_templates_dir("vc_row_inner.php")=="" && vc_shortcodes_theme_templates_dir("vc_column.php")=="" && vc_shortcodes_theme_templates_dir("vc_column_inner.php")=="")
		{
			vc_set_shortcodes_templates_dir(plugin_dir_path(__FILE__) . 'vc_templates');
		}
	}
}
add_action("init", "cost_calculator_init"); 

function cost_calculator_phpmailer_init($mail) 
{
	$cost_calculator_contact_form_options = array(
		"smtp_host" => '',
		"smtp_username" => '',
		"smtp_password" => '',
		"smtp_port" => '',
		"smtp_secure" => ''
	);
	
	$cost_calculator_contact_form_options = cost_calculator_stripslashes_deep(array_merge($cost_calculator_contact_form_options, (array)get_option("cost_calculator_contact_form_options")));
	
	$mail->CharSet='UTF-8';
	$mail->IsHTML(true);
	$smtp = (isset($cost_calculator_contact_form_options["smtp_host"]) ? $cost_calculator_contact_form_options["smtp_host"] : null);
	if(!empty($smtp))
	{
		$mail->IsSMTP();
		$mail->SMTPAuth = true; 
		//$mail->SMTPDebug = 2;
		$mail->Host = $cost_calculator_contact_form_options["smtp_host"];
		$mail->Username = $cost_calculator_contact_form_options["smtp_username"];
		$mail->Password = $cost_calculator_contact_form_options["smtp_password"];
		if((int)$cost_calculator_contact_form_options["smtp_port"]>0)
			$mail->Port = (int)$cost_calculator_contact_form_options["smtp_port"];
		$mail->SMTPSecure = $cost_calculator_contact_form_options["smtp_secure"];
	}
}

function cost_calculator_stripslashes_deep($value)
{
	$value = is_array($value) ?
				array_map('stripslashes_deep', $value) :
				stripslashes($value);
	
	return $value;
}
//get_font_subsets
function cc_ajax_get_font_details()
{
	if($_POST["font"]!="")
	{
		$result = array();
		$subsets = '';
		$fontExplode = explode(":", $_POST["font"]);
		$subsets_array = cc_get_google_font_subset($fontExplode[0]);
		if(count((array)$subsets_array)>1)
		{
			foreach($subsets_array as $subset)
			{
				$subsets .= '<option value="' . esc_attr($subset) . '">' . esc_html($subset) . '</option>';
			}
		}
		$result["subsets"] = $subsets;
		
		$variants = '';
		$fontExplode = explode(":", $_POST["font"]);
		$varaints_array = cc_get_google_font_variant($fontExplode[0]);
		if(count((array)$varaints_array)>1)
		{
			foreach($varaints_array as $variant)
			{
				$variants .= '<option value="' . esc_attr($variant) . '">' . esc_html($variant) . '</option>';
			}
		}
		$result["variants"] = $variants;
		
		echo "cc_start" . json_encode($result) . "cc_end";
	}
	exit();
}
add_action('wp_ajax_cc_ajax_get_font_details', 'cc_ajax_get_font_details');

/**
 * Returns array of Google Fonts
 * @return array of Google Fonts
 */
function cc_get_google_fonts()
{
	//get google fonts
	$fontsArray = get_option("cc_google_fonts");
	//update if option doesn't exist or it was modified more than 2 weeks ago
	if($fontsArray===FALSE || count((array)$fontsArray)==0 || (time()-$fontsArray->last_update>2*7*24*60*60)) 
	{
		$google_api_url = 'https://quanticalabs.com/.tools/GoogleFont/font.txt';
		$fontsJson = wp_remote_retrieve_body(wp_remote_get($google_api_url, array('sslverify' => false)));
		$fontsArray = json_decode($fontsJson);
		if(isset($fontsArray))
		{
			$fontsArray->last_update = time();		
			update_option("cc_google_fonts", $fontsArray);
		}
	}
	return $fontsArray;
}

/**
 * Returns array of subsets for provided Google Font
 * @param type $font - Google font
 * @return array of subsets for provided Google Font
 */
function cc_get_google_font_subset($font)
{
	$subsets = array();
	//get google fonts
	$fontsArray = cc_get_google_fonts();		
	$fontsCount = count($fontsArray->items);
	for($i=0; $i<$fontsCount; $i++)
	{
		if($fontsArray->items[$i]->family==$font)
		{
			for($j=0, $max=count($fontsArray->items[$i]->subsets); $j<$max; $j++)
			{
				$subsets[] = $fontsArray->items[$i]->subsets[$j];
			}
			break;
		}
	}
	return $subsets;
}

/**
 * Returns array of variants for provided Google Font
 * @param type $font - Google font
 * @return array of variants for provided Google Font
 */
function cc_get_google_font_variant($font)
{
	$variants = array();
	//get google fonts
	$fontsArray = cc_get_google_fonts();		
	$fontsCount = count($fontsArray->items);
	for($i=0; $i<$fontsCount; $i++)
	{
		if($fontsArray->items[$i]->family==$font)
		{
			for($j=0, $max=count($fontsArray->items[$i]->variants); $j<$max; $j++)
			{
				$variants[] = $fontsArray->items[$i]->variants[$j];
			}
			break;
		}
	}
	return $variants;
}
?>
