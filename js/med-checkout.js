 (function($){
$(function(){
	$('form.checkout').keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
		}
	});
	$( 'body' ).on( 'change', 'select.state_select', function() {
		$('#shipping_add_city').remove();
		
		if ($('#shipping_city option').length < 4) {
			/*$("#shipping_city").select2("val", "");*/
			$("#shipping_city").select2("destroy");
			$('#shipping_city option[value="В черте города"]').prop('selected', true);
			$("#shipping_city").select2();
			console.log('select the 2-nd option');
		}
	});
	$( 'body' ).on( 'change', 'select.city_select', function() {
		if ($("#shipping_city").val()=='Другой') {
			$("#shipping_address_1").val('');
			$("#shipping_add_field2").val('');
			$("#shipping_add_field3").val('');
			$("#shipping_add_field4").val('');
			$('#shipping_city_field').after('<div class="form-row form-row-last address-field" id="shipping_add_city"><label for="shipping_add_city_field" class="">Укажите свой населенный пункт</label><div class="input_add_city"><input class="input-text" name="shipping_add_city_field" id="shipping_add_city_field" placeholder="" value="" type="text"><a href="#" class="button">Применить</a></div></div>');
		} else {
			$('#shipping_add_city').remove();
		}
		$( document.body ).trigger( 'update_checkout' );
	});
	$( 'body' ).on( 'focusout', '#shipping_add_city', function() {
		new_city = $('#shipping_add_city_field').val();
		if (new_city) {
			$('#select option').removeAttr('selected');
			//$('#shipping_city').prepend('<option value="'+new_city+'">'+new_city+'</option>');
			$('<option value="'+new_city+'">'+new_city+'</option>').prependTo('#shipping_city').prop('selected', true);
			$('#shipping_city option:first').attr('selected', 'selected');
			$('#shipping_add_city').remove();			
		}

		/*$('.woocommerce-checkout-review-shipping-table').addClass( 'processing' ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});*/
		$( document.body ).trigger( 'update_checkout' );
		/*$('.woocommerce-checkout-review-shipping-table').removeClass( 'processing' ).unblock();*/
	});
	$( 'body' ).on( 'hide', ':radio[name="payment_method"]', function() {
		$( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );
	});
	/*this.pay = function () {
		var widget = new cp.CloudPayments();
		widget.charge({ // options
				publicId: 'test_api_00000000000000000000001',  //id из личного кабинета
				description: 'Пример оплаты (деньги сниматься не будут)', //назначение
				amount: 10, //сумма
				currency: 'RUB', //валюта
				invoiceId: '1234567', //номер заказа  (необязательно)
				accountId: 'user@example.com', //идентификатор плательщика (необязательно)
				data: {
					myProp: 'myProp value' //произвольный набор параметров
				}
			},
			function (options) { // success
				//действие при успешной оплате
			},
			function (reason, options) { // fail
				//действие при неуспешной оплате
			});
	};*/ 
});
})(jQuery);
var shipping_additional = new Array();
/*shipping_additional['shipping_add_field1'] = document.getElementById('shipping_address_1').value;
shipping_additional['shipping_add_field2'] = document.getElementById('shipping_address_2').value;
shipping_additional['shipping_add_field3'] = document.getElementById('shipping_building').value; 
shipping_additional['shipping_add_field4'] = document.getElementById('shipping_flat').value;*/
