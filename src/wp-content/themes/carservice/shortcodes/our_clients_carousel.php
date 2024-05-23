<?php
//post
function cs_theme_our_clients_carousel($atts)
{
	extract(shortcode_atts(array(
		"images" => "",
		"type" => "carousel",
		"onclick" => "link_image",
		"custom_links" => "",
		"custom_links_target" => "",
		"autoplay" => "",
		"hide_pagination_control" => "",
		"top_margin" => "none",
		"el_class" => ""
	), $atts));
	if ($onclick=="custom_link")
		$custom_links = explode(',', $custom_links);
	$output = "";
	$output .= '<div class="our-clients-list-container' . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . ($el_class!="" ? ' ' . esc_attr($el_class) : '') . (!empty($type) ? ' type-' . esc_attr($type) : '') . '">
					<ul class="our-clients-list autoplay-' . ($autoplay=='yes' ? '1' : '0') . (!empty($type) ? ' type-' . esc_attr($type) : '') . '">';
	$images = explode(',', $images);
	if(is_rtl() && $type=="carousel")
		$images = array_reverse($images);
	$i = 0;
	foreach($images as $attach_id)
	{
		$output .= '<li' . (!empty($type) && $type=="list" ? ' class="vertical-align"' : '') . '>';
		if(!empty($type) && $type=="list")
			$output .= '<div class="our-clients-item-container"><div class="vertical-align-cell">';
		if($onclick=="link_image")
		{
			$attachment_image = wp_get_attachment_image_src($attach_id, "full");
			$image_url = $attachment_image[0];
			$output .= '<a href="' . esc_url($image_url) . '" class="prettyPhoto">' . wp_get_attachment_image($attach_id, "full", false, array("alt" => "")) . '</a>';
		}
		else if($onclick=="custom_link" && isset($custom_links[$i]) && $custom_links[$i]!="")
			$output .= '<a href="' . esc_url($custom_links[$i]) . '"' . ($custom_links_target=="_blank" ? ' target="_blank"' : '') . '>' . wp_get_attachment_image($attach_id, "full", false, array("alt" => "")) . '</a>';
		else
			$output .= wp_get_attachment_image($attach_id, "full", false, array("alt" => ""));
		if(!empty($type) && $type=="list")
			$output .= '</div></div>';
		$output .= '</li>';
		$i++;
	}
	$output .= '</ul>';
	if($hide_pagination_control!=='yes')
		$output .= '<div class="cs-carousel-pagination"></div>';
	$output .= '</div>';
	return $output;
}

//visual composer
class WPBakeryShortCode_Our_Clients_Carousel extends WPBakeryShortCode {
	public function content( $atts, $content = null ) {
        return cs_theme_our_clients_carousel($atts);
    }
   public function singleParamHtmlHolder($param, $value) {
		global $themename;
		$output = '';
        // Compatibility fixes
        $old_names = array('yellow_message', 'blue_message', 'green_message', 'button_green', 'button_grey', 'button_yellow', 'button_blue', 'button_red', 'button_orange');
        $new_names = array('alert-block', 'alert-info', 'alert-success', 'btn-success', 'btn', 'btn-info', 'btn-primary', 'btn-danger', 'btn-warning');
        $value = str_ireplace($old_names, $new_names, $value);
        //$value = __($value, "carservice");
        //
        $param_name = isset($param['param_name']) ? $param['param_name'] : '';
        $type = isset($param['type']) ? $param['type'] : '';
        $class = isset($param['class']) ? $param['class'] : '';

        if ( isset($param['holder']) == true && $param['holder'] !== 'hidden' ) {
            $output .= '<'.$param['holder'].' class="wpb_vc_param_value ' . esc_attr($param_name) . ' ' . esc_attr($type) . ' ' . esc_attr($class) . '" name="' . esc_attr($param_name) . '">'.$value.'</'.$param['holder'].'>';
        }
        if($param_name == 'images') {
            $images_ids = empty($value) ? array() : explode(',', trim($value));
            $output .= '<ul class="attachment-thumbnails'.( empty($images_ids) ? ' image-exists' : '' ).'" data-name="' . esc_attr($param_name) . '">';
            foreach($images_ids as $image) {
                $img = wpb_getImageBySize(array( 'attach_id' => (int)$image, 'thumb_size' => $themename . '-small-thumb' ));
                $output .= ( $img ? '<li>'.$img['thumbnail'].'</li>' : '<li><img width="150" height="150" test="'.esc_attr($image).'" src="' . WPBakeryVisualComposer::getInstance()->assetURL('vc/blank.gif') . '" class="attachment-thumbnail" alt="" title="" /></li>');
            }
            $output .= '</ul>';
            $output .= '<a href="#" class="column_edit_trigger' . ( !empty($images_ids) ? ' image-exists' : '' ) . '">' . __( 'Add images', 'carservice' ) . '</a>';

        }
        return $output;
	}
}
//visual composer
function cs_theme_our_clients_carousel_vc_init()
{
	$target_arr = array(
		__( 'Same window', 'carservice' ) => '_self',
		__( 'New window', 'carservice' ) => "_blank"
	);
	$params = array(
		array(
			'type' => 'attach_images',
			'heading' => __( 'Images', 'carservice' ),
			'param_name' => 'images',
			'value' => '',
			'description' => __( 'Select images from media library.', 'carservice' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Type', 'carservice' ),
			'param_name' => 'type',
			'value' => array(
				__( 'Carousel', 'carservice' ) => 'carousel',
				__( 'List', 'carservice' ) => 'list'
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'On click action', 'carservice' ),
			'param_name' => 'onclick',
			'value' => array(
				__( 'Open prettyPhoto', 'carservice' ) => 'link_image',
				__( 'None', 'carservice' ) => 'link_no',
				__( 'Open custom links', 'carservice' ) => 'custom_link'
			),
			'description' => __( 'Select action for click event.', 'carservice' )
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'Custom links', 'carservice' ),
			'param_name' => 'custom_links',
			'description' => __( 'Enter links for each slide (Note: divide links with linebreaks (Enter)).', 'carservice' ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Custom link target', 'carservice' ),
			'param_name' => 'custom_links_target',
			'description' => __( 'Select how to open custom links.', 'carservice' ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			),
			'value' => $target_arr
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Slider autoplay', 'carservice' ),
			'param_name' => 'autoplay',
			'description' => __( 'Enable autoplay mode.', 'carservice' ),
			'value' => array( __( 'Yes', 'carservice' ) => 'yes' ),
			'dependency' => array(
				'element' => 'type',
				'value' => array( 'carousel' )
			)
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Hide pagination control', 'carservice' ),
			'param_name' => 'hide_pagination_control',
			'description' => __( 'If checked, pagination controls will be hidden.', 'carservice' ),
			'value' => array( __( 'Yes', 'carservice' ) => 'yes' ),
			'dependency' => array(
				'element' => 'type',
				'value' => array( 'carousel' )
			)
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
	);
	
	vc_map( array(
		"name" => __("Our Clients List", 'carservice'),
		"base" => "our_clients_carousel",
		"class" => "",
		"controls" => "full",
		"show_settings_on_create" => true,
		"icon" => "icon-wpb-layer-carousel",
		"category" => __('Carservice', 'carservice'),
		"params" => $params
	));
}
add_action("init", "cs_theme_our_clients_carousel_vc_init");
?>
