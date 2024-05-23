<?php
//contact form
function cs_theme_contact_form_shortcode($atts)
{
	global $theme_options;
	extract(shortcode_atts(array(
		"id" => "contact_form",
		"submit_label" => __("SEND MESSAGE", 'carservice'),
		"name_label" => __("Your Name *", 'carservice'),
		"name_required" => 1,
		"email_label" => __("Your Email *", 'carservice'),
		"email_required" => 1,
		"phone_label" => __("Your Phone", 'carservice'),
		"phone_required" => 0,
		"message_label" => __("Message *", 'carservice'),
		"message_required" => 1,
		"description" => __("We will contact you within one business day.", 'carservice'),
		"terms_checkbox" => 0,
		"terms_message" => "UGxlYXNlJTIwYWNjZXB0JTIwdGVybXMlMjBhbmQlMjBjb25kaXRpb25z",
		"top_margin" => "none",
		"el_class" => ""
	), $atts));
	
	$output = "";
	$output .= '<form class="contact-form ' . ($top_margin!="none" ? esc_attr($top_margin) : '') . ($el_class!="" ? ' ' . esc_attr($el_class) : '') . '" id="' . esc_attr($id) . '" method="post" action="#">
		<div class="vc_row wpb_row vc_inner">
			<fieldset class="vc_col-sm-6 wpb_column vc_column_container">
				<div class="block">
					<input class="text_input" name="name" type="text" value="' . esc_attr($name_label) . '" placeholder="' . esc_attr($name_label) . '" data-default="' . esc_attr($name_label) . '"' . ((int)$name_required ? ' data-required="1"' : '') . '>
				</div>
				<div class="block">
					<input class="text_input" name="email" type="text" value="' . esc_attr($email_label) . '" placeholder="' . esc_attr($email_label) . '" data-default="' . esc_attr($email_label) . '"' . ((int)$email_required ? ' data-required="1"' : '') . '>
				</div>
				<div class="block">
					<input class="text_input" name="phone" type="text" value="' . esc_attr($phone_label) . '" placeholder="' . esc_attr($phone_label) . '" data-default="' . esc_attr($phone_label) . '"' . ((int)$phone_required ? ' data-required="1"' : '') . '>
				</div>
			</fieldset>
			<fieldset class="vc_col-sm-6 wpb_column vc_column_container">
				<div class="block">
					<textarea class="margin_top_10" name="message" placeholder="' . esc_attr($message_label) . '" data-default="' . esc_attr($message_label) . '"' . ((int)$message_required ? ' data-required="1"' : '') . '>' . $message_label . '</textarea>
				</div>
			</fieldset>
		</div>
		<div class="vc_row wpb_row vc_inner submit-container margin-top-30' . ((int)$theme_options["google_recaptcha"] && empty($description) ? ' fieldset-with-recaptcha' : ((int)$theme_options["google_recaptcha"] && !empty($description) ? ' row-with-recaptcha' : '')) . '">';
			if(!empty($description))
			{
				$output .= '<div class="vc_col-sm-6 wpb_column vc_column_container">
					<p>' . $description . '</p>
				</div>
				<div class="vc_col-sm-6 wpb_column vc_column_container ' . ((int)$theme_options["google_recaptcha"] ? 'column-with-recaptcha' : 'align-right') . '">';
			}
			$output .= '<input type="hidden" name="action" value="theme_contact_form">
				<input type="hidden" name="id" value="' . esc_attr($id) . '">';
			if((int)$terms_checkbox)
			{
				$output .= '<div class="terms-container block">
					<input type="checkbox" name="terms" id="' . esc_attr($id) . 'terms" value="1"><label for="' . esc_attr($id) . 'terms">' . urldecode(base64_decode($terms_message)) . '</label>
				</div>';
				if((int)$theme_options["google_recaptcha"])
				{
					$output .= '<div class="recaptcha-container">';
				}
			}
			$output .= '<div class="vc_row wpb_row vc_inner margin-top-20 padding-bottom-20' . ((int)$theme_options["google_recaptcha"] ? ' button-with-recaptcha' : '') . '">
					<a class="more submit-contact-form" href="#" title="' . esc_attr($submit_label) . '"><span>' . $submit_label . '</span></a>
				</div>';
				if((int)$theme_options["google_recaptcha"])
				{
					if($theme_options["recaptcha_site_key"]!="" && $theme_options["recaptcha_secret_key"]!="")
					{
						wp_enqueue_script("google-recaptcha-v2");
						$output .= '<div class="g-recaptcha-wrapper block"><div class="g-recaptcha" data-sitekey="' . esc_attr($theme_options["recaptcha_site_key"]) . '"></div></div>';
					}
					else
						$output .= '<p>' . __("Error while loading reCapcha. Please set the reCaptcha keys under Theme Options in admin area", 'carservice') . '</p>';
					if((int)$terms_checkbox)
					{
						$output .= '</div>';
					}
				}
	if(!empty($description))
		$output .= '</div>';
	$output .= '</div>
	</form>';
	return $output;
}

//visual composer
function cs_theme_contact_form_vc_init()
{
	global $theme_options;
	vc_map( array(
		"name" => __("Contact form", 'carservice'),
		"base" => "cs_contact_form",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-contact-form",
		"category" => __('Carservice', 'carservice'),
		"params" => array(
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Id", 'carservice'),
				"param_name" => "id",
				"value" => "contact_form",
				"description" => __("Please provide unique id for each contact form on the same page/post", 'carservice')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Submit label", 'carservice'),
				"param_name" => "submit_label",
				"value" => __("SEND MESSAGE", 'carservice')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Name label", 'carservice'),
				"param_name" => "name_label",
				"value" => __("Your Name *", 'carservice')
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Name field required", 'carservice'),
				"param_name" => "name_required",
				"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Email label", 'carservice'),
				"param_name" => "email_label",
				"value" => __("Your Email *", 'carservice')
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Email field required", 'carservice'),
				"param_name" => "email_required",
				"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Phone label", 'carservice'),
				"param_name" => "phone_label",
				"value" => __("Your Phone", 'carservice')
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Phone field required", 'carservice'),
				"param_name" => "phone_required",
				"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0),
				"std" => 0
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Message label", 'carservice'),
				"param_name" => "message_label",
				"value" => __("Message *", 'carservice')
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Message field required", 'carservice'),
				"param_name" => "message_required",
				"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Description", 'carservice'),
				"param_name" => "description",
				"value" => __("We will contact you within one business day.", 'carservice')
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Terms and conditions checkbox", 'carservice'),
				"param_name" => "terms_checkbox",
				"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0),
				"std" => 0
			),
			array(
				"type" => "textarea_raw_html",
				"class" => "",
				"heading" => __("Terms and conditions message", 'carservice'),
				"param_name" => "terms_message",
				"value" => "UGxlYXNlJTIwYWNjZXB0JTIwdGVybXMlMjBhbmQlMjBjb25kaXRpb25z",
				"dependency" => Array('element' => "terms_checkbox", 'value' => "1")
			),
			array(
				"type" => "readonly",
				"class" => "",
				"heading" => __("reCaptcha", 'carservice'),
				"param_name" => "recaptcha",
				"value" => ((int)$theme_options["google_recaptcha"] ? __("Yes", 'carservice') : __("No", 'carservice')),
				"description" => sprintf(__("You can change this setting under <a href='%s' title='Theme Options'>Theme Options</a>", 'carservice'), esc_url(admin_url("themes.php?page=ThemeOptions")))
			),
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __("Top margin", 'carservice'),
				"param_name" => "top_margin",
				"value" => array(__("None", 'carservice') => "none", __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section")
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'carservice' ),
				'param_name' => 'el_class',
				'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'carservice' )
			)
		)
	));
}
add_action("init", "cs_theme_contact_form_vc_init");

//contact form submit
function cs_theme_contact_form()
{
	ob_start();
	global $theme_options;

    $result = array();
	$result["isOk"] = true;
	$verify_recaptcha = array();
	
	if(((isset($_POST["terms"]) && (int)$_POST["terms"]) || !isset($_POST["terms"])) && (((int)$theme_options["google_recaptcha"] && !empty($_POST["g-recaptcha-response"])) || !(int)$theme_options["google_recaptcha"]) && ((isset($_POST["name_required"]) && (int)$_POST["name_required"] && $_POST["name"]!=$_POST["name_default"] && $_POST["name"]!="") || (!isset($_POST["name_required"]) || !(int)$_POST["name_required"])) && ((isset($_POST["email_required"]) && (int)$_POST["email_required"] && $_POST["email"]!=$_POST["email_default"] && $_POST["email"]!="" && preg_match("#^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,12})$#", $_POST["email"])) || (!isset($_POST["email_required"]) || !(int)$_POST["email_required"])) && ((isset($_POST["phone_required"]) && (int)$_POST["phone_required"] && $_POST["phone"]!=$_POST["phone_default"] && $_POST["phone"]!="") || (!isset($_POST["phone_required"]) || !(int)$_POST["phone_required"])) && ((isset($_POST["message_required"]) && (int)$_POST["message_required"] && $_POST["message"]!=$_POST["message_default"] && $_POST["message"]!="") || (!isset($_POST["message_required"]) || !(int)$_POST["message_required"])))
	{
		if((int)$theme_options["google_recaptcha"])
		{
			$data = array(
				"secret" => $theme_options["recaptcha_secret_key"],
				"response" => $_POST["g-recaptcha-response"]
			);
			$remote_response = wp_remote_post("https://www.google.com/recaptcha/api/siteverify", array(
				"body" => $data,
				"sslverify" => false,
			));
			$verify_recaptcha = json_decode($remote_response["body"], true);
		}
		if(((int)$theme_options["google_recaptcha"] && isset($verify_recaptcha["success"]) && (int)$verify_recaptcha["success"]) || !(int)$theme_options["google_recaptcha"])
		{
			$values = array(
				"name" => ($_POST["name"]!=$_POST["name_default"] ? $_POST["name"] : ""),
				"phone" => ($_POST["phone"]!=$_POST["phone_default"] ? $_POST["phone"] : ""),
				"email" => ($_POST["email"]!=$_POST["email_default"] ? $_POST["email"] : ""),
				"message" => ($_POST["message"]!=$_POST["message_default"] ? $_POST["message"] : "")
			);
			$values = cs_theme_stripslashes_deep($values);
			$values = array_map("htmlspecialchars", $values);
			
			$headers[] = 'Reply-To: ' . $values["name"] . ' <' . $values["email"] . '>' . "\r\n";
			$headers[] = 'From: ' . (!empty($theme_options["cf_admin_name_from"]) ? $theme_options["cf_admin_name_from"] : $theme_options["cf_admin_name"]) . ' <' . (!empty($theme_options["cf_admin_email_from"]) ? $theme_options["cf_admin_email_from"] : $theme_options["cf_admin_email"]) . '>' . "\r\n";
			$headers[] = 'Content-type: text/html';
			$subject = $theme_options["cf_email_subject"];
			$subject = str_replace("[name]", $values["name"], $subject);
			$subject = str_replace("[email]", $values["email"], $subject);
			$subject = str_replace("[phone]", $values["phone"], $subject);
			$subject = str_replace("[message]", $values["message"], $subject);
			$body = $theme_options["cf_template"];
			$body = str_replace("[name]", $values["name"], $body);
			$body = str_replace("[email]", $values["email"], $body);
			$body = str_replace("[phone]", $values["phone"], $body);
			$body = str_replace("[message]", $values["message"], $body);
			$body = str_replace("[form_data]", "", $body);

			if(wp_mail($theme_options["cf_admin_name"] . ' <' . $theme_options["cf_admin_email"] . '>', $subject, $body, $headers))
				$result["submit_message"] = (!empty($theme_options["cf_thankyou_message"]) ? $theme_options["cf_thankyou_message"] : __("Thank you for contacting us", 'carservice'));
			else
			{
				$result["isOk"] = false;
				$result["error_message"] = $GLOBALS['phpmailer']->ErrorInfo;
				$result["submit_message"] = (!empty($theme_options["cf_error_message"]) ? $theme_options["cf_error_message"] : __("Sorry, we can't send this message", 'carservice'));
			}
		}
		else
		{
			$result["isOk"] = false;
			$result["error_captcha"] = (!empty($theme_options["cf_recaptcha_message"]) ? $theme_options["cf_recaptcha_message"] : __("Please verify captcha.", 'carservice'));
		}
	}
	else
	{
		$result["isOk"] = false;
		if(isset($_POST["name_required"]) && (int)$_POST["name_required"] && ($_POST["name"]==$_POST["name_default"] || $_POST["name"]==""))
			$result["error_name"] = (!empty($theme_options["cf_name_message"]) ? $theme_options["cf_name_message"] : __("Please enter your name.", 'carservice'));
		if(isset($_POST["email_required"]) && (int)$_POST["email_required"] && ($_POST["email"]==$_POST["email_default"] || $_POST["email"]=="" || !preg_match("#^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,12})$#", $_POST["email"])))
			$result["error_email"] = (!empty($theme_options["cf_email_message"]) ? $theme_options["cf_email_message"] : __("Please enter valid e-mail.", 'carservice'));
		if(isset($_POST["phone_required"]) && (int)$_POST["phone_required"] && ($_POST["phone"]==$_POST["phone_default"] || $_POST["phone"]==""))
			$result["error_phone"] = (!empty($theme_options["cf_phone_message"]) ? $theme_options["cf_phone_message"] : __("Please enter your phone number.", 'carservice'));
		if(isset($_POST["message_required"]) && (int)$_POST["message_required"] && ($_POST["message"]==$_POST["message_default"] || $_POST["message"]==""))
			$result["error_message"] = (!empty($theme_options["cf_message_message"]) ? $theme_options["cf_message_message"] : __("Please enter your message.", 'carservice'));
		if((int)$theme_options["google_recaptcha"] && empty($_POST["g-recaptcha-response"]))
			$result["error_captcha"] = (!empty($theme_options["cf_recaptcha_message"]) ? $theme_options["cf_recaptcha_message"] : __("Please verify captcha.", 'carservice'));
		if(isset($_POST["terms"]) && !(int)$_POST["terms"])
			$result["error_terms"] = (!empty($theme_options["cf_terms_message"]) ? $theme_options["cf_terms_message"] : __("Checkbox is required.", 'carservice'));
	}
	$system_message = ob_get_clean();
	$result["system_message"] = $system_message;
	echo @json_encode($result);
	exit();
}
add_action("wp_ajax_theme_contact_form", "cs_theme_contact_form");
add_action("wp_ajax_nopriv_theme_contact_form", "cs_theme_contact_form");
?>