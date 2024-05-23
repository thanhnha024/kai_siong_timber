<?php
function cost_calculator_contact_box_shortcode($atts)
{
	extract(shortcode_atts(array(
		"label" => "",
		"submit_label" => __("Submit now", 'cost-calculator'),
		"name_label" => __("YOUR NAME", 'cost-calculator'),
		"name_placeholder" => __("YOUR NAME", 'cost-calculator'),
		"name_required" => 1,
		"email_label" => __("YOUR EMAIL", 'cost-calculator'),
		"email_placeholder" => __("YOUR EMAIL", 'cost-calculator'),
		"email_required" => 1,
		"phone_label" => __("YOUR PHONE", 'cost-calculator'),
		"phone_placeholder" => __("YOUR PHONE", 'cost-calculator'),
		"phone_required" => 0,
		"message_label" => __("QUESTIONS OR COMMENTS", 'cost-calculator'),
		"message_placeholder" => __("QUESTIONS OR COMMENTS", 'cost-calculator'),
		"message_required" => 0,
		"description" => "",
		"labels_style" => "default",
		"terms_checkbox" => 0,
		"terms_message" => "UGxlYXNlJTIwYWNjZXB0JTIwdGVybXMlMjBhbmQlMjBjb25kaXRpb25z",
		"append" => "",
		"type" => "",
		"el_class" => ""
	), $atts));

	$cost_calculator_global_form_options = array(
		"main_color" => '',
		"box_color" => '',
		"text_color" => '',
		"border_color" => '',
		"label_color" => '',
		"dropdowncheckbox_label_color" => '',
		"form_label_color" => '',
		"inactive_color" => '',
		"tooltip_background_color" => '',
		"primary_font_custom" => '',
		"primary_font" => '',
		"primary_font_subset" => '',
		"secondary_font_custom" => '',
		"secondary_font" => '',
		"secondary_font_subset" => '',
		"send_email" => '',
		"send_email_client" => '',
		"save_calculation" => '',
		"calculation_status" => '',
		"google_recaptcha" => '',
		"recaptcha_site_key" => '',
		"recaptcha_secret_key" => ''
	);
	$cost_calculator_global_form_options = cost_calculator_stripslashes_deep(array_merge($cost_calculator_global_form_options, (array)get_option("cost_calculator_global_form_options")));
	
	$output = '<div class="vc_row wpb_row vc_inner cost-calculator-contact-box' . (empty($label) ? ' cost-calculator-flex-box' : '') . (!empty($el_class) ? ' ' . esc_attr($el_class) : '') . '">';
		if(!empty($label))
			$output .= '<div class="vc_row wpb_row vc_inner"><label>' . $label . '</label></div><div class="vc_row wpb_row vc_inner cost-calculator-flex-box">';
	$output .= '<fieldset class="vc_col-sm-6 wpb_column vc_column_container">
			<div class="cost-calculator-block">
				' . (!empty($name_label) && ($labels_style=="default" || $labels_style=="labelplaceholder") ? '<label>' . $name_label . '</label>' : '') . '
				<input name="name" type="text" value=""' . ((int)$name_required ? ' data-required="1"' : '') . ((!empty($name_placeholder) || !empty($name_label)) && $labels_style!="default" ? ' placeholder="' . (!empty($name_placeholder) ? esc_attr($name_placeholder) : esc_attr($name_label)) . '"' : '') . '>
			</div>
			<div class="cost-calculator-block">
				' . (!empty($email_label) && ($labels_style=="default" || $labels_style=="labelplaceholder") ? '<label>' . $email_label . '</label>' : '') . '
				<input name="email" type="text" value=""' . ((int)$email_required ? ' data-required="1"' : '') . ((!empty($email_placeholder) || !empty($email_label)) && $labels_style!="default" ? ' placeholder="' . (!empty($email_placeholder) ? esc_attr($email_placeholder) : esc_attr($email_label)) . '"' : '') . '>
			</div>
			<div class="cost-calculator-block">
				' . (!empty($phone_label) && ($labels_style=="default" || $labels_style=="labelplaceholder") ? '<label>' . $phone_label . '</label>' : '') . '
				<input name="phone" type="text" value=""' . ((int)$phone_required ? ' data-required="1"' : '') . ((!empty($phone_placeholder) || !empty($phone_label)) && $labels_style!="default" ? ' placeholder="' . (!empty($phone_placeholder) ? esc_attr($phone_placeholder) : esc_attr($phone_label)) . '"' : '') . '>
			</div>
		</fieldset>
		<fieldset class="vc_col-sm-6 wpb_column vc_column_container">
			<div class="cost-calculator-block cost-calculator-textarea-block">
				' . (!empty($message_label) && ($labels_style=="default" || $labels_style=="labelplaceholder") ? '<label>' . $message_label . '</label>' : '') . '
				<textarea name="message"' . ((int)$message_required ? ' data-required="1"' : '') . ((!empty($message_placeholder) || !empty($message_label)) && $labels_style!="default" ? ' placeholder="' . (!empty($message_placeholder) ? esc_attr($message_placeholder) : esc_attr($message_label)) . '"' : '') . '></textarea>
			</div>
		</fieldset>';
	if(!empty($label))
		$output .= '</div>';
	$output .= '</div>
	<div class="vc_row wpb_row vc_inner cost-calculator-contact-box-submit-container' . ((int)$cost_calculator_global_form_options["google_recaptcha"]==1 && empty($description) ? ' cost-calculator-fieldset-with-recaptcha' : ((int)$cost_calculator_global_form_options["google_recaptcha"]==1 && !empty($description) ? ' cost-calculator-row-with-recaptcha' : '')) . '">';
	if(!empty($description))
	{
		$output .= '<div class="vc_col-sm-6 wpb_column vc_column_container">
			<p>' . $description . '</p>
		</div>
		<div class="vc_col-sm-6 wpb_column vc_column_container' . ((int)$cost_calculator_global_form_options["google_recaptcha"]==1 ? ' cost-calculator-column-with-recaptcha' : '') . '">';
	}
	$output .= '<input type="hidden" name="action" value="cost_calculator_form">
		<input type="hidden" name="type" value="' . (!empty($type) ? esc_attr($type) : 'calculator') . '">';
	if((int)$terms_checkbox)
	{
		$output .= '<div class="cost-calculator-terms-container cost-calculator-block">
			<input type="checkbox" name="terms" id="terms" value="1"><label for="terms">' . urldecode(base64_decode($terms_message)) . '</label>
		</div>';
		if((int)$cost_calculator_global_form_options["google_recaptcha"]==1)
		{
			$output .= '<div class="cost-calculator-recaptcha-container">';
		}
	}
	$output .= '<div class="vc_row wpb_row vc_inner' . ((int)$cost_calculator_global_form_options["google_recaptcha"]==1 ? ' cost-calculator-button-with-recaptcha' : '') . '">
			<a class="cost-calculator-more cost-calculator-submit-form" href="#cost-calculator-submit" title="' . esc_attr($submit_label) . '"' . (!empty($append) ? ' data-append="' . esc_attr($append) . '"' : '') . '><span>' . $submit_label . '</span></a>
		</div>';
	if((int)$cost_calculator_global_form_options["google_recaptcha"]==1)
	{
		if($cost_calculator_global_form_options["recaptcha_site_key"]!="" && $cost_calculator_global_form_options["recaptcha_secret_key"]!="")
		{
			wp_enqueue_script("google-recaptcha-v2");
			$output .= '<div class="g-recaptcha-wrapper cost-calculator-block"><div class="g-recaptcha" data-sitekey="' . esc_attr($cost_calculator_global_form_options["recaptcha_site_key"]) . '"></div></div>';
		}
		else
			$output .= '<p>' . __("Error while loading reCAPTCHA. Please set the reCAPTCHA keys under Theme Options in admin area", 'cost-calculator') . '</p>';
		if((int)$terms_checkbox)
		{
			$output .= '</div>';
		}
	}
	if(!empty($description))
		$output .= '</div>';
	$output .= '</div>';
	
	return $output;
}
add_shortcode("cost_calculator_contact_box", "cost_calculator_contact_box_shortcode");

if(is_plugin_active($js_composer_path) && function_exists('vc_map'))
{
	$cost_calculator_global_form_options = array(
		"main_color" => '',
		"box_color" => '',
		"text_color" => '',
		"border_color" => '',
		"label_color" => '',
		"dropdowncheckbox_label_color" => '',
		"form_label_color" => '',
		"inactive_color" => '',
		"tooltip_background_color" => '',
		"primary_font_custom" => '',
		"primary_font" => '',
		"primary_font_subset" => '',
		"secondary_font_custom" => '',
		"secondary_font" => '',
		"secondary_font_subset" => '',
		"send_email" => '',
		"send_email_client" => '',
		"save_calculation" => '',
		"calculation_status" => '',
		"google_recaptcha" => '',
		"recaptcha_site_key" => '',
		"recaptcha_secret_key" => ''
	);
	$cost_calculator_global_form_options = cost_calculator_stripslashes_deep(array_merge($cost_calculator_global_form_options, (array)get_option("cost_calculator_global_form_options")));
	//visual composer
	vc_map( array(
		"name" => __("Cost calculator contact box", 'cost-calculator'),
		"base" => "cost_calculator_contact_box",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-cost-calculator-contact-box",
		"category" => __('Cost Calculator', 'cost-calculator'),
		"params" => array(
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Form label", 'cost-calculator'),
				"param_name" => "label",
				"value" => ""
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Submit label", 'cost-calculator'),
				"param_name" => "submit_label",
				"value" => __("Submit now", 'cost-calculator')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Name label", 'cost-calculator'),
				"param_name" => "name_label",
				"value" => __("YOUR NAME", 'cost-calculator')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Name placeholder", 'cost-calculator'),
				"param_name" => "name_placeholder",
				"value" => __("YOUR NAME", 'cost-calculator')
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Name field required", 'cost-calculator'),
				"param_name" => "name_required",
				"value" => array(__("Yes", 'cost-calculator') => 1, __("No", 'cost-calculator') => 0)
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Email label", 'cost-calculator'),
				"param_name" => "email_label",
				"value" => __("YOUR EMAIL", 'cost-calculator')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Email placeholder", 'cost-calculator'),
				"param_name" => "email_placeholder",
				"value" => __("YOUR EMAIL", 'cost-calculator')
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Email field required", 'cost-calculator'),
				"param_name" => "email_required",
				"value" => array(__("Yes", 'cost-calculator') => 1, __("No", 'cost-calculator') => 0)
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Phone label", 'cost-calculator'),
				"param_name" => "phone_label",
				"value" => __("YOUR PHONE", 'cost-calculator')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Phone placeholder", 'cost-calculator'),
				"param_name" => "phone_placeholder",
				"value" => __("YOUR PHONE", 'cost-calculator')
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Phone field required", 'cost-calculator'),
				"param_name" => "phone_required",
				"value" => array(__("No", 'cost-calculator') => 0, __("Yes", 'cost-calculator') => 1)
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Message label", 'cost-calculator'),
				"param_name" => "message_label",
				"value" => __("QUESTIONS OR COMMENTS", 'cost-calculator')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Message placeholder", 'cost-calculator'),
				"param_name" => "message_placeholder",
				"value" => __("QUESTIONS OR COMMENTS", 'cost-calculator')
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Message field required", 'cost-calculator'),
				"param_name" => "message_required",
				"value" => array(__("No", 'cost-calculator') => 0, __("Yes", 'cost-calculator') => 1)
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Description", 'cost-calculator'),
				"param_name" => "description",
				"value" => ""
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Labels style", 'cost-calculator'),
				"param_name" => "labels_style",
				"value" => array(__("Display labels only", 'cost-calculator') => "default", __("Display labels and placeholders", 'cost-calculator') => "labelplaceholder", __("Display placeholders only", 'cost-calculator') => "placeholder")
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Terms and conditions checkbox", 'cost-calculator'),
				"param_name" => "terms_checkbox",
				"value" => array(__("Yes", 'cost-calculator') => 1, __("No", 'cost-calculator') => 0),
				"std" => 0
			),
			array(
				"type" => "textarea_raw_html",
				"class" => "",
				"heading" => __("Terms and conditions message", 'cost-calculator'),
				"param_name" => "terms_message",
				"value" => "UGxlYXNlJTIwYWNjZXB0JTIwdGVybXMlMjBhbmQlMjBjb25kaXRpb25z",
				"dependency" => Array('element' => "terms_checkbox", 'value' => "1")
			),
			array(
				"type" => "readonly",
				"class" => "",
				"heading" => __("reCAPTCHA", 'cost-calculator'),
				"param_name" => "recaptcha",
				"value" => ((int)$cost_calculator_global_form_options["google_recaptcha"]>0 ? __("Yes", 'cost-calculator') : __("No", 'cost-calculator')),
				"description" => sprintf(__("You can change this setting under <a href='%s' title='Global Config'>Global Config</a>", 'cost-calculator'), esc_url(admin_url("admin.php?page=cost_calculator_admin_page_global_config")))
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Append data from another form (enter form id)", 'cost-calculator'),
				"param_name" => "append",
				"value" => "",
				"description" => esc_html__( 'Enter form id from which you would like to append data to this form submission.', 'cost-calculator' )
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Type", 'cost-calculator'),
				"param_name" => "type",
				"value" => ""
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'cost-calculator' ),
				'param_name' => 'el_class',
				'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'cost-calculator' )
			)
		)
	));
}

//cost calculator form submit
function cost_calculator_form()
{
	ob_start();
	$cost_calculator_contact_form_options = array(
		"admin_name" => '',
		"admin_email" => '',
		"admin_name_from" => '',
		"admin_email_from" => '',
		"smtp_host" => '',
		"smtp_username" => '',
		"smtp_password" => '',
		"smtp_port" => '',
		"smtp_secure" => '',
		"email_subject" => '',
		"calculation_details_header" => '',
		"template" => '',
		"name_message" => '',
		"email_message" => '',
		"phone_message" => '',
		"message_message" => '',
		"recaptcha_message" => '',
		"terms_message" => '',
		"thankyou_message" => '',
		"error_message" => ''
	);
	$cost_calculator_contact_form_options = cost_calculator_stripslashes_deep(array_merge($cost_calculator_contact_form_options, (array)get_option("cost_calculator_contact_form_options")));
	
	$cost_calculator_global_form_options = array(
		"main_color" => '',
		"box_color" => '',
		"text_color" => '',
		"border_color" => '',
		"label_color" => '',
		"dropdowncheckbox_label_color" => '',
		"form_label_color" => '',
		"inactive_color" => '',
		"tooltip_background_color" => '',
		"primary_font_custom" => '',
		"primary_font" => '',
		"primary_font_subset" => '',
		"secondary_font_custom" => '',
		"secondary_font" => '',
		"secondary_font_subset" => '',
		"send_email" => '',
		"send_email_client" => '',
		"save_calculation" => '',
		"calculation_status" => '',
		"google_recaptcha" => '',
		"recaptcha_site_key" => '',
		"recaptcha_secret_key" => ''
	);
	$cost_calculator_global_form_options = cost_calculator_stripslashes_deep(array_merge($cost_calculator_global_form_options, (array)get_option("cost_calculator_global_form_options")));

    $result = array();
	$result["isOk"] = true;
	$verify_recaptcha = array();
	
	$requiredCheck = true;
	$radioKey = "";
	foreach($_POST as $key=>$value)
	{
		if(str_ends_with($key, "required_field_is_radio"))
		{
			$radioKey = str_replace("_required_field_is_radio", "", $key);
			if(!array_key_exists($radioKey, $_POST))
			{
				$result["error_" . $radioKey] = (array_key_exists($radioKey . "_required_field_message", $_POST) ? stripslashes($_POST[$radioKey . "_required_field_message"]) : (array_key_exists($radioKey . "-label", $_POST) ? stripslashes($_POST[$radioKey . "-label"]) : $radioKey) . __(" field is required.", 'cost-calculator'));
				$requiredCheck = false;
			}
		}
		else if(array_key_exists($key . "_required_field", $_POST) && ($value=="" || $value==null || ((int)$value==0 && array_key_exists($key . "_required_field_is_checkbox", $_POST))))
		{
			$result["error_" . $key] = (array_key_exists($key . "_required_field_message", $_POST) ? stripslashes($_POST[$key . "_required_field_message"]) : (array_key_exists($key . "-label", $_POST) ? stripslashes($_POST[$key . "-label"]) : $key) . __(" field is required.", 'cost-calculator'));
			$requiredCheck = false;
		}
	}
	if($requiredCheck && ((isset($_POST["terms"]) && (int)$_POST["terms"]) || !isset($_POST["terms"])) && (((int)$cost_calculator_global_form_options["google_recaptcha"]>0 && !empty($_POST["g-recaptcha-response"])) || !(int)$cost_calculator_global_form_options["google_recaptcha"]) && ((isset($_POST["name_required"]) && (int)$_POST["name_required"] && $_POST["name"]!="") || (!isset($_POST["name_required"]) || !(int)$_POST["name_required"])) && ((isset($_POST["email_required"]) && (int)$_POST["email_required"] && $_POST["email"]!="" && preg_match("#^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,12})$#", $_POST["email"])) || (!isset($_POST["email_required"]) || !(int)$_POST["email_required"])) && ((isset($_POST["phone_required"]) && (int)$_POST["phone_required"] && $_POST["phone"]!="") || (!isset($_POST["phone_required"]) || !(int)$_POST["phone_required"])) && ((isset($_POST["message_required"]) && (int)$_POST["message_required"] && $_POST["message"]!="") || (!isset($_POST["message_required"]) || !(int)$_POST["message_required"])))
	{
		if((int)$cost_calculator_global_form_options["google_recaptcha"]>0)
		{
			$data = array(
				"secret" => $cost_calculator_global_form_options["recaptcha_secret_key"],
				"response" => $_POST["g-recaptcha-response"]
			);
			$remote_response = wp_remote_post("https://www.google.com/recaptcha/api/siteverify", array(
				"body" => $data,
				"sslverify" => false,
			));
			$verify_recaptcha = json_decode($remote_response["body"], true);
		}
		if(((int)$cost_calculator_global_form_options["google_recaptcha"]>0 && isset($verify_recaptcha["success"]) && (int)$verify_recaptcha["success"] && (((int)$cost_calculator_global_form_options["google_recaptcha"]==3 && isset($verify_recaptcha["score"]) && $verify_recaptcha["score"]>=0.5) || (int)$cost_calculator_global_form_options["google_recaptcha"]==1)) || !(int)$cost_calculator_global_form_options["google_recaptcha"])
		{
			if((int)$cost_calculator_global_form_options["send_email"] || (int)$cost_calculator_global_form_options["send_email_client"] || (int)$cost_calculator_global_form_options["save_calculation"])
			{
				$values = array(
					"name" => $_POST["name"],
					"phone" => $_POST["phone"],
					"email" => $_POST["email"],
					"message" => $_POST["message"]
				);
				$values = cost_calculator_stripslashes_deep($values);
				$values = array_map("htmlspecialchars", $values);
				
				$form_data = "";
				foreach($_POST as $key=>$value)
				{
					if(array_key_exists($key . "-label", $_POST))
					{
						if(array_key_exists($key . "-name", $_POST))
						{
							//if(!empty($value))
								$form_data .= "<tr style='border: 1px solid black;'><td style='border: 1px solid black;'>" . stripslashes($_POST[$key . "-label"]) . "</td><td style='border: 1px solid black;'>" . stripslashes($_POST[$key . "-name"]) . (strlen($value) ? ", " : "") . $value . "</td></tr>";
						}
						else
						{
							//if(!empty($value))
								$form_data .= "<tr style='border: 1px solid black;'><td style='border: 1px solid black;'>" . stripslashes($_POST[$key . "-label"]) . "</td><td style='border: 1px solid black;'>" . $value . "</td></tr>";
						}
					}
				}
				foreach($_POST as $key=>$value)
				{
					if(array_key_exists($key . "-summarylabel", $_POST) && !empty($_POST[$key . "-summarylabel"]))
					{
						$form_data .= "<tr style='border: 1px solid black;'><td style='border: 1px solid black;'><b>" . $_POST[$key . "-summarylabel"] . "</b></td><td style='border: 1px solid black;'><b>" . $value . "</b></td></tr>";
					}
				}
				if($form_data!="")
					$form_data = "<table style='border: 1px solid black; border-collapse: collapse;'>" . (!empty($cost_calculator_contact_form_options["calculation_details_header"]) ? "<tr style='border: 1px solid black;'><td colspan='2' style='border: 1px solid black;'><b>" . $cost_calculator_contact_form_options["calculation_details_header"] . "</b></td></tr>" : "") . $form_data . "</table>";
				$subject = (!empty($cost_calculator_contact_form_options["email_subject"]) ? $cost_calculator_contact_form_options["email_subject"] : __("Calculation from: [name]", 'cost-calculator'));
				$subject = str_replace("[name]", $values["name"], $subject);
				$subject = str_replace("[email]", $values["email"], $subject);
				$subject = str_replace("[phone]", $values["phone"], $subject);
				$subject = str_replace("[message]", $values["message"], $subject);
				$body = $cost_calculator_contact_form_options["template"];
				$body = str_replace("[name]", $values["name"], $body);
				$body = str_replace("[email]", $values["email"], $body);
				$body = str_replace("[phone]", $values["phone"], $body);
				$body = str_replace("[message]", $values["message"], $body);
				$body = str_replace("[form_data]", $form_data, $body);

				//save in database
				if((int)$cost_calculator_global_form_options["save_calculation"])
				{
					$args = array(
					  'post_content' => $body,
					  'post_status' => $cost_calculator_global_form_options["calculation_status"],
					  'post_title' => $subject,
					  'post_type' => "calculations"
					);
					if(!wp_insert_post($args))
					{
						if((int)$cost_calculator_global_form_options["send_email"] && $result["isOk"])
						{
							$result["error_message"] = __("Email sent but error when saving to database", 'cost-calculator');
						}
						else
						{
							$result["isOk"] = false;
							$result["submit_message"] = (!empty($cost_calculator_contact_form_options["error_message"]) ? $cost_calculator_contact_form_options["error_message"] : __("Sorry, we can't send this message", 'cost-calculator'));
						}
					}
					else
					{
						$result["submit_message"] = (!empty($cost_calculator_contact_form_options["thankyou_message"]) ? $cost_calculator_contact_form_options["thankyou_message"] : __("Thank you for contacting us", 'cost-calculator'));
					}
				}
				//send to admin
				if((int)$cost_calculator_global_form_options["send_email"])
				{
					$headers[] = 'Reply-To: ' . $values["name"] . ' <' . $values["email"] . '>' . "\r\n";
					$headers[] = 'From: ' . (!empty($cost_calculator_contact_form_options["admin_name_from"]) ? $cost_calculator_contact_form_options["admin_name_from"] : $cost_calculator_contact_form_options["admin_name"]) . ' <' . (!empty($cost_calculator_contact_form_options["admin_email_from"]) ? $cost_calculator_contact_form_options["admin_email_from"] : $cost_calculator_contact_form_options["admin_email"]) . '>' . "\r\n";
					$headers[] = 'Content-type: text/html';
					if(wp_mail($cost_calculator_contact_form_options["admin_name"] . ' <' . $cost_calculator_contact_form_options["admin_email"] . '>', $subject, $body, $headers))
					{
						$result["submit_message"] = (!empty($cost_calculator_contact_form_options["thankyou_message"]) ? $cost_calculator_contact_form_options["thankyou_message"] : __("Thank you for contacting us", 'cost-calculator'));
					}
					else
					{
						$result["isOk"] = false;
						$result["error_message"] = $GLOBALS['phpmailer']->ErrorInfo;
						$result["submit_message"] = (!empty($cost_calculator_contact_form_options["error_message"]) ? $cost_calculator_contact_form_options["error_message"] : __("Sorry, we can't send this message", 'cost-calculator'));
					}
					
				}
				//send to client
				if((int)$cost_calculator_global_form_options["send_email_client"] && $values["email"]!="")
				{
					$headers_client[] = 'Reply-To: ' . $cost_calculator_contact_form_options["admin_name"] . ' <' . $cost_calculator_contact_form_options["admin_email"] . '>' . "\r\n";
					$headers_client[] = 'From: ' . (!empty($cost_calculator_contact_form_options["admin_name_from"]) ? $cost_calculator_contact_form_options["admin_name_from"] : $cost_calculator_contact_form_options["admin_name"]) . ' <' . (!empty($cost_calculator_contact_form_options["admin_email_from"]) ? $cost_calculator_contact_form_options["admin_email_from"] : $cost_calculator_contact_form_options["admin_email"]) . '>' . "\r\n";
					$headers_client[] = 'Content-type: text/html';
					$send_email_client_status = wp_mail($values["name"] . ' <' . $values["email"] . '>', $subject, $body, $headers_client);
					if(!(int)$cost_calculator_global_form_options["send_email"] && $send_email_client_status)
					{
						$result["submit_message"] = (!empty($cost_calculator_contact_form_options["thankyou_message"]) ? $cost_calculator_contact_form_options["thankyou_message"] : __("Thank you for contacting us", 'cost-calculator'));
					}
					else if(!(int)$cost_calculator_global_form_options["send_email"])
					{
						$result["isOk"] = false;
						$result["error_message"] = $GLOBALS['phpmailer']->ErrorInfo;
						$result["submit_message"] = (!empty($cost_calculator_contact_form_options["error_message"]) ? $cost_calculator_contact_form_options["error_message"] : __("Sorry, we can't send this message", 'cost-calculator'));
					}
				}
			}
			else
			{
				$result["isOk"] = false;
				$result["submit_message"] = (!empty($cost_calculator_contact_form_options["error_message"]) ? $cost_calculator_contact_form_options["error_message"] : __("Sorry, we can't send this message", 'cost-calculator')) . " " . __("Sending disabled by administrator", 'cost-calculator');
			}
		}
		else
		{
			$result["isOk"] = false;
			$result["error_captcha"] = $cost_calculator_contact_form_options["recaptcha_message"];
		}
	}
	else
	{
		$result["isOk"] = false;
		if(isset($_POST["name_required"]) && (int)$_POST["name_required"] && $_POST["name"]=="")
			$result["error_name"] = (!empty($cost_calculator_contact_form_options["name_message"]) ? $cost_calculator_contact_form_options["name_message"] : __("Please enter your name.", 'cost-calculator'));
		if(isset($_POST["email_required"]) && (int)$_POST["email_required"] && ($_POST["email"]=="" || !preg_match("#^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,12})$#", $_POST["email"])))
			$result["error_email"] = (!empty($cost_calculator_contact_form_options["email_message"]) ? $cost_calculator_contact_form_options["email_message"] : __("Please enter valid e-mail.", 'cost-calculator'));
		if(isset($_POST["phone_required"]) && (int)$_POST["phone_required"] && $_POST["phone"]=="")
			$result["error_phone"] = (!empty($cost_calculator_contact_form_options["phone_message"]) ? $cost_calculator_contact_form_options["phone_message"] : __("Please enter your phone number.", 'cost-calculator'));
		if(isset($_POST["message_required"]) && (int)$_POST["message_required"] && $_POST["message"]=="")
			$result["error_message"] = (!empty($cost_calculator_contact_form_options["message_message"]) ? $cost_calculator_contact_form_options["message_message"] : __("Please enter your message.", 'cost-calculator'));
		if((int)$cost_calculator_global_form_options["google_recaptcha"]>0 && empty($_POST["g-recaptcha-response"]))
			$result["error_captcha"] = (!empty($cost_calculator_contact_form_options["recaptcha_message"]) ? $cost_calculator_contact_form_options["recaptcha_message"] : __("reCAPTCHA verification failed.", 'cost-calculator'));
		if(isset($_POST["terms"]) && !(int)$_POST["terms"])
			$result["error_terms"] = (!empty($cost_calculator_contact_form_options["terms_message"]) ? $cost_calculator_contact_form_options["terms_message"] : __("Checkbox is required.", 'cost-calculator'));
	}
	$system_message = ob_get_clean();
	$result["system_message"] = $system_message;
	echo @json_encode($result);
	exit();
}
add_action("wp_ajax_cost_calculator_form", "cost_calculator_form");
add_action("wp_ajax_nopriv_cost_calculator_form", "cost_calculator_form");
?>
