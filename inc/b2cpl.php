<?php
/**
 * Хуки плагина IN-WC-CRM для B2CPL
 */

// Статус заказа после успешной отправки
add_filter( 'inwccrm_b2cpl_set_order_status', 'mks_b2cpl_set_order_status', 10, 2);
function mks_b2cpl_set_order_status( $status, $order )
{
	return 'sended';
}

// возможность вскрытия
add_filter( 'inwccrm_b2cpl_flag_open', 'mks_b2cpl_flag_open', 10, 2);
function mks_b2cpl_flag_open( $value, $order )
{
	return 0;
}

// тип доставки (code из функции TARIF)
add_filter( 'inwccrm_b2cpl_delivery_type', 'mks_b2cpl_delivery_type', 10, 2);
function mks_b2cpl_delivery_type( $value, $order )
{
	$paymentMethod = $order->get_payment_method();
	if ( $paymentMethod == 'site' || $paymentMethod == 'cp' )
	{
		return 'пр2';
	}	
	return 'пр1';
}

// Сообщение для доставки
add_filter( 'inwccrm_b2cpl_delivery_term', 'mks_b2cpl_delivery_term', 10, 2);
function mks_b2cpl_delivery_term( $value, $order )
{
	return 'Нельзя вскрывать!';
}

// Флаги доставки
//add_filter( 'inwccrm_b2cpl_flag_delivery', 'mks_b2cpl_flag_delivery', 10, 2);
//add_filter( 'inwccrm_b2cpl_flag_return', 'mks_b2cpl_flag_delivery', 10, 2);
function mks_b2cpl_flag_delivery( $value, $order )
{
	return false;
}

// Флаг обновления заказа
//add_filter( 'inwccrm_b2cpl_flag_update', 'mks_b2cpl_flag_update', 10, 2);
function mks_b2cpl_flag_update( $value, $order )
{
	return 1;
}

// Оплата доставки
add_filter( 'inwccrm_b2cpl_price_delivery_pay', 'mks_b2cpl_price_delivery_pay', 10, 2);
function mks_b2cpl_price_delivery_pay( $value, $order )
{
	$paymentMethod = $order->get_payment_method();
	if ( $paymentMethod == 'site' || $paymentMethod == 'cp' || strpos( $$payment_method_code, 'alg_tinkoff_gateway' ) !== false )
	{
		return 0;
	}	
	return $order->get_shipping_total();

}

// Стоимость оплате  оплаченного товара онлайн нулевая
add_filter( 'inwccrm_b2cpl_item_price_pay', 'mks_b2cpl_item_price_pay', 10, 2);
function mks_b2cpl_item_price_pay( $itemPrice, $order, $product=null )
{
	$paymentMethod = $order->get_payment_method();
	if ( $paymentMethod == 'site' || $paymentMethod == 'cp' )
	{
		return 0;
	}	
	return $itemPrice;
}
