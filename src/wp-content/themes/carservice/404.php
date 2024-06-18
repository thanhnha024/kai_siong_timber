<?php
/*
Template Name: 404 page
*/
header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");
get_header();
?>
<div class="theme-page padding-bottom-66">
	<div class="clearfix top-border">
		<?php
		if(function_exists("vc_map"))
		{
			/*get page with 404 page template set*/
			$not_found_template_page_array = get_pages(array(
				'post_type' => 'page',
				'post_status' => 'publish',
				'number' => 1,
				'meta_key' => '_wp_page_template',
				'meta_value' => '404.php'
			));
			if(count($not_found_template_page_array))
			{
				$not_found_template_page = $not_found_template_page_array[0];
				if(count($not_found_template_page_array) && isset($not_found_template_page))
				{
					echo wpb_js_remove_wpautop(apply_filters('the_content', $not_found_template_page->post_content));
					global $post;
					$post = $not_found_template_page;
					setup_postdata($post);
				}
				else
					echo wpb_js_remove_wpautop(apply_filters('the_content', '[vc_row top_margin="none" el_class="margin-top-70"][vc_column type="" top_margin="none" width="1/1"][box_header title="OOPS." type="h2" bottom_border="0" top_margin="none"][box_header title="404" type="h1" bottom_border="0" top_margin="none"][vc_column_text el_class="description align-center"]We\'re sorry but something went wrong. Return to the <a title="GO TO HOME" href="#">homepage</a> or use the search below.[/vc_column_text][vc_wp_search el_class="page-margin-top"][/vc_column][/vc_row]'));
			}
			else
			{
				echo wpb_js_remove_wpautop(apply_filters('the_content', '[vc_row top_margin="none" el_class="margin-top-70"][vc_column type="" top_margin="none" width="1/1"][box_header title="OOPS." type="h2" bottom_border="0" top_margin="none"][box_header title="404" type="h1" bottom_border="0" top_margin="none"][vc_column_text el_class="description align-center"]We\'re sorry but something went wrong. Return to the <a title="GO TO HOME" href="#">homepage</a> or use the search below.[/vc_column_text][vc_wp_search el_class="page-margin-top"][/vc_column][/vc_row]'));		
			}
		}
		else
		{
			?>
			<div class="vc_row wpb_row vc_row-fluid margin-top-70">
				<div class="vc_col-sm-12 wpb_column vc_column_container ">
					<div class="wpb_wrapper">
						<h2><?php _e("OOPS.", 'carservice'); ?></h2><h1><?php _e("404", 'carservice'); ?></h1>
						<div class="wpb_text_column wpb_content_element  description align-center">
							<div class="wpb_wrapper">
								<p><?php echo sprintf(__("We&#8217;re sorry but something went wrong. Return to the <a title='GO TO HOME' href='%s'>homepage</a> or use the search below.", 'carservice'), esc_url(get_home_url())); ?></p>
							</div>
						</div>
						<div class="vc_wp_search wpb_content_element page-margin-top">
							<div class="widget widget_search">
								<div class="search-container">
									<a class="template-search" href="#" title="<?php esc_attr_e("Search", 'carservice'); ?>"></a>
									<form class="search-form" action="<?php echo esc_url(get_home_url()); ?>">
										<input name="s" class="search-input hint" type="text" value="<?php esc_attr_e('Search...', 'carservice'); ?>" placeholder="<?php esc_attr_e('Search...', 'carservice'); ?>">
										<fieldset class="search-submit-container">
											<span class="template-search"></span>
											<input type="submit" class="search-submit" value="">
										</fieldset>
									</form>
								</div>
							</div>
						</div>
					</div> 
				</div> 
			</div>
			<?php
		}
		?>
	</div>
</div>
<?php
get_footer(); 
?>
