<div class="wrap ql_services-settings-section">
	<h2><?php _e("Permalink settings", "ql_services"); ?></h2>
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
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="ql_services-config-form">
	<div class="ql_services-form-table-container">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="calculator_skin">
							<?php _e("Slug (permalink)", "ql_services"); ?>
						</label>
					</th>
					<td>
						<input type="text" class="regular-text input-full-width" value="<?php echo esc_attr($ql_services_permalink["slug"]); ?>" id="slug" name="slug">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="calculator_skin">
							<?php _e("Service label singular", "ql_services"); ?>
						</label>
					</th>
					<td>
						<input type="text" class="regular-text input-full-width" value="<?php echo esc_attr($ql_services_permalink["label_singular"]); ?>" id="label_singular" name="label_singular">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="calculator_skin">
							<?php _e("Service label plural", "ql_services"); ?>
						</label>
					</th>
					<td>
						<input type="text" class="regular-text input-full-width" value="<?php echo esc_attr($ql_services_permalink["label_plural"]); ?>" id="label_plural" name="label_plural">
					</td>
				</tr>
				<?php
				$currentTheme = wp_get_theme();
				if(strpos($currentTheme, "Finpeak")!==false)
				{
				?>
				<tr valign="top">
					<th scope="row">
						<label for="calculator_skin">
							<?php _e("Service category slug (permalink)", "ql_services"); ?>
						</label>
					</th>
					<td>
						<input type="text" class="regular-text input-full-width" value="<?php echo esc_attr($ql_services_permalink["category_slug"]); ?>" id="category_slug" name="category_slug">
					</td>
				</tr>
				<?php
				}
				?>
				<tr valign="top" class="no-border">
					<th colspan="2">
						<input type="submit" value="<?php esc_attr_e("Save Options", 'ql_services'); ?>" class="button-primary" name="submit">
						<input type="hidden" name="action" value="save_services_permalink">
					</th>
				</tr>
			</tbody>
		</table>
	</div>
</form>