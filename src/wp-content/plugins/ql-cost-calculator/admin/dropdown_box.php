<div id="dropdown-box-modal" class="cost-calculator-modal cost-calculator-element-modal" title="<?php esc_attr_e("Dropdown Box Settings", 'cost-calculator'); ?>">
	<form action="#" class="cost-calculator-modal-form" id="dropdown-box-modal-form">
		<div class="cost-calculator-form-container">
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Id", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="id" value="dropdown-box">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Name", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="name" value="dropdown-box">
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
					<label><?php _e("Number of options", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="options_count" class="cost-calculator-modal-dropdown">
						<?php
						for($i=1; $i<=30; $i++)
						{
							?>
							<option value="<?php esc_attr_e($i); ?>"><?php echo $i; ?></option>
							<?php
						}
						?>
					</select>
				</div>
			</div>
			<?php
			for($i=0; $i<30; $i++)
			{
			?>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix cost-calculator-dropdown-option-row<?php echo ($i>0 ? ' cost-calculator-hidden' : ''); ?>">
				<div class="cost-calculator-column-left">
					<label><?php _e("Option name ", 'cost-calculator'); echo ($i+1); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="option_name<?php esc_attr_e($i); ?>" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix cost-calculator-dropdown-option-row<?php echo ($i>0 ? ' cost-calculator-hidden' : ''); ?>">
				<div class="cost-calculator-column-left">
					<label class="option-value-label"><?php _e("Option value ", 'cost-calculator');  echo ($i+1); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="option_value<?php esc_attr_e($i); ?>" value="">
				</div>
			</div>
			<?php
			}
			?>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Default value", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="default_value" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Show 'choose' label", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="show_choose_label" class="cost-calculator-modal-dropdown">
						<option value="1" selected="selected"><?php _e("Yes", 'cost-calculator'); ?></option>
						<option value="0"><?php _e("No", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix show-choose-label-depends">
				<div class="cost-calculator-column-left">
					<label><?php _e("Choose label", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="choose_label" value="<?php esc_attr_e("Choose...", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix show-choose-label-depends">
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
		</div>
		<input type="hidden" name="shortcode" value="cost_calculator_dropdown_box">
	</form>
</div>
