<?php 
if(comments_open())
{
	global $terms_checkbox;
	global $terms_message;
	global $top_margin;
	global $theme_options;
	?>
<div class="vc_row wpb_row vc_inner<?php echo (!empty($top_margin) && $top_margin!='none' ? ' ' . esc_attr($top_margin) : ''); ?>">
	<h4 class="box-header">
		<?php _e("LEAVE A COMMENT", 'carservice'); ?>
	</h4>
	<?php
	if(get_option('comment_registration') && !is_user_logged_in())
	{
	?>
	<p><?php echo sprintf(__("You must be <a href='%s'>logged in</a> to post a comment.", 'carservice'), wp_login_url(esc_url(get_permalink()))); ?></p>
	<?php
	}
	else
	{
	?>
	<form class="comment-form margin-top-40" id="comment-form" method="post" action="#">
		<div class="vc_row vc_row-fluid wpb_row vc_inner">
			<fieldset class="vc_col-sm-6 wpb_column vc_column_container">
				<div class="block">
					<input class="text_input" name="name" type="text" value="<?php echo esc_html_e('Your Name *', 'carservice'); ?>" placeholder="<?php echo esc_html_e('Your Name *', 'carservice'); ?>">
				</div>
				<div class="block">
					<input class="text_input" name="email" type="text" value="<?php echo esc_html_e('Your Email *', 'carservice'); ?>" placeholder="<?php echo esc_html_e('Your Email *', 'carservice'); ?>">
				</div>
				<div class="block">
					<input class="text_input" name="website" type="text" value="<?php echo esc_html_e('Your Website', 'carservice'); ?>" placeholder="<?php echo esc_html_e('Your Website', 'carservice'); ?>">
				</div>
			</fieldset>
			<fieldset class="vc_col-sm-6 wpb_column vc_column_container">
				<div class="block">
					<textarea name="message" placeholder="<?php echo esc_html_e('Comment *', 'carservice'); ?>"><?php echo __('Comment *', 'carservice'); ?></textarea>
				</div>
			</fieldset>
		</div>
		<div class="vc_row wpb_row vc_inner margin-top-30">
			<div class="vc_col-sm-4 wpb_column vc_column_container">
				<p><?php _e("Your comment will be published within 24 hours.", 'carservice'); ?></p>
			</div>
			<div class="vc_col-sm-8 wpb_column vc_column_container <?php echo ((int)$theme_options["google_recaptcha_comments"] ? 'fieldset-with-recaptcha' : 'align-right');?>">
				<?php
				if((int)$terms_checkbox)
				{
				?>
					<div class="terms-container block">
						<input type="checkbox" name="terms" id="comment-formterms" value="1"><label for="comment-formterms"><?php echo urldecode(base64_decode($terms_message)); ?></label>
					</div>
					<div class="recaptcha-container">
				<?php
				}
				?>
				<div class="vc_row wpb_row vc_inner margin-top-20 padding-bottom-20<?php echo ((int)$theme_options["google_recaptcha_comments"] ? ' button-with-recaptcha' : '');?>">
					<a href="#cancel" id="cancel-comment" title="<?php echo esc_html_e('Cancel reply', 'carservice'); ?>"><?php echo __('Cancel reply', 'carservice'); ?></a>
					<a class="more submit-comment-form" href="#" title="<?php echo esc_html_e('POST COMMENT', 'carservice'); ?>"><span><?php echo __('POST COMMENT', 'carservice'); ?></span></a>
				</div>
				<?php
				if((int)$theme_options["google_recaptcha_comments"])
				{
					if($theme_options["recaptcha_site_key"]!="" && $theme_options["recaptcha_secret_key"]!="")
					{
						wp_enqueue_script("google-recaptcha-v2");
						?>
						<div class="g-recaptcha-wrapper block"><div class="g-recaptcha" data-sitekey="<?php echo esc_attr($theme_options["recaptcha_site_key"]); ?>"></div></div>
						<?php
					}
					else
					{
					?>
						<p><?php _e("Error while loading reCapcha. Please set the reCaptcha keys under Theme Options in admin area", 'carservice'); ?></p>
					<?php
					}
				}
				if((int)$terms_checkbox)
				{
				?>
				</div>
				<?php
				}
				?>
				<input type="hidden" name="action" value="theme_comment_form">
				<input type="hidden" name="comment_parent_id" value="0">
				<input type="hidden" name="paged" value="1">
				<input type="hidden" name="prevent_scroll" value="0">
			</div>
		</div>
	<?php
	}
	global $post;
	?>
		<fieldset>
			<input type="hidden" name="post_id" value="<?php echo esc_attr(get_the_ID()); ?>">
			<input type="hidden" name="post_type" value="<?php echo esc_attr($post->post_type); ?>">
		</fieldset>
	</form>
</div>
<?php
}
?>