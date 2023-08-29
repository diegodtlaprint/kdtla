<?php
/*
Author: Konrad G
Author URI: www.kgretk.com
File: Functions modifying WooCoomerce backend
Changelog:
2023-03-15 start
2023-05-31 removed colors, artwork columns for now
2023-08-15 full paths for artwork and previews

*/


defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
error_reporting(E_ALL);

//WC backend

// Add custom column to orders table
add_filter( 'manage_edit-shop_order_columns', 'custom_order_columns' );
function custom_order_columns( $columns ) {
    $new_columns = array();

    foreach ( $columns as $column_name => $column_info ) {
        $new_columns[ $column_name ] = $column_info;
        if ( 'order_total' === $column_name ) {
            // Add custom column after "order_total"
            //$new_columns['colors'] = __( 'Colors', 'woocommerce' );
			//$new_columns['artwork1'] = __( 'Artwork1', 'woocommerce' );
            $new_columns['sample'] = __( 'Sample?', 'woocommerce' );
        }
    }

    return $new_columns;
}


// Populate custom column with data
add_action( 'manage_shop_order_posts_custom_column', 'custom_order_column_data', 10, 2 );
function custom_order_column_data( $column ) {
    global $post;

    //$order->has_status( array( 'processing' ) ) 

    // if column with colors needed:
        /*
    if ( 'colors' === $column ) {
        $order = wc_get_order( $post->ID );
		
        //$shipping_methods = $order->get_shipping_methods();
        //$shipping_method = '';
        //foreach ( $shipping_methods as $method ) {
        //    $shipping_method .= $method->get_name() . '<br>';
        //}
        //echo $shipping_method;
		

		//print_r($order->get_meta('c1_name'));

		foreach ( $order->get_items() as $item_id => $item ) {
			$product_id = $item->get_product_id();
			
			//$variation_id = $item->get_variation_id();
			//$product = $item->get_product(); // see link above to get $product info
			//$product_name = $item->get_name();
			//$quantity = $item->get_quantity();
			//$subtotal = $item->get_subtotal();
			//$total = $item->get_total();
			//$tax = $item->get_subtotal_tax();
			//$tax_class = $item->get_tax_class();
			//$tax_status = $item->get_tax_status();
			//$allmeta = $item->get_meta_data(); print_r($allmeta);
			
            //TODO prod 1: color 1,2,3.. prod 2: colors...  - needed?
			$somemeta = $item->get_meta( 'color 1', true );
			//$item_type = $item->get_type(); // e.g. "line_item", "fee"
			echo $somemeta .', ';
			
		 }
    }
    */

    // if column with artwork needed:
        /*
	if ( 'artwork1' === $column ) {
		$order = wc_get_order( $post->ID );
		foreach ( $order->get_items() as $item_id => $item ) {
			
			echo $item->get_meta( 'Artwork 1 files', true );
		}
	}
    */

    // additional_sample - from plugin, or ch_option_sample -  from code
    if ( 'sample' === $column ) {
        $order = wc_get_order( $post->ID );
        echo $order->get_meta('additional_sample');
        //echo $order->get_meta('ch_option_sample');
    }
	
	 
}


// order preview - NOT line item

add_action( 'woocommerce_admin_order_preview_line_item', 'custom_order_preview', 10, 3 );
function custom_order_preview( $item_id, $item, $order ) {
	//echo ' i='.$item['color 1 name'];
	//print_r($item);

	echo ' **** ';  // TODO not working...
}


 /*
add_action( 'woocommerce_admin_order_preview_line_item_columns', 'custom_order_preview_col', 10, 2 );
function custom_order_preview_col( $array, $order ) {

	//echo ' **** ';  // TODO error...
}
*/


// order preview

// Display custom values in Order preview - hide meta data
add_action( 'woocommerce_admin_order_preview_end', 'custom_display_order_data_in_admin' );
function custom_display_order_data_in_admin(){
    // one time, at the end of preview
    //echo '<div> xxxxxxxx </div><br>';

    echo '<style> .wc-order-preview-table__column--product .wc-order-item-meta { display:none; } </style>';

}


// CHECKOUT - thank you page, after order, PART 1
add_action( 'woocommerce_order_item_meta_start', 'k_custom_display_order_item_meta', 10, 2 );

function k_custom_display_order_item_meta( $item_id, $item ){

    echo ' <br /><br /><br />';
    //echo '<pre>'; print_r($item); echo '</pre>'; 

    echo '<style>  .wc-item-meta { display:none; } </style>'; // will be shown in js

    echo '<br />';
    echo '<strong> Styles: </strong>';
    $st = $item->get_meta( 'style 1', true ); if (strlen($st)>0) echo $st .', ';
    $st = $item->get_meta( 'style 2', true ); if (strlen($st)>0) echo $st .', ';
    $st = $item->get_meta( 'style 3', true ); if (strlen($st)>0) echo $st .', ';
    $st = $item->get_meta( 'style 4', true ); if (strlen($st)>0) echo $st .', ';
    $st = $item->get_meta( 'style 5', true ); if (strlen($st)>0) echo $st .', ';

    echo '<br />';
    echo '<strong>Colors: </strong>';
    echo '<span class="kcolors_order">';
    $st = $item->get_meta( 'color 1', true ); if (strlen($st)>0) echo $st .', ';
    $st = $item->get_meta( 'color 2', true ); if (strlen($st)>0) echo $st .', ';
    $st = $item->get_meta( 'color 3', true ); if (strlen($st)>0) echo $st .', ';
    $st = $item->get_meta( 'color 4', true ); if (strlen($st)>0) echo $st .', ';
    $st = $item->get_meta( 'color 5', true ); if (strlen($st)>0) echo $st .', ';
    echo '</span>';

    echo '<br />';
    echo '<strong>Decorations: </strong>';
    
    $st = $item->get_meta( 'Front', true ); if (strlen($st)>0) { echo '<br />'; echo $st .', '; }
    $st = $item->get_meta( 'Front colors', true ); if (strlen($st)>0) echo $st .', ';
    $st = $item->get_meta( 'Front ink', true ); if (strlen($st)>0) echo $st .', ';
    
    $st = $item->get_meta( 'Back', true ); if (strlen($st)>0) { echo '<br />'; echo $st .', '; }
    $st = $item->get_meta( 'Back colors', true ); if (strlen($st)>0) echo $st .', ';
    $st = $item->get_meta( 'Back ink', true ); if (strlen($st)>0) echo $st .', ';
    
    $st = $item->get_meta( 'Left', true ); if (strlen($st)>0) { echo '<br />'; echo $st .', '; }
    $st = $item->get_meta( 'Left colors', true ); if (strlen($st)>0) echo $st .', ';
    $st = $item->get_meta( 'Left ink', true ); if (strlen($st)>0) echo $st .', ';
    
    $st = $item->get_meta( 'Right', true ); if (strlen($st)>0) { echo '<br />'; echo $st .', '; }
    $st = $item->get_meta( 'Right colors', true ); if (strlen($st)>0) echo $st .', ';
    $st = $item->get_meta( 'Right ink', true ); if (strlen($st)>0) echo $st .', ';
    
    $st = $item->get_meta( 'Neck', true ); if (strlen($st)>0) { echo '<br />'; echo $st .', '; }
    $st = $item->get_meta( 'Neck colors', true ); if (strlen($st)>0) echo $st .', ';
    $st = $item->get_meta( 'Neck ink', true ); if (strlen($st)>0) echo $st .', ';
    
    echo '<br />';echo '<br />';

    /* - not like this... 
    echo 'Colors:';
    echo '<br />';

    foreach ($item as $it) {
        if ( strpos($it, 'color')!==false) echo $it.' ';
        echo $it.' = '.strpos($it, 'color').'  ';
    }
    */


}

// CHECKOUT - thank you page, after order, PART 2
add_action( 'woocommerce_order_item_meta_end', 'k_custom_display_order_item_meta_end', 10, 2 );

function k_custom_display_order_item_meta_end( $item_id, $item ){

    echo ' <br /><br />';
    echo '<strong>Artwork: </strong>';
    echo ' <br />';
    echo 'Front: ';
    $art1 =  explode('|', $item->get_meta( 'Artwork 1 files', true )); //print_r($art1);
    if (strlen($art1[0])==0)
        echo ' - none - ';
    else
        foreach ($art1 as $a) {
            if (strlen($a)>4) {
                $b = explode( '/', $a );
                echo $b[sizeof($b)-1];
                echo '<br />';
            }
                
        }

    echo 'Back: ';
    $art1 =  explode('|', $item->get_meta( 'Artwork 2 files', true ));
    if (strlen($art1[0])==0)
        echo ' - none - ';
    else
        foreach ($art1 as $a) {
            if (strlen($a)>4) {
                $b = explode( '/', $a );
                echo $b[sizeof($b)-1];
                echo '<br />';
            }
                
        }

    echo 'Left: ';
    $art1 =  explode('|', $item->get_meta( 'Artwork 3 files', true ));
    if (strlen($art1[0])==0)
        echo ' - none - ';
    else
        foreach ($art1 as $a) {
            if (strlen($a)>4) {
                $b = explode( '/', $a );
                echo $b[sizeof($b)-1];
                echo '<br />';
            }
                
        }

    echo 'Right: ';
    $art1 =  explode('|', $item->get_meta( 'Artwork 4 files', true ));
    if (strlen($art1[0])==0)
        echo ' - none - ';
    else
        foreach ($art1 as $a) {
            if (strlen($a)>4) {
                $b = explode( '/', $a );
                echo $b[sizeof($b)-1];
                echo '<br />';
            }
                
        }

    echo 'Neck: ';
    $art1 =  explode('|', $item->get_meta( 'Artwork 5 files', true ));
    if (strlen($art1[0])==0)
        echo ' - none - ';
    else
        foreach ($art1 as $a) {
            if (strlen($a)>4) {
                $b = explode( '/', $a );
                echo $b[sizeof($b)-1];
                echo '<br />';
            }
                
        }
        
    // previews - full path for artwork - canvas-front.png, etc.
    //$p1 = '/wp-content/uploads/artwork/'.$item->get_meta( 'user session', true );

    echo '<br /> <strong> Previews: </strong> <br />';

    echo 'Front: ';
    $pre1 =  $item->get_meta( 'Artwork 1 preview', true ); //print_r($pre1);
    if (strlen($pre1)==0)
        echo ' - none - ';
    else
        echo '<img src="'.$pre1.'" style="width:60px;margin:5px;" />'; // full path, so $pre1, not $p1

    echo 'Back: ';
    $pre2 =  $item->get_meta( 'Artwork 2 preview', true );
    if (strlen($pre2)==0)
        echo ' - none - ';
    else
        echo '<img src="'.$pre2.'" style="width:60px;margin:5px;" />';

    echo 'Left: ';
    $pre3 =  $item->get_meta( 'Artwork 3 preview', true );
    if (strlen($pre3)==0)
        echo ' - none - ';
    else
        echo '<img src="'.$pre3.'" style="width:60px;margin:5px;" />';

    echo 'Right: ';
    $pre4 =  $item->get_meta( 'Artwork 4 preview', true );
    if (strlen($pre4)==0)
        echo ' - none - ';
    else
        echo '<img src="'.$pre4.'" style="width:60px;margin:5px;" />';



}



// BACKEND - order edit
add_action( 'woocommerce_before_order_itemmeta', 'k_custom_display_order_data_in_admin', 10, 2 );


function k_custom_display_order_data_in_admin( $item_id, $item ){
    // Targeting line items type only
    if( $item->get_type() !== 'line_item' ) return;

    $img_types = ['png', 'jpg', 'gif'];

    //$p1 = '/wp-content/uploads/artwork/'.$item->get_meta( 'user session', true );

    echo '<br /><br />';

    echo 'Artwork 1 Front: ';
    $art1 =  explode('|', $item->get_meta( 'Artwork 1 files', true )); //print_r($art1);
    
    if (strlen($art1[0])==0)
        echo ' - none - ';
    else
        foreach ($art1 as $a) {
            if (strlen($a)>4) {
                $ext = substr($a, strlen($a)-3, strlen($a));
                if ( !in_array( strtolower($ext), $img_types ) )
                    echo '<a href="'.$a.'" target="_blank">'.$ext.' file'.'</a> ';
                else
                    echo '<a href="'.$a.'" target="_blank"> <img src="'.$a .'" style="width:60px;margin:5px;" /> </a>'; // full path

            }            
        }

    echo 'Artwork 2 Back: ';
    $art2 =  explode('|', $item->get_meta( 'Artwork 2 files', true ));
    if (strlen($art2[0])==0)
        echo ' - none - ';
    else
        foreach ($art2 as $a) {
            if (strlen($a)>4) {
                $ext = substr($a, strlen($a)-3, strlen($a));
                if ( !in_array( strtolower($ext), $img_types ) )
                    echo '<a href="'.$a.'" target="_blank">'.$ext.' file'.'</a> ';
                else
                    echo '<a href="'.$a.'" target="_blank"> <img src="'.$a .'" style="width:60px;margin:5px;" /> </a>'; // full path

            }
        }

    echo 'Artwork 3 Left: ';
    $art3 =  explode('|', $item->get_meta( 'Artwork 3 files', true )); 
    if (strlen($art3[0])==0)
        echo ' - none - ';
    else
        foreach ($art3 as $a) {
            if (strlen($a)>4) {
                $ext = substr($a, strlen($a)-3, strlen($a));
                if ( !in_array( strtolower($ext), $img_types ) )
                    echo '<a href="'.$a.'" target="_blank">'.$ext.' file'.'</a> ';
                else
                    echo '<a href="'.$a.'" target="_blank"> <img src="'.$a .'" style="width:60px;margin:5px;" /> </a>'; // full path

            }
        }

    echo 'Artwork 4 Right: ';
    $art4 =  explode('|', $item->get_meta( 'Artwork 4 files', true )); 
    if (strlen($art4[0])==0)
        echo ' - none - ';
    else
        foreach ($art4 as $a) {
            if (strlen($a)>4) {
                $ext = substr($a, strlen($a)-3, strlen($a));
                if ( !in_array( strtolower($ext), $img_types ) )
                    echo '<a href="'.$a.'" target="_blank">'.$ext.' file'.'</a> ';
                else
                    echo '<a href="'.$a.'" target="_blank"> <img src="'.$a .'" style="width:60px;margin:5px;" /> </a>'; // full path

            }
        }

    echo 'Artwork 5 Neck: ';
    $art5 =  explode('|', $item->get_meta( 'Artwork 5 files', true )); 
    if (strlen($art5[0])==0)
        echo ' - none - ';
    else
        foreach ($art5 as $a) {
            if (strlen($a)>4) {
                $ext = substr($a, strlen($a)-3, strlen($a));
                if ( !in_array( strtolower($ext), $img_types ) )
                    echo '<a href="'.$a.'" target="_blank">'.$ext.' file'.'</a> ';
                else
                    echo '<a href="'.$a.'" target="_blank"> <img src="'.$a .'" style="width:60px;margin:5px;" /> </a>'; // full path

            }
        }

    echo '<br /><br />';

    // previews - canvas-front.png etc.

    echo 'Preview 1 Front: ';
    $pre1 =  $item->get_meta( 'Artwork 1 preview', true ); //print_r($pre1);
    if (strlen($pre1)==0)
        echo ' - none - ';
    else
        echo '<a href="'.$pre1.'" target="_blank"> <img src="'.$pre1.'" style="width:60px;margin:5px;" /> </a>'; // full path, so $pre1, not $p1


    echo 'Preview 2 Back: ';
    $pre2 =  $item->get_meta( 'Artwork 2 preview', true );
    if (strlen($pre2)==0)
        echo ' - none - ';
    else
        echo '<a href="'.$pre2.'" target="_blank"> <img src="'.$pre2.'" style="width:60px;margin:5px;" /> </a>';

    echo 'Preview 3 Left: ';
    $pre3 =  $item->get_meta( 'Artwork 3 preview', true );
    if (strlen($pre3)==0)
        echo ' - none - ';
    else
        echo '<a href="'.$pre3.'" target="_blank"> <img src="'.$pre3.'" style="width:60px;margin:5px;" /> </a>';

    echo 'Preview 4 Right: ';
    $pre4 =  $item->get_meta( 'Artwork 4 preview', true );
    if (strlen($pre4)==0)
        echo ' - none - ';
    else
        echo '<a href="'.$pre4.'" target="_blank"> <img src="'.$pre4.'" style="width:60px;margin:5px;" /> </a>';



    echo '<br /><br />';

    //print_r($item);
    //echo '<style> .wc-order-preview-table__column--product .wc-order-item-meta { display:none; } </style>';
    //echo '<style>     #woocommerce-order-items .woocommerce_order_items_wrapper table.woocommerce_order_items table.display_meta { display:none; } </style>';

}





// columns when editing order
/*
add_action( 'woocommerce_admin_order_item_headers', 'download_image_admin_order_item_headers', 10, 0 );
function download_image_admin_order_item_headers(){
    echo '<th class="item sortable" colspan="1" data-sort="string-ins">' . __( 'Download image', 'woocommerce' ) .'</th>';
}

add_action( 'woocommerce_admin_order_item_values', 'download_image_order_item_values', 10, 3 );
function download_image_order_item_values( $_product, $item, $item_id ){
    // Calling global $post to get the order ID
    global $post;
    // The Order ID
    $order_id = $post->ID;

    // the Product ID and variation ID (if different of zero for variations)
    $product_id = $item['product_id'];
    $variation_id = $item['variation_id'];
    
    // If is not a variable product we replace the variation ID by the product ID
    if (empty($variation_id)) $variation_id = $product_id;

    // HERE ==> Getting an instance of product object, Avoiding an error:
    // "Fatal error: Call to a member function get_gallery_attachment_ids()"
    $product = new WC_Product($product_id);
    // the Product post object
    $post_product = $product->post;

    $attachment_count = count($product->get_gallery_attachment_ids());
    $gallery = $attachment_count > 0 ? '[product-gallery]' : '';

    // CODE ERROR ===> This was returning empty before. You need to put
    // the product ID in get_post_thumbnail_id() function to get something
    $props = wc_get_product_attachment_props(get_post_thumbnail_id($product_id), $post_product);

    // Testing $props output (array not empty) => comment/uncomment line below
    // echo '<br>ITEM ID: ' . $item_id . '<br><pre>'; var_dump($props);  echo '</pre><br>';

    $image = get_the_post_thumbnail( $product->id, apply_filters('single_product_large_thumbnail_size', 'shop_single' ), array(
        'title' => $props['title'],
        'alt' => $props['alt'],
    ));



    // Added a condition to avoid other line items than products (like shipping line)
    if(!empty($product_id))
        echo apply_filters(
                'woocommerce_single_product_image_html', sprintf(
                        '<td class="name" colspan="1" ><a style="text-decoration: none;clear:both;float: left;margin-top: 5px;" href="%s" download = "Order#' . $order_id . '-' . $variation_id . '"><input type = "button" value="Download image"/></a></td>', esc_url($props['url'])
                ), $product->id
        );

 }

*/




