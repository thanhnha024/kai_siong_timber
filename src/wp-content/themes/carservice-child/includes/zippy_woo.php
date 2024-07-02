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
