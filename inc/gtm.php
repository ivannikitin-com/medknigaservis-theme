<?php
/**
 * Функции передачи данныж в GTM
 */

/**
 * Для реализации вывода ID категории в dataLayer по задаче
 * https://ivannikitin.com/my-account/projects/?project_id=7043&tab=task&action=todo&list_id=36078&task_id=38592#cpm-comment-198907
 */
add_action( 'wp_footer', function(){
	$category = get_queried_object();
	if ( isset( $category ) && $category->taxonomy == 'product_cat' )
	{
		echo "<script>(dataLayer = dataLayer ||[]).push({ 'product_cat' : {$category->term_id} })</script>";
	}
});

/**
 * Для реализации вывода издательства как бренда продукта
 * https://ivannikitin.com/my-account/projects/?project_id=7043&tab=task&action=todo&list_id=36078&task_id=38592
 */
/*
add_filter( 'gtm4wp_eec_product_array', function( $_temp_productdata, $action ){
	$product_id = $_temp_productdata['id'];
	$cache_key = 'pa_publisher_product_' . $product_id;
	$publisher = wp_cache_get( $cache_key );
	if ( false === $publisher )
	{
		$product = new WC_Product( $product_id );
		$publisher = $product->get_attribute( 'pa_publisher' );
		wp_cache_set( $cache_key, $publisher );
	}
	$_temp_productdata['brand'] = $publisher;
	return $_temp_productdata;
}, 10, 2 );*/