<?php
$themename = "carservice";
/*function your_prefix_vcSetAsTheme() 
{
	vc_set_as_theme();
}
add_action('init', 'your_prefix_vcSetAsTheme');*/
if(function_exists('set_revslider_as_theme'))
{
	function carservice_set_revolution_as_theme() 
	{
		set_revslider_as_theme();
	}
	add_action('init', 'carservice_set_revolution_as_theme');
}

//plugins activator
require_once("plugins_activator.php");

//for is_plugin_active
include_once( ABSPATH . 'wp-admin/includes/plugin.php');

//vc_remove_element("vc_row_inner");
if(function_exists("vc_remove_element"))
{
	vc_remove_element("vc_gmaps");
	vc_remove_element("vc_tour");
	vc_remove_element("vc_separator");
	vc_remove_element("vc_text_separator");
}

//theme options
cs_get_theme_file("/theme-options.php");

//menu walker
cs_get_theme_file("/mobile_menu_walker.php");

//custom meta box
cs_get_theme_file("/meta-box.php");

if(function_exists("vc_map"))
{
	//contact_form
	cs_get_theme_file("/contact_form.php");
	//shortcodes
	cs_get_theme_file("/shortcodes/shortcodes.php");
}

//comments
cs_get_theme_file("/comments-functions.php");

//widgets
cs_get_theme_file("/widgets/widget-contact-info.php");
cs_get_theme_file("/widgets/widget-contact-details.php");
cs_get_theme_file("/widgets/widget-contact-details-list.php");
cs_get_theme_file("/widgets/widget-list.php");
cs_get_theme_file("/widgets/widget-recent.php");
cs_get_theme_file("/widgets/widget-social-icons.php");
cs_get_theme_file("/widgets/widget-cart-icon.php");

function cs_theme_after_setup_theme()
{
	global $themename;
	//set default theme options
	if(!get_option($themename . "_installed") || !get_option("wpb_js_content_types") || !get_option("carservice_vc_access_rules"))
	{		
		$theme_options = array(
			"favicon_url" => get_template_directory_uri() . "/images/favicon.ico",
			"logo_url" => "",
			"logo_text" => "CARSERVICE",
			"footer_text" => sprintf(__('© Copyright 2024 <a target="_blank" title="%s" href="%s" rel="nofollow">Car Service Theme</a> by <a target="_blank" title="%s" href="%s">QuanticaLabs</a>', 'carservice'), esc_html__('Car Service Theme', 'carservice'), esc_url(__('https://1.envato.market/car-service-mechanic-auto-shop-wordpress-theme', 'carservice')), esc_html__('QuanticaLabs', 'carservice'), esc_url(__('https://quanticalabs.com', 'carservice'))),
			"sticky_menu" => 0,
			"responsive" => 1,
			"scroll_top" => 1,
			"layout" => 'fullwidth',
			"layout_style" => '',
			"layout_image_overlay" => '',
			"style_selector" => 0,
			"direction" => "default",
			"collapsible_mobile_submenus" => 1,
			"google_api_code" => "",
			"google_recaptcha" => "",
			"google_recaptcha_comments" => "",
			"recaptcha_site_key" => "",
			"recaptcha_secret_key" => "",
			"ga_tracking_id" => "",
			"ga_tracking_code" => "",
			"cf_admin_name" => get_option("admin_email"),
			"cf_admin_email" => get_option("admin_email"),
			"cf_admin_name_from" => "",
			"cf_admin_email_from" => "",
			"cf_smtp_host" => "",
			"cf_smtp_username" => "",
			"cf_smtp_password" => "",
			"cf_smtp_port" => "",
			"cf_smtp_secure" => "",
			"cf_email_subject" => "Carservice WP: Contact from WWW",
			"cf_template" => "<html>
	<head>
	</head>
	<body>
		<div><b>Name</b>: [name]</div>
		<div><b>E-mail</b>: [email]</div>
		<div><b>Phone</b>: [phone]</div>
		<div><b>Message</b>: [message]</div>
		[form_data]
	</body>
</html>",
			"cf_name_message" => __("Please enter your name.", 'carservice'),
			"cf_email_message" => __("Please enter valid e-mail.", 'carservice'),
			"cf_phone_message" => __("Please enter your phone number.", 'carservice'),
			"cf_message_message" => __("Please enter your message.", 'carservice'),
			"cf_recaptcha_message" => __("Please verify captcha.", 'carservice'),
			"cf_terms_message" => __("Checkbox is required.", 'carservice'),
			"cf_thankyou_message" => __("Thank you for contacting us", 'carservice'),
			"cf_error_message" => __("Sorry, we can't send this message", 'carservice'),
			"cf_name_message_comments" => __("Please enter your name.", 'carservice'),
			"cf_email_message_comments" => __("Please enter valid e-mail.", 'carservice'),
			"cf_comment_message_comments" => __("Please enter your message.", 'carservice'),
			"cf_recaptcha_message_comments" => __("Please verify captcha.", 'carservice'),
			"cf_terms_message_comments" => __("Checkbox is required.", 'carservice'),
			"cf_thankyou_message_comments" => __("Your comment has been added.", 'carservice'),
			"cf_error_message_comments" => __("Error while adding comment.", 'carservice'),
			"site_background_color" => '',
			"main_color" => '',
			"header_top_sidebar" => '',
			"primary_font" => '',
			"primary_font_custom" => ''
		);
		add_option($themename . "_options", $theme_options);
		
		add_option("wpb_js_content_types", array(
			"page",
			"ql_galleries",
			"ql_services",
			"ql_team")
		);
		
		$admin_role = get_role("administrator");
		$admin_role->add_cap("vc_access_rules_post_types", "custom" );
		$admin_role->add_cap("vc_access_rules_post_types/post");
		$admin_role->add_cap("vc_access_rules_post_types/page");
		$admin_role->add_cap("vc_access_rules_post_types/ql_galleries");
		$admin_role->add_cap("vc_access_rules_post_types/ql_services");
		$admin_role->add_cap("vc_access_rules_post_types/ql_team");
		add_option("carservice_vc_access_rules", 1);
		
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
		add_option($themename . "_installed", 1);
	}
	//set default cost calculator options
	if(is_plugin_active("ql-cost-calculator/ql-cost-calculator.php"))
	{
		if(!get_option($themename . "cost_calculator_installed"))
		{
			$cost_calculator_global_form_options = array(
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
				"primary_font" => "Open Sans:regular",
				"primary_font_subset" => array("latin", "latin-ext"),
				"secondary_font_custom" => "",
				"secondary_font" => "",
				"secondary_font_subset" => "",
				"send_email" => 1,
				"save_calculation" => 1,
				"calculation_status" => "draft",
				"google_recaptcha" => 0,
				"recaptcha_site_key" => "",
				"recaptcha_secret_key" => "",
				"wpbakery_noconflict" => ""
			);
			update_option("cost_calculator_global_form_options", $cost_calculator_global_form_options);
			add_option($themename . "cost_calculator_installed", 1);
		}
	}
	
	//Make theme available for translation
	//Translations can be filed in the /languages/ directory
	load_theme_textdomain('carservice', get_template_directory() . '/languages');
	
	//woocommerce
	add_theme_support("woocommerce", array(
		'gallery_thumbnail_image_width' => 150)
	);
	add_theme_support("wc-product-gallery-zoom");
	add_theme_support("wc-product-gallery-lightbox");
	add_theme_support("wc-product-gallery-slider");
	
	//register thumbnails
	add_theme_support("post-thumbnails");
	
	add_image_size("blog-post-thumb", 870, 580, true);
	add_image_size("gallery-thumb", 570, 380, true);
	add_image_size("large-thumb", 960, 680, true);
	add_image_size("big-thumb", 480, 320, true);
	add_image_size("medium-thumb", 390, 260, true);
	add_image_size("small-thumb", 270, 180, true);
	add_image_size("tiny-thumb", 90, 90, true);
	
	//enable custom background
	add_theme_support("custom-background"); //3.4
	//add_custom_background(); //deprecated
	
	//enable feed links
	add_theme_support("automatic-feed-links");
	
	//title tag
	add_theme_support("title-tag");
	
	//gutenberg
	add_theme_support("wp-block-styles");
	add_theme_support("align-wide");
	add_theme_support("editor-color-palette", array(
		array(
			'name' => __("carservice blue", 'carservice'),
			'slug' => 'carservice-blue',
			'color' => '#6b9e69',
		),
		array(
			'name' => __("carservice turquoise", 'carservice' ),
			'slug' => 'carservice-turquoise',
			'color' => '#5FC7AE',
		),
		array(
			'name' => __("carservice orange", 'carservice' ),
			'slug' => 'carservice-orange',
			'color' => '#F68220',
		),
		array(
			'name' => __("carservice green", 'carservice' ),
			'slug' => 'carservice-green',
			'color' => '#82B541',
		),
		array(
			'name' => __("carservice light blue", 'carservice' ),
			'slug' => 'carservice-light-blue',
			'color' => '#66A1C3',
		)
	));
	
	//register menus
	if(function_exists("register_nav_menu"))
	{
		register_nav_menu("main-menu", "Main Menu");
	}
	
	//custom theme filters
	add_filter('upload_mimes', 'cs_custom_upload_files');
	//using shortcodes in sidebar
	add_filter("widget_text", "do_shortcode");
	add_filter("image_size_names_choose", "cs_theme_image_sizes");
	add_filter('wp_list_categories','cs_category_count_span');
	add_filter('get_archives_link', 'cs_archive_count_span');
	add_filter('excerpt_more', 'cs_theme_excerpt_more', 99);
	add_filter('post_class', 'cs_check_image');
	add_filter('user_contactmethods', 'cs_contactmethods', 10, 1);
	add_filter('wp_title', 'cs_wp_title_filter', 10, 2);
	add_filter('site_transient_update_plugins', 'carservice_filter_update_vc_plugin', 10, 2);
	
	//custom theme woocommerce filters
	add_filter('woocommerce_pagination_args' , 'cs_woo_custom_override_pagination_args');
	add_filter('woocommerce_product_single_add_to_cart_text', 'cs_woo_custom_cart_button_text');
	add_filter('woocommerce_product_add_to_cart_text', 'cs_woo_custom_cart_button_text');
	add_filter('loop_shop_columns', 'cs_woo_custom_loop_columns');
	add_filter('woocommerce_product_description_heading', 'cs_woo_custom_product_description_heading');
	add_filter('woocommerce_checkout_fields' , 'cs_woo_custom_override_checkout_fields');
	add_filter('woocommerce_show_page_title', 'cs_woo_custom_show_page_title');
	add_filter('loop_shop_per_page', 'cs_loop_shop_per_page', 20);
	add_filter('woocommerce_review_gravatar_size', 'cs_woo_custom_review_gravatar_size');
	add_filter('theme_page_templates', 'cs_woocommerce_page_templates' , 11, 3);
		
	//custom theme actions
	if(!function_exists('_wp_render_title_tag')) 
		add_action('wp_head', 'cs_theme_slug_render_title');
	add_action("add_meta_boxes", "cs_theme_add_ql_services_custom_box");
	add_action("save_post", "cs_theme_save_ql_services_postdata");
	
	//custom theme woocommerce actions
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
	remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
	//remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
	add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 5);
	add_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 10);
	add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
	//add_action('woocommerce_after_shop_loop', 'woocommerce_result_count', 20);
	
	//phpMailer
	add_action('phpmailer_init', 'cs_phpmailer_init');
	
	//content width
	if(!isset($content_width)) 
		$content_width = 1050;
	
	//register sidebars
	if(function_exists("register_sidebar"))
	{
		//register custom sidebars
		$sidebars_list = get_posts(array( 
			'post_type' => $themename . '_sidebars',
			'posts_per_page' => '-1',
			'post_status' => 'publish',
			'orderby' => 'menu_order',
			'order' => 'ASC'
		));
		foreach($sidebars_list as $sidebar)
		{
			$before_widget = get_post_meta($sidebar->ID, "before_widget", true);
			$after_widget = get_post_meta($sidebar->ID, "after_widget", true);
			$before_title = get_post_meta($sidebar->ID, "before_title", true);
			$after_title = get_post_meta($sidebar->ID, "after_title", true);
			register_sidebar(array(
				"id" => $sidebar->post_name,
				"name" => $sidebar->post_title,
				'before_widget' => ($before_widget!='' && $before_widget!='empty' ? $before_widget : ''),
				'after_widget' => ($after_widget!='' && $after_widget!='empty' ? $after_widget : ''),
				'before_title' => ($before_title!='' && $before_title!='empty' ? $before_title : ''),
				'after_title' => ($after_title!='' && $after_title!='empty' ? $after_title : '')
			));
		}
	}
}
add_action("after_setup_theme", "cs_theme_after_setup_theme");
function cs_theme_switch_theme($theme_template)
{
	global $themename;
	delete_option($themename . "_installed");
}
add_action("switch_theme", "cs_theme_switch_theme");

/* --- phpMailer config --- */
function cs_phpmailer_init($mail)
{
	global $theme_options;
	$mail->CharSet='UTF-8';

	$smtp = $theme_options["cf_smtp_host"];
	if(!empty($smtp))
	{
		$mail->IsSMTP();
		$mail->SMTPAuth = true; 
		//$mail->SMTPDebug = 2;
		$mail->Host = $theme_options["cf_smtp_host"];
		$mail->Username = $theme_options["cf_smtp_username"];
		$mail->Password = $theme_options["cf_smtp_password"];
		if((int)$theme_options["cf_smtp_port"]>0)
			$mail->Port = (int)$theme_options["cf_smtp_port"];
		$mail->SMTPSecure = $theme_options["cf_smtp_secure"];
	}
}

function cs_custom_template_for_vc() 
{
    $data = array();
    $data['name'] = __('Single Post Template', 'carservice');
    $data['weight'] = 0;
    $data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/admin/images/visual_composer/layout.png');
    $data['custom_class'] = 'custom_template_for_vc_custom_template';
    $data['content'] = <<<CONTENT
        [vc_row top_margin="none" el_class="margin-top-70"][vc_column type="" top_margin="none" width="3/4"][single_post featured_image_size="default" show_post_title="1" show_post_featured_image="1" show_post_excerpt="0" show_post_categories="1" show_post_date="1" show_post_author="1" show_post_views="1" show_post_comments="1" show_leave_reply_button="1"][comments show_comments_form="1" show_comments_list="1" top_margin="page-margin-top"][/vc_column][vc_column type="cs-smart-column" top_margin="none" width="1/4"][call_to_action_box title="ONLINE APPOINTMENT" icon="percent" button_label="MAKE APPOINTMENT" button_url="#" top_margin="none"]Book your appointment now and get $5 discount.[/call_to_action_box][box_header title="LATEST POSTS" type="h6" bottom_border="1" top_margin="page-margin-top"][blog_small cs_pagination="0" items_per_page="3" offset="0" featured_image_size="default" ids="-" category="-" author="-" order_by="date" order="DESC" show_post_title="1" show_post_date="1" top_margin="none" el_class="margin-top-30" show_post_views="0"][box_header title="MOST VIEWED" type="h6" bottom_border="1" top_margin="page-margin-top"][blog_small cs_pagination="0" items_per_page="3" offset="0" featured_image_size="default" ids="-" category="-" author="-" order_by="views" order="DESC" show_post_title="1" show_post_date="0" top_margin="none" el_class="margin-top-30" show_post_views="1"][box_header title="TEXT WIDGET" type="h6" bottom_border="1" top_margin="page-margin-top"][vc_wp_text el_class="margin-top-24"]Here is a text widget settings ipsum lore tora dolor sit amet velum. Maecenas est velum, gravida <a href="#">vehicula dolor</a>[/vc_wp_text][vc_wp_categories options="" el_class="page-margin-top clearfix" title="CATEGORIES"][vc_wp_archives options="count" title="ARCHIVES" el_class="page-margin-top full-width clearfix"][vc_wp_tagcloud taxonomy="post_tag" title="TAGS" el_class="page-margin-top clearfix"][/vc_column][/vc_row]
CONTENT;
    vc_add_default_templates($data);
	
	$data = array();
    $data['name'] = __('Blog Template', 'carservice');
    $data['weight'] = 0;
    $data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/admin/images/visual_composer/layout.png');
    $data['custom_class'] = 'custom_template_for_vc_custom_template';
    $data['content'] = <<<CONTENT
        [vc_row top_margin="none" el_class="margin-top-70"][vc_column type="" top_margin="none" width="3/4"][blog cs_pagination="1" items_per_page="3" offset="0" featured_image_size="default" ids="-" category="-" author="-" order_by="date" order="DESC" show_post_title="1" show_post_excerpt="1" read_more="1" show_post_categories="1" show_post_author="1" show_post_date="1" show_post_views="1" show_post_comments="1" is_search_results="0" top_margin="none" cs_pagination="1" show_post_tags="0"][/vc_column][vc_column type="cs-smart-column" top_margin="none" width="1/4"][call_to_action_box title="ONLINE APPOINTMENT" icon="percent" button_label="MAKE APPOINTMENT" button_url="#" top_margin="none"]Book your appointment now and get $5 discount.[/call_to_action_box][box_header title="LATEST POSTS" type="h6" bottom_border="1" top_margin="page-margin-top"][blog_small cs_pagination="0" items_per_page="3" offset="0" featured_image_size="default" ids="-" category="-" author="-" order_by="date" order="DESC" show_post_title="1" show_post_date="1" top_margin="none" el_class="margin-top-30" show_post_views="0"][box_header title="MOST VIEWED" type="h6" bottom_border="1" top_margin="page-margin-top"][blog_small cs_pagination="0" items_per_page="3" offset="0" featured_image_size="default" ids="-" category="-" author="-" order_by="views" order="DESC" show_post_title="1" show_post_date="0" top_margin="none" el_class="margin-top-30" show_post_views="1"][box_header title="TEXT WIDGET" type="h6" bottom_border="1" top_margin="page-margin-top"][vc_wp_text el_class="margin-top-24"]Here is a text widget settings ipsum lore tora dolor sit amet velum. Maecenas est velum, gravida <a href="#">vehicula dolor</a>[/vc_wp_text][vc_wp_categories options="" el_class="page-margin-top clearfix" title="CATEGORIES"][vc_wp_archives options="count" title="ARCHIVES" el_class="page-margin-top full-width clearfix"][vc_wp_tagcloud taxonomy="post_tag" title="TAGS" el_class="page-margin-top clearfix"][/vc_column][/vc_row]
CONTENT;
    vc_add_default_templates($data);
	
	$data = array();
    $data['name'] = __('Search Page Template', 'carservice');
    $data['weight'] = 0;
    $data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/admin/images/visual_composer/layout.png');
    $data['custom_class'] = 'custom_template_for_vc_custom_template';
    $data['content'] = <<<CONTENT
        [vc_row type="" top_margin="none" el_class="margin-top-70"][vc_column type="" top_margin="none" width="3/4"][blog cs_pagination="1" items_per_page="3" offset="0" featured_image_size="default" ids="-" category="-" author="-" order_by="date" order="DESC" show_post_title="1" show_post_excerpt="1" read_more="1" show_post_categories="1" show_post_author="1" show_post_date="1" show_post_views="1" show_post_comments="1" is_search_results="1" top_margin="none" cs_pagination="1" show_post_tags="0"][/vc_column][vc_column type="cs-smart-column" top_margin="none" width="1/4"][call_to_action_box title="ONLINE APPOINTMENT" icon="percent" button_label="MAKE APPOINTMENT" button_url="#" top_margin="none"]Book your appointment now and get $5 discount.[/call_to_action_box][box_header title="LATEST POSTS" type="h6" bottom_border="1" top_margin="page-margin-top"][blog_small cs_pagination="0" items_per_page="3" offset="0" featured_image_size="default" ids="-" category="-" author="-" order_by="date" order="DESC" show_post_title="1" show_post_date="1" top_margin="none" el_class="margin-top-30" show_post_views="0"][box_header title="MOST VIEWED" type="h6" bottom_border="1" top_margin="page-margin-top"][blog_small cs_pagination="0" items_per_page="3" offset="0" featured_image_size="default" ids="-" category="-" author="-" order_by="views" order="DESC" show_post_title="1" show_post_date="0" top_margin="none" el_class="margin-top-30" show_post_views="1"][box_header title="TEXT WIDGET" type="h6" bottom_border="1" top_margin="page-margin-top"][vc_wp_text el_class="margin-top-24"]Here is a text widget settings ipsum lore tora dolor sit amet velum. Maecenas est velum, gravida <a href="#">vehicula dolor</a>[/vc_wp_text][vc_wp_categories options="" el_class="page-margin-top clearfix" title="CATEGORIES"][vc_wp_archives options="count" title="ARCHIVES" el_class="page-margin-top full-width clearfix"][vc_wp_tagcloud taxonomy="post_tag" title="TAGS" el_class="page-margin-top clearfix"][/vc_column][/vc_row]
CONTENT;
    vc_add_default_templates($data);
	
	$data = array();
    $data['name'] = __('Single Gallery Template', 'carservice');
    $data['weight'] = 0;
    $data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/admin/images/visual_composer/layout.png');
    $data['custom_class'] = 'custom_template_for_vc_custom_template';
    $data['content'] = <<<CONTENT
        [vc_row top_margin="none" el_class="margin-top-70"][vc_column type="" top_margin="none" width="1/1"][single_gallery top_margin="none"][/vc_column][/vc_row]
CONTENT;
    vc_add_default_templates($data);
	
	$data = array();
    $data['name'] = __('Single Service Template', 'carservice');
    $data['weight'] = 0;
    $data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/admin/images/visual_composer/layout.png');
    $data['custom_class'] = 'custom_template_for_vc_custom_template';
    $data['content'] = <<<CONTENT
        [vc_row el_class="margin-top-70"][vc_column width="1/4"][vc_wp_custommenu nav_menu="27" el_class="vertical-menu"][call_to_action_box title="ONLINE APPOINTMENT" icon="percent" button_label="MAKE APPOINTMENT" button_url="#" top_margin="page-margin-top" text=""]Book your appointment now and get $5 discount.[/call_to_action_box][box_header title="DOWNLOAD" type="h6" bottom_border="1" top_margin="page-margin-top"][vc_btn type="action" icon="arrow-circle-down" title="Download Brochure" url="#" extraclass="margin-top-30"][vc_btn type="action" icon="arrow-circle-down" title="Download Summary" url="#"][/vc_column][vc_column width="3/4"][single_service show_social_icons="1" show_twitter="1" show_facebook="1" show_linkedin="1" show_skype="1" show_googleplus="1" show_instagram="1"][/vc_column][/vc_row]
CONTENT;
    vc_add_default_templates($data);
	
	$data = array();
    $data['name'] = __('Single Team Member Template', 'carservice');
    $data['weight'] = 0;
    $data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/admin/images/visual_composer/layout.png');
    $data['custom_class'] = 'custom_template_for_vc_custom_template';
    $data['content'] = <<<CONTENT
        [vc_row type="full-width" top_margin="page-margin-top-section"][vc_column][single_team][/vc_column][/vc_row]
CONTENT;
    vc_add_default_templates($data);
	
	$data = array();
    $data['name'] = __('Team Member Page Layout', 'carservice');
    $data['weight'] = 0;
    $data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/admin/images/visual_composer/layout.png');
    $data['custom_class'] = 'custom_template_for_vc_custom_template';
    $data['content'] = <<<CONTENT
        [vc_row][vc_column width="1/3"][team_member_box featured_image="1121" headers="1" headers_links="1" headers_border="0" show_subtitle="1" show_excerpt="0" show_social_icons="1" show_featured_image="1" featured_image_links="0"][/vc_column][vc_column width="1/3"][box_header title="RESUME" type="h3" bottom_border="1"][vc_raw_html el_class="align-left margin-top-40"]JTNDdGFibGUlM0UlMEElMDklM0N0Ym9keSUzRSUwQSUwOSUwOSUzQ3RyJTNFJTBBJTA5JTA5JTA5JTNDdGQlM0VOYW1lJTNBJTIwTWFyayUyMFdoaWxiZXJnJTNDJTJGdGQlM0UlMEElMDklMDklM0MlMkZ0ciUzRSUwQSUwOSUwOSUzQ3RyJTNFJTBBJTA5JTA5JTA5JTNDdGQlM0VEYXRlJTIwb2YlMjBiaXJ0aCUzQSUyMDAyJTIwTWF5JTIwMTk2OCUzQyUyRnRkJTNFJTBBJTA5JTA5JTNDJTJGdHIlM0UlMEElMDklMDklM0N0ciUzRSUwQSUwOSUwOSUwOSUzQ3RkJTNFQWRkcmVzcyUzQSUyMDI3MiUyMExpbmRlbiUyMEF2ZW51ZSUyQyUyMFdpbnRlciUyMFBhcmslM0MlMkZ0ZCUzRSUwQSUwOSUwOSUzQyUyRnRyJTNFJTBBJTA5JTA5JTNDdHIlM0UlMEElMDklMDklMDklM0N0ZCUzRUVtYWlsJTNBJTIwJTNDYSUyMGhyZWYlM0QlMjdtYWlsdG8lM0FtYXJrLndoaWxiZXJnJTQwbWFpbC5jb20lMjclM0VtYXJrLndoaWxiZXJnJTQwbWFpbC5jb20lM0MlMkZhJTNFJTNDJTJGdGQlM0UlMEElMDklMDklM0MlMkZ0ciUzRSUwQSUwOSUwOSUzQ3RyJTNFJTBBJTA5JTA5JTA5JTNDdGQlM0VQaG9uZSUzQSUyMCUyQjE0OSUyMDc1JTIwMjMlMjAyMjIlMjAzNSUzQyUyRnRkJTNFJTBBJTA5JTA5JTNDJTJGdHIlM0UlMEElMDklM0MlMkZ0Ym9keSUzRSUwQSUzQyUyRnRhYmxlJTNF[/vc_raw_html][/vc_column][vc_column width="1/3"][box_header title="PROFILE" type="h3" bottom_border="1"][vc_column_text el_class="margin-top-34"]We offer a full range of garage services to vehicle owners located in Tucson area. All mechanic services are performed by highly qualified mechanics. We can handle any car problem.

We offer full range of garage services to vehicle owners in Tucson. Our professionals know how to handle a wide range of car services. Whether you drive a passenger car or medium sized truck or SUV, our mechanics strive to ensure that your vehicle will be performing at its best before leaving our car shop.[/vc_column_text][/vc_column][/vc_row][vc_row type="full-width" top_margin="page-margin-top-section" el_class="top-border"][vc_column][vc_row_inner top_margin="page-margin-top-section"][vc_column_inner width="1/3"][featured_item icon="share-time" title="TURNKEY"]We combine quality workmanship, superior knowledge and low prices.[/featured_item][/vc_column_inner][vc_column_inner width="1/3"][featured_item icon="person" title="RESOURCES"]We have the experience, personel and resources to make.[/featured_item][/vc_column_inner][vc_column_inner width="1/3"][featured_item icon="checklist" title="SUPPLY"]Work with us involves a carefully planned series of steps.[/featured_item][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row type="full-width" top_margin="page-margin-top-section" css=".vc_custom_1456404788489{background-color: #f5f5f5 !important;}" el_class="page-padding-top-section padding-bottom-50"][vc_column][vc_row_inner][vc_column_inner][box_header title="MY SKILLS" type="h3" bottom_border="1"][/vc_column_inner][/vc_row_inner][vc_row_inner el_class="margin-top-40"][vc_column_inner width="1/2"][vc_progress_bar values="%5B%7B%22label%22%3A%22Tire%20and%20Wheel%20Services%22%2C%22value%22%3A%2295%22%7D%2C%7B%22label%22%3A%22Lube%2C%20Oil%20and%20Filters%22%2C%22value%22%3A%2272%22%7D%2C%7B%22label%22%3A%22Belts%20and%20Hoses%22%2C%22value%22%3A%2260%22%7D%5D" units="%"][/vc_column_inner][vc_column_inner width="1/2"][vc_progress_bar values="%5B%7B%22label%22%3A%22Engine%20Diagnostics%22%2C%22value%22%3A%2292%22%7D%2C%7B%22label%22%3A%22Brake%20Repair%22%2C%22value%22%3A%2275%22%7D%2C%7B%22label%22%3A%22Air%20Conditioning%22%2C%22value%22%3A%2289%22%7D%5D" units="%"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row el_class="page-padding-top-section"][vc_column width="2/3"][box_header title="MY EXPERIENCE" type="h3" bottom_border="1"][timeline_item label="2014-2015" title="HITACHI CONSTRUCT" subtitle="DIGGER OPERATOR" el_class="margin-top-40"]Paetos dignissim at cursus elefeind norma arcu. Pellentesque accumsan est in tempus etos ullamcorper, sem quam suscipit lacus maecenas tortor.[/timeline_item][timeline_item label="2012-2014" title="BRICK LTD" subtitle="FOREMAN"]Paetos dignissim at cursus elefeind norma arcu. Pellentesque accumsan est in tempus etos ullamcorper, sem quam suscipit lacus maecenas tortor.[/timeline_item][timeline_item label="2011-2012" title="HOME RENEW" subtitle="SENIOR FOREMAN"]Paetos dignissim at cursus elefeind norma arcu. Pellentesque accumsan est in tempus etos ullamcorper, sem quam suscipit lacus maecenas tortor.[/timeline_item][/vc_column][vc_column width="1/3"][box_header title="TESTIMONIALS" type="h3" bottom_border="1"][cs_testimonials type="small" pagination="1" testimonials_count="2" testimonials_icon0="camper" testimonials_title0="``I have taken several of the family cars here for the past several years and without exception the experiences have been outstanding. I would highly recommend this place to any one who wants great service, honest value, and really great people.``" testimonials_author0="MITCHEL SMITH" testimonials_author_subtext0="ENGINE DIAGNOSTICS" testimonials_icon1="engine-belt" testimonials_title1="``I have taken several of the family cars here for the past several years and without exception the experiences have been outstanding. I would highly recommend this place to any one who wants great service, honest value, and really great people.``" testimonials_author1="MITCHEL SMITH" testimonials_author_subtext1="BELTS AND HOSES" autoplay="0" duration="500" top_margin="page-margin-top" scroll="1" el_class="margin-top-40"][/vc_column][/vc_row]
CONTENT;
    vc_add_default_templates($data);
}
if(is_plugin_active("js_composer/js_composer.php") && function_exists("vc_set_default_editor_post_types"))
	add_action("vc_load_default_templates_action", "cs_custom_template_for_vc");

/* --- Theme Custom Filters & Actions Functions --- */
//add new mimes for upload dummy content files (code can be removed after dummy content import)
function cs_custom_upload_files($mimes) 
{
    $mimes = array_merge($mimes, array('xml' => 'application/xml'), array('json' => 'application/json'), array('zip' => 'application/zip'), array('gz' => 'application/x-gzip'), array('ico' => 'image/x-icon'));
    return $mimes;
}
function cs_theme_image_sizes($sizes)
{
	$addsizes = array(
		"blog-post-thumb" => __("Blog post thumbnail", 'carservice'),
		"gallery-thumb" => __("Gallery thumbnail", 'carservice'),
		"large-thumb" => __("Large thumbnail", 'carservice'),
		"big-thumb" => __("Big thumbnail", 'carservice'),
		"medium-thumb" => __("Medium thumbnail", 'carservice'),
		"small-thumb" => __("Small thumbnail", 'carservice'),
		"tiny-thumb" => __("Tiny thumbnail", 'carservice'),
	);
	$newsizes = array_merge($sizes, $addsizes);
	return $newsizes;
}
function cs_category_count_span($links) 
{
	$links = str_replace('</a> (', '<span>', $links);
	$links = str_replace(')', '</span></a>', $links);
	return $links;
}
function cs_archive_count_span($links) 
{
	$links = str_replace('</a>&nbsp;(', '<span>', $links);
	$links = str_replace(')', '</span></a>', $links);
	return $links;
}
//excerpt
function cs_theme_excerpt_more($more) 
{
	return '';
}
//sticky
function cs_check_image($class) 
{
	if(is_sticky())
		$class[] = 'sticky';
	return $class;
}
//user info
function cs_contactmethods($contactmethods) 
{
	$contactmethods['twitter'] = 'Twitter';
	$contactmethods['facebook'] = 'Facebook';
	$contactmethods['linkedin'] = 'Linkedin';
	$contactmethods['skype'] = 'Skype';
	$contactmethods['googleplus'] = 'Google Plus';
	$contactmethods['instagram'] = 'Instagram';
	return $contactmethods;
}
if(!function_exists('_wp_render_title_tag')) 
{
    function cs_theme_slug_render_title() 
	{
		echo ''. wp_title('-', true, 'right') . '';
    }
}
function cs_wp_title_filter($title, $sep)
{
	//$title = get_bloginfo('name') . " | " . (is_home() || is_front_page() ? get_bloginfo('description') : $title);
	return $title;
}
function carservice_filter_update_vc_plugin($date) 
{
    if(!empty($date->checked["js_composer/js_composer.php"]))
        unset($date->checked["js_composer/js_composer.php"]);
    if(!empty($date->response["js_composer/js_composer.php"]))
        unset($date->response["js_composer/js_composer.php"]);
    return $date;
}

//Adds a box to the main column on the Services edit screens
function cs_theme_add_ql_services_custom_box() 
{
	add_meta_box( 
        "ql_services_config",
        __("Options", 'carservice'),
        "cs_theme_inner_ql_services_custom_box_main",
        "ql_services",
		"side",
		"core"
    );
}

function cs_theme_inner_ql_services_custom_box_main($post)
{
	global $themename;
	//Use nonce for verification
	wp_nonce_field(plugin_basename( __FILE__ ), $themename . "_ql_services_noncename");
	
	//The actual fields for data entry
	$icon = get_post_meta($post->ID, "icon", true);
	$custom_url = get_post_meta($post->ID, "ql_services_custom_url", true);
	echo '
	<table>
		<tr>
			<td>
				<label for="icon">' . __('Icon', 'carservice') . ':</label>
			</td>
			<td>
				<select style="width: 120px;" id="ql_services_icon" name="ql_services_icon">
					<option value="-"' . (empty($icon) || $icon=="-" ? ' selected="selected"' : '') . '>' . __('none', 'carservice') . '</option>
					<option class="sl-small-air-conditioning" value="sl-small-air-conditioning"' . ($icon=="sl-small-air-conditioning" || $icon=="air-conditioning" ? ' selected="selected"' : '') . '>' . __('air-conditioning', 'carservice') . '</option>
					<option class="sl-small-alarm" value="sl-small-alarm"' . ($icon=="sl-small-alarm" || $icon=="alarm" ? ' selected="selected"' : '') . '>' . __('alarm', 'carservice') . '</option>
					<option class="sl-small-camper" value="sl-small-camper"' . ($icon=="sl-small-camper" || $icon=="camper" ? ' selected="selected"' : '') . '>' . __('camper', 'carservice') . '</option>
					<option class="sl-small-car" value="sl-small-car"' . ($icon=="sl-small-car" || $icon=="car" ? ' selected="selected"' : '') . '>' . __('car', 'carservice') . '</option>
					<option class="sl-small-car-2" value="sl-small-car-2"' . ($icon=="sl-small-car-2" || $icon=="car-2" ? ' selected="selected"' : '') . '>' . __('car-2', 'carservice') . '</option>
					<option class="sl-small-car-3" value="sl-small-car-3"' . ($icon=="sl-small-car-3" || $icon=="car-3" ? ' selected="selected"' : '') . '>' . __('car-3', 'carservice') . '</option>
					<option class="sl-small-car-audio" value="sl-small-car-audio"' . ($icon=="sl-small-car-audio" || $icon=="car-audio" ? ' selected="selected"' : '') . '>' . __('car-audio', 'carservice') . '</option>
					<option class="sl-small-car-battery" value="sl-small-car-battery"' . ($icon=="sl-small-car-battery" || $icon=="car-battery" ? ' selected="selected"' : '') . '>' . __('car-battery', 'carservice') . '</option>
					<option class="sl-small-car-check" value="sl-small-car-check"' . ($icon=="sl-small-car-check" || $icon=="car-check" ? ' selected="selected"' : '') . '>' . __('car-check', 'carservice') . '</option>
					<option class="sl-small-car-checklist" value="sl-small-car-checklist"' . ($icon=="sl-small-car-checklist" || $icon=="car-checklist" ? ' selected="selected"' : '') . '>' . __('car-checklist', 'carservice') . '</option>
					<option class="sl-small-car-fire" value="sl-small-car-fire"' . ($icon=="sl-small-car-fire" || $icon=="car-fire" ? ' selected="selected"' : '') . '>' . __('car-fire', 'carservice') . '</option>
					<option class="sl-small-car-fix" value="sl-small-car-fix"' . ($icon=="sl-small-car-fix" || $icon=="car-fix" ? ' selected="selected"' : '') . '>' . __('car-fix', 'carservice') . '</option>
					<option class="sl-small-car-key" value="sl-small-car-key"' . ($icon=="sl-small-car-key" || $icon=="car-key" ? ' selected="selected"' : '') . '>' . __('car-key', 'carservice') . '</option>
					<option class="sl-small-car-lock" value="sl-small-car-lock"' . ($icon=="sl-small-car-lock" || $icon=="car-lock" ? ' selected="selected"' : '') . '>' . __('car-lock', 'carservice') . '</option>
					<option class="sl-small-car-music" value="sl-small-car-music"' . ($icon=="sl-small-car-music" || $icon=="car-music" ? ' selected="selected"' : '') . '>' . __('car-music', 'carservice') . '</option>
					<option class="sl-small-car-oil" value="sl-small-car-oil"' . ($icon=="sl-small-car-oil" || $icon=="car-oil" ? ' selected="selected"' : '') . '>' . __('car-oil', 'carservice') . '</option>
					<option class="sl-small-car-setting" value="sl-small-car-setting"' . ($icon=="sl-small-car-setting" || $icon=="car-setting" ? ' selected="selected"' : '') . '>' . __('car-setting', 'carservice') . '</option>
					<option class="sl-small-car-wash" value="sl-small-car-wash"' . ($icon=="sl-small-car-wash" || $icon=="car-wash" ? ' selected="selected"' : '') . '>' . __('car-wash', 'carservice') . '</option>
					<option class="sl-small-car-wheel" value="sl-small-car-wheel"' . ($icon=="sl-small-car-wheel" || $icon=="car-wheel" ? ' selected="selected"' : '') . '>' . __('car-wheel', 'carservice') . '</option>
					<option class="sl-small-caution-fence" value="sl-small-caution-fence"' . ($icon=="sl-small-caution-fence" || $icon=="caution-fence" ? ' selected="selected"' : '') . '>' . __('caution-fence', 'carservice') . '</option>
					<option class="sl-small-certificate" value="sl-small-certificate"' . ($icon=="sl-small-certificate" || $icon=="certificate" ? ' selected="selected"' : '') . '>' . __('certificate', 'carservice') . '</option>
					<option class="sl-small-check" value="sl-small-check"' . ($icon=="sl-small-check" || $icon=="check" ? ' selected="selected"' : '') . '>' . __('check', 'carservice') . '</option>
					<option class="sl-small-check-2" value="sl-small-check-2"' . ($icon=="sl-small-check-2" || $icon=="check-2" ? ' selected="selected"' : '') . '>' . __('check-2', 'carservice') . '</option>
					<option class="sl-small-check-shield" value="sl-small-check-shield"' . ($icon=="sl-small-check-shield" || $icon=="check-shield" ? ' selected="selected"' : '') . '>' . __('check-shield', 'carservice') . '</option>
					<option class="sl-small-checklist" value="sl-small-checklist"' . ($icon=="sl-small-checklist" || $icon=="checklist" ? ' selected="selected"' : '') . '>' . __('checklist', 'carservice') . '</option>
					<option class="sl-small-clock" value="sl-small-clock"' . ($icon=="sl-small-clock" || $icon=="clock" ? ' selected="selected"' : '') . '>' . __('clock', 'carservice') . '</option>
					<option class="sl-small-coffee" value="sl-small-coffee"' . ($icon=="sl-small-coffee" || $icon=="coffee" ? ' selected="selected"' : '') . '>' . __('coffee', 'carservice') . '</option>
					<option class="sl-small-cog-double" value="sl-small-cog-double"' . ($icon=="sl-small-cog-double" || $icon=="cog-double" ? ' selected="selected"' : '') . '>' . __('cog-double', 'carservice') . '</option>
					<option class="sl-small-eco-car" value="sl-small-eco-car"' . ($icon=="sl-small-eco-car" || $icon=="eco-car" ? ' selected="selected"' : '') . '>' . __('eco-car', 'carservice') . '</option>
					<option class="sl-small-eco-fuel" value="sl-small-eco-fuel"' . ($icon=="sl-small-eco-fuel" || $icon=="eco-fuel" ? ' selected="selected"' : '') . '>' . __('eco-fuel', 'carservice') . '</option>
					<option class="sl-small-eco-fuel-barrel" value="sl-small-eco-fuel-barrel"' . ($icon=="sl-small-eco-fuel-barrel" || $icon=="eco-fuel-barrel" ? ' selected="selected"' : '') . '>' . __('eco-fuel-barrel', 'carservice') . '</option>
					<option class="sl-small-eco-globe" value="sl-small-eco-globe"' . ($icon=="sl-small-eco-globe" || $icon=="eco-globe" ? ' selected="selected"' : '') . '>' . __('eco-globe', 'carservice') . '</option>
					<option class="sl-small-eco-nature" value="sl-small-eco-nature"' . ($icon=="sl-small-eco-nature" || $icon=="eco-nature" ? ' selected="selected"' : '') . '>' . __('eco-nature', 'carservice') . '</option>
					<option class="sl-small-electric-wrench" value="sl-small-electric-wrench"' . ($icon=="sl-small-electric-wrench" || $icon=="electric-wrench" ? ' selected="selected"' : '') . '>' . __('electric-wrench', 'carservice') . '</option>
					<option class="sl-small-email" value="sl-small-email"' . ($icon=="sl-small-email" || $icon=="email" ? ' selected="selected"' : '') . '>' . __('email', 'carservice') . '</option>
					<option class="sl-small-engine-belt" value="sl-small-engine-belt"' . ($icon=="sl-small-engine-belt" || $icon=="engine-belt" ? ' selected="selected"' : '') . '>' . __('engine-belt', 'carservice') . '</option>
					<option class="sl-small-engine-belt-2" value="sl-small-engine-belt-2"' . ($icon=="sl-small-engine-belt-2" || $icon=="engine-belt-2" ? ' selected="selected"' : '') . '>' . __('engine-belt-2', 'carservice') . '</option>
					<option class="sl-small-facebook" value="sl-small-facebook"' . ($icon=="sl-small-facebook" || $icon=="facebook" ? ' selected="selected"' : '') . '>' . __('facebook', 'carservice') . '</option>
					<option class="sl-small-faq" value="sl-small-faq"' . ($icon=="sl-small-faq" || $icon=="faq" ? ' selected="selected"' : '') . '>' . __('faq', 'carservice') . '</option>
					<option class="sl-small-fax" value="sl-small-fax"' . ($icon=="sl-small-fax" || $icon=="fax" ? ' selected="selected"' : '') . '>' . __('fax', 'carservice') . '</option>
					<option class="sl-small-fax-2" value="sl-small-fax-2"' . ($icon=="sl-small-fax-2" || $icon=="fax-2" ? ' selected="selected"' : '') . '>' . __('fax-2', 'carservice') . '</option>
					<option class="sl-small-garage" value="sl-small-garage"' . ($icon=="sl-small-garage" || $icon=="garage" ? ' selected="selected"' : '') . '>' . __('garage', 'carservice') . '</option>
					<option class="sl-small-gauge" value="sl-small-gauge"' . ($icon=="sl-small-gauge" || $icon=="gauge" ? ' selected="selected"' : '') . '>' . __('gauge', 'carservice') . '</option>
					<option class="sl-small-gearbox" value="sl-small-gearbox"' . ($icon=="sl-small-gearbox" || $icon=="gearbox" ? ' selected="selected"' : '') . '>' . __('gearbox', 'carservice') . '</option>
					<option class="sl-small-google-plus" value="sl-small-google-plus"' . ($icon=="sl-small-google-plus" || $icon=="google-plus" ? ' selected="selected"' : '') . '>' . __('google-plus', 'carservice') . '</option>
					<option class="sl-small-gps" value="sl-small-gps"' . ($icon=="sl-small-gps" || $icon=="gps" ? ' selected="selected"' : '') . '>' . __('gps', 'carservice') . '</option>
					<option class="sl-small-headlight" value="sl-small-headlight"' . ($icon=="sl-small-headlight" || $icon=="headlight" ? ' selected="selected"' : '') . '>' . __('headlight', 'carservice') . '</option>
					<option class="sl-small-heating" value="sl-small-heating"' . ($icon=="sl-small-heating" || $icon=="heating" ? ' selected="selected"' : '') . '>' . __('heating', 'carservice') . '</option>
					<option class="sl-small-image" value="sl-small-image"' . ($icon=="sl-small-image" || $icon=="image" ? ' selected="selected"' : '') . '>' . __('image', 'carservice') . '</option>
					<option class="sl-small-images" value="sl-small-images"' . ($icon=="sl-small-images" || $icon=="images" ? ' selected="selected"' : '') . '>' . __('images', 'carservice') . '</option>
					<option class="sl-small-inflator-pump" value="sl-small-inflator-pump"' . ($icon=="sl-small-inflator-pump" || $icon=="inflator-pump" ? ' selected="selected"' : '') . '>' . __('inflator-pump', 'carservice') . '</option>
					<option class="sl-small-lightbulb" value="sl-small-lightbulb"' . ($icon=="sl-small-lightbulb" || $icon=="lightbulb" ? ' selected="selected"' : '') . '>' . __('lightbulb', 'carservice') . '</option>
					<option class="sl-small-location-map" value="sl-small-location-map"' . ($icon=="sl-small-location-map" || $icon=="location-map" ? ' selected="selected"' : '') . '>' . __('location-map', 'carservice') . '</option>
					<option class="sl-small-oil-can" value="sl-small-oil-can"' . ($icon=="sl-small-oil-can" || $icon=="oil-can" ? ' selected="selected"' : '') . '>' . __('oil-can', 'carservice') . '</option>
					<option class="sl-small-oil-gauge" value="sl-small-oil-gauge"' . ($icon=="sl-small-oil-gauge" || $icon=="oil-gauge" ? ' selected="selected"' : '') . '>' . __('oil-gauge', 'carservice') . '</option>
					<option class="sl-small-oil-station" value="sl-small-oil-station"' . ($icon=="sl-small-oil-station" || $icon=="oil-station" ? ' selected="selected"' : '') . '>' . __('oil-station', 'carservice') . '</option>
					<option class="sl-small-parking-sensor" value="sl-small-parking-sensor"' . ($icon=="sl-small-parking-sensor" || $icon=="parking-sensor" ? ' selected="selected"' : '') . '>' . __('parking-sensor', 'carservice') . '</option>
					<option class="sl-small-payment" value="sl-small-payment"' . ($icon=="sl-small-payment" || $icon=="payment" ? ' selected="selected"' : '') . '>' . __('payment', 'carservice') . '</option>
					<option class="sl-small-pen" value="sl-small-pen"' . ($icon=="sl-small-pen" || $icon=="pen" ? ' selected="selected"' : '') . '>' . __('pen', 'carservice') . '</option>
					<option class="sl-small-percent" value="sl-small-percent"' . ($icon=="sl-small-percent" || $icon=="percent" ? ' selected="selected"' : '') . '>' . __('percent', 'carservice') . '</option>
					<option class="sl-small-person" value="sl-small-person"' . ($icon=="sl-small-person" || $icon=="person" ? ' selected="selected"' : '') . '>' . __('person', 'carservice') . '</option>
					<option class="sl-small-phone" value="sl-small-phone"' . ($icon=="sl-small-phone" || $icon=="phone" ? ' selected="selected"' : '') . '>' . __('phone', 'carservice') . '</option>
					<option class="sl-small-phone-call" value="sl-small-phone-call"' . ($icon=="sl-small-phone-call" || $icon=="phone-call" ? ' selected="selected"' : '') . '>' . __('phone-call', 'carservice') . '</option>
					<option class="sl-small-phone-call-24h" value="sl-small-phone-call-24h"' . ($icon=="sl-small-phone-call-24h" || $icon=="phone-call-24h" ? ' selected="selected"' : '') . '>' . __('phone-call-24h', 'carservice') . '</option>
					<option class="sl-small-phone-circle" value="sl-small-phone-circle"' . ($icon=="sl-small-phone-circle" || $icon=="phone-circle" ? ' selected="selected"' : '') . '>' . __('phone-circle', 'carservice') . '</option>
					<option class="sl-small-piggy-bank" value="sl-small-piggy-bank"' . ($icon=="sl-small-piggy-bank" || $icon=="piggy-bank" ? ' selected="selected"' : '') . '>' . __('piggy-bank', 'carservice') . '</option>
					<option class="sl-small-quote" value="sl-small-quote"' . ($icon=="sl-small-quote" || $icon=="quote" ? ' selected="selected"' : '') . '>' . __('quote', 'carservice') . '</option>
					<option class="sl-small-road" value="sl-small-road"' . ($icon=="sl-small-road" || $icon=="road" ? ' selected="selected"' : '') . '>' . __('road', 'carservice') . '</option>
					<option class="sl-small-screwdriver" value="sl-small-screwdriver"' . ($icon=="sl-small-screwdriver" || $icon=="screwdriver" ? ' selected="selected"' : '') . '>' . __('screwdriver', 'carservice') . '</option>
					<option class="sl-small-seatbelt-lock" value="sl-small-seatbelt-lock"' . ($icon=="sl-small-seatbelt-lock" || $icon=="seatbelt-lock" ? ' selected="selected"' : '') . '>' . __('seatbelt-lock', 'carservice') . '</option>
					<option class="sl-small-service-24h" value="sl-small-service-24h"' . ($icon=="sl-small-service-24h" || $icon=="service-24h" ? ' selected="selected"' : '') . '>' . __('service-24h', 'carservice') . '</option>
					<option class="sl-small-share-time" value="sl-small-share-time"' . ($icon=="sl-small-share-time" || $icon=="share-time" ? ' selected="selected"' : '') . '>' . __('share-time', 'carservice') . '</option>
					<option class="sl-small-shopping-cart" value="sl-small-shopping-cart"' . ($icon=="sl-small-shopping-cart" || $icon=="shopping-cart" ? ' selected="selected"' : '') . '>' . __('shopping-cart', 'carservice') . '</option>
					<option class="sl-small-signal-warning" value="sl-small-signal-warning"' . ($icon=="sl-small-signal-warning" || $icon=="signal-warning" ? ' selected="selected"' : '') . '>' . __('signal-warning', 'carservice') . '</option>
					<option class="sl-small-sign-zigzag" value="sl-small-sign-zigzag"' . ($icon=="sl-small-sign-zigzag" || $icon=="sign-zigzag" ? ' selected="selected"' : '') . '>' . __('sign-zigzag', 'carservice') . '</option>
					<option class="sl-small-snow-crystal" value="sl-small-snow-crystal"' . ($icon=="sl-small-snow-crystal" || $icon=="snow-crystal" ? ' selected="selected"' : '') . '>' . __('snow-crystal', 'carservice') . '</option>
					<option class="sl-small-speed-gauge" value="sl-small-speed-gauge"' . ($icon=="sl-small-speed-gauge" || $icon=="speed-gauge" ? ' selected="selected"' : '') . '>' . __('speed-gauge', 'carservice') . '</option>
					<option class="sl-small-steering-wheel" value="sl-small-steering-wheel"' . ($icon=="sl-small-steering-wheel" || $icon=="steering-wheel" ? ' selected="selected"' : '') . '>' . __('steering-wheel', 'carservice') . '</option>
					<option class="sl-small-testimonials" value="sl-small-testimonials"' . ($icon=="sl-small-testimonials" || $icon=="testimonials" ? ' selected="selected"' : '') . '>' . __('testimonials', 'carservice') . '</option>
					<option class="sl-small-toolbox" value="sl-small-toolbox"' . ($icon=="sl-small-toolbox" || $icon=="toolbox" ? ' selected="selected"' : '') . '>' . __('toolbox', 'carservice') . '</option>
					<option class="sl-small-toolbox-2" value="sl-small-toolbox-2"' . ($icon=="sl-small-toolbox-2" || $icon=="toolbox-2" ? ' selected="selected"' : '') . '>' . __('toolbox-2', 'carservice') . '</option>
					<option class="sl-small-truck" value="sl-small-truck"' . ($icon=="sl-small-truck" || $icon=="truck" ? ' selected="selected"' : '') . '>' . __('truck', 'carservice') . '</option>
					<option class="sl-small-truck-tow" value="sl-small-truck-tow"' . ($icon=="sl-small-truck-tow" || $icon=="truck-tow" ? ' selected="selected"' : '') . '>' . __('truck-tow', 'carservice') . '</option>
					<option class="sl-small-tunning" value="sl-small-tunning"' . ($icon=="sl-small-tunning" || $icon=="tunning" ? ' selected="selected"' : '') . '>' . __('tunning', 'carservice') . '</option>
					<option class="sl-small-twitter" value="sl-small-twitter"' . ($icon=="sl-small-twitter" || $icon=="twitter" ? ' selected="selected"' : '') . '>' . __('twitter', 'carservice') . '</option>
					<option class="sl-small-user-chat" value="sl-small-user-chat"' . ($icon=="sl-small-user-chat" || $icon=="user-chat" ? ' selected="selected"' : '') . '>' . __('user-chat', 'carservice') . '</option>
					<option class="sl-small-video" value="sl-small-video"' . ($icon=="sl-small-video" || $icon=="video" ? ' selected="selected"' : '') . '>' . __('video', 'carservice') . '</option>
					<option class="sl-small-wallet" value="sl-small-wallet"' . ($icon=="sl-small-wallet" || $icon=="wallet" ? ' selected="selected"' : '') . '>' . __('wallet', 'carservice') . '</option>
					<option class="sl-small-wedding-car" value="sl-small-wedding-car"' . ($icon=="sl-small-wedding-car" || $icon=="wedding-car" ? ' selected="selected"' : '') . '>' . __('wedding-car', 'carservice') . '</option>
					<option class="sl-small-windshield" value="sl-small-windshield"' . ($icon=="sl-small-windshield" || $icon=="windshield" ? ' selected="selected"' : '') . '>' . __('windshield', 'carservice') . '</option>
					<option class="sl-small-wrench" value="sl-small-wrench"' . ($icon=="sl-small-wrench" || $icon=="wrench" ? ' selected="selected"' : '') . '>' . __('wrench', 'carservice') . '</option>
					<option class="sl-small-wrench-double" value="sl-small-wrench-double"' . ($icon=="sl-small-wrench-double" || $icon=="wrench-double" ? ' selected="selected"' : '') . '>' . __('wrench-double', 'carservice') . '</option>
					<option class="sl-small-wrench-screwdriver" value="sl-small-wrench-screwdriver"' . ($icon=="sl-small-wrench-screwdriver" || $icon=="wrench-screwdriver" ? ' selected="selected"' : '') . '>' . __('wrench-screwdriver', 'carservice') . '</option>
					<option class="sl-small-youtube" value="sl-small-youtube"' . ($icon=="sl-small-youtube" || $icon=="youtube" ? ' selected="selected"' : '') . '>' . __('youtube', 'carservice') . '</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label>' . __('Custom url:', 'carservice') . '</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="ql_services_custom_url" name="ql_services_custom_url" value="' . (!empty($custom_url) ? esc_attr($custom_url) : '') . '">
			</td>
		</tr>
	</table>';
}

//When the post is saved, saves our custom data
function cs_theme_save_ql_services_postdata($post_id) 
{
	global $themename;
	//verify if this is an auto save routine. 
	//if it is our form has not been submitted, so we dont want to do anything
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return;

	//verify this came from the our screen and with proper authorization,
	//because save_post can be triggered at other times
	if (!isset($_POST[$themename . '_ql_services_noncename']) || !wp_verify_nonce($_POST[$themename . '_ql_services_noncename'], plugin_basename( __FILE__ )))
		return;


	//Check permissions
	if(!current_user_can('edit_post', $post_id))
		return;

	//OK, we're authenticated: we need to find and save the data
	update_post_meta($post_id, "icon", $_POST["ql_services_icon"]);
	update_post_meta($post_id, "ql_services_custom_url", $_POST["ql_services_custom_url"]);
}

/* --- Theme WooCommerce Custom Filters Functions --- */
function cs_woo_custom_override_pagination_args($args) 
{
	$args['prev_text'] = __('&lsaquo;', 'carservice');
	$args['next_text'] = __('&rsaquo;', 'carservice');
	return $args;
}
function cs_woo_custom_cart_button_text() 
{
	return __('ADD TO CART', 'carservice');
}
if(!function_exists('loop_columns')) 
{
	function cs_woo_custom_loop_columns() 
	{
		return 3; // 3 products per row
	}
}
function cs_woo_custom_product_description_heading() 
{
    return '';
}
function cs_woo_custom_show_page_title()
{
	return false;
}
function cs_loop_shop_per_page($cols)
{
	return 6;
}
function cs_woo_custom_override_checkout_fields($fields) 
{
	$fields['billing']['billing_first_name']['placeholder'] = __("First Name", 'carservice');
	$fields['billing']['billing_last_name']['placeholder'] = __("Last Name", 'carservice');
	$fields['billing']['billing_company']['placeholder'] = __("Company Name", 'carservice');
	$fields['billing']['billing_email']['placeholder'] = __("Email Address", 'carservice');
	$fields['billing']['billing_phone']['placeholder'] = __("Phone", 'carservice');
	return $fields;
}
function cs_woo_custom_review_gravatar_size()
{
	return 100;
}

function cs_woocommerce_page_templates($page_templates, $class, $post)
{
	if(is_plugin_active('woocommerce/woocommerce.php'))
	{
		$shop_page_id = wc_get_page_id('shop');
		if($post && absint($shop_page_id) === absint($post->ID))
		{
			$page_templates["path-to-template/full-width.php"] = "Template Name";
		}
	}
 	return $page_templates;
}

//admin functions
cs_get_theme_file("/admin/functions.php");

//theme options
global $theme_options;
$theme_options = array(
	"favicon_url" => '',
	"logo_url" => '',
	"logo_text" => '',
	"footer_text" => '',
	"sticky_menu" => '',
	"responsive" => '',
	"scroll_top" => '',
	"style_selector" => '',
	"direction" => '',
	"collapsible_mobile_submenus" => '',
	"google_api_code" => '',
	"google_recaptcha" => '',
	"google_recaptcha_comments" => '',
	"recaptcha_site_key" => '',
	"recaptcha_secret_key" => '',
	"ga_tracking_id" => '',
	"ga_tracking_code" => '',
	"cf_admin_name" => '',
	"cf_admin_email" => '',
	"cf_admin_name_from" => '',
	"cf_admin_email_from" => '',
	"cf_smtp_host" => '',
	"cf_smtp_username" => '',
	"cf_smtp_password" => '',
	"cf_smtp_port" => '',
	"cf_smtp_secure" => '',
	"cf_email_subject" => '',
	"cf_template" => '',
	"cf_name_message" => '',
	"cf_email_message" => '',
	"cf_phone_message" => '',
	"cf_message_message" => '',
	"cf_recaptcha_message" => '',
	"cf_terms_message" => '',
	"cf_thankyou_message" => '',
	"cf_error_message" => '',
	"cf_name_message_comments" => '',
	"cf_email_message_comments" => '',
	"cf_comment_message_comments" => '',
	"cf_recaptcha_message_comments" => '',
	"cf_terms_message_comments" => '',
	"cf_thankyou_message_comments" => '',
	"cf_error_message_comments" => '',
	"main_color" => '',
	"header_top_sidebar" => '',
	"primary_font" => '',
	"primary_font_custom" => ''
);
$theme_options = cs_theme_stripslashes_deep(array_merge($theme_options, (array)get_option($themename . "_options")));

function cs_theme_enqueue_scripts()
{
	global $themename;
	global $theme_options;
	//style
	if($theme_options["primary_font"]!="" && $theme_options["primary_font_custom"]=="")
		wp_enqueue_style("google-font-primary", "//fonts.googleapis.com/css?family=" . urlencode($theme_options["primary_font"]) . (!empty($theme_options["primary_font_subset"]) ? "&subset=" . implode(",", $theme_options["primary_font_subset"]) : ""));
	else if($theme_options["primary_font_custom"]=="")
		wp_enqueue_style("google-font-opensans", "//fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,600,700,800&amp;subset=latin,latin-ext");
	wp_enqueue_style("reset", get_template_directory_uri() . "/style/reset.css");
	wp_enqueue_style("superfish", get_template_directory_uri() ."/style/superfish.css");
	wp_enqueue_style("prettyPhoto", get_template_directory_uri() ."/style/prettyPhoto.css");
	wp_enqueue_style("jquery-qtip", get_template_directory_uri() ."/style/jquery.qtip.css");
	wp_enqueue_style("odometer", get_template_directory_uri() ."/style/odometer-theme-default.css");
	wp_enqueue_style("animations", get_template_directory_uri() ."/style/animations.css");
	wp_enqueue_style("main-style", get_stylesheet_uri());
	if((int)$theme_options["responsive"])
		wp_enqueue_style("responsive", get_template_directory_uri() ."/style/responsive.css");
	else
		wp_enqueue_style("no-responsive", get_template_directory_uri() ."/style/no_responsive.css");

	if(is_plugin_active('woocommerce/woocommerce.php'))
	{
		wp_enqueue_style("woocommerce-custom", get_template_directory_uri() ."/woocommerce/style.css");
		if((int)$theme_options["responsive"])
			wp_enqueue_style("woocommerce-responsive", get_template_directory_uri() ."/woocommerce/responsive.css");
		else
			wp_dequeue_style("woocommerce-smallscreen");
		if(is_rtl())
			wp_enqueue_style("woocommerce-rtl", get_template_directory_uri() ."/woocommerce/rtl.css");
	}
	wp_enqueue_style("cs-streamline-small", get_template_directory_uri() ."/fonts/streamline-small/style.css");
	wp_enqueue_style("cs-template", get_template_directory_uri() ."/fonts/template/styles.css");
	wp_enqueue_style("cs-social", get_template_directory_uri() ."/fonts/social/styles.css");
	wp_enqueue_style("custom", get_template_directory_uri() ."/custom.css");
	//js
	wp_enqueue_script("jquery-ui-core", false, array("jquery"), false, true);
	wp_enqueue_script("jquery-ui-accordion", false, array("jquery"), false, true);
	wp_enqueue_script("jquery-ui-tabs", false, array("jquery"), false, true);
	wp_enqueue_script("jquery-ui-datepicker", false, array("jquery"), false, true);
	wp_enqueue_script("jquery-ui-selectmenu", false, array("jquery"), false, true);
	wp_enqueue_script("jquery-ui-slider", false, array("jquery"), false, true);
	wp_enqueue_script("jquery-ui-touch-punch", get_template_directory_uri() ."/js/jquery.ui.touch-punch.min.js", array("jquery"), false, true);
	wp_enqueue_script("jquery-imagesloaded", get_template_directory_uri() . "/js/jquery.imagesloaded-packed.js", array("jquery"), false, true);
	wp_enqueue_script("jquery-isotope", get_template_directory_uri() ."/js/jquery.isotope.min.js", array("jquery"), false, true);
	wp_enqueue_script("jquery-ba-bqq", get_template_directory_uri() ."/js/jquery.ba-bbq.min.js", array("jquery"), false, true);
	wp_enqueue_script("jquery-easing", get_template_directory_uri() ."/js/jquery.easing.1.4.1.min.js", array("jquery"), false, true);
	wp_enqueue_script("jquery-carouFredSel", get_template_directory_uri() ."/js/jquery.carouFredSel-6.2.1-packed.js", array("jquery"), false, true);
	wp_enqueue_script("jquery-touchSwipe", get_template_directory_uri() ."/js/jquery.touchSwipe.min.js", array("jquery"), false, true);
	wp_enqueue_script("jquery-transit", get_template_directory_uri() ."/js/jquery.transit.min.js", array("jquery"), false, true);
	wp_enqueue_script("jquery-hint", get_template_directory_uri() ."/js/jquery.hint.min.js", array("jquery"), false, true);
	wp_enqueue_script("jquery-qtip", get_template_directory_uri() ."/js/jquery.qtip.min.js", array("jquery"), false, true);
	wp_enqueue_script("jquery-block-ui", get_template_directory_uri() ."/js/jquery.blockUI.min.js", array("jquery"), false, true);
	wp_enqueue_script("jquery-prettyPhoto", get_template_directory_uri() ."/js/jquery.prettyPhoto.js", array("jquery"), false, true);
	wp_enqueue_script("jquery-odometer", get_template_directory_uri() ."/js/odometer.min.js", array("jquery", "theme-main" ), false, true);
	if(!empty($theme_options['ga_tracking_id']))
	{
		wp_enqueue_script("google-analytics", "https://www.googletagmanager.com/gtag/js?id=" . esc_attr($theme_options["ga_tracking_id"]), array(), false, true);
	}
	wp_register_script("google-maps-v3", "//maps.google.com/maps/api/js" . ($theme_options["google_api_code"]!="" ? "?key=" . esc_attr($theme_options["google_api_code"]) . "&amp;callback=carserviceInitMap" : ""), array(), false, true);
	wp_register_script("google-recaptcha-v2", "https://google.com/recaptcha/api.js", array(), false, true);
	if(function_exists("is_customize_preview") && !is_customize_preview())
		wp_enqueue_script("theme-main", get_template_directory_uri() ."/js/main.js", array("jquery", "jquery-ui-core", "jquery-ui-accordion", "jquery-ui-tabs"), false, true);
	if(!empty($theme_options['ga_tracking_id']))
	{
		$inline_script = "window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '" . $theme_options['ga_tracking_id'] . "');";
		wp_add_inline_script("google-analytics", $inline_script);
	}
	if(!empty($theme_options['ga_tracking_code']))
	{
		$inline_script = $theme_options['ga_tracking_code'];
		wp_add_inline_script("jquery", $inline_script);
	}
	
	//ajaxurl
	$data["ajaxurl"] = admin_url("admin-ajax.php");
	//themename
	$data["themename"] = $themename;
	//home url
	$data["home_url"] = esc_url(get_home_url());
	//is_rtl
	$data["is_rtl"] = ((is_rtl() || $theme_options["direction"]=='rtl') && ((isset($_COOKIE["cs_direction"]) && $_COOKIE["cs_direction"]!="LTR") || !isset($_COOKIE["cs_direction"]))) || (isset($_COOKIE["cs_direction"]) && $_COOKIE["cs_direction"]=="RTL") ? 1 : 0;
	
	//pass data to javascript
	$params = array(
		'l10n_print_after' => 'config = ' . json_encode($data) . ';'
	);
	wp_localize_script("theme-main", "config", $params);
}
add_action("wp_enqueue_scripts", "cs_theme_enqueue_scripts", 12);

//function to display number of posts
function cs_getPostViews($postID)
{
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count=='')
	{
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }
    return (int)$count;
}

//function to count views
function cs_setPostViews($postID) 
{
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count=='')
	{
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, 1);
    }
	else
	{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function cs_get_time_iso8601() 
{
	$offset = get_option('gmt_offset');
	$timezone = ($offset < 0 ? '-' : '+') . (abs($offset)<10 ? '0'.abs($offset) : abs($offset)) . '00' ;
	return get_the_time('Y-m-d\TH:i:s') . $timezone;					
}

function cs_theme_direction() 
{
	global $wp_locale, $theme_options;
	if(isset($theme_options['direction']) || (isset($_COOKIE["cs_direction"]) && ($_COOKIE["cs_direction"]=="LTR" || $_COOKIE["cs_direction"]=="RTL")))
	{
		if($theme_options['direction']=='default' && empty($_COOKIE["cs_direction"]))
			return;
		$wp_locale->text_direction = ($theme_options['direction']=='rtl' && ((isset($_COOKIE["cs_direction"]) && $_COOKIE["cs_direction"]!="LTR") || !isset($_COOKIE["cs_direction"])) || (isset($_COOKIE["cs_direction"]) && $_COOKIE["cs_direction"]=="RTL") ? 'rtl' : 'ltr');
	}
}
add_action("after_setup_theme", "cs_theme_direction");

function cs_get_theme_file($file)
{
	if(file_exists(get_stylesheet_directory() . $file))
        require_once(get_stylesheet_directory() . $file);
    else
        require_once(get_template_directory() . $file);
}

//carservice get_font_subsets
function cs_ajax_get_font_subsets()
{
	if($_POST["font"]!="")
	{
		$subsets = '';
		$fontExplode = explode(":", $_POST["font"]);
		$subsets_array = cs_get_google_font_subset($fontExplode[0]);
		
		foreach($subsets_array as $subset)
			$subsets .= '<option value="' . esc_attr($subset) . '">' . $subset . '</option>';
		
		echo "cs_start" . $subsets . "cs_end";
	}
	exit();
}
add_action('wp_ajax_carservice_get_font_subsets', 'cs_ajax_get_font_subsets');

/**
 * Returns array of Google Fonts
 * @return array of Google Fonts
 */
function cs_get_google_fonts()
{
	//get google fonts
	$fontsArray = get_option("carservice_google_fonts");
	//update if option doesn't exist or it was modified more than 2 weeks ago
	if($fontsArray===FALSE || count((array)$fontsArray)==0 || (time()-$fontsArray->last_update>2*7*24*60*60)) 
	{
		$google_api_url = 'https://quanticalabs.com/.tools/GoogleFont/font.txt';
		$fontsJson = wp_remote_retrieve_body(wp_remote_get($google_api_url, array('sslverify' => false)));
		$fontsArray = json_decode($fontsJson);
		if(isset($fontsArray))
		{
			$fontsArray->last_update = time();		
			update_option("carservice_google_fonts", $fontsArray);
		}
	}
	return $fontsArray;
}

/**
 * Returns array of subsets for provided Google Font
 * @param type $font - Google font
 * @return array of subsets for provided Google Font
 */
function cs_get_google_font_subset($font)
{
	$subsets = array();
	//get google fonts
	$fontsArray = cs_get_google_fonts();		
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

function cs_get_page_by_title($title, $post_type = "page")
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
/*add_filter('nav_menu_link_attributes', 'cs_theme_menu_atts_filter', 10, 3);
function cs_theme_menu_atts_filter($atts, $item, $args ) 
{
    if(in_array('menu-item-has-children', $item->classes))
	{
        $atts['aria-haspopup'] = 'true';
    }
    return $atts;
}*/
?>
