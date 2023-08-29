/*
Plugin Name: KDTLA
Plugin URI: kdtla
Description: Customized WooCommerce for DTLAPrint (multiple colors, quantities, print options, pricing etc.).
Author: Konrad G
Author URI: www.kgretk.com
Copyright: dtlaprint.com, 2023
File: kdtla.js v 1.26
*/


( function ( $ ) {

    console.log('kdtla v01 start ...');
    //$ = jQuery;

	var debug = 1; // temp

	
	var ktoken = ''; //'333'; //temp
	var k_session = ''; //'abcde';

	var artwork_path = '/wp-content/uploads/artwork/';

	if ( typeof(plugin_url)=='undefined' ) { plugin_url = '/error'; return; }

	var kupl_url  = plugin_url + '/kupload.php';
	var krbg_url  = plugin_url + '/kbg.php';
	var ktoken_url  = plugin_url + '/ktoken.php';
	//var kconv_url = plugin_url + '/kzconvert.php';
	// site_url		- defined in /kdtla/1_templates/dtla_template_product.php
	// plugin_url	- defined in /kdtla/1_templates/dtla_template_product.php


	// allowed types: GIF, JPG, PNG, WEBP ( HEIC not supported )
	// allowed but converted: SVG, PDF, PSD, TIFF, EPS, AI
	const validImageTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/webp'];
	const validTypes =  [ 'image/svg+xml', 'application/pdf', 'application/x-photoshop', 'image/tiff', 'application/postscript' ];

	// max file size
	const max_file_size = 11000000;

	// MOQ
	const sp_moq = 25;
	const emb_moq = 12;
	const fin_moq = 50; // for 3 finishings: if ( fin=='ht' || fin=='wml' || fin=='whl' )
	// product_moq defined in template_product
	


	//var multi_colors = $('#multi_colors').prop('checked') || false;


	// INIT PART 1 (part 2 at the end)

	var k_per_item_price = 0;
	var fin_all_cost = 0;
	var prod_price = 0;
	var qt = 0;
	var ordering_blank = 0;

	const k_price = new Intl.NumberFormat("en-US", {
		style: "currency",
		currency: "USD",
		minimumFractionDigits: 2
	});

	// set first color, if not editing
	var colors_selected = 0;
	var flex_first = 0;
	var kcolors = ['', '', '', '', ''];
	var kcolors_html = ['', '', '', '', ''];

	var kstyles = ['', '', '', '', ''];

	var art_files_no = 0;


	if ( k_editing == 0 ) {
		var first_color = $('.k_colors_main .kcolor_box')[0];
		$(first_color).addClass('kcolor_box_selected');
		$($('.kcolor_box2')[0]).css('background-color', $(first_color).css('background-color') );
		$($('.c_name')[0]).val( $(first_color).attr('data') );
		$($('.c_html')[0]).val( $(first_color).css('background-color') );
		
		kcolors[0] = $(first_color).attr('data');
		kcolors_html[0] = $(first_color).css('background-color');
	

		// set default quantity
		//set_def_q(1); 


		// artwork popup 
		var current_canvas = 0;
		var canvas1 = null;
		var canvas2 = null;
		var canvas3 = null;
		var canvas4 = null;

		//clear artwork files
		update_art_files(1);
		update_art_files(2);
		update_art_files(3);
		update_art_files(4);
		update_art_files(5);

		//clear artwork preview
		$('#art1_preview').val('');
		$('#art2_preview').val('');
		$('#art3_preview').val('');
		$('#art4_preview').val('');
		$('#art5_preview').val('');

		// clear style id
		$('#style1').val('');
		$('#style2').val('');
		$('#style3').val('');
		$('#style4').val('');
		$('#style5').val('');

		// set first style
		$('#style1').val($(first_color).attr('data-style'));
		kstyles[0] = $(first_color).attr('data-style');

	}

	// set delivery time, initial

	$('#k_delivery_dates').val(delivery_std);
	if (debug) console.log(' delivery: '+delivery_std);


	// STRUCTURE INTERACTIONS, ACCORDION v2

	$('#div_kcolors_trig').click(function() {
		if ( $('#div_kcolors:visible').length ) {
			$('#div_kcolors_trig_arrow').css('transform','rotate(0deg)');
			$('#div_kcolors').hide('fast');
		}
		else {
			$('#div_kcolors_trig_arrow').css('transform','rotate(-90deg)');
			$('#div_kcolors').show('fast');
			// v2 +
			$('#div_kdecor').hide('fast'); $('#div_kfin').hide('fast'); $('#div_knotes').hide('fast');
		}	
	});
	$('#div_kdecor_trig').click(function() {
		if ( $('#div_kdecor:visible').length ) {
			$('#div_kdecor_trig_arrow').css('transform','rotate(0deg)');
			$('#div_kdecor').hide('fast');
		}
		else {
			$('#div_kdecor_trig_arrow').css('transform','rotate(-90deg)');
			$('#div_kdecor').show('fast');
			// v2 +
			$('#div_kcolors').hide('fast');  $('#div_kfin').hide('fast');  $('#div_knotes').hide('fast');
		}	
	});
	$('#div_kfin_trig').click(function() {
		if ( $('#div_kfin:visible').length ) {
			$('#div_kfin_trig_arrow').css('transform','rotate(0deg)');
			$('#div_kfin').hide('fast');
		}
		else {
			$('#div_kfin_trig_arrow').css('transform','rotate(-90deg)');
			$('#div_kfin').show('fast');
			// v2 +
			$('#div_kcolors').hide('fast'); $('#div_kdecor').hide('fast'); $('#div_knotes').hide('fast');
		}	
	});
	$('#div_knotes_trig').click(function() {
		if ( $('#div_knotes:visible').length ) {
			$('#div_knotes_trig_arrow').css('transform','rotate(0deg)');
			$('#div_knotes').hide('fast');
		}
		else {
			$('#div_knotes_trig_arrow').css('transform','rotate(-90deg)');
			$('#div_knotes').show('fast');
			// v2 +
			$('#div_kcolors').hide('fast'); $('#div_kdecor').hide('fast'); $('#div_kfin').hide('fast'); 
		}	
	});

	/* NEXT buttons */
	$('#kcolors_next').click(function() {
		$('#div_kcolors_trig_arrow').css('transform','rotate(0deg)');
		$('#div_kcolors').hide('fast');
		$('#div_kdecor_trig_arrow').css('transform','rotate(-90deg)');
		$('#div_kdecor').show('slow');
	});
	$('#kdecor_next').click(function() {
		$('#div_kdecor_trig_arrow').css('transform','rotate(0deg)');
		$('#div_kdecor').hide('fast'); 
		$('#div_kfin_trig_arrow').css('transform','rotate(-90deg)');
		$('#div_kfin').show('slow');
	});
	$('#kfin_next').click(function() {
		$('#div_kfin_trig_arrow').css('transform','rotate(0deg)');
		$('#div_kfin').hide('fast'); 
		$('#div_knotes_trig_arrow').css('transform','rotate(-90deg)');
		$('#div_knotes').show('slow');
	})

	/* BLANK SAMPLES INFO */
	/* #order-blank-samples - checkbox OR #order-blank-samples-button - button */
	$('#decorated').change(function() { // checkbox
		//$('#order-blank-samples-button').click(function() { // for button
			//if ($('#blank_samples_info:visible').length ) { // for button
			if ( $('#decorated').prop('checked') ) { // 1 for decorated - // checkbox
			$('#order-blank-samples-button').removeClass('selected');
			$('#blank_samples_info').hide('fast'); 
			$('#blank_samples_info_fin').hide('fast'); 
			$('#order-blank-samples-button').text('Order Blank Samples');
					$('#div_kcolors').show('fast');
					$('#kcolors_next').css('visibility','');
			$('#table_d').show('fast');
			$('.k_finishing').show('fast');
			$('#kdecor_next').css('visibility','');
			$('#div_kdecor_trig').show();
				//$('#div_kdecor').show();
			$('#div_kfin_trig').show(); 
			//$('#div_kfin').show(); 
			$('#div_knotes_trig').show();
			//$('#div_knotes').show();
			ordering_blank = 0;
			$('#ordering_blanks').val( 'no' );
				$('#decorated-2').text('Decorated');
			
			if (debug) console.log(' last prod price=' + prod_price );

			// update price with decorations + finishings
			$('#k_per_item').val( Math.round( ( k_per_item_price + fin_all_cost ) * 100 ) / 100 );
			$('#k_total_price').val ( Math.round( ( (k_per_item_price + fin_all_cost) * qt) * 100 ) / 100 );

			$('#k_per_item_span').text( k_price.format( Math.round( ( k_per_item_price + fin_all_cost ) * 100 ) / 100 ));
			$('#k_total_price_span').text( k_price.format( Math.round( ( (k_per_item_price + fin_all_cost) * qt) * 100 ) / 100 ));

		}
		else {
			$('#decorated-2').text('Blank');
			$('#order-blank-samples-button').addClass('selected');
			$('#blank_samples_info').show('fast'); 
			//$('#blank_samples_info_fin').show('fast'); 
			$('#order-blank-samples-button').text('Order With Logo'); // TODO text to variable?
				$('#div_kcolors').show('fast');
				$('#kcolors_next').css('visibility','hidden');
			$('#kdecor_next').css('visibility','hidden');

			$('#table_d').hide('fast');
			$('.k_finishing').hide('fast');
			$('#div_kdecor_trig').hide(); 
					$('#div_kdecor').hide();
			$('#div_kfin_trig').hide(); 
			$('#div_kfin').hide(); 
			$('#div_knotes_trig').hide();
			$('#div_knotes').hide();
			ordering_blank = 1;
			$('#ordering_blanks').val( 'yes' );

			if (debug) console.log(' last prod price=' + prod_price );
			// modify price to product only
			$('#k_per_item').val( prod_price );
			$('#k_total_price').val ( Math.round( ( prod_price * qt) * 100 ) / 100 );

			$('#k_per_item_span').text( k_price.format( prod_price ) );
			$('#k_total_price_span').text( k_price.format( Math.round( ( prod_price * qt) * 100 ) / 100 ));


			//TODO clear finishings?

		}

		// SHIPPING text update
		if ( $('#k_total_price').val()*1 > 1500) {
			$('#free_shipping_info').text('Free Shipping');
		} else {
			$('#free_shipping_info').text('Shipping Calculated in Cart');
		}

		// K_STICKY info update
		if ( typeof($('#ks_total'))!=='undefined' ) {
			$('#ks_total').text( $('#k_total_price_span').text() );
			$('#ks_q').text( $('#quantity_total2').val() );
			$('#ks_price').text( $('#k_per_item_span').text() );
			$('#ks_del').text( $('#k_delivery_dates').val() );
		}

		// MOQ info update
		moq_warn();
		// clear warnings
		clear_warn();

	});
	
	// set default quantities for a color - no 8/8
	function set_def_q( r ) {

		// set default quantities - 0 is S, 1 is M, 2 is L ... length=1 for OS  //if (debug) console.log('q visible = '+ $('#table_q .q1:visible').length);
		if ( k_editing == 0 ) {
			if ($('#table_q .q1:visible').length == 1) 
				$($($('#table_q tr')[r]).find('.q1')[0]).val(100);
			if ($('#table_q .q1:visible').length == 2) {
				$($($('#table_q tr')[r]).find('.q1')[0]).val(50);
				$($($('#table_q tr')[r]).find('.q1')[1]).val(50);
			}
			if ($('#table_q .q1:visible').length > 2) {
				$($($('#table_q tr')[r]).find('.q1')[0]).val(25);
				$($($('#table_q tr')[r]).find('.q1')[1]).val(25);
				$($($('#table_q tr')[r]).find('.q1')[2]).val(25);
				$($($('#table_q tr')[r]).find('.q1')[3]).val(25);
				
			}
			if (debug) console.log(' SET default quantities');
		} 
		//zero quantities for hidden colors - in line 966, calc_quantity_total()

	}


	// for artwork load to canvas
	function readImage(imgFile, canvas) {
		// defined at the top of the file
		// allowed types: GIF, JPG, PNG, WEBP ( HEIC not supported )
		// allowed but converted: SVG, PDF, PSD, TIFF, EPS, AI
		//const validImageTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/webp'];
		//const validTypes =  [ 'image/svg+xml', 'application/pdf', 'application/x-photoshop', 'image/tiff', 'application/postscript' ];

		//	if(!imgFile.type.match(/image.*/)) {
		//		console.log("The dropped file is not an image: ", imgFile.type);
		//		return;
		//	}

			// checking file type
			if ( !validImageTypes.includes(imgFile.type) ) { //&& !validTypes.includes(imgFile.type) ) {
				console.log("ERROR 0: File type not allowed! ", imgFile.type);
				return;
			}
			// exclude HEIC ...
			if(imgFile.type == 'image/heic') {
				console.log("Sorry, HEIC images are not supported... ", imgFile.type);
				return;
			}

			
			var remove_bg = $('#rebackground').prop('checked') || false; //remove_bg

			if (debug) console.log('remove bg? ' + remove_bg);
			if (debug) console.log('- - type=' + imgFile.type);


			// file number
			//art_files_no++;


			var reader = new FileReader();

			// regular upload, no bg removal
			if ( !remove_bg ) {
				reader.readAsDataURL(imgFile);
				reader.onload = function(e){
					

					var img = new Image();

					img.src = e.target.result; 	
					

					if (debug) console.log(' reading file: '+imgFile.name + ' no='+art_files_no);

					var HideControls = {
						'tl':true, 
						'tr':false, //top right corner is not visible, for delete button
						'bl':true,
						'br':true,
						'ml':true,
						'mt':true,
						'mr':true,
						'mb':true,
						'mtr':true
					};
					
					// full paths in filename correction
					if (imgFile.name.split('/').length>0) imgFile.name = imgFile.name.split('/')[imgFile.name.split('/').length -1];
					if (debug) console.log(' reading file2: '+imgFile.name + ' no='+art_files_no);

					img.onload = function() {
						
						var imgInstance = new fabric.Image(this, {
							left: canvas.width/3, //Math.random()*canvas.width*0.8+0.1,
							top: canvas.height/3, //Math.random()*canvas.height*0.8+0.1,
							//angle: Math.random()*20-10,
							opacity: 1,
							borderColor:'#30f88a',
							cornerColor:'#30f88a',
							cornerSize:20,
							transparentCorners:false,
							name: imgFile.name + art_files_no
						});
						imgInstance.scale((canvas.width/img.width)/3);
						imgInstance.setControlsVisibility(HideControls);
						canvas.add(imgInstance);

						if (debug) console.log(' curr canvas='+current_canvas+' w='+canvas.width + ' scale='+(canvas.width/img.width)/3);

						art_files_no++;
					}
					
					add_delete_button( canvas );
					

					//create filenames
					add_art_files(current_canvas, imgFile);

					// add art file names to hidden field
					update_art_files(current_canvas);

				};
				
			}


			// upload to remove bg only if checked
			if (remove_bg) {

				// show animation
				$('.kdtla_images').append('<div class="dtla_loader2"><img src="/wp-content/uploads/2023/05/dtla-loader.gif"></div>');

				side = 'no_side';
				if ( current_canvas == 1 ) side = 'front';
				if ( current_canvas == 2 ) side = 'back';
				if ( current_canvas == 3 ) side = 'left';
				if ( current_canvas == 4 ) side = 'right';
				if ( current_canvas == 5 ) side = 'neck'; // readimage not used for neck
				

				reader.readAsDataURL(imgFile); //readAsArrayBuffer ?
				reader.onload = function(e){
					//console.log('target=' + e.target.result); //data:image/jpeg;base64, ...


					$.ajax({
						url: krbg_url + '?s=' + k_session +  '&c=0&ktoken=' + $('#ktoken').val() + '&side=' + side + '&filename=' + imgFile.name ,
						type: 'POST',
						responseType : "blob",
						data: { name: 'form_image1', value: e.target.result },
					
						success: function(data, message, jq ) {
							
							if (debug) console.log('(remove bg) from ajax: ' + data.length + ' mess=' + message ); //+ ' jq=' + JSON.stringify(jq) );

							var HideControls = {
								'tl':true, 
								'tr':false, //top right corner is not visible, for delete button
								'bl':true,
								'br':true,
								'ml':true,
								'mt':true,
								'mr':true,
								'mb':true,
								'mtr':true
							};

							// full paths in filename correction
							if (imgFile.name.split('/').length>0) imgFile.name = imgFile.name.split('/')[imgFile.name.split('/').length -1];

								var img = new Image();
								img.onload = function() {
									
									var imgInstance = new fabric.Image(this, {
										left: canvas.width/3, //Math.random()*canvas.width*0.8+0.1,
										top: canvas.height/3, //Math.random()*canvas.height*0.8+0.1,
										//angle: Math.random()*20-10,
										opacity: 1,
										borderColor:'#30f88a',
										cornerColor:'#30f88a',
										cornerSize:24,
										transparentCorners:false,
										name: imgFile.name + art_files_no
									});
									imgInstance.scale((canvas.width/img.width)/3);
									imgInstance.setControlsVisibility(HideControls);
									canvas.add(imgInstance);

									art_files_no++;
								}
								img.src = data;

								add_delete_button( canvas );

								//$('#img_ajax' + current_canvas)[0].src = data;
								$('#artwork-img-prev').html('<img src="'+ data +'">');

								// remove animation
								$('.dtla_loader2').remove();

								// next image - don't remove bg again
								$('#rebackground').prop('checked',false);
							
							//return data;
						},
						error: function(xhr, status, error) {
							alert('Error! : '+error + ' (' + xhr.responseText  + ')' ); // + xhr.responseJSON.error 
							if (debug) console.log(' Error: ' + xhr.responseText + ' status=' + status + ' err=' + error);

							// remove animation
							$('.dtla_loader2').remove();
						}
					});
							
					//create filenames
					add_art_files(current_canvas, imgFile);

					// add art file names to hidden field
					update_art_files(current_canvas);
					
				};
				
			}



			if (debug) console.log('readimage, img loading...');
			
			// not used. canvas-container set to absolute position
			//	$($('.kdtla_images figure img:not(.upload_artwork)')[current_canvas]).hide(); //hide image so canvas shows up (0 for model...)
			

	}; //end readimage



	// for artwork load to canvas, converted
	function readConverted(img_data, canvas, img_name, img_type ) {
		// defined at the top of the file
		// allowed types: GIF, JPG, PNG, WEBP ( HEIC not supported )
		// allowed but converted: SVG, PDF, PSD, TIFF, EPS, AI
		//const validImageTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/webp'];
		//const validTypes =  [ 'image/svg+xml', 'application/pdf', 'application/x-photoshop', 'image/tiff', 'application/postscript' ];

		if ( typeof(canvas)=='undefined' || typeof(canvas)=='null' || canvas==null ) return;

			// checking file type
			if ( !validTypes.includes(img_type) ) {
				console.log("ERROR 1: File type not allowed! ", img_type);
				return;
			}
			

			
				
					
					var img = new Image();

					if (debug) console.log(' reading converted file: '+img_name + ' no='+art_files_no);

					var HideControls = {
						'tl':true, 
						'tr':false, //top right corner is not visible, for delete button
						'bl':true,
						'br':true,
						'ml':true,
						'mt':true,
						'mr':true,
						'mb':true,
						'mtr':true
					};
					
					// full paths in filename correction - maybe not needed here
					if (img_name.split('/').length>0) img_name = img_name.split('/')[img_name.split('/').length -1];

					img.onload = function() {
						var imgInstance = new fabric.Image(this, {
							left: canvas.width/3, //Math.random()*canvas.width*0.8+0.1,
							top: canvas.height/3, //Math.random()*canvas.height*0.8+0.1,
							//angle: Math.random()*20-10,
							opacity: 1,
							borderColor:'#30f88a',
							cornerColor:'#30f88a',
							cornerSize:20,
							transparentCorners:false,
							name: img_name + art_files_no
						});
						imgInstance.scale((canvas.width/img.width)/3);
						imgInstance.setControlsVisibility(HideControls);
						canvas.add(imgInstance);


						// file number
						art_files_no++;

						//if (debug) console.log(' curr canvas'+current_canvas+' w='+canvas.width + ' scale='+(canvas.width/img.width)/3);

					}
					img.src = img_data;

					var obj = new Object();
					obj.name = img_name;

					add_delete_button( canvas );

					//create filenames
					add_art_files(current_canvas, obj);

					// add art file names to hidden field
					update_art_files(current_canvas);
				


	}; //end readconverted

	function add_delete_button( canvas ) {

		if ( typeof(canvas)=='undefined' || typeof(canvas)=='null' || canvas==null ) return;

					// delete image button
					function addDeleteBtn(x, y){
						$(".deleteBtn").remove(); 
						var btnLeft = x-12;
						var btnTop = y-12;
						var deleteBtn = '<span class="deleteBtn" style="position:absolute;top:'+btnTop+'px;left:'+btnLeft+'px;">X</span>';
						$($(".canvas-container")[current_canvas-1]).append(deleteBtn);
					}
					
					canvas.on('object:selected',function(e){
							addDeleteBtn(e.target.oCoords.tr.x, e.target.oCoords.tr.y);
					});
					
					
					canvas.on('mouse:down',function(e){
						if(!canvas.getActiveObject())
						{
							$(".deleteBtn").hide(); 
						}
					});
					canvas.on('mouse:up',function(e){
						if(canvas.getActiveObject())
						{
							$(".deleteBtn").css('top',e.target.oCoords.tr.y - 12); 
							$(".deleteBtn").css('left',e.target.oCoords.tr.x - 12); 
							$(".deleteBtn").show(); 
						}
					});
					
					
					canvas.on('object:modified',function(e){
						$(".deleteBtn").css('top',e.target.oCoords.tr.y - 12); 
						$(".deleteBtn").css('left',e.target.oCoords.tr.x - 12); 
					});
					
					canvas.on('object:moving',function(e){
						$(".deleteBtn").hide(); 
					});
					
					canvas.on('object:scaling',function(e){
						$(".deleteBtn").hide(); 
					});
					canvas.on('object:rotating',function(e){
						$(".deleteBtn").hide(); 
					});
					
					$(document).on('click',".deleteBtn",function(){
						if(canvas.getActiveObject())
						{
							if (debug) console.log('removed '+canvas.getActiveObject().name + ' from canvas=' + current_canvas );
							$('#art_files'+current_canvas+' [data-item="'+canvas.getActiveObject().name+'"]').remove();
							canvas.remove(canvas.getActiveObject());
							$(".deleteBtn").remove();
						}
					});

	} // end delete_button



	// add image, for editing
	function addImage( imgurl, imgname, canvas, current_canvas, file_no = null) {

		//set_canvas_backgrounds(current_canvas);

		//check if need to add an image for converted file, based on file extension
		var f_ext = imgurl.substr(imgurl.length-3,imgurl.length); // png, gif, jpg, (j)peg, (w)ebp
		if ( !['png','gif','jpg','peg','ebp'].includes(f_ext) ) {
			// .ai 
			if (f_ext=='.ai')
				imgurl = imgurl.substr(0,imgurl.length-3) + '.png';
			else
				imgurl = imgurl.substr(0,imgurl.length-3) + 'png';

			// TODO.tiff ?

			if (debug) console.log('editing, file was converted:'+imgurl);
		}

		fabric.Image.fromURL( imgurl, function(myImg) {
			var img1 = myImg.set({
				left: canvas.width/3,
				top: canvas.height/3,
				opacity: 1,
				borderColor:'#30f88a',
				cornerColor:'#30f88a',
				cornerSize:20,
				transparentCorners:false,
				name: imgname + file_no //art_files_no
			});
			myImg.scaleToHeight(200);
			canvas.add(myImg); 
		});

		$($('.flex-control-thumbs li img')[current_canvas]).click();

		add_delete_button( canvas ); // controls too ?
			
		//$($('.kdtla_images figure img:not(.upload_artwork)')[current_canvas]).hide();


	} // end addImage


	// save artwork file on the server, and converted
	function upload_file(el, side, canvas = null ) {

		if (debug) console.log(' - uploading: '+el.name);

		// dtla loader image
		$('#artwork-img-prev').html('<img src="/wp-content/uploads/2023/05/dtla-loader.gif">');

		// uncomment if multiple files
		//$($('#file1')[0].files).each(function(i,el) {
			if (debug) console.log('...input: file name = ' + el.name + ' size='+el.size);

		//	if(!el.type.match(/image.*/)) {
		//		console.log("The file is not an image! ", el.type);
		//		return;
		//	}

			// checking file type
			if ( !validImageTypes.includes(el.type) && !validTypes.includes(el.type) ) {
				console.log("ERROR 2: File type not allowed! ", el.type);
				return;
			}
			// exclude HEIC ...
			if(el.type == 'image/heic') {
				console.log("Sorry, uploading HEIC images is not supported... ", el.type);
				return;
			}

			
			//readImage(el);  // no because base64

			var kform = new FormData();
			//kform.append('ktoken', $('#ktoken').val() );
			kform.append('f1', el ); //$("#file1").prop("files")[0] );
			

			$.ajax({
				url: kupl_url + '?s=' + k_session + '&side=' + side + '&c=0&ktoken=' + $('#ktoken').val(),
				type: 'POST',
				responseType : "json",
				//data: { name: 'form_image1', value: e.target.result, token: ktoken },

				processData: false, // important
				contentType: false, // important
				dataType : 'json',
				data: kform,
				
			
				success: function(data, message, jq ) {
					// message?
					if (debug) console.log('from ajax: ' + data.length + ' result=' + jq.responseJSON.result  + ' error=' + jq.responseJSON.error ); //+ ' jq=' + JSON.stringify(jq) );
					if ( jq.responseJSON.converted.length>0 ) {
						if (debug) console.log('.... converted! ');
						
						$('#artwork-img-prev').html('<img src="'+ jq.responseJSON.converted +'">');
						if ( typeof(canvas)!=='undefined' )
							readConverted(jq.responseJSON.converted, canvas, el.name, el.type );

					}

					//$('#im1').attr('src', jq.responseJSON.converted);
					
					//return data;

					// remove animation
					$('.dtla_loader1').remove();
				},
				error: function(xhr, status, error) {
					alert('Error occured: '+error + ' (' + xhr.responseJSON.error + ')' );
					if (debug) console.log(' Error: ' + xhr.responseText + ' status=' + status + ' err=' + error);

					// remove animation
					$('.dtla_loader1').remove();
				}
			});
			
		//});	
	};

	// save canvas preview on the server
	function upload_file_canvas(dataURL, side ) {

		var deferred = $.Deferred();
		if (debug) console.log(' -  - canvas uploading - ' + side);

		// update preview file name in hidden field
		// FULL PATH or just canvas-front.png
		if (side == 'front') $('#art1_preview').val( site_url + artwork_path + k_session + '/preview/canvas-front.png');
		if (side == 'back')  $('#art2_preview').val( site_url + artwork_path + k_session + '/preview/canvas-back.png');
		if (side == 'left')  $('#art3_preview').val( site_url + artwork_path + k_session + '/preview/canvas-left.png');
		if (side == 'right') $('#art4_preview').val( site_url + artwork_path + k_session + '/preview/canvas-right.png');
		// TODO maybe not needed...

		//$($('#file1')[0].files).each(function(i,el) {
			//console.log('...input: file[' + i + '].name = ' + el.name + ' size='+el.size);
			//console.log('...input: file name = ' + el.name + ' size='+el.size);


			$.ajax({
				url: kupl_url + '?s=' + k_session + '&side=' + side + '&c=1&ktoken=' + $('#ktoken').val(),
				//headers: { 'Content-Type': 'application/octet-stream' },
				contentType: "application/octet-stream",
				type: 'POST',
				data: dataURL.split(',')[1],
				responseType : "json",

				processData: false, // important
				contentType: false, // important
				
				success: function(data, message, jq ) {
					if (debug) console.log('z ajax: result=' + jq.responseJSON.result  + ' error=' + jq.responseJSON.error ); //+ ' jq=' + JSON.stringify(jq) ); ' + data.length + '
					//return jq.responseJSON.result;
					
					//return deferred.promise();
				},
				error: function(xhr, status, error) {
					alert('Error occured: '+error + ' (' + xhr.responseJSON.error + ')' ); //xhr.responseJSON.error
					console.log(' Error: ' + xhr.responseText + ' status=' + status + ' err=' + error);
				}
			}).done(function(e) {
					
					// return deferred object
					if (debug) console.log(' done. ');
					
					deferred.resolve();
					//return deferred.promise();
			});
			
		//});
		return deferred.promise();
	};


	// add file names under artwork buttons
	function add_art_files(current_canvas, imgFile, file_no = null) {

		// when editing
		if ( file_no==0 || file_no>0 ) {
			art_files_no = file_no;
			if (debug) console.log('add art = ' + art_files_no);
		}
			
		// full paths in filename correction
		if (imgFile.name.split('/').length>0) imgFile.name = imgFile.name.split('/')[imgFile.name.split('/').length -1];

		//$('#art_files' + current_canvas).append('<span title="' + imgFile.name +'" data-item="'+imgFile.name + art_files_no + '" data-no="' + art_files_no +  '" >' +imgFile.name + '</span><div data-item="'+imgFile.name + art_files_no +'" data-cc="' + current_canvas + '" ' + ' data-no="' + art_files_no + '" class="art_delete">&nbsp;</div>');

		// with font awesome
		$('#art_files' + current_canvas).append('<span title="' + imgFile.name +'" data-item="'+imgFile.name + art_files_no + '" data-no="' + art_files_no +  '" > <i class="fa-solid fa-image"></i> ' +imgFile.name + '</span><div data-item="'+imgFile.name + art_files_no +'" data-cc="' + current_canvas + '" ' + ' data-no="' + art_files_no + '" class="art_delete">&nbsp; <i class="fa fa-trash"></i> </div>');


		//delete filename and delete icon after click
		$('.art_delete').click(function(){
			var filename = $(this).data('item');
			var cc = $(this).data('cc'); // canvas to delete from
			var fno = $(this).data('no'); //filename number
			
			if (cc == 1) canvas1.getObjects().forEach(function(el,i){ if ( el.name == filename ) canvas1.remove(el); } );
			if (cc == 2) canvas2.getObjects().forEach(function(el,i){ if ( el.name == filename ) canvas2.remove(el); });
			if (cc == 3) canvas3.getObjects().forEach(function(el,i){ if ( el.name == filename ) canvas3.remove(el); });
			if (cc == 4) canvas4.getObjects().forEach(function(el,i){ if ( el.name == filename ) canvas4.remove(el); });

			if (debug) console.log(' removing '+filename + ' from canvas ' + cc);
			//$(this).parent().find("span[title='" + filename + "'][data-no='" + fno +"']").remove(); 
			$(this).parent().find("span[data-item='" + filename + "']").remove(); 
			update_art_files(cc);
			$(this).remove(); 
		});

		// force decoration if file uploaded
		force_decor();
	}


	// art files names in hidden fields from span
	function update_art_files(c) {
		// c = current_canvas
		if ( c == 1 ) side = 'front';
		if ( c == 2 ) side = 'back';
		if ( c == 3 ) side = 'left';
		if ( c == 4 ) side = 'right';
		if ( c == 5 ) side = 'neck';

		var a1 = ''; 
		$('#art_files' + c + ' span').each(function(){ 
			//a1 = a1 + this.title + '|'; // 6/21 added: site_url + artwork_path + k_session
			a1 = a1 +  site_url + artwork_path + k_session + '/' + side + '/' + this.title + '|';
		});
		$('#art' + c + '_files').val(a1);
		a1 = '';
	}


	// set canvas backgrounds
	// 0 then all of them, or 1,2,3,4 for each one
	function set_canvas_backgrounds( cc = null ) {
		
		//if ( typeof(canvas1)=='undefined' || typeof(canvas1)=='null' || canvas1 == null ) return;
		var left2 = 0;

		if ( cc == 1 || cc == 0 ) {
			curr_h = $('.kdtla_images figure img:not(.upload_artwork)')[1].naturalHeight; //naturalWidth;
			//curr_w = $('.kdtla_images figure img:not(.upload_artwork)')[1].naturalWidth;
			scx = canvas1.height / curr_h;
			scy = scx; // canvas1.width / curr_w;
			//sc = Math.max(scx, scy);
			
			if ($('.kdtla_images figure img:not(.upload_artwork)')[1].naturalWidth < $('.kdtla_images figure img:not(.upload_artwork)')[1].width)
				left2 = ( $('.kdtla_images figure img:not(.upload_artwork)')[1].width - $('.kdtla_images figure img:not(.upload_artwork)')[1].naturalWidth ) /2;

			curr = $('.kdtla_images figure img:not(.upload_artwork)')[1].src;
			canvas1.setBackgroundImage(curr, canvas1.renderAll.bind(canvas1), {
				crossOrigin: 'anonymous', //width: canvas1.width, height: canvas1.height, 
				originX: 'left', originY: 'top', scaleX: scx, scaleY: scy, left: left2
			});
		
			if ($($('.canvas-container')[1]).height() < curr_h ) 
				$($('.kdtla_images figure img:not(.upload_artwork)')[1]).css('opacity',0);
			else
				$($('.kdtla_images figure img:not(.upload_artwork)')[1]).css('opacity',1);

			
		}
		if ( cc == 2 || cc == 0) {
			curr_h = $('.kdtla_images figure img:not(.upload_artwork)')[2].naturalHeight;
			scx = canvas2.height / curr_h;
			scy = scx;

			if ($('.kdtla_images figure img:not(.upload_artwork)')[2].naturalWidth < $('.kdtla_images figure img:not(.upload_artwork)')[2].width)
				left2 = ( $('.kdtla_images figure img:not(.upload_artwork)')[2].width - $('.kdtla_images figure img:not(.upload_artwork)')[2].naturalWidth ) /2;

			curr = $('.kdtla_images figure img:not(.upload_artwork)')[2].src;
			canvas2.setBackgroundImage(curr, canvas2.renderAll.bind(canvas2), {
				crossOrigin: 'anonymous', //width: canvas1.width, height: canvas1.height, 
				originX: 'left', originY: 'top', scaleX: scx, scaleY: scy, left: left2
			});
		
			if ($($('.canvas-container')[2]).height() < curr_h ) 
				$($('.kdtla_images figure img:not(.upload_artwork)')[2]).css('opacity',0);
			else
				$($('.kdtla_images figure img:not(.upload_artwork)')[2]).css('opacity',1);
		}
		if ( cc == 3 || cc == 0) {
			curr_h = $('.kdtla_images figure img:not(.upload_artwork)')[3].naturalHeight;
			scx = canvas3.height / curr_h;
			scy = scx;

			if ($('.kdtla_images figure img:not(.upload_artwork)')[3].naturalWidth < $('.kdtla_images figure img:not(.upload_artwork)')[3].width)
				left2 = ( $('.kdtla_images figure img:not(.upload_artwork)')[3].width - $('.kdtla_images figure img:not(.upload_artwork)')[3].naturalWidth ) /2;

			curr = $('.kdtla_images figure img:not(.upload_artwork)')[3].src;
			canvas3.setBackgroundImage(curr, canvas3.renderAll.bind(canvas3), {
				crossOrigin: 'anonymous', //width: canvas1.width, height: canvas1.height, 
				originX: 'left', originY: 'top', scaleX: scx, scaleY: scy, left: left2
			});
		
			if ($($('.canvas-container')[3]).height() < curr_h ) 
				$($('.kdtla_images figure img:not(.upload_artwork)')[3]).css('opacity',0);
			else
				$($('.kdtla_images figure img:not(.upload_artwork)')[3]).css('opacity',1);
		}
		if ( cc == 4 || cc == 0) {
			curr_h = $('.kdtla_images figure img:not(.upload_artwork)')[4].naturalHeight;
			scx = canvas4.height / curr_h;
			scy = scx;

			if ($('.kdtla_images figure img:not(.upload_artwork)')[4].naturalWidth < $('.kdtla_images figure img:not(.upload_artwork)')[4].width)
				left2 = ( $('.kdtla_images figure img:not(.upload_artwork)')[4].width - $('.kdtla_images figure img:not(.upload_artwork)')[4].naturalWidth ) /2;

			curr = $('.kdtla_images figure img:not(.upload_artwork)')[4].src;
			canvas4.setBackgroundImage(curr, canvas4.renderAll.bind(canvas4), {
				crossOrigin: 'anonymous', //width: canvas1.width, height: canvas1.height, 
				originX: 'left', originY: 'top', scaleX: scx, scaleY: scy, left: left2
			});
		
			if ($($('.canvas-container')[4]).height() < curr_h ) 
				$($('.kdtla_images figure img:not(.upload_artwork)')[4]).css('opacity',0);
			else
				$($('.kdtla_images figure img:not(.upload_artwork)')[4]).css('opacity',1);
		}
		if (debug) console.log(' C background='+cc+' scale='+scx+' , '+scy + ' curr_h='+curr_h + ' left='+ left2);
	}
	

	// check if all images exist, replace if not
	function check_images() {
		// thumbs
		$('.flex-control-thumbs li img').each(function(i,el){
			$.ajax({
				url: el.src,
				type:'HEAD',
				error: function() {
					if (debug) console.log('NO image...'+el.src);
					el.src='/wp-content/uploads/image_unavailable.png';
				},    
				//success: function(){  console.log('image exist='+el.src); } 
			});
		});
			
		// large images
		$('.woocommerce-product-gallery__image img').each(function(i,el){
			$.ajax({
				url: el.src,
				type:'HEAD',
				error: function() {
					if (debug) console.log('NO image...'+el.src);
					el.src='/wp-content/uploads/image_unavailable.png';
					$(el).addClass('unavailable'); //in case..
				}
			});
		});
	}


	// MOQ warnings
	// MOQ info - showing depending on total quantity and print type, only if not Blank

	function moq_warn() {
		
		// first check if Blanks
		if ( $('#decorated').prop('checked')===false ) {
			$('.single_add_to_cart_button').removeAttr('disabled'); 
			$('#ks_add').css("pointer-events", "auto");
			$('#ks_add').css("opacity", '');
			$('.single_add_to_cart_button').show();
			$('#moq_info').hide();
			if (debug) console.log(' blanks so no MOQ');
			$('#moq_info_prod').hide();
			return;
		}


		// in each side - front, back, left, right, neck
		// SP qt < 25 
		if ( qt<sp_moq && $('#front_print_type').val()=='Screen Print' ) {
			$('#d_warning_front').text(d_warn['sp']);
			$('#d_warning_front').show();
			$('.single_add_to_cart_button').attr('disabled','disabled'); 
			$('#ks_add').css("pointer-events", "none"); // for K_STICKY bar
			$('#ks_add').css("opacity", "0.5");
			$('#moq_info').text(d_warn['sp']);
			$('#moq_info').show();
		}
		
		if ( qt<sp_moq && $('#back_print_type').val()=='Screen Print' ) {
			$('#d_warning_back').text(d_warn['sp']);
			$('#d_warning_back').show();
			$('.single_add_to_cart_button').attr('disabled','disabled'); 
			$('#ks_add').css("pointer-events", "none");
			$('#ks_add').css("opacity", "0.5");
			$('#moq_info').text(d_warn['sp']);
			$('#moq_info').show();
		}
		if ( qt<sp_moq && $('#left_print_type').val()=='Screen Print' ) {
			$('#d_warning_left').text(d_warn['sp']);
			$('#d_warning_left').show();
			$('.single_add_to_cart_button').attr('disabled','disabled'); 
			$('#ks_add').css("pointer-events", "none");
			$('#ks_add').css("opacity", "0.5");
			$('#moq_info').text(d_warn['sp']);
			$('#moq_info').show();
		}
		if ( qt<sp_moq && $('#right_print_type').val()=='Screen Print' ) {
			$('#d_warning_right').text(d_warn['sp']);
			$('#d_warning_right').show();
			$('.single_add_to_cart_button').attr('disabled','disabled'); 
			$('#ks_add').css("pointer-events", "none");
			$('#ks_add').css("opacity", "0.5");
			$('#moq_info').text(d_warn['sp']);
			$('#moq_info').show();
		}
		if ( qt<sp_moq && $('#neck_print_type').val()=='Screen Print' ) {
			$('#d_warning_neck').text(d_warn['sp']);
			$('#d_warning_neck').show();
			$('.single_add_to_cart_button').attr('disabled','disabled'); 
			$('#ks_add').css("pointer-events", "none");
			$('#ks_add').css("opacity", "0.5");
			$('#moq_info').text(d_warn['sp']);
			$('#moq_info').show();
		}

		// or EMB qt < 12
		if ( qt<emb_moq && $('#front_print_type').val()=='Embroidery' ) {
			$('#d_warning_front').text(d_warn['emb']);
			$('#d_warning_front').show();
			$('.single_add_to_cart_button').attr('disabled','disabled'); 
			$('#ks_add').css("pointer-events", "none");
			$('#ks_add').css("opacity", "0.5");
			$('#moq_info').text(d_warn['emb']);
			$('#moq_info').show();
		}
		if ( qt<emb_moq && $('#back_print_type').val()=='Embroidery' ) {
			$('#d_warning_back').text(d_warn['emb']);
			$('#d_warning_back').show();
			$('.single_add_to_cart_button').attr('disabled','disabled'); 
			$('#ks_add').css("pointer-events", "none");
			$('#ks_add').css("opacity", "0.5");
			$('#moq_info').text(d_warn['emb']);
			$('#moq_info').show();
		}
		if ( qt<emb_moq && $('#left_print_type').val()=='Embroidery' ) {
			$('#d_warning_left').text(d_warn['emb']);
			$('#d_warning_left').show();
			$('.single_add_to_cart_button').attr('disabled','disabled'); 
			$('#ks_add').css("pointer-events", "none");
			$('#ks_add').css("opacity", "0.5");
			$('#moq_info').text(d_warn['emb']);
			$('#moq_info').show();
		}
		if ( qt<emb_moq && $('#right_print_type').val()=='Embroidery' ) {
			$('#d_warning_right').text(d_warn['emb']);
			$('#d_warning_right').show();
			$('.single_add_to_cart_button').attr('disabled','disabled'); 
			$('#ks_add').css("pointer-events", "none");
			$('#ks_add').css("opacity", "0.5");
			$('#moq_info').text(d_warn['emb']);
			$('#moq_info').show();
		}
		if ( qt<emb_moq && $('#neck_print_type').val()=='Embroidery' ) {
			$('#d_warning_neck').text(d_warn['emb']);
			$('#d_warning_neck').show();
			$('.single_add_to_cart_button').attr('disabled','disabled'); 
			$('#ks_add').css("pointer-events", "none");
			$('#ks_add').css("opacity", "0.5");
			$('#moq_info').text(d_warn['emb']);
			$('#moq_info').show();
		}

		
		// enable Add to Cart again
		if ( qt>=sp_moq && $('#front_print_type').val()=='Screen Print' ) {
			$('.single_add_to_cart_button').removeAttr('disabled'); 
			$('#ks_add').css("pointer-events", "auto");
			$('#ks_add').css("opacity", '');
			$('.single_add_to_cart_button').show();
		}
		if ( qt>=sp_moq && $('#back_print_type').val()=='Screen Print' ) {
			$('.single_add_to_cart_button').removeAttr('disabled'); 
			$('#ks_add').css("pointer-events", "auto");
			$('#ks_add').css("opacity", '');
			$('.single_add_to_cart_button').show();
		}
		if ( qt>=sp_moq && $('#left_print_type').val()=='Screen Print' ) {
			$('.single_add_to_cart_button').removeAttr('disabled'); 
			$('#ks_add').css("pointer-events", "auto");
			$('#ks_add').css("opacity", '');
			$('.single_add_to_cart_button').show();
		}
		if ( qt>=sp_moq && $('#right_print_type').val()=='Screen Print' ) {
			$('.single_add_to_cart_button').removeAttr('disabled'); 
			$('#ks_add').css("pointer-events", "auto");
			$('#ks_add').css("opacity", '');
			$('.single_add_to_cart_button').show();
		}
		if ( qt>=sp_moq && $('#neck_print_type').val()=='Screen Print' ) {
			$('.single_add_to_cart_button').removeAttr('disabled'); 
			$('#ks_add').css("pointer-events", "auto");
			$('#ks_add').css("opacity", '');
			$('.single_add_to_cart_button').show();
		}
		// emb if no else SP
		if ( qt>=emb_moq && $('#front_print_type').val()=='Embroidery' && $('#back_print_type').val()!=='Screen Print' && $('#left_print_type').val()!=='Screen Print' && $('#right_print_type').val()!=='Screen Print' && $('#neck_print_type').val()!=='Screen Print'  ) {
			$('.single_add_to_cart_button').removeAttr('disabled'); 
			$('#ks_add').css("pointer-events", "auto");
			$('#ks_add').css("opacity", '');
			$('.single_add_to_cart_button').show();
		}
		if ( qt>=emb_moq && $('#back_print_type').val()=='Embroidery' && $('#front_print_type').val()!=='Screen Print' && $('#left_print_type').val()!=='Screen Print' && $('#right_print_type').val()!=='Screen Print' && $('#neck_print_type').val()!=='Screen Print'  ) {
			$('.single_add_to_cart_button').removeAttr('disabled'); 
			$('#ks_add').css("pointer-events", "auto");
			$('#ks_add').css("opacity", '');
			$('.single_add_to_cart_button').show();
		}
		if ( qt>=emb_moq && $('#left_print_type').val()=='Embroidery' && $('#back_print_type').val()!=='Screen Print' && $('#front_print_type').val()!=='Screen Print' && $('#right_print_type').val()!=='Screen Print' && $('#neck_print_type').val()!=='Screen Print'  ) {
			$('.single_add_to_cart_button').removeAttr('disabled'); 
			$('#ks_add').css("pointer-events", "auto");
			$('#ks_add').css("opacity", '');
			$('.single_add_to_cart_button').show();
		}
		if ( qt>=emb_moq && $('#right_print_type').val()=='Embroidery' && $('#back_print_type').val()!=='Screen Print' && $('#left_print_type').val()!=='Screen Print' && $('#front_print_type').val()!=='Screen Print' && $('#neck_print_type').val()!=='Screen Print'  ) {
			$('.single_add_to_cart_button').removeAttr('disabled'); 
			$('#ks_add').css("pointer-events", "auto");
			$('#ks_add').css("opacity", '');
			$('.single_add_to_cart_button').show();
		}
		if ( qt>=emb_moq && $('#neck_print_type').val()=='Embroidery' && $('#back_print_type').val()!=='Screen Print' && $('#left_print_type').val()!=='Screen Print' && $('#right_print_type').val()!=='Screen Print' && $('#front_print_type').val()!=='Screen Print'  ) {
			$('.single_add_to_cart_button').removeAttr('disabled'); 
			$('#ks_add').css("pointer-events", "auto");
			$('#ks_add').css("opacity", '');
			$('.single_add_to_cart_button').show();
		}


		// product MOQ check
		if (qt < product_moq) {
			if (debug) console.log('Below product MOQ! moq='+product_moq);
			$('#moq_info_prod').show();
			// disable Add to Cart
			$('.single_add_to_cart_button').attr('disabled','disabled'); 
			$('#ks_add').css("pointer-events", "none");
			$('#ks_add').css("opacity", "0.5");

		} else {
			$('#moq_info_prod').hide();
		}

		// all other decorations
		if ( qt>=0 && $('#front_print_type').val()!=='Embroidery' &&  $('#back_print_type').val()!=='Embroidery' && $('#left_print_type').val()!=='Embroidery' && $('#right_print_type').val()!=='Embroidery' && $('#neck_print_type').val()!=='Embroidery' && $('#front_print_type').val()!=='Screen Print' && $('#back_print_type').val()!=='Screen Print' && $('#left_print_type').val()!=='Screen Print' && $('#right_print_type').val()!=='Screen Print' && $('#neck_print_type').val()!=='Screen Print' && qt >= product_moq ) {
			// enable Add to Cart
			$('.single_add_to_cart_button').removeAttr('disabled'); 
			$('#ks_add').css("pointer-events", "auto");
			$('#ks_add').css("opacity", '');
			$('.single_add_to_cart_button').show();
		}
		
	}


	// clear warnings
	function clear_warn() {
		

		if ( (( qt>=sp_moq && $('#front_print_type').val()=='Screen Print' ) || ( qt>=emb_moq && $('#front_print_type').val()=='Embroidery' ) || ( $('#front_print_type').val()!=='Screen Print' && $('#front_print_type').val()!=='Embroidery' )) && ( $('#front_print_type').val().length ==0 || ( $('#art_files1 span').length > 0 && $('#front_print_type').val().length > 0 ) ) ) {
			//	$('#d_warning_front').text('');
			$('#d_warning_front').hide();
		}
		if ( (( qt>=sp_moq && $('#back_print_type').val()=='Screen Print' ) || ( qt>=emb_moq && $('#back_print_type').val()=='Embroidery' ) || ( $('#back_print_type').val()!=='Screen Print' && $('#back_print_type').val()!=='Embroidery' )) && ( $('#back_print_type').val().length ==0 || ( $('#art_files2 span').length > 0 && $('#back_print_type').val().length > 0 ) ) ) {
			//$('#d_warning_back').text('');
			$('#d_warning_back').hide();
		}
		if ( (( qt>=sp_moq && $('#left_print_type').val()=='Screen Print' ) || ( qt>=emb_moq && $('#left_print_type').val()=='Embroidery' ) || ( $('#left_print_type').val()!=='Screen Print' && $('#left_print_type').val()!=='Embroidery' ))  && ( $('#left_print_type').val().length ==0 || ( $('#art_files3 span').length > 0 && $('#left_print_type').val().length > 0 )) ) {
			//$('#d_warning_left').text('');
			$('#d_warning_left').hide();
		}
		if ( (( qt>=sp_moq && $('#right_print_type').val()=='Screen Print' ) || ( qt>=emb_moq && $('#right_print_type').val()=='Embroidery' ) || ( $('#right_print_type').val()!=='Screen Print' && $('#right_print_type').val()!=='Embroidery' )) && ( $('#right_print_type').val().length ==0 || ( $('#art_files4 span').length > 0 && $('#right_print_type').val().length > 0 )) ) {
			//$('#d_warning_right').text('');
			$('#d_warning_right').hide();
		}
		if ( (( qt>=sp_moq && $('#neck_print_type').val()=='Screen Print' ) || ( qt>=emb_moq && $('#neck_print_type').val()=='Embroidery' ) || ( $('#neck_print_type').val()!=='Screen Print' && $('#neck_print_type').val()!=='Embroidery' )) && ( $('#neck_print_type').val().length ==0 || ( $('#art_files5 span').length > 0 && $('#neck_print_type').val().length > 0 )) ) {
			//$('#d_warning_neck').text('');
			$('#d_warning_neck').hide();
		}

		if ( ( qt>=sp_moq && ( $('#front_print_type').val()=='Screen Print' || $('#back_print_type').val()=='Screen Print' || $('#left_print_type').val()=='Screen Print' || $('#right_print_type').val()=='Screen Print' || $('#neck_print_type').val()=='Screen Print' ) ) || (  ( qt>=emb_moq && ( $('#front_print_type').val()=='Embroidery' || $('#back_print_type').val()=='Embroidery' || $('#left_print_type').val()=='Embroidery' || $('#right_print_type').val()=='Embroidery' || $('#neck_print_type').val()=='Embroidery' ) ) ) || ( $('#front_print_type').val()!=='Screen Print' && $('#front_print_type').val()!=='Embroidery' && $('#back_print_type').val()!=='Screen Print' && $('#back_print_type').val()!=='Embroidery' && $('#left_print_type').val()!=='Screen Print' && $('#left_print_type').val()!=='Embroidery' && $('#right_print_type').val()!=='Screen Print' && $('#right_print_type').val()!=='Embroidery' && $('#neck_print_type').val()!=='Screen Print' && $('#neck_print_type').val()!=='Embroidery' ) ) {
			$('#moq_info').hide();
		}

		
	}

	// force decoration if something uploaded - select Emb or first option (if no Emb for this product)
	function force_decor() {
		if (debug) console.log(' FORCE decoration');

		if ( $('#art_files1 span').length > 0 && $('#front_print_type').prop('selectedIndex')==0 ) {
			if ($('#front_print_type option[value="Embroidery"]').length>0)
				$('#front_print_type').val('Embroidery').trigger('change');
			else
				$('#front_print_type').prop('selectedIndex', 1).trigger('change');

			if (debug) console.log(' Front files exist ');
		}

		if ( $('#art_files2 span').length > 0 && $('#back_print_type').prop('selectedIndex')==0 ) {
			if ($('#back_print_type option[value="Embroidery"]').length>0)
				$('#back_print_type').val('Embroidery').trigger('change');
			else
				$('#back_print_type').prop('selectedIndex', 1).trigger('change');

			if (debug) console.log(' Back files exist ');
		}

		if ( $('#art_files3 span').length > 0 && $('#left_print_type').prop('selectedIndex')==0 ) {
			if ($('#left_print_type option[value="Embroidery"]').length>0)
				$('#left_print_type').val('Embroidery').trigger('change');
			else
				$('#left_print_type').prop('selectedIndex', 1).trigger('change');

			if (debug) console.log(' Left files exist ');
		}

		if ( $('#art_files4 span').length > 0 && $('#right_print_type').prop('selectedIndex')==0 ) {
			if ($('#right_print_type option[value="Embroidery"]').length>0)
				$('#right_print_type').val('Embroidery').trigger('change');
			else
				$('#right_print_type').prop('selectedIndex', 1).trigger('change');

			if (debug) console.log(' Right files exist ');
		}

		if ( $('#art_files5 span').length > 0 && $('#neck_print_type').prop('selectedIndex')==0 ) {
			if ($('#neck_print_type option[value="Embroidery"]').length>0)
				$('#neck_print_type').val('Embroidery').trigger('change');
			else
				$('#neck_print_type').prop('selectedIndex', 1).trigger('change');

			if (debug) console.log(' Neck files exist ');
		}


	}



	// = = = = = = = = = = = = = = = = = = = = = = = = = =		START, ONLOAD  

	window.addEventListener('load', function() {
		if (debug) console.log('LOADED');

		var w_w = 0;
		var w_h = 0;

		$('.dtla_loader1').remove();

		// get old session from page
		k_session = $('#ksession').val(); // defined in /kdtla/k_custom/k_custom_fields_wc1.php
		ktoken = $('#ktoken').val();
		if (debug) console.log('OLD k_session='+k_session + ' ktoken='+ ktoken );


		// set default quantity - no 8/8
		//set_def_q(1); 

		// set canvas, delay for WC to set up gallery
		setTimeout( function() { 
		
			// canvas width - based on WooCommerce first product image (not 0 - model image)
			w_w = $($('.woocommerce-product-gallery__image img')[1]).width(); // not $('.flex-active-slide').width();
			w_h = $($('.woocommerce-product-gallery__image')[1]).height(); // not img
			

			// in case
			if (w_w>4000) w_w=500;
			
			// create canvas
			$('.woocommerce-product-gallery__image').each(function(i,el){
				if ( i>0 ) // no canvas on model
					$(el).append('<canvas id="c'+i+'" width="'+w_w+'px" height="'+w_h+'px"></canvas>') ;
			});
			
			if (debug) console.log('canvas dimensions: w_w='+w_w+' w_h='+w_h);
			if (debug) console.log('WC img 0 width='+$($('.woocommerce-product-gallery__image')[0]).width()+' img 1 height='+$($('.woocommerce-product-gallery__image')[1]).height() ); 


			//is fabric loaded
			if (typeof fabric === 'undefined') {
				console.log('Fabric not loaded!');
			}
			else {

				canvas1 = new fabric.Canvas('c1', {width:w_w, height:w_h});
				canvas2 = new fabric.Canvas('c2', {width:w_w, height:w_h});
				canvas3 = new fabric.Canvas('c3', {width:w_w, height:w_h});
				canvas4 = new fabric.Canvas('c4', {width:w_w, height:w_h});

				if (debug) {
					this.window.canvas1 = canvas1; this.window.canvas2 = canvas2; this.window.canvas3 = canvas3; this.window.canvas4 = canvas4;
				}

				canvas1.on("object:selected", function(options) {
					options.target.bringToFront();
				});
				canvas2.on("object:selected", function(options) {
					options.target.bringToFront();
				});
				canvas3.on("object:selected", function(options) {
					options.target.bringToFront();
				});
				canvas4.on("object:selected", function(options) {
					options.target.bringToFront();
				});


				$('.canvas-container').css('position','absolute');
				$('.canvas-container').css('top','0px');
				$('.canvas-container').css('left','0px');
				
				// to center on full screen
				if (debug) console.log(' canvas-container width = '+$('.canvas-container').css('width') + ' images w='+$('.kdtla_images figure img:not(.upload_artwork)')[1].naturalWidth + ' gallery w='+$($('.woocommerce-product-gallery__image')[1]).width() );

				var to_center = 0;
				if ( $($('.woocommerce-product-gallery__image img')[1]).width() > $('.canvas-container').width() ) {
					to_center = ( $($('.woocommerce-product-gallery__image img')[1]).width() - $('.canvas-container').width() )/2;
					if (debug) console.log(' to center='+to_center);
				}

				$('.canvas-container').css('left', (parseInt( $($('.woocommerce-product-gallery__image img')[1]).css('margin-left') )+to_center) + 'px' );
				if (debug) console.log( 'to_center='+to_center+ ' cont to: '+ ( parseInt( $($('.woocommerce-product-gallery__image img')[1]).css('margin-left') )+to_center) + 'px') ;
			}

			check_images();

		}, 800); //this delay must be lower than restoring art files, line 1510

		

		// EDITING?
		if ( k_editing == 1 ) {
			if (debug) console.log(' EDITING');

			// blanks?
			if ($('#ordering_blanks').val()=='yes') { 
				$('#decorated').prop('checked',false);
				$('#decorated').trigger('change');
			} else {
				$('#decorated').prop('checked',true);
				$('#decorated').trigger('change');
			}
			if (debug) console.log(' EDIT, blanks? ' + $('#ordering_blanks').val() );


			// restore colors
			var cc = k_edit_c.split(',');
			if ( cc.length > 2 ) { 
				//$('#multi_colors').click();
				//$('#multi_colors').trigger('change');
				$('#multi_colors').prop('checked',true);
			}
			$('#multi_colors').prop('checked',true); // always multi

			$('.k_colors_main .kcolor_box_selected').each(function(i) {
				var c_empty = kcolors.indexOf('');
				$($('.kcolor_box2')[c_empty]).css('background-color', $(this).css('background-color') );
				$($('.c_name')[c_empty]).val( $(this).attr('data') );
				$($('.c_html')[c_empty]).val( $(this).css('background-color') );
				$($('#table_q tr')[c_empty + 1]).show('fast');

				//set_def_q(c_empty + 1); // def quantities for next color

				setTimeout(calc_quantity_total, 500);

				//kcolors.push( $(this).attr('data') ); //[colors_selected] no
				kcolors[c_empty] = $(this).attr('data');
				kcolors_html[c_empty] = $(this).css('background-color');
				if (debug) { console.log('Editing: ' + kcolors); console.log(kcolors_html); }

				// restore style id
				kstyles[c_empty] = $(this).attr('data-style'); // style_id to the array
				// style_id to hidden fields, c_empty is 1 to 5
				for (var i=0;i<5;i++) {
					if ( kstyles[i].length > 0 )
						$('#style'+(i+1)).val(kstyles[i]);
					else
						$('#style'+(i+1)).val('');

					if (debug) console.log('Editing STYLE id = ' + $(this).attr('data-style'));
				}


			});

			var fc = $($('.k_colors_main .kcolor_box_selected')[0]).attr('data');

			// update images according to first selected color
			// delay if not exist yet
			setTimeout( function(){ 
				if ($('.kdtla_images figure img:not(.upload_artwork)').size()>0) {
					$('.kdtla_images figure img:not(.upload_artwork)')[1].src=kcolors_images[ fc ][0]; //front
					$('.kdtla_images figure img:not(.upload_artwork)')[2].src=kcolors_images[ fc ][1]; //back
					$('.kdtla_images figure img:not(.upload_artwork)')[3].src=kcolors_images[ fc ][2]; //side
					$('.kdtla_images figure img:not(.upload_artwork)')[4].src=kcolors_images[ fc ][3]; //side right
					$('.kdtla_images li img')[1].src=kcolors_images[ fc ][0];
					$('.kdtla_images li img')[2].src=kcolors_images[ fc ][1];
					$('.kdtla_images li img')[3].src=kcolors_images[ fc ][2];
					$('.kdtla_images li img')[4].src=kcolors_images[ fc ][3];
				};

				check_images();
			},500);
			
			//check_images();

			// kcolor_box_selected class added in PHP
			/*
			cc.forEach(function(i) {
				if (i.length>0) {
					$('.kcolor_box[data="'+i+'"]').click();  // " because space in color name "
					if (debug) console.log(' edit, '+i);
				}
			}); */

			if (debug) console.log(' EDITING, colors='+k_edit_c);


			// restore quantities for edited product from k_edit_q variable
			var a = JSON.parse(k_edit_q);
			for (const p1 in a) {
				//console.log(`${p1}: ${a[p1]}`); 
				var o2 = a[p1];
				for (const p2 in o2 ) {
					//console.log(`${p2}: ${o2[p2]}`);
					if (o2[p2]>0) $('#table_q td [name="'+p2+'"]').val(o2[p2]);
				}
			}

			// restore finishings
			var f = k_edit_fin.split(',');
			f.forEach(function(i) { 
				if (i.length>0) $('.fin_tile[data-fin='+i+']').addClass('fin_tile_selected'); //.click();  
			});
			if (debug) console.log(' EDITING, fin='+k_edit_fin);

			// restore art files 
			setTimeout( function(){

				set_canvas_backgrounds(0);

				//var iurl = site_url + artwork_path + k_session;
				var iurl = ''; // if full path in artwork name
				var obj = new Object();
				$('#art1_files').val().split('|').forEach(function(el,i){
					obj.name = el;
					if (el.length>0) {
						//console.log(' ADDING TO CANVAS: '+ i + ':' + iurl + '/front/' + el ); 
						console.log(' ADDING TO CANVAS: '+ i + ':' + el + ' art no='+art_files_no ); 
						//addImage( iurl + '/front/' + el, el, canvas1, 1, i );
						addImage(  el, el, canvas1, 1, i );
						add_art_files(1, obj, i );
					}
				});
				$('#art2_files').val().split('|').forEach(function(el,i){ 
					obj.name = el;
					if (el.length>0) {
						//console.log(' ADDING TO CANVAS: '+ i + ':' + iurl + '/back/' + el + ' art no='+art_files_no ); 
						console.log(' ADDING TO CANVAS: '+ i + ':' + el + ' art no='+art_files_no ); 
						//addImage( iurl + '/back/' + el, el, canvas2, 2, i );
						addImage(  el, el, canvas2, 2, i );
						add_art_files(2, obj, i );
					}
				});
				$('#art3_files').val().split('|').forEach(function(el,i){ 
					obj.name = el;
					if (el.length>0) {
						//console.log(' ADDING TO CANVAS: '+ i + ':' + iurl + '/left/' + el ); 
						console.log(' ADDING TO CANVAS: '+ i + ':' + el + ' art no='+art_files_no ); 
						//addImage( iurl + '/left/' + el, el, canvas3, 3, i );
						addImage(  el, el, canvas3, 3, i );
						add_art_files(3, obj, i );
					}
				});
				$('#art4_files').val().split('|').forEach(function(el,i){ 
					obj.name = el;
					if (el.length>0) {
						//console.log(' ADDING TO CANVAS: '+ i + ':' + iurl + '/right/' + el ); 
						console.log(' ADDING TO CANVAS: '+ i + ':' + el + ' art no='+art_files_no ); 
						//addImage( iurl + '/right/' + el, el, canvas4, 4, i );
						addImage(  el, el, canvas4, 4, i );
						add_art_files(4, obj, i );
					}
				});
				$('#art5_files').val().split('|').forEach(function(el,i){ 
					obj.name = el;
					if (el.length>0) {
						//console.log(' ADDING TO CANVAS: ' + iurl + '/front/' + el ); 
						//addImage( iurl + '/front/' + el, el, canvas5, 5 ); // not for Neck
						add_art_files(5, obj, i );
					}
				});
			}, 1000);

			// restore artwork positions
			if ($('#_art_pos').val().length > 10) {

				var art_pos1 = JSON.parse( $('#_art_pos').val() );
				setTimeout( function(){ 
					if (debug) console.log('art pos=, ='+art_pos1 );
					//canvas1.getObjects().forEach( function(el,i) { canvas1.getObjects()[i].set( art_pos1[1][i] ) });
					canvas1.getObjects().forEach( function(el,i) { el.set( art_pos1[1][el.name.substr(-1,1)] ) });
					canvas1.renderAll();
					console.log('pos 1 restored, c1 len='+canvas1.getObjects().length );
					canvas2.getObjects().forEach( function(el,i) { el.set( art_pos1[2][el.name.substr(-1,1)] ) });
					canvas2.renderAll();
					canvas3.getObjects().forEach( function(el,i) { el.set( art_pos1[3][el.name.substr(-1,1)] ) });
					canvas3.renderAll();
					canvas4.getObjects().forEach( function(el,i) { el.set( art_pos1[4][el.name.substr(-1,1)] ) });
					canvas4.renderAll();
	
					$('.bulk_pricing-modal_button').css('width','92%');
	
				}, 2000);

			}
			

			// hide quote when editing
			$('.send_my_quote_button').hide();
			

			k_editing = 0;

		} else {
			// initial values - if not editing

			$('#ordering_blanks').val( 'no' );
			//$('#order-blank-samples').val('1'); // decorated - drop down
			$('#decorated').prop('checked',true); // decorated checkbox
			$('#multi_colors').prop('checked',true); // always multi


			// get fresh session
			$.ajax({
				url: ktoken_url + '?s=' + k_session + '&ktoken=' + $('#ktoken').val(),
				type: 'GET',
				responseType : "json",
				processData: false, // important
				contentType: false, // important
				dataType : 'json',
			
				success: function(data, message, jq ) {
					// message?
					if (debug) console.log('SESSION from ajax: ' + ' result=' + jq.responseJSON.result  );
					if (debug) console.log('NEW SESSION from ajax: ' + '  s=' + jq.responseJSON.ksession + ' token=' + jq.responseJSON.ktoken  );

					k_session = jq.responseJSON.ksession;
					ktoken = jq.responseJSON.ktoken;
					$('#ksession').val( jq.responseJSON.ksession );
					$('#ktoken').val( jq.responseJSON.ktoken );
					$('#wcsession').val( jq.responseJSON.wc );
				},
				error: function(xhr, status, error) {
					
					if (debug) console.log('SESSION Error: ' + xhr.responseText + ' status=' + status + ' err=' + error);

				}
			});

		}
		
		// end editing

		//app.js correction for local
		const cursor = '<span class="cursor"></span>';
		const cursorInner = '<span class="cursor-move-inner" style=""></span>';
		let loop = 1;
		let normal = null; // not working , TODO...


		//temp
		//$('.woocommerce-product-gallery__image').append('<canvas class="c" width="500px" height="625px"></canvas>');

		// TEMP - description fix
		//var a=$($('#tab-description p')[0]).text();$($('#tab-description p')[0]).html(a.replace(/•/g,'<br/>•'));


		// artwork overlay - added in product-image.php
		//$('.woocommerce-product-gallery__image').append('<img src="/wp-content/uploads/upload_artwork.png" class="upload_artwork" />');



		//artwork overlay click to open first artwork
		$('.upload_artwork').click(function() {
			$($('.artwork_link')[0]).click();
		});
		
		// artwork overlay to hide on mouse over - flex-viewport not working
		$('.kdtla_images').on('mouseover', function(){ $('.upload_artwork').css('opacity',0); }); 
		$('.kdtla_images').on('mouseout', function(){ $('.upload_artwork').css('opacity',1); });


		// canvas number update after thumbnail click - model is 0 so no upload
		// timeout because WooCommerce delayed loading...
		setTimeout( function(){
			$('.flex-control-thumbs li').click(function() {
				
				current_canvas = $(this).index();
				console.log(' thumb click, canvas=' + current_canvas);
			});
		}, 500);
		

		// artwork popup
		$('.artwork_link').click(function() {
			// clear preview image
			$('#artwork-img-prev').html('');

			current_canvas = $(this).data('canvas_id');
			console.log('art1, current_canvas =' + current_canvas );
			$('#dropzone').show();
			$('#dropzone_overlay').show();

			//scroll to product image
			$($('.flex-control-thumbs li img')[current_canvas]).click();
			
			set_canvas_backgrounds(current_canvas);


		});

		// preview artwork image, in artwork popup (currently in app.js)
		/*
		var _URL = window.URL || window.webkitURL;
		$("#upload_b").change(function(e) {
			var image, file;
			if ((file = this.files[0])) {
				image = new Image();
				image.onload = function() {
						src = this.src;
						$('#artwork-img-prev').html('<img src="'+ src +'"></div>');
						$($('#artwork-img-prev img')[0]).data('name', this.name);
						e.preventDefault();
					}
				};
				image.src = _URL.createObjectURL(file);
			console.log(image);
		});
		*/
		
		// add image to canvas, on change - moved to Save & Close button
		/*
		$('#upload_b').on('change',function(el) {
			$($('#upload_b')[0].files).each(function(i,el) {
				console.log('...input: file[' + i + '].name = ' + el.name + ' size='+el.size);
			//	if ( current_canvas == 1 ) readImage(el, canvas1); 
				if ( current_canvas == 2 ) readImage(el, canvas2); 
				if ( current_canvas == 3 ) readImage(el, canvas3); 
				if ( current_canvas == 4 ) readImage(el, canvas4); 

			//	if ( current_canvas == 1 ) upload_file(el, 'front');
				if ( current_canvas == 2 ) upload_file(el, 'back');
				if ( current_canvas == 3 ) upload_file(el, 'left');
				if ( current_canvas == 4 ) upload_file(el, 'right');
				if ( current_canvas == 5 ) { 
					upload_file(el, 'neck');
					//create filenames
					add_art_files(current_canvas, el);

					// add art file names to hidden field
					update_art_files(current_canvas);
				}
			});
		});
		*/

		// artwork popup - close
		$('#dropzone_close').click(function() {
			$('#dropzone').hide();
			$('#dropzone_overlay').hide();
			// clear preview image
			$('#artwork-img-prev').html('');
		});


		// add artwork - upload, convert, preview
		$("#upload_b").change(function(e) {
			var image, file;

			// if type of converted
			$($('#upload_b')[0].files).each(function(i,el) {
				// upload block - repeated in Save function
				if ( validTypes.includes(el.type) ) {
					if (debug) console.log('...input for converting: file[' + i + '].name = ' + el.name + ' size='+el.size);
					if ( current_canvas == 1 ) upload_file(el, 'front' ); // without canvas here
					if ( current_canvas == 2 ) upload_file(el, 'back'  );
					if ( current_canvas == 3 ) upload_file(el, 'left'  );
					if ( current_canvas == 4 ) upload_file(el, 'right' );
					if ( current_canvas == 5 ) { upload_file(el, 'neck'); }
					// $('#artwork-img-prev') is inside upload
				}

			});

		});

		// artwork popup - close (upload files also)
		$('#dropzone_save').click(function() {
			$('#dropzone').hide();
			$('#dropzone_overlay').hide();

			// show animation
			if ($('#upload_b')[0].files.length > 0 )
				$('.kdtla_images').append('<div class="dtla_loader1"><img src="/wp-content/uploads/2023/05/dtla-loader.gif"></div>');
			
			// add artwork to canvas
			$($('#upload_b')[0].files).each(function(i,el) {
				if (debug) console.log('...input: file[' + i + '].name = ' + el.name + ' size='+el.size);
				if (el.size > max_file_size) {
					console.log('Max file size exceeded! s='+el.size);
					return;
				}

				// if extension to convert, then upload first - readimage inside upload_file
				if ( validTypes.includes(el.type) ) {
					if ( current_canvas == 1 ) upload_file(el, 'front', canvas1 );
					if ( current_canvas == 2 ) upload_file(el, 'back' , canvas2 );
					if ( current_canvas == 3 ) upload_file(el, 'left' , canvas3 );
					if ( current_canvas == 4 ) upload_file(el, 'right', canvas4 );
					if ( current_canvas == 5 ) { 
						upload_file(el, 'neck');
						//create filenames
						add_art_files(current_canvas, el);

						// add art file names to hidden field
						update_art_files(current_canvas);
					}
				}

				if ( validImageTypes.includes(el.type) ) {
					if ( current_canvas == 1 ) readImage(el, canvas1); 
					if ( current_canvas == 2 ) readImage(el, canvas2); 
					if ( current_canvas == 3 ) readImage(el, canvas3); 
					if ( current_canvas == 4 ) readImage(el, canvas4); 

					if ( current_canvas == 1 ) upload_file(el, 'front');
					if ( current_canvas == 2 ) upload_file(el, 'back');
					if ( current_canvas == 3 ) upload_file(el, 'left');
					if ( current_canvas == 4 ) upload_file(el, 'right');
					if ( current_canvas == 5 ) { 
						upload_file(el, 'neck');
						//create filenames
						add_art_files(current_canvas, el);

						// add art file names to hidden field
						update_art_files(current_canvas);
					}
				}
			});

			// clear preview image
			$('#artwork-img-prev').html('');

			// for mobile
			$($('.upper-canvas')[current_canvas-1]).css('pointer-events','auto');

			//scroll to product image, 1st
			$($('.flex-control-thumbs li img')[current_canvas]).click();

			// clear warnings, after a timeout
			setTimeout(clear_warn, 500);
		});


		// add image to canvas, on drop 
		//var target = document.getElementsByClassName('kdtla_images')[0]; // drop outside of images loads only the image...
		var target = document.body;

		target.addEventListener("dragover", function(e) {e.preventDefault();}, true);
		target.addEventListener("drop", function(e) {
			e.preventDefault(); 
			
			if (debug) console.log('Dropped file(s) ');

			

			if (current_canvas!==1 && current_canvas!==2 && current_canvas!==3 && current_canvas!==4) {
				current_canvas = 1;

				//scroll to product image, 1st
				$($('.flex-control-thumbs li img')[current_canvas]).click();
			}

			

			set_canvas_backgrounds(current_canvas);

			if (debug) console.log(' drop, curr canvas ' + current_canvas + ' w='+canvas1.width + ' w_w='+w_w);

			
			
			if (e.dataTransfer.items) {

				// show animation
				$('.kdtla_images').append('<div class="dtla_loader1"><img src="/wp-content/uploads/2023/05/dtla-loader.gif"></div>');

				// Use DataTransferItemList interface to access the file(s)
				for (var i = 0; i < e.dataTransfer.items.length; i++) {
					// If dropped items aren't files, reject them
					if (e.dataTransfer.items[i].kind === 'file') {
						var file = e.dataTransfer.items[i].getAsFile();
						var aux = file.name.split('.');
   						var fext = aux[aux.length -1].toLowerCase();

						if ( !validImageTypes.includes(file.type) && !validTypes.includes(file.type) ) {
							console.log("ERROR 3: File type not allowed! ", file.type);
							$('.dtla_loader1').remove();
							return;
						}

						if (debug) console.log('... file[' + i + '].name = ' + file.name + ' size='+file.size + ' type=' + file.type + ' ext='+fext);
						if (file.size > max_file_size) {
							console.log('Max file size exceeded! s='+file.size);
							return;
						}
							
						// if extension to convert, then upload first - readimage inside upload_file
						if ( validTypes.includes(file.type) ) {
							if ( current_canvas == 1 ) upload_file(e.dataTransfer.files[i], 'front', canvas1 ); //  files or items?
							if ( current_canvas == 2 ) upload_file(e.dataTransfer.files[i], 'back' , canvas2 );
							if ( current_canvas == 3 ) upload_file(e.dataTransfer.files[i], 'left' , canvas3 );
							if ( current_canvas == 4 ) upload_file(e.dataTransfer.files[i], 'right', canvas4 );
							if ( current_canvas == 5 ) upload_file(e.dataTransfer.files[i], 'neck');
						}

						if ( validImageTypes.includes(file.type) ) {
							if ( current_canvas == 1 ) readImage(e.dataTransfer.files[i], canvas1); 
							if ( current_canvas == 2 ) readImage(e.dataTransfer.files[i], canvas2); 
							if ( current_canvas == 3 ) readImage(e.dataTransfer.files[i], canvas3); 
							if ( current_canvas == 4 ) readImage(e.dataTransfer.files[i], canvas4); 
	
							if ( current_canvas == 1 ) upload_file(e.dataTransfer.files[i], 'front');
							if ( current_canvas == 2 ) upload_file(e.dataTransfer.files[i], 'back');
							if ( current_canvas == 3 ) upload_file(e.dataTransfer.files[i], 'left');
							if ( current_canvas == 4 ) upload_file(e.dataTransfer.files[i], 'right');
							if ( current_canvas == 5 ) upload_file(e.dataTransfer.files[i], 'neck');

						}
						
					}
					else {
						if (debug) console.log('- not a file?');
						$('.dtla_loader1').remove();
					}
				}
			} else {
				// Use DataTransfer interface to access the file(s)
				for (var i = 0; i < e.dataTransfer.files.length; i++) {

					var file = e.dataTransfer.files[i].getAsFile();

					if ( !validImageTypes.includes(file.type) && !validTypes.includes(file.type) ) {
						console.log("ERROR 3: File type not allowed! ", file.type);
						$('.dtla_loader1').remove();
						return;
					}

					if (debug) console.log('...dt file[' + i + '].name = ' + e.dataTransfer.files[i].name);
					//readImage(e.dataTransfer.files[i]);

					// if extension to convert, then upload first - readimage inside upload_file
					if ( validTypes.includes(file.type) ) {
						if ( current_canvas == 1 ) upload_file(e.dataTransfer.files[i], 'front', canvas1 ); //  files or items?
						if ( current_canvas == 2 ) upload_file(e.dataTransfer.files[i], 'back' , canvas2 );
						if ( current_canvas == 3 ) upload_file(e.dataTransfer.files[i], 'left' , canvas3 );
						if ( current_canvas == 4 ) upload_file(e.dataTransfer.files[i], 'right', canvas4 );
						if ( current_canvas == 5 ) upload_file(e.dataTransfer.files[i], 'neck');
					}

					if ( validImageTypes.includes(file.type) ) {

						if ( current_canvas == 1 ) readImage(e.dataTransfer.files[i], canvas1); 
						if ( current_canvas == 2 ) readImage(e.dataTransfer.files[i], canvas2); 
						if ( current_canvas == 3 ) readImage(e.dataTransfer.files[i], canvas3); 
						if ( current_canvas == 4 ) readImage(e.dataTransfer.files[i], canvas4); 

						if ( current_canvas == 1 ) upload_file(e.dataTransfer.files[i], 'front');
						if ( current_canvas == 2 ) upload_file(e.dataTransfer.files[i], 'back');
						if ( current_canvas == 3 ) upload_file(e.dataTransfer.files[i], 'left');
						if ( current_canvas == 4 ) upload_file(e.dataTransfer.files[i], 'right');
						if ( current_canvas == 5 ) upload_file(e.dataTransfer.files[i], 'neck');
					}
				}
			}
			
			//clear data
			if (e.dataTransfer.items) {
				e.dataTransfer.items.clear();
			} else {
				e.dataTransfer.clearData();
			}
			
			// remove animation - just in case, after 3 sec
			setTimeout(()=>{ $('.dtla_loader1').remove(); },3000 );

			// clear warnings, after a timeout
			setTimeout(clear_warn, 500);

		}, true);


		// upload canvas previews after click on Add to Cart (but before sending the form)

		$('form.cart').submit(function(e) {
			e.preventDefault();
			if (debug) console.log(' - - uploading previews...');
			
			// show loader animation
			$('.k_product1').append('<div class="dtla_loader1" style="position:fixed;"><img src="/wp-content/uploads/2023/05/dtla-loader.gif"></div>');
			
			// add product id to the form
			var prod_id = $('form.cart').find('button[name=add-to-cart]').val();
			var qty = $('form.cart').find(' .qty')[0].id;
			// form 0 is search, form 1 is product
			$('form.cart').append('<input name="add-to-cart" value="' + prod_id + '" style="display:none" > ');
			$('form.cart').append('<input name="quantity" value="1" id="' + qty + '" style="display:none" > ');

			$('form.cart').append('<span id="upl_info"></span>');

			var uploads = [];

			if ($('#art_files1 span').length > 0) {
				$('#upl_info').text('uploading preview 1 ...');
				uploads.push( upload_file_canvas( canvas1.toDataURL("image/png"), 'front' ) );
			}
			else
				$('#art1_preview').val('');

			if ($('#art_files2 span').length > 0) {
				$('#upl_info').text('uploading preview 2 ...');
				uploads.push( upload_file_canvas( canvas2.toDataURL("image/png"), 'back' ) );
			}
			else
				$('#art2_preview').val('');

			if ($('#art_files3 span').length > 0) {
				$('#upl_info').text('uploading preview 3 ...');
				uploads.push(upload_file_canvas( canvas3.toDataURL("image/png"), 'left' ) );
			}
			else
				$('#art3_preview').val('');

			if ($('#art_files4 span').length > 0) {
				$('#upl_info').text('uploading preview 4 ...');
				uploads.push( upload_file_canvas( canvas4.toDataURL("image/png"), 'right' ) );
			}
			else
				$('#art4_preview').val('');


			// add artwork positions - for editing
			art_pos=[ [],[],[],[], [] ]; // 0 not used
			canvas1.getObjects().forEach( function(el,i) { art_pos[1][i]={'left':canvas1.getObjects()[i].left, 'top':canvas1.getObjects()[i].top, 'scaleX':canvas1.getObjects()[i].scaleX , 'scaleY':canvas1.getObjects()[i].scaleY, 'angle':canvas1.getObjects()[i].angle  } });
			canvas2.getObjects().forEach( function(el,i) { art_pos[2][i]={'left':canvas2.getObjects()[i].left, 'top':canvas2.getObjects()[i].top, 'scaleX':canvas2.getObjects()[i].scaleX , 'scaleY':canvas2.getObjects()[i].scaleY, 'angle':canvas2.getObjects()[i].angle  } });
			canvas3.getObjects().forEach( function(el,i) { art_pos[3][i]={'left':canvas3.getObjects()[i].left, 'top':canvas3.getObjects()[i].top, 'scaleX':canvas3.getObjects()[i].scaleX , 'scaleY':canvas3.getObjects()[i].scaleY, 'angle':canvas3.getObjects()[i].angle  } });
			canvas4.getObjects().forEach( function(el,i) { art_pos[4][i]={'left':canvas4.getObjects()[i].left, 'top':canvas4.getObjects()[i].top, 'scaleX':canvas4.getObjects()[i].scaleX , 'scaleY':canvas4.getObjects()[i].scaleY, 'angle':canvas4.getObjects()[i].angle  } });

			$('#_art_pos').val( JSON.stringify(art_pos) );


			var ff = this;

			// should run after all previews are uploaded
			$.when.apply($, uploads).then(function(e) {
				//console.log(uploads);
				if (debug) console.log("All uploads have finished");
				$('#upl_info').text('Previews uploaded. Adding to cart ...');

				if (debug) console.log(' -submit sent... ');
				
				//$(this).off('submit').submit(); //off('submit').
				ff.submit();
				if (debug) console.log(' - after submit sent... ');

				// remove animation - page reloading but in case of user going back
				setTimeout(()=>{ $('.dtla_loader1').remove(); },1000 );
			});

		});




		// colors and quantity functions
		//$('#multi_colors').prop('checked')

		
		//$($('.kcolor_box2')[0]).css('background-color', '#ffffff' );

		//var qt = 0;
		function calc_quantity_total() {
			qt = 0;

			//zero quantities for hidden colors
			$('#table_q tr:hidden .q1').each(function(){ this.value = ''; });

			// sum all visible quantities
			$('#table_q tr:visible .q1').each(function(){ qt = qt + this.value * 1; });
			$('#quantity_total').val( qt );
			//if (debug) console.log('total='+qt+', q='+$('#table_q tr:visible .q1').length );

			// disable finishings if below MOQ
			if ( qt<fin_moq ) {
				$('.fin_tile').each(function() {
					
					var fin = $(this).data('fin');  
					if ( fin=='ht' || fin=='wml' || fin=='whl' ) {
						$(this).addClass('fin_tile_disabled'); 

						if ( $(this).hasClass('fin_tile_selected') ) { 
							$(this).removeClass('fin_tile_selected'); 
							console.log('Fin "'+ fin +'" disabled.'); 
						} 
					} 
				});
			}
			else {
				$('.fin_tile').each(function() {
					$(this).removeClass('fin_tile_disabled'); 
				});
			}


			// update price after quantity change
			price_update( qt );
		}


		// COLOR THUMB on mouseover
		$('body').append('<div id="kthumb"><img id="kthumb_image" /><span id="kthumb_name">name</span></div>');


		// color box - mouseover to show thumbnail
		$('.k_colors_main .kcolor_box').mouseover(function(e) { 
			// show color name
				//$('#kcolor_name').text($(this).attr('data'));
			$('#kthumb_name').text($(this).attr('data'));
			//show thumbnail
			$('#kthumb_image')[0].src = kcolors_images[$(this).attr('data')][0];
			$('#kthumb').css('top', e.clientY + window.scrollY + 20 + 'px'); // 20px lower
			$('#kthumb').css('left', e.clientX + window.scrollX - 85 + 'px'); // 85 = kthumb width / 2
			$('#kthumb').show();

		});

		$('.k_colors_main .kcolor_box').mouseout(function() { 
			$('#kcolor_name').text('');
			$('#kthumb').hide();
			$('#kthumb_image')[0].src = '';
		});

		// color box - updates quantities table, images, etc..
		$('.k_colors_main .kcolor_box').click(function() {

			// for single color
			if ( !$('#multi_colors').prop('checked') ) {
				$('.k_colors_main .kcolor_box').removeClass('kcolor_box_selected');
				$(this).addClass('kcolor_box_selected');

				$($('.kcolor_box2')[0]).css('background-color', $(this).css('background-color') );
				$($('.c_name')[0]).val( $(this).attr('data') );
				$($('.c_html')[0]).val( $(this).css('background-color') );
				//$($('#table_q tr')[1]).show('slow');
				$('#style1').val($(this).attr('data-style')); // style id to hidden field
				$('#style2').val('');
				$('#style3').val('');
				$('#style4').val('');
				$('#style5').val('');
				kstyles[0] = $(this).attr('data-style'); // style id to array

				kcolors[0] = $(this).attr('data');
				kcolors_html[0] = $(this).css('background-color');
				if (debug) console.log('no multi: '+kcolors);

				// update images according to clicked color box
				$('.kdtla_images figure img:not(.upload_artwork)')[1].src=kcolors_images[$(this).attr('data')][0]; //front
				$('.kdtla_images figure img:not(.upload_artwork)')[2].src=kcolors_images[$(this).attr('data')][1]; //back
				$('.kdtla_images figure img:not(.upload_artwork)')[3].src=kcolors_images[$(this).attr('data')][2]; //side
				$('.kdtla_images figure img:not(.upload_artwork)')[4].src=kcolors_images[$(this).attr('data')][3]; //side right
				$('.kdtla_images li img')[1].src=kcolors_images[$(this).attr('data')][0];
				$('.kdtla_images li img')[2].src=kcolors_images[$(this).attr('data')][1];
				$('.kdtla_images li img')[3].src=kcolors_images[$(this).attr('data')][2];
				$('.kdtla_images li img')[4].src=kcolors_images[$(this).attr('data')][3];

				check_images();

				//scroll to 1st product image after color change, only once
				if ( !flex_first) {
					$($('.flex-control-thumbs li img')[1]).click();
					flex_first = 1;
				}
				

			}
				
			colors_selected = $('.k_colors_main .kcolor_box_selected').length;

			// click again to un-select
			if ( $(this).hasClass('kcolor_box_selected') && colors_selected >1 ) {
				$(this).removeClass('kcolor_box_selected');

				var c = $(this).attr('data'); // color name
				var ci = kcolors.indexOf(c); //color index
				kcolors[ci] = '';
				kcolors_html[ci] = '';
				kstyles[ci] = ''; // remove style_id
				$('#style'+(ci+1) ).val('');
				//kcolors = kcolors.filter(function(value, index, arr){  return value !== c; }) // remove clicked color from array

				//if (ci>0) 
				//	$($('#table_q tr')[ci + 1]).hide('fast');
				$($('.kcolor_box2')[ci]).css('background-color', '#ffffff' );
				$($('.c_name')[ci]).val( '' );
				$($('.c_html')[ci]).val( '' );

				// copy quantities from next rows up
				var s=$('#table_q tr .q1').length/5; // for 5 color rows
				for (var i=0;i<5;i++) { 
					
					if ($($('#table_q tr .c_name')[i]).val()=='') {
						for (var j=i;j<4;j++) { 
							for (var k=0;k<s;k++) { 
								$($('#table_q tr .q1')[j * s +k]).val( $($('#table_q tr .q1')[(j+1) * s +k]).val() ); 
							} 
							$($('#table_q tr .c_name')[j]).val( $($('#table_q tr .c_name')[j+1]).val() );
							$($('#table_q tr .c_html')[j]).val( $($('#table_q tr .c_html')[j+1]).val() );
							$($('.kcolor_box2')[j]).css('background-color', $($('.kcolor_box2')[j+1]).css('background-color') );
							kcolors[j] = kcolors[j+1];
							kcolors_html[j] = kcolors_html[j+1];
							kstyles[j] = kstyles[j+1]; 
							$('#style'+(j+1) ).val( $('#style'+(j+2) ).val() );
							//if (debug) { console.log('i='+i+' j='+j+' k='+k); console.log(kcolors); console.log(kstyles); }
						}
					} 
				}

				// empty the last rows
				colors_selected = $('.k_colors_main .kcolor_box_selected').length;
				for (var i=5;i>colors_selected-1;i--) {
					$($('.c_name')[i]).val( '' );
					$($('.kcolor_box2')[i]).css('background-color', '#ffffff' );
					kcolors[i] = '';
					kcolors_html[i] = '';
					kstyles[i] = '';
					$('#style'+(i+1) ).val('');

					$($('#table_q tr')[i+1]).hide('fast'); // hide next row
					//if (debug) console.log(' hidden  '+i+1 );
				}
				

				setTimeout(calc_quantity_total, 500);
				
				//$($('#table_q tr')[ci + 1]).css('opacity',0.5);
				if (debug) console.log(' removed '+c );
				//if (debug) { console.log(kcolors); console.log(kcolors_html); console.log(kstyles);}
				
			}
			else
				if ( colors_selected <5 && $('#multi_colors').prop('checked') && !$(this).hasClass('kcolor_box_selected') ) {
					$(this).addClass('kcolor_box_selected');
					
					var c_empty = kcolors.indexOf('');
					$($('.kcolor_box2')[c_empty]).css('background-color', $(this).css('background-color') );
					$($('.c_name')[c_empty]).val( $(this).attr('data') );
					$($('.c_html')[c_empty]).val( $(this).css('background-color') );
					$($('#table_q tr')[c_empty + 1]).show('fast');

					//set_def_q(c_empty + 1); // def quantities for next color

					setTimeout(calc_quantity_total, 500);

					//kcolors.push( $(this).attr('data') ); //[colors_selected] no
					kcolors[c_empty] = $(this).attr('data');
					kcolors_html[c_empty] = $(this).css('background-color');
					if (debug) { console.log(kcolors); console.log(kcolors_html); }

					kstyles[c_empty] = $(this).attr('data-style'); // style_id to the array
					// style_id to hidden fields, c_empty is 1 to 5
					for (var i=0;i<5;i++) {
						if ( typeof(kstyles[i])=='undefined' )
							kstyles[i] = ''

						if (  kstyles[i].length > 0 )
							$('#style'+(i+1)).val(kstyles[i]);
						else
							$('#style'+(i+1)).val('');

						if (debug) console.log('STYLE id = ' + $(this).attr('data-style'));
					}


					// update images according to clicked color box
					$('.kdtla_images figure img:not(.upload_artwork)')[1].src=kcolors_images[$(this).attr('data')][0]; //front
					$('.kdtla_images figure img:not(.upload_artwork)')[2].src=kcolors_images[$(this).attr('data')][1]; //back
					$('.kdtla_images figure img:not(.upload_artwork)')[3].src=kcolors_images[$(this).attr('data')][2]; //side
					$('.kdtla_images figure img:not(.upload_artwork)')[4].src=kcolors_images[$(this).attr('data')][3]; //side right
					$('.kdtla_images li img')[1].src=kcolors_images[$(this).attr('data')][0];
					$('.kdtla_images li img')[2].src=kcolors_images[$(this).attr('data')][1];
					$('.kdtla_images li img')[3].src=kcolors_images[$(this).attr('data')][2];
					$('.kdtla_images li img')[4].src=kcolors_images[$(this).attr('data')][3];

					check_images();
				}
			

			
			if ( colors_selected >= 4 )
				$('#kcolor_name').html('<span style="color:red;">Max 5 colors</span>');

			// chanage to image ?...
			colors_selected = $('.k_colors_main .kcolor_box_selected').length;
			if (debug) console.log('colors selected=' + colors_selected);


			// canvas bg change
			set_canvas_backgrounds(0);
			
			if (debug) console.log(' SCALE='+canvas1.width / w_w);

		});


		$('#multi_colors').change(function() {
			
			$('.k_colors_main .kcolor_box').removeClass('kcolor_box_selected'); 
			for (var i=0;i<5;i++) {
				
				$($('.kcolor_box2')[i]).css('background-color', '#ffffff' );
				$($('.c_name')[i]).val( '' );
				$($('.c_html')[i]).val( '' );
				$('#style'+(i+1)).val(''); // clear style_id
				if (i>0) $($('#table_q tr')[i+1]).hide('fast');
				// TODO clear quantities?
			}
			$($('#table_q tr')[1]).show();
			kcolors = ['', '', '', '', '']; //kcolors[0]
			kcolors_html = ['', '', '', '', ''];
			kstyles = ['', '', '', '', ''];

			setTimeout(calc_quantity_total, 500);

			if (debug) console.log('Multi change, '+ kcolors);
		})

		// total quantity
		$('#table_q tr .q1').change(function() {

			if (debug) console.log('change,'+this.value);
			calc_quantity_total();

		});

		// color_delete click to unselect
		$('.color_delete').each(function(i,el){
			$(el).click(function(){
				var row_color = $(el).parent().parent().find('.c_name').val(); console.log('c='+row_color);
				$('.k_colors_main .kcolor_box_selected[data="' + row_color + '"]').click();
			});
		});



		// decorations - change events
		$('#front_print_type').change(function() {
			if (this.value=='Screen Print') {
				$('.sp_options_front').show();
				$('#front_colors option:eq(1)').prop('selected', true); // selects first option, so price updates without error
				$('#front_ink option:eq(1)').prop('selected', true);
			}
			else {
				$('.sp_options_front').hide();
				$('#front_colors option:eq(0)').prop('selected', true); // selects 0/empty so it is not sent to the cart
				$('#front_ink option:eq(0)').prop('selected', true);
			};
			
			if ( $('#front_print_type').prop('selectedIndex')==0 ) 
				setTimeout( function() { 
					force_decor();
					price_update( qt );
				}, 300 );
			else
				price_update( qt );
		});

		$('#back_print_type').change(function() { 
			if (this.value=='Screen Print') {
				$('.sp_options_back').show();
				$('#back_colors option:eq(1)').prop('selected', true);
				$('#back_ink option:eq(1)').prop('selected', true);
			} else {
				$('.sp_options_back').hide();
				$('#back_colors option:eq(0)').prop('selected', true);
				$('#back_ink option:eq(0)').prop('selected', true);
			}; 

			if ( $('#back_print_type').prop('selectedIndex')==0 ) 
				setTimeout( function() { 
					force_decor();
					price_update( qt );
				}, 300 );
			else
				price_update( qt );
		});

		$('#left_print_type').change(function() { 
			if (this.value=='Screen Print') {
				$('.sp_options_left').show();
				$('#left_colors option:eq(1)').prop('selected', true);
				$('#left_ink option:eq(1)').prop('selected', true);
			}
			else {
				$('.sp_options_left').hide();
				$('#left_colors option:eq(0)').prop('selected', true);
				$('#left_ink option:eq(0)').prop('selected', true);
			}

			if ( $('#left_print_type').prop('selectedIndex')==0 ) 
				setTimeout( function() { 
					force_decor();
					price_update( qt );
				}, 300 );
			else
				price_update( qt );
		});

		$('#right_print_type').change(function() { 
			if (this.value=='Screen Print') {
				$('.sp_options_right').show();
				$('#right_colors option:eq(1)').prop('selected', true);
				$('#right_ink option:eq(1)').prop('selected', true);
			}
			else {
				$('.sp_options_right').hide(); 
				$('#right_colors option:eq(0)').prop('selected', true);
				$('#right_ink option:eq(0)').prop('selected', true);
			}

			if ( $('#right_print_type').prop('selectedIndex')==0 ) 
				setTimeout( function() { 
					force_decor();
					price_update( qt );
				}, 300 );
			else
				price_update( qt );
		});
		
		$('#neck_print_type').change(function() { 
			if (this.value=='Screen Print') {
				$('.sp_options_neck').show();
				$('#neck_colors option:eq(1)').prop('selected', true);
				$('#neck_ink option:eq(1)').prop('selected', true);
			}
			else {
				$('.sp_options_neck').hide(); 
				$('#neck_colors option:eq(0)').prop('selected', true);
				$('#neck_ink option:eq(0)').prop('selected', true);
			}

			if ( $('#neck_print_type').prop('selectedIndex')==0 ) 
				setTimeout( function() { 
					force_decor();
					price_update( qt );
				}, 300 );
			else
				price_update( qt );
		});
			

		// colors, ink change
		$('#front_colors').change(function() { if ( $(this).val() > 0 ) price_update( qt ); });
		$('#back_colors').change(function() { if ( $(this).val() > 0 ) price_update( qt ); });
		$('#left_colors').change(function() { if ( $(this).val() > 0 ) price_update( qt ); });
		$('#right_colors').change(function() { if ( $(this).val() > 0 ) price_update( qt ); });

		$('#front_ink').change(function() { if ( $(this).val() != '') price_update( qt ); });
		$('#back_ink').change(function() { if ( $(this).val() != '') price_update( qt ); });
		$('#left_ink').change(function() { if ( $(this).val() != '') price_update( qt ); });
		$('#right_ink').change(function() { if ( $(this).val() != '') price_update( qt ); });




		// FINISHING - TILE CLICK
		
		$('.fin_tile').click(function() {

			// fin MOQ check, if quantity < 50 
			if ( qt<fin_moq ) {
				var cfin = $(this).data('fin');
				if (cfin=='ht' || cfin=='wml' || cfin=='whl') {
					console.log('Click, Finishing '+cfin+' is below MOQ.');
					return;
				}
			}
			


			// add / remove class
			if ( $(this).hasClass('fin_tile_selected') ) 
				$(this).removeClass('fin_tile_selected'); 
			else 
				$(this).addClass('fin_tile_selected');
			
			$('#fin_all').val('');
			$('.fin_tile').each(function(i, el) {
				if ($(this).hasClass('fin_tile_selected')) { 
					//$('#fin_all').val( $('#fin_all').val()+'+f'+(i+1) );
					$('#fin_all').val( $('#fin_all').val() + $(this).data('fin') +',' );
				} } );

			
			price_update_finishing();


			//SUMMARY price update
			if ( ordering_blank == 0) {
				$('#k_per_item').val( Math.round( ( k_per_item_price + fin_all_cost ) * 100 ) / 100 );
				$('#k_total_price').val ( Math.round( ( (k_per_item_price + fin_all_cost) * qt) * 100 ) / 100 );

				$('#k_per_item_span').text( k_price.format( Math.round( ( k_per_item_price + fin_all_cost ) * 100 ) / 100 ));
				$('#k_total_price_span').text( k_price.format( Math.round( ( (k_per_item_price + fin_all_cost) * qt) * 100 ) / 100 ));
			}
			else {
				$('#k_per_item').val( prod_price );
				$('#k_total_price').val ( Math.round( ( prod_price * qt) * 100 ) / 100 );

				$('#k_per_item_span').text( k_price.format( prod_price ));
				$('#k_total_price_span').text( k_price.format( Math.round( ( prod_price * qt) * 100 ) / 100 ));
			}

			// SHIPPING text update
			if ( $('#k_total_price').val()*1 > 1500) {
				$('#free_shipping_info').text('Free Shipping');
			} else {
				$('#free_shipping_info').text('Shipping Calculated in Cart');
			}
			

			// K_STICKY info update
			if ( typeof($('#ks_total'))!=='undefined' ) {
				$('#ks_total').text( $('#k_total_price_span').text() );
				$('#ks_q').text( $('#quantity_total2').val() );
				$('#ks_price').text( $('#k_per_item_span').text() );
				$('#ks_del').text( $('#k_delivery_dates').val() );
			}
			
		});
		




		// PRICE UPDATE VIA AJAX
		function price_update( qt ) {
			if (debug) console.log('ajax: price update, qt=' + qt );

			$.ajax({
				url: plugin_url + '/kpricing.php',
				data: { 
					'quantity': qt,
					//'colors': $('#colors').val(), 
					//'ink': $('#ink').val(),
					//'price_table_header': price_table_header, 
					'price_table': price_table,
					// all fields in decorations 
					'front_print_type': $('#front_print_type').val(),
					'front_colors': $('#front_colors').val(),
					'front_ink': $('#front_ink').val(),
					'back_print_type': $('#back_print_type').val(),
					'back_colors': $('#back_colors').val(),
					'back_ink': $('#back_ink').val(),
					'left_print_type': $('#left_print_type').val(),
					'left_colors': $('#left_colors').val(),
					'left_ink': $('#left_ink').val(),
					'right_print_type': $('#right_print_type').val(),
					'right_colors': $('#right_colors').val(),
					'right_ink': $('#right_ink').val(),
					'neck_print_type': $('#neck_print_type').val(),
					'neck_colors': $('#neck_colors').val(),
					'neck_ink': $('#neck_ink').val(),

				},
				type: 'POST',
				dataType : "json",
				success: function (response) {
					if (debug) console.log( response ); // response = object, if header json in php; var x = JSON.stringify(response); not needed
					
					
					// update finishing prices - span
					$('#fin1').text(  response.f1 ); // without $ (error)
					$('#fin2').text(  response.f2 );
					$('#fin3').text(  response.f3 );
					$('#fin4').text(  response.f4 );
					$('#fin5').text(  response.f5 );
					$('#fin6').text(  response.f6 );

					// separate finishing prices, for cart
					$('#fin1_cost').val( response.f1 ); // without $
					$('#fin2_cost').val( response.f2 );
					$('#fin3_cost').val( response.f3 );
					$('#fin4_cost').val( response.f4 );
					$('#fin5_cost').val( response.f5 );
					$('#fin6_cost').val( response.f6 );

					// lead times - span
					$('#fin1_days_span').text( response.f1_days );
					$('#fin2_days_span').text( response.f2_days );
					$('#fin3_days_span').text( response.f3_days );
					$('#fin4_days_span').text( response.f4_days );
					$('#fin5_days_span').text( response.f5_days );
					$('#fin6_days_span').text( response.f6_days );

					// lead times - for cart
					$('#fin1_days').val( response.f1_days );
					$('#fin2_days').val( response.f2_days );
					$('#fin3_days').val( response.f3_days );
					$('#fin4_days').val( response.f4_days );
					$('#fin5_days').val( response.f5_days );
					$('#fin6_days').val( response.f6_days );


					// update summary
					k_per_item_price = Math.round( response.price * 100)/100;

					price_update_finishing();

					if ( ordering_blank == 0) {
						$('#k_per_item').val( Math.round( ( k_per_item_price + fin_all_cost ) * 100 ) / 100 );
						$('#k_total_price').val ( Math.round( ( (k_per_item_price + fin_all_cost) * qt) * 100 ) / 100 );

						$('#k_per_item_span').text( k_price.format( Math.round( ( k_per_item_price + fin_all_cost ) * 100 ) / 100 ) );
						$('#k_total_price_span').text( k_price.format( Math.round( ( (k_per_item_price + fin_all_cost) * qt) * 100 ) / 100 ) );
					}
					else {
						$('#k_per_item').val( prod_price );
						$('#k_total_price').val ( Math.round( ( prod_price * qt) * 100 ) / 100 );

						$('#k_per_item_span').text( k_price.format( prod_price ));
						$('#k_total_price_span').text( k_price.format( Math.round( ( prod_price * qt) * 100 ) / 100 ));
					}
					
					$('#quantity_total2').val( qt );


					// SHIPPING text update
					if ( $('#k_total_price').val()*1 > 1500) {
						$('#free_shipping_info').text('Free Shipping');
					} else {
						$('#free_shipping_info').text('Shipping Calculated in Cart');
					}
					
					// K_STICKY info update
					if ( typeof($('#ks_total'))!=='undefined' ) {
						$('#ks_total').text( $('#k_total_price_span').text() );
						$('#ks_q').text( $('#quantity_total2').val() );
						$('#ks_price').text( $('#k_per_item_span').text() );
						$('#ks_del').text( $('#k_delivery_dates').val() );
					}



					// TODO - move to function ...

					// WARNINGS -  SELECT DECORATION - TODO move to after file upload
					if ( $('#art_files1 span').length > 0 && $('#front_print_type').val().length == 0 ) {
						$('#d_warning_front').text(d_warn['no_decor']);
						$('#d_warning_front').show();
					}
					if ( $('#art_files2 span').length > 0 && $('#back_print_type').val().length == 0 ) {
						$('#d_warning_back').text(d_warn['no_decor']);
						$('#d_warning_back').show();
					}
					if ( $('#art_files3 span').length > 0 && $('#left_print_type').val().length == 0 ) {
						$('#d_warning_left').text(d_warn['no_decor']);
						$('#d_warning_left').show();
					}
					if ( $('#art_files4 span').length > 0 && $('#right_print_type').val().length == 0 ) {
						$('#d_warning_right').text(d_warn['no_decor']);
						$('#d_warning_right').show();
					}
					if ( $('#art_files5 span').length > 0 && $('#neck_print_type').val().length == 0 ) {
						$('#d_warning_neck').text(d_warn['no_decor']);
						$('#d_warning_neck').show();
					}

					// WARNINGS - ADD ARTWORK
					if ( $('#art_files1 span').length == 0 && $('#front_print_type').val().length > 0 ) {
						$('#d_warning_front').text(d_warn['no_artwork']);
						$('#d_warning_front').show();
					}
					if ( $('#art_files2 span').length == 0 && $('#back_print_type').val().length > 0 ) {
						$('#d_warning_back').text(d_warn['no_artwork']);
						$('#d_warning_back').show();
					}
					if ( $('#art_files3 span').length == 0 && $('#left_print_type').val().length > 0 ) {
						$('#d_warning_left').text(d_warn['no_artwork']);
						$('#d_warning_left').show();
					}
					if ( $('#art_files4 span').length == 0 && $('#right_print_type').val().length > 0 ) {
						$('#d_warning_right').text(d_warn['no_artwork']);
						$('#d_warning_right').show();
					}
					if ( $('#art_files5 span').length == 0 && $('#neck_print_type').val().length > 0 ) {
						$('#d_warning_neck').text(d_warn['no_artwork']);
						$('#d_warning_neck').show();
					}


					// MOQ info - showing depending on total quantity and print type
					// in each side - front, back, left, right, neck
					moq_warn();

					
				

					// clear warnings - here and uploads 
					clear_warn();



					prod_price = response.prod_price * 1; // ?

					// $('#x_from').text('aa') , x_saved 
					// span class = x_from_row
					if ($('#x_from').length > 0) {
						if ( response.q_from == 0 ) {
							$('.x_from_row').hide();
						} else {
							var saved = Math.round( ( k_per_item_price - response.price_next) * 100 ) / 100 ;
							$('#x_from').text( response.q_from );
							$('#x_saved').text( '$' + saved );
							$('.x_from_row').show();

							if (debug) console.log('x_from=' + response.q_from + ' x_saved=' + saved );
						}
					}


					if (debug) console.log('ajax end, prod_price=' + response.prod_price + ' final price=' + response.price );

				},
				error: function(xhr, status, error) {
					alert('Error during pricing update: '+error + ' (' + xhr.responseJSON.error + ')' );
					if (debug) console.log(' Error: ' + xhr.responseText + ' status=' + status + ' err=' + error);
					clear_warn();
				}
			});

		}

	
		function price_update_finishing() {

			// go through all finishing tiles
			fin_all_cost = 0;
			fin_days = 0;
			$('.fin_tile').each(function(i, el) {
				if ($(this).hasClass('fin_tile_selected')) {
					//fin_all_cost = Math.round( ( fin_all_cost + $(this).find('input').val() * 1 ) * 100)/100;
					fin_all_cost = Math.round( ( fin_all_cost + $(this).find('.fin_price').text() * 1 ) * 100)/100; // span without $ !
					fin_days = Math.max(fin_days, 1 * $('#fin'+(i+1)+'_days').val() ) * 1;
				} } );

			$('#fin_all_cost').val(fin_all_cost);
			$('#k_delivery_dates').val( delivery_std_dy * 1 + fin_days + '-' + (delivery_std_dy * 1 + 2 + fin_days * 1) + ' ' + delivery_text );

			if (debug) console.log('updated finishings='+fin_all_cost + ' fin add days=' + fin_days);
		}
	
		// bulk pricing update
		$('.bulk_pricing-modal_button').click(function() {
			if (debug) ('BULK modal');

			$.ajax({
				url: plugin_url + '/kbulk.php',
				data: { 
					'price_table': price_table,
					'quantity': $('#quantity_total2').val(),

					// all fields in decorations 
					'front_print_type': $('#front_print_type').val(),
					'front_colors': $('#front_colors').val(),
					'front_ink': $('#front_ink').val(),
					'back_print_type': $('#back_print_type').val(),
					'back_colors': $('#back_colors').val(),
					'back_ink': $('#back_ink').val(),
					'left_print_type': $('#left_print_type').val(),
					'left_colors': $('#left_colors').val(),
					'left_ink': $('#left_ink').val(),
					'right_print_type': $('#right_print_type').val(),
					'right_colors': $('#right_colors').val(),
					'right_ink': $('#right_ink').val(),
					'neck_print_type': $('#neck_print_type').val(),
					'neck_colors': $('#neck_colors').val(),
					'neck_ink': $('#neck_ink').val(),

				},
				type: 'POST',
				dataType : "json",
				success: function (response) {
					if (debug) console.log( response.bulk[1] );
					var total = 0;
					for (let i=1; i < response.bulk[1].length; i++  ) {

						// row 0 = your price
						total = response.bulk[1][i]; // just price, without: response.bulk[0][i] * , without Math.round
						$($($('#table_bulk tbody tr')[0]).find('td')[i]).text( '$' + total ); 

						// SP 1 color
						total =  Math.round( ( response.bulk[2][i] * 100 ) )/100; //  price
						$($($('#table_bulk tbody tr')[2]).find('td')[i]).text( '$' + total );

						// SP 2 colors
						total =  response.bulk[3][i] ; // quantity * price
						$($($('#table_bulk tbody tr')[3]).find('td')[i]).text( '$' + total );

						// SP 3 colors
						total =  response.bulk[4][i] ; // quantity * price
						$($($('#table_bulk tbody tr')[4]).find('td')[i]).text( '$' + total );
						// SP 4 colors
						total =  response.bulk[5][i] ; // quantity * price
						$($($('#table_bulk tbody tr')[5]).find('td')[i]).text( '$' + total );

						//EMB
						total = response.bulk[6][i] ; // quantity * price
						$($($('#table_bulk tbody tr')[6]).find('td')[i]).text( '$' + total );

						//DTG
						total =  response.bulk[7][i] ; // quantity * price
						$($($('#table_bulk tbody tr')[7]).find('td')[i]).text( '$' + total );

						//WBT
						total =  response.bulk[8][i] ; // quantity * price
						$($($('#table_bulk tbody tr')[8]).find('td')[i]).text( '$' + total );

					}
				},
				error: function(xhr, status, error) {
					alert('Error occured: '+error + ' (' + xhr.responseJSON.error + ')' );
					if (debug) console.log(' Error: ' + xhr.responseText + ' status=' + status + ' err=' + error);
				}

			});

		});


		


		// INIT PART 2

		// initial check
			if ($('#front_print_type').val()=='Screen Print') $('.sp_options_front').show();
			if ($('#back_print_type').val()=='Screen Print') $('.sp_options_back').show();
			if ($('#left_print_type').val()=='Screen Print') $('.sp_options_left').show();
			if ($('#right_print_type').val()=='Screen Print') $('.sp_options_right').show();
			if ($('#neck_print_type').val()=='Screen Print') $('.sp_options_neck').show();
			calc_quantity_total();


		// initial position, overlay correction
		$('.upload_artwork').css('left', ($('.flex-active-slide').width() - $('.upload_artwork').width())/2 + 'px' ); 
		$('.upload_artwork').css('top', ($('.flex-active-slide').height() - $('.upload_artwork').height())/2 + 'px' ); 
		$('.upload_artwork').show();

		
		// corrections during window resize
		$( window ).resize(function() {
			if (debug) console.log(' RESIZE ');
			
			setTimeout(() => {
				$('.upload_artwork').css('left', ($('.flex-active-slide').width() - $('.upload_artwork').width())/2 + 'px' ); 
				$('.upload_artwork').css('top', ($('.flex-active-slide').height() - $('.upload_artwork').height())/2 + 'px' ); 
			}, 300);
			
			
			var to_center = 0;
			if ( $($('.woocommerce-product-gallery__image img')[1]).width() > $('.canvas-container').width() ) {
				to_center = ( $($('.woocommerce-product-gallery__image img')[1]).width() - $('.canvas-container').width() )/2;
				// also hide images under canvas
				//$('.kdtla_images figure img:not(.upload_artwork)').css('opacity',0);
			} else {
				//unhide images
				$('.kdtla_images figure img:not(.upload_artwork)').css('opacity',1);
			}

			//$('.canvas-container').css('left', (parseInt( $($('.woocommerce-product-gallery__image img')[1]).css('margin-left') )+to_center) + 'px' );
			set_canvas_backgrounds(0);
		});
		
		// Diego
		$(document).ready(function() {
			// Call the set_canvas_backgrounds function with a parameter (e.g., cc=0) to process all canvas elements
			set_canvas_backgrounds(0);
		});


		// K_STICKY init

		if ( typeof($('#ks_total'))!=='undefined' ) {

			// set product name and sku
			//$('#ks_title').text( $($('.product_title')[0]).text() );
			//$('#ks_sku').text( $($('.sku_wrapper')[0]).text().replace('- SKU:','') ); // or .sku_wrapper .sku
		
			//da - nested title/sku fix
			// Assuming .product_title contains "Richardson – Performance Trucker Cap"
			var productTitle = $($('.product_title')[0]).text();
			var titleAfterHyphen = productTitle.split('–')[1].trim();

			// Update the text of #ks_title without affecting #ks_sku
			$('#ks_title').contents().filter(function() {
				return this.nodeType === 3; // Filter text nodes
			}).first().replaceWith(titleAfterHyphen);

			// Update the text of #ks_sku separately
			$('#ks_sku').text($($('.sku_wrapper')[0]).text().replace('- SKU:', '').trim());
			$('.ks_sku2').text($($('.sku_wrapper')[0]).text().replace('- SKU:', '').trim());


			
			
			// move div to position below header
			$('#k_sticky').insertBefore('#primary');
			//$('#primary').css('margin-top','4em');
			
			$('#ks_add').click(function(){ $('form.cart').submit(); });
		}


		//gclid from cookie to cf7
			/*
			function C(k){var x=document.cookie.match('(^|; )'+k+'=([^;]*)'||0);if(x) return x[2]; else return '';};
			if(C('dtla-gclid').length>0){jQuery('[name=gclid]').val(C('dtla-gclid'))};
			if(C('dtla-utm_source').length>0){jQuery('[name=utmsource]').val(C('dtla-utm_source'))};
			if(C('dtla-utm_campaign').length>0){jQuery('[name=utmcampaign]').val(C('dtla-utm_campaign'))};

			console.log('dtla-gclid='+C('dtla-gclid')+' s='+C('dtla-utm_source') +' camp='+C('dtla-utm_campaign') );
			*/



	}) //end load
    

}( jQuery ) );

