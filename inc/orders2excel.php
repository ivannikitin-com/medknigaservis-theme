<?php
/**
 * Хуки плагина IN-WC-CRM для Orders2Excel
 */

// Шапка таблицы
add_filter( 'inwccrm_orders2excel_table_header', 'mks_orders2excel_table_header');
function mks_orders2excel_table_header()
{
	return array(
            'A1' => __( 'ISBN', IN_WC_CRM ),
            'B1' => __( 'ШтрихКод', IN_WC_CRM ),
            'C1' => __( 'Наименование', IN_WC_CRM ),
            'D1' => __( 'Количество', IN_WC_CRM ),
            'E1' => __( 'Код', IN_WC_CRM ),
            'F1' => __( 'Код С-Пб', IN_WC_CRM )
        );
}

// Выборка элементов заказов для заполнения
add_filter( 'inwccrm_orders2excel_table_data', 'mks_orders2excel_table_data', 10, 2);
function mks_orders2excel_table_data( $items, $orders )
{
	$items = array();
	$discontTotal = 0; 		// Всего скидок
	$shippingTotal = 0;		// Всего доставка
	$itemsTotal = 0;		// Всего сумма товаров
	foreach ( $orders as $order )
	{
		$orderId = $order->get_order_number();
		
		// Считаем скидки и доставку
		$discontTotal += $order->get_total_discount();
		$shippingTotal += $order->get_shipping_total();
		
		$orderItems = $order->get_items();
		foreach( $orderItems as $item_id => $item_data )
		{
			$product_name = $item_data['name'];
			$item_quantity = wc_get_order_item_meta($item_id, '_qty', true);
			$product = wc_get_product( $item_data->get_product_id() );
			if ( !$product ) continue;
			
			$item_sku = $product->get_sku();

			// Суммируем стоимость товаров
			$itemsTotal += $item_data->get_subtotal(); // Без скидок. Со скидками $item_data->get_total()
			
			/**
			if ( strpos( $item_sku, '-' ) !== false )
			{
				$parts = explode( '-', $item_sku );
				$item_sku = $parts[0];
			}
			*/

			$items[] = array(
				'orderId' => $orderId,
				'sku' => $item_sku,
				'name' => $product_name,
				'quantity'  => $item_quantity			
			);
		}
	}
	
	// Добавляем РЯД скидок
	$items[] = array(
		'orderId' => $discontTotal,
		'sku' => '',
		'name' => 'Всего скидок',
		'quantity'  => ''
	);

	// Добавляем РЯД доставки
	$items[] = array(
		'orderId' => $shippingTotal,
		'sku' => ( $shippingTotal > 0) ? '00003945' : '',
		'name' => 'Доставка',
		'quantity'  => ( $shippingTotal > 0) ? '1' : ''
	);

	// Добавляем РЯД стоимости книг
	$items[] = array(
		'orderId' => $itemsTotal,
		'sku' => '',
		'name' => 'Цена книг',
		'quantity'  => ''
	);	
	
	return $items;
}

// Заполнение данными ряда Excel
add_filter( 'inwccrm_orders2excel_table_row_data', 'mks_orders2excel_table_row_data', 10, 3);
function mks_orders2excel_table_row_data( $rowData, $orderItem, $row )
{
	return array(
		'C' . $row => $orderItem['name'],
		'D' . $row => $orderItem['quantity'],
		'E' . $row => $orderItem['sku'],
		'F' . $row => $orderItem['orderId']
	);
}