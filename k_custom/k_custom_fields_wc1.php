<?php
/*
Author: Konrad G
Author URI: www.kgretk.com
File: WooCommerce hooks and functions for custom fields - for dtlaprint.com
Changelog:
2023-01-25 v1
2023-03-25 added Patches print type
2023-05-22 added styles1,2,3,4,5
*/


defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
error_reporting(E_ALL);


/**
 * Output custom fields.
 */
function k_add_to_cart_custom_fields() {
	global $k_editing, $cart_item_key, $k_edit_c_arr, $k_edit_d, $k_edit_fin, $k_edit_ksession, $k_edit_blanks, $k_edit_art1, $k_edit_art2, $k_edit_art3, $k_edit_art4, $k_edit_art5, $k_edit_files1, $k_edit_files2, $k_edit_files3, $k_edit_files4, $k_edit_files5, $k_edit_art_pos;
	
	$k_editing = 0; // for JS

	$k_edit_style1 = '';
	$k_edit_style2 = '';
	$k_edit_style3 = '';
	$k_edit_style4 = '';
	$k_edit_style5 = '';

	$k_edit_q = array();
	$k_edit_c = '';
	$k_edit_c_arr = array();
	$k_edit_html = '';
	$k_edit_d = array();
	$k_edit_fin = '';
	$k_edit_notes = '';
	$k_edit_ksession = '';
	$k_edit_blanks = '';

	$k_edit_art1 = '';
	$k_edit_art2 = '';
	$k_edit_art3 = '';
	$k_edit_art4 = '';
	$k_edit_art5 = '';

	$k_edit_files1 = '';
	$k_edit_files2 = '';
	$k_edit_files3 = '';
	$k_edit_files4 = '';
	$k_edit_files5 = '';

	$k_edit_art_pos = '';

	// is item defined? product to be edited
	if ( isset($_GET) && isset($_GET['item'])) {
		$cart_item_key = (string)$_GET['item'];
		$cart = WC()->cart->get_cart();
		if ( isset($cart[ $cart_item_key ]) ) {
			$cart_item = $cart[ $cart_item_key ];
			$k_editing = 1;

			$ksizes = explode('|', $cart_item['ksizes']);
			$colors = array('1', '2', '3', '4', '5');

			foreach( $colors as $k=>$c) {
				$ci = 'c'.$c.'_name';
				$chtml = 'c'.$c.'_html'; 

				$k_edit_q[$c] = array();

				//check if color is defined
				if ( isset( $cart_item[$ci] ) && strlen($cart_item[$ci])>0 ) {
					//echo ' '.$ci.'='.$cart_item[$ci] . ' html ' . $cart_item[$chtml];

					$k_edit_c .= $cart_item[$ci] . ',';
					$k_edit_c_arr[] = $cart_item[$ci];
					//$k_edit_html .= $cart_item[$chtml] . ',';


					foreach( $ksizes as $s ) {
						$i = 'q'.$c.'_'. trim($s) ;

						if ( isset( $cart_item[$i] ))
							$k_edit_q[$c][$i] = $cart_item[$i];
						else
							$k_edit_q[$c][$i] = 0;
					}

				}

			}
			//print_r($k_edit_c_arr);
			//print_r($k_edit_q); // JS table with quantities per color

			// get styles for editing
			if ( isset( $cart_item['k_edit_style1'] ) && strlen($cart_item['k_edit_style1'])>0 )
				$k_edit_style1 = $cart_item['k_edit_style1'];
			if ( isset( $cart_item['k_edit_style2'] ) && strlen($cart_item['k_edit_style2'])>0 )
				$k_edit_style2 = $cart_item['k_edit_style2'];
			if ( isset( $cart_item['k_edit_style3'] ) && strlen($cart_item['k_edit_style3'])>0 )
				$k_edit_style3 = $cart_item['k_edit_style3'];
			if ( isset( $cart_item['k_edit_style4'] ) && strlen($cart_item['k_edit_style4'])>0 )
				$k_edit_style4 = $cart_item['k_edit_style4'];
			if ( isset( $cart_item['k_edit_style5'] ) && strlen($cart_item['k_edit_style5'])>0 )
				$k_edit_style5 = $cart_item['k_edit_style5'];

			// get decoration options for product editing
			if ( isset( $cart_item['front_print_type'] ) && strlen($cart_item['front_print_type'])>0 ) {
				$k_edit_d['front_print_type'] = $cart_item['front_print_type'];
				if ( isset( $cart_item['front_colors'] )) $k_edit_d['front_colors'] = $cart_item['front_colors'];
				if ( isset( $cart_item['front_ink'] )) $k_edit_d['front_ink'] = trim($cart_item['front_ink']);
			}
			if ( isset( $cart_item['back_print_type'] ) && strlen($cart_item['back_print_type'])>0 ) {
				$k_edit_d['back_print_type'] = $cart_item['back_print_type'];
				if ( isset( $cart_item['back_colors'] )) $k_edit_d['back_colors'] = $cart_item['back_colors'];
				if ( isset( $cart_item['back_ink'] )) $k_edit_d['back_ink'] = trim($cart_item['back_ink']);
			}
			if ( isset( $cart_item['left_print_type'] ) && strlen($cart_item['left_print_type'])>0 ) {
				$k_edit_d['left_print_type'] = $cart_item['left_print_type'];
				if ( isset( $cart_item['left_colors'] )) $k_edit_d['left_colors'] = $cart_item['left_colors'];
				if ( isset( $cart_item['left_ink'] )) $k_edit_d['left_ink'] = trim($cart_item['left_ink']);
			}
			if ( isset( $cart_item['right_print_type'] ) && strlen($cart_item['right_print_type'])>0 ) {
				$k_edit_d['right_print_type'] = $cart_item['right_print_type'];
				if ( isset( $cart_item['right_colors'] )) $k_edit_d['right_colors'] = $cart_item['right_colors'];
				if ( isset( $cart_item['right_ink'] )) $k_edit_d['right_ink'] = trim($cart_item['right_ink']);
			}
			if ( isset( $cart_item['neck_print_type'] ) && strlen($cart_item['neck_print_type'])>0 ) {
				$k_edit_d['neck_print_type'] = $cart_item['neck_print_type'];
				if ( isset( $cart_item['neck_colors'] )) $k_edit_d['neck_colors'] = $cart_item['neck_colors'];
				if ( isset( $cart_item['neck_ink'] )) $k_edit_d['neck_ink'] = trim($cart_item['neck_ink']);
			}

			// finishings for editing
			if ( isset( $cart_item['fin_all'] ) && strlen($cart_item['fin_all'])>0 )
				$k_edit_fin = $cart_item['fin_all'];

			if ( isset( $cart_item['design_notes'] ) && strlen($cart_item['design_notes'])>0 )
				$k_edit_notes = $cart_item['design_notes'];

			if ( isset( $cart_item['ksession'] ) && strlen($cart_item['ksession'])>0 )
				$k_edit_ksession = $cart_item['ksession'];

			if ( isset( $cart_item['ordering_blanks'] ) && strlen($cart_item['ordering_blanks'])>0 )
				$k_edit_blanks = $cart_item['ordering_blanks'];

			if ( isset( $cart_item['art1_preview'] ) && strlen($cart_item['art1_preview'])>0 )
				$k_edit_art1 = $cart_item['art1_preview'];
			if ( isset( $cart_item['art2_preview'] ) && strlen($cart_item['art2_preview'])>0 )
				$k_edit_art2 = $cart_item['art2_preview'];
			if ( isset( $cart_item['art3_preview'] ) && strlen($cart_item['art3_preview'])>0 )
				$k_edit_art3 = $cart_item['art3_preview'];
			if ( isset( $cart_item['art4_preview'] ) && strlen($cart_item['art4_preview'])>0 )
				$k_edit_art4 = $cart_item['art4_preview'];
			if ( isset( $cart_item['art5_preview'] ) && strlen($cart_item['art5_preview'])>0 )
				$k_edit_art5 = $cart_item['art5_preview'];

			if ( isset( $cart_item['art1_files'] ) && strlen($cart_item['art1_files'])>0 )
				$k_edit_files1 = $cart_item['art1_files'];
			if ( isset( $cart_item['art2_files'] ) && strlen($cart_item['art2_files'])>0 )
				$k_edit_files2 = $cart_item['art2_files'];
			if ( isset( $cart_item['art3_files'] ) && strlen($cart_item['art3_files'])>0 )
				$k_edit_files3 = $cart_item['art3_files'];
			if ( isset( $cart_item['art4_files'] ) && strlen($cart_item['art4_files'])>0 )
				$k_edit_files4 = $cart_item['art4_files'];
			if ( isset( $cart_item['art5_files'] ) && strlen($cart_item['art5_files'])>0 )
				$k_edit_files5 = $cart_item['art5_files'];

			if ( isset( $cart_item['_art_pos'] ) && strlen($cart_item['_art_pos'])>0 )
				$k_edit_art_pos = $cart_item['_art_pos'];
			

			//if (null !== get_post_custom_values('sizes'))
			//	echo ' ksizes=' . get_post_custom_values('sizes')[0] ;

			add_filter('woocommerce_product_single_add_to_cart_text','k_customize_add_to_cart_button_woocommerce');


		}
		else {
			status_header(403); //??
			nocache_headers();
			echo "<script>jQuery('.entry-summary .product_title').hide();jQuery('.entry-summary .sku_wrapper').hide();jQuery('.entry-summary .share_wrapper').hide();jQuery('.entry-summary a').hide();jQuery('.woocommerce-product-gallery').remove();</script><h1 style='color:red;'>No such item in cart!!</h1>";
			exit;
		}
	}


	require_once(KDTLA_PATH .'1_templates/dtla_template_product.php');
	
}
add_action( 'woocommerce_before_add_to_cart_button', 'k_add_to_cart_custom_fields', 10 );

// for editing
function k_customize_add_to_cart_button_woocommerce(){
	return __('Update this product in the cart', 'woocommerce');
}



/**
 * Output buttons after Add to Cart.
 */
function k_add_to_cart_buttons() {
	// Sanja added bulk_pricing-modal-toggle
?>
<a href="#send_my_quote" class="decoration_links send_my_quote_button">Send Me My Quote</a>
<a href="#bulk_pricing-modal" class="decoration_links bulk_pricing-modal_button bulk_pricing-modal-toggle">Bulk Price Breaks</a>
<p style="color: #F05C4E;font-size: 14px;padding-top: 10px;display: inline-block;">Questions? There is an option at the end to submit for review, no payment required. Your account rep will review the order and reach out to assist with any questions.</p>
	
</p>

<?php
}

add_action( 'woocommerce_after_add_to_cart_button', 'k_add_to_cart_buttons', 10 );


/**
 * Add custom field text to cart item.
 *
 * @param array $cart_item_data
 * @param int   $product_id
 * @param int   $variation_id
 *
 * @return array
 */
function k_add_custom_fields_text_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
	global $product, $custom_fields;
	
	// editing item in the cart
	if ( strlen(filter_input( INPUT_POST, 'kvariation' ) ?? '' )>0 ) {
		$cart_item_key = filter_input( INPUT_POST, 'kvariation' );
		/*
		$cart = WC()->cart->get_cart();
		if ( isset($cart[ $cart_item_key ]) ) {
			$cart_item = $cart[ $cart_item_key ];
		}
		*/
		
		
		// Get the cart item data
		
		$cart_item_data = WC()->cart->get_cart_item( $cart_item_key );

		// Update the attributes
		//$cart_item_data['variation']['attribute_name'] = 'new_attribute_value';
		foreach ($custom_fields as $cf):
			$cf = (object)$cf;
	
			$custom_input = filter_input( INPUT_POST, $cf->key );
	
			if ( !empty( $custom_input ) ) {
				$cart_item_data[ $cf->key ] = $custom_input;
			}
			// quantities zero
			if ( substr( $cf->key, 0, 1 ) == 'q' && empty( $custom_input ) ) {
				$cart_item_data[ $cf->key ] = 0;
			}
	
		endforeach;

		// Save the updated cart item data
		//WC()->cart->set_cart_item_data( $cart_item_key, $cart_item_data ); // no such method!
		// remove previous product 
		WC()->cart->remove_cart_item( $cart_item_key );
		//echo ' removed '; print_r($cart_item_data); die();
		return $cart_item_data;

	} else {
		// new item to the cart
		foreach ($custom_fields as $cf):
			$cf = (object)$cf;
	
			$custom_input = filter_input( INPUT_POST, $cf->key );
	
			if ( !empty( $custom_input ) ) {
				$cart_item_data[ $cf->key ] = $custom_input;
			}
	
		endforeach;
		return $cart_item_data;
	}
	
	//echo ' var='.$variation_id; print_r($cart_item_data); die();
	
}

add_filter( 'woocommerce_add_cart_item_data', 'k_add_custom_fields_text_to_cart_item', 10, 3 );


/**
 * Redirect to cart after editing - filter not called!
 *
 *  
 * 
 */
/*
function k_custom_add_to_cart_redirect( $redirect_url, $product ) {

    if ( !isset($_GET['add-to-cart']) || !is_numeric($_REQUEST['add-to-cart']) ) {
        return $redirect_url;
	}

	if ( strlen(filter_input( INPUT_POST, 'kvariation' ))>0 ) {
		$redirect_url = '/cart/'; //WC_Helper::get_cart_url(); //  echo ' redir ' ; die();// wc_get_cart_url(); ?
		$redirect_url = add_query_arg( 'product_added', 'true', $redirect_url );

		//wp_safe_redirect( wc_get_cart_url() );
		die(' redit ');
		return $redirect_url;
	} else {
		echo ' no kvar '; die();
	}
	die(' no ');
}
add_filter( 'woocommerce_add_to_cart_redirect', 'k_custom_add_to_cart_redirect', 10, 2 );
*/



/**
 * Display custom field text in the cart.
 *
 * @param array $item_data
 * @param array $cart_item
 *
 * @return array
 */
function k_display_custom_fields_text_cart( $item_data, $cart_item ) {
	global $custom_fields;

	foreach ($custom_fields as $cf):
		$cf = (object)$cf;

		if ( !empty( $cart_item[ $cf->key ] ) ) {
			$item_data[] = array(
				'key'     => __( $cf->label, 'atn' ),
				'value'   => wc_clean( $cart_item[ $cf->key ] ),
				'display' => '',
			);
		}


	endforeach;

	return $item_data;
}

add_filter( 'woocommerce_get_item_data', 'k_display_custom_fields_text_cart', 10, 2 );



/**
 * Add custom fields text to order.
 *
 * @param WC_Order_Item_Product $item
 * @param string                $cart_item_key
 * @param array                 $values
 * @param WC_Order              $order
 */
function k_add_custom_fields_text_to_order_items( $item, $cart_item_key, $values, $order ) {

	global $custom_fields;

	foreach ($custom_fields as $cf):
		$cf = (object)$cf;

		if ( !empty( $values[ $cf->key ] ) ) {
			$item->add_meta_data( __( $cf->label, 'atn' ), $values[ $cf->key ] );
		}

	endforeach;
}

add_action( 'woocommerce_checkout_create_order_line_item', 'k_add_custom_fields_text_to_order_items', 10, 4 );




/**
 * Output additional content before cart.
 */
function k_before_cart_table() {

	require_once( KDTLA_PATH . '1_templates/dtla_template_cart_top.php');
	
}
add_action( 'woocommerce_before_cart_table', 'k_before_cart_table', 10 );



/**
 * Output row for each product in the cart.
 */
function k_cart_row_info( $cart_item ) {

	require( KDTLA_PATH . '1_templates/dtla_template_cart_row.php');
	
}
add_action( 'wc_k_cart_row_info', 'k_cart_row_info', 10 );






/**
 * Output links/icons after each product in a cart, EDIT link
 */
function k_after_cart_item_name( $cart_item, $cart_item_key, $product_permalink = null ) {

	//TODO not needed?
	// no Edit word
	if (isset($product_permalink)) {
		echo ' &nbsp; ';
		//echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s?item='. $cart_item_key .'" class="k_cart_edit_link" title="Edit or copy" >&nbsp;</a>', esc_url( $product_permalink ) ), $cart_item, $cart_item_key ) );
	}
}
add_action( 'woocommerce_after_cart_item_name', 'k_after_cart_item_name', 10, 3 );

/**
 * Output additional content before cart.
 */
function k_after_cart_table() {

	//require_once('dtla_template_cart_bottom.php'); // TODO not needed?
	
}
add_action( 'woocommerce_after_cart_table', 'k_after_cart_table', 10 );










/* ***********************		KDTLA FUNCTIONALITY ******************	*/


// input options only if 1 in a location/type table   , && $key<>trim('Blank')
function k_input1($class, $input, $edit = null ) { //echo ' xxxx '; print_r($edit);
	?>
		<select id="<?php echo $class; ?>" name="<?php echo $class; ?>" >
                    
			<option value="">Select a method</option>
                <?php foreach($input as $key => $value): 
						if ( $value ): ?>
                    <option value="<?php echo $key; ?>" <?php if (isset($edit[$class])) if ($edit[$class] == $key) echo ' selected="selected" '; ?> ><?php echo $key; ?></option>
                <?php endif; 
					endforeach; ?>
                    
        </select>
	<?php
}

// all input options - TODO change select to :  # of colors, Ink type
function k_input2($class, $input, $edit = null) { //echo ' xxxx '; print_r($edit);
	?>
		<select id="<?php echo $class; ?>" name="<?php echo $class; ?>" >
			<option value="">select</option>
                <?php foreach($input as $key => $value): ?>
                    <option value="<?php echo trim($value); ?>" <?php if (isset($edit[$class])) if ($edit[$class] == trim($value)) echo ' selected="selected" '; ?> ><?php echo trim($value); ?></option>
                <?php endforeach; ?>
                    
        </select>
	<?php
	// without <option value="">select</option>
}

// quantities, field names: q1_S, q1_M ... q2_S ..
function k_inputq($class, $color_no, $input) {
    global $sizes_names;
    foreach($input as $k=>$i) {
	?>
		<td><input type="number" min="0" max="1000" class="<?php echo $class; ?>" name="<?php echo $color_no. '_' .trim($sizes_names[$k]); ?>" placeholder="0" /></td>
	<?php
    }
}


// output product info within a template - k_template_product.php


function k_colors() {
	global $product, $custom_fields, $k_edit_c_arr, $sku;
	$styles = '';

	if (null !== get_post_custom_values('product_id') && null !== get_post_custom_values('product_id')[0])
		$product_id = get_post_custom_values('product_id')[0];
	
	//	echo ' PROD ID='.$product_id; echo ' SKU ='.$product->get_sku();

    if (null !== get_post_custom_values('sizes') && null !== get_post_custom_values('styles')[0])
        $styles = get_post_custom_values('styles')[0];
        
        //print_r(explode('##', $styles));echo ' <br />';
        if (strlen($styles)>2) {
			$t = explode('##', $styles);
			$t[0] = null;
			foreach($t as $tt) {
			if (isset($tt))
				list($style_id[], $color_name[], $color_html[]) = explode('|', $tt);
			}
			//print_r($style_id); print_r($color_name); print_r($color_html);
			if (is_array($k_edit_c_arr))
				foreach($color_html as $k=>$co) {
					$sel = '';
					if (in_array($color_name[$k], $k_edit_c_arr)) $sel = 'kcolor_box_selected';
					echo '<span class="kcolor_box '.$sel.'" style="background-color:#'. $co . '" data="'.$color_name[$k] .'" data-style="' .$product_id.'-'.$style_id[$k] .'"  ></span>'; // 6/15 added $product_id
					
					//echo '<span class="kcolor_box" style="background-color:#'. $co . '" data="'.$color_name[$k] .'" title="'.$color_name[$k] .'"></span>';
					// TODO add style if editing
				}
		}
        echo '<span class="kcolor_name" id="kcolor_name"></span>';
	//onclick - change color; on mouse over - show thumb, color name - in JS
	
}

// colors dropdown - can't put span with color inside option...
function k_colors_dropdown($text) {

	$styles = '';

	if (null !== get_post_custom_values('product_id') && null !== get_post_custom_values('product_id')[0])
		$product_id = get_post_custom_values('product_id')[0];
	

    if (null !== get_post_custom_values('sizes') && null !== get_post_custom_values('styles')[0])
        $styles = get_post_custom_values('styles')[0];
        
        //print_r(explode('##', $styles));echo ' <br />';
        if (strlen($styles)>2) {
			$t = explode('##', $styles);
			$t[0] = null;
			foreach($t as $tt) {
			if (isset($tt))
				list($style_id[], $color_name[], $color_html[]) = explode('|', $tt);
			}
			
			echo '<select class="kcolors_dropdown">';
				echo '<option>'. $text . '</option>';
				foreach($color_html as $k=>$co) {
					echo '<option  data="'.$color_name[$k] .'" data-style="' .$product_id.'-'.$style_id[$k] .'"   >';
					echo  $color_name[$k]; // '&#9724; '
					echo '</option>';
					
				}
			echo '</select>';
		}

	
	// TODO if editing?
}

function k_quantities() {
	global $product, $sizes_names, $kquantities;
	
    if (null !== get_post_custom_values('sizes'))
		$sizes_names = explode('|', get_post_custom_values('sizes')[0]);
	//print_r( explode('|', get_post_custom_values('sizes')[0]) );
	//print_r($sizes_names);

    $kquantities = [
        'color1' => array(),
        'color2' => array(),
        'color3' => array(),
        'color4' => array(),
		'color5' => array()
    ];
    foreach($sizes_names as $ks) {
        $kquantities['color1'][] = 0;
        $kquantities['color2'][] = 0;
        $kquantities['color3'][] = 0;
        $kquantities['color4'][] = 0;
		$kquantities['color5'][] = 0;
    }
    //TODO what if more colors?

	//print_r($kquantities);
	// k_inputq ( class, color_no, input)

	?>

	<table style="width:99%;" id="table_q">
		<thead><td></td><?php foreach($sizes_names as $ks) { echo '<td>'.$ks.'</td>'; } ?><td></td></thead>
		<tr>
			<td><span class="kcolor_box2" style="background-color:#fff" data=""></span>
				<input type="text" class="c_name" name="c1_name" placeholder="Select color..." readonly tabindex="-1" />
				<input type="text" class="c_html" name="c1_html" />
			</td>
			<?php k_inputq('q1', 'q1', $kquantities['color1']); ?> 
			<td><span class="color_delete"><i class="fa fa-trash"></i> </span></td>
		</tr>
		<tr class="hidden">
			<td><span class="kcolor_box2" style="background-color:#fff" data=""></span>
				<input type="text" class="c_name" name="c2_name" placeholder="Select color..." readonly tabindex="-1" />
				<input type="text" class="c_html" name="c2_html" />
			</td>
			<?php k_inputq('q1', 'q2', $kquantities['color2']); ?> 
			<td><span class="color_delete"><i class="fa fa-trash"></i> </span></td>
		</tr>
		<tr class="hidden">
			<td><span class="kcolor_box2" style="background-color:#fff" data=""></span>
				<input type="text" class="c_name" name="c3_name" placeholder="Select color..." readonly tabindex="-1" />
				<input type="text" class="c_html" name="c3_html" />
			</td>
			<?php k_inputq('q1', 'q3', $kquantities['color3']); ?> 
			<td><span class="color_delete"><i class="fa fa-trash"></i> </span></td>
		</tr>
		<tr class="hidden">
			<td><span class="kcolor_box2" style="background-color:#fff" data=""></span>
				<input type="text" class="c_name" name="c4_name" placeholder="Select color..." readonly tabindex="-1" />
				<input type="text" class="c_html" name="c4_html" />
			</td>
			<?php k_inputq('q1', 'q4', $kquantities['color4']); ?> 
			<td><span class="color_delete"><i class="fa fa-trash"></i> </span></td>
		</tr>
		<tr class="hidden">
			<td><span class="kcolor_box2" style="background-color:#fff" data=""></span>
				<input type="text" class="c_name" name="c5_name" placeholder="Select color..." readonly tabindex="-1" />
				<input type="text" class="c_html" name="c5_html" />
			</td>
			<?php k_inputq('q1', 'q5', $kquantities['color5']); ?> 
			<td><span class="color_delete"><i class="fa fa-trash"></i> </span></td>
		</tr>
		
	</table>


	<?php

	
}

function k_positions() {
	global $product, $custom_fields;
	global $k_edit_d;

	//$print_locations_screen = explode('|', get_post_custom_values('print_locations_screen')[0]);
	// update options from product meta, order like in def
	if (null !== get_post_custom_values('sizes'))
		$custom_fields[0]['sizes'] = explode('|', get_post_custom_values('sizes')[0]);

	if (null !== get_post_custom_values('print_locations_screen'))
		$custom_fields[1]['options'] = explode('|', get_post_custom_values('print_locations_screen')[0]);

	

	// array for location / print type combinations - Blank means no print, not blank samples... ?
	// removed Blank, or add? 'Blank' => 1,
	// WBT changed to Water-based Transfer ? to Waterbase Transfers
	// 3/25 added Patches
	$k_loc_type = array(
		'Front' => array( 'Screen Print' => 0, 'Embroidery' => 0, 'DTG' => 0, 'Waterbase Transfers' => 0, 'Patches' => 0 ),
		'Back' => array( 'Screen Print' => 0, 'Embroidery' => 0, 'DTG' => 0, 'Waterbase Transfers' => 0, 'Patches' => 0 ),
		'Left Sleeve' => array( 'Screen Print' => 0, 'Embroidery' => 0, 'DTG' => 0, 'Waterbase Transfers' => 0, 'Patches' => 0 ),
		'Right Sleeve' => array( 'Screen Print' => 0, 'Embroidery' => 0, 'DTG' => 0, 'Waterbase Transfers' => 0, 'Patches' => 0 ),
		'Left' => array( 'Screen Print' => 0, 'Embroidery' => 0, 'DTG' => 0, 'Waterbase Transfers' => 0, 'Patches' => 0 ),
		'Right' => array('Screen Print' => 0, 'Embroidery' => 0, 'DTG' => 0, 'Waterbase Transfers' => 0, 'Patches' => 0 ),
		'Neck Label' => array('Screen Print' => 0, 'Embroidery' => 0, 'DTG' => 0, 'Waterbase Transfers' => 0, 'Patches' => 0 ),

		//'Left Side' => array('Blank' => 1, 'Screen Print' => 0, 'Embroidery' => 0, 'DTG' => 0, 'WBT' => 0),
		//'Right Side' => array('Blank' => 1, 'Screen Print' => 0, 'Embroidery' => 0, 'DTG' => 0, 'WBT' => 0),
		// left chest, right chest ?
	);
	/*
	if (null !== get_post_custom_values('print_locations_screen')) {
		$pl_scr = explode('|', get_post_custom_values('print_locations_screen')[0]);
		foreach ($pl_scr as $k=>$v) {
			$k_loc_type[trim($v)]['Screen Print'] = 1;
		}
	}
	if (null !== get_post_custom_values('print_locations_Embroidery')) {
		$pl_scr = explode('|', get_post_custom_values('print_locations_Embroidery')[0]);
		foreach ($pl_scr as $k=>$v) {
			$k_loc_type[trim($v)]['Embroidery'] = 1;
		}
	}
	if (null !== get_post_custom_values('print_locations_DTG')) {
		$pl_scr = explode('|', get_post_custom_values('print_locations_DTG')[0]);
		foreach ($pl_scr as $k=>$v) {
			$k_loc_type[trim($v)]['DTG'] = 1;
		}
	}
	if (null !== get_post_custom_values('print_locations_WBTransfer')) {
		$pl_scr = explode('|', get_post_custom_values('print_locations_WBTransfer')[0]);
		foreach ($pl_scr as $k=>$v) {
			$k_loc_type[trim($v)]['WBT'] = 1;
		}
	}
	*/

	// get product print_options
	$print_options2 = array();
	if (null !== get_post_custom_values('print_options')) {
		$print_options = explode('|', get_post_custom_values('print_options')[0]);
		foreach ($print_options as $k=>$v)
			$print_options2[trim($v)] = 1;
	}
	

	// change to decoration_locations
	if (null !== get_post_custom_values('decoration_locations')) {
		$pl_scr = explode('|', get_post_custom_values('decoration_locations')[0]);
		foreach ($pl_scr as $k=>$v) {
			$k_loc_type[trim($v)]['Screen Print'] = array_key_exists('Screen Print', $print_options2 );
			$k_loc_type[trim($v)]['Embroidery'] = array_key_exists('Embroidery', $print_options2 );
			$k_loc_type[trim($v)]['DTG'] = array_key_exists('DTG', $print_options2 );
			$k_loc_type[trim($v)]['Waterbase Transfers'] = array_key_exists('Waterbase Transfers', $print_options2 );
			$k_loc_type[trim($v)]['Patches'] = array_key_exists('Patches', $print_options2 );
			//Waterbase Transfers or Water-based Transfer ?
			// without Blank
		}
	}



	// colors/sizes for inputs

	$max_colors_range = range(1, 6);

	// TODO leave in case defined in xml?
	/*
	if (null !== get_post_custom_values('max_colors')) {
		$max_colors_range = range(1, get_post_custom_values('max_colors')[0]);
	}
	*/

	// defined here, not xml
	$print_ink_types1 = "Oil Based | Water Based | Puff | Stretch | Flocking | 3M Reflective";
	$print_ink_types = explode('|', $print_ink_types1 );

	// TODO leave in case defined in xml?
	/*
	if (null !== get_post_custom_values('print_ink_types')) {
		$print_ink_types = explode('|', get_post_custom_values('print_ink_types')[0]);
	}
	*/

	// emb sizes not used
	/*
	if (null !== get_post_custom_values('print_sizes_embroidery')) {
		$print_sizes_embroidery = explode('|', get_post_custom_values('print_sizes_embroidery')[0]);
	}
	*/
	//echo '<pre>';	print_r($k_loc_type);
	//print_r( $k_loc_type['Left'] ); echo ' sum='.array_sum($k_loc_type['Left']);

	?>

	<table id="table_d">
		<thead><td>Position</td><td>Decoration</td><td>
			<!-- Colors <span class="ink-label"><a href="#ink-typepopup">Ink Type <i class="far fa-question-circle"></i></a></span> -->
				</td><td>Artwork</td></thead>
		<tr <?php if ( array_sum($k_loc_type['Front'])==0 ) echo 'style="display:none"'; ?>>
			<td>Front</td>
			<td> <?php k_input1('front_print_type', $k_loc_type['Front'], $k_edit_d ); ?> </td>
			<td class="sp_empty"> 
				<span class="sp_options_front">
				<span class="colors-label">Colors </span><span class="ink-label"><a href="#ink-typepopup">Ink Type <i class="far fa-question-circle"></i></a></span>	
				<?php k_input2('front_colors', $max_colors_range, $k_edit_d); k_input2('front_ink', $print_ink_types, $k_edit_d);  ?> </span></td>
			<td>
				<a href="javascript:void(0)" class="artwork_link" data-canvas_id="1" ><i class="fa-solid fa-upload"></i>Upload</a> <!-- <img src="" id="img_ajax1" width="40px" /> -->
				<span id="art_files1"></span>
			</td>
		</tr>
		<tr class="d_warning_row"><td colspan="4" id="d_warning_front"> </td></tr>
		<tr <?php if ( array_sum($k_loc_type['Back'])==0 ) echo 'style="display:none"'; ?>>
			<td>Back</td>
			<td> <?php k_input1('back_print_type', $k_loc_type['Back'], $k_edit_d ); ?> </td>
			<td class="sp_empty">
				<span class="sp_options_back">
				<span class="colors-label">Colors </span><span class="ink-label"><a href="#ink-typepopup">Ink Type <i class="far fa-question-circle"></i></a></span>
				<?php k_input2('back_colors', $max_colors_range, $k_edit_d ); k_input2('back_ink', $print_ink_types, $k_edit_d );  ?> </span></td>
			<td>
				<a href="javascript:void(0)" class="artwork_link" data-canvas_id="2" ><i class="fa-solid fa-upload"></i>Upload</a>
				<span id="art_files2"></span>
			</td>
		</tr>
		<tr class="d_warning_row"><td colspan="4" id="d_warning_back"> </td></tr>
		<tr <?php if ( array_sum($k_loc_type['Left'])==0 ) echo 'style="display:none"'; ?> >
			<td>Left</td>
			<td> <?php k_input1('left_print_type', $k_loc_type['Left'], $k_edit_d );  ?> </td>
			<td class="sp_empty">
				<span class="sp_options_left">
				<span class="colors-label">Colors </span><span class="ink-label"><a href="#ink-typepopup">Ink Type <i class="far fa-question-circle"></i></a></span>
				<?php k_input2('left_colors', $max_colors_range, $k_edit_d ); k_input2('left_ink', $print_ink_types, $k_edit_d );  ?> </span></td>
			<td>
				<a href="javascript:void(0)" class="artwork_link" data-canvas_id="3" ><i class="fa-solid fa-upload"></i>Upload</a>
				<span id="art_files3"></span>
			</td>
		</tr>
		<tr class="d_warning_row"><td colspan="4" id="d_warning_left"> </td></tr>
		<tr <?php if ( array_sum($k_loc_type['Right'])==0 ) echo 'style="display:none"'; ?>>
			<td>Right</td>
			<td> <?php k_input1('right_print_type', $k_loc_type['Right'], $k_edit_d ); ?> </td>
			<td class="sp_empty">
				<span class="sp_options_right">
				<span class="colors-label">Colors </span><span class="ink-label"><a href="#ink-typepopup">Ink Type <i class="far fa-question-circle"></i></a></span>
				<?php k_input2('right_colors', $max_colors_range, $k_edit_d ); k_input2('right_ink', $print_ink_types, $k_edit_d );  ?> </span></td>
			<td>
				<a href="javascript:void(0)" class="artwork_link" data-canvas_id="4" ><i class="fa-solid fa-upload"></i>Upload</a>
				<span id="art_files4"></span>
			</td>
		</tr>
		<tr class="d_warning_row"><td colspan="4" id="d_warning_right"> </td></tr>
		<tr <?php if ( array_sum($k_loc_type['Neck Label'])==0 ) echo 'style="display:none"'; ?>>
			<td>Neck Label</td>
			<td> <?php k_input1('neck_print_type', $k_loc_type['Neck Label'], $k_edit_d ); ?> </td>
			<td class="sp_empty">
				<span class="sp_options_neck">
				<span class="colors-label">Colors </span><span class="ink-label"><a href="#ink-typepopup">Ink Type <i class="far fa-question-circle"></i></a></span>
				<?php k_input2('neck_colors', $max_colors_range, $k_edit_d ); k_input2('neck_ink', $print_ink_types, $k_edit_d );  ?> </span></td>
			<td>
				<a href="javascript:void(0)" class="artwork_link" data-canvas_id="5" ><i class="fa-solid fa-upload"></i>Upload</a>
				<span id="art_files5"></span>
			</td>
		</tr>
		<tr class="d_warning_row"><td colspan="4" id="d_warning_neck"> </td></tr>
	</table>

	<?php
	//print_r($print_sizes_embroidery);
	//print_r($max_colors_range);

}

function k_finishing() {
	global $product, $custom_fields, $k_edit_fin;

	//if (null !== get_post_custom_values('print_locations_screen'))
	//	print_r( explode('|', get_post_custom_values('print_locations_screen')[0]) );

	// fields hidden via css 
	?>
	<input type="text" id="fin_all" name="fin_all" style="width:45%" value="<?php echo $k_edit_fin; ?>"/>
	<input type="text" id="fin_all_cost" name="fin_all_cost" style="width:45%" />
	<input type="text" id="fin1_cost" name="fin1_cost" style="width:15%" />
	<input type="text" id="fin2_cost" name="fin2_cost" style="width:15%" />
	<input type="text" id="fin3_cost" name="fin3_cost" style="width:15%" />
	<input type="text" id="fin4_cost" name="fin4_cost" style="width:15%" />
	<input type="text" id="fin5_cost" name="fin5_cost" style="width:15%" />
	<input type="text" id="fin6_cost" name="fin6_cost" style="width:15%" />

	<input type="text" id="fin1_days" name="fin1_days" style="width:15%" />
	<input type="text" id="fin2_days" name="fin2_days" style="width:15%" />
	<input type="text" id="fin3_days" name="fin3_days" style="width:15%" />
	<input type="text" id="fin4_days" name="fin4_days" style="width:15%" />
	<input type="text" id="fin5_days" name="fin5_days" style="width:15%" />
	<input type="text" id="fin6_days" name="fin6_days" style="width:15%" />
	<?php

}

// additional fields, for cart
function k_add_fields() {
	global $product, $custom_fields, $sizes_names, $kquantities, $k_editing, $cart_item_key, $k_edit_ksession, $k_edit_blanks, $k_edit_art1, $k_edit_art2, $k_edit_art3, $k_edit_art4, $k_edit_art5, $k_edit_files1, $k_edit_files2, $k_edit_files3, $k_edit_files4, $k_edit_files5, $k_edit_art_pos;

	//print_r($sizes_names);
	//echo json_encode( $sizes_names);

	if (null !== get_post_custom_values('sizes'))
		echo '<input name="ksizes" value="' . get_post_custom_values('sizes')[0] . '" />';


	// fields hidden via css
?>
	<br />
	prices <br/>
		<input type="float" min="0" name="k_total_price" id="k_total_price"  readonly />
		<input type="float" min="0" name="k_per_item" id="k_per_item"  readonly />
		<br />
	preview <br />
		<input type="text" id="art1_preview" name="art1_preview" value="<?php echo $k_edit_art1; ?>" />
		<input type="text" id="art2_preview" name="art2_preview" value="<?php echo $k_edit_art2; ?>" />
		<input type="text" id="art3_preview" name="art3_preview" value="<?php echo $k_edit_art3; ?>" />
		<input type="text" id="art4_preview" name="art4_preview" value="<?php echo $k_edit_art4; ?>" />
		<input type="text" id="art5_preview" name="art5_preview" value="<?php echo $k_edit_art5; ?>" />
	files <br />
		<input type="text" id="art1_files" name="art1_files" value="<?php echo $k_edit_files1; ?>" />
		<input type="text" id="art2_files" name="art2_files" value="<?php echo $k_edit_files2; ?>" />
		<input type="text" id="art3_files" name="art3_files" value="<?php echo $k_edit_files3; ?>" />
		<input type="text" id="art4_files" name="art4_files" value="<?php echo $k_edit_files4; ?>" />
		<input type="text" id="art5_files" name="art5_files" value="<?php echo $k_edit_files5; ?>" />

		<input type="text" id="_art_pos" name="_art_pos" value='<?php echo $k_edit_art_pos; ?>' />

	blanks?
		<input type="text" id="ordering_blanks" name="ordering_blanks" value="<?php echo $k_edit_blanks; ?>" />
		<?php
		/* may be cached, but needed for editing */
		$wc = k_get_session(); // WC session id from cookie

		if (strlen($k_edit_ksession)>0)
			$ks = $k_edit_ksession;
		else {
			
			$ks = date("Ymd-H-i-s-").$wc;
		}
		$ktoken = k_token( $ks );
		

		$product_id = get_post_custom_values('product_id')[0];
		?>
		<input type="text" id="ksession" name="ksession" value="<?php echo $ks; ?>" />
		<input type="text" id="ktoken" name="ktoken" value="<?php echo $ktoken; ?>" />
		<input type="text" id="kvariation" name="kvariation" value="<?php echo $cart_item_key; ?>" />
		<input type="text" id="wcsession" name="wcsession" value="<?php echo $wc; ?>" />
		<input type="text" id="product_id" name="product_id" value="<?php echo $product_id; ?>" />
<?php

// konrad testing
// 
//$product_id = get_post_custom_values('product_id')[0];
//$styles = get_post_custom_values('styles')[0];
	
// echo ' ----- konrad testing Local ---- <br />';
// echo 'product_id='.$product_id; echo ' <br />';
// echo 'styles='.$styles; echo ' <br />';
// echo ' <br /><br />';

// echo 'price_table = '.get_post_custom_values('price_table')[0] .' <br />';

// echo 'print_options = '.get_post_custom_values('print_options')[0] .' <br />';
// echo 'decoration_locations = '.get_post_custom_values('decoration_locations')[0] .' <br />';

// echo 'leadtimestd = '.get_post_custom_values('leadtimestd')[0] .' <br />';
// echo 'leadtimerush = '.get_post_custom_values('leadtimerush')[0] .' <br />';


//print_r(explode('##', $styles));echo ' <br />';

/*
$t = explode('##', $styles);
$t[0] = null;
foreach($t as $tt) {
  if (isset($tt))
    list($style_id[], $color_name[], $color_html[]) = explode('|', $tt);
}
*/


//print_r($style_id); print_r($color_name);
/*
$cdn_url = 'https://cdn.dtlaprint.com/wp-content/uploads/local/products/'.substr($product_id,0,2).'/';

foreach ($style_id as $s){
  $photo_url = $cdn_url . $product_id . '-' . $s . '-front.jpg';  echo $photo_url ; echo ' <br />';
  $photo_url = $cdn_url . $product_id . '-' . $s . '-back.jpg';  echo $photo_url ; echo ' <br />';
  $photo_url = $cdn_url . $product_id . '-' . $s . '-side.jpg';  echo $photo_url ; echo ' <br />';
  $photo_url = $cdn_url . $product_id . '-' . $s . '-side-right.jpg';  echo $photo_url ; echo ' <br />';
  echo '<div style="position:relative">';
  $photo_url = $cdn_url . $product_id . '-' . $s . '-front.jpg';  echo '<img src="'.$photo_url.'" style="width:100px;float:left;" />';
  $photo_url = $cdn_url . $product_id . '-' . $s . '-back.jpg';  echo '<img src="'.$photo_url.'" style="width:100px;float:left;" />';
  $photo_url = $cdn_url . $product_id . '-' . $s . '-side.jpg';  echo '<img src="'.$photo_url.'" style="width:100px;float:left;" />';
  $photo_url = $cdn_url . $product_id . '-' . $s . '-side-right.jpg';  echo '<img src="'.$photo_url.'" style="width:100px;float:left;" />';
	echo '</div>';
	
  echo ' <br />';
}


*/

//echo ' ses='; print_r( WC()->session->get_session_data() );


}





// FOR JAVASCRIPT VARIABLES, displayed in dtla_template_product

//function k_get_price_table_header() {
//	return json_encode( explode('|', get_post_custom_values('price_table_header')[0]) );
//}

function k_get_price_table() {
	if ( null !== get_post_custom_values('price_table'))
		return json_encode( explode('|', get_post_custom_values('price_table')[0]) );
	else
		return json_encode(['1', '2', '3']);
	
}

function k_get_product_moq() {
	if ( null !== get_post_custom_values('moq'))
		return get_post_custom_values('moq')[0];
	else
		return '1';
	
}

function k_get_delivery_std() {
	if ( null !== get_post_custom_values('leadtimestd'))
		return get_post_custom_values('leadtimestd')[0];
	else
		return '5 Business Days';
}
function k_get_delivery_std_dy() {
	if ( null !== get_post_custom_values('leadtimestd'))
		return explode('-', get_post_custom_values('leadtimestd')[0])[0];
	else
		return '5';
}

function k_get_delivery_rush() {
	if ( null !== get_post_custom_values('leadtimerush'))
		return get_post_custom_values('leadtimerush')[0];
	else
		return '5 Business Days';
}
function k_get_delivery_rush_dy() {
	if ( null !== get_post_custom_values('leadtimerush'))
		return explode('-', get_post_custom_values('leadtimerush')[0])[0];
	else
		return '5';
}





// CART - price, quantity updates

add_action( 'woocommerce_before_calculate_totals', 'add_custom_price' );

function add_custom_price( $cart_object ) {
	global $product, $custom_fields;
	

	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
 
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) return;

    
    foreach ( $cart_object->get_cart() as $key=>$prod ) {

		//  TODO remove
        //echo ' key='.$key; //$prod['key']; 
		//echo ' kolor='.$prod['c1_name'];
		//echo ' q = '.$prod['quantity_total2']; //$prod['data']->get_price();
		
		//TODO secure data from form? hash and compare... ?
		//Undefined array key "k_per_item" after adding to cart?

		//echo ' pr='.$prod['data']->get_price();

		if (isset($prod['k_per_item'])) $prod['data']->set_price( $prod['k_per_item'] ); //$custom_price; 
		if (isset($prod['quantity_total2'])) $cart_object->set_quantity( $key,  $prod['quantity_total2']);

		//echo ' pr2='.$prod['data']->get_price();
    }
	
}