<?php
/**
 * Image Swap style thumbnail
 *
 * @package Total Wordpress Theme
 * @subpackage Templates/WooCommerce
 * @version 3.3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Return placeholder if there isn't a thumbnail defined.
if ( ! has_post_thumbnail() ) {
    wpex_woo_placeholder_img();
    return;
}

// Get featured image
$attachment = get_post_thumbnail_id();

// Display featured image if defined
if ( $attachment ) {
ob_start();
    wpex_post_thumbnail( array(
        'attachment' => $attachment,
        'size'       => 'shop_catalog',
        'alt'        => wpex_get_esc_title(),
        'class'      => 'woo-entry-image-main',
    ) );
$wpex_image = apply_filters( 'woocommerce_product_get_image', ob_get_clean());
	
}

// Display placeholder
else {
    $dummy_image = '<img src="'. wc_placeholder_img_src() .'" alt="'. esc_html__( 'Placeholder Image', 'total' ) .'" class="woo-entry-image-main" />';
	$wpex_image = apply_filters( 'woocommerce_product_get_image', $dummy_image);
}
echo $wpex_image; 
?>