<?php
/*
Template Name: Single post
*/
get_header();
cs_setPostViews(get_the_ID());
$post_template_page_array = get_pages(array(
	'post_type' => 'page',
	'post_status' => 'publish',
	'meta_key' => '_wp_page_template',
	'meta_value' => 'single.php',
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
<div class="theme-page padding-bottom-66">
	<div class="vc_row wpb_row vc_row-fluid gray full-width page-header vertical-align-table">
		<div class="vc_row wpb_row vc_inner vc_row-fluid">
			<div class="page-header-left">
				<?php
				$blog_template_page = null;
				if(count($post_template_page_array) && isset($post_template_page))
				{
					$parent_id = wp_get_post_parent_id($post_template_page->ID);
					if($parent_id)
					{
						$blog_template_page = get_post($parent_id);
					}
				}
				if($blog_template_page==null)
				{
					$blog_template_page_array = get_pages(array(
						'post_type' => 'page',
						'post_status' => 'publish',
						'number' => 1,
						'meta_key' => '_wp_page_template',
						'meta_value' => 'template-blog.php',
						'sort_order' => 'ASC',
						'sort_column' => 'menu_order',
						'hierarchical' => false
					));
					if(count($blog_template_page_array))
					{
						$blog_template_page_array = array_values($blog_template_page_array);
						$blog_template_page = $blog_template_page_array[0];
					}
				}
				?><h1><?php
				if(isset($blog_template_page))
				{
					$blog_page = get_post($blog_template_page->ID);
					?>
					<a href="<?php echo esc_url(get_permalink($blog_page)); ?>" title="<?php echo esc_attr($blog_page->post_title); ?>">
						<?php echo esc_html($blog_page->post_title); ?>
					</a>
					<?php
				}
				else
				{
					echo __("BLOG", 'carservice');
				}
				?></h1>
			</div>
			<div class="page-header-right">
				<div class="bread-crumb-container">
					<label><?php _e("YOU ARE HERE:", 'carservice'); ?></label>
					<ul class="bread-crumb">
						<li>
							<a href="<?php echo esc_url(get_home_url()); ?>" title="<?php esc_attr_e('HOME', 'carservice'); ?>">
								<?php esc_html_e('HOME', 'carservice'); ?>
							</a>
						</li>
						<li class="separator">
							&#47;
						</li>
						<li>
						<?php
						if(isset($blog_template_page))
						{
							$blog_page = get_post($blog_template_page->ID);
							?>
							<a href="<?php echo esc_url(get_permalink($blog_page)); ?>" title="<?php echo esc_attr($blog_page->post_title); ?>">
								<?php echo esc_html($blog_page->post_title); ?>
							</a>
							<?php
						}
						else
						{
							echo __("BLOG", 'carservice');
						}
						?>
						</li>
						<?php
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
				echo wpb_js_remove_wpautop(apply_filters('the_content', '[vc_row top_margin="none" el_class="margin-top-70"][vc_column type="" top_margin="none" width="3/4"][single_post featured_image_size="default" show_post_title="1" show_post_featured_image="1" show_post_excerpt="0" show_post_categories="1" show_post_date="1" show_post_author="1" show_post_views="1" show_post_comments="1" show_leave_reply_button="1"][comments show_comments_form="1" show_comments_list="1" top_margin="page-margin-top"][/vc_column][vc_column type="cs-smart-column" top_margin="none" width="1/4"][call_to_action_box title="ONLINE APPOINTMENT" icon="percent" button_label="MAKE APPOINTMENT" button_url="#" top_margin="none"]Book your appointment now and get $5 discount.[/call_to_action_box][box_header title="LATEST POSTS" type="h6" bottom_border="1" top_margin="page-margin-top"][blog_small cs_pagination="0" items_per_page="3" offset="0" featured_image_size="default" ids="-" category="-" author="-" order_by="date" order="DESC" show_post_title="1" show_post_date="1" top_margin="none" el_class="margin-top-30" show_post_views="0"][box_header title="MOST VIEWED" type="h6" bottom_border="1" top_margin="page-margin-top"][blog_small cs_pagination="0" items_per_page="3" offset="0" featured_image_size="default" ids="-" category="-" author="-" order_by="views" order="DESC" show_post_title="1" show_post_date="0" top_margin="none" el_class="margin-top-30" show_post_views="1"][box_header title="TEXT WIDGET" type="h6" bottom_border="1" top_margin="page-margin-top"][vc_wp_text el_class="margin-top-24"]Here is a text widget settings ipsum lore tora dolor sit amet velum. Maecenas est velum, gravida <a href="#">vehicula dolor</a>[/vc_wp_text][vc_wp_categories options="" el_class="page-margin-top clearfix" title="CATEGORIES"][vc_wp_archives options="count" title="ARCHIVES" el_class="page-margin-top full-width clearfix"][vc_wp_tagcloud taxonomy="post_tag" title="TAGS" el_class="page-margin-top clearfix"][/vc_column][/vc_row]'));
			}
		}
		else
		{
			cs_get_theme_file("/shortcodes/single-post.php");
			echo '<div class="vc_row wpb_row vc_row-fluid margin-top-70"><div class="vc_col-sm-12 wpb_column vc_column_container">' . cs_theme_single_post(array()) . '</div></div>';
		}
		?>
	</div>
</div>
<?php
get_footer();
?>