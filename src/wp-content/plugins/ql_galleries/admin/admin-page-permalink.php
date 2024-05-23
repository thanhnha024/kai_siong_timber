<div class="wrap ql_galleries-settings-section">
	<h2><?php _e("Permalink settings", "ql_galleries"); ?></h2>
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
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="ql_galleries-config-form">
	<div class="ql_galleries-form-table-container">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="calculator_skin">
							<?php _e("Slug (permalink)", "ql_galleries"); ?>
						</label>
					</th>
					<td>
						<input type="text" class="regular-text input-full-width" value="<?php echo esc_attr($ql_galleries_permalink["slug"]); ?>" id="slug" name="slug">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="calculator_skin">
							<?php _e("Gallery label singular", "ql_galleries"); ?>
						</label>
					</th>
					<td>
						<input type="text" class="regular-text input-full-width" value="<?php echo esc_attr($ql_galleries_permalink["label_singular"]); ?>" id="label_singular" name="label_singular">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="calculator_skin">
							<?php _e("Gallery label plural", "ql_galleries"); ?>
						</label>
					</th>
					<td>
						<input type="text" class="regular-text input-full-width" value="<?php echo esc_attr($ql_galleries_permalink["label_plural"]); ?>" id="label_plural" name="label_plural">
					</td>
				</tr>
				<tr valign="top" class="no-border">
					<th colspan="2">
						<input type="submit" value="<?php esc_attr_e("Save Options", 'ql_galleries'); ?>" class="button-primary" name="submit">
						<input type="hidden" name="action" value="save_galleries_permalink">
					</th>
				</tr>
			</tbody>
		</table>
	</div>
</form>