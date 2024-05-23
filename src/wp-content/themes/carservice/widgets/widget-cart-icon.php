<?php
class cs_cart_icon_widget extends WP_Widget 
{
	/** constructor */
	function __construct()
	{
		global $themename;
		$widget_options = array(
			'classname' => 'cs_cart_icon_widget',
			'description' => 'Displays Shop Cart Icon'
		);
        parent::__construct('carservice_cart_icon', __('Woocommerce Shop Cart Icon', 'carservice'), $widget_options);
    }
	
	/** @see WP_Widget::widget */
    function widget($args, $instance) 
	{
        extract($args);

		//these are our widget options
		$cart_items_number = $instance["cart_items_number"];
		$icon_target = $instance["icon_target"];

		echo $before_widget;
		global $woocommerce;
		if(is_plugin_active('woocommerce/woocommerce.php'))
		{
			$cart_url = wc_get_cart_url();
			?>
			<a <?php echo ($icon_target=="new_window" ? " target='_blank'" : ""); ?>href="<?php echo esc_url($cart_url);?>" class="cart-icon template-shopping-cart">&nbsp;<?php if($cart_items_number=="yes"): ?><span class="cart-items-number"<?php echo (!(int)$woocommerce->cart->cart_contents_count ? ' style="display: none;"' : ''); ?>><?php echo $woocommerce->cart->cart_contents_count; ?></span><?php endif;?></a>
			<?php
		}
        echo $after_widget;
    }
	
	/** @see WP_Widget::update */
    function update($new_instance, $old_instance) 
	{
		$instance = $old_instance;
		$instance['cart_items_number'] = strip_tags($new_instance['cart_items_number']);
		$instance['icon_target'] = $new_instance['icon_target'];
		return $instance;
    }
	
	 /** @see WP_Widget::form */
	function form($instance) 
	{
		$cart_items_number = (isset($instance["cart_items_number"]) ? esc_attr($instance["cart_items_number"]) : '');
		$icon_target = (isset($instance["icon_target"]) ? $instance["icon_target"] : '');
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('cart_items_number')); ?>"><?php _e('Show cart items number', 'carservice'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('cart_items_number')); ?>" name="<?php echo esc_attr($this->get_field_name('cart_items_number')); ?>">
				<option value="yes"<?php echo ($cart_items_number=="yes" ? ' selected="selected"' : ''); ?>><?php _e('yes', 'carservice'); ?></option>
				<option value="no"<?php echo ($cart_items_number=="no" ? ' selected="selected"' : ''); ?>><?php _e('no', 'carservice'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('icon_target')); ?>"><?php _e('Icon target', 'carservice'); ?></label>
			<select name="<?php echo esc_attr($this->get_field_name('icon_target')); ?>">
				<option value="same_window"<?php echo ($icon_target=="same_window" ? " selected='selected'" : ""); ?>><?php _e('same window', 'carservice'); ?></option>
				<option value="new_window"<?php echo ($icon_target=="new_window" ? " selected='selected'" : ""); ?>><?php _e('new window', 'carservice'); ?></option>
			</select>
		</p>
		<?php
	}
}
//register widget
function cs_cart_icon_widget_init()
{
	return register_widget("cs_cart_icon_widget");
}
add_action('widgets_init', 'cs_cart_icon_widget_init');
?>