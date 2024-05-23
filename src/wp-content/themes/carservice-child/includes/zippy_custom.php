<?php
add_action('wp_enqueue_scripts', 'shin_scripts');
function shin_scripts()
{
    $version = '2.2.0';

    // Load CSS
    wp_enqueue_style('main-style-css', THEME_URL . '-child' . '/assets/main/main.css', array(), $version, 'all');
    // Load JS
    wp_enqueue_script('main-scripts-js', THEME_URL . '-child' . '/assets/main/main.js', array('jquery'), $version, true);
}
add_action('login_enqueue_scripts', 'add_logo_to_login_page');
function add_logo_to_login_page()
{
  $logo = get_theme_mod('custom_logo');
  $image = wp_get_attachment_image_src($logo, 'full');
  // $image_url = $image[0];
  // var_dump($image_url);

  if (!empty($image_url)) : ?>
    <style type="text/css">
      body.login div#login h1 a {
        background-image: url("<?php echo $image_url; ?>");
        width: 100%;
        background-size: 48%;
      }

      #login form#loginform .input,
      #login form#registerform .input,
      #login form#lostpasswordform .input {
        border-width: 0px;
        border-radius: 0px;
        box-shadow: unset;
      }

      #login form#loginform .input,
      #login form#registerform .input,
      #login form#lostpasswordform .input {
        border-bottom: 1px solid #d2d2d2;
      }

      #login form .submit .button {
        background-color: #1f2222;
        width: 100%;
        height: 40px;
        border-width: 0px;
        margin-top: 10px;
      }

      .login .button.wp-hide-pw {
        color: #1f2222;
      }
    </style>
<?php endif;
}
