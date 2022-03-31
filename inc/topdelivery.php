<?php
/**
 * Хуки плагина IN-WC-CRM для TopDelivery
 */

// https://ivannikitin.com/my-account/projects/?project_id=7043&tab=task&action=todo&list_id=46872&task_id=55024
// 1. Добавить к списке товаров Доставку отдельной строкой. Артикул shipping, вес 0, кол-во 1,  declaredPrice - 0 - по умолчанию.
add_filter( 'inwccrm_topdelivery_items', 'mks_topdelivery_items', 10, 2);
function mks_topdelivery_items( $items, $order )
{
    $paymentMethod = $order->get_payment_method();
    $items[] = array(
        'itemId'        => $order->get_id(),
        'name'          => 'Доставка',
        'article'       => 'shipping_' . $order->get_id(),
        'count'         => 1,
        'declaredPrice' => $order->get_shipping_total(),
        'clientPrice'   => ( $paymentMethod == 'site' || $paymentMethod == 'cp' ) ? 0 : $order->get_shipping_total(),
        'weight'        => 0,
        'push'          => 1,
        'status' => array(
            'id'            => NULL,
            'name'          => NULL,
            'deliveryCount' => NULL,
            'vat'           => NULL,
            'trueMark'      => NULL,
        )
    );    
    return $items;
}

add_filter( 'inwccrm_topdelivery_clientcosts_clientdeliverycost', 'msk_topdelivery_clientcosts_clientdeliverycost', 10, 2);
function msk_topdelivery_clientcosts_clientdeliverycost( $cost, $order )
{
    // Мы уже добавили доставку как отдельный товар, поэтому сумма доставки 0
    return 0;
}



// 2. Если на заказ оплачен на сайте (Сейчас на сайте (со скидкой 3% на все товары в корзине)), то цену за товары передавать из столбца Стоимость к оплате.
add_filter( 'inwccrm_topdelivery_orderitem_clientprice', 'mks_topdelivery_orderitem_clientprice', 10, 3);
function mks_topdelivery_orderitem_clientprice( $itemTotalPrice, $order, $orderItem )
{
    $paymentMethod = $order->get_payment_method(); // get_post_meta( $order->id, '_payment_method', true)
    if ( $paymentMethod == 'site' || $paymentMethod == 'cp' )
        return 0;

    return $itemTotalPrice;
}



// 3. desiredDateDelivery нужно передавать из поля Дата доставки заказа (см. рис.) и интервал доставки. 
add_filter( 'inwccrm_topdelivery_desireddatedelivery', 'mks_topdelivery_desireddatedelivery', 10, 2);
function mks_topdelivery_desireddatedelivery( $desiredDateDelivery, $order )
{
    $metaDeliveryDate = get_post_meta( $order->id, '_shipping_delivery_date', true);
    if ( !empty( $metaDeliveryDate ) ) 
    {
        // Желаемое время доставки
        $metaDeliveryTime = get_post_meta( $order->id, '_shipping_delivery_time', true);
        $parts = explode( '-', $metaDeliveryTime );
        $beginTime = ( isset( $parts[0]) ) ? $parts[0] : '08:00:00';
        $endTime = ( isset( $parts[1]) ) ? $parts[1] : '18:00:00';

        // Желаемая дата доставки
        $desiredDateDelivery = array(
            'date'  => $metaDeliveryDate,
            'timeInterval' => array(
                'bTime' => $beginTime,
                'eTime' => $endTime
            )
        );
    }

    return $desiredDateDelivery;
}

// Передача доп.полей для доставки
add_filter( 'inwccrm_topdelivery_deliveryaddress_incityaddress_address', 'mks_topdelivery_deliveryaddress_incityaddress_address', 10, 2);
function mks_topdelivery_deliveryaddress_incityaddress_address( $address, $order )
{
    $building = get_post_meta( $order->id, '_shipping_building', true);
    if ( empty ( $building ) )  $flat = get_post_meta( $order->id, 'shipping_building', true);
    if ( ! empty( $building ) ) $address .= ', корп. ' . $building;

    $flat = get_post_meta( $order->id, '_shipping_flat', true);
    if ( empty ( $flat ) )  $flat = get_post_meta( $order->id, 'shipping_flat', true);
    if ( ! empty( $flat ) ) $address .= ', кв. ' . $flat;

    return $address;
}

// Статус заказа после успешной отправки
add_filter( 'inwccrm_topdelivery_set_order_status', 'mks_topdelivery_set_order_status', 10, 2);
function mks_topdelivery_set_order_status( $status, $order )
{
	return 'sended';
}

// URL заказа на сайте TopDelivery
add_filter( 'inwccrm_topdelivery_orderurl', 'mks_topdelivery_orderurl', 10, 2);
function mks_topdelivery_orderurl( $url, $order )
{
	return 'https://medknigaservis.ru/';
}