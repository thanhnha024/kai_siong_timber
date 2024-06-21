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


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




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
