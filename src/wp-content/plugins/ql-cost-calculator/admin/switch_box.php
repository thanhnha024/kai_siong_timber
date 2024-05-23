<div id="switch-box-modal" class="cost-calculator-modal cost-calculator-element-modal" title="<?php esc_attr_e("Switch Box Settings", 'cost-calculator'); ?>">
	<form action="#" class="cost-calculator-modal-form" id="switch-box-modal-form">
		<div class="cost-calculator-form-container">
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Id", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="id" value="switch-box">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Name", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="name" value="switch-box">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Label", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="label" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Hide label", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="hide_label" class="cost-calculator-modal-dropdown">
						<option value="0" selected="selected"><?php _e("No", 'cost-calculator'); ?></option>
						<option value="1"><?php _e("Yes", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("'Yes' text", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="yes_text" value="<?php esc_attr_e("Yes", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("'No' text", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="no_text" value="<?php esc_attr_e("No", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Default value", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="default_value" value="1">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Is checked", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="checked" class="cost-calculator-modal-dropdown">
						<option value="1" selected="selected"><?php _e("Yes", 'cost-calculator'); ?></option>
						<option value="0"><?php _e("No", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Top margin", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="top_margin" class="cost-calculator-modal-dropdown">
						<option value="none" selected="selected"><?php _e("None", 'cost-calculator'); ?></option>
						<option value="page-margin-top"><?php _e("Small", 'cost-calculator'); ?></option>
						<option value="page-margin-top-section"><?php _e("Large", 'cost-calculator'); ?></option>
					</select>
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
		</div>
		<input type="hidden" name="shortcode" value="cost_calculator_switch_box">
	</form>
</div>
