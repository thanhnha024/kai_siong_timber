<?php
function carservice_testimonials_shortcode($atts)
{
	global $theme_options;
	extract(shortcode_atts(array(
		"type" => "big",
		"pagination" => 1,
		"testimonials_count" => 1,
		"autoplay" => 0,
		"pause_on_hover" => 1,
		"scroll" => 1,
		"effect" => "scroll",
		"easing" => "easeInOutQuint",
		"duration" => 750,
		"ontouch" => 0,
		"onmouse" => 0,
		"top_margin" => "none",
		"el_class" => ""
	), $atts));
	if($effect=="_fade")
		$effect = "fade";
	if(strpos($easing, 'ease')!==false)
	{
		$newEasing = 'ease';
		if(strpos($easing, 'inout')!==false)
			$newEasing .= 'InOut';
		else if(strpos($easing, 'in')!==false)
			$newEasing .= 'In';
		else if(strpos($easing, 'out')!==false)
			$newEasing .= 'Out';
		$newEasing .= ucfirst(substr($easing, strlen($newEasing), strlen($easing)-strlen($newEasing)));
	}
	else
		$newEasing = $easing;
	
	$output = '<div class="testimonials-container' . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . (!empty($type) ? ' type-' . esc_attr($type) : '') . ($el_class!="" ? ' ' . esc_attr($el_class) : '') . '">';
	if(empty($type) || $type=="big")
		$output .= '<a href="#" class="slider-control left template-arrow-left-1"></a>';
	else if((int)$pagination)
		$output .= '<div class="cs-carousel-pagination"></div>';
	$output .= '<ul class="testimonials-list autoplay-' . esc_attr($autoplay) . ' pause_on_hover-' . esc_attr($pause_on_hover) . ' scroll-' . esc_attr($scroll) . ' effect-' . esc_attr($effect) . ' easing-' . esc_attr($newEasing) . ' duration-' . esc_attr($duration) . ((int)$ontouch ? ' ontouch' : '') . ((int)$onmouse ? ' onmouse' : '') . '">';
	if(is_rtl())
	{
		for($i=$testimonials_count-1; $i>=0; $i--)
		{
			$output .= '<li>
					' . (isset($atts["testimonials_icon" . $i]) && $atts["testimonials_icon" . $i]!="" && $atts["testimonials_icon" . $i]!="none" && (empty($type) || (!empty($type) && $type=="big")) ? '<div class="hexagon small"><div class="sl-small-' . esc_attr($atts["testimonials_icon" . $i]) . '"></div></div>' : '') . '
					' . (isset($atts["testimonials_title" . $i]) && $atts["testimonials_title" . $i]!="" ? '<p>' . str_replace('``', '"', $atts["testimonials_title" . $i]) . '</p>' : '') . '
					' . (!empty($type) && $type=="small" ? '<div class="ornament' . (!empty($atts["testimonials_icon" . $i]) && $atts["testimonials_icon" . $i]!="" && $atts["testimonials_icon" . $i]!="none" ? '"><div class="hexagon small"><div class="sl-small-' . esc_attr($atts["testimonials_icon" . $i]) . '"></div></div>' : ' empty-circle">') . '</div>' : '') . '
					' . ((isset($atts["testimonials_author" . $i]) && $atts["testimonials_author" . $i]!="") || (isset($atts["testimonials_author_subtext" . $i]) && $atts["testimonials_author_subtext" . $i]!="") ? '<div class="author-details-box">' : '') . '
					' . (isset($atts["testimonials_author" . $i]) && $atts["testimonials_author" . $i]!="" ? '<h6>' . $atts["testimonials_author" . $i] . '</h6>' : '') . '
					' . (isset($atts["testimonials_author_subtext" . $i]) && $atts["testimonials_author_subtext" . $i]!="" ? '<div class="author-details">' . $atts["testimonials_author_subtext" . $i] . '</div>' : '') . '
					' . ((isset($atts["testimonials_author" . $i]) && $atts["testimonials_author" . $i]!="") || (isset($atts["testimonials_author_subtext" . $i]) && $atts["testimonials_author_subtext" . $i]!="") ? '</div>' : '') . '
				</li>';
		}
	}
	else
	{
		for($i=0; $i<$testimonials_count; $i++)
		{
			$output .= '<li>
					' . (isset($atts["testimonials_icon" . $i]) && $atts["testimonials_icon" . $i]!="" && $atts["testimonials_icon" . $i]!="none" && (empty($type) || (!empty($type) && $type=="big")) ? '<div class="hexagon small"><div class="sl-small-' . esc_attr($atts["testimonials_icon" . $i]) . '"></div></div>' : '') . '
					' . (isset($atts["testimonials_title" . $i]) && $atts["testimonials_title" . $i]!="" ? '<p>' . str_replace('``', '"', $atts["testimonials_title" . $i]) . '</p>' : '') . '
					' . (!empty($type) && $type=="small" ? '<div class="ornament' . (!empty($atts["testimonials_icon" . $i]) && $atts["testimonials_icon" . $i]!="" && $atts["testimonials_icon" . $i]!="none" ? '"><div class="hexagon small"><div class="sl-small-' . esc_attr($atts["testimonials_icon" . $i]) . '"></div></div>' : ' empty-circle">') . '</div>' : '') . '
					' . ((isset($atts["testimonials_author" . $i]) && $atts["testimonials_author" . $i]!="") || (isset($atts["testimonials_author_subtext" . $i]) && $atts["testimonials_author_subtext" . $i]!="") ? '<div class="author-details-box">' : '') . '
					' . (isset($atts["testimonials_author" . $i]) && $atts["testimonials_author" . $i]!="" ? '<h6>' . $atts["testimonials_author" . $i] . '</h6>' : '') . '
					' . (isset($atts["testimonials_author_subtext" . $i]) && $atts["testimonials_author_subtext" . $i]!="" ? '<div class="author-details">' . $atts["testimonials_author_subtext" . $i] . '</div>' : '') . '
					' . ((isset($atts["testimonials_author" . $i]) && $atts["testimonials_author" . $i]!="") || (isset($atts["testimonials_author_subtext" . $i]) && $atts["testimonials_author_subtext" . $i]!="") ? '</div>' : '') . '
				</li>';
		}
	}
	$output .= '</ul>';
	if(empty($type) || $type=="big")
		$output .= '<a href="#" class="slider-control right template-arrow-left-1"></a>';
	$output .= '</div>';
	return $output;
}

$count = array();
for($i=1; $i<=30; $i++)
	$count[$i] = $i;
	
$params = array(
	array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Type", 'carservice'),
		"param_name" => "type",
		"value" => array(__("Big", 'carservice') => "big", __("Small", 'carservice') => "small")
	),
	array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Display pagination", 'carservice'),
		"param_name" => "pagination",
		"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0),
		"dependency" => Array('element' => "type", 'value' => "small")
	),
	array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Number of testimonials", 'carservice'),
		"param_name" => "testimonials_count",
		"value" => $count
	)
);
for($i=0; $i<30; $i++)
{
	$params[] = array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Icon", 'carservice') . " " . ($i+1),
		"param_name" => "testimonials_icon" . $i,
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
	);
	$params[] = array(
		"type" => "textfield",
		"heading" => __("Text", 'carservice') . " " . ($i+1),
		"param_name" => "testimonials_title" . $i,
		"value" => "Sample Sentence Text"
	);
	$params[] = array(
		"type" => "textfield",
		"class" => "",
		"heading" => __("Author", 'carservice') . " " . ($i+1),
		"param_name" => "testimonials_author" . $i,
		"value" => ""
	);
	$params[] = array(
		"type" => "textfield",
		"class" => "",
		"heading" => __("Author subtitle", 'carservice') . " " . ($i+1),
		"param_name" => "testimonials_author_subtext" . $i,
		"value" => ""
	);
}
$params = array_merge($params, array(
	array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Autoplay", 'carservice'),
		"param_name" => "autoplay",
		"value" => array(__("No", 'carservice') => 0, __("Yes", 'carservice') => 1)
	),
	array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Pause on hover", 'carservice'),
		"param_name" => "pause_on_hover",
		"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0),
		"dependency" => Array('element' => "autoplay", 'value' => 1)
	),
	/*array(
		"type" => "textfield",
		"class" => "",
		"heading" => __("Scroll", 'carservice'),
		"param_name" => "scroll",
		"value" => 1,
		"description" => __("Number of items to scroll in one step", 'carservice')
	),*/
	array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Effect", 'carservice'),
		"param_name" => "effect",
		"value" => array(
			__("scroll", 'carservice') => "scroll", 
			__("none", 'carservice') => "none", 
			__("directscroll", 'carservice') => "directscroll",
			__("fade", 'carservice') => "_fade",
			__("crossfade", 'carservice') => "crossfade",
			__("cover", 'carservice') => "cover",
			__("uncover", 'carservice') => "uncover"
		)
	),
	array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Sliding easing", 'carservice'),
		"param_name" => "easing",
		"value" => array(
			__("easeInOutQuint", 'carservice') => "easeInOutQuint",
			__("swing", 'carservice') => "swing", 
			__("linear", 'carservice') => "linear", 
			__("easeInQuad", 'carservice') => "easeInQuad",
			__("easeOutQuad", 'carservice') => "easeOutQuad",
			__("easeInOutQuad", 'carservice') => "easeInOutQuad",
			__("easeInCubic", 'carservice') => "easeInCubic",
			__("easeOutCubic", 'carservice') => "easeOutCubic",
			__("easeInOutCubic", 'carservice') => "easeInOutCubic",
			__("easeInQuart", 'carservice') => "easeInQuart",
			__("easeOutQuart", 'carservice') => "easeOutQuart",
			__("easeInOutQuart", 'carservice') => "easeInOutQuart",
			__("easeInSine", 'carservice') => "easeInSine",
			__("easeOutSine", 'carservice') => "easeOutSine",
			__("easeInOutSine", 'carservice') => "easeInOutSine",
			__("easeInExpo", 'carservice') => "easeInExpo",
			__("easeOutExpo", 'carservice') => "easeOutExpo",
			__("easeInOutExpo", 'carservice') => "easeInOutExpo",
			__("easeInQuint", 'carservice') => "easeInQuint",
			__("easeOutQuint", 'carservice') => "easeOutQuint",
			__("easeInCirc", 'carservice') => "easeInCirc",
			__("easeOutCirc", 'carservice') => "easeOutCirc",
			__("easeInOutCirc", 'carservice') => "easeInOutCirc",
			__("easeInElastic", 'carservice') => "easeInElastic",
			__("easeOutElastic", 'carservice') => "easeOutElastic",
			__("easeInOutElastic", 'carservice') => "easeInOutElastic",
			__("easeInBack", 'carservice') => "easeInBack",
			__("easeOutBack", 'carservice') => "easeOutBack",
			__("easeInOutBack", 'carservice') => "easeInOutBack",
			__("easeInBounce", 'carservice') => "easeInBounce",
			__("easeOutBounce", 'carservice') => "easeOutBounce",
			__("easeInOutBounce", 'carservice') => "easeInOutBounce"
		)
	),
	array(
		"type" => "textfield",
		"class" => "",
		"heading" => __("Sliding transition speed (ms)", 'carservice'),
		"param_name" => "duration",
		"value" => 750
	),
	/*array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Slide on touch", 'carservice'),
		"param_name" => "ontouch",
		"value" => array(__("No", 'carservice') => 0, __("Yes", 'carservice') => 1)
	),
	array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Slide on mouse", 'carservice'),
		"param_name" => "onmouse",
		"value" => array(__("No", 'carservice') => 0, __("Yes", 'carservice') => 1)
	),*/
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
));
vc_map( array(
	"name" => __("Testimonials", 'carservice'),
	"base" => "cs_testimonials",
	"class" => "",
	"controls" => "full",
	"show_settings_on_create" => true,
	"icon" => "icon-wpb-layer-testimonials",
	"category" => __('Carservice', 'carservice'),
	"params" => $params
));
?>