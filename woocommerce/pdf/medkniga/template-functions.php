<?php
/**
 * Use this file for all your template filters and actions.
 * Requires WooCommerce PDF Invoices & Packing Slips 1.4.13 or higher
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'wpo_wcpdf_after_order_data', 'wpo_wcpdf_coupons_used_invoice', 10, 2);
function wpo_wcpdf_coupons_used_invoice( $template_type, $order ) {
	$coupons = $order->get_items( 'coupon' );
    if ( $coupons && $template_type == 'invoice' ) {
		?>
		<tr class="coupon">
			<th class="nowrap"><?php _e( 'Купон/Скидка:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
			<td>
		<?php
		foreach( $coupons as $item_id => $item ) {
			echo '<span style="display:none;">' . esc_html( $item->get_code() ) . '</span>';
			echo esc_html( $item->get_code() );
		}
		?>
			</td>
		</tr>
		<?php
    }
}


