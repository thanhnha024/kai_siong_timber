<?php
$output = $title = $tab_id = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

wp_enqueue_script('jquery_ui_tabs_rotate');

$id = (empty($tab_id) ? sanitize_title( $title ) : $tab_id);
if(substr($id, 0, 4)!="http" && substr($id, 0, 5)!="https")
{
	$output .= "\n\t\t\t" . '<div id="'. esc_attr($id) .'" class="wpb_tab">';/* wpb_row vc_row-fluid ui-tabs-panel wpb_ui-tabs-hide clearfix">';*/
	$output .= ($content=='' || $content==' ') ? __("Empty section. Edit page to add content here.", "carservice") : "\n\t\t\t\t" . wpb_js_remove_wpautop($content);
	$output .= "\n\t\t\t" . '</div> ' . $this->endBlockComment('.wpb_tab');
}

echo $output;