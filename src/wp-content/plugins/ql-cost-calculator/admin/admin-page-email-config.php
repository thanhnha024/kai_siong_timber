<div class="wrap cost-calculator-settings-section">
	<h2><?php _e("Template Config", "cost-calculator"); ?></h2>
</div>
<?php
if(!empty($message))
{
?>
<div class="<?php echo ($message!="" ? "updated" : "error"); ?> settings-error"> 
	<p>
		<?php echo $message; ?>
	</p>
</div>
<?php
}
?>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="cost-calculator-config-form hidden">
	<input type="hidden" name="action" value="save">
	<div class="cost-calculator-tabs">
		<ul class="nav-tabs">
			<li class="nav-tab">
				<a href="#tab-admin-email">
					<?php _e('Admin email', 'cost-calculator'); ?>
				</a>
			</li>
			<li class="nav-tab">
				<a href="#tab-admin-smtp">
					<?php _e('Admin SMTP (optional)', 'cost-calculator'); ?>
				</a>
			</li>
			<li class="nav-tab">
				<a href="#tab-email-template">
					<?php _e('Calculation template', 'cost-calculator'); ?>
				</a>
			</li>
			<li class="nav-tab">
				<a href="#tab-form-messages">
					<?php _e('Form messages', 'cost-calculator'); ?>
				</a>
			</li>
		</ul>
		<div id="tab-admin-email">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="admin_name">
								<?php _e("Name (Send To)", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["admin_name"]); ?>" id="admin_name" name="admin_name">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="admin_email">
								<?php _e("Email (Send To)", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["admin_email"]); ?>" id="admin_email" name="admin_email">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="admin_name_from">
								<?php _e("Name (Send From) - Optional", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["admin_name_from"]); ?>" id="admin_name_from" name="admin_name_from">
							<label class="small_label"><?php esc_html_e("If not set, 'Name (Send To)' will be used", 'cost-calculator'); ?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="admin_email_from">
								<?php _e("Email (Send From) - Optional", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["admin_email_from"]); ?>" id="admin_email_from" name="admin_email_from">
							<label class="small_label"><?php esc_html_e("If not set, 'Email (Send To)' will be used", 'cost-calculator'); ?></label>
						</td>
					</tr>
					<tr valign="top" class="no-border">
						<th colspan="2">
							<input type="submit" value="<?php esc_attr_e("Save Options", 'cost-calculator'); ?>" class="button-primary" name="submit">
						</th>
					</tr>
				</tbody>
			</table>			
		</div>
		<div id="tab-admin-smtp">
			<?php
			if(!empty($smtp))
			{
			?>
			<div class="cost-calculator-info-box"> 
				<p>
					<?php printf(__("Plugin will use SMTP configuration from active theme. You can set SMTP details under <a href='%s' title='Theme Options'>Theme Options</a>", 'cost-calculator'), esc_url(admin_url("themes.php?page=ThemeOptions#tab-email-config")));?>
				</p>
			</div>
			<?php
			}
			else
			{
			?>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="smtp_host">
								<?php _e("Host", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["smtp_host"]); ?>" id="smtp_host" name="smtp_host"<?php echo (!empty($smtp) ? ' readonly="readonly"' : '');?>>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="smtp_username">
								<?php _e("Username", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["smtp_username"]); ?>" id="smtp_username" name="smtp_username"<?php echo (!empty($smtp) ? ' readonly="readonly"' : '');?>>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="smtp_password">
								<?php _e("Password", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="password" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["smtp_password"]); ?>" id="smtp_password" name="smtp_password"<?php echo (!empty($smtp) ? ' readonly="readonly"' : '');?>>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="smtp_port">
								<?php _e("Port", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["smtp_port"]); ?>" id="smtp_port" name="smtp_port"<?php echo (!empty($smtp) ? ' readonly="readonly"' : '');?>>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="smtp_secure">
								<?php _e("SMTP Secure", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<select name="smtp_secure" id="smtp_secure"<?php echo (!empty($smtp) ? ' disabled="disabled"' : '');?>>
								<option value="">-</option>
								<option value="ssl" <?php echo ($cost_calculator_contact_form_options["smtp_secure"]=="ssl" ? "selected='selected'" : "") ?>><?php _e("ssl", "cost-calculator"); ?></option>
								<option value="tls" <?php echo ($cost_calculator_contact_form_options["smtp_secure"]=="tls" ? "selected='selected'" : "") ?>><?php _e("tls", "cost-calculator"); ?></option>
							</select>
						</td>
					</tr>
					<tr valign="top" class="no-border">
						<th colspan="2">
							<input type="submit" value="<?php esc_attr_e("Save Options", 'cost-calculator'); ?>" class="button-primary" name="submit">
						</th>
					</tr>
				</tbody>
			</table>	
			<?php
			}
			?>
		</div>
		<div id="tab-email-template">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="email_subject">
								<?php _e("Subject", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["email_subject"]); ?>" id="email_subject" name="email_subject">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="calculation_details_header">
								<?php _e("Calculation details header", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["calculation_details_header"]); ?>" id="calculation_details_header" name="calculation_details_header">
						</td>
					</tr>
					<tr valign="top" class="no-border">
						<td colspan="2">
							<?php _e("Available shortcodes:", 'cost-calculator'); ?>
							<br>
							<strong>[name], [email], [phone], [message], [form_data]</strong>
						</td>
					</tr>
					<tr valign="top">
						<td colspan="2">
							<?php wp_editor($cost_calculator_contact_form_options["template"], "template", array("editor_height" => 250));?>
						</td>
					</tr>
					<tr valign="top" class="no-border">
						<th colspan="2">
							<input type="submit" value="<?php esc_attr_e("Save Options", 'cost-calculator'); ?>" class="button-primary" name="submit">
						</th>
					</tr>
				</tbody>
			</table>			
		</div>
		<div id="tab-form-messages">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="name_message">
								<?php _e("Name field required message", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["name_message"]); ?>" id="name_message" name="name_message">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="email_message">
								<?php _e("Email field required message", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["email_message"]); ?>" id="email_message" name="email_message">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="phone_message">
								<?php _e("Phone field required message", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["phone_message"]); ?>" id="phone_message" name="phone_message">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="message_message">
								<?php _e("Message field required message", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["message_message"]); ?>" id="message_message" name="message_message">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="recaptcha_message">
								<?php _e("reCAPTCHA required message", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["recaptcha_message"]); ?>" id="recaptcha_message" name="recaptcha_message">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="terms_message">
								<?php _e("Terms and conditions checkbox required message", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["terms_message"]); ?>" id="terms_message" name="terms_message">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="thankyou_message">
								<?php _e("Form thank you message", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["thankyou_message"]); ?>" id="thankyou_message" name="thankyou_message">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="error_message">
								<?php _e("Form error message", "cost-calculator"); ?>
							</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo esc_attr($cost_calculator_contact_form_options["error_message"]); ?>" id="error_message" name="error_message">
						</td>
					</tr>
					<tr valign="top" class="no-border">
						<th colspan="2">
							<input type="submit" value="<?php esc_attr_e("Save Options", 'cost-calculator'); ?>" class="button-primary" name="submit">
						</th>
					</tr>
				</tbody>
			</table>			
		</div>
	</div>
</form>