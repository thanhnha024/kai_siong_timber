<?php
function child_theme_enqueue_styles()
{
    wp_enqueue_style("parent-style", get_template_directory_uri() . "/style.css", array("reset", "superfish", "prettyPhoto", "jquery-qtip", "odometer", "animations"));
}
add_action("wp_enqueue_scripts", "child_theme_enqueue_styles", 12);
function child_theme_enqueue_rtl_styles()
{
    if (is_rtl())
        wp_enqueue_style("parent-rtl", get_template_directory_uri() . "/rtl.css");
}
add_action("wp_enqueue_scripts", "child_theme_enqueue_rtl_styles", 13);

/*
 * Define Variables
 */
if (!defined('THEME_DIR'))
    define('THEME_DIR', get_template_directory());
if (!defined('THEME_URL'))
    define('THEME_URL', get_template_directory_uri());


/*
 * Include framework files
 */
foreach (glob(THEME_DIR . '-child' . "/includes/*.php") as $file_name) {
    require_once($file_name);
}


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);




function get_hansel_and_gretel_breadcrumbs()
{
    // Set variables for later use
    $here_text        = __('');
    $home_link        = home_url('/');
    $home_text        = __('Home');
    $link_before      = '<span typeof="v:Breadcrumb">';
    $link_after       = '</span>';
    $link_attr        = ' rel="v:url" property="v:title"';
    $link             = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
    $delimiter        = ' / ';
    $before           = '<span class="current">';
    $after            = '</span>';
    $page_addon       = '';
    $breadcrumb_trail = '';
    $category_links   = '';

    $wp_the_query   = $GLOBALS['wp_the_query'];
    $queried_object = $wp_the_query->get_queried_object();

    if (is_singular()) {
        $post_object = sanitize_post($queried_object);

        $title          = apply_filters('the_title', $post_object->post_title);
        $parent         = $post_object->post_parent;
        $post_type      = $post_object->post_type;
        $post_id        = $post_object->ID;
        $post_link      = $before . $title . $after;
        $parent_string  = '';
        $post_type_link = '';

        if ('post' === $post_type) {

            $categories = get_the_category($post_id);
            if ($categories) {
                $category  = $categories[0];
                $category_links = get_category_parents($category, true, $delimiter);
                $category_links = str_replace('<a',   $link_before . '<a' . $link_attr, $category_links);
                $category_links = str_replace('</a>', '</a>' . $link_after,             $category_links);
            }
        }

        if (!in_array($post_type, ['post', 'page', 'attachment'])) {
            if (is_single()) {
                $current_page = get_queried_object();
                $product_id = $current_page->ID;
                $product_cats_ids = wc_get_product_term_ids($product_id, 'product_cat');
                sort($product_cats_ids);
                foreach ($product_cats_ids as $key => $category) {
                    $category_id =  $category;
                    $term = get_term_by('id', $category_id, 'product_cat');
                    $link_cate = get_category_link($category_id);
                    if ($key === 0) {
                        $post_type_link   .= sprintf($link, $link_cate, $term->name);
                    } else {

                        $post_type_link   .= '/' . sprintf($link, $link_cate, $term->name);
                    }
                }
            }
        }

        // Get post parents if $parent !== 0
        if (0 !== $parent) {

            $parent_links = [];
            while ($parent) {
                $post_parent = get_post($parent);

                $parent_links[] = sprintf($link, esc_url(get_permalink($post_parent->ID)), get_the_title($post_parent->ID));

                $parent = $post_parent->post_parent;
            }
            $parent_links = array_reverse($parent_links);
            $parent_string = implode($delimiter, $parent_links);
        }


        if ($parent_string) {
            $breadcrumb_trail = $parent_string . $delimiter . $post_link;
        } else {
            $breadcrumb_trail = $post_link;
        }

        if ($post_type_link)
            $breadcrumb_trail = $post_type_link . $delimiter . $breadcrumb_trail;

        if ($category_links)
            $breadcrumb_trail = $category_links . $breadcrumb_trail;
    }

    if (is_archive()) {

        if (
            is_category()
            || is_tag()
            || is_tax()
        ) {
            $term_object        = get_term($queried_object);
            $taxonomy           = $term_object->taxonomy;
            $term_id            = $term_object->term_id;
            $term_name          = $term_object->name;
            $term_parent        = $term_object->parent;
            $taxonomy_object    = get_taxonomy($taxonomy);
            $current_term_link  = $before . $term_name . $after;
            $parent_term_string = '';

            if (0 !== $term_parent) {
                $parent_term_links = [];
                while ($term_parent) {
                    $term = get_term($term_parent, $taxonomy);

                    $parent_term_links[] = sprintf($link, esc_url(get_term_link($term)), $term->name);

                    $term_parent = $term->parent;
                }

                $parent_term_links  = array_reverse($parent_term_links);
                $parent_term_string = implode($delimiter, $parent_term_links);
            }

            if ($parent_term_string) {
                $breadcrumb_trail = $parent_term_string . $delimiter . $current_term_link;
            } else {
                $breadcrumb_trail = $current_term_link;
            }
        } elseif (is_author()) {

            $breadcrumb_trail = __('Author archive for ') .  $before . $queried_object->data->display_name . $after;
        } elseif (is_date()) {
            $year     = $wp_the_query->query_vars['year'];
            $monthnum = $wp_the_query->query_vars['monthnum'];
            $day      = $wp_the_query->query_vars['day'];
            if ($monthnum) {
                $date_time  = DateTime::createFromFormat('!m', $monthnum);
                $month_name = $date_time->format('F');
            }

            if (is_year()) {

                $breadcrumb_trail = $before . $year . $after;
            } elseif (is_month()) {

                $year_link        = sprintf($link, esc_url(get_year_link($year)), $year);

                $breadcrumb_trail = $year_link . $delimiter . $before . $month_name . $after;
            } elseif (is_day()) {

                $year_link        = sprintf($link, esc_url(get_year_link($year)),             $year);
                $month_link       = sprintf($link, esc_url(get_month_link($year, $monthnum)), $month_name);

                $breadcrumb_trail = $year_link . $delimiter . $month_link . $delimiter . $before . $day . $after;
            }
        } elseif (is_post_type_archive()) {

            $post_type        = $wp_the_query->query_vars['post_type'];
            $post_type_object = get_post_type_object($post_type);

            $breadcrumb_trail = $before . $post_type_object->labels->singular_name . $after;
        }
    }

    if (is_search()) {
        $breadcrumb_trail = __('Search query for: ') . $before . get_search_query() . $after;
    }
    if (is_404()) {
        $breadcrumb_trail = $before . __('Error 404') . $after;
    }
    if (is_paged()) {

        $current_page = get_query_var('paged') ? get_query_var('paged') : get_query_var('page');
        $page_addon   = $before . sprintf(__(' ( Page %s )'), number_format_i18n($current_page)) . $after;
    }

    $breadcrumb_output_link  = '';
    $breadcrumb_output_link .= '<div class="breadcrumb">';
    if (
        is_home()
        || is_front_page()
    ) {
        if (is_paged()) {;

            $breadcrumb_output_link .= '<a href="' . $home_link . '">' . $home_text . '</a>';
            $breadcrumb_output_link .= $page_addon;
        }
    } else {
        $breadcrumb_output_link .= '<a href="' . $home_link . '" rel="v:url" property="v:title">' . $home_text . '</a>';
        $breadcrumb_output_link .= $delimiter;
        $breadcrumb_output_link .= $breadcrumb_trail;
        $breadcrumb_output_link .= $page_addon;
    }
    $breadcrumb_output_link .= '</div><!-- .breadcrumbs -->';

    return $breadcrumb_output_link;
}
function convert_to_webp($upload)
{
    $image_path = $upload['file'];
    // % compression (0-100)
    $compression_quality = 80;
    $supported_mime_types = array(
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    );
    $image_info = getimagesize($image_path);
    if ($image_info !== false && array_key_exists($image_info['mime'], $supported_mime_types)) {
        $image = imagecreatefromstring(file_get_contents($image_path));
        if ($image) {
            if (imageistruecolor($image)) {
                $webp_path = preg_replace('/\.(jpg|jpeg|png)$/','.webp', $image_path);
                imagewebp($image, $webp_path, $compression_quality);
                $upload['file'] = $webp_path;
                $upload['type'] = 'image/webp';
                // Delete corner image
                unlink($image_path);
            } else {
                // If is image 8-bit, doing uncompress
                $upload['file'] = $image_path;
                $upload['type'] = $image_info['mime'];
            }
        }
    }
    return $upload;
}
function convert_to_webp_upload($upload)
{
    $upload = convert_to_webp($upload);
    return $upload;
}
add_filter('wp_handle_upload', 'convert_to_webp_upload');

// Hook into the image upload action
add_action('add_attachment', 'save_uploaded_image_names_and_urls_to_file');

function save_uploaded_image_names_and_urls_to_file($attachment_id) {
    if (wp_attachment_is_image($attachment_id)) {
        $image_name = get_the_title($attachment_id);
        $image_url = wp_get_attachment_url($attachment_id);
        
        $file_path = WP_CONTENT_DIR . '/uploads/uploaded_image_urls.txt';
        
        if (!file_exists($file_path)) {
            touch($file_path);
        }
        
        $entry = $image_name . ' : ' . $image_url . PHP_EOL;
        file_put_contents($file_path, $entry, FILE_APPEND | LOCK_EX);
    }
}

add_action('delete_attachment', 'remove_deleted_image_from_file');

function remove_deleted_image_from_file($attachment_id) {

    if (wp_attachment_is_image($attachment_id)) {
   
        $image_url = wp_get_attachment_url($attachment_id);
        $file_path = WP_CONTENT_DIR . '/uploads/uploaded_image_urls.txt';
        
        $file_contents = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        $updated_contents = array_filter($file_contents, function($line) use ($image_url) {
            return strpos($line, $image_url) === false;
        });
        
        file_put_contents($file_path, implode(PHP_EOL, $updated_contents) . PHP_EOL);
        
        if (empty($updated_contents)) {
            unlink($file_path);
        }
    }
}

// Create an endpoint to download the file and display the list
add_action('admin_menu', function() {
    add_menu_page('Uploaded Images', 'Uploaded Images', 'manage_options', 'uploaded-images', function() {
        $file_path = WP_CONTENT_DIR . '/uploads/uploaded_image_urls.txt';
        $file_url = content_url('uploads/uploaded_image_urls.txt');
        
        // Handle delete request
        if (isset($_GET['delete_line'])) {
            $line_to_delete = $_GET['delete_line'];
            $file_contents = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $line = $file_contents[$line_to_delete];

            preg_match('/ : (.*)$/', $line, $matches);
            $image_url = $matches[1];

            $attachment_id = attachment_url_to_postid($image_url);
            if ($attachment_id) {
                wp_delete_attachment($attachment_id, true);
            }

            // Update the file
            unset($file_contents[$line_to_delete]);
            file_put_contents($file_path, implode(PHP_EOL, $file_contents) . PHP_EOL);
            
            if (empty($file_contents)) {
                unlink($file_path);
            }

            echo '<div class="updated"><p>Deleted successfully.</p></div>';
        }

        // Handle reset request
        if (isset($_GET['reset_list'])) {
            file_put_contents($file_path, '');
            echo '<div class="updated"><p>List reset successfully.</p></div>';
        }

        $file_contents = file_exists($file_path) ? file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

        echo '<h1>Uploaded Images</h1>';
        echo '<a href="' . esc_url($file_url) . '" download>Download URL List</a> | ';
        echo '<a href="?page=uploaded-images&reset_list=1" onclick="return confirm(\'Are you sure you want to reset the list?\');">Reset List</a>';
        echo '<ul>';
        foreach ($file_contents as $index => $line) {
            echo '<li>' . esc_html($line) . ' <a href="?page=uploaded-images&delete_line=' . $index . '" onclick="return confirm(\'Are you sure you want to delete this item?\');">Delete</a></li>';
        }
        echo '</ul>';
    });
});

function custom_sidebar() {
    register_sidebar( array(
        'name'          => 'Custom Sidebar',
        'id'            => 'custom_sidebar',
        'before_widget' => '<div class="widget-content">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action( 'widgets_init', 'custom_sidebar' );
function custom_menu_shortcode() {
    ob_start();

    // Get menu object
    $locations = get_nav_menu_locations();
    if (isset($locations["main-menu"])) :
        $main_menu_object = get_term($locations["main-menu"], "nav_menu");
        if (has_nav_menu("main-menu") && $main_menu_object->count > 0) : ?>
            <a href="#" class="mobile-menu-switch vertical-align-cell">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </a>
            <div class="menu-container clearfix vertical-align-cell">
                <?php
                wp_nav_menu(array(
                    "container" => "nav",
                    "theme_location" => "main-menu",
                    "menu_class" => "sf-menu"
                ));
                ?>
            </div>
            <div class="mobile-menu-container">
                <div class="mobile-menu-divider"></div>
                <?php
                global $theme_options;
                wp_nav_menu(array(
                    "container" => "nav",
                    "theme_location" => "main-menu",
                    "menu_class" => "mobile-menu" . (!isset($theme_options["collapsible_mobile_submenus"]) || (int)$theme_options["collapsible_mobile_submenus"] ? " collapsible-mobile-submenus" : ""),
                    "walker" => (!isset($theme_options["collapsible_mobile_submenus"]) || (int)$theme_options["collapsible_mobile_submenus"] ? new Mobile_Menu_Walker_Nav_Menu() : '')
                ));
                ?>
            </div>
        <?php
        endif;
    endif;

    return ob_get_clean();
}

add_shortcode('custom_menu', 'custom_menu_shortcode');
