<?php
get_header();
global $post;
$post = get_post(get_option("woocommerce_shop_page_id"));
$current_category = get_queried_object();
setup_postdata($post);
$current_url = $_SERVER['REQUEST_URI'];
$url_path = parse_url($current_url, PHP_URL_PATH);
$path_segments = explode('/', trim($url_path, '/'));
$slug = end($path_segments);
$slug = str_replace('-', ' ', $slug);
$title = ucwords($slug);
?>
<div class="theme-page padding-bottom-70">
	<div class="vc_row wpb_row vc_row-fluid gray full-width page-header vertical-align-table">
		<div class="vc_row wpb_row vc_inner vc_row-fluid">
			<?php if (!is_single()) : ?>
				<div class="page-header-left">
					<?php if (is_archive() && !is_sub_category($current_category)) : ?>

						<h1><?php echo 'Product Categories'; ?></h1>

					<?php elseif (is_sub_category($current_category)) : ?>

						<h1><?php echo 'Sub-Product Categories'; ?></h1>

					<?php else : ?>
						<h1><?php echo $title; ?></h1>

					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="page-header-right">
				<div class="bread-crumb-container">
					<?php echo get_hansel_and_gretel_breadcrumbs(); ?>
				</div>

			</div>
		</div>
	</div>
</div>
<div class="clearfix">
	<div class="vc_row wpb_row vc_row-fluid margin-top-70">
		<?php if (!is_single()) : ?>
			<?php
			if (is_active_sidebar('sidebar-shop')) {
				$sidebar = cs_get_page_by_title("Sidebar Shop", "carservice_sidebars");
			}
			?>
			<?php
			if (is_active_sidebar('sidebar-shop')) {
				if (isset($sidebar) && !(int)get_post_meta($sidebar->ID, "hidden", true)) {
			?>
					<div class="vc_col-sm-3 wpb_column vc_column_container<?php echo ((int)get_post_meta($sidebar->ID, "hide_on_mobiles", true) ? ' hide-on-mobiles' : ''); ?>">
						<div class="wpb_wrapper">
							<div class="wpb_widgetised_column wpb_content_element clearfix">
								<div class="wpb_wrapper">
									<?php dynamic_sidebar('sidebar-shop'); ?>
								</div>
							</div>
						</div>
					</div>
			<?php
				}
			}
			?>
		<?php endif;
		?>

		<div class="vc_col-sm-<?php echo (is_active_sidebar('sidebar-shop') && isset($sidebar) && !(int)get_post_meta($sidebar->ID, "hidden", true) ? '9' : '12'); ?> wpb_column vc_column_container">
			<div class="wpb_wrapper">
				<?php
				if (have_posts()) :
					woocommerce_content();
				else :
					_e("No products found", 'carservice');
				endif;
				?>
			</div>
		</div>

	</div>
</div>
<?php
global $post;
$post = get_post(get_option("woocommerce_shop_page_id"));
setup_postdata($post);
get_footer();
?>
