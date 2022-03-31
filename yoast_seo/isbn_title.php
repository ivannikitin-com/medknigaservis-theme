<?php
/**
 * Формирование SEO TITLE с ISBN
 * https://ivannikitin.com/my-account/projects/?project_id=7043&tab=task&action=todo&list_id=9801&task_id=79999#cpm-comment-321251
 */
add_filter( 'wpseo_title', 'mks_seo_title_isbn', 10, 2 );
function mks_seo_title_isbn( $title, $presentation ){
	// Если это продукт
    if ( is_product() ) {
        global $product;
    
        $delimeter = ' • ';
        $isbn = $product->get_attribute( 'isbn' );
        if ( $isbn ){
            $title_parts = array(
                '«' . $product->get_title() . '» ' . $product->get_attribute( 'book-author' ),
                'ISBN ' . $isbn,
                $product->get_attribute( 'god' ),
                'Медкнигасервис'
            );
            return implode($delimeter, $title_parts);
        }
        else {
            $title_parts = array(
                $product->get_title(),
                $product->get_sku(),
                'Медкнигасервис'
            );
            return implode($delimeter, $title_parts);
        }
    }
	return $title;
}