<?php
/*
Plugin Name: Car Service Theme Shortcodes
Plugin URI: https://1.envato.market/quanticalabs-portfolio
Description: Car Service Theme Shortcodes plugin
Author: QuanticaLabs
Author URI: https://1.envato.market/quanticalabs-portfolio
Version: 1.0
*/

function carservice_shortcodes_vc_init()
{
	if(function_exists("vc_map"))
	{
		add_shortcode("cs_contact_form", "cs_theme_contact_form_shortcode");
		add_shortcode("announcement_box", "cs_theme_announcement_box_shortcode");
		add_shortcode("blog", "cs_theme_blog");
		add_shortcode("blog_2_columns", "cs_theme_blog_2_columns");
		add_shortcode("blog_3_columns", "cs_theme_blog_3_columns");
		add_shortcode("blog_small", "cs_theme_blog_small");
		add_shortcode("call_to_action_box", "cs_theme_call_to_action_box");
		add_shortcode("comments", "cs_theme_comments");
		add_shortcode("featured_item", "cs_theme_featured_item");
		add_shortcode("items_list", "cs_theme_items_list");
		add_shortcode("item", "cs_theme_item");
		add_shortcode("cs_map", "cs_theme_map_shortcode");
		add_shortcode("our_clients_carousel", "cs_theme_our_clients_carousel");
		add_shortcode("cs_post_carousel", "cs_theme_post_carousel_shortcode");
		add_shortcode("box_header", "cs_theme_box_header");
		add_shortcode("vc_btn", "cs_theme_button");
		add_shortcode("single_gallery", "cs_theme_single_gallery");
		add_shortcode("single_post", "cs_theme_single_post");
		add_shortcode("single_service", "cs_theme_single_service");
		add_shortcode("single_team", "cs_theme_single_team");
		add_shortcode("team_member_box", "cs_theme_team_member_box");
		add_shortcode("cs_testimonials", "carservice_testimonials_shortcode");
		add_shortcode("timeline_item", "cs_theme_timeline_item");
		if(function_exists("vc_add_shortcode_param"))
		{
			vc_add_shortcode_param('dropdownmulti' , 'cs_dropdownmultiple_settings_field');
			vc_add_shortcode_param('hidden', 'cs_hidden_settings_field');
			vc_add_shortcode_param('readonly', 'cs_readonly_settings_field');
			vc_add_shortcode_param('listitem' , 'cs_listitem_settings_field');
			vc_add_shortcode_param('listitemwindow' , 'cs_listitemwindow_settings_field');
		}
	}
}
add_action("init", "carservice_shortcodes_vc_init");
?>