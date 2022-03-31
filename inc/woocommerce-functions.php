<?php
add_filter('woocommerce_currency_symbol', 'add_my_currency_symbol', 10, 2);
 
function add_my_currency_symbol( $currency_symbol, $currency ) {
     switch( $currency ) {
          case 'RUB': $currency_symbol = 'руб.'; break;
     }
     return $currency_symbol;
}
// Используем формат цены вариативного товара WC 2.0
add_filter( 'woocommerce_variable_sale_price_html', 'wc_wc20_variation_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'wc_wc20_variation_price_format', 10, 2 );
function wc_wc20_variation_price_format( $price, $product ) {
// Основная цена
$prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
$price = $prices[0] !== $prices[1] ? sprintf( __( 'от %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
// Цена со скидкой
$prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
sort( $prices );
$saleprice = $prices[0] !== $prices[1] ? sprintf( __( 'от %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

if ( $price !== $saleprice ) {
$price = '<del>' . $saleprice . '</del> <ins>' . $price . '</ins>';
}
return $price;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
function woocommerce_header_add_to_cart_fragment( $fragments ) {

	ob_start();
	?>

						<a href="<?php echo wc_get_cart_url(); ?>" class="menucart">
                        	<span class="fa fa-shopping-cart"></span>
                            <span class="hidden-xs">Товаров на</span>
                            <span> <?php echo (is_page('cart'))? wc_price(0) : WC()->cart->get_cart_total(); ?></span>
                        </a>
	<?php
	
	$fragments['a.menucart'] = ob_get_clean();
	
	return $fragments;
}
function remove_added_to_cart_notice()
{
    $notices = WC()->session->get('wc_notices', array());
	$added_to_cart_key=array();
    if ($notices){
	foreach( $notices['success'] as $key => $notice){
        if( strpos( $notice, 'removed' ) !== false){
            $added_to_cart_key[] = $key;
        }
    }
//	foreach( $added_to_cart_key as $unset_notice){
		unset( $notices['success'] );
//	}

    WC()->session->set('wc_notices', $notices);
	}
}
add_action('woocommerce_before_single_product','remove_added_to_cart_notice',1);
add_action('woocommerce_shortcode_before_product_cat_loop','remove_added_to_cart_notice',1);
add_action('woocommerce_before_shop_loop','remove_added_to_cart_notice',1);
add_filter( 'woocommerce_notice_types','med_remove_notices');
function med_remove_notices ($notice_types){
	$all_notices  = WC()->session->get( 'wc_notices', array() );
	unset($notice_types['success']);
	unset($notice_types['notice']);
	return $notice_types;
}

/***********************************
Настраиваем карточку товара
**********************************/
//add_action( 'after_setup_theme', 'med_theme_setup' );
//function med_theme_setup() {
   remove_theme_support( 'wc-product-gallery-zoom' );
//}
remove_action( 'woocommerce_before_single_product','wc_print_notices',10 );
add_action( 'woocommerce_before_single_product_summary', 'med_electron',1 );
function med_electron() {
	/*Запрос к серверу с электронными книгами далаем заранее, чтобы перед выводом бейджика 
	успеть обновить поле признака электронной книги, если запрос не вернет url книги*/
	med_download_button();
}

remove_action( 'woocommerce_before_single_product_summary','woocommerce_show_product_sale_flash',10 );
//add_action( 'woocommerce_before_single_product_summary', 'med_badges',5 ); /*10.03.2019*/
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'med_single_med_badges_open',1 );/*10.03.2019*/
function med_single_med_badges_open($html){ 
	$html .='<div class="single_product_badges">';
	return $html;
}

add_filter( 'woocommerce_single_product_image_thumbnail_html', 'med_single_med_badges',3 );/*10.03.2019*/	

function med_badges(){
	global $product;

	if ( !is_front_page() && has_term( 'novinki', 'product_cat', $product->get_ID())) { 

		echo '<div class="new">' . esc_html__( 'Новинка', 'woocommerce' ) . '</div>';
	}
	if ( get_post_meta($product->get_ID(),'_backorders',true) == 'notify') { 

		echo '<div class="backorder">' . esc_html__( 'Предзаказ', 'woocommerce' ) . '</div>';
	}	
}

function med_single_med_badges($html){	
	global $product;
	if (null == $product) {
		return $html;
	}
	ob_start();
	woocommerce_show_product_sale_flash();
	med_badges();
	$badges_html = ob_get_clean();
	$html .= $badges_html;
	return $html;
}

add_filter( 'woocommerce_single_product_image_thumbnail_html', 'med_single_med_badges_close',4 );/*10.03.2019*/
function med_single_med_badges_close($html){
	$html .='</div><!-- /.single_product_badges -->';
	return $html;
}
//add_action( 'woocommerce_single_product_image_html', 'woocommerce_show_product_sale_flash',6 );
remove_action( 'woocommerce_single_product_summary','woocommerce_template_single_price',10 );
add_action( 'woocommerce_single_product_summary','med_single_product_attributes',10 );
function med_single_product_attributes(){
	global $product; ?>
	<table class="shop_attributes">
		<tbody><tr class="">
		<th><?php _e('SKU', 'woocommerce'); ?></th>
			<td><?php echo $product->get_sku(); ?></td>
		</tr></tbody>
	</table>	
	<?php wc_display_product_attributes($product); ?>	
<?php }
add_action( 'woocommerce_single_product_summary','woocommerce_template_single_price',15 );
add_filter( 'wc_product_enable_dimensions_display','med_enable_dimensions_display');
function med_enable_dimensions_display(){
	return false;
}
add_action('woocommerce_single_product_summary','med_electron_button_output',35);
function med_electron_button_output() {
	global $button_html;
	if ($button_html) {
		echo $button_html;
	}
}
function med_download_button(){
	global $product, $button_html;	
	$isbn=$product->get_attribute('isbn');
	$is_electron_badge = get_post_meta($product->get_id(),'_yith_wcbm_product_meta',true);
	$button_html="";
	echo '<div class="hidden">';
	echo '$isbn:'.$isbn.'<br>';
	echo '$is_electron_badge:';
	print_r($is_electron_badge);
	echo '</div>';
	if (!$isbn || !$is_electron_badge || !isset($is_electron_badge['id_badge'])) return;
	$request = 'http://www.rosmedlib.ru/cgi-bin/mb4x?usr_data=gdaccessdata(shell,want_to_buy(ISBN:'.$isbn.',medknigaservis))';

	try
	{
		file_put_contents( $_SERVER['DOCUMENT_ROOT'] . '/../logs/rosmedlib-send.log', $request . PHP_EOL . PHP_EOL, FILE_APPEND );		
		$answer = file_get_contents($request);
		$xml_answer = simplexml_load_string($answer,null,LIBXML_NOCDATA);
		if (property_exists($xml_answer,'url')) {
			$button_html = '<a href="'.$xml_answer->url.'" class="button buy_digital" target="_blank">'.__('Купить электронную версию','medknigasevis').' за '.wc_price($xml_answer['price']).'</a>';
		} else {
		/*Если в ответе сервера на содержится url электронной книги, то очищаем поля признака электронной книги*/
			update_post_meta($product->get_id(),'_yith_wcbm_product_meta',array());
			update_post_meta($product->get_id(),'_virtual','no');
		}
	}
	catch ( Exception $e )
	{
		// Была ошибка!
		file_put_contents( $_SERVER['DOCUMENT_ROOT'] . '/../logs/rosmedlib-error.log', $e->getMessage() . PHP_EOL . PHP_EOL, FILE_APPEND );
	}
}
remove_action( 'woocommerce_single_product_summary','woocommerce_template_single_meta',40 );
add_action( 'woocommerce_after_single_product_summary','woocommerce_template_single_meta',5 );
add_filter('term_links-product_cat', 'med_links_target_add');
add_filter('term_links-product_tag', 'med_links_target_add');
function med_links_target_add($links){
	$default_cat=get_term_by('id',get_option( 'default_product_cat' ),'product_cat');
	foreach ($links as $key=>&$link){
		if (strpos($link,$default_cat->name)>0) {
			unset($links[$key]); 
		} else {
			$link = str_replace ('<a ','<a target="_blank" ',$link);
		}
	}
	return $links;
}



add_filter( 'woocommerce_product_additional_information_heading','med_additional_information_heading');
add_filter( 'woocommerce_product_description_heading','med_additional_information_heading');
function med_additional_information_heading(){
	return '';
}
remove_action( 'woocommerce_product_additional_information', 'wc_display_product_attributes', 10 );
add_action( 'woocommerce_product_additional_information', 'med_display_book_contents', 10 );
function med_display_book_contents(){
	global $product;
	$contents=get_field('contents');
	if ($contents) {
		echo $contents;
	}
}
add_filter( 'woocommerce_product_tabs', 'med_edit_product_tabs', 98 );
function med_edit_product_tabs( $tabs ) {
	global $product;
	$contents=get_field('contents');
	if (!$contents){	
		unset($tabs['additional_information']);
	} else {
		$tabs ['additional_information']['title'] = "Содержание" ;
	}
	$count = $product->get_review_count();
	$count=($count)?' ('.$count.')':'';
	if ($product->get_reviews_allowed()){
		$tabs ['reviews']['title'] = "Комментарии".$count ;
		$tabs ['reviews']['priority'] = 40;
	}
	$example=get_post_meta($product->get_id(),'example',true);
	if ($example){
	$tabs['example'] = array(
            'title' => "Пример страниц",
            'priority' => 35,
            'callback' => 'page_example_tab'
        );
	}
	return $tabs;
}
function page_example_tab(){
	global $product;
	
	$example_file=get_field('example');
	
	if ($example_file) {
		$example = $example_file;
	} else {
		$example=get_post_meta($product->get_id(),'example',true);
	}
	if (!$example) return;
	?>
	<div class="dnl_file">
		<?php echo do_shortcode('[pdf-embedder url="'.$example.'"]'); ?>
		</div>	
	<?php 
}
add_filter( 'wc_add_to_cart_message_html','med_add_to_cart_message_html');
function med_add_to_cart_message_html($message){
	if ( 'no' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
		$message=preg_replace('|(>).+(</a>)|isU', "$1".__('View your cart','woocommerce')."$2",$message);
	}
	return $message;
}
add_filter( 'woocommerce_format_sale_price', 'med_format_sale_price',10,3 );
function med_format_sale_price ($price, $regular_price, $sale_price){
	$price =  '<ins>' . ( is_numeric( $sale_price ) ? wc_price( $sale_price ) : $sale_price ) . '</ins> <del>' . ( is_numeric( $regular_price ) ? wc_price( $regular_price ) : $regular_price ) . '</del>';
	return $price;
}

//add_action( 'woocommerce_after_single_product_summary','med_bestselling_products',30 );
function med_bestselling_products() {
	if (is_single(83633)) {
		$cur_terms ='';
		$cur_terms_arr = array();
		$cur_terms = get_the_terms( get_the_ID(), 'product_cat' );
		if( is_array( $cur_terms ) ){
			foreach ($cur_terms as $cur_term) {
				$cur_terms_arr[] = $cur_term->term_id;
			}
			$cur_terms_str=implode(',',$cur_terms_arr);
			echo '<section class="related products">';
			echo do_shortcode('[bestselling_product_categories cats="'.$cur_terms_str.'" per_cat="3" columns="3"]');
			echo '<section>';
		}

	}
}

/***********************************
Настраиваем товарную категорию
**********************************/

// ***[[[ ЭТОТ ХУК СИЛЬНО НАГРУЖАЕТ MYSQL ]]] ***
add_filter( 'pre_get_posts', 'med_hide_empty_price_products', 25 );
function med_hide_empty_price_products( $query ) {
	if(
		! is_admin()
		&& $query->is_main_query()
		&& ( is_shop() || is_product_category() || is_product_tag() )
	) { 
		$query->set( 
			'meta_query', 
			array( 
				'relation' => 'OR',
				array(
					'key'       => '_regular_price',
					'value'     => '',
					'compare'   => 'NOT IN'
				),
				array(
					'key'       => '_virtual',
					'value'     => 'yes',
					'compare'   => 'IN'
				),
				array(
					'key'       => '_sku',
					'value'     => '-P',
					'compare'   => 'LIKE'
				),
				// array(
				// 	'key'       => '_stock_status',
				// 	'value'     => 'outofstock',
				// 	'compare'   => 'NOT IN'
				// )							
			)
		); 
	} 
}

//Подменяем wc-функцию, чтобы ссылка открывалась в новой вкладке
function woocommerce_template_loop_product_link_open() {
		global $product;

		$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );

		echo '<a href="' . esc_url( $link ) . '" target="_blank" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
}

add_action( 'woocommerce_archive_description', 'med_category_title',15 );
function med_category_title(){ ?>
	<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
<?php }
remove_action( 'woocommerce_before_shop_loop_item_title', array( 'WPEX_WooCommerce_Config', 'loop_product_thumbnail' ), 10 );

/*Помещаем бейджик распродажи и новинки внутрь обертки div class="single_product_badges"*/
remove_action( 'woocommerce_before_shop_loop_item_title','woocommerce_show_product_loop_sale_flash',10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

add_filter( 'woocommerce_product_get_image', 'med_single_med_badges_open',2 );/*10.03.2019*/
add_filter( 'woocommerce_product_get_image','med_single_med_badges',3 );
add_filter( 'woocommerce_product_get_image', 'med_single_med_badges_close',10000 );/*10.03.2019*/



//add_filter( 'woocommerce_product_get_image','med_loop_badges_output_and_wrap',1000 );
function med_loop_badges_output_and_wrap($html) {
	ob_start();
	woocommerce_show_product_sale_flash();
	med_badges();
	$other_bages = ob_get_clean();
	$html = $html.'<div class="single_product_badges">'.$other_bages.'</div><!--/.single_product_badges-->';
	return $html;
}
//add_filter( 'yith_wcbm_print_container_image_and_badge', function($flag){return false;} ); 

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating',5 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating',15 );

if ( ! function_exists( 'woocommerce_template_loop_product_title' ) ) {

	/**
	 * Show the product title in the product loop. By default this is an H3.
	 */
	function woocommerce_template_loop_product_title() {
		echo '<h2 class="woocommerce-loop-product__title">' . str_cut(get_the_title(),65) . '</h2>';
	}
}

remove_action( 'woocommerce_after_shop_loop_item','woocommerce_template_loop_product_link_close',5 );
add_action( 'woocommerce_after_shop_loop_item','woocommerce_template_loop_product_link_close',11 );
add_action( 'woocommerce_after_shop_loop', 'med_add_taxonomy_archive_description', 1000 );
function med_add_taxonomy_archive_description() {
	if (!is_shop()) {
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	$add_description = get_field('add_description', 'product_cat_'.$term->term_id);
	if ($add_description){
		echo '<div class="term-description clr">';
		echo $add_description;	
		echo '</div>';
	}
	}
}
add_action( 'init', 'custom_fix_thumbnail' );
  
function custom_fix_thumbnail() {
  add_filter('woocommerce_placeholder_img_src', 'med_woocommerce_placeholder_img_src');
    
    function med_woocommerce_placeholder_img_src( $src ) {
    $src = get_stylesheet_directory_uri().'/placeholder_1.png';     
    return $src;
    }
}

// DEBUG: Дает ошибку!
//add_filter( 'woocommerce_product_add_to_cart_text', 'med_archive_custom_cart_button_text' );
function med_archive_custom_cart_button_text($default_text) {
	global $woocommerce, $product;
	foreach( WC()->cart->get_cart() as $cart_item_key => $values ) {
		$_product = $values['data'];
		 
		if( get_the_ID() == $_product->get_id() ) {
			return __('Оформить', 'woocommerce');
		}
	}
	return $default_text;
}

add_filter( 'woocommerce_loop_add_to_cart_link','med_archive_custom_cart_button_link');
function med_archive_custom_cart_button_link($html){
	return $html;
}

// DEBUG: Дает ошибку!
//add_filter( 'woocommerce_loop_add_to_cart_args','med_loop_add_to_cart_args');
function med_loop_add_to_cart_args($args){
	global $woocommerce;
	foreach( WC()->cart->get_cart() as $cart_item_key => $values ) {
		$_product = $values['data'];
		 
		if( get_the_ID() == $_product->get_id() ) {
			$args['class']=$args['class']." in_cart_already";
			return $args;
		}
	}	
	return $args;
}
add_filter( 'woocommerce_subcategory_count_html', 'med_hide_category_count' );
function med_hide_category_count() {
	return '';
}
remove_filter('pre_term_description', 'wp_filter_kses');
remove_filter('pre_term_description', 'wp_kses_data');

/**
* Display product sub categories.
*
* @subpackage	Loop
* @param array $args
* @return null|boolean
*/
function woocommerce_output_product_categories( $args = array() ) {
		global $wp_query;
		
		$defaults = array(
			'before'        => '',
			'after'         => '',
			'force_display' => false,
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		// Main query only
		if ( ! is_main_query() && ! $force_display ) {
			return;
		}

		// Don't show when filtering, searching or when on page > 1 and ensure we're on a product archive
		if ( is_search() || is_filtered() || is_paged() || ( ! is_product_category() && ! is_shop() ) ) {
			return;
		}

		// Check categories are enabled
		if ( is_shop() && '' === get_option( 'woocommerce_shop_page_display' ) ) {
			return;
		}

		// Find the category + category parent, if applicable
		$term 			= get_queried_object();
		$parent_id 		= empty( $term->term_id ) ? 0 : $term->term_id;

		if ( is_product_category() ) {
			$display_type = get_woocommerce_term_meta( $term->term_id, 'display_type', true );

			switch ( $display_type ) {
				case 'products' :
					return;
				break;
				case '' :
					if ( '' === get_option( 'woocommerce_category_archive_display' ) ) {
						return;
					}
				break;
			}
		}

		// NOTE: using child_of instead of parent - this is not ideal but due to a WP bug ( https://core.trac.wordpress.org/ticket/15626 ) pad_counts won't work
		$product_categories = get_categories( apply_filters( 'woocommerce_product_subcategories_args', array(
			'parent'       	=> $parent_id,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'   	=> 0,
			'hierarchical' 	=> 1,
			'taxonomy'     	=> 'product_cat',
			'pad_counts'   	=> 1,
			'menu_order'	=> false,
		) ) );

		/*if ( apply_filters( 'woocommerce_product_subcategories_hide_empty', true ) ) {
			$product_categories = wp_list_filter( $product_categories, array( 'count' => 0 ), 'NOT' );
		}*/
		
		$product_categories = sort_nested_arrays( $product_categories, array('name' => 'asc') );

		if ( $product_categories ) {
			$total_number=count($product_categories);
			$number_per_column=ceil($total_number/3);
			echo $before;
			/*col span_1_of_3*/
			echo '<div class="col-md-4 col-sm-12">';
			foreach ( $product_categories as $key=>$category ) {
				if ((($key+1)>$number_per_column) && (($key+1)%$number_per_column==1)){
					echo '</div>';
					echo '<div class="col-md-4 col-sm-12">';
				}
				wc_get_template( 'content-product_cat.php', array(
					'category' => $category,
				) );
			}
			echo '</div>';
			// If we are hiding products disable the loop and pagination
			if ( is_product_category() ) {
				$display_type = get_woocommerce_term_meta( $term->term_id, 'display_type', true );

				switch ( $display_type ) {
					case 'subcategories' :
						$wp_query->post_count    = 0;
						$wp_query->max_num_pages = 0;
					break;
					case '' :
						if ( 'subcategories' === get_option( 'woocommerce_category_archive_display' ) ) {
							$wp_query->post_count    = 0;
							$wp_query->max_num_pages = 0;
						}
					break;
				}
			}

			if ( is_shop() && 'subcategories' === get_option( 'woocommerce_shop_page_display' ) ) {
				$wp_query->post_count    = 0;
				$wp_query->max_num_pages = 0;
			}

			echo $after;

			return true;
		}
}
add_filter( 'woocommerce_product_subcategories_args','med_product_subcategories_args');
function med_product_subcategories_args($args){
	$args['orderby']='name';
	$args['order']='ASC';
	$args['hide_empty']=0;
	unset($args['menu_order']);
	return $args;
}
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );


/***********************************
/* Cart */
/***********************************/

//Вывод баннеров под таблицей корзины
add_action( 'woocommerce_after_cart_table', 'med_output_banners',10 );
function med_output_banners() {
	$left_banner=get_field('left_banner',2);
	if (!$left_banner) {$left_banner = get_stylesheet_directory_uri().'/img/banner.jpg';}
	$right_banner=get_field('right_banner',2);
	if (!$right_banner) {$right_banner = get_stylesheet_directory_uri().'/img/banner.jpg';}?>
    <div class="info_blocks">
		<div class="mk-row clr">
			<div class="col-sm-6 col-md-6"><img src="<?php echo $left_banner; ?>" class="img-responsive"></div>
            <div class="col-sm-6 col-md-6"><img src="<?php echo $right_banner ?>" class="img-responsive"></div>
		</div><!--/.row-->
	</div><!--/.info_blocks-->
	<?php
}

/***********************************
/* Checkout */
/***********************************/

add_action( 'woocommerce_created_customer', 'med_save_extra_register_fields' );
function med_save_extra_register_fields(){
	if ( !isset( $_POST['billing_first_name']) && isset($_POST['first_name'])  ) {

              update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['first_name'] ) );

       }

 

       if ( !isset( $_POST['billing_last_name']) && isset($_POST['last_name'])  ) {

              update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['last_name'] ) );

       }
}
add_filter( 'woocommerce_default_address_fields' , 'med_override_default_address_fields' );
function med_override_default_address_fields( $address_fields ) {
	$address_fields['state']['priority']=10;
	 $address_fields['address_1']['required']=false;
	 $address_fields['address_1']['priority']=78;
	 $address_fields['address_2']['required']=false;
	 $address_fields['address_2']['priority']=79;
     $address_fields['building']= array(
				'label'        => __( 'Корпус', 'woocommerce' ),
				'required'     => false,
				'class'        => array( 'form-row-first' ),
				'autocomplete' => '',
				'priority'     => 80
				);
	 $address_fields['flat']= array(
				'label'        => __( 'Квартира', 'woocommerce' ),
				'required'     => false,
				'class'        => array( 'form-row-last' ),
				'autocomplete' => '',
				'priority'     => 90
				);	 
     return $address_fields;
}

function med_my_address_formatted_address( $fields, $customer_id, $name ) {
	if ($name==='shipping') {
		$building = get_user_meta( $customer_id, 'shipping_building', true ); 
		$fields['building'] = $building;
		$flat = get_user_meta( $customer_id, 'shipping_flat', true ); 
		$fields['flat'] = $flat;
	}
	if ($name==='billing') {
		if (!$fields['first_name']) {
			$fields['first_name'] = get_user_meta( $customer_id, 'first_name', true );
			$fields['last_name'] = get_user_meta( $customer_id, 'last_name', true );
		}
	}
	return $fields;
}
add_filter( 'woocommerce_order_formatted_shipping_address' , 'med_default_order_address_fields', 10,2 );
function med_default_order_address_fields( $fields, $order ) {
	$building = get_post_meta( $order->id, 'shipping_building', true ); 
	$fields['building'] = $building;
	$flat = get_post_meta( $order->id, 'shipping_flat', true ); 
	$fields['flat'] = $flat;
	return $fields;
}

add_filter( 'woocommerce_formatted_address_replacements', 'add_new_replacement_fields',10,2 );
function add_new_replacement_fields( $replacements, $address ) {
	$replacements['{state}'] = isset($address['state']) ? WC()->countries->states['RU'][$address['state']] : '';
	$replacements['{address_2}'] = (isset($address['address_2']) && $address['address_2'] && $address['address_2']!=='-') ? ', д. '.$address['address_2'] : '';
	$replacements['{building}'] = (isset($address['building']) && $address['building'])? ' корп. '.$address['building'] : '';
	$replacements['{flat}'] = (isset($address['flat']) && $address['flat'])? ', кв. '.$address['flat'] : '';
	return $replacements;
}

add_filter( 'woocommerce_localisation_address_formats', 'med_address_formats' );
function med_address_formats( $formats ) {
	$formats['default'] = "{postcode}\n{country}, {state}, {city}\n{address_1}{address_2}{building}{flat}\n{name}\n{company}";

	return $formats;
}
add_action( 'woocommerce_before_checkout_shipping_form','med_shipping_form_title',1);
function med_shipping_form_title(){ ?>
	<h3><?php _e( 'Shipping details', 'woocommerce' ); ?></h3>
<?php }
add_action( 'woocommerce_checkout_after_customer_details','payment_wrap_open',5);
function payment_wrap_open(){
	echo '<div class="payment_wrapper">';
}
add_action( 'woocommerce_checkout_after_customer_details','payment_wrap_close',15);
function payment_wrap_close(){
	echo '</div>';
}
/**
 * HOTFIX: Фильтр отключен, поскольку ломает работу плагина GTM4WP
 * ВАЖНО! Не включайте этот код БЕЗ КОНСУЛЬТАЦИИ С ИВАНОМ и проверкой секаута на сайте!!!
 *
add_filter( 'woocommerce_cart_item_product', 'med_woocommerce_checkout_item_product',1,3 ); 
function med_woocommerce_checkout_item_product($cart_item_data,  $cart_item,  $cart_item_key )
{
	if (is_page('checkout')){
		return '';
	} else {
		return $cart_item_data;
	}
}
*/
add_action ('woocommerce_review_order_before_shipping','med_woocommerce_review_order_before_shipping');
function med_woocommerce_review_order_before_shipping(){
?>
<script type='text/javascript'>
		function my_function(result){
			document.getElementById('address').innerHTML='<h3><?php echo  __( 'Shipping address', 'woocommerce' ); ?></h3>' + result['name'] + ', <br />' +  result['address'];
			document.getElementById('shipping_address_1').value = result['name'] + ', <br />' + result['address'];
			/*shipping_additional['shipping_add_field1'] = result['name'] + '<br />' + result['address'];*/
			document.getElementById('shipping_address_2').value = '-';
			document.getElementById('shipping_building').value = '';
			document.getElementById('shipping_flat').value = '';
		}
		boxberry.displaySettings({top:10});
		function bb_pvz_function(result) {
			console.log(result);
			document.getElementById('address').innerHTML='<h3><?php echo  __( 'Shipping address', 'woocommerce' ); ?></h3>' + 'ПВЗ '+result['id']+' Boxberry' + ', <br />' +  result['address'];
			document.getElementById('shipping_address_1').value = 'ПВЗ '+result['id']+' Boxberry' + ', <br />' + result['address'];
			/*shipping_additional['shipping_add_field1'] = result['name'] + '<br />' + result['address'];*/
			document.getElementById('shipping_address_2').value = '-';
			document.getElementById('shipping_building').value = '';
			document.getElementById('shipping_flat').value = '';
		}
		jQuery(function() {
			jQuery( '#shipping_add_field1, #shipping_add_field2, #shipping_add_field3, #shipping_add_field4' ).blur( function() {
				shipping_additional[this.id] = this.value;
			});
			jQuery( document.body ).ajaxComplete(function() {
				jQuery(".input-checkbox").styler();
				jQuery('#shipping_add_field1').val(shipping_additional['shipping_add_field1']);
				jQuery('#shipping_add_field2').val(shipping_additional['shipping_add_field2']);
				jQuery('#shipping_add_field3').val(shipping_additional['shipping_add_field3']);
				jQuery('#shipping_add_field4').val(shipping_additional['shipping_add_field4']);
			});
		});

</script>			

<?php }

add_filter( 'woocommerce_enable_order_notes_field','med_disable_order_notes_block');
function med_disable_order_notes_block($par){
	return false;
}

function med_wc_terms( $terms_is_checked ) {
	return true;
}
add_filter( 'woocommerce_terms_is_checked_default', 'med_wc_terms', 10 );
/*add_filter( 'woocommerce_create_account_default_checked','med_wc_terms', 10 );*/

add_action('woocommerce_checkout_process', 'med_custom_checkout_fields_process');

function med_custom_checkout_fields_process() {
    // Проверяем, заполнено ли поле, если же нет, добавляем ошибку.
    if ( (isset($_POST['shipping_add_field1']) && ! $_POST['shipping_add_field1']) || (isset($_POST['shipping_add_field2']) && ! $_POST['shipping_add_field2'] ))
       wc_add_notice( __( 'Пожалуйста, введите адрес доставки!' ), 'error' );
	if ( isset($_POST['shipping_add_field1'])) {
		$_POST['shipping_address_1'] = $_POST['shipping_add_field1'];
	}
	if ( isset($_POST['shipping_add_field2'])) {
		$_POST['shipping_address_2'] = $_POST['shipping_add_field2'];
	}
	if (isset($_POST['shipping_add_field3'])) {
		$_POST['shipping_building'] = $_POST['shipping_add_field3'];
	}
	if (isset($_POST['shipping_add_field4'])) {
		$_POST['shipping_flat'] = $_POST['shipping_add_field4'];
	}
	if (isset($_POST['shipping_add_city_field'])) {
		$_POST['shipping_city'] = $_POST['shipping_add_city_field'];
	}
	
}

// Очистка номер телефона от всех символов кроме цифр и плюса при оформлении заказа
add_action( 'woocommerce_checkout_posted_data', 'med_checkout_posted_data_filter_callback' );
function  med_checkout_posted_data_filter_callback( $data ) {
    $field_key = 'billing_phone';

    if( isset($data[$field_key]) ) {
        // Filtering billing phone (removing everything else than numbers)
        $data[$field_key] = preg_replace( '/[^0-9+]/', '', $data[$field_key] );
        $data[$field_key] = str_replace("+8","+7", $data[$field_key]);
    }
    return $data;
}

// Очистка номер телефона от всех символов кроме цифр и плюса при редактировании в личном кабинете
add_action( 'woocommerce_process_myaccount_field_billing_phone', 'med_filter_my_account_billing_phone_fields' );
function med_filter_my_account_billing_phone_fields( $value ) {
	$result = preg_replace( '/[^0-9+]/', '', $value );
	$result = str_replace("+8","+7", $result);
    return $result;
}

// Очистка номер телефона от всех символов кроме цифр и плюса при редактировании профиля в админке
add_action( 'personal_options_update', 'med_save_user_billing_phone_fields', 999999 );
add_action( 'edit_user_profile_update', 'med_save_user_billing_phone_fields', 999999 );
function med_save_user_billing_phone_fields( $user_id ) {
    $field_key = 'billing_phone';

    if( isset($_POST[$field_key]) && ! empty($_POST[$field_key]) ) {
        // Filtering billing phone (removing everything else than numbers)
        $billing_phone = preg_replace( '/[^0-9]/', '', sanitize_text_field($_POST[$field_key]) );
        $billing_phone = str_replace("+8","+7", $billing_phone);

        // Update the billing phone
        update_user_meta( $user_id, 'billing_phone', $billing_phone );
    }
}

// Сохраняем метаданные заказа со значением поля
add_action( 'woocommerce_checkout_update_order_meta', 'med_shipping_update_order_meta' );

function med_shipping_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['shipping_building'] ) ) {
        update_post_meta( $order_id, 'shipping_building', sanitize_text_field( $_POST['shipping_building'] ) );
    }
	if ( ! empty( $_POST['shipping_flat'] ) ) {
        update_post_meta( $order_id, 'shipping_flat', sanitize_text_field( $_POST['shipping_flat'] ) );
    }
}

add_action( 'woocommerce_save_account_details','med_shipping_update_order_meta' );
function med_override_address_fields( $fields ) {
     unset($fields['billing']['billing_city']);
	 unset($fields['billing']['billing_building']);
	 unset($fields['billing']['billing_flat']);
	 $fields['shipping']['shipping_state']['placeholder'] = 'Выберите…';
     return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'med_override_address_fields' );

/*Payment methods conditional display*/
function alter_shipping_methods($available_gateways){
global $woocommerce;

if (null == (WC()->cart)){
	return;
}
$needs_shipping = WC()->cart->needs_shipping();
if (!$needs_shipping) {
	unset($available_gateways['bacs']);
	return $available_gateways;
}
$chosen_titles = array();
$available_methods = WC()->shipping->get_packages();
$chosen_rates = ( isset( WC()->session ) ) ? WC()->session->get( 'chosen_shipping_methods' ) : array();
$chosen_method_cost = 0;
foreach ($available_methods as $method) {
	foreach ($chosen_rates as $chosen) { 
		if( isset( $method['rates'][$chosen] ) ) {
			$chosen_method_cost = $method['rates'][ $chosen ]->cost;
			$chosen_titles[] = $method['rates'][ $chosen ]->label;
		}
		
	}
}
foreach ($chosen_titles as $key=>$chosen_title) {
	
	if (mb_stripos($chosen_title,'пункт')!==false || mb_stripos($chosen_title,'выдач')!==false || mb_stripos($chosen_title,'ПВЗ')!==false || mb_stripos($chosen_title,'курьер')!==false || mb_stripos($chosen_title,'гипермаркет')!==false || mb_stripos($chosen_title,'магаз')!==false)  {
		unset($available_gateways['bacs']);
	}
	$delivery_to_shop = (mb_stripos($chosen_title,'магазин')!==false || mb_stripos($chosen_title,'гипермаркет')!==false) && (mb_stripos($chosen_title,'Фрунзенск')!==false || mb_stripos($chosen_title,'Новокузнецк')!==false || mb_stripos($chosen_title,'Савёлов')!==false);
	if ($delivery_to_shop) {
			unset($available_gateways['cp']);
	}
	if ((mb_stripos($chosen_title,'Боксберри'))!==false || (mb_stripos($chosen_title,'Беларус'))!==false || (mb_stripos($chosen_title,'Армения'))!==false || (mb_stripos($chosen_title,'Киргизия'))!==false || (mb_stripos($chosen_title,'Казахстан'))!==false ) {
		unset($available_gateways['cod']);
	}
}
/*if( (WC()->cart->subtotal < WC()->cart->shipping_total) || (WC()->cart->total>=7000)) {*/
/*if( (WC()->cart->get_subtotal() < WC()->cart->get_shipping_total()) ) {*/ //Если стоимость корзины меньше стоимости доставки, то только выводить только онлайн-оплату
if( WC()->cart->get_subtotal() < 1201 && !$delivery_to_shop ) {
	unset($available_gateways['cod']);
}

$applied_coupons = WC()->cart->get_applied_coupons();
foreach ($applied_coupons as $coupon_code ) {
	$coupons1 = new WC_Coupon( $coupon_code );
	/*strpos($coupons1->get_code(),'rp_wcd') === 0   Условие временно отключено*/
	//echo WC()->cart->get_subtotal();
        if (/*$coupons1->is_type('fixed_cart')*/$coupons1->get_amount()>=WC()->cart->get_subtotal() /*|| $chosen_method_cost == 0*/) {
				unset($available_gateways['cp']);
				break;
        }
}

return $available_gateways;
}
add_action('woocommerce_available_payment_gateways', 'alter_shipping_methods');

//Добавление QR-кода для онлайн-оплаты заказа при переходе из ЛК
add_action( 'woocommerce_pay_order_after_submit', 'med_output_payment_qr_code',5 );
function med_output_payment_qr_code($order) {
	echo do_shortcode('[kaya_qrcode content="'.$order->get_checkout_payment_url(true).'"]');
}

/*My account*/
function iconic_account_menu_items( $items ) {

$items['information'] = __( 'Information', 'iconic' );
 $items['information2'] = __( 'Information2', 'iconic' );
 return $items;

}

add_filter( 'woocommerce_shipping_fields', 'med_woocommerce_shipping_fields_class_clear' );
function med_woocommerce_shipping_fields_class_clear( $fields ) {
    $fields['shipping_address_2']['label_class']=str_replace('screen-reader-text','',$fields['shipping_address_2']['label_class']);
    return $fields;
}

add_filter( 'woocommerce_account_menu_items', 'iconic_account_menu_items', 10, 1 );
remove_filter( 'the_title', 'wc_page_endpoint_title' );
add_filter( 'woocommerce_account_menu_items','med_account_menu_items_order');
function med_account_menu_items_order( $items){
	$items = array(
		'edit-address'    => __( 'Addresses', 'woocommerce' ),
		'edit-account'    => __( 'Account details', 'woocommerce' ),		
		'orders'          => __( 'Orders', 'woocommerce' ),
		/*'subscription'    => __( 'Мои подписки', 'woocommerce' ),	*/
	);
	return $items;
}

add_filter( 'woocommerce_address_to_edit', 'med_address_to_edit',10,2  );
function med_address_to_edit($address, $load_address){
	unset($address['billing_city']);
	unset($address['billing_building']);
	unset($address['billing_flat']);
	return $address;
}
remove_action( 'woocommerce_order_details_after_customer_details', 'wooccm_custom_checkout_details' );

function wc_cart_totals_regular_html(){
	$total_regular=0;
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		if ($cart_item['variation_id']) { 
		$product_id = $cart_item['variation_id']; 
		} else { 
		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		}
		$product = wc_get_product( $product_id );
		$regular_price = $product->get_regular_price() * $cart_item['quantity'];
		$total_regular = $total_regular + $regular_price;
	}
	echo wc_price($total_regular);
}

add_filter( 'woocommerce_my_account_my_orders_actions', 'med_remove_view_order_in_myaccount', 10,2 );

function med_remove_view_order_in_myaccount($actions, $order){
	unset($actions['view']);
	return $actions;
}

function wc_checkout_discount_amount_html(){
	$total_regular=0;
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		if ($cart_item['variation_id']) { 
		$product_id = $cart_item['variation_id']; 
		} else { 
		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		}
		$product = wc_get_product( $product_id );
		$regular_price = $product->get_regular_price() * $cart_item['quantity'];
		$total_regular = $total_regular + $regular_price;
	}	
	$total_discount_sum = $total_regular - WC()->cart->total;
	if (WC()->cart->shipping_total > 0) {
		$total_discount_sum = $total_discount_sum + WC()->cart->shipping_total;
	}
	echo '- '.wc_price($total_discount_sum);
}
if (class_exists('WooCommerce_Advanced_Shipping')) {
add_action ('was_after_meta_box_settings','med_shipping_method_additional_fields');
function med_shipping_method_additional_fields($settings){ ?>
	<p class='was-option'>
		<label for='shipping_comment'><?php _e( 'Комментарий', 'woocommerce-advanced-shipping' ); ?></label>
		<input
			type='text'
			class=''
			id='shipping_comment'
			name='_was_shipping_method[shipping_comment]'
			style='width: 400px;'
			value='<?php echo esc_attr( @$settings['shipping_comment'] ); ?>' placeholder=''>
	</p>
	<p class='was-option'>
		<label for='pickpoint_zone'><?php _e( 'Зона доставки Pickpoint', 'woocommerce-advanced-shipping' ); ?></label>
		<input
			type='text'
			class=''
			id='pickpoint_zone'
			name='_was_shipping_method[pickpoint_zone]'
			style='width: 50px;'
			value='<?php echo esc_attr( @$settings['pickpoint_zone'] ); ?>' placeholder=''>
	</p>
	<p class='was-option'>
		<label for='boxberry_zone'><?php _e( 'Зона доставки Boxberry', 'woocommerce-advanced-shipping' ); ?></label>
		<input
			type='text'
			class=''
			id='boxberry_zone'
			name='_was_shipping_method[boxberry_zone]'
			style='width: 50px;'
			value='<?php echo esc_attr( @$settings['boxberry_zone'] ); ?>' placeholder=''>
	</p>	
<?php }
}
add_action( 'woocommerce_login_form_end','med_social_login' );
add_action( 'woocommerce_register_form_end','med_social_login' ); 
function med_social_login(){
	echo get_ulogin_panel();
}
if (function_exists('wooccm_add_payment_method_to_new_order')){
	remove_action( 'woocommerce_email_after_order_table', 'wooccm_add_payment_method_to_new_order', 10, 3 );
}

remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
add_filter( 'woocommerce_thankyou_order_received_text','med_thankyou_order_received_text',10,2);
function med_thankyou_order_received_text($text, $order){
	$text = '<b>Ваш заказ <span class="c_3e9a8a">&#8470;'.$order->get_order_number().'</span> принят в работу, в ближайшее время мы свяжемся с Вами.</b><br />';
	$text .= 'Пожалуйста, обратите внимание на часы работы нашего магазина.';
	return $text;
}
add_action( 'woocommerce_thankyou','med_low_thankyou_text',20);
function med_low_thankyou_text(){ ?>
	<div class="woocommerce-thankyou-low-text">
		<img class="img_thanks" src="<?php echo get_stylesheet_directory_uri(); ?>/img/thanks.png">
		<div>Мы рады, что познакомились с Вами, и Вам больше не придется тратить время на то, чтобы нас найти.</div>
	</div>
<?php }
// Add Link (Tab) to My Account menu
add_filter ( 'woocommerce_account_menu_items', 'med_log_history_link', 40 );
function med_log_history_link( $menu_links ){
 
    $menu_links = array_slice( $menu_links, 0, 4, true ) 
    + array( 'mailing' => __( 'Рассылки','fleetservice' ))
    + array_slice( $menu_links, 4, NULL, true );
 
    return $menu_links;
 
}
// Register Permalink Endpoint
add_action( 'init', 'med_add_endpoint' );
function med_add_endpoint() {
 
    // WP_Rewrite is my Achilles' heel, so please do not ask me for detailed explanation
    add_rewrite_endpoint( 'mailing', EP_PAGES );
 
}
// woocommerce_account_{ENDPOINT NAME}_endpoint
//add_action( 'woocommerce_account_mailing_endpoint', 'med_my_account_endpoint_content' );
function med_my_account_endpoint_content() { ?>
 
    <p><?php _e('Здесь Вы можете управлять подпиской на рассылки.','medknigaservis'); ?></p>
    <div class="mailpoet_subscription">
    <?php echo do_shortcode( '[mailpoet_manage]'); ?>
    </div>
 
<?php }


/*Партнерская программа*/
//if (function_exists('WooCommerceProcessTransaction')){
	remove_action('woocommerce_order_status_processing', 'WooCommerceProcessTransaction');
	remove_action('woocommerce_checkout_order_processed', 'WooCommerceProcessTransaction');
	
//}
add_filter('woocommerce_login_redirect', 'wc_login_redirect');

function wc_login_redirect( $redirect_to ) {
	//$user = wp_get_current_user();
	$username = trim( $_POST['username'] );
	if ( is_email( $username ) && apply_filters( 'woocommerce_get_username_from_email', true ) ) {
					$user = get_user_by( 'email', $username );

					if ( ! $user ) {
						$user = get_user_by( 'login', $username );
					}
	} else {
		$user = get_user_by( 'login', $username );
	}
	$roles = $user->roles;
	foreach ($roles as $role_name) {
		if ( $role_name==='affiliate') {
			$redirect_to = site_url().'/affiliate-home/';
			return $redirect_to;
		}
	}
     
    return $redirect_to;
}

/**
 * WooCommerce 3.3 - Hide uncategorized category from the shop page on the frontend and Remove Categories from WooCommerce Product Category Widget
 */
add_filter( 'woocommerce_product_subcategories_args', 'med_remove_uncategorized_category' );
add_filter( 'woocommerce_product_categories_widget_args', 'med_remove_uncategorized_category' );
add_filter( 'woocommerce_product_categories_widget_dropdown_args', 'med_remove_uncategorized_category' );
add_filter( 'ywcca_wc_product_categories_widget_args', 'med_remove_uncategorized_category' );

function med_remove_uncategorized_category( $args ) {
  $args['exclude'] = get_option( 'default_product_cat' );
  return $args;
}
//обрезание описания рубрик и меток в админке сайта start
function wph_trim_cats() {
    add_filter('get_terms', 'wph_truncate_cats_description', 10, 2);
}
function wph_truncate_cats_description($terms, $taxonomies) {
    if('product_cat' != $taxonomies[0])
        return $terms;
    foreach($terms as $key=>$term) {
        $terms[$key]->description = mb_substr($term->description, 0, 80);
        if($terms[$key]->description != '') {
            $terms[$key]->description .= '...';
        }
    }
    return $terms;
}
add_action('admin_head-edit-tags.php', 'wph_trim_cats');

/*****************************************************************/
/* Добавление полей с shipping_method и _wpam_id в таблицу заказов в админке */
/*****************************************************************/
add_filter( 'manage_edit-shop_order_columns', 'custom_woo_columns_function' );
function custom_woo_columns_function( $columns ) {
    $new_columns = ( is_array( $columns ) ) ? $columns : array();
    unset( $new_columns[ 'wc_actions' ] );

    // all of your columns will be added before the actions column
    $new_columns['shipping_method'] = 'Способ доставки';
	$new_columns['order_coupons'] = 'Скидки на заказ';
	$new_columns['wpam_id'] = 'id партнера';

    //stop editing
    $new_columns[ 'wc_actions' ] = $columns[ 'wc_actions' ];


    return $new_columns;
}

// Change order of columns ==> changed (working)
add_action( 'manage_shop_order_posts_custom_column', 'custom_woo_admin_value', 2 );
function custom_woo_admin_value( $column ) {
    global $post, $the_order;

    if ( empty( $the_order ) || $the_order->get_id() != $post->ID ) {
        $the_order = wc_get_order( $post->ID );
    }

    if ( $column == 'shipping_method' ) {
        $shipping_method = $the_order->get_shipping_method();
        echo empty($shipping_method) ? '' : $shipping_method;
    }
	if ( $column == 'wpam_id' ) {
        $wpam_id = get_post_meta($the_order->get_id(), '_wpam_id', true);
        echo empty($wpam_id) ? '' : $wpam_id;
    }
	if ( $column == 'order_coupons' ) {
		$used_coupons = $the_order->get_used_coupons();
		if ($used_coupons) {
			$coupons_count = count($used_coupons);
			$i = 1;//?
		    $coupons_list = '';
		    if ($coupons_count) {
		    	$coupons_list .= "<ul class='wc_coupon_list'>";
				foreach( $used_coupons as $coupon) {
					$coupon_title = apply_filters('woocommerce_order_item_get_code', $coupon);
					//$coupons_list .= wc_cart_totals_coupon_label( $coupon );
					$coupons_list .= "<li class='code1'><span class='tips1'><span>".$coupon_title.'</span></span></li>';
				}		    	
		    	$coupons_list .= "</ul>";
		    }
			/*foreach( $used_coupons as $coupon) {
				$coupon_title = apply_filters('woocommerce_order_item_get_code', $coupon);
				//$coupons_list .= wc_cart_totals_coupon_label( $coupon );
				$coupons_list .= $coupon_title;
				if( $i < $coupons_count ) {
					$coupons_list .= ', ';
					$i++;
				}
			}*/
	        echo $coupons_list;
    	}
    }    
}

add_action( 'woocommerce_after_checkout_validation','med_pickpoint_selection_checkout',10,2  );
function med_pickpoint_selection_checkout($data, $errors) {
	$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
	foreach ( WC()->shipping->get_packages() as $i => $package ) {
		if ( isset( $chosen_shipping_methods[ $i ], $package['rates'][ $chosen_shipping_methods[ $i ] ] ) ) {
			$method = $package['rates'][ $chosen_shipping_methods[ $i ]];
			$is_PVZ = mb_strpos ($method->label, 'Пункты выдачи заказов', 0, 'UTF-8');
			if (($is_PVZ !== false) && !strpos($data['shipping_address_1'],'PickPoint') && mb_strpos($data['shipping_address_1'],'Постамат',0,'UTF-8')===false && mb_strpos($data['shipping_address_1'],'ПВЗ',0,'UTF-8')===false) {
				$errors->add( 'shipping', 'Выберите пункт выдачи заказов на карте PickPoint нажав на кнопку "Выбрать".' );
			}
		}
	}	
}
//add_action( 'woocommerce_cart_coupon', 'med_custom_sort_coupons' );
function med_custom_sort_coupons() {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    $applied_coupons = WC()->cart->get_applied_coupons();
    WC()->cart->remove_coupons();

    ksort($applied_coupons);

    foreach ( $applied_coupons as $coupon_code ) {
        WC()->cart->add_discount( $coupon_code );
        wc_clear_notices(); // Avoid repetitive notices
    }
    WC()->cart->calculate_totals();
}


//add_action( 'woocommerce_after_single_product_summary', 'single_product_popular_retail_rocket', 19);
function single_product_popular_retail_rocket() {
    global $post;
    
    $term_ids = wp_get_post_terms( $post->ID, 'product_cat', array('fields' => 'ids', 'parent' => '0') );
	
	/**
	 * Есkи категорию прочитать не удалось подставляем Книги для пациентов.
	 * Да, криво, знаю. Но зато быстро, дешево и сердито!
	 */
	if ( empty( $term_ids[0] ) )
		$term_ids[0] = 35;

    echo '<div data-retailrocket-markup-block="5cadca6c97a52513dcf67acd" data-category-id="' . $term_ids[0] . '"></div>';
}


//add_action( 'woocommerce_archive_description', 'search_product_retail_rocket', 19);
function search_product_retail_rocket() {
    if ( is_search() ) {
        echo '<div data-retailrocket-markup-block="5cadca9097a528100c078bbf" data-search-phrase="' . get_search_query() . '"></div>';
    }
}

/*
 * Change card in archive products
 */

remove_action( 'woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart', 10, 0 );
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10, 0);
add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_start_product_footer', 11);
add_action( 'woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart', 13 );
add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 12);
add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_end_product_footer', 15);

function woocommerce_template_loop_start_product_footer() {
    echo '<div class="product_footer">';
}

function woocommerce_template_loop_end_product_footer() {
    echo '</div>';
}
/*Добавляем кастомный статус к числу тех, при которых заказ считается оплаченным*/
add_filter('woocommerce_order_is_paid_statuses', 'med_order_is_paid_statuses');
function med_order_is_paid_statuses($statuses_array){
	$statuses_array[] = 'sended';
	return $statuses_array;
}
/*****************************************************************************************
 * Замена стандартного вывода похожих товаров на кастомный, при котором выводятся самые продаваемые товары из каждой товарной категории данного товара
 ****************************************************************************************/
/*Очистка транзиентного кэша похожих товаров для удобства отладки*/
add_action( 'woocommerce_before_single_product_summary', 'med_delete_wc_related' );
function med_delete_wc_related(){
	global $product;
	delete_transient('wc_related_'.$product->get_id());
}

/*Замена стандартной функции вывода похожих товаров для реализации требований заказчика: выбирать из каждой 
товарной категории низшего уровня данного товара равное количество самых продаваемых товаров*/
function woocommerce_related_products( $args = array() ) {
		global $product;

		if ( ! $product ) {
			return;
		}

		$defaults = array(
			'posts_per_page' => 8,
			'columns'        => 4,
			'orderby'        => 'none',
			'order'          => 'asc',
		);

		$args = wp_parse_args( $args, $defaults );

		// Get visible related products then sort them at random.
		$args['related_products'] = array_filter( array_map( 'wc_get_product', med_get_related_products( $product->get_id(), $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );


		// Handle orderby.
		$args['related_products'] = wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );

		// Set global loop values.
		wc_set_loop_prop( 'name', 'related' );
		wc_set_loop_prop( 'columns', apply_filters( 'woocommerce_related_products_columns', $args['columns'] ) );

		wc_get_template( 'single-product/related.php', $args );
}

/*Замена стандартной функции выборки похожих товаров для реализации требований заказчика: выбирать из каждой 
товарной категории низшего уровня данного товара равное количество самых продаваемых товаров*/
function med_get_related_products( $product_id, $limit = 5, $exclude_ids = array() ) {
	$limit = 8;
	$product_id     = absint( $product_id );
	$limit          = $limit >= -1 ? $limit : 5;
	$exclude_ids    = array_merge( array( 0, $product_id ), $exclude_ids );
	$transient_name = 'wc_related_' . $product_id;
	$query_args     = http_build_query(
		array(
			'limit'       => $limit,
			'exclude_ids' => $exclude_ids,
		)
	);


	$init_exclude_ids = $exclude_ids;

	$transient     = get_transient( $transient_name );
	$related_posts = $transient && isset( $transient[ $query_args ] ) ? $transient[ $query_args ] : false;

	//Товарные категории, из которых не нужно выбирать товары для блока Похожих товаров
	$excluded_cats_ids = array(597,300,1868,11,154,368);
	//$excluded_cats_ids = array();

	// We want to query related posts if they are not cached, or we don't have enough.
	if ( false === $related_posts || count( $related_posts ) < $limit ) {
		$cats_array = apply_filters( 'woocommerce_product_related_posts_relate_by_category', true, $product_id ) ? apply_filters( 'woocommerce_get_related_product_cat_terms', wc_get_product_term_ids( $product_id, 'product_cat' ), $product_id ) : array();
		$tags_array = apply_filters( 'woocommerce_product_related_posts_relate_by_tag', true, $product_id ) ? apply_filters( 'woocommerce_get_related_product_tag_terms', wc_get_product_term_ids( $product_id, 'product_tag' ), $product_id ) : array();

		foreach ($cats_array as $key=>$cur_product_cat) { //Удаляем категории "Новинки", "Uncatogorized", "Распродажа"
			if (in_array($cur_product_cat,$excluded_cats_ids)) {
				unset($cats_array[$key]);
			}
		}
		// Don't bother if none are set, unless woocommerce_product_related_posts_force_display is set to true in which case all products are related.
		if ( empty( $cats_array ) && empty( $tags_array ) && ! apply_filters( 'woocommerce_product_related_posts_force_display', false, $product_id ) ) {
			$related_posts = array();
		} else {
			$related_posts =  array();
			$get_limit = intval($limit/count($cats_array)); 
			$i = 1;
			foreach ($cats_array as $cur_product_cat) { //Выбираем из каждой категории по n самых продаваемых товаров
				//echo $cur_product_cat.': ';
				$data_store    = WC_Data_Store::load( 'product' );
				if ($i == count($cats_array) && ((8-$i*$get_limit)>0)) { //если это последняя итерация
					$get_limit = $get_limit + 8-($i*$get_limit);
				}

				$args1 =array (
					'numberposts' => $get_limit,
					'category'    => 0,
					'include'     => array(),
					'exclude'     => $exclude_ids,
					'post_type'   => 'product',
					'post_status' => 'publish',
					'suppress_filters' => true,
					'tax_query' => array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'id',
							'terms'    => $cur_product_cat
							),
					),
					'meta_query' => array(
						'relation' => 'AND',
						'total_sales_key' => array(
							'key'     => 'total_sales',
							'value'   => 0,
							'type' => 'numeric',
							'compare' => '>=', // по умолчанию '='
						),
						array(
							'key'     => '_stock_status',
							'value'   => 'instock',
							'compare' => '=',
						),
					),
					'orderby' => 'total_sales_key',
				);
				$related_products_full = get_posts($args1);

				if ($related_products_full) {
					$related_products = array();
					foreach( $related_products_full as $related_product_full ){
						$related_products[] = $related_product_full->ID;
					}	
				}

				if ($related_products) {
					//echo implode(',', $related_products).'<br>';
				}
				$related_posts = array_merge($related_posts,$related_products);
				$exclude_ids = array_merge($exclude_ids,$related_products);
				$i++;
			}
		}

		if ( $transient ) {
			$transient[ $query_args ] = $related_posts;
		} else {
			$transient = array( $query_args => $related_posts );
		}

		set_transient( $transient_name, $transient, DAY_IN_SECONDS );
	}

	$exclude_ids = $init_exclude_ids ;
	$related_posts = apply_filters(
		'woocommerce_related_products',
		$related_posts,
		$product_id,
		array(
			'limit'        => $limit,
			'excluded_ids' => $exclude_ids,
		)
	);

	/*if ( apply_filters( 'woocommerce_product_related_posts_shuffle', true ) ) {
		shuffle( $related_posts );
	}*/

	return array_slice( $related_posts, 0, $limit );
}

add_filter( 'woocommerce_get_related_product_cat_terms','med_related_posts_relate_by_category',1,2);
function med_related_posts_relate_by_category($terms_array,$product_id ) {
	//Оставляем в категориях термины только самого низкого уровня
	foreach ($terms_array as $key=>$cur_term) {
		$child_terms = get_term_children( (int)$cur_term, 'product_cat' );
		//echo $key.'=>'.$cur_term.'<br>';
		if (!empty($child_terms)) {
			unset($terms_array[$key]);
		}
	}
	return $terms_array;
}

add_filter( 'woocommerce_product_related_posts_relate_by_tag','med_related_posts_relate_by_tag',1,2);
function med_related_posts_relate_by_tag($flag,$product_id) {
	//Исключаем теги товара из поиска похожих товаров
	return false;
}

/******************************* 
 * Конец альтернативного вывода похожих товаров 
 * *******************************/ 

/*После импорта вариативного товара проверяем, есть ли у него атрибуты. Если нет, преобразуем в простой товар.*/
add_action( 'pmxi_saved_post', 'wpai_wp_all_import_variable_product_imported', 10, 3 );

function wpai_wp_all_import_variable_product_imported( $post_id, $xml_node, $is_update ) {
    $post_product = wc_get_product( $post_id ); 
    $attributes = $post_product->get_attributes();
   	$product_types = wp_get_object_terms( $post_id, 'product_type');

    if (count($attributes)==0){
    	if ($post_product->is_type('variation')) {
    		$parent_id = $post_product->get_parent_id();

    		//Меняем тип
	    	$result = wp_remove_object_terms( $parent_id, 'variable', 'product_type');
	    	wp_set_object_terms( $parent_id, 'simple', 'product_type', true );
			$logger = function($m) {printf("[%s] $m", date("H:i:s"));flush();};
			call_user_func($logger, 'Convert to simple');
			$sku = $post_product->get_sku();
			if (substr($sku,-2) == '-P') {
				$sku = mb_substr($post_product->get_sku(),0,-2);
			}		
			$parent_product = new WC_Product( $parent_id );
	        $parent_product->set_sku( $sku );
	        $parent_product->set_regular_price($parent_product->get_price());	        
	        $parent_product->save();  
	        wp_delete_post($post_id, true);	   
    	}
    }

}

function allow_payment_without_login( $allcaps, $caps, $args ) {
    // Check we are looking at the WooCommerce Pay For Order Page
    if ( !isset( $caps[0] ) || $caps[0] != 'pay_for_order' )
        return $allcaps;
    // Check that a Key is provided
    if ( !isset( $_GET['key'] ) )
        return $allcaps;

    // Find the Related Order
    $order = wc_get_order( $args[2] );
    if( !$order )
        return $allcaps; # Invalid Order

    // Get the Order Key from the WooCommerce Order
    $order_key = $order->get_order_key();
    // Get the Order Key from the URL Query String
    $order_key_check = $_GET['key'];

    // Set the Permission to TRUE if the Order Keys Match
    $allcaps['pay_for_order'] = ( $order_key == $order_key_check );

    return $allcaps;
}
add_filter( 'user_has_cap', 'allow_payment_without_login', 10, 3 );