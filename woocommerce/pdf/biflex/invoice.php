<?php global $wpo_wcpdf; ?>
<table class="head container">
	<tr>
		<td class="header">
		<?php
		if( $wpo_wcpdf->get_header_logo_id() ) {
			$wpo_wcpdf->header_logo();
		} else {
			echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'wpo_wcpdf' ) );
		}
		?>
		</td>
		<td class="shop-info">
			<div class="shop-name"><h3><?php $wpo_wcpdf->shop_name(); ?></h3></div>
			<div class="shop-address"><?php $wpo_wcpdf->shop_address(); ?></div>
		</td>
	</tr>
</table>

<?php $account_details = get_option( 'woocommerce_bacs_accounts' ); ?>
<h3>Образец заполнения платежного поручения</h3>
<table class="filling-example">
<tr>
<td>ИНН <?php echo $account_details['0']['bic']; ?></td><td>КПП <?php echo $account_details['0']['kpp']; ?></td><td></td><td></td>
</tr>
<tr>
<td colspan="2">Получатель<br><?php echo $account_details['0']['account_name']; ?></td><td>Сч. №</td><td><?php echo $account_details['0']['account_number']; ?></td>
</tr>
<tr>
<td colspan="2">Банк получателя<br><?php echo $account_details['0']['bank_name']; ?></td><td>Сч. №</td><td><?php echo $account_details['0']['kor_account']; ?></td>
</tr>
</table>

<h1 class="document-type-label">
Счет № <?php $wpo_wcpdf->invoice_number(); ?> от <?php $wpo_wcpdf->invoice_date(); ?><br>Счет действителен в течение 3 дней
</h1>

Плательщик: <?php $wpo_wcpdf->billing_address(); ?><br>
Грузополучатель: <?php $wpo_wcpdf->billing_address(); ?>

<?php do_action( 'wpo_wcpdf_after_document_label', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>

<?php do_action( 'wpo_wcpdf_before_order_details', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>

<table class="order-details">
	<thead>
		<tr>
			<th class="item-number">№</th>
			<th class="product">Наименование товара</th>
			<th class="quantity">Количество</th>
			<th class="quantity">Ед. изм.</th>
			<th class="price">Цена</th>
			<th class="price">Сумма</th>
		</tr>
	</thead>
	<tbody>
		<?php $items = $wpo_wcpdf->get_order_items(); if( sizeof( $items ) > 0 ) : $i=1; foreach( $items as $item_id => $item ) : ?>
		<tr class="<?php echo apply_filters( 'wpo_wcpdf_item_row_class', $item_id, $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order, $item_id ); ?>">
			<td class="item-number"><?php echo $i++; ?></td>
			<td class="product">
				<?php $description_label = __( 'Description', 'wpo_wcpdf' ); // registering alternate label translation ?>
				<span class="item-name"><?php echo $item['name']; ?></span>
				<?php do_action( 'wpo_wcpdf_before_item_meta', $wpo_wcpdf->export->template_type, $item, $wpo_wcpdf->export->order  ); ?>
				<span class="item-meta"><?php echo $item['meta']; ?></span>
				<dl class="meta">
					<?php $description_label = __( 'SKU', 'wpo_wcpdf' ); // registering alternate label translation ?>
					<?php if( !empty( $item['sku'] ) ) : ?><dt class="sku"><?php _e( 'SKU:', 'wpo_wcpdf' ); ?></dt><dd class="sku"><?php echo $item['sku']; ?></dd><?php endif; ?>
					<?php if( !empty( $item['weight'] ) ) : ?><dt class="weight"><?php _e( 'Weight:', 'wpo_wcpdf' ); ?></dt><dd class="weight"><?php echo $item['weight']; ?><?php echo get_option('woocommerce_weight_unit'); ?></dd><?php endif; ?>
				</dl>
				<?php do_action( 'wpo_wcpdf_after_item_meta', $wpo_wcpdf->export->template_type, $item, $wpo_wcpdf->export->order  ); ?>
			</td>
			<td class="quantity"><?php echo $item['quantity']; ?></td>
			<?php 
			$units = get_the_terms( $item['product_id'], 'pa_units'); 
			?>
			<td class="quantity"><?php foreach ($units as $unit) echo $unit->name.'</br>'; ?></td>
			<td class="price"><?php echo $item['single_price']; ?></td>
			<td class="price"><?php echo $item['order_price']; ?></td>
		</tr>
		<?php endforeach; endif; ?>
	</tbody>
	<tfoot>
		<?php $order_totals=$wpo_wcpdf->get_woocommerce_totals(); ?>
		<?php if (isset($order_totals['discount'])): ?>
		<tr>
			<th class="description"  colspan="5">Итого</th>
			<td class="price"><span class="totals-price"><?php echo $wpo_wcpdf->order_subtotal(); ?></span></td>
		</tr>			
		<tr>
			<th class="description"  colspan="5"><?php echo $order_totals['discount']['label']; ?></th>
			<td class="price"><span class="totals-price"><?php echo $order_totals['discount']['value']; ?></span></td>
		</tr>		
		<?php endif; ?>
		<?php //if ($order_totals['shipping']['value']>0): ?>		
		<tr>
			<th class="description"  colspan="5"><?php echo $order_totals['shipping']['label']; ?></th>
			<td class="price"><span class="totals-price"><?php echo $order_totals['shipping']['value']; ?></span></td>
		</tr>
		<?php //endif; ?>		
		<tr>
			<th class="description"  colspan="5">Итого НДС</th>
			<?php $nds=$wpo_wcpdf->export->order->get_total()-$wpo_wcpdf->export->order->get_total()/1.18; 
			$nds=round($nds,2);
			$nds=wc_price($nds);
			?>
			<?php //$nds=wc_price(1115); ?>
			<td class="price"><span class="totals-price"><?php echo $nds; ?></span></td>
		</tr>
		<tr>
			<th class="description"  colspan="5"><?php echo $order_totals['order_total']['label']; ?></th>
			<td class="price"><span class="totals-price"><?php echo $order_totals['order_total']['value']; ?></span></td>
		</tr>	
	</tfoot>
</table>

<p>Всего наименований <?php echo $i-1; ?>, на сумму <?php echo $order_totals['order_total']['value']; ?></p>
<p class="total-str"><b><?php echo num2str($wpo_wcpdf->export->order->get_total()); ?></b></p>

<?php do_action( 'wpo_wcpdf_after_order_details', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>

<div class="extra">
<p><?php if ( isset($wpo_wcpdf->settings->template_settings['extra_1'])) echo $wpo_wcpdf->settings->template_settings['extra_1']; ?></p>
<p><?php if ( isset($wpo_wcpdf->settings->template_settings['extra_2'])) echo $wpo_wcpdf->settings->template_settings['extra_2']; ?></p>
</div>
<?php if ( $wpo_wcpdf->get_footer() ): ?>
<div id="footer">
	<?php $wpo_wcpdf->footer(); ?>
</div><!-- #letter-footer -->
<?php endif; ?>

<pre>
<?php //print_r($wpo_wcpdf->get_woocommerce_totals()); ?>
</pre>

<pre>
<?php 
//$order = WC_API_Orders::($wpo_wcpdf->export->order->id);
//echo $order->$order->get_total(); 
?>
</pre>

<pre>
<?php //echo $wpo_wcpdf->export->order->get_total(); ?>
</pre>
