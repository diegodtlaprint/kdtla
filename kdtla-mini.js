/*
Plugin Name: KDTLA
Plugin URI: kdtla
Description: Customized WooCommerce for DTLAPrint (multiple colors, quantities, print options, pricing etc.).
Author: Konrad G
Author URI: www.kgretk.com
Copyright: dtlaprint.com, 2023
File: kdtla-mini.js v 1.26

*/

// define popup ids
var quote_popup_id 	= 10745;		// send my quote popup id: LS 9654, s3 10745   elementorProFrontend.modules.popup.showPopup( { id: 10745 } ); 
var upsell_popup_id = 4267;			// upsell popup id: LS: 9669, s3: 4267
var resale_update_again = 0;

window.addEventListener('load', function() {

	console.log('- kdtla mini loaded');

	//$ = jQuery;

	// mini cart click - go to /cart
	jQuery('.elementor-menu-cart__toggle_wrapper').click(function(){ window.location='/cart/' })


	// not used, but kept just in case
	jQuery( document.body ).on( 'removed_from_cart', function() {
		setTimeout(() => { 
			mini_compress();
		}, 500); 
	} );
	
	// mini cart correction, after delay
	setTimeout(() => { 
		mini_compress()
	}, 2000); 
	
	function mini_compress() {
		console.log('- mini compressing..');
		jQuery('.woocommerce-mini-cart dl.variation').hide(); // hide details
		jQuery('.woocommerce-mini-cart dl.variation').parent().prepend('<span class="mm"> (+) </span>');
		jQuery('.mm').click(function(el,i) { 
			if ($(this).parent().find('dl.variation:visible').length>0) {
				$(this).parent().find('dl.variation').hide(); 
				$(this).text('(+)');
			}
			else {
				$(this).parent().find('dl.variation').show();
				$(this).text('(-)');
			}
		});  
		// prod price correction
		jQuery('.woocommerce-mini-cart .elementor-menu-cart__product-price .woocommerce-Price-amount bdi').each(function(i,el){ $(el).text('$'+$($(el).parent().parent().parent().parent().find('.variation-Priceperitem')[1]).text() ); });
	}

	
	// Send My Quote clicked 
	jQuery('.send_my_quote_button').click(function(){

		console.log('quote clicked...');

		
		// version 2 - product info in a field, no page reload
		if (1) {
			elementorProFrontend.modules.popup.showPopup( { id: quote_popup_id } );
			localStorage.setItem('kdtla_quote_product', add_prod_info('#form-field-field_62a0a07') );
			add_prod_info('#form-field-field_62a0a07');
			jQuery('#form-field-field_945677e').val($('#wcsession').val());
			console.log(' - - mini - quote, prod info only, no adding to cart.');
		}

		// version 1b - with add to cart and page reload
		// if Send My Quote button clicked again
		if (0)
			if ( localStorage.getItem('kdtla_quote')==1 ) {
				elementorProFrontend.modules.popup.showPopup( { id: quote_popup_id } );
				setTimeout(() => { 
					$('#form-field-field_62a0a07').val( localStorage.getItem('kdtla_quote_product') );
					$('#form-field-field_945677e').val($('#wcsession').val());
					console.log(' - - mini - product, wc session updated');

				}, 1000);

			} else {
				// if clicked first time, then submit form and open popup after page reload
				localStorage.setItem('kdtla_quote', 1);
				localStorage.setItem('kdtla_quote_product', add_prod_info('#form-field-field_62a0a07') );
				jQuery('.send_my_quote_button').text('Adding to Quote...');
				jQuery('.send_my_quote_button').attr('disabled','disabled');
				jQuery('form.cart').submit();
			}
		
		
	});
	
	// on page load, open quote popup - not used
	/*
	if ( localStorage.getItem('kdtla_quote')==1 ) {
		setTimeout(() => { 
			elementorProFrontend.modules.popup.showPopup( { id: quote_popup_id } ); // send my quote popup id
			$('#form-field-field_62a0a07').val( localStorage.getItem('kdtla_quote_product') );
			$('#form-field-field_945677e').val($('#wcsession').val());
			localStorage.removeItem('kdtla_quote');
		}, 1000);
		
	}
	*/
	
	// quote popup - cancel
	jQuery(document).on('click','.elementor-element-7db2ac6b .elementor-button-link', function(event){
		elementorProFrontend.modules.popup.closePopup( {}, event);
	});
	
	/*
	$( document ).on( 'elementor/popup/show', ( event, id, instance ) => {
		if ( id === quote_popup_id ) {
			$('#form-field-field_945677e').val($('#wcsession').val()); // not working .. ?
			console.log(' - - mini - wc session update ..');
		}
	} );
	
	
	$( document ).on( 'elementor/popup/hide', ( event, id, instance ) => {
		if ( id === quote_popup_id ) {
			//localStorage.removeItem('kdtla_quote');
			console.log(' no popup: kdtla_quote');
		}
	} );
	*/

	// quote - version 2 - ff = #form-field-field_62a0a07
	function add_prod_info( ff ) {

		//product id, url, name ?
		var prod_id = $('form.cart').find('button[name=add-to-cart]').val();
		var prod_url = document.location.href;

		// quantities: 
		var sizes=[]; 
		$('#table_q thead td').each(function(i,el){ if(i>0) sizes[i]=$(el).text().trim(); });

		var q = ''; $('#table_q tr:visible').each(function(r,el) { if( r>0) q=q+ $(el).find('.c_name').val();  $(el).find('.q1:visible').each(function(i,c){ if($(c).val()>0) q=q+','+$(c).val(); else q=q+',0' }); q=q+' | '; });

		// decorations:
		var d='';
		d=d+'| Front='+$('#front_print_type').val(); if($('#front_print_type').val()=='Screen Print') { d=d+',c='+$('#front_colors').val()+',ink='+$('#front_ink').val(); }
		d=d+'| Back='+$('#back_print_type').val(); if($('#back_print_type').val()=='Screen Print') { d=d+',c='+$('#back_colors').val()+',ink='+$('#back_ink').val(); }
		d=d+'| Left='+$('#left_print_type').val(); if($('#left_print_type').val()=='Screen Print') { d=d+',c='+$('#left_colors').val()+',ink='+$('#left_ink').val(); }
		d=d+'| Right='+$('#right_print_type').val(); if($('#right_print_type').val()=='Screen Print') { d=d+',c='+$('#right_colors').val()+',ink='+$('#right_ink').val(); }
		d=d+'| Neck='+$('#neck_print_type').val(); if($('#neck_print_type').val()=='Screen Print') { d=d+',c='+$('#neck_colors').val()+',ink='+$('#neck_ink').val(); }

		// finishings:
		var fin='| Fin: ';
		$('.fin_tile').each(function(i, el) { if ($(this).hasClass('fin_tile_selected')) { fin=fin+ $(this).data('fin') +','  } } );

		// prices
		var prices = '| Per item='+$('#k_per_item').val() +'| Total='+$('#k_total_price').val();
		
		// notes
		var notes = $('#design_notes').val();

		// out to field in the popup
		$( ff ).val( prod_id + '|' + prod_url + '|' + sizes + '|' + q + d + fin + prices + '|' + notes );
		
		return prod_id + '|' + prod_url + '|' + sizes + '|' + q + d + fin + prices + '|' + notes;

	}
	
	// upsell popup - after add to cart
	jQuery('form.cart').submit(function(e) {
		localStorage.setItem('kdtla_upsell', 1);
		console.log('mini - upsell after reload...');

	});

	// open upsell if .. TEMP dont
	if ( localStorage.getItem('kdtla_upsell')==1 ) {
		setTimeout(() => { 
			//elementorProFrontend.modules.popup.showPopup( { id: upsell_popup_id } ); 
		}, 1000);
		localStorage.setItem('kdtla_upsell', 0);
	}



	
	
	// K_STICKY
	// correction for WP logged in
	if (typeof(jQuery('#wpadminbar'))!=='undefined' && jQuery('#wpadminbar').length > 0 ) {
		//jQuery('#k_sticky').css('margin-top','2em');
		jQuery('#primary').css('margin-top','4em');
	}


	// K_STICKY correction for notification bar - depending on header height - only for desktop/tablet
	var k_sticky_position = 0;
	if (typeof(jQuery('#k_sticky'))!=='undefined' && jQuery('#k_sticky').length > 0 ) {
		k_sticky_position = $('#k_sticky').offset().top;
	}
	
	if (window.screen.availWidth > 673 && k_sticky_position > 0 ) {
		$(window).scroll(function(){
			if ($('.easy-notification-bar').height()>0) {
				if ($(window).scrollTop() > k_sticky_position - $($('.elementor-location-header')[0]).height() ){
					$('#k_sticky').css('position','fixed').css('top', $($('.elementor-location-header')[0]).height() );
					$('#primary').css('margin-top','4em');
					if (typeof(jQuery('#wpadminbar'))!=='undefined' && jQuery('#wpadminbar').length > 0 ) {
						$('#k_sticky').css('margin-top','2em');
					}
				} else {
					$('#k_sticky').css('position','relative');
						$('#k_sticky').css('top','0');
					$('#k_sticky').css('margin-top','0em');
					$('#primary').css('margin-top','0em');
				}  
			} else {
				$('#k_sticky').css('position','fixed').css('top', $($('.elementor-location-header')[0]).height() ); //.css('margin-top','2em');
				$('#primary').css('margin-top','4em');
				if (typeof(jQuery('#wpadminbar'))!=='undefined' && jQuery('#wpadminbar').length > 0 ) {
					$('#k_sticky').css('margin-top','2em');
				}
			}
		});
	}
	

	// position depending on header height - if not mobile - TODO
	//jQuery('#k_sticky').css('top', $($('.elementor-location-header')[0]).height() + 'px' ); 



	// CHECKOUT
	jQuery('#ship-to-different-address-checkbox').hide(); //  not remove
	//jQuery('#ship-to-different-address-checkbox').prop('disabled','disabled'); // cant be disabled because shipping address missing
	jQuery('#ship-to-different-address-checkbox').prop('checked',false); // initial false


	// CHECKOUT - LOCAL OR SHIPPED - select defined in checkout page (elementor), values: local, shipped
	// on page load
	jQuery('.woocommerce-shipping-fields').hide();
	var local_pick = jQuery(jQuery('#shipping_method_0 option')[0]).val(); // Local pickup must be first in the shipping options
	var ship_1 = jQuery(jQuery('#shipping_method_0 option')[1]).val(); // next option after Local


	// on change
	jQuery('#shipping_or_pickup').change(function(){
		if( jQuery('#shipping_or_pickup').val()=='local') {
			jQuery('.woocommerce-shipping-fields').hide(); 
			jQuery('#shipping_method_0').val(local_pick);
			jQuery('#ship-to-different-address-checkbox').prop('checked',false);
			jQuery('#shipping_method_0').trigger('update');
		}
		else {
			jQuery('.shipping_address').show();
			jQuery('.woocommerce-shipping-fields').show();  
			jQuery('.thwcfe-shipping-field').show();
			jQuery('#shipping_method_0').val(ship_1);
			jQuery('#ship-to-different-address-checkbox').prop('checked',true);
			jQuery('#shipping_method_0').trigger('update');
		}
	});

	// link to shipping changes in order summary
	jQuery( document.body ).on('updated_checkout', function() {

		console.log('  updating checkout ... ');

		// shipping method change - must be inside cart update
		jQuery('#shipping_method_0').change(function(){
			if ( jQuery('#shipping_method_0 :selected').text().substr(0,5).toLowerCase() == 'local') {
				jQuery('#shipping_or_pickup').val('local'); 
				//jQuery('#shipping_or_pickup').trigger('change');
				jQuery('.woocommerce-shipping-fields').hide(); 
				jQuery('#ship-to-different-address-checkbox').prop('checked',false);
				console.log('  shipping_method_0 changed ... local ');
			} 
			else {
				jQuery('#shipping_or_pickup').val('shipped');
				//jQuery('#shipping_or_pickup').trigger('change');
				jQuery('.woocommerce-shipping-fields').show(); 
				jQuery('.thwcfe-shipping-field').show();
				jQuery('#ship-to-different-address-checkbox').prop('checked',true);
				console.log('  shipping_method_0 changed ... not local ');
			}
		});

		// if only Local and Free in shipping - error in address
		if (jQuery('#shipping_method_0 option').length < 3) {
			if (jQuery('.wrong_address').length == 0) {
				jQuery('#shipping_state_field').prepend('<div class="wrong_address"> Review Zip Code & State, Error </div>');
				jQuery('#order_review [data-title=Shipping]').prepend('<div class="wrong_address"> Review Zip Code & State, Error </div>');
			}
		} else {
			jQuery('.wrong_address').remove();
		}

		// if set to local after address correction
		/*
		console.log('sync ship or local');
		var ship_1 = jQuery(jQuery('#shipping_method_0 option')[1]).val(); // first not local
		if ( jQuery('#shipping_or_pickup').val() == 'shipped' && jQuery('#shipping_method_0 :selected').text().substr(0,5).toLowerCase() == 'local' ) {
			jQuery('#shipping_method_0 option[value="'+ship_1+'"]').attr('selected','selected');
			jQuery('#shipping_method_0').trigger('update');
		}
		*/

		// if Shipped then remove Local
		if ( jQuery('#shipping_or_pickup').val() == 'shipped' && jQuery(jQuery('#shipping_method_0 option')[0]).text().substr(0,5).toLowerCase() == 'local' ) {
			jQuery(jQuery('#shipping_method_0 option')[0]).remove();
			console.log(' removed local...');
		}

	});



	// CHECKOUT delivery - rush
	// on page load
	jQuery('#delivery_field').insertBefore('div#customer_details');
	jQuery(jQuery('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[0]).addClass('kselected');
	jQuery(jQuery('.kdelivery1 .thwcfe-input-field')[0]).prop('checked',true); // select first option
	
	// on change - counting from 0
	jQuery('.kdelivery1 .thwcfe-input-field').change(function() { 
		$('.kdelivery1 .woocommerce-input-wrapper .delivery_label').removeClass('kselected'); 
		if ($('.kdelivery1 .thwcfe-input-field')[0].checked) $($('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[0]).addClass('kselected');  
		if ($('.kdelivery1 .thwcfe-input-field')[1].checked) $($('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[1]).addClass('kselected');  
		if ($('.kdelivery1 .thwcfe-input-field')[2].checked) $($('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[2]).addClass('kselected');  
		if ($('.kdelivery1 .thwcfe-input-field')[3].checked) $($('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[3]).addClass('kselected'); 
		if ($('.kdelivery1 .thwcfe-input-field')[4].checked) $($('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[4]).addClass('kselected'); 
		if ($('.kdelivery1 .thwcfe-input-field')[5].checked) $($('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[5]).addClass('kselected'); 
		if ($('.kdelivery1 .thwcfe-input-field')[6].checked) $($('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[6]).addClass('kselected'); 
		if ($('.kdelivery1 .thwcfe-input-field')[7].checked) $($('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[7]).addClass('kselected'); 
		if ($('.kdelivery1 .thwcfe-input-field')[8].checked) $($('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[8]).addClass('kselected'); 
		if ($('.kdelivery1 .thwcfe-input-field')[9].checked) $($('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[9]).addClass('kselected');
		if ($('.kdelivery1 .thwcfe-input-field')[10].checked) $($('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[10]).addClass('kselected'); 
		if ($('.kdelivery1 .thwcfe-input-field')[11].checked) $($('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[11]).addClass('kselected');  
	});
	

	// CHECKOUT - standard delivery time update
	// hide some fields - labels counting from 1
	
	if ( typeof(delivery_plus)!=='undefined' && delivery_plus==0 ) { 
		$($('#delivery_field label')[2]).hide(); 
		$($('#delivery_field label')[3]).hide(); 
		$($('#delivery_field label')[5]).hide(); 
		$($('#delivery_field label')[6]).hide(); 
		$($('#delivery_field label')[8]).hide(); 
		$($('#delivery_field label')[9]).hide(); 
		$($('#delivery_field label')[11]).hide(); 
		$($('#delivery_field label')[12]).hide(); 
	}

	if ( typeof(delivery_plus)!=='undefined' && delivery_plus==2 ) { 
		$($('#delivery_field label')[1]).hide(); 
		$($('#delivery_field label')[3]).hide(); 
		$($('#delivery_field label')[4]).hide(); 
		$($('#delivery_field label')[6]).hide(); 
		$($('#delivery_field label')[7]).hide(); 
		$($('#delivery_field label')[9]).hide(); 
		$($('#delivery_field label')[10]).hide(); 
		$($('#delivery_field label')[12]).hide(); 
		jQuery(jQuery('.kdelivery1 .thwcfe-input-field')[1]).prop('checked',true); // select first option
		$($('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[1]).addClass('kselected');  
	}

	if ( typeof(delivery_plus)!=='undefined' && delivery_plus==14 ) { 
		$($('#delivery_field label')[1]).hide(); 
		$($('#delivery_field label')[2]).hide(); 
		$($('#delivery_field label')[4]).hide(); 
		$($('#delivery_field label')[5]).hide(); 
		$($('#delivery_field label')[7]).hide(); 
		$($('#delivery_field label')[8]).hide(); 
		$($('#delivery_field label')[10]).hide(); 
		$($('#delivery_field label')[11]).hide(); 
		jQuery(jQuery('.kdelivery1 .thwcfe-input-field')[2]).prop('checked',true); // select first option
		$($('.kdelivery1 .woocommerce-input-wrapper .delivery_label')[2]).addClass('kselected');  
	}

	

	// CHECKOUT - RESALE PERMIT
	// set or clear cookie which is used by k_switch_product_tax_class in k_functions.php, after a small delay
	jQuery('#resale_permit_file').change(function(){
		console.log('RESALE uploading...');
		setTimeout(() => {
			resale_cookie();
			jQuery('#shipping_method_0').trigger('update');
		}, 800);
		hasRun = false;
	});

	jQuery('#shipping_state').change(function(){
		hasRun = false;
		resale_cookie();
		console.log('  run again ... ');
	});

	// on cart update
	var hasRun = false;
	jQuery( document.body ).on('updated_checkout', function() { 
		if (!hasRun) {
			setTimeout(() => {
				resale_cookie();
				//jQuery('#shipping_method').trigger('update'); // local
				jQuery('#shipping_method_0').trigger('update');
			}, 1000);
			hasRun = true;
			console.log(' run once ');
		} else console.log(' already run ... ');

		// shipping method change - must be inside cart update
		jQuery('#shipping_method_0').change(function(){
			resale_cookie();
			//hasRun = false;
			console.log('  shipping changed.. run again ?... ');
		});
	});

	// set or clear cookie  
	function resale_cookie() {
		if ( jQuery('.thwcfe-uloaded-file-list-item').length > 0 && ( jQuery('#shipping_state').val()=='CA' || jQuery('#shipping_method_0').val().substr(0,5)=='local' )  ) {
			document.cookie = "dtla_resale=business; expires=; path=/";
			console.log('RESALE cookie set.');
		} else { 
			document.cookie = "dtla_resale=none; expires=; path=/";
			console.log('RESALE cookie - cleared.');
		};
	}

	// TEMP - local only
	/*
	jQuery('#billing_state').change(function(){
		hasRun = false;
		resale_cookie();
		console.log(' b run again ... ');
	});
	*/
	

	

	// CHECKOUT - ORDER RECEIVED

	if (jQuery('.woocommerce-order-details').length > 0) {
		jQuery('.elementor-section [data-id=0a4e4d5]').hide();
		jQuery('.elementor-element-8a9155d').addClass('k_order_received'); // step 3
	}
		



	// CHECKOUT - ORDER RECEIVED - hide some fields
	// some are displayed via action k_custom_display_order_item_meta in k_wc_backend.php

	jQuery('.wc-item-meta li:contains("html")').hide();
	jQuery('.wc-item-meta li p:contains("rgb")').hide();
	jQuery('.wc-item-meta li:contains("positions")').hide();
	jQuery('.wc-item-meta li:contains("Blanks")').hide();
	jQuery('.wc-item-meta li:contains("user session")').hide();
	jQuery('.wc-item-meta li:contains("variation")').hide();
	jQuery('.wc-item-meta li:contains("sizes")').hide();
	jQuery('.wc-item-meta li:contains(" cost")').hide();
	// displayed via action so hide
	jQuery('.wc-item-meta li:contains("style")').hide();
	jQuery('.wc-item-meta li:contains("Front")').hide();
	jQuery('.wc-item-meta li:contains("Back")').hide();
	jQuery('.wc-item-meta li:contains("Left")').hide();
	jQuery('.wc-item-meta li:contains("Right")').hide();
	jQuery('.wc-item-meta li:contains("Neck")').hide();
	
	jQuery('.wc-item-meta li:contains("Price")').hide();
	jQuery('.wc-item-meta li:contains("Quantity total")').hide();
	jQuery('.wc-item-meta li:contains("Artwork")').hide();

	
	jQuery('.wc-item-meta li:contains("Finishings")').css('width','90%');
	//jQuery('.wc-item-meta li:contains("Quantity total")').css('width','90%');
	//jQuery('.wc-item-meta li:contains("Price per")').css('width','90%');
	//jQuery('.wc-item-meta li:contains("Price total")').css('width','90%');

	var p1 = jQuery('.wc-item-meta li:contains("Artwork 1 preview") a').text();
	if (p1.length>10) jQuery('.wc-item-meta li:contains("Artwork 1 preview")').append('<img src="'+p1+'" width="100px" />');
	var p2 = jQuery('.wc-item-meta li:contains("Artwork 2 preview") a').text();
	if (p2.length>10) jQuery('.wc-item-meta li:contains("Artwork 2 preview")').append('<img src="'+p2+'" width="100px" />');
	var p3 = jQuery('.wc-item-meta li:contains("Artwork 3 preview") a').text();
	if (p3.length>10) jQuery('.wc-item-meta li:contains("Artwork 3 preview")').append('<img src="'+p3+'" width="100px" />');
	var p4 = jQuery('.wc-item-meta li:contains("Artwork 4 preview") a').text();
	if (p4.length>10) jQuery('.wc-item-meta li:contains("Artwork 4 preview")').append('<img src="'+p4+'" width="100px" />');

	jQuery('.wc-item-meta li a:contains("preview")').hide();

	var p1 = jQuery('.wc-item-meta li:contains("Artwork 1 files") a').text(); // TODO if multiple files?
	jQuery('.wc-item-meta li:contains("Artwork 1 files") p').hide();
	if (p1.length>10) jQuery('.wc-item-meta li:contains("Artwork 1 files")').append('<img src="'+p1+'" width="100px" />');

	// remove "color 1" etc, for each product
	jQuery('.woocommerce-table__line-item').each( function(i,el){
		var kcolors = $(el).find('.kcolors_order').text().split(','); console.log(kcolors);

		jQuery(this).find('.wc-item-meta li:contains("color 1 size")').each(function(i,el){
			if (i==0) 
				jQuery(this).html( jQuery(this).text().replace('color 1 size','<strong> Color 1 ('+ kcolors[0] +'): </strong> ') );
			else
				jQuery(this).text( jQuery(this).text().replace('color 1 size',' ') ); });

		jQuery(this).find('.wc-item-meta li:contains("color 2 size")').each(function(i,el){
			if (i==0) 
				jQuery(this).html( jQuery(this).text().replace('color 2 size','<strong> Color 2 ('+ kcolors[1] +'): </strong> ') );
			else
				jQuery(this).text( jQuery(this).text().replace('color 2 size',' ') ); });

		jQuery(this).find('.wc-item-meta li:contains("color 3 size")').each(function(i,el){
			if (i==0) 
				jQuery(this).html( jQuery(this).text().replace('color 3 size','<strong> Color 3 ('+ kcolors[2] +'): </strong>') );
			else
				jQuery(this).text( jQuery(this).text().replace('color 3 size',' ') ); });

		jQuery(this).find('.wc-item-meta li:contains("color 4 size")').each(function(i,el){
			if (i==0) 
				jQuery(this).html( jQuery(this).text().replace('color 4 size','<strong> Color 4 ('+ kcolors[3] +'): </strong>') );
			else
				jQuery(this).text( jQuery(this).text().replace('color 4 size',' ') ); });

		jQuery(this).find('.wc-item-meta li:contains("color 5 size")').each(function(i,el){
			if (i==0) 
				jQuery(this).html( jQuery(this).text().replace('color 5 size','<strong> Color 5 ('+ kcolors[4] +'): </strong>') );
			else
				jQuery(this).text( jQuery(this).text().replace('color 5 size',' ') ); });
	});
	

	
	// remaining color 1, color 2, etc
	jQuery('.wc-item-meta li:contains("color ")').hide();

	// show meta after all modifications
	jQuery('.wc-item-meta').show();



	// CHECKOUT - ORDER-PAY

	if ( jQuery('.e-checkout__order_review').length == 0 ) {
		jQuery('.elementor-element-e353e63').hide();
		jQuery('.wc_payment_methods').show();
		jQuery('#payment_method_stripe').click();
		jQuery('.payment_method_cod').remove();
	}



	// WC BACKEND

	// edit order - hide some fields

	setTimeout(() => { 

		jQuery('.display_meta th:contains("html")').parent().hide();
		jQuery('.display_meta th:contains("Artwork")').parent().hide();
		jQuery('.display_meta th:contains("session")').parent().hide();
		jQuery('.display_meta th:contains(" cost")').parent().hide();
		jQuery('.display_meta th:contains("Price ")').parent().hide();
		jQuery('.display_meta th:contains("Quantity total")').parent().hide();
		jQuery('.display_meta th:contains("Blanks")').parent().hide();
		jQuery('.display_meta th:contains("variation")').parent().hide();
		jQuery('.display_meta th:contains("sizes")').parent().hide();
		console.log('- backend - hide some fields ');

	}, 1000); 

	// remove "color 1" etc
	/*
	jQuery('.display_meta th:contains("color 1 size")').each(function(i,el){
		if (i==0) 
			jQuery(this).html( jQuery(this).text().replace('color 1 size','<strong> Color 1: </strong> ') );
		else
			jQuery(this).text( jQuery(this).text().replace('color 1 size',' ') ); });

	jQuery('.display_meta th:contains("color 2 size")').each(function(i,el){
		if (i==0) 
			jQuery(this).html( jQuery(this).text().replace('color 2 size','<strong> Color 2: </strong> ') );
		else
			jQuery(this).text( jQuery(this).text().replace('color 2 size',' ') ); });

	jQuery('.display_meta th:contains("color 3 size")').each(function(i,el){
		if (i==0) 
			jQuery(this).html( jQuery(this).text().replace('color 3 size','<strong> Color 3: </strong>') );
		else
			jQuery(this).text( jQuery(this).text().replace('color 3 size',' ') ); });

	jQuery('.display_meta th:contains("color 4 size")').each(function(i,el){
		if (i==0) 
			jQuery(this).html( jQuery(this).text().replace('color 4 size','<strong> Color 4: </strong>') );
		else
			jQuery(this).text( jQuery(this).text().replace('color 4 size',' ') ); });

	jQuery('.display_meta th:contains("color 5 size")').each(function(i,el){
		if (i==0) 
			jQuery(this).html( jQuery(this).text().replace('color 5 size','<strong> Color 5: </strong>') );
		else
			jQuery(this).text( jQuery(this).text().replace('color 5 size',' ') ); });
		*/


	// MENU

	// temp - trial sub menu
	setTimeout(() => { 
		var a=jQuery('.elementor-repeater-item-de3717c').offset() || 0;
		jQuery('.elementor-repeater-item-de3717c .sub-menu').css('top',-a.top+200);
		jQuery('.elementor-repeater-item-de3717c .sub-menu').css('left',120);
		jQuery('.elementor-repeater-item-de3717c .sub-menu').css('width',1400);
		jQuery('.elementor-repeater-item-de3717c .sub-menu').css('border','1px solid red');
		console.log('sub temp...');
	}, 2000); 
	
	jQuery('.elementor-repeater-item-de3717c').on('mouseover', function(el){  
		var a=jQuery('.elementor-repeater-item-de3717c').offset();
		jQuery('.elementor-repeater-item-de3717c .sub-menu').css('top',-a.top+200);
		jQuery('.elementor-repeater-item-de3717c .sub-menu').css('left',120);
		jQuery('.elementor-repeater-item-de3717c .sub-menu').css('width',1400);
		jQuery('.elementor-repeater-item-de3717c .sub-menu').css('border','1px solid red');
		console.log('sub temp 2...');
	});
	
	// tabs - on hover
	//jQuery('#eael-advance-tabs-9b37280 li').on('mouseover', function(el){  this.click(); });
	//jQuery('#eael-advance-tabs-884f298 li').on('mouseover', function(el){  this.click(); });

	/*
	jQuery('#menu-tab-1.e-n-tab-title').on('mouseover', function(el){  this.click(); });
	jQuery('#menu-tab-2.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#menu-tab-3.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#menu-tab-4.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#menu-tab-5.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#menu-tab-6.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#menu-tab-7.e-n-tab-title').on('mouseover', function(el){  this.click(); });
	*/
	jQuery('#product-tab-1.e-n-tab-title').on('mouseover', function(el){  this.click(); });
	jQuery('#product-tab-2.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#product-tab-3.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#product-tab-4.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#product-tab-5.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#product-tab-6.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#product-tab-7.e-n-tab-title').on('mouseover', function(el){  this.click(); });

	jQuery('#service-tab-1.e-n-tab-title').on('mouseover', function(el){  this.click(); });
	jQuery('#service-tab-2.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#service-tab-3.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#service-tab-4.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#service-tab-5.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#service-tab-6.e-n-tab-title').on('mouseover', function(el){  this.click(); });
    jQuery('#service-tab-7.e-n-tab-title').on('mouseover', function(el){  this.click(); });
	
	// konrad: menu links
	jQuery(jQuery('#service-tab-1')[0]).append('<a href="/screen-printing/" style="position:absolute;width:100%;z-index:100;" onclick="window.location=this.href">&nbsp;</a>');
	jQuery('#service-tab-2').append('<a href="/embroidery/" style="position:absolute;width:100%;z-index:100;" onclick="window.location=this.href">&nbsp;</a>');
	jQuery('#service-tab-3').append('<a href="/dtg-direct-to-garment-shirt-printing-los-angeles/" style="position:absolute;width:100%;z-index:100;" onclick="window.location=this.href">&nbsp;</a>');
	jQuery(jQuery('#service-tab-4')).append('<a href="/wholesale-water-based-transfer-printing-los-angeles/" style="position:absolute;width:100%;z-index:100;" onclick="window.location=this.href">&nbsp;</a>');
	jQuery(jQuery('#service-tab-5')[0]).append('<a href="/custom-dyed-apparel-hoodies-shirts-sweatpants/" style="position:absolute;width:100%;z-index:100;" onclick="window.location=this.href">&nbsp;</a>');
	jQuery(jQuery('#service-tab-6')[0]).append('<a href="/finishing-services/" style="position:absolute;width:100%;z-index:100;" onclick="window.location=this.href">&nbsp;</a>');
    
    jQuery(jQuery('#product-tab-1')[0]).append('<a href="/custom/hats/" style="position:absolute;width:100%;z-index:100;" onclick="window.location=this.href">&nbsp;</a>');
	jQuery('#product-tab-2').append('<a href="/custom/t-shirts/" style="position:absolute;width:100%;z-index:100;" onclick="window.location=this.href">&nbsp;</a>');
	jQuery('#product-tab-3').append('<a href="/custom/jackets/" style="position:absolute;width:100%;z-index:100;" onclick="window.location=this.href">&nbsp;</a>');
	jQuery(jQuery('#product-tab-4')).append('<a href="/custom/hoodies-sweaters/" style="position:absolute;width:100%;z-index:100;" onclick="window.location=this.href">&nbsp;</a>');
	jQuery(jQuery('#product-tab-5')[0]).append('<a href="/custom/leggings-sweatpants/" style="position:absolute;width:100%;z-index:100;" onclick="window.location=this.href">&nbsp;</a>');
	jQuery(jQuery('#product-tab-6')[0]).append('<a href="/custom/tote-bags/" style="position:absolute;width:100%;z-index:100;" onclick="window.location=this.href">&nbsp;</a>');
    
    // temp - DTG to Digital
	jQuery('#front_print_type option[value="DTG"]').text('Digital');
	jQuery('#back_print_type option[value="DTG"]').text('Digital');
	jQuery('#left_print_type option[value="DTG"]').text('Digital');
	jQuery('#right_print_type option[value="DTG"]').text('Digital');
	jQuery('#neck_print_type option[value="DTG"]').text('Digital');
	
}) // end on load




/*jQuery(document).ready(function(jQuery) {
	jQuery(".more-customi-img-bx").each(function() {
	  var link = jQuery(this).find("a").attr("href");
	  jQuery(this).wrapInner('<a href="' + link + '"></a>');
	});
  
	jQuery(".more-customi-img-bx").click(function() {
	  var link = jQuery(this).find("a").attr("href");
	  window.location = link;
	});
  });*/


jQuery(document).ready(function(jQuery) {
	jQuery(".nav__link").each(function() {
	  var link = jQuery(this).find("a").attr("href");
	  jQuery(this).wrapInner('<a href="' + link + '"></a>');
	});
  
	jQuery(".nav__link").click(function() {
	  var link = jQuery(this).find("a").attr("href");
	  window.location = link;
	});
  });
  