<div id="contact-box-modal" class="cost-calculator-modal cost-calculator-element-modal" title="<?php esc_attr_e("Contact Box Settings", 'cost-calculator'); ?>">
	<form action="#" class="cost-calculator-modal-form" id="contact-box-modal-form">
		<div class="cost-calculator-form-container">
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Form label", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="label" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Submit label", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="submit_label" value="<?php esc_attr_e("Submit now", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Name label", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="name_label" value="<?php esc_attr_e("YOUR NAME", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Name placeholder", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="name_placeholder" value="<?php esc_attr_e("YOUR NAME", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Name field required", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="name_required" class="cost-calculator-modal-dropdown">
						<option value="1" selected="selected"><?php _e("Yes", 'cost-calculator'); ?></option>
						<option value="0"><?php _e("No", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Email label", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="email_label" value="<?php esc_attr_e("YOUR EMAIL", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Email placeholder", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="email_placeholder" value="<?php esc_attr_e("YOUR EMAIL", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Email field required", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="email_required" class="cost-calculator-modal-dropdown">
						<option value="1" selected="selected"><?php _e("Yes", 'cost-calculator'); ?></option>
						<option value="0"><?php _e("No", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Phone label", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="phone_label" value="<?php esc_attr_e("YOUR PHONE", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Phone placeholder", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="phone_placeholder" value="<?php esc_attr_e("YOUR PHONE", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Phone field required", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="phone_required" class="cost-calculator-modal-dropdown">
						<option value="0" selected="selected"><?php _e("No", 'cost-calculator'); ?></option>
						<option value="1"><?php _e("Yes", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Message label", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="message_label" value="<?php esc_attr_e("QUESTIONS OR COMMENTS", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Message placeholder", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="message_placeholder" value="<?php esc_attr_e("QUESTIONS OR COMMENTS", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Message field required", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="message_required" class="cost-calculator-modal-dropdown">
						<option value="0" selected="selected"><?php _e("No", 'cost-calculator'); ?></option>
						<option value="1"><?php _e("Yes", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Description", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="description" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Labels style", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="labels_style" class="cost-calculator-modal-dropdown">
						<option value="default" selected="selected"><?php _e("Display labels only", 'cost-calculator'); ?></option>
						<option value="labelplaceholder"><?php _e("Display labels and placeholders", 'cost-calculator'); ?></option>
						<option value="placeholder"><?php _e("Display placeholders only", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Terms and conditions checkbox", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="terms_checkbox" class="cost-calculator-modal-dropdown">
						<option value="0" selected="selected"><?php _e("No", 'cost-calculator'); ?></option>
						<option value="1"><?php _e("Yes", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix terms-depends">
				<div class="cost-calculator-column-left">
					<label><?php _e("Terms and conditions message", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="terms_message" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php esc_html_e("Append data from another form (enter form id)", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="append" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Type", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="type" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Extra class name", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="el_class" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("reCAPTCHA", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="type" value="<?php echo ((int)$cost_calculator_global_form_options["google_recaptcha"] ? esc_attr__("Yes", 'cost-calculator') : esc_attr__("No", 'cost-calculator')); ?>" readonly="readonly">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-align-right cost-calculator-clearfix">
				<?php printf(__("You can change this setting under <a href='%s' title='Global Config'>Global Config</a>", 'cost-calculator'), esc_url(admin_url("admin.php?page=cost_calculator_admin_page_global_config"))); ?>
			</div>
		</div>
		<input type="hidden" name="shortcode" value="cost_calculator_contact_box">
	</form>
</div>