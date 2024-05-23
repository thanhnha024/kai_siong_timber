<?php
get_header();
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
						$parent_id = wp_get_post_parent_id(get_the_ID());
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
	<div class="clearfix<?php echo (function_exists("has_blocks") && has_blocks() ? ' has-gutenberg-blocks' : '');?>">
		<?php
		if(have_posts()) : while (have_posts()) : the_post();
			if(!function_exists("vc_map") && !has_blocks())
			{
				echo '<div class="vc_row wpb_row vc_row-fluid page-margin-top padding-bottom-70 single-page">';
			}
			the_content();
			cs_get_theme_file("/comments-form.php");	
			if(comments_open())
			{
			?>
				<div class="comments-list-container clearfix page-margin-top">
			<?php
			}
			comments_template();
			if(comments_open())
			{
			?>
				</div>
			<?php
			}
			if(!function_exists("vc_map") && !has_blocks())
			{
				echo '</div>';
			}
		endwhile; endif;
		?>
	</div>
</div>
<?php
get_footer(); 
?>