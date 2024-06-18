<?php
$output = $title = $tab_id = '';
extract(shortcode_atts($this->predefined_atts, $atts));

wp_enqueue_script('jquery_ui_tabs_rotate');

/*$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_tab ui-tabs-panel wpb_ui-tabs-hide clearfix', $this->settings['base']);
$output .= "\n\t\t\t" . '<div id="tab-'. (empty($tab_id) ? sanitize_title( $title ) : $tab_id) .'" class="'.$css_class.'">';
$output .= ($content=='' || $content==' ') ? __("Empty section. Edit page to add content here.", "carservice") : "\n\t\t\t\t" . wpb_js_remove_wpautop($content);
$output .= "\n\t\t\t" . '</div> ' . $this->endBlockComment('.wpb_tab');*/

$id = (empty($tab_id) ? sanitize_title( $title ) : $tab_id);
if(substr($id, 0, 4)!="http" && substr($id, 0, 5)!="https")
{
	$output .= "\n\t\t\t" . '<div id="'. esc_attr($id) .'" class="wpb_tab">';/* wpb_row vc_row-fluid ui-tabs-panel wpb_ui-tabs-hide clearfix">';*/
	$output .= ($content=='' || $content==' ') ? __("Empty section. Edit page to add content here.", "carservice") : "\n\t\t\t\t" . wpb_js_remove_wpautop($content);
	$output .= "\n\t\t\t" . '</div> ' . $this->endBlockComment('.wpb_tab');
}

echo $output;