<?php
//comment form submit
function cs_theme_comment_form()
{
	global $theme_options;
	
	$result = array();
	$result["isOk"] = true;
	$verify_recaptcha = array();
	
	if(((isset($_POST["terms"]) && (int)$_POST["terms"]) || !isset($_POST["terms"])) && (((int)$theme_options["google_recaptcha_comments"] && !empty($_POST["g-recaptcha-response"])) || !(int)$theme_options["google_recaptcha_comments"]) && $_POST["name"]!="" && $_POST["name"]!=__("Your Name *", 'carservice') && $_POST["email"]!="" && $_POST["email"]!=__("Your Email *", 'carservice') && preg_match("#^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,12})$#", $_POST["email"]) && $_POST["message"]!="" && $_POST["message"]!=__("Comment *", 'carservice'))
	{
		if((int)$theme_options["google_recaptcha_comments"])
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
		if(((int)$theme_options["google_recaptcha_comments"] && isset($verify_recaptcha["success"]) && (int)$verify_recaptcha["success"]) || !(int)$theme_options["google_recaptcha_comments"])
		{
			$values = array(
				"name" => $_POST["name"],
				"email" => $_POST["email"],
				"website" => $_POST["website"],
				"message" => $_POST["message"]
			);
			$values = cs_theme_stripslashes_deep($values);
			$values = array_map("htmlspecialchars", $values);
		
			$time = current_time('mysql');

			$data = array(
				'comment_post_ID' => (int)$_POST['post_id'],
				'comment_author' => $values['name'],
				'comment_author_email' => $values['email'],
				'comment_author_url' => ($values['website']!=__("Your Website", 'carservice') ? $values['website'] : ""),
				'comment_content' => $values['message'],
				'comment_parent' => (isset($_POST['parent_comment_id']) ? (int)$_POST['parent_comment_id'] : 0),
				'comment_date' => $time,
				'comment_approved' => ((int)get_option('comment_moderation') ? 0 : 1),
				'comment_parent' => (int)$_POST['comment_parent_id']
			);

			if($comment_id = wp_insert_comment($data))
			{
				$result["submit_message"] = (!empty($theme_options["cf_thankyou_message_comments"]) ? $theme_options["cf_thankyou_message_comments"] : __("Your comment has been added.", 'carservice'));
				if(get_option('comments_notify'))
					wp_notify_postauthor($comment_id);
				//get post comments
				//post
				$comments_query = new WP_Query("p=" . (int)$_POST['post_id'] . "&post_type=" . $_POST["post_type"]);
				if($comments_query->have_posts()) : $comments_query->the_post(); 
					ob_start();
					$result['comment_id'] = $comment_id;
					if(isset($_POST['comment_parent_id']) && (int)$_POST['comment_parent_id']==0)
					{
						global $wpdb;
						$query = $wpdb->prepare("SELECT COUNT(*) AS count FROM $wpdb->comments WHERE comment_approved = 1 AND comment_post_ID = %d AND comment_parent = 0", get_the_ID());
						$parents = $wpdb->get_row($query);
						$_GET["paged"] = ceil($parents->count/5);
						$result["change_url"] = "#page-" . $_GET["paged"];
					}
					else
						$_GET["paged"] = (int)$_POST["paged"];
					global $withcomments;
					$withcomments = true;
					comments_template();
					$result['html'] = ob_get_contents();
					ob_end_clean();
				endif;
				//Reset Postdata
				wp_reset_postdata();
			}
			else 
			{
				$result["isOk"] = false;
				$result["submit_message"] = (!empty($theme_options["cf_error_message_comments"]) ? $theme_options["cf_error_message_comments"] : __("Error while adding comment.", 'carservice'));
			}
		}
		else
		{
			$result["isOk"] = false;
			$result["error_captcha"] = (!empty($theme_options["cf_recaptcha_message_comments"]) ? $theme_options["cf_recaptcha_message_comments"] : __("Please verify captcha.", 'carservice'));
		}
	}
	else
	{
		$result["isOk"] = false;
		if($_POST["name"]=="" || $_POST["name"]==__("Your Name *", 'carservice'))
			$result["error_name"] = (!empty($theme_options["cf_name_message_comments"]) ? $theme_options["cf_name_message_comments"] : __("Please enter your name.", 'carservice'));
		if($_POST["email"]=="" || $_POST["email"]==__("Your Email *", 'carservice') || !preg_match("#^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,12})$#", $_POST["email"]))
			$result["error_email"] = (!empty($theme_options["cf_email_message_comments"]) ? $theme_options["cf_email_message_comments"] : __("Please enter valid e-mail.", 'carservice'));
		if($_POST["message"]=="" || $_POST["message"]==__("Comment *", 'carservice'))
			$result["error_message"] = (!empty($theme_options["cf_comment_message_comments"]) ? $theme_options["cf_comment_message_comments"] : __("Please enter your message.", 'carservice'));
		if((int)$theme_options["google_recaptcha_comments"] && empty($_POST["g-recaptcha-response"]))
			$result["error_captcha"] = (!empty($theme_options["cf_recaptcha_message_comments"]) ? $theme_options["cf_recaptcha_message_comments"] : __("Please verify captcha.", 'carservice'));
		if(isset($_POST["terms"]) && !(int)$_POST["terms"])
			$result["error_terms"] = (!empty($theme_options["cf_terms_message_comments"]) ? $theme_options["cf_terms_message_comments"] : __("Checkbox is required.", 'carservice'));
	}
	echo @json_encode($result);
	exit();
}
add_action("wp_ajax_theme_comment_form", "cs_theme_comment_form");
add_action("wp_ajax_nopriv_theme_comment_form", "cs_theme_comment_form");

//get comments list
function cs_theme_get_comments()
{
	$result = array();
	$comments_query = new WP_Query("p=" . $_GET["post_id"] . "&post_type=" . $_GET["post_type"]);
	if($comments_query->have_posts()) : $comments_query->the_post();
	ob_start();
	global $withcomments;
	$withcomments = true;
	comments_template();
	$result["html"] = ob_get_contents();
	ob_end_clean();
	endif;
	//Reset Postdata
	wp_reset_postdata();
	echo @json_encode($result);
	exit();
}
add_action("wp_ajax_theme_get_comments", "cs_theme_get_comments");
add_action("wp_ajax_nopriv_theme_get_comments", "cs_theme_get_comments");
?>