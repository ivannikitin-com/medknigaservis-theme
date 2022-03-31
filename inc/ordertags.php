<?php
/**
 * Правила заказов
 */
add_filter( 'inwccrm_ordertags_check', 'mks_ordertags_check', 10, 5);
function mks_ordertags_check($result, $order, $param, $condition, $value)
{
    // Логируем все проверки
    $logFile = get_stylesheet_directory() . '/inc/ordertags.log';
    $logMessage = '[' . date('d.m.Y H:i:s'). '] Заказ #' . $order->id . PHP_EOL . 
        '  param: ' . var_export($param, true) . PHP_EOL .
        '  value: ' . var_export($value, true) . PHP_EOL .
        '  result: ' . var_export($result, true) . PHP_EOL .
        '  condition: ' . var_export($condition, true) . PHP_EOL .
        '  get_shipping_method: ' . $order->get_shipping_method() . PHP_EOL .
        var_export($order, true) .
        '----------------------------------------------------------' . PHP_EOL;
    file_put_contents( $logFile, $logMessage, FILE_APPEND);
    return $result;
}
