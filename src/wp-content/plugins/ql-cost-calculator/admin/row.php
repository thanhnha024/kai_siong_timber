<div id="row-modal" class="cost-calculator-modal" title="<?php esc_attr_e("Row Settings", 'cost-calculator'); ?>">
	<form action="#" class="cost-calculator-modal-form" id="row-modal-form">
		<div class="cost-calculator-form-container">
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
		<input type="hidden" name="shortcode" value="vc_row">
	</form>
</div>
