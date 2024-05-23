<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php global $theme_options; ?>

<head>
	<!--meta-->
	<meta http-equiv="content-type" content="text/html; charset=<?php echo esc_attr(get_bloginfo("charset")); ?>" />
	<meta name="generator" content="WordPress <?php echo esc_attr(get_bloginfo("version")); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.2" />
	<meta name="description" content="<?php echo esc_html(get_bloginfo('description')); ?>" />
	<meta name="format-detection" content="telephone=no" />
	<!--style-->
	<link rel="alternate" type="application/rss+xml" title="<?php esc_attr_e('RSS 2.0', 'carservice'); ?>" href="<?php echo esc_url(get_bloginfo("rss2_url")); ?>" />
	<link rel="pingback" href="<?php echo esc_url(get_bloginfo("pingback_url")); ?>" />
	<?php
	if (!function_exists('has_site_icon') || !has_site_icon()) {
	?>
		<link rel="shortcut icon" href="<?php echo (empty($theme_options["favicon_url"]) ? esc_url(get_template_directory_uri() . "/images/favicon.ico") : esc_url($theme_options["favicon_url"])); ?>" />
	<?php
	}
	wp_head();
	$main_color = (isset($_COOKIE['cs_main_color']) ? $_COOKIE['cs_main_color'] : $theme_options['main_color']);
	if ($theme_options["site_background_color"] != "" || $main_color != "" || $theme_options["primary_font_custom"] != "" || $theme_options["primary_font"] != "")
		cs_get_theme_file("/custom_colors.php");
	?>
</head>
<?php
$image_overlay = ((!isset($_COOKIE['cs_image_overlay']) && $theme_options['layout_image_overlay'] == 'overlay') || ((isset($_COOKIE['cs_image_overlay']) && $_COOKIE['cs_image_overlay'] == 'overlay') || (!isset($_COOKIE['cs_image_overlay']) && $theme_options['layout_image_overlay'] == '')) ? ' overlay' : '');
$layout_style_class = (isset($_COOKIE['cs_layout']) && $_COOKIE['cs_layout'] == "boxed" ? (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style'] != "" ? $_COOKIE['cs_layout_style'] . $image_overlay : 'image-1' . $image_overlay) : (isset($theme_options['layout']) && $theme_options['layout'] == "boxed" ? (isset($theme_options['layout_style']) && $theme_options['layout_style'] != "" ? $theme_options['layout_style'] . (substr($theme_options['layout_style'], 0, 5) == "image" && isset($theme_options['layout_image_overlay']) && $theme_options['layout_image_overlay'] != "0" ? $image_overlay : '') : 'image-1' . $image_overlay) : ''));
?>

<body <?php body_class(($layout_style_class != "color_preview" ? esc_attr($layout_style_class) : "")); ?>>
	<div class="site-container<?php echo (isset($_COOKIE['cs_layout']) ? ($_COOKIE['cs_layout'] == "boxed" ? ' boxed' : '') : ($theme_options['layout'] == "boxed" ? ' boxed' : '')); ?>">
		<?php
		if ($theme_options["header_top_sidebar"] != "") {
		?>
			<div class="header-top-bar-container clearfix">
				<?php
				$sidebar = get_post($theme_options["header_top_sidebar"]);
				if (isset($sidebar) && !(int)get_post_meta($sidebar->ID, "hidden", true) && is_active_sidebar($sidebar->post_name)) :
				?>
					<div class="header-top-bar<?php echo ((int)get_post_meta($sidebar->ID, "hide_on_mobiles", true) ? ' hide-on-mobiles' : ''); ?>">
						<?php
						dynamic_sidebar($sidebar->post_name);
						?>
					</div>
					<a href="#" class="header-toggle template-arrow-up"></a>
				<?php
				endif;
				?>
			</div>
		<?php
		}
		$menu_type = (isset($_COOKIE['cs_menu_type']) && $_COOKIE['cs_menu_type'] != "" ? ' ' . $_COOKIE['cs_menu_type'] : ((int)$theme_options["sticky_menu"] ? ' sticky' : ''));
		?>
		<!-- Header -->
		<div class="header-container<?php echo !empty($menu_type) ? esc_attr($menu_type) : ''; ?>">
			<div class="vertical-align-table">
				<div class="header clearfix">
					<div class="logo vertical-align-cell">
						<h1><a href="<?php echo esc_url(get_home_url()); ?>" title="<?php echo esc_attr(get_bloginfo("name")); ?>">
								<?php if ($theme_options["logo_url"] != "") : ?>
									<img src="<?php echo esc_url($theme_options["logo_url"]); ?>" alt="logo">
								<?php endif; ?>
								<?php if ($theme_options["logo_text"] != "") : ?>
									<?php echo $theme_options["logo_text"]; ?>
								<?php
								endif;
								?>
							</a></h1>
					</div>
					<?php
					//Get menu object
					$locations = get_nav_menu_locations();
					if (isset($locations["main-menu"])) {
						$main_menu_object = get_term($locations["main-menu"], "nav_menu");
						if (has_nav_menu("main-menu") && $main_menu_object->count > 0) {
					?>
							<a href="#" class="mobile-menu-switch vertical-align-cell">
								<span class="line"></span>
								<span class="line"></span>
								<span class="line"></span>
							</a>
							<div class="header-middle-bar-container">
								<?php
								$sidebar_search = get_post($theme_options["header_top_right_sidebar"]);
								if (isset($sidebar_search) && !(int)get_post_meta($sidebar_search->ID, "hidden", true) && is_active_sidebar($sidebar_search->post_name)) :
								?>
									<div class="search-container">
										<?php
										dynamic_sidebar($sidebar_search->post_name);
										?>
									</div>

								<?php endif; ?>
							</div>
							<div class="menu-container clearfix vertical-align-cell">
								<?php
								wp_nav_menu(array(
									"container" => "nav",
									"theme_location" => "main-menu",
									"menu_class" => "sf-menu"
								));
								?>
							</div>
							<div class="mobile-menu-container">
								<div class="mobile-menu-divider"></div>
								<?php
								wp_nav_menu(array(
									"container" => "nav",
									"theme_location" => "main-menu",
									"menu_class" => "mobile-menu" . (!isset($theme_options["collapsible_mobile_submenus"]) || (int)$theme_options["collapsible_mobile_submenus"] ? " collapsible-mobile-submenus" : ""),
									"walker" => (!isset($theme_options["collapsible_mobile_submenus"]) || (int)$theme_options["collapsible_mobile_submenus"] ? new Mobile_Menu_Walker_Nav_Menu() : '')
								));
								?>
							</div>
					<?php
						}
					}
					?>
				</div>
			</div>
		</div>
		<!-- /Header -->
