<?php
$output = $title = $interval = $el_class = '';
extract(shortcode_atts(array(
    'title' => '',
    'interval' => 0,
    'el_class' => '',
	'top_margin' => 'none'
), $atts));

wp_enqueue_script('jquery-ui-tabs');

$el_class = $this->getExtraClass($el_class);

$element = 'wpb_tabs';
if ( 'vc_tour' == $this->shortcode) $element = 'wpb_tour';

// Extract tab titles
//preg_match_all( '/vc_tab title="([^\"]+)"(\stab_id\=\"([^\"]+)\")(\sicon\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );
preg_match_all( '/vc_tab(\sicon\=\"([^\"]+)\"){0,1} title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );
$tab_titles = array();

if ( isset($matches[0]) ) { $tab_titles = $matches[0]; }
$tabs_nav = '';
$tabs_nav .= '<ul class="tabs_navigation clearfix">';
foreach ( $tab_titles as $tab ) {
	//preg_match('/title="([^\"]+)"(\stab_id\=\"([^\"]+)\")(\sicon\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
	preg_match('/(\sicon\=\"([^\"]+)\"){0,1} title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );

	if(isset($tab_matches[3][0])) {
		$href = (isset($tab_matches[5][0]) ? $tab_matches[5][0] : sanitize_title( $tab_matches[3][0] ) );
		if(substr($href, 0, 4)!="http" && substr($href, 0, 5)!="https")
			$href = "#" . $href;
		$tabs_nav .= '<li><a href="'. esc_url($href) .'"' . (isset($tab_matches[2][0]) ? ' class="' . esc_attr($tab_matches[2][0]) . '"' : '') . '>' . $tab_matches[3][0] . '</a></li>';

	}
}
$tabs_nav .= '</ul>'."\n";

$output .= '<div class="clearfix tabs'.(isset($width) ? esc_attr($width) : '').esc_attr($el_class).($top_margin!="none" ? ' ' . esc_attr($top_margin) : '').'" data-interval="'.esc_attr($interval).'">';
$output .= "\n\t\t\t".$tabs_nav;
$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
$output .= "\n\t\t".'</div>'.$this->endBlockComment('.wpb_wrapper').$this->endBlockComment((isset($width) ? $width : ''));

echo $output;