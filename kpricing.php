<?php
/*
Author: Konrad G
Author URI: www.kgretk.com
File: Price calculation.
Changelog:
2023-01-25 v1
2023-02-08 v2, column numbers updated, added MOQ and Days
2023-03-25 added Patches print type
2023-03-28 added q_from
2023-04-02 neck pricing * 0.45
2023-05-09 fin round
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
// finishing	fin2	MOQ	Add_Days	Break1to10 ...
// FOLD+BAG+PACK	info	1	1	$1.59 ...

// if the above csv files structure change, then column number must be updated (get_column function)

// SP ink multiplier - line 320-360
/*
Waterbased	x1.25
Oilbased	x1
Puff	x1.45
Stretch	x1.45
Flocking	x2.5
3M Reflective x1.85
*/

// TODO add md5 to validate price components

$csv_path = '../../uploads/';

$prices_sp = array_map('str_getcsv', file($csv_path . 'dtla-2023-sp.csv'));
$prices_other = array_map('str_getcsv', file($csv_path . 'dtla-2023-other.csv'));
$prices_finishing = array_map('str_getcsv', file($csv_path . 'dtla-2023-finishing.csv'));

$max_colors = 8;

$quantity = 0;
$q_from = 0;
$price = 0;
$prod_price = 0;


// temporary
if (null !==($_GET) && sizeof($_GET) > 0 && $_GET['text']==1) {

	?>
	sp:
	<pre>
		SP:
		<?php print_r( $prices_sp );  ?>
		other:
		<?php print_r( $prices_other );  ?>
		finishing:
		<?php print_r( $prices_finishing );  ?>
	</pre>
	
	
	
	
	<?php

}
else {

	header('Content-type: application/json; charset=utf-8');
	ob_start();
	
	$a = array();


	// secure POST? - OK, since not used for db or file operations
	
	if (null !==($_POST) && sizeof($_POST) > 0) {

		$a['quantity'] = intval($_POST['quantity']);
		// to be safe
		if ($a['quantity'] == 0) $a['quantity'] = 1;
		
		get_column( $a['quantity'] );
		$a['q_from'] = $q_from;

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


		// SP ink multiplier - in function
		/*
		Waterbased	x1.25
		Oilbased	x1
		Puff	x1.45
		Stretch	x1.45
		Flocking	x2.5
		3M Reflective x1.85
		*/

		// decorations price calc, each side
		$front_price = 0;
		$back_price = 0;
		$left_price = 0;
		$right_price = 0;
		$neck_price = 0;

		$front_moq = 1;
		$back_moq = 1;
		$left_moq = 1;
		$right_moq = 1;
		$neck_moq = 1;

		$front_days = 0;
		$back_days = 0;
		$left_days = 0;
		$right_days = 0;
		$neck_days = 0;


		// product price from price_table
		$price_table = $_POST['price_table'];
		$a['prod_price'] = round ( 1 * kc( $price_table[$col_pr] ), 2);

		//FINAL PRICE, + $front_price + $back_price + $left_price + $right_price + $neck_price - already added 
		$a['price'] = round( your_prod( $a['prod_price'], $a['quantity'] )  , 2);

		//TODO + finishings ?


		// get prices for user selections
		//get_column( 100 );	$a['price-100'] = your_prod( 0 , 100);
		//get_column( 250 );	$a['price-250'] = your_prod( 0 , 250);

		get_column( $q_from + $a['quantity'] + 1);
		$a['price_next'] = your_prod( 0 , $q_from + $a['quantity'] + 1);


/*
		$a['sp'] = kc( $prices_sp[$a['colors']][$col_sp] ) *1; //SP price for given quantity and colors - table [ row ] [ column ]
		$a['setup'] = kc( $prices_sp[$a['colors']][1] ) *1; //SP setup cost in column 2

		$a['dtg'] = kc( $prices_other[1][$col_o] ); //DTG price for given quantity - table [ row ] [ column ]
		$a['wbt'] = kc( $prices_other[2][$col_o] ); //WBT price for given quantity
		$a['emb'] = kc( $prices_other[3][$col_o] ); //EMB price for given quantity
*/
		$a['f1'] = round ( kc( $prices_finishing[1][$col_fin] ) ,2 ); //Finishing price for given quantity - table [ row ] [ column ]
		$a['f2'] = round ( kc( $prices_finishing[2][$col_fin] ) ,2 );
		$a['f3'] = round ( kc( $prices_finishing[3][$col_fin] ) ,2 );
		$a['f4'] = round ( kc( $prices_finishing[4][$col_fin] ) ,2 );
		$a['f5'] = round ( kc( $prices_finishing[5][$col_fin] ) ,2 );
		$a['f6'] = round ( kc( $prices_finishing[6][$col_fin] ) ,2 );

		// MOQ, days for finishings
		$a['f1_moq'] = kc( $prices_finishing[1][2] ); // column 2
		$a['f2_moq'] = kc( $prices_finishing[2][2] );
		$a['f3_moq'] = kc( $prices_finishing[3][2] );
		$a['f4_moq'] = kc( $prices_finishing[4][2] );
		$a['f5_moq'] = kc( $prices_finishing[5][2] );
		$a['f6_moq'] = kc( $prices_finishing[6][2] );

		$a['f1_days'] = kc( $prices_finishing[1][3] ); // column 3
		$a['f2_days'] = kc( $prices_finishing[2][3] );
		$a['f3_days'] = kc( $prices_finishing[3][3] );
		$a['f4_days'] = kc( $prices_finishing[4][3] );
		$a['f5_days'] = kc( $prices_finishing[5][3] );
		$a['f6_days'] = kc( $prices_finishing[6][3] );



		
		// temp
		if ($debug) {
			$a['col_pr'] = $col_pr; $a['col_sp'] = $col_sp; $a['col_o'] = $col_o; $a['col_fin'] = $col_fin; //temp

			$a['front_price-1'] = kc( $prices_sp[ (int)$a['front_colors']][$col_sp] );
			if ( $a['front_colors']>0 )
				$a['front_price-setup-div-q'] = kc( $prices_sp[(int)$a['front_colors']][1]/$a['quantity']);
			else
				$a['front_price-setup-div-q'] = 0;
			$a['front_price-ink-m'] = $front_ink_m;



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
	global $q_from;

	// pricing header: 1-9	10-24	25-49	50-74	75-99	100-249	250-499	500-749	750-999	1000-2499	2500-4999	5000-7499	7500-9999
	// using breaks from product price header, not csv files
	// column number depends on csv file structure (see info at start)

	if (              $q <= 9   ) { $col_pr = 0; $col_sp = 4; $col_o = 3; $col_fin = 4; $q_from = 10 - $q; }
	if ( $q >= 10  && $q <= 24  ) { $col_pr = 1; $col_sp = 5; $col_o = 4; $col_fin = 5; $q_from = 25 - $q; }
	if ( $q >= 25  && $q <= 49  ) { $col_pr = 2; $col_sp = 6; $col_o = 5; $col_fin = 6; $q_from = 50 - $q; }
	if ( $q >= 50  && $q <= 74  ) { $col_pr = 3; $col_sp = 7; $col_o = 6; $col_fin = 7; $q_from = 75 - $q; }
	if ( $q >= 75  && $q <= 99  ) { $col_pr = 4; $col_sp = 8; $col_o = 7; $col_fin = 8; $q_from = 100 - $q; }
	if ( $q >= 100 && $q <= 249 ) { $col_pr = 5; $col_sp = 9; $col_o = 8; $col_fin = 9; $q_from = 250 - $q; }
	if ( $q >= 250 && $q <= 499 ) { $col_pr = 6; $col_sp = 10; $col_o = 9; $col_fin = 10; $q_from = 500 - $q; }
	if ( $q >= 500 && $q <= 749 ) { $col_pr = 7; $col_sp = 11; $col_o = 10; $col_fin = 11; $q_from = 750 - $q; }
	if ( $q >= 750 && $q <= 999 ) { $col_pr = 8; $col_sp = 12; $col_o = 11; $col_fin = 12; $q_from = 1000 - $q; }
	if ( $q >= 1000 && $q <= 2499 ) { $col_pr = 9; $col_sp = 13; $col_o = 12; $col_fin = 13; $q_from = 2500 - $q; }
	if ( $q >= 2500 && $q <= 4999 ) { $col_pr = 10; $col_sp = 14; $col_o = 13; $col_fin = 14; $q_from = 5000 - $q; }
	if ( $q >= 5000 && $q <= 7499 ) { $col_pr = 11; $col_sp = 15; $col_o = 14; $col_fin = 15; $q_from = 7500 - $q; }
	if ( $q >= 7500 && $q <= 9999 ) { $col_pr = 12; $col_sp = 16; $col_o = 15; $col_fin = 16; $q_from = 10000 - $q; }
	if ( $q >= 9999               ) { $col_pr = 12; $col_sp = 17; $col_o = 16; $col_fin = 17; $q_from = 0; }
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



// get current product at diff quantities - same function as in kbulk.php
function your_prod( $prod_pr, $quant ) {
	global $price_table;
	global $a;
	global $prices_sp;
	global $prices_other;
	global $col_pr;
	global $col_sp;
	global $col_o;

	global $front_price;
	global $back_price;
	global $left_price;
	global $right_price;
	global $neck_price;

	global $front_moq;
	global $back_moq;
	global $left_moq;
	global $right_moq;
	global $neck_moq;

	global $front_days;
	global $back_days;
	global $left_days;
	global $right_days;
	global $neck_days;
	

	// SP ink multiplier
		/*
		Waterbased	x1.25
		Oilbased	x1
		Puff	x1.45
		Stretch	x1.45
		Flocking	x2.5
		3M Reflective x1.85
		*/

	global $front_ink_m;
	global $back_ink_m;
	global $left_ink_m;
	global $right_ink_m;
	global $neck_ink_m;
	
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
			$front_price = ( kc( $prices_sp[ (int)$a['front_colors']][$col_sp] ) + kc( $prices_sp[(int)$a['front_colors']][1]/$a['quantity']) ) * $front_ink_m;
			$front_moq = $prices_sp[1][2];
			$front_days = $prices_sp[1][3];
			break;
		case 'Embroidery':
			$front_price = kc( $prices_other[3][$col_o] ); // row no 3 from Other csv file
			$front_moq 	= $prices_other[3][1];
			$front_days = $prices_other[3][2];
			break;
		case 'WBT':
		case 'Water-based Transfer':
		case 'Waterbase Transfers':
			$front_price = kc( $prices_other[2][$col_o] );
			$front_moq 	= $prices_other[2][1];
			$front_days = $prices_other[2][2];
			break;
		case 'DTG':
			$front_price = kc( $prices_other[1][$col_o] );
			$front_moq 	= $prices_other[1][1];
			$front_days = $prices_other[1][2];
			break;
		case 'Patches':
			$front_price = kc( $prices_other[4][$col_o] ); // row 4
			$front_moq 	= $prices_other[4][1];
			$front_days = $prices_other[4][2];
			break;
	}
	$a['front_price'] = $front_price;
	$a['front_moq'] = $front_moq;
	$a['front_days'] = $front_days;

	switch ( trim($a['back_print_type']) ) {
		case 'Screen Print': 
			$back_price = ( kc( $prices_sp[ (int)$a['back_colors']][$col_sp] ) + kc( $prices_sp[(int)$a['back_colors']][1]/$a['quantity']) ) * $back_ink_m;
			$back_moq = $prices_sp[1][2];
			$back_days = $prices_sp[1][3];
			break;
		case 'Embroidery':
			$back_price = kc( $prices_other[3][$col_o] ); // row no 3 from Other csv file
			$back_moq 	= $prices_other[3][1];
			$back_days 	= $prices_other[3][2];
			break;
		case 'WBT':
		case 'Water-based Transfer':
		case 'Waterbase Transfers':
			$back_price = kc( $prices_other[2][$col_o] );
			$back_moq 	= $prices_other[2][1];
			$back_days 	= $prices_other[2][2];
			break;
		case 'DTG':
			$back_price = kc( $prices_other[1][$col_o] );
			$back_moq 	= $prices_other[1][1];
			$back_days 	= $prices_other[1][2];
			break;
		case 'Patches':
			$back_price = kc( $prices_other[4][$col_o] ); // row 4
			$back_moq 	= $prices_other[4][1];
			$back_days = $prices_other[4][2];
			break;
	}
	$a['back_price'] = $back_price;
	$a['back_moq'] = $back_moq;
	$a['back_days'] = $back_days;

	switch ( trim($a['left_print_type']) ) {
		case 'Screen Print': 
			$left_price = ( kc( $prices_sp[ (int)$a['left_colors']][$col_sp] ) + kc( $prices_sp[(int)$a['left_colors']][1]/$a['quantity']) ) * $left_ink_m;
			$left_moq = $prices_sp[1][2];
			$left_days = $prices_sp[1][3];
			break;
		case 'Embroidery':
			$left_price = kc( $prices_other[3][$col_o] ); // row no 3 from Other csv file
			$left_moq 	= $prices_other[3][1];
			$left_days 	= $prices_other[3][2];
			break;
		case 'WBT':
		case 'Water-based Transfer':
		case 'Waterbase Transfers':
			$left_price = kc( $prices_other[2][$col_o] );
			$left_moq 	= $prices_other[2][1];
			$left_days 	= $prices_other[2][2];
			break;
		case 'DTG':
			$left_price = kc( $prices_other[1][$col_o] );
			$left_moq 	= $prices_other[1][1];
			$left_days 	= $prices_other[1][2];
			break;
		case 'Patches':
			$left_price = kc( $prices_other[4][$col_o] ); // row 4
			$left_moq 	= $prices_other[4][1];
			$left_days = $prices_other[4][2];
			break;
	}
	$a['left_price'] = $left_price;
	$a['left_moq'] = $left_moq;
	$a['left_days'] = $left_days;

	switch ( trim($a['right_print_type']) ) {
		case 'Screen Print': 
			$right_price = ( kc( $prices_sp[ (int)$a['right_colors']][$col_sp] ) + kc( $prices_sp[(int)$a['right_colors']][1]/$a['quantity']) ) * $right_ink_m;
			$right_moq = $prices_sp[1][2];
			$right_days = $prices_sp[1][3];
			break;
		case 'Embroidery':
			$right_price = kc( $prices_other[3][$col_o] ); // row no 3 from Other csv file
			$right_moq 	= $prices_other[3][1];
			$right_days	= $prices_other[3][2];
			break;
		case 'WBT':
		case 'Water-based Transfer':
		case 'Waterbase Transfers':
			$right_price = kc( $prices_other[2][$col_o] );
			$right_moq 	= $prices_other[2][1];
			$right_days	= $prices_other[2][2];
			break;
		case 'DTG':
			$right_price = kc( $prices_other[1][$col_o] );
			$right_moq 	= $prices_other[1][1];
			$right_days	= $prices_other[1][2];
			break;
		case 'Patches':
			$right_price = kc( $prices_other[4][$col_o] ); // row 4
			$right_moq 	= $prices_other[4][1];
			$right_days = $prices_other[4][2];
			break;
	}
	$a['right_price'] = $right_price;
	$a['right_moq'] = $right_moq;
	$a['right_days'] = $right_days;

	switch ( trim($a['neck_print_type']) ) {
		case 'Screen Print': 
			$neck_price = ( kc( $prices_sp[ (int)$a['neck_colors']][$col_sp] ) + kc( $prices_sp[(int)$a['neck_colors']][1]/$a['quantity']) ) * $neck_ink_m;
			$neck_moq = $prices_sp[1][2];
			$neck_days = $prices_sp[1][3];
			break;
		case 'Embroidery':
			$neck_price = kc( $prices_other[3][$col_o] ); // row no 3 from Other csv file
			$neck_moq 	= $prices_other[3][1];
			$neck_days	= $prices_other[3][2];
			break;
		case 'WBT':
		case 'Water-based Transfer':
		case 'Waterbase Transfers':
			$neck_price = kc( $prices_other[2][$col_o] );
			$neck_moq 	= $prices_other[2][1];
			$neck_days	= $prices_other[2][2];
			break;
		case 'DTG':
			$neck_price = kc( $prices_other[1][$col_o] );
			$neck_moq 	= $prices_other[1][1];
			$neck_days	= $prices_other[1][2];
			break;
		case 'Patches':
			$neck_price = kc( $prices_other[4][$col_o] ); // row 4
			$neck_moq 	= $prices_other[4][1];
			$neck_days = $prices_other[4][2];
			break;
	}
	$neck_price = $neck_price * 0.45; // correction 4/2
	$a['neck_price'] = $neck_price;
	$a['neck_moq'] = $neck_moq;
	$a['neck_days'] = $neck_days;
	

	//FINAL PRICE
	$prod_pr = round ( 1 * kc( $price_table[$col_pr] ), 2);
	return round( $prod_pr + $front_price + $back_price + $left_price + $right_price + $neck_price , 2 );

	
}
