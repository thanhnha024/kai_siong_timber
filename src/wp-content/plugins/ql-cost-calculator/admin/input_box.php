<div id="input-box-modal" class="cost-calculator-modal cost-calculator-element-modal" title="<?php esc_attr_e("Input Box Settings", 'cost-calculator'); ?>">
	<form action="#" class="cost-calculator-modal-form" id="input-box-modal-form">
		<div class="cost-calculator-form-container">
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Id", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="id" value="input-box">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Name", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="name" value="input-box">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Type", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="type" class="cost-calculator-modal-dropdown">
						<option value="text" selected="selected"><?php _e("text", 'cost-calculator'); ?></option>
						<option value="number"><?php _e("number", 'cost-calculator'); ?></option>
						<option value="date"><?php _e("date", 'cost-calculator'); ?></option>
						<option value="email"><?php _e("email", 'cost-calculator'); ?></option>
						<option value="checkbox"><?php _e("checkbox", 'cost-calculator'); ?></option>
						<option value="radio"><?php _e("radio", 'cost-calculator'); ?></option>
						<option value="hidden"><?php _e("hidden", 'cost-calculator'); ?></option>
						<option value="submit"><?php _e("submit", 'cost-calculator'); ?></option>
					</select>
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
			<div class="cost-calculator-form-container-row cost-calculator-clearfix type-depends-hide-label">
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
			<div class="cost-calculator-form-container-row cost-calculator-clearfix type-depends-group-label">
				<div class="cost-calculator-column-left">
					<label><?php _e("Group label", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="group_label" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Default value", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="default_value" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix type-depends">
				<div class="cost-calculator-column-left">
					<label><?php _e("Is checked", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="checked" class="cost-calculator-modal-dropdown">
						<option value="1"><?php _e("Yes", 'cost-calculator'); ?></option>
						<option value="0" selected="selected"><?php _e("No", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix type-depends">
				<div class="cost-calculator-column-left">
					<label><?php _e("Checkbox type", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="checkbox_type" class="cost-calculator-modal-dropdown">
						<option value="type-button" selected="selected"><?php _e("Button", 'cost-calculator'); ?></option>
						<option value="default"><?php _e("Default", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix type-depends">
				<div class="cost-calculator-column-left">
					<label><?php _e("Checked hidden text", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="checkbox_yes" value="<?php esc_attr_e("checked", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix type-depends">
				<div class="cost-calculator-column-left">
					<label><?php _e("Not checked hidden text", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="checkbox_no" value="<?php esc_attr_e("not checked", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Placeholder", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="placeholder" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix type-depends-required">
				<div class="cost-calculator-column-left">
					<label><?php _e("Required", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="required" class="cost-calculator-modal-dropdown">
						<option value="1"><?php _e("Yes", 'cost-calculator'); ?></option>
						<option value="0" selected="selected"><?php _e("No", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix required-depends">
				<div class="cost-calculator-column-left">
					<label><?php _e("Required field message", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="required_message" value="<?php esc_attr_e("This field is required", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("After pseudo element css class", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="after_pseudo" value="">
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
		<input type="hidden" name="shortcode" value="cost_calculator_input_box">
	</form>
</div>
