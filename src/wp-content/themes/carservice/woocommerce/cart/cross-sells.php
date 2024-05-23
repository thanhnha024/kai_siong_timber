<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( $cross_sells ) : ?>
	<div class="vc_row wpb_row vc_row-fluid page-margin-top">
		<div class="vc_col-sm-6 wpb_column vc_column_container">
			<div class="cross-sells">
				<?php
				$heading = apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may be interested in&hellip;', 'carservice' ) );

				if ( $heading ) :
					?>
					<h4 class="box-header"><?php echo esc_html( $heading ); ?></h4>
				<?php endif; ?>

				<?php woocommerce_product_loop_start(); ?>

					<?php foreach ( $cross_sells as $cross_sell ) : ?>

						<?php
							$post_object = get_post( $cross_sell->get_id() );

							setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

							wc_get_template_part( 'content', 'product' );
						?>

					<?php endforeach; ?>

				<?php woocommerce_product_loop_end(); ?>

			</div>
		</div>
	</div>
	<?php
endif;

wp_reset_postdata();
