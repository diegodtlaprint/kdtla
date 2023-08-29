<?php
/*
Author: Konrad G
Author URI: www.kgretk.com
File: Token generation.					- TODO do we need it?
Changelog:
2023-03-13 start
2023-07-27 needed due to caching
*/


// define some things

$secret = 'seCRet kkxx';

$d = 0; //temp debug


// don't change below
$a = array();
$error = '';

$ip = isset($_SERVER["HTTP_CF_CONNECTING_IP"])? $_SERVER["HTTP_CF_CONNECTING_IP"]: $_SERVER["REMOTE_ADDR"];

if ($d) $a['debug'] = '| DEBUG |';
if ($d) $a['debug'] .= ' ip='.$ip .'|';
if ($d) $a['debug'] .= ' ks='.k_get_session();

header('Content-type: application/json; charset=utf-8');


// session from WC cookie
$a['wc'] = 0;
$s = k_get_session();

if ( strlen($s)==0 ) {
	// no WC session then create own id if no cookie already

	if (isset($_COOKIE) && sizeof($_COOKIE)>0) {
		if (isset($_COOKIE['ksession']) ) {
			if ($d) $a['debug'] = '| ksession cookie = '.$_COOKIE['ksession'];

			$s = $_COOKIE['ksession'];
		}
	}
	
	if ( strlen($s)==0 ) {
		$s = date("Ymd-H-i-s-") . uniqid();
		setcookie("ksession", $s, time()+3600*24*2, "/", "", 1, 0);
	}
} else {
	$a['wc'] = $s;
	$s = date("Ymd-H-i-s-") . $s;
}


/* no WC session before adding to cart ... */
/*
if ( strlen($s)==0 ) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	$a['error'] = 'No WC session! ...';
	echo json_encode($a);
	die();
}
*/

//security
if (!(strpos($s, '..') === false)) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	$a['error'] = 'Wrong session!';
	echo json_encode($a);
	die();
}


// calculate token
$ktoken = md5( $s . $secret . $ip );
//$ktoken = md5( $secret  ); // temp
//$ktoken = md5( $s . $secret ); // less safe, without user IP

if ($d) $a['debug'] .= ' t='.$ktoken;



// output session and token
$a['ksession'] = $s;
$a['ktoken'] = $ktoken;



if (!$error) {
	$a['result'] = 'OK';
	
}
else {
	$a['error'] = $error;
}



echo json_encode($a);





//session id from WC cookie - wp_woocommerce_session_...
function k_get_session() {
	$values = array('');
	if (isset($_COOKIE) && sizeof($_COOKIE)>0)
		foreach( $_COOKIE as $key => $value ) {
			if( stripos( $key, 'wp_woocommerce_session_' ) === false ) {
				continue;
			}
			$values = explode( '||', $value );
		}

	//return date("Ymd-H-i-").$values[0];
	return $values[0];
}

