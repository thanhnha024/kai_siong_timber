<?php
class cs_contact_details_list_widget extends WP_Widget 
{
	/** constructor */
	function __construct() 
	{
		$widget_options = array(
			'classname' => 'cs_contact_details_list_widget',
			'description' => 'Displays Constact Details List'
		);
		$control_options = array('width' => 625);
        parent::__construct('carservice_contact_details_list', __('Contact Details List', 'carservice'), $widget_options, $control_options);
    }
	
	/** @see WP_Widget::widget */
    function widget($args, $instance) 
	{
        extract($args);

		//these are our widget options
		$icon = isset($instance['icon']) ? $instance["icon"] : "";
		$text = isset($instance['text']) ? (array)$instance["text"] : array("");
		$url = isset($instance['url']) ? $instance["url"] : "";
		$target = isset($instance['target']) ? $instance["target"] : "";

		echo $before_widget;
		$arrayEmpty = true;
		for($i=0; $i<count($text); $i++)
		{
			if($text[$i]!="")
				$arrayEmpty = false;
		}
		if(!$arrayEmpty):
		?>
		<ul class="contact-details clearfix">
			<?php
			for($i=0; $i<count($text); $i++)
			{
				if($text[$i]!=""):
			?>
				<li class="template-<?php echo esc_attr($icon[$i]); ?>">
					<?php if($url[$i]!=""): ?>
					<a <?php echo ($target[$i]=="new_window" ? " target='_blank' " : ""); ?>href="<?php echo esc_url($url[$i]);?>">
					<?php endif;
					echo $text[$i];
					if($url[$i]!=""): ?>
					</a>
					<?php
					endif;
					?>
				</li>
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
		$instance['icon'] = isset($new_instance['icon']) ? $new_instance['icon'] : "";
		$instance['text'] = isset($new_instance['text']) ? (array)$new_instance['text'] : array("");
		$instance['url'] = isset($new_instance['url']) ? $new_instance['url'] : "";
		$instance['target'] = isset($new_instance['target']) ? $new_instance['target'] : "";
		return $instance;
    }
	
	 /** @see WP_Widget::form */
	function form($instance) 
	{	
		if(!isset($instance["text"])):
		?>
			<input type="hidden" id="widget-contact-details-list-button_id" value="<?php echo esc_attr($this->get_field_id('add_new_button')); ?>">
		<?php
		endif;
		$icon = (isset($instance["icon"]) ? $instance["icon"] : '');
		$text = (isset($instance["text"]) ? (array)$instance["text"] : array(""));
		$url = (isset($instance["url"]) ? $instance["url"] : '');
		$target = (isset($instance["target"]) ? $instance["target"] : '');
		$icons = array(
			"arrow-circle-down",
			"arrow-circle-right",
			"arrow-dropdown",
			"arrow-left-1",
			"arrow-left-2",
			"arrow-right-1",
			"arrow-right-2",
			"arrow-menu",
			"arrow-up",
			"bubble",
			"bullet",
			"calendar",
			"clock",
			"location",
			"eye",
			"mail",
			"map-marker",
			"phone",
			"search",
			"shopping-cart"
		);
		for($i=0; $i<(count($text)<4 ? 4 : count($text)); $i++)
		{
		?>
		<p class="widget-border">
			<label for="<?php echo esc_attr($this->get_field_id('icon')) . absint($i); ?>"><?php _e('Icon', 'carservice'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('icon')) . absint($i); ?>" name="<?php echo esc_attr($this->get_field_name('icon')); ?>[]">
				<option value="">-</option>
				<?php for($j=0; $j<count($icons); $j++)
				{
				?>
				<option value="<?php echo esc_attr($icons[$j]); ?>"<?php echo (isset($icon[$i]) && $icons[$j]==$icon[$i] ? " selected='selected'" : "") ?>><?php echo $icons[$j]; ?></option>
				<?php
				}
				?>
			</select>
			<label for="<?php echo esc_attr($this->get_field_id('text')) . absint($i); ?>"><?php _e('Text', 'carservice'); ?></label>
			<input style="width: 220px;" type="text" class="regular-text" value="<?php echo (isset($text[$i]) ? esc_attr($text[$i]) : ''); ?>" name="<?php echo esc_attr($this->get_field_name('text')); ?>[]">
			<br>
			<label for="<?php echo esc_attr($this->get_field_id('url')) . absint($i); ?>"><?php _e('Url', 'carservice'); ?></label>
			<input style="width: 220px;" type="text" class="regular-text" value="<?php echo (isset($url[$i]) ? esc_attr($url[$i]) : ''); ?>" name="<?php echo esc_attr($this->get_field_name('url')); ?>[]">
			<label for="<?php echo esc_attr($this->get_field_id('target')) . absint($i); ?>"><?php _e('Url target', 'carservice'); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name('target')); ?>[]">
				<option value="same_window"<?php echo (isset($target[$i]) && $target[$i]=="same_window" ? " selected='selected'" : ""); ?>><?php _e('same window', 'carservice'); ?></option>
				<option value="new_window"<?php echo (isset($target[$i]) && $target[$i]=="new_window" ? " selected='selected'" : ""); ?>><?php _e('new window', 'carservice'); ?></option>
			</select>
		</p>
		<?php
		}
		?>
		<p>
			<input type="button" class="button" name="<?php echo esc_attr($this->get_field_name('add_new_button')); ?>" id="<?php echo esc_attr($this->get_field_id('add_new_button')); ?>" value="<?php esc_attr_e('Add item', 'carservice'); ?>" />
		</p>
		<?php
	}
}
//register widget
function cs_contact_details_list_widget_init()
{
	return register_widget("cs_contact_details_list_widget");
}
add_action('widgets_init', 'cs_contact_details_list_widget_init');
?>