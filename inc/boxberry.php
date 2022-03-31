<?php
/**
 * Хуки плагина IN-WC-CRM для BoxBerry
 */

// Стоимость оплате  оплаченного товара онлайн нулевая
add_filter( 'inwccrm_boxberry_payment_sum', 'mks_boxberry_payment_sum', 10, 2);
function mks_boxberry_payment_sum( $orderTotal, $order )
{
	$paymentMethod = $order->get_payment_method();
	if ( $paymentMethod == 'site' || $paymentMethod == 'cp' )
	{
		return 0;
	}	
	return $orderTotal;
}


// Переключение типа доставки по способу доставки в заказе
add_filter( 'inwccrm_boxberry_vid', 'mks_boxberry_vid', 10, 2);
function mks_boxberry_vid( $vid, $order )
{
	if ( $order->get_shipping_method() == 'Курьерская доставка до двери' ) return 2;
	if ( $order->get_shipping_method() == 'Пункты выдачи заказов сети Боксберри' ) return 1;
	return $vid;
}


// убираем блок при доставке в ПВЗ
add_filter( 'inwccrm_boxberry_shop_name', 'inwccrm_boxberry_shop_name', 10, 2);
function inwccrm_boxberry_shop_name( $shopName, $order )
{
	if ( $order->get_shipping_method() == 'Пункты выдачи заказов сети Боксберри' )
	{
		$address = $order->get_shipping_address_1();
		if ( !empty($address) )
		{
			$matches = array();
			if ( preg_match('/ПВЗ\s+([\d\.]+)\s+.*/', $address, $matches) )
			{
				return $matches[1];
			}
		}
	}
	return $shopName;
}

// убираем блок при доставке в ПВЗ
add_filter( 'inwccrm_boxberry_kurdost', 'mks_boxberry_kurdost', 10, 2);
function mks_boxberry_kurdost( $kurdost, $order )
{
	if ( $order->get_shipping_method() == 'Пункты выдачи заказов сети Боксберри' ) return array();
	return $kurdost;
}



// В почтовой отправке vid = 3
// packing_strict поставьте 0.
// это временно. я скажу, когда вернуть обратно на 1
add_filter( 'inwccrm_boxberry_packing_strict', 'mks_boxberry_packing_strict', 10, 2);
function mks_boxberry_packing_strict( $packing_strict, $order )
{
	if ( $order->get_shipping_method() != 'Курьерская доставка до двери' ) return 0;
	return $packing_strict;
}


// Желаемая дата доставки
add_filter( 'inwccrm_boxberry_kurdost_delivery_date', 'mks_boxberry_kurdost_delivery_date', 10, 2);
function mks_boxberry_kurdost_delivery_date( $delivery_date, $order )
{
	$metaDeliveryDate = get_post_meta( $order->id, '_shipping_delivery_date', true);
    if ( !empty( $metaDeliveryDate ) ) 
    {
		return $metaDeliveryDate;
    }
	
    return $delivery_date;
}


// Желаемое время доставки ОТ
add_filter( 'inwccrm_boxberry_kurdost_timesfrom1', 'mks_boxberry_kurdost_timesfrom1', 10, 2);
function mks_boxberry_kurdost_timesfrom1( $time, $order )
{
	// Желаемое время доставки
	$metaDeliveryTime = get_post_meta( $order->id, '_shipping_delivery_time', true);
	$parts = explode( '-', $metaDeliveryTime );
	$beginTime = ( isset( $parts[0]) ) ? $parts[0] : '08:00:00';
	$endTime = ( isset( $parts[1]) ) ? $parts[1] : '18:00:00';
	$time = substr( $beginTime, 0, 5 );

    return $time;
}

// Желаемое время доставки ДО
add_filter( 'inwccrm_boxberry_kurdost_timesto1', 'mks_boxberry_kurdost_timesto1', 10, 2);
function mks_boxberry_kurdost_timesto1( $time, $order )
{
	// Желаемое время доставки
	$metaDeliveryTime = get_post_meta( $order->id, '_shipping_delivery_time', true);
	$parts = explode( '-', $metaDeliveryTime );
	$beginTime = ( isset( $parts[0]) ) ? $parts[0] : '08:00:00';
	$endTime = ( isset( $parts[1]) ) ? $parts[1] : '18:00:00';
	$time = substr( $endTime, 0, 5 );

    return $time;
}

// Город
add_filter( 'inwccrm_boxberry_kurdost_citi', 'mks_boxberry_kurdost_citi', 10, 2);
function mks_boxberry_kurdost_citi( $city, $order )
{
	if ( $city != 'В черте города' ) return $city;

	$state = empty( $order->get_shipping_state() ) ? $order->get_billing_state() : $order->get_shipping_state();

	if ( $state == 'MS' ) return 'Москва';
	if ( $state == 'LD' ) return 'Санкт-Петербург';

	return $city;
}

// Статус заказа после успешной отправки
add_filter( 'inwccrm_Boxberry_set_order_status', 'mks_Boxberry_set_order_status', 10, 2);
function mks_Boxberry_set_order_status( $status, $order )
{
	return 'sended';
}

// Изменение имени латиницей в русский
//add_filter( 'inwccrm_boxberry_customer_fio', 'mks_boxberry_lat_converter', 10, 2);
//add_filter( 'inwccrm_boxberry_customer_address', 'mks_boxberry_lat_converter', 10, 2);
function mks_boxberry_customer_fio( $value, $order )
{
	$value = str_replace('AaBCcHKMOoPpTXx', 'АаВСсНКМОоРрТХх', $value);
	return $value;
}
