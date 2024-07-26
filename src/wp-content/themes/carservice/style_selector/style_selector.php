<link rel="stylesheet" property='stylesheet' type="text/css" href="<?php echo esc_url(get_template_directory_uri() . '/style_selector/style_selector.css'); ?>">
<?php
global $theme_options;
?>
<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri() . '/style_selector/style_selector.js'); ?>"></script>
<div class="style-selector<?php echo (isset($_COOKIE['cs_style_selector']) ? ' ' . esc_attr($_COOKIE['cs_style_selector']) : ' opened'); ?>">
	<div class="style-selector-icon">
		&nbsp;
	</div>
	<div class="style-selector-content">
		<h4><?php _e("Style Selector", 'carservice'); ?></h4>
		<ul>
			<li class="hide-on-mobile clearfix">
				<label><?php _e("Menu Type:", 'carservice'); ?></label>
				<select name="menu_type">
					<option value="default"<?php echo (!isset($_COOKIE['cs_menu_type']) || $_COOKIE['cs_menu_type']=="default" ? ' selected="selected"' : ''); ?>><?php _e("Default", 'carservice'); ?></option>
					<option value="sticky"<?php echo ((!isset($_COOKIE['cs_menu_type']) && (int)$theme_options['sticky_menu']==1) || (isset($_COOKIE['cs_menu_type']) && $_COOKIE['cs_menu_type']=="sticky") ? ' selected="selected"' : ''); ?>><?php _e("Sticky", 'carservice'); ?></option>
				</select>
			</li>
			<li class="hide-on-mobile clearfix">
				<label><?php _e("Layout Style:", 'carservice'); ?></label>
				<select name="layout_style">
					<option value="fullwidth"<?php echo (!isset($_COOKIE['cs_layout']) || (isset($_COOKIE['cs_layout']) && $_COOKIE['cs_layout']=="") ? ' selected="selected"' : ''); ?>><?php _e("Full width", 'carservice'); ?></option>
					<option value="boxed"<?php echo ((!isset($_COOKIE['cs_layout']) && $theme_options['layout']=="boxed") || (isset($_COOKIE['cs_layout']) && $_COOKIE['cs_layout']=="boxed") ? ' selected="selected"' : ''); ?>><?php _e("Boxed", 'carservice'); ?></option>
				</select>
			</li>
			<li class="clearfix">
				<label><?php _e("Direction:", 'carservice'); ?></label>
				<select name="style_selector_direction">
					<option value="LTR"<?php echo (!isset($_COOKIE['cs_direction']) || $_COOKIE['cs_direction']=="LTR" ? ' selected="selected"' : ''); ?>><?php _e("LTR", 'carservice'); ?></option>
					<option value="RTL"<?php echo ((!isset($_COOKIE['cs_direction']) && $theme_options["direction"]=="rtl") || (isset($_COOKIE['cs_direction']) && $_COOKIE['cs_direction']=="RTL") ? ' selected="selected"' : ''); ?>><?php _e("RTL", 'carservice'); ?></option>
				</select>
			</li>
			<li>
				<label class="single-label"><?php _e("Main Color (examples)", 'carservice'); ?></label>
				<ul class="layout-chooser for-main-color clearfix">
					<li<?php echo ((!isset($_COOKIE['cs_main_color']) && strtoupper($theme_options['main_color'])=='1E69B8') || (isset($_COOKIE['cs_main_color']) && strtoupper($_COOKIE['cs_main_color'])=='1E69B8') || (!isset($_COOKIE['cs_main_color']) && (!isset($theme_options['main_color']) || $theme_options['main_color']=="")) ? ' class="selected"' : '');?>>
						<a href="#" class="color-preview" style="background-color: #6b9e69;" data-color="1E69B8">	
							<span class="tick"></span>
						</a>
					</li>
					<li<?php echo ((!isset($_COOKIE['cs_main_color']) && $theme_options['main_color']=='5FC7AE') || (isset($_COOKIE['cs_main_color']) && $_COOKIE['cs_main_color']=='5FC7AE') ? ' class="selected"' : '');?>>
						<a href="#" class="color-preview" style="background-color: #5FC7AE;" data-color="5FC7AE">	
							<span class="tick"></span>
						</a>
					</li>
					<li<?php echo ((!isset($_COOKIE['cs_main_color']) && $theme_options['main_color']=='F68220') || (isset($_COOKIE['cs_main_color']) && $_COOKIE['cs_main_color']=='F68220') ? ' class="selected"' : '');?>>
						<a href="#" class="color-preview" style="background-color: #F68220;" data-color="F68220">
							<span class="tick"></span>
						</a>
					</li>
					<li<?php echo ((!isset($_COOKIE['cs_main_color']) && $theme_options['main_color']=='82B541') || (isset($_COOKIE['cs_main_color']) && $_COOKIE['cs_main_color']=='82B541') ? ' class="selected"' : '');?>>
						<a href="#" class="color-preview" style="background-color: #82B541;" data-color="82B541">
							<span class="tick"></span>
						</a>
					</li>
					<li class="last<?php echo ((!isset($_COOKIE['cs_main_color']) && $theme_options['main_color']=='66A1C3') || (isset($_COOKIE['cs_main_color']) && $_COOKIE['cs_main_color']=='66A1C3') ? ' selected' : '') . '"';?>>
						<a href="#" class="color-preview" style="background-color: #66A1C3;" data-color="66A1C3">
							<span class="tick"></span>
						</a>
					</li>
				</ul>
			</li>
			<li class="clearfix hide-on-mobile">
				<label class="single-label"><?php _e("Boxed Layout Pattern", 'carservice'); ?></label>
				<ul class="layout-chooser">
					<li<?php echo ((!isset($_COOKIE['cs_layout_style']) && $theme_options['layout_style']=='pattern-1') || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='pattern-1') ? ' class="selected"' : ''); ?>>
						<a href="#" class="pattern-1">
							<span class="tick"></span>
						</a>
					</li>
					<li<?php echo ((!isset($_COOKIE['cs_layout_style']) && $theme_options['layout_style']=='pattern-2') || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='pattern-2') ? ' class="selected"' : ''); ?>>
						<a href="#" class="pattern-2">
							<span class="tick"></span>
						</a>
					</li>
					<li<?php echo ((!isset($_COOKIE['cs_layout_style']) && $theme_options['layout_style']=='pattern-3') || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='pattern-3') ? ' class="selected"' : ''); ?>>
						<a href="#" class="pattern-3">
							<span class="tick"></span>
						</a>
					</li>
					<li<?php echo ((!isset($_COOKIE['cs_layout_style']) && $theme_options['layout_style']=='pattern-4') || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='pattern-4') ? ' class="selected"' : ''); ?>>
						<a href="#" class="pattern-4">
							<span class="tick"></span>
						</a>
					</li>
					<li class="last<?php echo ((!isset($_COOKIE['cs_layout_style']) && $theme_options['layout_style']=='pattern-5') || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='pattern-5') ? ' selected' : '') . '"'; ?>>
						<a href="#" class="pattern-5">
							<span class="tick"></span>
						</a>
					</li>
					<li class="first<?php echo ((!isset($_COOKIE['cs_layout_style']) && $theme_options['layout_style']=='pattern-6') || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='pattern-6') ? ' selected' : ''); ?>">
						<a href="#" class="pattern-6">
							<span class="tick"></span>
						</a>
					</li>
					<li<?php echo ((!isset($_COOKIE['cs_layout_style']) && $theme_options['layout_style']=='pattern-7') || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='pattern-7') ? ' class="selected"' : ''); ?>>
						<a href="#" class="pattern-7">
							<span class="tick"></span>
						</a>
					</li>
					<li<?php echo ((!isset($_COOKIE['cs_layout_style']) && $theme_options['layout_style']=='pattern-8') || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='pattern-8') ? ' class="selected"' : ''); ?>>
						<a href="#" class="pattern-8">
							<span class="tick"></span>
						</a>
					</li>
					<li<?php echo ((!isset($_COOKIE['cs_layout_style']) && $theme_options['layout_style']=='pattern-9') || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='pattern-9') ? ' class="selected"' : ''); ?>>
						<a href="#" class="pattern-9">
							<span class="tick"></span>
						</a>
					</li>
					<li class="last<?php echo ((!isset($_COOKIE['cs_layout_style']) && $theme_options['layout_style']=='pattern-10') || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='pattern-10') ? ' selected' : '') . '"'; ?>>
						<a href="#" class="pattern-10">
							<span class="tick"></span>
						</a>
					</li>
				</ul>
			</li>
			<li class="clearfix hide-on-mobile">
				<label class="single-label"><?php _e("Boxed Layout Image", 'carservice'); ?></label>
				<ul class="layout-chooser">
					<li<?php echo ((!isset($_COOKIE['cs_layout_style']) && (!isset($theme_options['layout_style']) || $theme_options['layout_style']=='image-1')) || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='image-1') ? ' class="selected"' : ''); ?>>
						<a href="#" class="image-1">
							<span class="tick"></span>
						</a>
					</li>
					<li<?php echo ((!isset($_COOKIE['cs_layout_style']) && $theme_options['layout_style']=='image-2') || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='image-2') ? ' class="selected"' : ''); ?>>
						<a href="#" class="image-2">
							<span class="tick"></span>
						</a>
					</li>
					<li<?php echo ((!isset($_COOKIE['cs_layout_style']) && $theme_options['layout_style']=='image-3') || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='image-3') ? ' class="selected"' : ''); ?>>
						<a href="#" class="image-3">
							<span class="tick"></span>
						</a>
					</li>
					<li<?php echo ((!isset($_COOKIE['cs_layout_style']) && $theme_options['layout_style']=='image-4') || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='image-4') ? ' class="selected"' : ''); ?>>
						<a href="#" class="image-4">
							<span class="tick"></span>
						</a>
					</li>
					<li class="last<?php echo ((!isset($_COOKIE['cs_layout_style']) && $theme_options['layout_style']=='image-5') || (isset($_COOKIE['cs_layout_style']) && $_COOKIE['cs_layout_style']=='image-5') ? ' selected' : '') . '"'; ?>>
						<a href="#" class="image-5">
							<span class="tick"></span>
						</a>
					</li>
					<li class="first">
						<input type="checkbox"<?php echo ((!isset($_COOKIE['cs_image_overlay']) && $theme_options['layout_image_overlay']=='overlay') || ((isset($_COOKIE['cs_image_overlay']) && $_COOKIE['cs_image_overlay']=='overlay') || (!isset($_COOKIE['cs_image_overlay']) && $theme_options['layout_image_overlay']=='')) ? ' checked="checked"' : ''); ?> id="overlay"><label class="overlay-label" for="overlay"><?php _e("Overlay", 'carservice'); ?></label>
					</li>
				</ul>
			</li>
		</ul>
	</div>
</div>
