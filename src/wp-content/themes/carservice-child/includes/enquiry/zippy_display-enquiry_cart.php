<?php 
// Display enquiry cart do_shortcode('[display_enquiry_cart]');
function display_enquiry_cart()
{
  $enquiry_cart = WC()->session->get('enquiry_cart', array());

  if (empty($enquiry_cart)) {
    return;
  }

  ob_start();
?>
  <div class="woocommerce enquiry-cart">
    <h2>Enquiry Cart</h2>
    <ul class="woocommerce-mini-cart cart_list product_list_widget">
    <?php
      foreach ($enquiry_cart as $key => $item) {
        // Check if it's a variation or simple product
        $product = isset($item['variation_id']) && $item['variation_id'] ? wc_get_product($item['variation_id']) : wc_get_product($item['product_id']);

        $product_name = $product->get_name();
        $product_link = $product->get_permalink();
        $product_image = $product->get_image();
        $quantity = $item['quantity'];
      ?>
        <li class="woocommerce-mini-cart-item mini_cart_enquiry_item" data-item-key="<?php echo $key; ?>">
          <div class="enquiry-product-remove">
            <a href="#" class="remove remove_from_cart_button remove-enquiry-item" aria-label="Remove <?php echo $product_name; ?> from cart" data-item-key="<?php echo $key; ?>">×</a>
          </div>
          <div class="enquiry-product-info">
            <a href="<?php echo $product_link; ?>">
              <?php echo $product_name; ?>
            </a>
            <span class="empuiry-quantity"><?php echo $quantity; ?> items</span>
          </div>
          <a class="enquiry-product-image" href="<?php echo $product_link; ?>">
            <?php echo $product_image; ?>

          </a>
        </li>
      <?php
      }
      ?>
    </ul>
    <div class="enquiry-cart-actions">
      <a class="button enquiry-cart-button" href="/enquiry-cart/" id="send-enquiry">View Enquiry</a>
    </div>
  </div>
 
<?php
  return ob_get_clean();
}

add_shortcode('display_enquiry_cart', 'display_enquiry_cart');
//
