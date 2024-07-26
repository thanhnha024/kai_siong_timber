<?php
// Display single product do_shortcode('[product_enquiry_button]')
function product_enquiry_button_shortcode($atts)
{
  global $product;

  if (!$product || 'simple' !== $product->get_type()) {
    return 'No product found or not a simple product.';
  }

  $product_id = $product->get_id();
  $product_title = $product->get_name();

  ob_start();
?>
  <div class="enquiry-button-single">
    <button class="enquiry-single-button" product-id="<?php echo esc_attr($product_id); ?>" data-product-title="<?php echo esc_attr($product_title); ?>">Enquiry</button>
  </div>
  <div id="success-message" style="display:none;">Items added successfully!</div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelector('.enquiry-single-button').addEventListener('click', function() {
        var productId = this.getAttribute('product-id');
        var productTitle = this.getAttribute('data-product-title');
        var quantity = 1; // Default quantity is 1
        var productsToAdd = {
          enquiry: [{
            productId: productId,
            quantity: quantity
          }]
        };

        if (productsToAdd.enquiry.length > 0) {
          jQuery.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            data: {
              action: 'add_to_enquiry_cart',
              products: productsToAdd
            },
            success: function(response) {
              var successMessage = document.getElementById('success-message');
              successMessage.style.display = 'block';
              location.reload();

            },
            error: function(error) {
              alert('Failed to add product to enquiry cart.');
            }
          });
        } else {
          alert('No product to add.');
        }
      });
    });
  </script>
<?php
  return ob_get_clean();
}


add_shortcode('product_enquiry_button', 'product_enquiry_button_shortcode');

add_action('wp_ajax_add_to_enquiry_cart', 'add_to_enquiry_cart');
add_action('wp_ajax_nopriv_add_to_enquiry_cart', 'add_to_enquiry_cart');

function add_to_enquiry_cart()
{
  if (isset($_POST['products']) && is_array($_POST['products'])) {
    $products = $_POST['products'];

    if (!empty($products['enquiry'])) {
      $product = reset($products['enquiry']);
      $product_id = intval($product['productId']);
      $quantity = intval($product['quantity']);

      if ($product_id > 0 && $quantity > 0) {
        $enquiry_cart = WC()->session->get('enquiry_cart', array());
        if (isset($enquiry_cart[$product_id])) {
          $enquiry_cart[$product_id]['quantity'] += $quantity;
        } else {
          $enquiry_cart[$product_id] = array(
            'product_id' => $product_id,
            'quantity' => $quantity,
          );
        }
        WC()->session->set('enquiry_cart', $enquiry_cart);
      } else {
        wp_send_json_error('Invalid product ID or quantity.');
      }
    } else {
      wp_send_json_error('No products to process.');
    }
  } else {
    wp_send_json_error('Invalid request.');
  }
}

// Enquiry button shortcode [enquiry_button_cart]
function enquiry_button_shortcode()
{
     if (!WC()->session) {
		WC()->session = new WC_Session_Handler();
		WC()->session->init();
  	}
    $enquiry_cart = WC()->session->get('enquiry_cart', array());
    $enquiry_count = 0;
    foreach ($enquiry_cart as $item) {
        $enquiry_count += intval($item['quantity']);
    }

    ob_start();
?>
<div class="enquiry-button-nav"><a href="/enquiry/" class="wp-block-button__link wp-element-button">Enquiry | <span><?php echo $enquiry_count; ?></span> </a></div>
<?php
    return ob_get_clean();
}

add_shortcode('enquiry_button_cart', 'enquiry_button_shortcode');


?>