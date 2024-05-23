<?php
wp_enqueue_script('jquery-ui-accordion');
$output = $title = $interval = $el_class = $collapsible = $active_tab = '';
//
extract(shortcode_atts(array(
    'title' => '',
    'interval' => 0,
    'el_class' => '',
    'collapsible' => 'no',
    'active_tab' => '1',
	'top_margin' => 'none'
), $atts));

$el_class = $this->getExtraClass($el_class);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_accordion wpb_content_element '.$el_class.' not-column-inherit', $this->settings['base']);

$output .= "\n\t\t".'<ul class="accordion'.(isset($width) ? esc_attr($width) : '').esc_attr($el_class) . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') .'" data-collapsible='.esc_attr($collapsible).' data-active-tab="'.esc_attr($active_tab).'">';
$output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_accordion_heading'));
$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
$output .= "\n\t\t".'</ul> '.$this->endBlockComment('.wpb_wrapper').$this->endBlockComment((isset($width) ? $width : ''));

/*$output .= "\n\t".'<div class="'.$css_class.'" data-collapsible='.$collapsible.' data-active-tab="'.$active_tab.'">'; //data-interval="'.$interval.'"
$output .= "\n\t\t".'<div class="wpb_wrapper wpb_accordion_wrapper ui-accordion">';
$output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_accordion_heading'));

$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');
$output .= "\n\t".'</div> '.$this->endBlockComment('.wpb_accordion');*/

echo $output;