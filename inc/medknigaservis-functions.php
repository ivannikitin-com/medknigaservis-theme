<?php
function phone_clean($phone){
	$phone= str_replace([' ', '(', ')', '-'], '', $phone);
	return $phone;
}
/*add_action( 'wpex_hook_primary_after','med_partners_block',10 );
function med_partners_block() {
	$partners_content=get_field('partners_content');
	if ($partners_content) {
		echo $partners_content;
	}
}*/
/*Обрезаем utf8-строку до заданного количества символов */
function str_cut($instr,$number){
	$instrlength=(mb_strlen($instr,'UTF-8'));
	if ($instrlength>$number) {
		$outstr=trim(mb_substr($instr,0,$number-1,'UTF-8')).'…';
	} else {
		$outstr=mb_substr($instr,0,$number-1,'UTF-8');
	}
	return $outstr;
}
remove_action( 'wpex_hook_primary_after', 'wpex_get_sidebar_template' );
add_action( 'wpex_hook_primary_before', 'wpex_get_sidebar_template' );
function med_get_sidebar_template() {
	//echo '!!!';
	if ( ! in_array( wpex_content_area_layout(), array( 'full-screen', 'full-width' ) ) ) {
		get_sidebar( 'second' );
	}
}
add_action( 'wpex_hook_primary_after','med_get_sidebar_template',5 );
remove_action( 'wpex_hook_main_top', 'wpex_page_header' );
remove_action( 'wpex_hook_page_header_inner', 'wpex_page_header_title' );
remove_action( 'wpex_hook_page_header_inner', 'wpex_display_breadcrumbs' );
add_filter( 'gettext', 'theme_change_comment_field_names', 20, 3 );
function theme_change_comment_field_names( $translated_text, $text, $domain ) {
	if ( is_product_category()|| is_tax()) {
		switch ( $text ) {
		       case 'Sale':
				$translated_text = 'Выгодно!';
                break;
		       /*case 'View cart':
				$translated_text = 'В Вашей корзине';
                break;*/					
		}
	}	
    if ( is_product() ) {

        switch ( $text ) {
            case 'You must be <a href="%s">logged in</a> to post a review.':
				$translated_text = 'Для публикации отзыва <a href="%s">войдите</a> на сайт, используя E-mail или социальные сети.';
                break;
            case 'Be the first to review &ldquo;%s&rdquo;':
				$translated_text = 'Помогите коллегам в поисках полезной информации! Оцените книгу, пожалуйста!';
                break;
            case 'Your review':
				$translated_text = 'Ваш комментарий';
                break;	
            case 'Submit':
				$translated_text = 'Оценить';
                break;					
            case 'Sale':
				$translated_text = 'Выгодно!';
                break;				
			case '%s customer review' :
				$translated_text = str_ireplace('отзыв клиента','комментарий',$translated_text);
                break;
			case '%s customer reviews' :
				$translated_text = str_ireplace('обзоров','отзывов клиентов',$translated_text);
				$translated_text = str_ireplace('обзора','отзыва клиентов',$translated_text);
                break;				
			case '%1$s review for %2$s' :
				$translated_text = str_ireplace('обзор на %2$s','комментарий',$translated_text);
				break;
			case '%1$s reviews for %2$s' :
				$translated_text = str_ireplace('обзоров на %2$s','комментариев',$translated_text);
				$translated_text = str_ireplace('обзора на %2$s','комментария',$translated_text);
				break;				
        }
	}
    if ( is_checkout() ) {

        switch ( $text ) {

            case 'Select an option&hellip;':
				$translated_text = '-';
                break;
				
			case 'I&rsquo;ve read and accept the <a href="%s" target="_blank" class="woocommerce-terms-and-conditions-link">terms &amp; conditions</a>':
				$translated_text = 'Я принимаю <a href="%s" class="woocommerce-terms-and-conditions-link">Условия пользования сайтом</a>';
                break;
			case 'Shipping costs will be calculated once you have provided your address.':
				$translated_text = 'Для отображения доступных способов доставки заполните все обязательные адресные поля.';
                break;	
            case 'There are no shipping methods available. Please double check your address, or contact us if you need any help.':
				$translated_text = 'Для отображения доступных способов доставки заполните все обязательные адресные поля.';
                break;
		}
	}

	if ( is_cart() ) {
		switch ( $text ) {
			case 'Total' :
				$translated_text = 'Подытог';
                break;
			case 'Proceed to checkout' :
				$translated_text = 'Перейти к оформлению';
                break;                
		}
	}

	if (is_page('my-account')) {
		switch ( $text ) {
			case 'Addresses':
				$translated_text = 'Мои данные';
                break;
			case 'Account details':
				$translated_text = 'E-mail/пароль';
                break;
			case 'Orders':
				$translated_text = 'История заказов';
                break;
			case 'Current password (leave blank to leave unchanged)':
				$translated_text = 'Пароль';
                break;
			case 'New password (leave blank to leave unchanged)':
				$translated_text = 'Новый пароль';
                break;
			case 'Confirm new password':
				$translated_text = 'Повторите пароль';
                break;
			case 'Order #%1$s was placed on %2$s and is currently %3$s.' :
				$translated_text = 'Заказ #%1$s был оформлен %2$s. Статус заказа - %3$s.';
                break;				
			case 'Processing' :
				$translated_text = 'В обработке';
                break;				
		}
	}

	switch ($text) {	
	
			case 'Add to cart' :
				$translated_text = 'В корзину';
                break;
			case 'You may be interested in&hellip;' :
				$translated_text = 'Вас также может заинтересовать';
                break;	
			case 'Product' :
				$translated_text = 'Название';
                break;		
			case 'Coupon code' :
				$translated_text = 'Есть сертификат или промо код?';
                break;		
			case 'Apply coupon' :
				$translated_text = 'Применить';
                break;	
			case 'Update cart' :
				$translated_text = 'Обновить';
                break;				
			case 'Subtotal' :
				$translated_text = 'Товаров на сумму';
                break;
			case 'Your order' :
				$translated_text = 'Ваш заказ';
                break;
			case 'Upload Files' :
				$translated_text = 'Загрузить';
                break;
			case 'Billing details' :
				$translated_text = 'Кому доставить';
                break;
			case 'Shipping details' :
				$translated_text = 'Куда доставить';
                break;	
			case 'Addresses' :
				$translated_text = 'Данные покупателя';
                break;				
			case 'The following addresses will be used on the checkout page by default.' :
				$translated_text = 'Следующие данные будут использованы при оформлении заказов по-умолчанию.';
                break;	
			case 'I&rsquo;ve read and accept the <a href="%s" target="_blank">terms &amp; conditions</a>' :
				$translated_text = 'Я подтверждаю, что я старше 18 лет, принимаю условия работы сайта и даю добровольное согласие на обработку своих персональных данных и получение E-mail и SMS-рассылок с информацией об акциях и новых поступлениях Интернет-магазина';
                break;	
			case 'Clear selection' :
				$translated_text = 'Отменить выбор';
                break;
			case 'Billing address' :
				$translated_text = 'Покупатель';
                break;
			case 'Billing address.' :
				$translated_text = 'Данные покупателя.';
                break;
			case 'You may also like&hellip;' :
				$translated_text = 'Так же вам будет интересно';
                break;	
			case 'Select a state&hellip;' :
				$translated_text = 'Выберите&hellip;';
                break;				
			case 'Select an option&hellip;' :
				$translated_text = 'Выберите&hellip;';
                break;	
			case 'Place order' :
				$translated_text = 'Оформить заказ';
                break;	
			case 'Sorry, this page could not be found.' :
				$translated_text = 'К сожалению, страница не найдена.';
                break;		
			case 'I&rsquo;ve read and accept the <a href="%s" class="woocommerce-terms-and-conditions-link">terms &amp; conditions</a>':
				$translated_text = 'Я подтверждаю, что я старше 18 лет, принимаю <a href="%s" class="woocommerce-terms-and-conditions-link">условия работы сайта</a> и даю добровольное согласие на обработку своих персональных данных и получение E-mail / SMS-рассылок с информацией об акциях и новых поступениях Интернет-магазина';
                break;	
		    case 'View cart':
				/*$translated_text = 'В Вашей корзине';*/
                break;	
		    case 'On hold':
				$translated_text = 'Новый';
                break;
		    case 'Mark processing':
				$translated_text = 'Отметить Готов к обработке';
                break;		
		    case 'Mark':
				$translated_text = 'Отметить';
                break;
		    case 'Processing':
				$translated_text = 'Готов к обработке';
                break;	
		    case 'Processing <span class=\"count\">(%s)</span>':
				$translated_text = str_ireplace('Обрабатывается','Новый',$translated_text);			
				$translated_text = str_ireplace('Обрабатываются','Новые',$translated_text);
                break;	
		    /*case 'This is the affiliates section of this store.  If you are an existing affiliate, please <a href="%s">log in</a> to access your control panel.':
				$translated_text = 'Это раздел для партнеров. Если Вы являетесь партнером, пожалуйста, <a href="'.wc_get_page_permalink( 'myaccount' ).'">авторизуйтесь</a> ';
                break;	
		    case 'If you are not an affiliate, but wish to become one, you will need to apply.  To apply, you must be a registered user on this blog.  If you have an existing account on this blog, please <a href="%s">log in</a>.  If not, please <a href="%s">register</a>.':
				$translated_text = 'Если Вы не являетесь партнером, но хотите им стать, то заполните, пожалуйста, <a href="/svyaz/">заявку</a>.';
                break;				*/
	}
	
    return $translated_text;
}

add_filter( 'gettext_with_context', 'whitecat_context_translation', 10, 6 );
function whitecat_context_translation( $translation, $text, $context, $domain ){
		switch ( $text ) {
            case 'On hold':
				if ($context=="Order status") {
					$translation = 'Новый';
					break;
				
				}
            case 'Processing':
				if ($context=="Order status") {
					$translation = 'Готов к обработке';
					break;
				
				}				
			case 'Failed':
				if ($context=="Order status") {
					$translation = 'Неудавшийся';
					break;
				}				
		}

	return $translation;
}

/* Партнёры на Главной */
add_action( 'wpex_hook_primary_after','med_partners_block_2',10 );
function med_partners_block_2() {
	if( have_rows('parthners') ):?>
	<div class="container padd_col hidden-xs clear">
        <div class="parthners match-height-grid clr">
            <div class="row">
			<?php while( have_rows('parthners') ): the_row();
				$image = get_sub_field('image_parthners');
				$title = get_sub_field('title_parthners');
				$descr = get_sub_field('descr_parthners');
				$link = get_sub_field('link');
			?>
			<div class="col span_1_of_3">
				<div class="column-inner">
					<?php if( $link ): ?>
						<a href="<?php echo $link; ?>" class="color_1 match-height-content" target="_blank" rel="nofollow">
					<?php endif; ?>					
					
					<?php if( $image ): ?>
						<img src="<?php echo $image; ?>" class="img-responsive">
					<?php endif; ?>
					
					<?php if( $title ): ?>
						<h2 class="vcex-icon-box-heading"><?php echo $title; ?></h2>
					<?php endif; ?>
					
					<?php if( $descr ): ?>
						<div class="parthner-content clr"><p><?php echo $descr; ?></p></div>
					<?php endif; ?>
					
					<?php if( $link ): ?>
						</a>
					<?php endif; ?>
					
				</div><!--/.column-inner-->
			</div><!--/.col-->
		<?php endwhile; ?>
		
           </div><!--/.row-->
		</div><!--/.parthners-->        
	</div><!--/.container-->       
		
	<?php endif;
	}
// возвращает ID картинки по URL
function get_attachment_id_by_url($attachment_url) {
    global $wpdb;
  
	$attachment_url=basename($attachment_url);
    // таблица постов, там же перечисленны и медиафайлы
    $table  = $wpdb->prefix . 'posts';
    $attachment_id = $wpdb->get_var( 
        $wpdb->prepare( "SELECT ID FROM $table WHERE guid RLIKE %s", $attachment_url ) 
    );
    // Returns id
    return $attachment_id;}	
function sort_nested_arrays( $array, $args = array('votes' => 'desc') ){
	usort( $array, function( $a, $b ) use ( $args ){
		$res = 0;

		$a = (object) $a;
		$b = (object) $b;

		foreach( $args as $k => $v ){
			if( $a->$k == $b->$k ) continue;

			$res = ( $a->$k < $b->$k ) ? -1 : 1;
			if( $v=='desc' ) $res= -$res;
			break;
		}

		return $res;
	} );

	return $array;
}
add_filter("acf/settings/remove_wp_meta_box", function($val) { return false; } );

add_shortcode('retailrocket', 'retailrocket_widget');
function retailrocket_widget($atts){
	
	// DISABLE
	return '';
	
	$atts = shortcode_atts( array(
		'users' => 'logged_in',
		'code' 	=> ''
	), $atts );
	if (!$atts['code']) return '';
	$output = '<div data-retailrocket-markup-block="'.$atts['code'].'" ></div>';
	if ($atts['users'] =='logged_in') {
		if ( !is_user_logged_in() ) $output = '';
	}
	return $output;
}

add_action('template_redirect', 'redirect_single_post');
function redirect_single_post() {
	if (is_search()) {
		global $wp_query;
		if ($wp_query->post_count == 1 && $wp_query->max_num_pages == 1) {
			wp_redirect(get_permalink($wp_query->posts['0']->ID));
			exit;
		}
	}
}