<?php
get_header();

$current_category = get_queried_object();

if ($current_category && is_a($current_category, 'WP_Term')) {
    $current_category_id = $current_category->term_id;

    $sub_args = array(
        'taxonomy'   => 'product_cat',
        'parent'     => $current_category_id,
        'hide_empty' => false,
        'exclude'    => array(get_term_by('slug', 'uncategorized', 'product_cat')->term_id),
    );
    $sub_categories = get_terms($sub_args);

    if (!empty($sub_categories)) {
        echo '<div class="theme-page padding-bottom-70">';
        echo '<div class="vc_row wpb_row vc_row-fluid gray full-width page-header vertical-align-table">';
        echo '<div class="vc_row wpb_row vc_inner vc_row-fluid">';
        echo '<div class="page-header-left">';
        echo '<h1>' . $current_category->name . '</h1>';
        echo '</div>';
        echo '<div class="page-header-right">';
        echo '<div class="bread-crumb-container">';
        echo '<label>' . __("YOU ARE HERE:", 'carservice') . '</label>';
        woocommerce_breadcrumb();
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
		echo '<div class="vc_row wpb_row vc_row-fluid">';
		echo '<div class="wpb_column vc_column_container vc_col-sm-12">';
		echo '<div class="wpb_wrapper">';
		echo '<div class="wpb_text_column wpb_content_element ">';
		echo '<div class="wpb_wrapper">';
        echo '<ul class="sub-categories">';
        foreach ($sub_categories as $sub_category) {
            $sub_thumbnail_id = get_term_meta($sub_category->term_id, 'thumbnail_id', true);
            $sub_image_url = ($sub_thumbnail_id) ? wp_get_attachment_url($sub_thumbnail_id) : '/wp-content/uploads/woocommerce-placeholder.png';

            echo '<li class="sub-category">';
            echo '<a href="' . get_term_link($sub_category) . '">';
            echo '<img src="' . $sub_image_url . '" alt="' . $sub_category->name . '">';
            echo '<span class="title-sub-category">' . $sub_category->name . '</span>';
            echo '</a>';
            echo '</li>';
        }
        echo '</ul>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
    } else {
        echo cs_get_theme_file("/woocommerce-shop.php");
    }
}?>
<?php
get_footer();
?>
