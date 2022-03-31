<?php
/**
 * Хуки плагина IN-WC-CRM для PickPoint
 */
// -------------------------------------------- Старые фильтры. v 1.0 --------------------------------------------
// Число рядов в запросе
add_filter( 'inwccrm_pickpoint_datatable_order_limit', 'mks_pickpoint_order_limit');
function mks_pickpoint_order_limit()
{
	return 100;
}

// Число рядов в запросе
add_filter( 'inwccrm_pickpoint_datatable_page_length', 'mks_pickpoint_datatable_page_length');
function mks_pickpoint_datatable_page_length()
{
	return 50;
}

// Названия колонок
add_filter( 'inwccrm_pickpoint_datatable_header_columns', 'mks_pickpoint_datatable_header_columns');
function mks_pickpoint_datatable_header_columns( $cols )
{
	$cols[6] = 'К оплате';
	return $cols;
}


// Способоы доставки в шапке
add_filter( 'inwccrm_pickpoint_header_shipping_methods', 'mks_pickpoint_header_shipping_methods');
function mks_pickpoint_header_shipping_methods( $shipping_methods )
{
	unset( $shipping_methods['flat_rate'] );
	unset( $shipping_methods['free_shipping'] );
	unset( $shipping_methods['local_pickup'] );
	
	return $shipping_methods;
}

/**
 * Вывод колонки Стоимость доставки
 * ЕСЛИ payment == Наличные "Наличные, тогда total_2 = total
 * Если payment НЕ НАЛИЧНЫЕ, тогда total_2 = 0 
 */
add_filter( 'inwccrm_pickpoint_datatable_shipping_cost', 	'mks_pickpoint_datatable_shipping_cost', 10, 2 );
add_filter( 'inwccrm_pickpoint_sum', 						'mks_pickpoint_datatable_shipping_cost', 10, 2 );
function mks_pickpoint_datatable_shipping_cost( $shipping_cost, $order )
{		
	$total_2 = 0;	
	
	$payment_method = $order->get_payment_method_title();
	if ( $payment_method == 'Наличными/картой при получении' || $payment_method == 'По квитанции в банке' )
		$total_2 = $order->get_total();
		
	return $total_2;
}

// --------------------------------- Фильтры версии 1.1 ---------------------------------

// Статус заказа после успешной отправки
add_filter( 'inwccrm_pickpoint_set_order_status', 'mks_pickpoint_set_order_status', 10, 2);
function mks_pickpoint_set_order_status( $status, $order )
{
	return 'sended';
}


// E-mail незарегистрированных пользователей
add_filter( 'inwccrm_pickpoint_email', 'mks_pickpoint_email', 10, 2);
function mks_pickpoint_email( $email, $order )
{
	if ( empty( $email ) ) 
		return '';
	else
		return $email;
}