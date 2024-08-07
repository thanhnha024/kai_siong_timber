<?php
// add_action('after_setup_theme', 'yourtheme_setup');

// function yourtheme_setup()
// {
//   add_theme_support('wc-product-gallery-zoom');
//   add_theme_support('wc-product-gallery-lightbox');
//   add_theme_support('wc-product-gallery-slider');
// }


add_filter('woocommerce_product_tabs', 'force_description_product_tabs');
function force_description_product_tabs($tabs)
{

  $tabs['description'] = array(
    'title'    => __('Description', 'woocommerce'),
    'priority' => 10,
    'callback' => 'woocommerce_product_description_tab',
  );

  return $tabs;
}

add_filter('woocommerce_product_tabs', 'remove_product_tabs', 9999);

function remove_product_tabs($tabs)
{
  unset($tabs['additional_information']);
  return $tabs;
}

add_filter('woocommerce_product_tabs', 'remove_review_tab', 9999);

function remove_review_tab($tabs)
{
  unset($tabs['reviews']);
  return $tabs;
}



function add_product_attribute_into_description_tab($content)
{
  if (is_product()) : ?>
    <?php

    global $product;

    echo $content; ?>

    <div class="product-attributes-custom">
      <?php do_action('woocommerce_product_additional_information', $product); ?>
    </div>

  <?php
  else : ?>
<?php
    return $content;

  endif;
}


// change position of content product

// remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );

add_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 0);

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);


remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10);


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

add_action('woocommerce_before_shop_loop_item', 'show_stock_status');

function show_stock_status() {
    global $product;

    if (!$product instanceof WC_Product) {
        return;
    }

    if ($product->is_in_stock()) {
        echo '<p class="stock-status in-stock">In Stock</p>';
    } else {
        echo '<p class="stock-status out-of-stock">Out of Stock</p>';
    }
}
