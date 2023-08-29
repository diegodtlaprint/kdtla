<?php
/*
Author: Konrad G
Author URI: www.kgretk.com
File: WooCommerce - user cart preview
Changelog:
2023-03-23 start

*/


$session_id = null;
$values = null;

echo '<h2> user cart </h2>';


// get user cart
if ( isset($_GET['cart']) && strlen($_GET['cart'])>0 ) {
	echo '<p> Cart id='.(string)$_GET['cart']; //get_query_var('cart'); 
	echo '<br /> </p>';

	$session = new WC_Session_Handler();
	$session_data = $session->get_session( (string)$_GET['cart'] );

	if ( false !== $session_data ) {

		// Contains array of items in cart including product ids, quantities, totals, etc.
		$cart_data = unserialize( $session_data['cart'] );

		k_show_cart( $cart_data );


		echo '<br />'; echo '<br />';

		//echo ' session id='.(string)$_GET['cart'];		echo '<br />';	echo '<pre>';		print_r($cart_data);
	}
	else echo '<br /><br /> No such cart!';

	

} else {

	echo '<p>To see user cart, enter cart id:  </p>';
	echo '<form action="/wp-admin/admin.php" method="GET">';
	echo '<input name="page" id="page" value="kcart" hidden />';
	echo '<input name="cart" id="cart" value="" placeholder="t_xxxxx" />';
	
	echo '<button>Display user cart</button>';
	echo '<p>( use url: wp-admin/admin.php?page=kcart&cart=xxxx  ) </p>';

	echo '<h4> Your cart: </h4>';
	// show current user cart
	foreach( $_COOKIE as $key => $value ) {

		if( stripos( $key, 'wp_woocommerce_session_' ) === false ) {
		continue;
		}

		$values = explode( '||', $value );

	}

	$session_id = $values[0];
	$session = new WC_Session_Handler();
	$session_data = $session->get_session( $session_id );

	// Contains array of items in cart including product ids, quantities, totals, etc.
	$cart_data = unserialize( $session_data['cart'] );

	k_show_cart( $cart_data );
	

	echo '<br />'; echo '<br />';
	echo '<div style="display:none;">';
	echo ' your session id='.$session_id;	echo '<br />';	//echo '<pre>';	print_r($cart_data);
	echo '</div>';
}



function k_show_cart( $cart_data, $cart = null ) {

	// from cart.php
	//$session = new WC_Session_Handler();
	//$session_data = $session->get_session( (string)$_GET['cart'] );
	

			foreach ( $cart_data as $cart_item_key => $cart_item ) {
				//$cart_item['data'] = WC()->session->get('_my_cart_item_data',$cart_item_key);

				$_product = new WC_Product($cart_item['product_id']);
				//$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( 1) // $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) 
				{
					$product_permalink = ''; //apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<div class="kwc-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						

						<span class="product-thumbnail">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

						if ( ! $product_permalink ) {
							echo $thumbnail; // PHPCS: XSS ok.
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
						}
						?>
						</span>

						<div class="kwc_cart_row1">

							<span class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
							<?php
							if ( ! $product_permalink ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
							} else {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
							}
							
							//do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key, $product_permalink ); //kg added $product_permalink
							
							// Backorder notification.
							/*
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
							}
							*/
							?>
							</span>
							
							<span class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
								<?php
									//echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
								$<?php echo $cart_item['k_per_item']; ?>/ pc @ <?php echo $cart_item['quantity_total2']; ?>
							</span>


							<span class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
								<?php
									//echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									
								?>
								$<?php echo $cart_item['k_total_price']; ?>
							</span>




						</div>
						<br /><br /><br />
						<div class="kwc_cart_row2">
						
							<span class="kwc_meta">
								

								<?php //k_quantities(); ?>

								<?php
								// Meta data.
								//echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

								
								?>
								
								
								<?php //do_action( 'k_cart_row_info' ); 
								
								require( KDTLA_PATH . '1_templates/dtla_template_cart_row.php'); //1_templates since v 0.6
								?>

							</span>

						</div>

					</div>
					<?php
				}
			}
			

}

// below style from cart.php, perhaps not needed all of that...

?>

<style>

.page-content {
	max-width: 1440px;
	margin: 0 auto;

}

.entry-title {
	margin: 1em auto!important;
	max-width: 1440px!important;
}

	.awdr_cart_strikeout_line {
		float:left;
	}



.kwc-cart-form__cart-item {
	border:1px solid #ddd;
	border-radius: 10px;
	margin-bottom: 10px;
	float: left;
	width: 100%;
}

.kwc_cart_row1 {
	position: relative;
	width: 82%;
	float: right;
	margin-bottom: 30px;
}
.kwc_cart_row2 {
	width: 83%;
	float: right;
}

.kwc_meta {
  display: block; /* block */
}


.product-edit {
	position: absolute;
	top: 12px;
	right: 70px;
}


.product-remove {
	position: absolute;
	top: 10px;
	right: 35px;
}

.row_expand {
	position: absolute;
	top: 12px;
	right: 10px;
	cursor:pointer;
	transition: 0.5s all;
	width: 10px;
    height: 20px;
}

.product-thumbnail {
  width: 160px;
  display: block;
  float: left;
  margin: 10px;
  border: 3px solid #eee;
  border-radius: 12px;
  padding: 4px;
}

.product-thumbnail img {
	max-width:200px;
	object-fit:contain;
}



.product-name {
	float:left;
	max-width:80%;
	font-family: 'Poppins';
	font-style: normal;
	font-weight: 600;
	font-size: 28px;
	line-height: 40px;
	margin:10px 0;
}
.product-name a {
	color:#222;
	text-decoration: none;
}


.k_cart_edit_link  {
	font-size:14px!important;
	margin-left:40px;

}
.k_cart_edit_link:before {
	content: "\f464 ";
	font-family: Dashicons;
}


.product-price {
	float:left;
	clear:left;
	color:#aaa;
}
.product-subtotal {
	float:right;
	font-weight: 600;
	font-size: 24px;
	line-height: 16px;
	padding-right: 70px;
}
.product-quantity {
	max-width:300px;
	float: right;
	clear: both;
	display:none; /* show temporarily */
}

.kwc_cart_row1  input {
	width:6em!important;  
	padding: 0;
	text-align: right!important;
}


#coupon_code {
	width:160px;
}

.actions {
	float: left;
	height: 100px;
	background: #eee;
	border-radius: 10px;
	padding: 5px;
	display: block;
}
.ac_update {
	float:right;
}

.cart-collaterals {
	float:right;
}



#table_q .hidden {
	display:none;
}

#table_q thead td, #table_d thead td {
	height:10px;
	padding: 0;
}
#table_q tr td, #table_d tr td {
	border:0px solid red!important;
	background: transparent;
	padding: 2px 4px;
}


#table_d tr {
	display:none;
}
#table_d tr:first-child {
	display: block;
}
/* decoration table columns */
#table_d tr td:nth-child(1) {
width: 130px;
}
#table_d tr td:nth-child(2) {
width: 160px;
}
#table_d tr td:nth-child(3) {
width: 70px;
}
#table_d tr td:nth-child(4) {
width: 160px;
}

#table_d .preview {
	float:left;
}


.cart_totals h2, .shop_table, .shop_table_responsive {
	display:none;
}

.cart-collaterals {
	width:50%!important;
}
.wc-proceed-to-checkout  {
	float: right;
	 padding: 0!important;
	 margin:0;
	 margin-bottom: 40px;
}

.wc-proceed-to-checkout  .checkout-button {
  background: red!important;
	border-radius: 20px!important;
	font-size: 1em !important;
	padding:12px!important;
}



#table_q .q1,  #table_q .q2, #table_q .q3,  #table_q .q4,  #table_q .q5,
#table_d .q1 {
	border-radius: 5px;
	border-color:#ccc;
}

/* if spans instead of inputs */

#table_q tr:first-child td {
	width:70px;
}
#table_q .q1,  #table_q .q2, #table_q .q3,  #table_q .q4,  #table_q .q5 {
	display:block;
	padding-left:10px;
	margin-top: 10px;
	border-radius: 5px;
	border:1px solid #ccc;
}
#table_d .q1 {
	display:block;
	padding-left:10px;
	margin-bottom: 5px;
	border-radius: 5px;
	border:0px solid #ccc; 
	font-size:12px;
	float:left; /* filenames next to preview */
	max-width:85%;
}


/* hide arrow in numerical fields */
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

#table_d thead td {
	font-size: 12px;
}


#k_cart_finishings {
	width:99%;
	float:left;
}

.fin_tile {
	border:1px solid #E5E7EB;
	border-radius: 8px;
	display:block;
	float:left;
	/*width:30%;*/
	padding:1%;
	margin: 1%;
	color: #6B7280;
}
.fin_tile_img {
	width:60px;
	float:left;
}
.fin_tile img {
	float: left;
	width: 54px;
	margin-right: 4%;
}

.fin_tile_cntent {
	width:75%;
	float:right;
}

#fin1, #fin2, #fin3, #fin4, #fin5, #fin6,
#fin1_days, #fin2_days, #fin3_days, #fin4_days, #fin5_days, #fin6_days {
	border:0px;
	padding: 0;
	width:35%;
	text-align: right;
	background: inherit;
	transition: all 0s;
}

#fin1_days, #fin2_days, #fin3_days, #fin4_days, #fin5_days, #fin6_days {
	text-align: left;
	width:8px;
}

.fin_tile_selected {
	background: #FEEFEE;
}

#k_cart_finishings  h3 {
  font-size: 1.25rem!important;;
}


#table_q .c_name_cart {
	margin: 0;
padding: 0;
border: 0;
font-size: 12px;
}
.kcolor_box_cart {
	width: 30px;
display: block;
height: 30px;
}




@media (max-width: 767px){

	.product-thumbnail {
		width: 30%;
	}

	.kwc_cart_row1 {
		width: 64%;
		margin-bottom: 10px;
	}

	.product-name {
		font-size: 20px;
		line-height: 26px;
	}

	.product-subtotal {
		padding-right: 10px;
	}

}

@media (min-width: 768px) and (max-width: 1024px){
	.kwc_cart_row1 {
		width: 75%;
		margin-bottom: 20px;
	}
}

@media (max-width: 1024px){
	.kwc_cart_row2 {
		width: 99%;
	}
}

.elementor-10 .elementor-element.elementor-element-8a9155d .elementor-icon-list-icon i {
  color: #F05C4E;
}
.elementor-element-8a9155d li.elementor-icon-list-item.elementor-inline-item:nth-child(2) .elementor-icon-list-icon {
	background: #F05C4E;
}
.elementor-element-8a9155d li.elementor-icon-list-item.elementor-inline-item:nth-child(2) .elementor-icon-list-icon i.fas.fa-check::before {
	content: '2';
	color:#fff;
}
.elementor-element-8a9155d li.elementor-icon-list-item.elementor-inline-item:nth-child(3) .elementor-icon-list-icon {
	background: transparent;
	border:1px solid #D1D5DB;
}
.elementor-element-8a9155d li.elementor-icon-list-item.elementor-inline-item:nth-child(3) .elementor-icon-list-icon i.fas.fa-check {
	color:#9CA3AF;
}

.elementor-element-8a9155d {
	/* hack to show above Cart */
margin-bottom: 90px;
margin-top: -125px;
}



</style>
