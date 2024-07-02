<?php
add_action('wp_enqueue_scripts', 'shin_scripts');
function shin_scripts()
{
  $version = time();

  // Load CSS
  wp_enqueue_style('main-style-css', THEME_URL . '-child' . '/assets/main/main.css', array(), $version, 'all');
  // Load JS
  wp_enqueue_script('main-scripts-js', THEME_URL . '-child' . '/assets/main/main.js', array('jquery'), $version, true);
}

//Create button add enquiry has href link id variation table
function add_to_enquiry_btn(){
	if ( function_exists('is_product') && is_product() ) {
        global $product;
        if ( $product && $product->is_type('variable') ) {
    		echo '<a style="margin-left: 10px" class="add_to_enquiry_custom" href="#ProductVariationTable">Add to Enquiry</a>';        
        } else {
          return;
        }
    }
}
add_action( 'woocommerce_after_add_to_cart_button', 'add_to_enquiry_btn', 30 );

//Check variable product has price?
function check_variable_product_price() {
	if ( function_exists('is_product') && is_product()){
	global $product;
    $productid = wc_get_product($product);

    if ($productid && $productid->is_type('variable')) {
        $variations = $productid->get_children();

        foreach ($variations as $variation_id) {
            $variation = wc_get_product($variation_id);

            if ($variation && (float) $variation->get_price() <= 0) {
                return 1;
            }
        }
    }
	return 0;
	}
}
//Display none button enquiry when all attribute has price
function display_none_enquiry_btn_full_price(){
	if (check_variable_product_price() == 0) {
    echo "<style>.add_to_enquiry_custom,.table-responsive,.enquiry-button-custom{display:none!important}</style>";
	}
}
add_action( 'woocommerce_single_product_summary', 'display_none_enquiry_btn_full_price', 10 );
//Display button enquiry for product type simple but no price
function display_button_enquiry_for_simple_no_price(){
    if ( function_exists('is_product') && is_product() ) {
        global $product;
        if ( $product && $product->is_type('simple') ) {
            $price = $product->get_price();
            if ( !empty($price) || $price === '0' ) {
               return;
            } else {
                echo do_shortcode('[product_enquiry_button]');
				echo "<style>.add_to_enquiry_custom,.enquiry-button-custom{display:block !important}</style>";
            }
        } else {
          return;
        }
    }
}
add_action( 'woocommerce_single_product_summary', 'display_button_enquiry_for_simple_no_price', 10 );