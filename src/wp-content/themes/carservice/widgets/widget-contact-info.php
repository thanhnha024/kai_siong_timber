<?php
class cs_contact_info_widget extends WP_Widget 
{
	/** constructor */
	function __construct() 
	{
        $widget_options = array(
			'classname' => 'cs_contact_info_widget',
			'description' => 'Displays Contact Informations Box'
		);
        parent::__construct('carservice_contact_info', __('Contact Informations Box', 'carservice'), $widget_options);
    }
	
	/** @see WP_Widget::widget */
    function widget($args, $instance) 
	{
        extract($args);

		//these are our widget options
		$title = $instance['title'];
		$content = $instance['content'];
		$icon = $instance["icon"];

		echo $before_widget;
		echo '<div class="contact-details-box' . ($icon!="" && $icon!="none" ? ' sl-small-' . esc_attr($icon) : '') . '">';
		if($title) 
		{
			echo $before_title . apply_filters("widget_title", $title) . $after_title;
		}
		if($content!='')
			echo '<p>' . do_shortcode(apply_filters("widget_text", nl2br($content))) . '</p>';
		echo '</div>';
        echo $after_widget;
    }
	
	/** @see WP_Widget::update */
    function update($new_instance, $old_instance) 
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['content'] = $new_instance['content'];
		$instance['icon'] = $new_instance['icon'];
		return $instance;
    }
	
	 /** @see WP_Widget::form */
	function form($instance) 
	{	
		$title = esc_attr(isset($instance['title']) ? $instance['title'] : '');
		$content = esc_attr(isset($instance['content']) ? $instance['content'] : '');
		$icon = (isset($instance["icon"]) ? $instance["icon"] : '');
		$icons = array(
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
		);
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'carservice'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('icon')); ?>"><?php _e('Icon', 'carservice'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('icon')); ?>" name="<?php echo esc_attr($this->get_field_name('icon')); ?>">
				<?php foreach($icons as $ico)
				{
				?>
				<option value="<?php echo (isset($ico) ? esc_attr($ico) : ''); ?>"<?php echo (isset($icon) && $ico==$icon ? " selected='selected'" : "") ?>><?php echo $ico; ?></option>
				<?php
				}
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php _e('Content', 'carservice'); ?></label>
			<textarea rows="10" class="widefat" id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>"><?php echo $content; ?></textarea>
		</p>
		<?php
	}
}
//register widget
function cs_contact_info_widget_init()
{
	return register_widget("cs_contact_info_widget");
}
add_action('widgets_init', 'cs_contact_info_widget_init');
?>