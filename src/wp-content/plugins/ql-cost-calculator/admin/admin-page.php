<div class="wrap cost-calculator-settings-section">
	<h2><?php _e("Cost Calculator", "cost-calculator"); ?></h2>
</div>
<div class="updated settings-error" id="cost-calculator-shortcode-info"></div>
<?php
//get google fonts
$fontsArray = cc_get_google_fonts();
?>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="cost-calculator-form">
	<div class="cost-calculator-form-container">
		<div class="cost-calculator-form-container-row cost-calculator-clearfix">
			<div class="cost-calculator-column-left">
				<label for="edit-cost-calculator-shortcode-id"><?php _e("Choose shortcode id for edit: ", "cost-calculator"); ?></label>
			</div>
			<div class="cost-calculator-column-right">
				<select id="edit-cost-calculator-shortcode-id" class="cost-calculator-admin-dropdown">
					<option value="-1" selected="selected"><?php _e("Choose...", "cost-calculator"); ?></option>
						<?php
							$cost_calculator_shortcodes_list = get_option("cost_calculator_shortcodes_list");
							if(!empty($cost_calculator_shortcodes_list))
							{
								foreach($cost_calculator_shortcodes_list as $key=>$val)
								{
									echo "<option value='{$key}'>{$key}</option>";
								}
							}
						?>
				</select>
				<span class="spinner"></span>
				<img id="shortcode-delete" src="<?php echo esc_url(plugins_url('images/delete.png', __FILE__));?>" alt="del" title="<?php _e("Delete this shortcode", "cost-calculator"); ?>">
			</div>
		</div>
		<div class="cost-calculator-form-container-row cost-calculator-clearfix">
			<div class="cost-calculator-column-left">
				<label for="cost-calculator-shortcode-id" id="cost-calculator-shortcode-id-label"><?php _e("Create new shortcode id *", "cost-calculator"); ?></label>
			</div>
			<div class="cost-calculator-column-right">
				<input type="text" class="regular-text" id="cost-calculator-shortcode-id" value="" pattern="[a-zA-z0-9_-]+" title="<?php _e("Please use only listed characters: letters, numbers, hyphen(-) and underscore(_)", "cost-calculator"); ?>"/>
				<span class="description"><?php _e("Unique identifier for cost calculator shortcode.", "cost-calculator"); ?></span>
			</div>
		</div>
		<div class="cost-calculator-form-container-row cost-calculator-advanced-settings-button-container cost-calculator-clearfix">
			<a class="cost-calculator-advanced-settings" href="#"><?php _e("Show advanced settings...", "cost-calculator"); ?></a>
		</div>
		<div class="cost-calculator-hidden">
			<div class="cost-calculator-advanced-settings-container cost-calculator-clearfix">
				<h3><?php _e("Colors config", 'cost-calculator'); ?></h3>
				<div class="cost-calculator-advanced-settings-column cost-calculator-advanced-settings-left">
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="calculator_skin"><?php _e("Predefined skin", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<select id="calculator_skin" name="calculator_skin" class="cost-calculator-skin-dropdown">
								<option value="default"><?php _e("Skin 1 (Clean)", 'cost-calculator'); ?></option>
								<option value="carservice"><?php _e("Skin 2 (Car Service)", 'cost-calculator'); ?></option>
								<option value="renovate"><?php _e("Skin 3 (Renovation)", 'cost-calculator'); ?></option>
								<option value="gymbase"><?php _e("Skin 4 (GymBase)", 'cost-calculator'); ?></option>
								<option value="finpeak"><?php _e("Skin 5 (Finpeak)", 'cost-calculator'); ?></option>
							</select>
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="main_color"><?php _e("Main color", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<div class="cost-calculator-colorpicker-container">
								<span class="cost-calculator-color-preview" style="background-color: #56B665;"></span>
								<input type="text" class="regular-text cost-calculator-color input-short" value="" id="main_color" name="main_color" data-default-color="56B665">
							</div>
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="main_color"><?php _e("Box background color", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<div class="cost-calculator-colorpicker-container">
								<span class="cost-calculator-color-preview" style="background-color: transparent;"></span>
								<input type="text" class="regular-text cost-calculator-color input-short" value="" id="box_color" name="box_color" data-default-color="transparent">
							</div>
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="main_color"><?php _e("Text color", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<div class="cost-calculator-colorpicker-container">
								<span class="cost-calculator-color-preview" style="background-color: #303030;"></span>
								<input type="text" class="regular-text cost-calculator-color input-short" value="" id="text_color" name="text_color" data-default-color="303030">
							</div>
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="main_color"><?php _e("Borders color", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<div class="cost-calculator-colorpicker-container">
								<span class="cost-calculator-color-preview" style="background-color: #EBEBEB;"></span>
								<input type="text" class="regular-text cost-calculator-color input-short" value="" id="border_color" name="border_color" data-default-color="EBEBEB">
							</div>
						</div>
					</div>
				</div>
				<div class="cost-calculator-advanced-settings-column cost-calculator-advanced-settings-right">
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="main_color"><?php _e("Labels color", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<div class="cost-calculator-colorpicker-container">
								<span class="cost-calculator-color-preview" style="background-color: #303030;"></span>
								<input type="text" class="regular-text cost-calculator-color input-short" value="" id="label_color" name="label_color" data-default-color="303030">
							</div>
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="main_color"><?php _e("Dropdown and checkbox label color", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<div class="cost-calculator-colorpicker-container">
								<span class="cost-calculator-color-preview" style="background-color: #303030;"></span>
								<input type="text" class="regular-text cost-calculator-color input-short" value="" id="dropdowncheckbox_label_color" name="dropdowncheckbox_label_color" data-default-color="303030">
							</div>
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="main_color"><?php _e("Form labels color", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<div class="cost-calculator-colorpicker-container">
								<span class="cost-calculator-color-preview" style="background-color: #999999;"></span>
								<input type="text" class="regular-text cost-calculator-color input-short" value="" id="form_label_color" name="form_label_color" data-default-color="999999">
							</div>
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="main_color"><?php _e("Inactive color", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<div class="cost-calculator-colorpicker-container">
								<span class="cost-calculator-color-preview" style="background-color: #EEEEEE;"></span>
								<input type="text" class="regular-text cost-calculator-color input-short" value="" id="inactive_color" name="inactive_color" data-default-color="EEEEEE">
							</div>
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="main_color"><?php _e("Slider tooltip background color", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<div class="cost-calculator-colorpicker-container">
								<span class="cost-calculator-color-preview" style="background-color: #FFFFFF;"></span>
								<input type="text" class="regular-text cost-calculator-color input-short" value="" id="tooltip_background_color" name="tooltip_background_color" data-default-color="FFFFFF">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="cost-calculator-advanced-settings-container cost-calculator-clearfix">
				<h3><?php _e("Fonts config", 'cost-calculator'); ?></h3>
				<div class="cost-calculator-advanced-settings-column cost-calculator-advanced-settings-left">
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="primary_font_custom"><?php _e("Primary font name", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<input type="text" class="regular-text input-short" value="" id="primary_font_custom" name="primary_font_custom">
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="cc_primary_font"><?php _e("Primary font google", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<span class="spinner"></span>
							<select id="cc_primary_font" name="primary_font" class="cost-calculator-fonts-dropdown">
								<option selected="selected" value=""><?php _e("Default (Raleway)", 'cost-calculator'); ?></option>
								<?php
								$fontsCount = count($fontsArray->items);
								for($i=0; $i<$fontsCount; $i++)
								{
									?>
									<option value="<?php echo esc_attr($fontsArray->items[$i]->family); ?>"><?php echo $fontsArray->items[$i]->family; ?></option>
									<?php
								}
								?>
							</select>
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix cost-calculator-hidden">
						<div class="cost-calculator-column-left">
							<label for="primary_font_variant"><?php _e("Google font variant", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<select id="primary_font_variant" name="primary_font_variant[]" class="cost-calculator-multiselect font-variant" multiple="multiple">
							</select>
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix cost-calculator-hidden">
						<div class="cost-calculator-column-left">
							<label for="primary_font_subset"><?php _e("Google font subset", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<select id="primary_font_subset" name="primary_font_subset[]" class="cost-calculator-multiselect font-subset" multiple="multiple">
							</select>
						</div>
					</div>
				</div>
				<div class="cost-calculator-advanced-settings-column cost-calculator-advanced-settings-right">
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="secondary_font_custom"><?php _e("Secondary font name", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<input type="text" class="regular-text input-short" value="" id="secondary_font_custom" name="secondary_font_custom">
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="cc_secondary_font"><?php _e("Secondary font google", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<span class="spinner"></span>
							<select id="cc_secondary_font" name="secondary_font" class="cost-calculator-fonts-dropdown">
								<option selected="selected" value=""><?php _e("Default (Lato)", 'cost-calculator'); ?></option>
								<?php
								$fontsCount = count($fontsArray->items);
								for($i=0; $i<$fontsCount; $i++)
								{
									?>
									<option value="<?php echo esc_attr($fontsArray->items[$i]->family); ?>"><?php echo $fontsArray->items[$i]->family; ?></option>
									<?php
								}
								?>
							</select>
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix cost-calculator-hidden">
						<div class="cost-calculator-column-left">
							<label for="secondary_font_variant"><?php _e("Google font variant", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<select id="secondary_font_variant" name="secondary_font_variant[]" class="cost-calculator-multiselect font-variant" multiple="multiple">
							</select>
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix cost-calculator-hidden">
						<div class="cost-calculator-column-left">
							<label for="secondary_font_subset"><?php _e("Google font subset", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<select id="secondary_font_subset" name="secondary_font_subset[]" class="cost-calculator-multiselect font-subset" multiple="multiple">
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="cost-calculator-advanced-settings-container cost-calculator-clearfix">
				<h3><?php _e("Other", 'cost-calculator'); ?></h3>
				<div class="cost-calculator-advanced-settings-column cost-calculator-advanced-settings-left">
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="form_display"><?php _e("Form display", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<select id="form_display" name="form_display" class="cost-calculator-dropdown">
								<option selected="selected" value="visible"><?php esc_html_e("Visible", 'cost-calculator'); ?></option>
								<option value="hidden"><?php esc_html_e("Hidden", 'cost-calculator'); ?></option>
							</select>
						</div>
					</div>
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="thank_you_page_url"><?php _e("Thank you page url", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<input type="text" class="regular-text input-short" value="" id="thank_you_page_url" name="thank_you_page_url">
						</div>
					</div>
				</div>
				<div class="cost-calculator-advanced-settings-column cost-calculator-advanced-settings-right">
					<div class="cost-calculator-form-container-row cost-calculator-clearfix">
						<div class="cost-calculator-column-left">
							<label for="form_action_url"><?php _e("Form action url", "cost-calculator"); ?></label>
						</div>
						<div class="cost-calculator-column-right">
							<input type="text" class="regular-text input-short" value="#" id="form_action_url" name="form_action_url">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	/*
	<!--<a class="button thickbox" href="#TB_inline?width=600&height=550&inlineId=row-modal" title="<?php esc_attr_e("Row Settings", 'cost-calculator'); ?>"><?php _e("Row", 'cost-calculator'); ?></a>-->
	<a class="button add-row" href="#" title="<?php esc_attr_e("Add new row", 'cost-calculator'); ?>"><?php _e("Add new row", 'cost-calculator'); ?></a>
	<!--<a class="button thickbox" href="#TB_inline?width=600&height=550&inlineId=column-modal" title="<?php esc_attr_e("Column Settings", 'cost-calculator'); ?>"><?php _e("Column", 'cost-calculator'); ?></a>-->
	<a class="button thickbox" href="#TB_inline?width=600&height=550&inlineId=dropdown-box-modal" title="<?php esc_attr_e("Dropdown Box Settings", 'cost-calculator'); ?>"><?php _e("Dropdown Box", 'cost-calculator'); ?></a>
	<a class="button thickbox" href="#TB_inline?width=600&height=550&inlineId=slider-box-modal" title="<?php esc_attr_e("Slider Box Settings", 'cost-calculator'); ?>"><?php _e("Slider Box", 'cost-calculator'); ?></a>
	<a class="button thickbox" href="#TB_inline?width=600&height=550&inlineId=input-box-modal" title="<?php esc_attr_e("Input Box Settings", 'cost-calculator'); ?>"><?php _e("Input Box", 'cost-calculator'); ?></a>
	<a class="button thickbox" href="#TB_inline?width=600&height=550&inlineId=switch-box-modal" title="<?php esc_attr_e("Switch Box Settings", 'cost-calculator'); ?>"><?php _e("Switch Box", 'cost-calculator'); ?></a>
	<a class="button thickbox" href="#TB_inline?width=600&height=550&inlineId=summary-box-modal" title="<?php esc_attr_e("Summary Box Settings", 'cost-calculator'); ?>"><?php _e("Summary Box", 'cost-calculator'); ?></a>
	<a class="button thickbox" href="#TB_inline?width=600&height=550&inlineId=contact-box-modal" title="<?php esc_attr_e("Contact Box Settings", 'cost-calculator'); ?>"><?php _e("Contact Box", 'cost-calculator'); ?></a>
	*/?>
	<p class="cost-calculator-submit">
		<span class="cc-plugin-button-tick"></span>
		<input type="submit" name="submit" class="cost-calculator-save-changes cost-calculator-button" value="<?php esc_attr_e("Save Shortcode", 'cost-calculator'); ?>">
		<span class="spinner" style="float: none; margin: 0 10px;"></span>
	</p>
	<div class="cost-calculator-form-container">
		<a class="cost-calculator-add-row cost-calculator-button" href="#" title="<?php esc_attr_e("Add Row", 'cost-calculator'); ?>"><?php _e("Add Row", 'cost-calculator'); ?></a>
		<div id="cost-calculator-shortcode-builder">
			<div class="cost-calculator-row" data-row-layout="columns_1_1-1">
				<div class="cost-calculator-row-bar">
					<div class="cost-calculator-sortable-handle cost-calculator-row-sortable-handle"></div>
					<select name="row-layout" class="cost-calculator-row-layout">
						<option value="columns_1_1-1" selected="selected"><?php _e("1 column", 'cost-calculator');?></option>
						<option value="columns_2_1-2_1-2"><?php _e("2 columns", 'cost-calculator');?></option>
						<option value="columns_3_1-3_1-3_1-3"><?php _e("3 columns", 'cost-calculator');?></option>
						<option value="columns_4_1-4_1-4_1-4_1-4"><?php _e("4 columns", 'cost-calculator');?></option>
						<option value="columns_2_2-3_1-3"><?php _e("2/3+1/3", 'cost-calculator');?></option>
						<option value="columns_2_3-4_1-4"><?php _e("3/4+1/4", 'cost-calculator');?></option>
						<option value="columns_3_1-4_1-2_1-4"><?php _e("1/4+1/2+1/4", 'cost-calculator');?></option>
					<select>
					<a href="#" class="cost-calculator-remove-row" title="<?php esc_attr_e("Remove Row", 'cost-calculator'); ?>"></a>
					<a href="#" class="cost-calculator-edit-row" title="<?php esc_attr_e("Edit Row Settings", 'cost-calculator'); ?>"></a>
				</div>
				<div class="cost-calculator-columns-container cost-calculator-clearfix">
					<div class="cost-calculator-column column-1-1">
						<div class="cost-calculator-column-bar">
							<div class="cost-calculator-sortable-handle cost-calculator-column-sortable-handle"></div>
							<select name="add-element" class="cost-calculator-add-element">
								<option value="-1" selected="selected"><?php _e("Add Element", 'cost-calculator');?></option>
								<option value="dropdown-box"><?php _e("Dropdown Box", 'cost-calculator');?></option>
								<option value="slider-box"><?php _e("Slider Box", 'cost-calculator');?></option>
								<option value="input-box"><?php _e("Input Box", 'cost-calculator');?></option>
								<option value="switch-box"><?php _e("Switch Box", 'cost-calculator');?></option>
								<option value="summary-box"><?php _e("Summary Box", 'cost-calculator');?></option>
								<option value="contact-box"><?php _e("Contact Box", 'cost-calculator');?></option>
							<select>
							<a href="#" class="cost-calculator-edit-column" title="<?php esc_attr_e("Edit Column Settings", 'cost-calculator'); ?>"></a>
						</div>
						<textarea class="cost-calculator-content-area"></textarea>
					</div>
				</div>
			</div>
		</div>
		<a class="cost-calculator-add-row cost-calculator-button" href="#" title="<?php esc_attr_e("Add Row", 'cost-calculator'); ?>"><?php _e("Add Row", 'cost-calculator'); ?></a>
	</div>
	<p class="cost-calculator-submit">
		<span class="cc-plugin-button-tick"></span>
		<input type="submit" name="submit" class="cost-calculator-save-changes cost-calculator-button" value="<?php esc_attr_e("Save Shortcode", 'cost-calculator'); ?>">
		<span class="spinner" style="float: none; margin: 0 10px;"></span>
	</p>
</form>
<?php
require_once("row.php");
require_once("column.php");
require_once("dropdown_box.php");
require_once("slider_box.php");
require_once("input_box.php");
require_once("switch_box.php");
require_once("summary_box.php");
require_once("contact_box.php");
?>