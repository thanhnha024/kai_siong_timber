<?php

// Add the shortcode variation table do_shortcode('[product_variation_table]')
function product_variation_table_shortcode($atts)
{
  global $product;

  if (!$product || 'product' !== get_post_type($product->get_id())) {
    return '';
  }

  $variations = $product->get_children();
  if (empty($variations)) {
    return;
  }

  $attributes = $product->get_attributes();

  ob_start();
?>
  <div class="table-responsive" id="ProductVariationTable" style="margin: 30px 0">
	  <h4>Table Variation</h4>
    <table class="product-variation-table">
      <thead>
        <tr>
          <th>SKU</th>
          <?php
          foreach ($attributes as $attribute_name => $attribute) {
            echo '<th>' . wc_attribute_label($attribute_name) . '</th>';
          }
          ?>
          <th style="width: 200px; text-align: center">Quantity</th>
        </tr>
      </thead>
      <tbody>
          <?php
          foreach ($variations as $variation_id) {
              $variation_obj = new WC_Product_Variation($variation_id);
              $attributes_price = $variation_obj->get_price();
              
          ?>
              <tr>
                  <td><?php echo $variation_obj->get_sku(); ?></td>
                  <?php
                  foreach ($attributes as $attribute_name => $attribute) {
                      $attribute_value = $variation_obj->get_attribute($attribute_name);
                      echo '<td>' . esc_html($attribute_value ? $attribute_value : '-') . '</td>';
                  }
                  ?>
                  <td style="display:flex;justify-content:center">
                      <div class="quantity" data-price="<?php echo $attributes_price; ?>">
                          <input type="button" value="-" class="qty_button minus">
                          <input type="number" step="1" min="0" max="<?php echo $variation_obj->get_stock_quantity(); ?>" name="quantity" value="0" title="Qty" class="input-text qty text" size="4" data-variation-id="<?php echo $variation_obj->get_id(); ?>" data-price="<?php echo $variation_obj->get_price(); ?>" />
                          <input type="button" value="+" class="qty_button plus">
                      </div>
                  </td>
              </tr>
          <?php
              
          }
          ?>
      </tbody>
    </table>
  </div>
  <div class="enquiry-button-custom">
    <button id="enquiry-button">Enquiry</button>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
//       document.querySelectorAll('.quantity').forEach(function(quantityWrapper) {
//           var input = quantityWrapper.querySelector('input.qty');
//           var minusButton = quantityWrapper.querySelector('.minus');
//           var plusButton = quantityWrapper.querySelector('.plus');

//           minusButton.addEventListener('click', function() {
//               var currentValue = parseInt(input.value, 10);
//               if (!isNaN(currentValue) && currentValue > 0) {
//                   input.value = currentValue - 1;
//               }
//           });

//           plusButton.addEventListener('click', function() {
//               var currentValue = parseInt(input.value, 10);
//               var max = parseInt(input.getAttribute('max'), 10);
//               if (!isNaN(currentValue) && (isNaN(max) || currentValue < max)) {
//                   input.value = currentValue ;
//               }
//           });
//       });

      document.getElementById('enquiry-button').addEventListener('click', function() {
        var quantities = document.querySelectorAll('.quantity input.qty');
        var productsToAdd = {
          cart: [],
          enquiry: []
        };

        quantities.forEach(function(input) {
          var quantity = parseInt(input.value, 10);
          var variationId = input.getAttribute('data-variation-id');
          var price = input.getAttribute('data-price');
          if (quantity > 0) {
            productsToAdd.enquiry.push({
                variationId: variationId,
                quantity: quantity
              });
          }
        });

        if (productsToAdd.cart.length > 0 || productsToAdd.enquiry.length > 0) {
          jQuery.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            data: {
              action: 'add_multiple_to_cart_or_enquiry',
              products: productsToAdd
            },
            success: function(response) {
              location.reload();
            },
            error: function(error) {
              alert('Failed to process items.');
            }
          });
        } else {
          alert('No products to add.');
        }
      });
    });
  </script>
<?php

  return ob_get_clean();
}

add_shortcode('product_variation_table', 'product_variation_table_shortcode');

add_action('wp_ajax_add_multiple_to_cart_or_enquiry', 'add_multiple_to_cart_or_enquiry');
add_action('wp_ajax_nopriv_add_multiple_to_cart_or_enquiry', 'add_multiple_to_cart_or_enquiry');

function add_multiple_to_cart_or_enquiry()
{
  if (isset($_POST['products']) && is_array($_POST['products'])) {
    $products = $_POST['products'];

    // Process cart products
    if (!empty($products['cart'])) {
      foreach ($products['cart'] as $product) {
        $variation_id = intval($product['variationId']);
        $quantity = intval($product['quantity']);
        if ($variation_id > 0 && $quantity > 0) {
          WC()->cart->add_to_cart($variation_id, $quantity);
        }
      }
    }
    // Process enquiry products
     if (!empty($products['enquiry'])) {
      foreach ($products['enquiry'] as $product) {
        $variation_id = intval($product['variationId']);
        $quantity = intval($product['quantity']);
        if ($variation_id > 0 && $quantity > 0) {
          $enquiry_cart = WC()->session->get('enquiry_cart', array());
          if (isset($enquiry_cart[$variation_id])) {
            $enquiry_cart[$variation_id]['quantity'] += $quantity;
          } else {
            $enquiry_cart[$variation_id] = array(
              'variation_id' => $variation_id,
              'quantity' => $quantity
            );
          }
          WC()->session->set('enquiry_cart', $enquiry_cart);
        }
      }
    }

    wp_send_json_success('Products processed successfully.');
  } else {
    wp_send_json_error('No products to process.');
  }
}
//



?>