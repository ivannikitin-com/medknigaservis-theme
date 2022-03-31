<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php //do_action( 'wpo_wcpdf_before_document', $this->type, $this->order ); ?>
<?php global $wpo_wcpdf; 

$order_totals=$wpo_wcpdf->get_woocommerce_totals();
$order = $this->order;

/*$cart = WC()->cart->get_cart();

var_dump($cart);*/
/* foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );	

	$product_attr = get_post_meta( $product_id, '_product_attributes' );
	if (isset($product_attr[0]['isbn'])) : ?>
	<dt class="isbn">
	<?php _e( 'ISBN:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt>
	<dd class="isbn"><?php echo $product_attr[0]['isbn']['value']; ?></dd>
	<?php endif;	
}	  */
?>
<?php //do_action( 'wpo_wcpdf_after_document_label', $this->type, $this->order ); ?>

<table class="order-data-addresses">
	<tr class="order-number document-type-label">
		<td class="header nowrap"><?php _e( 'Order Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?><?php $this->order_number(); ?></td>
		<td></td>
		<td class="header"><span class="logo"><img src="<?php echo get_theme_mod('custom_logo', ''); ?>"></span></td>
	</tr>
	<tr>
		<td class="order-data padBot20"><?php echo $order->get_shipping_method(); ?></td>
		<td></td>
		<td></td>
	</tr>		
	<tr>
		<td class="address billing-address">
			<!-- <h3><?php _e( 'Billing Address:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3> -->
			<?php do_action( 'wpo_wcpdf_before_billing_address', $this->type, $this->order ); ?>
			<?php //$this->billing_address(); 
    			    $first_name = $this->order->data['billing']['first_name'];
    			    $last_name = $this->order->data['billing']['last_name'];
    			    
    			    echo $last_name . ' ' . $first_name;
			?>
			<?php do_action( 'wpo_wcpdf_after_billing_address', $this->type, $this->order ); ?>
			<?php if ( isset($this->settings['display_email']) ) { ?>
			<div class="billing-email"><?php $this->billing_email(); ?></div>
			<?php } ?>
			<?php if ( isset($this->settings['display_phone']) ) { ?>
			<div class="billing-phone"><?php $this->billing_phone(); ?>
			<?php $billing_whatsapp_notification = get_post_meta($order->get_order_number(),'billing_whatsapp_notification', true); 
			if ( $billing_whatsapp_notification ) { ?>
			  <img alt="WhatsApp Icon" src="<?php echo get_stylesheet_directory_uri()?>/woocommerce/pdf/medkniga/whatsapp_48.jpg" title="WhatsApp" width="16" height="16">
			<?php }?>
			</div>
			<?php } ?>
		</td>
		<td></td>
		<!--<td class="address shipping-address">
			<?php if ( isset($this->settings['display_shipping_address']) && $this->ships_to_different_address()) { ?>
			<h3><?php _e( 'Ship To:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
			<?php do_action( 'wpo_wcpdf_before_shipping_address', $this->type, $this->order ); ?>
			<?php $this->shipping_address(); ?>
			<?php do_action( 'wpo_wcpdf_after_shipping_address', $this->type, $this->order ); ?>
			<?php } ?>
		</td>-->
		<td class="order-data">
			<table>
				<?php do_action( 'wpo_wcpdf_before_order_data', $this->type, $this->order ); ?>
				<?php if ( isset($this->settings['display_number']) ) { ?>
				<tr class="invoice-number">
					<th></th>
					<td><?php $this->invoice_number(); ?></td>
				</tr>
				<?php } ?>
				<?php if ( isset($this->settings['display_date']) ) { ?>
				<tr class="invoice-date">
					<th><?php _e( 'Invoice Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->invoice_date(); ?></td>
				</tr>
				<?php } ?>
				<tr class="order-date">
					<th><?php _e( 'Order Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->order_date(); ?></td>
				</tr>
				<tr class="payment-method">
					<th class="nowrap"><?php _e( 'Payment Method:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->payment_method(); ?></td>
				</tr>
				<?php do_action( 'wpo_wcpdf_after_order_data', $this->type, $this->order ); ?>
			</table>			
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_order_details', $this->type, $this->order ); ?>

<table class="order-details">
	<thead>
		<tr>
			<th>№</th>
			<th class="product"><?php _e('Product', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
			<th class="quantity"><?php _e('Quantity', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
			<th class="price"><?php _e('Price', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php $items = $this->get_order_items(); if( sizeof( $items ) > 0 ) : $i=1; foreach( $items as $item_id => $item ) : ?>
		<tr class="<?php echo apply_filters( 'wpo_wcpdf_item_row_class', $item_id, $this->type, $this->order, $item_id ); ?>">
			<td class="item-number"><?php echo $i++; ?></td>
			<td class="product">
				<?php $description_label = __( 'Description', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
				<span class="item-name"><?php echo $item['name']; ?></span>
				<?php do_action( 'wpo_wcpdf_before_item_meta', $this->type, $item, $this->order ); ?>
				<span class="item-meta"><?php echo $item['meta']; ?></span>
				<dl class="meta">
					<?php $description_label = __( 'SKU', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
					<?php if( !empty( $item['sku'] ) ) : ?><dt class="sku nowrap"><?php _e( 'SKU:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="sku"><?php echo $item['sku']; ?></dd><?php endif; ?>
					
					<?php 
				
					$product_attr = get_post_meta( $item["product_id"], '_product_attributes' );
					echo '<!-- DEBUG  $product_attr '; var_dump( $product_attr ); echo ' -->'; 
					
					$arr1 = [];
						
					if (isset($product_attr[0]['isbn']))
					{
						$arr1[] = "<span class='isbn'>" . __( 'ISBN', 'medknigaservis' ) . ": " . $product_attr[0]['isbn']['value'] . "</span>";
					} 
					
					if (isset($product_attr[0]['god'])) 
					{
						$arr1[] = "<span class='year'>" . $product_attr[0]['god']['value'] . "</span>";
					}
					

					echo implode(', ', $arr1);					

					if( !empty( $item['weight'] ) ) : ?>
					<div>
					<dt class="weight"><?php _e( 'Weight:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt>
					<span class="weight"><?php echo $item['weight']; ?><?php echo get_option('woocommerce_weight_unit'); ?></span>
					</div>
					<?php endif; ?>
				</dl>
				<?php do_action( 'wpo_wcpdf_after_item_meta', $this->type, $item, $this->order  ); ?>
			</td>
			<td class="quantity"><?php echo $item['quantity']; ?></td>
			<td class="price"><?php echo $item['order_price']; ?></td>
		</tr>
		<?php endforeach; endif; ?>
	</tbody>
	<tfoot>
		<tr class="no-borders">
			<td class="address shipping-address padTop20" colspan="2">
				<?php if ( isset($this->settings['display_shipping_address']) && $this->ships_to_different_address()) { ?>
				<h3><?php _e( 'Ship To:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
				<?php do_action( 'wpo_wcpdf_before_shipping_address', $this->type, $this->order ); ?>
				<?php $this->shipping_address(); ?>
				<?php do_action( 'wpo_wcpdf_after_shipping_address', $this->type, $this->order ); ?>
				<?php } ?>
			</td>
			
			<td class="no-borders" colspan="2">
				<table class="totals">
					<tfoot>
						<?php //foreach( $this->get_woocommerce_totals() as $key => $total ) : ?>
						<!--<tr class="<?php //echo $key; ?>">
							<th class="description"><?php //echo $total['label']; ?></th>
							<td class="price"><span class="totals-price"><?php //echo $total['value']; ?></span></td>
						</tr>-->
						<tr class="subtotal">
							<th class="description">Общее кол-во</th>
							<td class="price"><span class="totals-price"><?php echo $order->get_item_count(); ?></span></td>
						
						</tr>
						<tr class="subtotal">
							<th class="description">Подытог</th>
							<td class="price"><span class="totals-price"><?php echo $order->get_subtotal_to_display(); ?></span></td>
						</tr>
						<tr class="discount">
							<th class="description">Скидка</th>
							<td class="price"><span class="totals-price"><?php echo $order->get_discount_to_display(); ?></span></td>
						</tr>
						<tr class="shipping">
							<th class="description">Доставка</th>
							<td class="price"><span class="totals-price"><?php echo wc_price($order->get_shipping_total()); ?></span></td>
						</tr>
						<tr class="total">
							<th class="description">Всего</th>
							<td class="price"><span class="totals-price"><?php echo wc_price($order->get_total()); ?></span></td>
						</tr>						
						<?php //endforeach; ?>
					</tfoot>
				</table>
			</td>
		</tr>
	</tfoot>
</table>

<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>

<?php if ( $this->get_footer() ): ?>
<div id="footer">
	<?php $this->footer(); ?>
</div><!-- #letter-footer -->
<?php endif; ?>
<?php do_action( 'wpo_wcpdf_after_document', $this->type, $this->order ); ?>
