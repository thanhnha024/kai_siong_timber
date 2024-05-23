<div id="slider-box-modal" class="cost-calculator-modal cost-calculator-element-modal" title="<?php esc_attr_e("Slider Box Settings", 'cost-calculator'); ?>">
	<form action="#" class="cost-calculator-modal-form" id="slider-box-modal-form">
		<div class="cost-calculator-form-container">
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Id", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="id" value="slider-box">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Name", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="name" value="slider-box">
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
					<label><?php _e("Default value", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="default_value" value="6">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">	
					<label><?php _e("Unit value", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="unit_value" value="1">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">	
					<label><?php _e("Step", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="step" value="1">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">	
					<label><?php _e("Min value", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="min" value="0">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Max value", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="max" value="10">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Show min-max label", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="minmax_label" class="cost-calculator-modal-dropdown">
						<option value="1"><?php _e("Yes", 'cost-calculator'); ?></option>
						<option value="0" selected="selected"><?php _e("No", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Currency sign before value", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="currency" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Currency sign afer value", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="currency_after" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Thousands separator", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="thousands_separator" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Show input field", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="input_field" class="cost-calculator-modal-dropdown">
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
		<input type="hidden" name="shortcode" value="cost_calculator_slider_box">
	</form>
</div>
