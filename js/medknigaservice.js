jQuery( document ).ready( function() {
	jQuery.mask.definitions[ '9' ] = false;
	jQuery.mask.definitions[ 'd' ] = '[0-9]';
	jQuery( ".mask, #billing_phone" ).mask( "+d (ddd) ddd-dd-dd" );
	jQuery( ":checkbox" ).styler();
	jQuery( document.body ).on( 'updated_checkout', function() {
		/*if (jQuery("#select2-shipping_state-container").text() == "Беларусь") {
			jQuery(".mask, #billing_phone").mask("+375 (dd) ddd-dd-dd");
		} else {
			jQuery(".mask, #billing_phone").mask("+d (ddd) ddd-dd-dd");
		}*/

		switch ( jQuery( "#select2-shipping_state-container" ).text() ) {
			case "Армения":
				jQuery( ".mask, #billing_phone" ).mask( "+374 (dd) ddd-ddd" );
				break;
			case "Беларусь":
				jQuery( ".mask, #billing_phone" ).mask( "+375 (dd) ddd-dd-dd" );
				break;
			case "Казахстан":
				jQuery( ".mask, #billing_phone" ).mask( "+7 (ddd) ddd-dd-dd" );
				break;
			case "Киргизия":
				jQuery( ".mask, #billing_phone" ).mask( "+996 (ddd) ddd-ddd" );
				break;
			default:
				jQuery( ".mask, #billing_phone" ).mask( "+d (ddd) ddd-dd-dd" );
		}
		jQuery( ":checkbox" ).styler();
	} );

	/*jQuery( document.body ).on( 'click', '.post-type-shop_order .edit_address', function() { 
		jQuery(".post-type-shop_order #_shipping_postcode").mask("dddddd");
	}*/

	// Shipping State field in checkout
	var shippingState = jQuery( '#shipping_state' );

	if ( shippingState.val() === '' ) {
		console.log( shippingState.parent().addClass( 'woocommerce-invalid' ) );
	};
	if ( jQuery( 'body.woocommerce-cart' ).length || jQuery( 'body.woocommerce-checkout' ).length ) {
		jQuery( '#mini_cart a.menucart' ).addClass( 'disabled' );
		jQuery( document.body ).on( 'wc_fragments_refreshed', function() {
			jQuery( '#mini_cart a.menucart' ).addClass( 'disabled' );

		} );
	};
	jQuery( document.body ).on( 'click', '#searchform .col_i button', function( e ) {
		e.preventDefault();
		jQuery( 'input#s' ).val( '' );
	} );
} );

window.addEventListener( "load", function() {
	let elem = document.querySelector( '#history_back a' );
	if ( window.history.length > 2 ) {
		elem.addEventListener( 'click', ( e ) => {
			e.preventDefault();
			history.back();
		} )
		elem.classList.remove( "hidden" );
	}
} );