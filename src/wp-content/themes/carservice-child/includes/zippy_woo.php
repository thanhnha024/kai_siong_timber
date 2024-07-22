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

add_filter('the_content', 'add_product_attribute_into_description_tab');

function add_product_attribute_into_description_tab($content)
{
  if (is_product()) : ?>
    <?php

    global $product;

    echo $content; ?>

    <div class="product-attributes-custom">
      <h4>TECHNICAL DATA</h4>
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
