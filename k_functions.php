<?php
/*
Author: Konrad G
Author URI: www.kgretk.com
File: Functions, snippets, which could be placed in theme's functions.php
Changelog:
2023-03-15 start

*/


defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
error_reporting(E_ALL);



//Konrad - disable WC zoom
if (!function_exists('k_disable_wc_zoom')):
add_action( 'wp', 'k_disable_wc_zoom', 99 );
function k_disable_wc_zoom() {
	remove_theme_support( 'wc-product-gallery-zoom' );
}
endif;


//Konrad: template file name - temp
if (!function_exists('k1')):
function k1($a)  {
	$cf = explode('/',$a); $cf2 = $cf[sizeof($cf)-1]; echo '<!-- kstart: '.$cf2.' -->'; 
}
endif;

if (!function_exists('k2')):
function k2($a)  {
	$cf = explode('/',$a); $cf2 = $cf[sizeof($cf)-1]; echo '<!-- kend: '.$cf2.' -->'; 
}
endif;

/* Konrad - enable custom fields in products after ACF */
add_filter('acf/settings/remove_wp_meta_box', '__return_false');




/* Konrad  - testing taxonomy routing */
/*
//manufacturer/gildan/embroidered-t-shirts
function k_rewrite_rules() {
    add_rewrite_rule(
        //'^manufacturer/([^/]*)/?',
		'^manufacturer/([^/]*)/([^/]*)/?',
        //'index.php?pagename=manufacturer&brand=$matches[1]&print_type=$matches[2]',
		//'index.php?taxonomy=pa_brand&term=$matches[1]/$matches[2]',
		'index.php?taxonomy=pa_manufacturer&term=$matches[1]&print=$matches[2]', // or c=
        'top'
    );
}

add_action( 'init', 'k_rewrite_rules' );

function k_query_vars( $qvars ) {
	$qvars[] = 'term';
	$qvars[] = 'print';
	$qvars[] = 'c'; // order matters ...
	return $qvars;
}
add_filter( 'query_vars', 'k_query_vars' );
*/


/* Konrad - testing category description */
/*
add_action('woocommerce_archive_description', 'k_woocommerce_archive_description', 10);

function k_woocommerce_archive_description() {
	//echo __FILE__;
	$csv_path = ''; //'../../uploads/';
	//$print_type_descr = array_map('str_getcsv', file( $csv_path . 'print_type_descr.csv'));
	echo ' c='.get_query_var( 'c' );
	echo '  print-type='.get_query_var( 'print' );
	//print_r($print_type_descr);

}
*/


/* Konrad - new tabs */

if (!function_exists('k_woo_tabs')):
add_filter( 'woocommerce_product_tabs', 'k_woo_tabs', 98 );
function k_woo_tabs( $tabs ) {

	$tabs['additional_information']['title'] = __( 'Add. info' );	// Rename the additional information tab
	
	$tabs['description']['priority'] = 10;			// Description first
	$tabs['shipping_tab']['priority'] = 20;	
	$tabs['artwork_tab']['priority'] = 30;	
	$tabs['additional_information']['priority'] = 40;	// Additional information 4th, if not hidden

	unset( $tabs['additional_information'] );  	// Remove the additional information

	// new shipping tab
	$tabs['shipping_tab'] = array(
		'title' 	=> __( 'Shipping & Delivery', 'woocommerce' ),
		'priority' 	=> 11,
		'callback' 	=> 'woo_new_product_tab_shipping_content'
	);

	// new artwork tab
	$tabs['artwork_tab'] = array(
		'title' 	=> __( 'Artwork', 'woocommerce' ),
		'priority' 	=> 12,
		'callback' 	=> 'woo_new_product_tab_artwork_content'
	);

	return $tabs;
}
endif;




/* moved to functions.php in theme */

/* content for new product tabs */
/*
function woo_new_product_tab_shipping_content() {

	// The new tab content

	echo '<h2>Shipping & Delivery</h2>';
	echo '<p>Here\'s your new product tab.</p>';
	
}


function woo_new_product_tab_artwork_content() {

	// The new tab content

	echo '<h2>Artwork</h2>';
	echo '<p>Artwork your new product tab.</p>';
	
}

*/

/*
// testing after item name

add_filter( 'woocommerce_cart_item_name', 'k_test', 10, 3 );

//add_action( 'woocommerce_after_cart_item_name', 'k_test' ); //, 10, 3

function k_test( $item_name,  $cart_item,  $cart_item_key ) {
    // Display name and product id here instead
    echo 'prod=' . $item_name.' ('.$cart_item['product_id'].')';
	return ' aa ';
}
*/





/* correction - disable touch events for artwork moving on mobile */
add_filter( 'woocommerce_single_product_carousel_options', 'filter_single_product_carousel_options' );
function filter_single_product_carousel_options( $args ) {
    $args['animation']      = 'slide';
    //$args['easing']       = 'swing';
    $args['smoothHeight']   = true;
	$args['directionNav']   = false;
    $args['controlNav']     = 'thumbnails';
    $args['slideshow']      = false;
    $args['animationSpeed'] = 500;
	$args['animationLoop']  = false;
    $args['allowOneSlide']  = false;
	$args['touch']          = false;
    
    return $args;
}


/* RESALE PERMIT - JS in kdtla-mini.js to set cookie if resale permit uploaded */
add_filter('woocommerce_product_get_tax_class', 'k_switch_product_tax_class', 100, 2 );
add_filter('woocommerce_product_variation_get_tax_class', 'k_switch_product_tax_class', 100, 2 );
function k_switch_product_tax_class( $tax_class, $product ){
    if( isset($_COOKIE["dtla_resale"]) && $_COOKIE["dtla_resale"] == 'business' ){
        return "Zero Rate";
    }
    return $tax_class;
}



/* CHECKOUT - max delivery time */
add_action('woocommerce_after_checkout_form', 'k_delivery_times');

function k_delivery_times() {

	$delivery_plus = 0;
	$fins = array();
	
	$cart = WC()->cart->cart_contents;
	foreach ($cart as $item) {
		//echo $item['style1'];
		
		if (isset($item['fin_all']) && strlen($item['fin_all'])>0) {
			//echo ' : ' . $item['fin_all'];
			$fins = explode(',',$item['fin_all']);
		}

		// checking finishings to get max delivery time -  fbp, ss, upc +2 days ; ht, wml, hl + 14 days
		foreach($fins as $fin){
			if (in_array($fin, ['fbp','ss','upc']))
				$delivery_plus = max( $delivery_plus, 2 );
		}
		foreach($fins as $fin){
			if (in_array($fin, ['ht','wml','hl']))
				$delivery_plus = max( $delivery_plus, 14 );
		}
			
		//echo ' delivery plus=' . $delivery_plus;	echo '<br />';

	}

	// export JS with max delivery time
	echo '<script type="text/javascript">var delivery_plus='.$delivery_plus.'</script>';

	//print_r($cart);

}


/* COD payment type - default status to Pending Payment */
/*
add_filter( 'woocommerce_cod_process_payment_order_status', 'change_cod_payment_order_status', 10, 2 );
function change_cod_payment_order_status( $order_status, $order ) {
    return 'pending-payment';
}
*/



/* Konrad - add dashicons for logged out users - not working with Elementor... */
/*
function ww_load_dashicons(){
	wp_enqueue_style('dashicons');
 }
 add_action('wp_enqueue_scripts', 'ww_load_dashicons', 999);
*/




/* D testing */
/*
add_filter( 'woocommerce_order_item_get_formatted_meta_data', 'unset_specific_order_item_meta_data', 10, 2);

function unset_specific_order_item_meta_data($formatted_meta, $item){
    // Only on emails notifications
    $is_resend = isset($_POST['wc_order_action']) ?  wc_clean( wp_unslash( $_POST['wc_order_action'] ) ) === 'send_order_details' : false;

    if ( !$is_resend && (is_admin() || is_wc_endpoint_url() ) ) {
      return $formatted_meta;
    }

    foreach( $formatted_meta as $key => $meta ){
        if( in_array( $meta->key, array('Artwork Positions', 'color 1 html', 'Artwork 1 preview', 'Artwork 1 files', 'user session' ) ) )
            unset($formatted_meta[$key]);
    }
    return $formatted_meta;
}
*/

