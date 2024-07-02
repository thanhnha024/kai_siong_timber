<?php
function display_enquiry_cart_page()
{
    if (isset(WC()->session)) {
        if (!is_admin() && !WC()->session->has_session()) {
            WC()->session->set_customer_session_cookie(true);
        }
    }
    $enquiry_cart = WC()->session->get('enquiry_cart', array());

    // Merge duplicate products
    $merged_cart = array();
    foreach ($enquiry_cart as $item) {
        $product_id = isset($item['product_id']) ? $item['product_id'] : null;
        $variation_id = isset($item['variation_id']) ? $item['variation_id'] : null;
        $key = ($product_id ? $product_id : '') . ($variation_id ? $variation_id : '');
        if ($key != '') {
            if (isset($merged_cart[$key])) {
                $merged_cart[$key]['quantity'] += $item['quantity'];
            } else {
                $merged_cart[$key] = $item;
            }
        }
    }
    $enquiry_cart = $merged_cart;

    if (empty($enquiry_cart)) {
        return 'Your enquiry cart is empty.';
    }

    ob_start();
?>
    <div class="woocommerce enquiry-table">
        <form method="post" id="enquiry_cart_form">
            <table class="enquiry-cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($enquiry_cart as $key => $item) {
                        $product = isset($item['variation_id']) && $item['variation_id'] ? wc_get_product($item['variation_id']) : wc_get_product($item['product_id']);
                        $product_name = $product->get_name();
                        $product_link = $product->get_permalink();
                        $product_image = $product->get_image();
                        $quantity = $item['quantity'];
                    ?>
                        <tr class="table_cart_enquiry_item" data-item-key="<?php echo $key; ?>">
                            <td>
                                <div class="d-flex align-items-center enquiry-product">
                                    <div class="product-image">
                                        <a href="<?php echo $product_link; ?>">
                                            <?php echo $product_image; ?>
                                        </a>
                                    </div>
                                    <div class="enquiry-product-info">
                                        <a href="<?php echo $product_link; ?>">
                                            <?php echo $product_name; ?>
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="enquiry-product-quantity quantity w-100 d-flex justify-content-center">
                                    <button class="quantity-button minus" type="button" data-item-key="<?php echo $key; ?>">-</button>
                                    <input type="number" class="input-text qty text" name="quantity[<?php echo $key; ?>]" value="<?php echo $quantity; ?>" min="1">
                                    <button class="quantity-button plus" type="button" data-item-key="<?php echo $key; ?>">+</button>
                                </div>
                            </td>
                            <td>
                                <div class="enquiry-product-remove w-100 d-flex justify-content-center">
                                    <a href="#" class="remove remove-enquiry-item" aria-label="Remove <?php echo $product_name; ?> from cart" data-item-key="<?php echo $key; ?>">×</a>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="my-enquiry-actions d-flex justify-content-between align-items-center">
                <div class="back-to-shop"><a href="/shop/">Back to Shop</a></div>
                <div class="update-enquiry-cart-button"><button type="submit" name="update_enquiry_cart" value="1">Update Listing</button></div>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Quantity adjustment buttons
            document.querySelectorAll('.quantity-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    var itemKey = this.getAttribute('data-item-key');
                    var inputField = document.querySelector('input[name="quantity[' + itemKey + ']"]');
                    var currentValue = parseInt(inputField.value);
                    if (this.classList.contains('minus')) {
                        if (currentValue > 1) {
                            inputField.value = currentValue - 1;
                        }
                    } else if (this.classList.contains('plus')) {
                        inputField.value = currentValue + 1;
                    }
                });
            });

            document.querySelectorAll('.remove-enquiry-item').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    var itemKey = this.getAttribute('data-item-key');

                    jQuery.ajax({
                        url: wc_add_to_cart_params.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'remove_enquiry_item',
                            item_key: itemKey
                        },
                        success: function(response) {
                            if (response.success) {
                                var rows = document.querySelectorAll('tr[data-item-key="' + itemKey + '"]');

                                rows.forEach(function(row) {
                                    row.remove();
                                });
                                if (document.querySelectorAll('.enquiry-cart-table tbody tr').length === 0) {
                                    document.querySelector('.enquiry-cart-table').innerHTML = '<tbody><tr><td colspan="3">Your enquiry cart is empty.</td></tr></tbody>';
                                }
                                location.reload();
                            } else {
                                console.error('Error removing item:', response.data.message);
                            }
                            loadingIndicator.style.display = 'none';
                        },
                        error: function(error) {
                            console.error('Error removing item:', error);
                            loadingIndicator.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
    <div class="enquiry-form w-100">
        <h2>Send Enquiry</h2>
        <form method="post" action="">
            <p>
                <label for="enquiry_name">Your Name<span class="text-danger">*</span>:</label>
                <input type="text" id="enquiry_name" name="enquiry_name" required>
            </p>
            <p>
                <label for="enquiry_email">Your Email<span class="text-danger">*</span>:</label>
                <input type="email" id="enquiry_email" name="enquiry_email" required>
            </p>
            <p>
                <label for="enquiry_message">Message:</label>
                <textarea id="enquiry_message" name="enquiry_message" rows="4"></textarea>
            </p>
            <p>
                <button type="submit" name="send_enquiry" value="1">Confirm Enquiry</button>
            </p>
        </form>
    </div>
<?php
    if (isset($_POST['update_enquiry_cart']) && $_POST['update_enquiry_cart'] == 1) {
        foreach ($_POST['quantity'] as $key => $quantity) {
            if ($quantity > 0) {
                $enquiry_cart[$key]['quantity'] = intval($quantity);
            } else {
                unset($enquiry_cart[$key]);
            }
        }
        WC()->session->set('enquiry_cart', $enquiry_cart);
    }

    if (isset($_POST['send_enquiry']) && $_POST['send_enquiry'] == 1) {
        handle_enquiry_form_submission($enquiry_cart);
    }

    return ob_get_clean();
}

function remove_enquiry_item()
{

    $item_key = isset($_POST['item_key']) ? sanitize_text_field($_POST['item_key']) : '';
    if (empty($item_key)) {
        wp_send_json_error(array('message' => 'Invalid item key.'));
    }
    if (isset(WC()->session)) {
        if (!is_admin() && !WC()->session->has_session()) {
            WC()->session->set_customer_session_cookie(true);
        }
    }

    $enquiry_cart = WC()->session->get('enquiry_cart', array());
    unset($enquiry_cart[$item_key]);
    
    WC()->session->set('enquiry_cart', $enquiry_cart);

    wp_send_json_success();
}
add_action('wp_ajax_remove_enquiry_item', 'remove_enquiry_item');
add_action('wp_ajax_nopriv_remove_enquiry_item', 'remove_enquiry_item');

function handle_enquiry_form_submission($enquiry_cart)
{
    $name = sanitize_text_field($_POST['enquiry_name']);
    $email = sanitize_email($_POST['enquiry_email']);
    $message = sanitize_textarea_field($_POST['enquiry_message']);

    $order = wc_create_order();

    foreach ($enquiry_cart as $item) {
        $product = isset($item['variation_id']) && $item['variation_id'] ? wc_get_product($item['variation_id']) : wc_get_product($item['product_id']);
        $quantity = $item['quantity'];

        $order->add_product($product, $quantity);
    }

    $order->set_address(array(
        'first_name' => $name,
        'email'      => $email
    ), 'billing');

    $order->update_meta_data('_enquiry_message', $message);

    $order->update_meta_data('_is_enquiry_order', 'yes');
    $order->update_status('processing', 'Enquiry placed by customer.');

    WC()->mailer()->get_emails()['WC_Email_New_Order']->trigger($order->get_id());

    echo '<div class="woocommerce-message">Your enquiry has been sent successfully as an order.</div>';

    WC()->session->set('enquiry_cart', array());

    $order->save();
}

function add_enquiry_message_to_order_email($order, $sent_to_admin, $plain_text, $email)
{
    if ($sent_to_admin && $order->get_meta('_is_enquiry_order') === 'yes') {
        $enquiry_message = $order->get_meta('_enquiry_message');
        if ($enquiry_message) {
            echo '<h2>Message</h2><p>' . nl2br(esc_html($enquiry_message)) . '</p>';
        }
    }
}
add_action('woocommerce_email_order_meta', 'add_enquiry_message_to_order_email', 10, 4);

add_shortcode('display_enquiry_cart_page', 'display_enquiry_cart_page');
?>