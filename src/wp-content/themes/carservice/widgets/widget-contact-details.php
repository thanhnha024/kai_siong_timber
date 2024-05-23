<?php
class cs_contact_details_widget extends WP_Widget 
{
	/** constructor */
	function __construct() 
	{
		$widget_options = array(
			'classname' => 'cs_contact_details_widget',
			'description' => 'Displays Contact Details Box'
		);
		$control_options = array('width' => 665);
        parent::__construct('carservice_contact_details', __('Contact Details Box', 'carservice'), $widget_options, $control_options);
    }
	
	/** @see WP_Widget::widget */
    function widget($args, $instance) 
	{
        extract($args);

		//these are our widget options
		$title = isset($instance['title']) ? $instance['title'] : "";
		$content = isset($instance['content']) ? $instance['content'] : "";
		$icon_type = isset($instance['icon_type']) ? (array)$instance["icon_type"] : array("");
		$icon_value = isset($instance['icon_value']) ? $instance["icon_value"] : "";
		$icon_target = isset($instance['icon_target']) ? $instance["icon_target"] : "";
		
		echo $before_widget;
		if($title) 
		{
			echo $before_title . apply_filters("widget_title", $title) . $after_title;
		}
		if($content!='')
			echo do_shortcode(apply_filters("widget_text", $content));
	
		$arrayEmpty = true;
		for($i=0; $i<count($icon_type); $i++)
		{
			if($icon_type[$i]!="")
				$arrayEmpty = false;
		}
		if(!$arrayEmpty):
		?>
		<ul class="social-icons gray margin-top-26 clearfix">
			<?php
			for($i=0; $i<count($icon_type); $i++)
			{
				if($icon_type[$i]!=""):
			?>
			<li><a<?php echo ($icon_target[$i]=="new_window" ? " target='_blank'" : ""); ?> href="<?php echo esc_url($icon_value[$i]);?>" class="social-<?php echo esc_attr($icon_type[$i]);?>"></a></li>
			<?php
				endif;
			}
			?>
		</ul>
		<?php
		endif;
        echo $after_widget;
    }
	
	/** @see WP_Widget::update */
    function update($new_instance, $old_instance) 
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['animation'] = strip_tags($new_instance['animation']);
		$instance['content'] = $new_instance['content'];
		$icon_type = (array)$new_instance['icon_type'];
		while(end($icon_type)==="")
			array_pop($icon_type);
		$instance['icon_type'] = $icon_type;
		$instance['icon_value'] = $new_instance['icon_value'];
		$instance['icon_target'] = $new_instance['icon_target'];
		return $instance;
    }
	
	 /** @see WP_Widget::form */
	function form($instance) 
	{	
		$title = esc_attr(isset($instance['title']) ? $instance['title'] : '');
		$animation = esc_attr(isset($instance['animation']) ? $instance['animation'] : '');
		$content = esc_attr(isset($instance['content']) ? $instance['content'] : '');
		$icon_type = (isset($instance["icon_type"]) ? (array)$instance["icon_type"] : array(""));
		$icon_value = (isset($instance["icon_value"]) ?  $instance["icon_value"] : '');
		$icon_target = (isset($instance["icon_target"]) ? $instance["icon_target"] : '');
		$icons = array(
			"angies-list",
			"behance",
			"deviantart",
			"dribbble",
			"email",
			"envato",
			"facebook",
			"flickr",
			"foursquare",
			"github",
			"google-plus",
			"houzz",
			"instagram",
			"linkedin",
			"location",
			"mobile",
			"paypal",
			"pinterest",
			"reddit",
			"rss",
			"skype",
			"soundcloud",
			"spotify",
			"stumbleupon",
			"tumblr",
			"twitter",
			"weibo",
			"whatsapp",
			"vimeo",
			"vine",
			"vk",
			"xing",
			"yelp",
			"youtube"
		);
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'carservice'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php _e('Content', 'carservice'); ?></label>
			<textarea rows="10" class="widefat" id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>"><?php echo $content; ?></textarea>
		</p>
		<?php
		for($i=0; $i<(count($icon_type)<4 ? 4 : count($icon_type)); $i++)
		{
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('icon_type')) . absint($i); ?>"><?php _e('Icon type', 'carservice'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('icon_type')) . absint($i); ?>" name="<?php echo esc_attr($this->get_field_name('icon_type')); ?>[]">
				<option value="">-</option>
				<?php for($j=0; $j<count($icons); $j++)
				{
				?>
				<option value="<?php echo (isset($icons[$j]) ? esc_attr($icons[$j]) : ''); ?>"<?php echo (isset($icon_type[$i]) && $icons[$j]==$icon_type[$i] ? " selected='selected'" : "") ?>><?php echo $icons[$j]; ?></option>
				<?php
				}
				?>
			</select>
			<input style="width: 220px;" type="text" class="regular-text" value="<?php echo (isset($icon_value[$i]) ? esc_attr($icon_value[$i]) : ''); ?>" name="<?php echo esc_attr($this->get_field_name('icon_value')); ?>[]">
			<select name="<?php echo esc_attr($this->get_field_name('icon_target')); ?>[]">
				<option value="same_window"<?php echo (isset($icon_target[$i]) && $icon_target[$i]=="same_window" ? " selected='selected'" : ""); ?>><?php _e('same window', 'carservice'); ?></option>
				<option value="new_window"<?php echo (isset($icon_target[$i]) && $icon_target[$i]=="new_window" ? " selected='selected'" : ""); ?>><?php _e('new window', 'carservice'); ?></option>
			</select>
		</p>
		<?php
		}
		?>
		<p>
			<input type="button" class="button" name="<?php echo esc_attr($this->get_field_name('add_new_button')); ?>" id="<?php echo esc_attr($this->get_field_id('add_new_button')); ?>" value="<?php esc_attr_e('Add icon', 'carservice'); ?>" />
		</p>
		<?php
	}
}
//register widget
function cs_contact_details_widget_init()
{
	return register_widget("cs_contact_details_widget");
}
add_action('widgets_init', 'cs_contact_details_widget_init');
?>