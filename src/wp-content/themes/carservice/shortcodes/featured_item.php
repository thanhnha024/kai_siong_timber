<?php
//post
function cs_theme_featured_item($atts, $content)
{
	extract(shortcode_atts(array(
		"icon" => "none",
		"type" => "default",
		"title" => "",
		"url" => "",
		"title_link" => 1,
		"icon_link" => 1,
		"counter" => "0",
		"counter_sign" => "",
		"top_margin" => "none"
	), $atts));
	
	$output = "";
	$output .= '<div class="feature-item' . ($type=="big" ? ' feature-item-big' : '') . ((int)$counter>0 ? ' with-counter' : '') . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . '">';
	if(!empty($url) && (int)$icon_link && $icon!="none" && $type=="big")
		$output .= '<a href="' . esc_url($url) . '" class="feature-item-icon-url">&nbsp;</a>';
	if($icon!="none" && $type=="big")
		$output .= '<div class="hexagon"><div class="sl-small-' . esc_attr($icon) . '"></div></div>';
	if((int)$counter>0)
		$output .= '<span class="number animated-element" data-value="' . esc_attr((int)$counter) . '"></span>' . (!empty($counter_sign) ? '<span class="number sign">' . $counter_sign . '</span>' : '');
	if($title!="")
		$output .= '<h' . ($type=="big" ? '4' : '5') . ($type=="big" ? ' class="box-header"' : '') . '>' . (!empty($url) && (int)$title_link ? '<a href="' . esc_url($url) . '">' : '') . $title . (!empty($url) && (int)$title_link ? '</a>' : '') . '</h' . ($type=="big" ? '4' : '5') . '>';
	if(!empty($url) && (int)$icon_link && $icon!="none" && $type!="big")
		$output .= '<a href="' . esc_url($url) . '" class="feature-item-icon-url">&nbsp;</a>';
	if($icon!="" && $icon!="none" && $type!="big")
		$output .= '<div class="icon sl-small-' . esc_attr($icon) . '"></div>';
	if($content!="")
		$output .= '<p>' . wpb_js_remove_wpautop($content) . '</p>';
	$output .= '</div>';
	return $output;
}

//visual composer
function cs_theme_featured_item_vc_init()
{
	$params = array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Icon", 'carservice'),
			"param_name" => "icon",
			"value" => array(
				__("none", 'carservice') => "none",
				__("air-conditioning", 'carservice') => "air-conditioning",
				__("alarm", 'carservice') => "alarm",
				__("camper", 'carservice') => "camper",
				__("car", 'carservice') => "car",
				__("car-2", 'carservice') => "car-2",
				__("car-3", 'carservice') => "car-3",
				__("car-audio", 'carservice') => "car-audio",
				__("car-battery", 'carservice') => "car-battery",
				__("car-check", 'carservice') => "car-check",
				__("car-checklist", 'carservice') => "car-checklist",
				__("car-fire", 'carservice') => "car-fire",
				__("car-fix", 'carservice') => "car-fix",
				__("car-key", 'carservice') => "car-key",
				__("car-lock", 'carservice') => "car-lock",
				__("car-music", 'carservice') => "car-music",
				__("car-oil", 'carservice') => "car-oil",
				__("car-setting", 'carservice') => "car-setting",
				__("car-wash", 'carservice') => "car-wash",
				__("car-wheel", 'carservice') => "car-wheel",
				__("caution-fence", 'carservice') => "caution-fence",
				__("certificate", 'carservice') => "certificate",
				__("check", 'carservice') => "check",
				__("check-2", 'carservice') => "check-2",
				__("check-shield", 'carservice') => "check-shield",
				__("checklist", 'carservice') => "checklist",
				__("clock", 'carservice') => "clock",
				__("coffee", 'carservice') => "coffee",
				__("cog-double", 'carservice') => "cog-double",
				__("eco-car", 'carservice') => "eco-car",
				__("eco-fuel", 'carservice') => "eco-fuel",
				__("eco-fuel-barrel", 'carservice') => "eco-fuel-barrel",
				__("eco-globe", 'carservice') => "eco-globe",
				__("eco-nature", 'carservice') => "eco-nature",
				__("electric-wrench", 'carservice') => "electric-wrench",
				__("email", 'carservice') => "email",
				__("engine-belt", 'carservice') => "engine-belt",
				__("engine-belt-2", 'carservice') => "engine-belt-2",
				__("facebook", 'carservice') => "facebook",
				__("faq", 'carservice') => "faq",
				__("fax", 'carservice') => "fax",
				__("fax-2", 'carservice') => "fax-2",
				__("garage", 'carservice') => "garage",
				__("gauge", 'carservice') => "gauge",
				__("gearbox", 'carservice') => "gearbox",
				__("google-plus", 'carservice') => "google-plus",
				__("gps", 'carservice') => "gps",
				__("headlight", 'carservice') => "headlight",
				__("heating", 'carservice') => "heating",
				__("image", 'carservice') => "image",
				__("images", 'carservice') => "images",
				__("inflator-pump", 'carservice') => "inflator-pump",
				__("lightbulb", 'carservice') => "lightbulb",
				__("location-map", 'carservice') => "location-map",
				__("oil-can", 'carservice') => "oil-can",
				__("oil-gauge", 'carservice') => "oil-gauge",
				__("oil-station", 'carservice') => "oil-station",
				__("parking-sensor", 'carservice') => "parking-sensor",
				__("payment", 'carservice') => "payment",
				__("pen", 'carservice') => "pen",
				__("percent", 'carservice') => "percent",
				__("person", 'carservice') => "person",
				__("phone", 'carservice') => "phone",
				__("phone-call", 'carservice') => "phone-call",
				__("phone-call-24h", 'carservice') => "phone-call-24h",
				__("phone-circle", 'carservice') => "phone-circle",
				__("piggy-bank", 'carservice') => "piggy-bank",
				__("quote", 'carservice') => "quote",
				__("road", 'carservice') => "road",
				__("screwdriver", 'carservice') => "screwdriver",
				__("seatbelt-lock", 'carservice') => "seatbelt-lock",
				__("service-24h", 'carservice') => "service-24h",
				__("share-time", 'carservice') => "share-time",
				__("shopping-cart", 'carservice') => "shopping-cart",
				__("signal-warning", 'carservice') => "signal-warning",
				__("sign-zigzag", 'carservice') => "sign-zigzag",
				__("snow-crystal", 'carservice') => "snow-crystal",
				__("speed-gauge", 'carservice') => "speed-gauge",
				__("steering-wheel", 'carservice') => "steering-wheel",
				__("team", 'carservice') => "team",
				__("testimonials", 'carservice') => "testimonials",
				__("toolbox", 'carservice') => "toolbox",
				__("toolbox-2", 'carservice') => "toolbox-2",
				__("truck", 'carservice') => "truck",
				__("truck-tow", 'carservice') => "truck-tow",
				__("tunning", 'carservice') => "tunning",
				__("twitter", 'carservice') => "twitter",
				__("user-chat", 'carservice') => "user-chat",
				__("video", 'carservice') => "video",
				__("wallet", 'carservice') => "wallet",
				__("wedding-car", 'carservice') => "wedding-car",
				__("windshield", 'carservice') => "windshield",
				__("wrench", 'carservice') => "wrench",
				__("wrench-double", 'carservice') => "wrench-double",
				__("wrench-screwdriver", 'carservice') => "wrench-screwdriver",
				__("youtube", 'carservice') => "youtube"
			)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Type", 'carservice'),
			"param_name" => "type",
			"value" => array(__("Default", 'carservice') => "default",  __("Big", 'carservice') => "big")
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Title", 'carservice'),
			"param_name" => "title",
			"value" => ""
		),
		array(
			"type" => "textarea_html",
			"holder" => "div",
			"class" => "",
			"heading" => __("Text", 'carservice'),
			"param_name" => "content",
			"value" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Url", 'carservice'),
			"param_name" => "url",
			"value" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Title link", 'carservice'),
			"param_name" => "title_link",
			"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Icon link", 'carservice'),
			"param_name" => "icon_link",
			"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Counter value", 'carservice'),
			"param_name" => "counter",
			"value" => "0"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Counter value sign", 'carservice'),
			"param_name" => "counter_sign",
			"value" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Top margin", 'carservice'),
			"param_name" => "top_margin",
			"value" => array(__("None", 'carservice') => "none", __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section")
		)
	);
	
	vc_map( array(
		"name" => __("Featured Item", 'carservice'),
		"base" => "featured_item",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-featured-item",
		"category" => __('Carservice', 'carservice'),
		"params" => $params
	));
}
add_action("init", "cs_theme_featured_item_vc_init");
?>
