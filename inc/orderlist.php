<?php
/**
 * Хуки плагина IN-WC-CRM для Списка заказов
 */

// Всего заказов в запросе
add_filter( 'inwccrm_orderlist_datatable_order_limit', 'mks_orderlist_datatable_order_limit' );
function mks_orderlist_datatable_order_limit( $limit )
{
	return -1;
}

// Методы доставки
add_filter( 'inwccrm_orderlist_shipping_methods', 'mks_inwccrm_orderlist_shipping_methods' );
function mks_inwccrm_orderlist_shipping_methods( $shippingMethods )
{
	// Убираем ненужные
	unset( $shippingMethods['flat_rate'] );
	unset( $shippingMethods['free_shipping'] );
	unset( $shippingMethods['local_pickup'] );
	unset( $shippingMethods['advanced_shipping'] );
	// Добавляем нужные
	// $shippingMethods['custom_pickpoint'] = 'Пункты выдачи заказов';
	$shippingMethods['custom_pickpoint_pvz'] = 'Пункты выдачи заказов сети PickPoint';
	$shippingMethods['custom_boxberry_pvz'] = 'Пункты выдачи заказов сети Боксберри';
	$shippingMethods['custom_post'] = 'Доставка почтой';
	$shippingMethods['custom_courier'] = 'Курьерская доставка до двери';
	return $shippingMethods;
}

// Методы оплаты
add_filter( 'inwccrm_orderlist_payment_methods', 'mks_orderlist_payment_methods' );
function mks_orderlist_payment_methods( $paymentMethods )
{
	$paymentMethods['nal'] = 'Наличными/картой при получении';
	$paymentMethods['site'] = 'Сейчас на сайте (со скидкой 2% на все товары в корзине)';
	return $paymentMethods;
}

// Статус заказа по умолчанию
add_filter( 'inwccrm_orderlist_default_status', 'mks_inwccrm_orderlist_default_status' );
function mks_inwccrm_orderlist_default_status()
{
	return 'wc-in-work';
}

// Колонки таблицы
add_filter( 'inwccrm_orderlist_columns', 'mks_inwccrm_orderlist_columns');
function mks_inwccrm_orderlist_columns( $columns )
{
	// Убираем Стоимость доставки
	unset($columns['shipping_cost']);
	
	// Добавляем Стоимость к оплате
	$columns['payment_value'] = 'Стоимость к оплате';
	
	// Добавлеяем почтовый индекс
	$columns['zip'] = 'Индекс';

	// Добавлеяем почтовый индекс
	$columns['region'] = 'Регион';	

	// Время доставки
	$columns['deliveryTime'] = 'Желаемое время';
	
	return $columns;
}
// Вывод новых колонок
add_filter( 'inwccrm_orderlist_column_data', 'mks_inwccrm_orderlist_column_data', 10, 3);
function mks_inwccrm_orderlist_column_data( $data, $column, $order )
{
	switch ( $column )
    {
		case 'payment_value':
			$total_2 = 0;	
			$payment_method = $order->get_payment_method_title();
			if ( $payment_method == 'Наличными/картой при получении' || $payment_method == 'По квитанции в банке' )
			{
				$total_2 = $order->get_total();
			}
			return $total_2;
			
		case 'zip':
			return empty( $order->get_shipping_postcode() ) ? $order->get_billing_postcode() : $order->get_shipping_postcode();

		case 'region':
			return empty( $order->get_shipping_state() ) ? $order->get_billing_state() : $order->get_shipping_state();
	
		
		case 'deliveryTime':
			$deliveryTime = get_post_meta( $order->id, '_shipping_delivery_time', true);
			if ( empty ($deliveryTime) ) return '';

			$intervalValues = explode( '-', $deliveryTime );
			$beginTime = ( isset( $intervalValues[0] ) ) ? $intervalValues[0] : '';
			$endTime = ( isset( $intervalValues[1] ) ) ? $intervalValues[1] : '';

			if ( !empty( $beginTime ) )
			{
				$beginTimeParts = explode(':', $beginTime );
				$beginTime = $beginTimeParts[0];
			}
			if ( !empty( $endTime ) )
			{
				$endTimeParts = explode(':', $endTime );
				$endTime = $endTimeParts[0];
			}
			return $beginTime . ' - ' . $endTime;

		default:
			return $data;
	}
}