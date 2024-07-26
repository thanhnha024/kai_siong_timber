<?php
/*
Plugin Name: WooCommerce Category Accordion Widget
Description: Display store categories as menu accordion  ,
Version: 1.0
Author: Toan
*/

class WC_Category_Accordion_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'wc_category_accordion_widget',
            __('WooCommerce Category Accordion', 'text_domain'),
            array('description' => __('Display store categories as menu accordion.', 'text_domain'),)
        );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $this->display_accordion_menu();

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('Danh mục sản phẩm', 'text_domain');
?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e(esc_attr('Title:')); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
    <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

    private function display_accordion_menu()
    {
        $shop_page_url = get_permalink(wc_get_page_id('shop'));

        echo '<div class="accordion-menu">';
        echo '<div class="accordion-item all-categories">';
        echo '<div class="accordion-header"><a href="' . esc_url($shop_page_url) . '">All Categories</a></div>';
        echo '</div>';

        $args = array(
            'taxonomy'     => 'product_cat',
            'orderby'      => 'name',
            'show_count'   => 0,
            'pad_counts'   => 0,
            'hierarchical' => 1,
            'title_li'     => ''
        );

        $all_categories = get_categories($args);

        foreach ($all_categories as $cat) {
            if ($cat->category_parent == 0) {
                $category_id = $cat->term_id;
                echo '<div class="accordion-item" data-category-id="' . $category_id . '">';
                echo '<div class="accordion-header"><a href="' . get_term_link($cat->slug, 'product_cat') . '">' . $cat->name . '</a><span class="accordion-icon">+</span></div>';
                echo '<div class="accordion-content">';
                $this->display_subcategories($category_id);
                echo '</div></div>';
            }
        }
        echo '</div>';
    }

    private function display_subcategories($parent_id)
    {
        $args = array(
            'taxonomy'     => 'product_cat',
            'child_of'     => $parent_id,
            'parent'       => $parent_id,
            'orderby'      => 'name',
            'show_count'   => 0,
            'pad_counts'   => 0,
            'hierarchical' => 1,
            'title_li'     => ''
        );

        $sub_cats = get_categories($args);

        if ($sub_cats) {
            echo '<ul>';
            foreach ($sub_cats as $sub_category) {
                echo '<li><a href="' . get_term_link($sub_category->slug, 'product_cat') . '" data-category-id="' . $sub_category->term_id . '">' . $sub_category->name . '</a></li>';
            }
            echo '</ul>';
        }
    }
}

function register_wc_category_accordion_widget()
{
    register_widget('WC_Category_Accordion_Widget');
}
add_action('widgets_init', 'register_wc_category_accordion_widget');

function wc_category_accordion_styles()
{
    ?>
    <script>
        jQuery(document).ready(function($) {
            $('.accordion-header').click(function() {
                var accordionItem = $(this).parent('.accordion-item');
                var icon = $(this).find('.accordion-icon');
                var content = accordionItem.find('.accordion-content');

                content.slideToggle();

                if (accordionItem.hasClass('active')) {
                    icon.text('+');
                } else {
                    icon.text('-');
                }

                accordionItem.toggleClass('active');
                accordionItem.siblings().removeClass('active').find('.accordion-content').slideUp();
                accordionItem.siblings().find('.accordion-icon').text('+');
            });

            var currentUrl = window.location.href;

            $('.accordion-item').each(function() {
                var categoryId = $(this).data('category-id');
                var categoryLink = $(this).find('.accordion-header a').attr('href');

                if (currentUrl === categoryLink) {
                    $(this).addClass('active');
                    $(this).find('.accordion-content').show();
                    $(this).find('.accordion-icon').text('-');
                }

                $(this).find('li a').each(function() {
                    var subCategoryLink = $(this).attr('href');
                    if (currentUrl === subCategoryLink) {
                        $(this).parent().addClass('active');
                        $(this).closest('.accordion-item').addClass('active');
                        $(this).closest('.accordion-content').show();
                        $(this).closest('.accordion-item').find('.accordion-icon').text('-');
                    }
                });
            });
        });
    </script>
    <style>
        .accordion-menu .accordion-item {
            margin: 10px 0;
        }
        .accordion-menu .accordion-item .accordion-header {
            padding: 10px;
            cursor: pointer;
            background: #f7f7f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #ddd;
        }
        .accordion-menu .accordion-item .accordion-header a {
            color: #040404;
        }
        .accordion-menu .accordion-item .accordion-content {
            display: none;
            margin: 10px 5px;
        }
       .accordion-menu .accordion-item .accordion-content ul {
            list-style: none;
            padding: 0;
            margin: 0 0 0 15px;
        }
        .accordion-menu .accordion-item .accordion-content ul li {
            padding: 10px;
			border: 1px solid #ddd;
			margin: 10px 0;
        }
		 .accordion-menu .accordion-item .accordion-content ul li.active a {
           color: #6b9e69;
        }
		 .accordion-menu .accordion-item .accordion-content ul li a {
            color: #040404;
        }
        .accordion-menu .accordion-item .accordion-content ul li a::before {
            display: none;
        }
        .accordion-menu .accordion-item.active .accordion-content {
            display: block;
        }
        .accordion-menu .accordion-item.active .accordion-header a {
            color: #6b9e69;
        }
        .accordion-menu .accordion-item.active .sub-category {
            margin: 0 20px;
        }
        .accordion-menu .accordion-item.active .sub-category.active a {
            color: #6b9e69;
        }
    </style>
<?php
}
add_action('wp_head', 'wc_category_accordion_styles');
?>