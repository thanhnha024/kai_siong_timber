<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $width
 * @var $css
 * @var $offset
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Column_Inner
 */
$el_class = $width = $el_id = $css = $offset = $type = '';
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$width = wpb_translateColumnWidthToSpan( $width );
$width = vc_column_offset_class_merge( $offset, $width );

$css_classes = array(
	$this->getExtraClass( $el_class ),
	'wpb_column',
	'vc_column_container',
	$width,
);
if(!empty($top_margin) && $top_margin!="none")
	$css_classes[] = $top_margin;
if(!empty($type)!="")
	$css_classes[] = $type;
if($type=="cost-calculator-container" && empty($action))
	$css_classes[] = "cost-calculator-form";

if ( vc_shortcode_custom_css_has_property( $css, array(
	'border',
	'background',
) ) ) {
	$css_classes[] = 'vc_col-has-fill';
}

$wrapper_attributes = array();

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
else if($type=="cost-calculator-container")
{
	$wrapper_attributes[] = 'id="cost-calculator-form"';
}
if($type=="cost-calculator-container")
{
	$wrapper_attributes[] = 'action="' . (!empty($action) ? esc_attr($action) : '#') . '"';
	$wrapper_attributes[] = 'method="post"';
}
if($type=="cost-calculator-container")
	$output .= '<form ' . implode( ' ', $wrapper_attributes ) . '>';
else
	$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
$output .= '<div class="vc_column-inner ' . esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) ) . '">';
$output .= '<div class="wpb_wrapper">';
$output .= wpb_js_remove_wpautop( $content );
$output .= '</div>';
$output .= '</div>';
if($type=="cost-calculator-container")
	$output .= '</form>';
else
	$output .= '</div>';

echo $output;
