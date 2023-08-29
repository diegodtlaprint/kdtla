<?php
/*
Author: Konrad G
Author URI: www.kgretk.com
File: Price calculation for bulk table
Changelog:
2023-03-24 v1

*/


global $col_pr;
global $col_sp;
global $col_o;
global $col_fin;


$debug = 1; //temp

// csv files should be placed in /wp-content/uploads/
// dtla-2023-sp.csv , dtla-2023-other.csv , dtla-2023-finishings.csv

// sp structure - prices start at column 4 (counting from 0):
//	NumberOfColors	SetupCost	MOQ	Add Days	Break1to10 ...
//	1	60	25	5	$6.32  ...

// other structure - prices start at column 3 (counting from 0), rows order matters! :
// other	MOQ	Add_Days	Break1to10 ...
// DTG	1	5	$55.00 ...

// finishing structure - prices start at column 4 (counting from 0), rows order matters! :
// fininishing	fin2	MOQ	Add_Days	Break1to10 ...
// FOLD+BAG+PACK	info	1	1	$1.59 ...

// if the above csv files structure change, then column number must be updated (get_column function)

		// SP ink multiplier
		/*
		Waterbased	x1.25
		Oilbased	x1
		Puff	x1.45
		Stretch	x1.45
		Flocking	x2.5
		3M Reflective x1.85
		*/
		$front_ink_m = 1;
		$back_ink_m = 1;
		$left_ink_m = 1;
		$right_ink_m = 1;
		$neck_ink_m = 1;

// MAYBE add md5 to validate price components

$csv_path = '../../uploads/';

$prices_sp = array_map('str_getcsv', file($csv_path . 'dtla-2023-sp.csv'));
$prices_other = array_map('str_getcsv', file($csv_path . 'dtla-2023-other.csv'));
//$prices_finishing = array_map('str_getcsv', file($csv_path . 'dtla-2023-finishing.csv'));

$max_colors = 8;

$quantity = 0;
$price = 0;
$prod_price = 0;

$a = array();


$bulk = array(
	['x','1','50','100','250','500','1000','2500','5000'], // 1 = sample
	['your','', '', '', '', '', '', '', '0'],
	['SP 1 col', '', '', '', '', '', '', '', '1'],
	['SP 2 col', '', '', '', '', '', '', '', '2'],
	['SP 3 col', '', '', '', '', '', '', '', '3'],
	['SP 4 col', '', '', '', '', '', '', '', '4'],
	['EMB', '', '', '', '', '', '', '', '5'],
	['DTG', '', '', '', '', '', '', '', '6'],
	['WBT', '', '', '', '', '', '', '', '7']
);


if (null !==($_GET) && sizeof($_GET) > 0 && $_GET['text']==1) {

// temporary
	?>
	sp:
	<pre>
		SP:
		<?php print_r( $prices_sp );  ?>
		other:
		<?php print_r( $prices_other );  ?>
		finishing: not needed
		<?php //print_r( $prices_finishing );  ?>
		 

		<?php print_r( $bulk );  ?>
	</pre>
	
	
	
	
	<?php

}
else {

	header('Content-type: application/json; charset=utf-8');
	ob_start();
	// if (null !==($_GET) && sizeof($_GET) > 0) { echo 'get:'; print_r($_GET); } 
	// if (null !==($_POST) && sizeof($_POST) > 0) { echo 'POST:'; print_r($_POST); } 

	// if (null !==($_FILES) ) { echo '_FILES:'; print_r($_FILES); } 
	
	

	$a['ink'] = 1;


	// secure POST? - OK, since not used for db or file operations
	
	if (null !==($_POST) && sizeof($_POST) > 0) {
		
		$a['quantity'] = intval($_POST['quantity']);
		get_column( $a['quantity'] );

		$price_table = $_POST['price_table']; //from product
		$a['prod_price'] = round ( 1 * kc( $price_table[$col_pr] ), 2);

		$a['front_print_type'] = $_POST['front_print_type'];
		$a['front_colors'] = $_POST['front_colors'];
		$a['front_ink'] = $_POST['front_ink'];
		$a['back_print_type'] = $_POST['back_print_type'];
		$a['back_colors'] = $_POST['back_colors'];
		$a['back_ink'] = $_POST['back_ink'];
		$a['left_print_type'] = $_POST['left_print_type'];
		$a['left_colors'] = $_POST['left_colors'];
		$a['left_ink'] = $_POST['left_ink'];
		$a['right_print_type'] = $_POST['right_print_type'];
		$a['right_colors'] = $_POST['right_colors'];
		$a['right_ink'] = $_POST['right_ink'];
		$a['neck_print_type'] = $_POST['neck_print_type'];
		$a['neck_colors'] = $_POST['neck_colors'];
		$a['neck_ink'] = $_POST['neck_ink'];
		
		
		
		//$a['quantity'] = 100;
		//get_column( $a['quantity'] );

		//temp - for testing without POST
		/*
		$price_table = ["$18.89","$17.32","$16.57","$15.89","$15.68","$15.48","$14.52","$14.39","$14.25","$14.11","$13.98","$13.84","$13.7"];
		$a['prod_price'] = round ( 1 * kc( $price_table[$col_pr] ), 2);
		$a['price_table'] = $price_table;

		$a['front_print_type'] = 'Screen Print';
		$a['front_colors'] = 1;
		$a['front_ink'] = 'Oil Based';
		$a['back_print_type'] = '';
		$a['back_colors'] = '';
		$a['back_ink'] = '';
		$a['left_print_type'] = '';
		$a['left_colors'] = '';
		$a['left_ink'] = '';
		$a['right_print_type'] = '';
		$a['right_colors'] = '';
		$a['right_ink'] = '';
		$a['neck_print_type'] = '';
		$a['neck_colors'] = '';
		$a['neck_ink'] = '';
		*/
		// end testing

		$front_price = 0;
		$back_price = 0;
		$left_price = 0;
		$right_price = 0;
		$neck_price = 0;


		// calculate recommended options
		foreach ($bulk[0] as $k=>$v) {
			if ( $k>0 ) { //except first column
				//echo $v.'-';
				get_column( $v ); // update column number

				// get prices for user selections
				$prod_price = round ( 1 * kc( $price_table[$col_pr] ), 2);
				$bulk[1][$k] = your_prod( $prod_price, $v);

				// SP 1 color
				$bulk[2][$k] =  round( ( $prod_price + kc( $prices_sp[ 1 ][$col_sp] ) + kc( $prices_sp[ 1 ][1]/$v) ) * $front_ink_m , 2);
				// SP 2 colors
				$bulk[3][$k] =  round( ( $prod_price + kc( $prices_sp[ 2 ][$col_sp] ) + kc( $prices_sp[ 2 ][1]/$v) ) * $front_ink_m, 2);
				// SP 3 colors
				$bulk[4][$k] =  round( ( $prod_price + kc( $prices_sp[ 3 ][$col_sp] ) + kc( $prices_sp[ 3 ][1]/$v) ) * $front_ink_m, 2);
				// SP 4 colors
				$bulk[5][$k] =  round( ( $prod_price + kc( $prices_sp[ 4 ][$col_sp] ) + kc( $prices_sp[ 4 ][1]/$v) ) * $front_ink_m, 2);

				//EMB
				$bulk[6][$k] =  round( $prod_price + kc( $prices_other[3][$col_o] ) , 2); // row number according to csv file
				//DTG
				$bulk[7][$k] =  round( $prod_price + kc( $prices_other[1][$col_o] ) , 2);
				//WBT
				$bulk[8][$k] =  round( $prod_price + kc( $prices_other[2][$col_o] ) , 2);


			}
		}

		//FINAL PRICE
		//$a['price'] = round( $a['prod_price'] + $front_price + $back_price + $left_price + $right_price + $neck_price , 2 );

		
		$a['bulk'] = $bulk;


		if ($debug) {
			$a['col_pr'] = $col_pr; $a['col_sp'] = $col_sp; $a['col_o'] = $col_o; $a['col_fin'] = $col_fin; //temp


		}

	}

	$out = json_encode( $a );
	echo $out;

	ob_end_flush();	


}

function get_column( $q ) {
	global $col_pr;
	global $col_sp;
	global $col_o;
	global $col_fin;

	// pricing header: 1-9	10-24	25-49	50-74	75-99	100-249	250-499	500-749	750-999	1000-2499	2500-4999	5000-7499	7500-9999
	// using breaks from product price header, not csv files
	// column number depends on csv file structure (see info at start)

	if (              $q <= 9   ) { $col_pr = 0; $col_sp = 4; $col_o = 3; $col_fin = 4; }
	if ( $q >= 10  && $q <= 24  ) { $col_pr = 1; $col_sp = 5; $col_o = 4; $col_fin = 5; }
	if ( $q >= 25  && $q <= 49  ) { $col_pr = 2; $col_sp = 6; $col_o = 5; $col_fin = 6; }
	if ( $q >= 50  && $q <= 74  ) { $col_pr = 3; $col_sp = 7; $col_o = 6; $col_fin = 7; }
	if ( $q >= 75  && $q <= 99  ) { $col_pr = 4; $col_sp = 8; $col_o = 7; $col_fin = 8; }
	if ( $q >= 100 && $q <= 249 ) { $col_pr = 5; $col_sp = 9; $col_o = 8; $col_fin = 9; }
	if ( $q >= 250 && $q <= 499 ) { $col_pr = 6; $col_sp = 10; $col_o = 9; $col_fin = 10; }
	if ( $q >= 500 && $q <= 749 ) { $col_pr = 7; $col_sp = 11; $col_o = 10; $col_fin = 11; }
	if ( $q >= 750 && $q <= 999 ) { $col_pr = 8; $col_sp = 12; $col_o = 11; $col_fin = 12; }
	if ( $q >= 1000 && $q <= 2499 ) { $col_pr = 9; $col_sp = 13; $col_o = 12; $col_fin = 13; }
	if ( $q >= 2500 && $q <= 4999 ) { $col_pr = 10; $col_sp = 14; $col_o = 13; $col_fin = 14; }
	if ( $q >= 5000 && $q <= 7499 ) { $col_pr = 11; $col_sp = 15; $col_o = 14; $col_fin = 15; }
	if ( $q >= 7500 && $q <= 9999 ) { $col_pr = 12; $col_sp = 16; $col_o = 15; $col_fin = 16; }
	if ( $q >= 9999               ) { $col_pr = 12; $col_sp = 17; $col_o = 16; $col_fin = 17; }
}

// clear $ from price
function kc($v) {
	if ( substr($v,0,1)=='$') {
		$z = substr($v,1,10);
		if (is_numeric($z))
			return trim($z);
		else
			die('error: not numeric'.$z);
	}
	else
		return trim($v);

}


// get current product at diff quantities
function your_prod( $prod_pr, $quant ) {
	global $price_table;
	global $a;
	global $prices_sp;
	global $prices_other;
	global $col_sp;
	global $col_o;

	// SP ink multiplier
		/*
		Waterbased	x1.25
		Oilbased	x1
		Puff	x1.45
		Stretch	x1.45
		Flocking	x2.5
		3M Reflective x1.85
		*/
		$front_ink_m = 1;
		$back_ink_m = 1;
		$left_ink_m = 1;
		$right_ink_m = 1;
		$neck_ink_m = 1;



	switch ( trim($a['front_ink']) ) {
		//case 'Oil Based':
		//case 'OilBased': $front_ink_m = 1; break;
		case 'Waterbased':
		case 'Water Based':	 $front_ink_m = 1.25; break;
		case 'Puff': $front_ink_m = 1.45; break;
		case 'Stretch': $front_ink_m = 1.45; break;
		case 'Flocking': $front_ink_m = 2.5; break;
		case '3M Reflective': $front_ink_m = 1.85; break;
	}
	switch ( trim($a['back_ink']) ) {
		case 'Waterbased': 
		case 'Water Based': $back_ink_m = 1.25; break;
		case 'Puff': $back_ink_m = 1.45; break;
		case 'Stretch': $back_ink_m = 1.45; break;
		case 'Flocking': $back_ink_m = 2.5; break;
		case '3M Reflective': $back_ink_m = 1.85; break;
	}
	switch ( trim($a['left_ink']) ) {
		case 'Waterbased':
		case 'Water Based': $left_ink_m = 1.25; break;
		case 'Puff': $left_ink_m = 1.45; break;
		case 'Stretch': $left_ink_m = 1.45; break;
		case 'Flocking': $left_ink_m = 2.5; break;
		case '3M Reflective': $left_ink_m = 1.85; break;
	}
	switch ( trim($a['right_ink']) ) {
		case 'Waterbased':
		case 'Water Based': $right_ink_m = 1.25; break;
		case 'Puff': $right_ink_m = 1.45; break;
		case 'Stretch': $right_ink_m = 1.45; break;
		case 'Flocking': $right_ink_m = 2.5; break;
		case '3M Reflective': $right_ink_m = 1.85; break;
	}
	switch ( trim($a['neck_ink']) ) {
		case 'Waterbased':
		case 'Water Based': $neck_ink_m = 1.25; break;
		case 'Puff': $neck_ink_m = 1.45; break;
		case 'Stretch': $neck_ink_m = 1.45; break;
		case 'Flocking': $neck_ink_m = 2.5; break;
		case '3M Reflective': $neck_ink_m = 1.85; break;
	}
	// no decorations
	$front_price = 0;
	$back_price = 0;
	$left_price = 0;
	$right_price = 0;
	$neck_price = 0;

	switch ( trim($a['front_print_type']) ) {
		case 'Screen Print': 
			$front_price = ( kc( $prices_sp[ (int)$a['front_colors']][$col_sp] ) + kc( $prices_sp[(int)$a['front_colors']][1]/$quant) ) * $front_ink_m;
			
			break;
		case 'Embroidery':
			$front_price = kc( $prices_other[3][$col_o] ); // row no 3 from Other csv file
			
			break;
		case 'WBT':
		case 'Water-based Transfer':
		case 'Waterbase Transfers':
			$front_price = kc( $prices_other[2][$col_o] );
			
			break;
		case 'DTG':
			$front_price = kc( $prices_other[1][$col_o] );
			
			break;
	
	}
	

	switch ( trim($a['back_print_type']) ) {
		case 'Screen Print': 
			$back_price = ( kc( $prices_sp[ (int)$a['back_colors']][$col_sp] ) + kc( $prices_sp[(int)$a['back_colors']][1]/$quant) ) * $back_ink_m;
			
			break;
		case 'Embroidery':
			$back_price = kc( $prices_other[3][$col_o] ); // row no 3 from Other csv file
			
			break;
		case 'WBT':
		case 'Water-based Transfer':
		case 'Waterbase Transfers':
			$back_price = kc( $prices_other[2][$col_o] );
			
			break;
		case 'DTG':
			$back_price = kc( $prices_other[1][$col_o] );
			
			break;
	
	}
	

	switch ( trim($a['left_print_type']) ) {
		case 'Screen Print': 
			$left_price = ( kc( $prices_sp[ (int)$a['left_colors']][$col_sp] ) + kc( $prices_sp[(int)$a['left_colors']][1]/$quant) ) * $left_ink_m;
			
			break;
		case 'Embroidery':
			$left_price = kc( $prices_other[3][$col_o] ); // row no 3 from Other csv file
			
			break;
		case 'WBT':
		case 'Water-based Transfer':
		case 'Waterbase Transfers':
			$left_price = kc( $prices_other[2][$col_o] );
			
			break;
		case 'DTG':
			$left_price = kc( $prices_other[1][$col_o] );
			
			break;
	}
	

	switch ( trim($a['right_print_type']) ) {
		case 'Screen Print': 
			$right_price = ( kc( $prices_sp[ (int)$a['right_colors']][$col_sp] ) + kc( $prices_sp[(int)$a['right_colors']][1]/$quant) ) * $right_ink_m;
			
			break;
		case 'Embroidery':
			$right_price = kc( $prices_other[3][$col_o] ); // row no 3 from Other csv file
			
		case 'WBT':
		case 'Water-based Transfer':
		case 'Waterbase Transfers':
			$right_price = kc( $prices_other[2][$col_o] );
			
			break;
		case 'DTG':
			$right_price = kc( $prices_other[1][$col_o] );
			
			break;
	}
	

	switch ( trim($a['neck_print_type']) ) {
		case 'Screen Print': 
			$neck_price = ( kc( $prices_sp[ (int)$a['neck_colors']][$col_sp] ) + kc( $prices_sp[(int)$a['neck_colors']][1]/$quant) ) * $neck_ink_m;
			
			break;
		case 'Embroidery':
			$neck_price = kc( $prices_other[3][$col_o] ); // row no 3 from Other csv file
			
			break;
		case 'WBT':
		case 'Water-based Transfer':
		case 'Waterbase Transfers':
			$neck_price = kc( $prices_other[2][$col_o] );
			
			break;
		case 'DTG':
			$neck_price = kc( $prices_other[1][$col_o] );
			
			break;
	}
	

	//FINAL PRICE
	return round( $prod_pr + $front_price + $back_price + $left_price + $right_price + $neck_price , 2 );

	
}