<?php
/*
Template Name: Team Member
*/
get_header();
$post_template_page_array = get_pages(array(
	'post_type' => 'page',
	'post_status' => 'publish',
	'meta_key' => '_wp_page_template',
	'meta_value' => 'single-ql_team.php',
	'hierarchical' => false
));
if(count($post_template_page_array))
{
	$post_template_page_array = array_values($post_template_page_array);
	$post_template_page = $post_template_page_array[0];
	if(count($post_template_page_array) && isset($post_template_page))
	{
		$vcBase = new Vc_Base();
		$vcBase->addShortcodesCustomCss($post_template_page->ID);
	}
}
?>
<div class="theme-page padding-bottom-70">
	<div class="vc_row wpb_row vc_row-fluid gray full-width page-header vertical-align-table">
		<div class="vc_row wpb_row vc_inner vc_row-fluid">
			<div class="page-header-left">
				<h1><?php the_title(); ?></h1>
			</div>
			<div class="page-header-right">
				<div class="bread-crumb-container">
					<label><?php _e("YOU ARE HERE:", 'carservice'); ?></label>
					<ul class="bread-crumb">
						<li>
							<a href="<?php echo esc_url(get_home_url()); ?>" title="<?php esc_attr_e('Home', 'carservice'); ?>">
								<?php _e('HOME', 'carservice'); ?>
							</a>
						</li>
						<?php
						if(count($post_template_page_array) && isset($post_template_page))
						{
							$parent_id = wp_get_post_parent_id($post_template_page->ID);
							if($parent_id)
							{
								$parent = get_post($parent_id);
								?>
								<li class="separator">
									&#47;
								</li>
								<li>
									<a href="<?php echo esc_url(get_permalink($parent)); ?>" title="<?php echo esc_attr($parent->post_title); ?>">
										<?php echo esc_html($parent->post_title); ?>
									</a>
								</li>
								<?php
							}
						}
						if(!empty(get_the_title()))
						{
						?>
						<li class="separator">
							&#47;
						</li>
						<li>
							<?php the_title(); ?>
						</li>
						<?php
						}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix">
		<?php
		if(function_exists("vc_map"))
		{
			if(count($post_template_page_array) && isset($post_template_page))
			{
				$vcBase = new Vc_Base();
				$vcBase->addShortcodesCustomCss($post_template_page->ID);
				echo wpb_js_remove_wpautop(apply_filters('the_content', $post_template_page->post_content));
				global $post;
				$post = $post_template_page;
				setup_postdata($post);
			}
			else
			{
				echo wpb_js_remove_wpautop(apply_filters('the_content', '[vc_row type="full-width" top_margin="page-margin-top-section"][vc_column][single_team][/vc_column][/vc_row]'));
			}
		}
		else
		{
			cs_get_theme_file("/shortcodes/single-team.php");
			echo '<div class="vc_row wpb_row vc_row-fluid full-width page-margin-top-section"><div class="vc_col-sm-12 wpb_column vc_column_container">' . cs_theme_single_team(array()) . '</div></div>';
		}
		?>
	</div>
</div>
<?php
get_footer();
?>