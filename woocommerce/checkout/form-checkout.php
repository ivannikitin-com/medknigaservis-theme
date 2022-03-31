<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

<div class="col2-set match-height-grid1">

	<div class="col-1 match-height-content1">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div id="customer_details">

				<?php do_action( 'woocommerce_checkout_shipping' ); ?>

				<?php do_action( 'woocommerce_checkout_billing' ); ?>

		</div><!--#customer_details-->

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>
	
	</div><!--/.col-1-->
	
	<div class="col-2 order-column match-height-content1">

		<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?> <span>(<?php echo WC()->cart->get_cart_contents_weight().' '.__( get_option('woocommerce_weight_unit'), 'woocommerce' ); ?>)</span></h3> 

		<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

		<div id="order_review" class="woocommerce-checkout-review-order clearfix">
			<?php do_action( 'woocommerce_checkout_order_review' ); ?>
		</div>
		
		<!--<div id="address"></div>-->
		
	</div><!--/.col-2-->
</div><!--/.col2-set-->	
<div class="col2-set">

	<div class="col-1">

		<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
	
	</div><!--/.col-1-->
	<div class="col-2">
	</div><!--/.col-2-->

</div><!--/.col2-set-->
	
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>