<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$add_address_output=0;
if ( 1 < count( $available_methods ) ) :
	foreach ( $available_methods as $key => $method ) : 
		if (mb_stripos($method->label,'почт') !== false) {
			unset($available_methods[$key]);
		}
	endforeach;
endif;
?>

<tr class="shipping">
	<td data-title="<?php echo esc_attr( $package_name ); ?>">
		<?php if ( 1 < count( $available_methods ) ) : ?>
			<ul id="shipping_method">
				<?php foreach ( $available_methods as $method ) : ?>
					<li <?php echo (checked( $method->id, $chosen_method, false ))?'class="selected_item"':'';?> >
						<?php printf( '<input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s />
						<label for="shipping_method_%1$d_%2$s">%5$s</label>',
						$index, sanitize_title( $method->id ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ), wc_cart_totals_shipping_method_label( $method ) );
	
						$_was_shipping_method= get_post_meta( $method->id,'_was_shipping_method',true);

						do_action( 'woocommerce_after_shipping_rate', $method, $index );
						/*if ((checked( $method->id, $chosen_method, false ) && (mb_stripos($method->label,'пункт') !== false && mb_stripos($method->label,'выдач') !== false) || (mb_stripos($method->label,'пвз') !== false)) && isset($_was_shipping_method['pickpoint_zone']) && $_was_shipping_method['pickpoint_zone'])  {*/
						if ( isset($_was_shipping_method['pickpoint_zone']) && $_was_shipping_method['pickpoint_zone']!=='' && checked( $method->id, $chosen_method, false ) ) {
							echo '<a href="#" class="button" onclick="PickPoint.open(my_function);return false">Выбрать</a>';
						}
/*						if ( isset($_was_shipping_method['boxberry_zone']) && $_was_shipping_method['boxberry_zone']!=='' && checked( $method->id, $chosen_method, false ) ) {
							echo '<a href="#" class="button" onclick="boxberry.open(my_function);return false">Выбрать</a>';
						}*/						
						$order_weight = WC()->cart->get_cart_contents_weight();
						if ($order_weight<1) {
							$order_weight = 1;
						}						
						if ((checked( $method->id, $chosen_method, false ) && (mb_stripos($method->label,'пункт') !== false && mb_stripos($method->label,'выдач') !== false) || (mb_stripos($method->label,'пвз') !== false)) && isset($_was_shipping_method['boxberry_zone']) && $_was_shipping_method['boxberry_zone']) {
							$shipping_city_4_boxberry = WC()->customer->get_shipping_city();
							if (WC()->customer->get_shipping_state() == 'РФ, Москва' || WC()->customer->get_shipping_state() === 'MS') {
								$shipping_city_4_boxberry = "Москва";
							}
							if (WC()->customer->get_shipping_state() == 'РФ, Санкт-Петербург' || WC()->customer->get_shipping_state() === 'LE') {
								$shipping_city_4_boxberry = "Санкт-Петербург";
							}							
							echo '<a href="#" class="button" onclick="boxberry.open('."'bb_pvz_function'".','."'1\$RLsqL2_rvA7qC-6XrxlYP6o6OfvrnKFa'".", '".$shipping_city_4_boxberry."','',1000,".$order_weight.');return false">Выбрать</a>';
						}?>						
					</li>
					<div class="shipping_method_comment"><?php echo $_was_shipping_method['shipping_comment']; ?></div>
					<?php if (checked( $method->id, $chosen_method, false ) && ((mb_stripos($method->label,'курьер') !== false) || (mb_stripos($method->label,'почт') !== false))) { 
							if (is_user_logged_in()) {	
							$customer_id = get_current_user_id();
								$shipping_address_1 = get_user_meta( $customer_id, 'shipping_address_1', true );
								$shipping_address_2 = get_user_meta( $customer_id, 'shipping_address_2', true );
								$shipping_building = get_user_meta( $customer_id, 'shipping_building', true );
								$shipping_flat = get_user_meta( $customer_id, 'shipping_flat', true );
							} else {
								$shipping_address_1 = '';
								$shipping_address_2 = '';
								$shipping_building = '';
								$shipping_flat = '';	
							}?>
						<div class="address_additional">
							<p class="form-row form-row-first" id="shipping_add_1"><label for="shipping_add_field1" class="">Улица <abbr class="required" title="required">*</abbr></label><input class="input-text " name="shipping_add_field1" id="shipping_add_field1" placeholder="" value="<?php echo $shipping_address_1; ?>" type="text"></p>
							<p class="form-row" id="shipping_add_2"><label for="shipping_add_field2" class="">Дом <abbr class="required" title="required">*</abbr></label><input class="input-text " name="shipping_add_field2" id="shipping_add_field2" placeholder="" value="<?php echo $shipping_address_2; ?>" type="text"></p>
							<p class="form-row" id="shipping_add_3"><label for="shipping_add_field3" class="">Корпус</label><input class="input-text " name="shipping_add_field3" id="shipping_add_field3" placeholder="" value="<?php echo $shipping_building; ?>" type="text"></p>
							<p class="form-row form-row-last" id="shipping_add_4"><label for="shipping_add_field4" class="">Квартира</label><input class="input-text " name="shipping_add_field4" id="shipping_add_field4" placeholder="" value="<?php echo $shipping_flat; ?>" type="text"></p>
						</div>
						<?php } ?>
				<?php endforeach; ?>
			</ul>			
		<?php elseif ( 1 === count( $available_methods ) ) :  ?>
			<?php
				$method = current( $available_methods );
				
				$_was_shipping_method = get_post_meta( $method->id,'_was_shipping_method',true);
				
				echo '<div class="selected_item">';
				printf( '<label>%3$s</label><input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d" value="%2$s" class="shipping_method " />', $index, esc_attr( $method->id ), wc_cart_totals_shipping_method_label( $method ) );
				if ( isset($_was_shipping_method['pickpoint_zone']) && $_was_shipping_method['pickpoint_zone']!=='') {
					//echo ((mb_stripos($method->label,'пункт') !== false && mb_stripos($method->label,'выдач') !== false) || (mb_stripos($method->label,'пвз') !== false))?'<a href="#" class="button" onclick="PickPoint.open(my_function);return false;">Выбрать</a>':'';
					echo '<a href="#" class="button" onclick="PickPoint.open(my_function);return false;">Выбрать</a>';
				}
				$order_weight = WC()->cart->get_cart_contents_weight();
				if ($order_weight<1) {
					$order_weight = 1;
				}
				if ( isset($_was_shipping_method['boxberry_zone']) && $_was_shipping_method['boxberry_zone']) {
					$shipping_city_4_boxberry = WC()->customer->get_shipping_city();
					if (WC()->customer->get_shipping_state() == 'РФ, Москва' || WC()->customer->get_shipping_state() === 'MO') {
						$shipping_city_4_boxberry = "Москва";
					}
					if (WC()->customer->get_shipping_state() == 'РФ, Санкт-Петербург' || WC()->customer->get_shipping_state() === 'LE') {
						$shipping_city_4_boxberry = "Санкт-Петербург";
					}					
					echo '<a href="#" class="button" onclick="boxberry.open('."'bb_pvz_function'".','."'1\$RLsqL2_rvA7qC-6XrxlYP6o6OfvrnKFa'".", '".WC()->customer->get_shipping_city()."','',1000,".$order_weight.');return false">Выбрать</a>';
				}				
				echo '</div>';
	
				echo '<div class="shipping_method_comment">'.$_was_shipping_method['shipping_comment'].'</div>';
				do_action( 'woocommerce_after_shipping_rate', $method, $index );
				if ((mb_stripos($method->label,'курьер') !== false) || (mb_stripos($method->label,'почт') !== false)) { ?>
							<div class="address_additional">
							<p class="form-row form-row-first" id="shipping_add_1"><label for="shipping_add_field1" class="">Улица <abbr class="required" title="required">*</abbr></label><input class="input-text " name="shipping_add_field1" id="shipping_add_field1" placeholder="" value="<?php echo $shipping_address_1; ?>" type="text"></p>
							<p class="form-row" id="shipping_add_2"><label for="shipping_add_field2" class="">Дом <abbr class="required" title="required">*</abbr></label><input class="input-text " name="shipping_add_field2" id="shipping_add_field2" placeholder="" value="<?php echo $shipping_address_2; ?>" type="text"></p>
							<p class="form-row" id="shipping_add_3"><label for="shipping_add_field3" class="">Корпус</label><input class="input-text " name="shipping_add_field3" id="shipping_add_field3" placeholder="" value="<?php echo $shipping_building; ?>" type="text"></p>
							<p class="form-row form-row-last" id="shipping_add_4"><label for="shipping_add_field4" class="">Квартира</label><input class="input-text " name="shipping_add_field4" id="shipping_add_field4" placeholder="" value="<?php echo $shipping_flat; ?>" type="text"></p>
							</div>
				<?php } ?>
		<?php elseif ( ! WC()->customer->has_calculated_shipping() ) : ?>
			<?php echo wpautop( __( 'Shipping costs will be calculated once you have provided your address.', 'woocommerce' ) ); ?>
		<?php else : ?>
			<?php echo apply_filters( is_cart() ? 'woocommerce_cart_no_shipping_available_html' : 'woocommerce_no_shipping_available_html', wpautop( __( 'There are no shipping methods available. Please double check your address, or contact us if you need any help.', 'woocommerce' ) ) ); ?>
		<?php endif; ?>

		<?php if ( $show_package_details ) : ?>
			<?php echo '<p class="woocommerce-shipping-contents"><small>' . esc_html( $package_details ) . '</small></p>'; ?>
		<?php endif; ?>

		<?php if ( is_cart() && ! $index ) : ?>
			<?php woocommerce_shipping_calculator(); ?>
		<?php endif; ?>
	</td>
</tr>

