<?php
/*
Author: Konrad G
Author URI: www.kgretk.com
File: Artwork files - background remove
Changelog:
2023-04-21 start
2023-06-13 API trial

*/


// define some things

$path_main = '../../uploads/artwork/'; //with / at the end - ERROR in Imagemagick?
$secret = 'seCRet kkxx';

// for bg removal uploads
$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF); // HEIC not accepted

$d = 0; //temp debug
$do_log = 1; // log upload info to text file


// background removal version: 1. simple, 2. API
$bg_v = 2;


// API key from:  https://withoutbg.com/
//$wbg_api = '2b1842f4-76c5-4ee8-8cb6-1621c4503ef4'; // kgretk API
$wbg_api = 'dff536fb-8b03-4b60-a828-32967aa4d5bc'; // dtlaprint API key



// ============= don't change below

$s = '';
$a = array();
$error = false;
$n = '-';
$sides = array('front', 'back', 'left', 'right', 'neck'); // also folder names
$side = '';

if ($d) $a['debug'] = '| DEBUG |';

// get session 
$s = (string) trim(rawurldecode($_GET['s']));


// calculate token
$ip = isset($_SERVER["HTTP_CF_CONNECTING_IP"])? $_SERVER["HTTP_CF_CONNECTING_IP"]: $_SERVER["REMOTE_ADDR"];
$ktoken = md5( $s . $secret . $ip ); // TODO add user agent?
//$ktoken = md5( $secret  ); // temp
//$ktoken = md5( $s . $secret ); // less safe, without user IP

// path to save artwork, with removed background
$path  = $path_main . $s .'/';

// $path_without_bg = $path . 'without_bg' . '/'; // not used - to use, uncomment block in lines 195


// security - check token
	if ( isset($_GET['ktoken']) && $_GET['ktoken'] !== $ktoken ) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 403 FORBIDDEN', true, 403);
		$a['error'] = 'Wrong token! '; //.@$_POST['ktoken'];
		echo json_encode($a);
		die();
	}

// check if uploads/artwork folder exists

if (!is_dir($path_main)) {
	$a['error'] = 'Main path folder does not exist!';
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	echo json_encode($a);
	die;
}

//security
if (!(strpos($s, '..') === false)) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	$a['error'] = 'Wrong session!';
	echo json_encode($a);
	die();
}


// session check
if ( isset($_GET) && isset($_GET['s']) && strlen($_GET['s'])<1 ) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	$a['error'] = 'Wrong session! ...';
	echo json_encode($a);
	die();
}

// side check 
if ( isset($_GET) && ( !isset($_GET['side']) || !in_array($_GET['side'], $sides) ) ) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	$a['error'] = 'Wrong side! ...' . @$_GET['side'];
	echo json_encode($a);
	die();
}



if (null !==($_POST) && sizeof($_POST) > 0) {
	

	// Konrad version - simple background removal, only white, #FFFFFF

	if ( $bg_v == 1 ) {
		ob_start();

		//$color = '255,255,255,1'; // RGB + alpha, #FFFFFF .. e.g. #DDDDDD = 221,221,221
		//$colors = explode(',', $color);

		$color_from = 255;
		$color_to = 255; // does not work with range...
		$colors_range = array();

		for ($i=$color_from; $i>=$color_to; $i--) {
			$colors_range[] = $i; // color
		}

		$img = imagecreatefromstring( base64_decode( explode('base64,', $_POST['value'])[1] ) );
		

		for ($i=0; $i<=($color_from - $color_to); $i++) {
			$remove = imagecolorallocatealpha($img, $colors_range[$i], $colors_range[$i], $colors_range[$i], 1 );
			imagecolortransparent($img, $remove);
		}
		
		imagepng($img);

		$data = ob_get_clean();
		echo 'data:image/png;base64,'.base64_encode($data);

		imagedestroy($img);
		// end of simple version

	} else {

		// version with API - withoutbg.com
		
		$kimg = explode('base64,', $_POST['value'])[1];
			
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.withoutbg.com/v1.0/image-without-background-base64');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
			'X-API-Key: '.$wbg_api,
		]);

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"image_base64\": \"$kimg\"\n}");

		$response = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$obj = json_decode($response);

		// if no error
		if ($code == 200) {
			
			echo 'data:image/png;base64,'.$obj->{'img_without_background_base64'};
			$data = base64_decode($obj->{'img_without_background_base64'});
		} else {
			// error so
			$error = 1;

			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			$a['error'] = $obj->detail;
			$a['error_code'] = $code;

			// log API error
			if ($do_log) {
				$ip = @$_SERVER['REMOTE_ADDR'];
				$addr = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
				
				$log = fopen($path_main .'/without_bg_errors.txt', 'a');
				flock($log, LOCK_EX);
				fputs($log, date('Y-m-d, H:i:s').', '.$ip.', '.$addr.', '.($error ? 'FAILED' : 'OK').', '.$n. ", ".$code.','.$obj->detail.','.@$_SERVER['HTTP_REFERER']." \r\n"  );
				fclose($log);
			}


			echo json_encode($a);
			die();
		}

		// end of API version

	}



	// save image without bg

		// check if really an image
		$imageInfo = @getimagesizefromstring($data);
		if ($imageInfo === false) {
			// Not an image
			$error = 'upload only images!';
			header($_SERVER['SERVER_PROTOCOL'] . ' 403 FORBIDDEN', true, 403);
			$a['error'] = 'upload only images! ';
			echo json_encode($a);
			die();
		
		} else {
			// Check if the image type is supported
			if (!in_array($imageInfo[2], $allowedTypes)) {
				$error = 'not supported image!';
				header($_SERVER['SERVER_PROTOCOL'] . ' 403 FORBIDDEN', true, 403);
				$a['error'] = 'not supported image type! ';
				echo json_encode($a);
				die();
			}
		}

		// create preview folder - not used, images saved in /front/ filename-WITHOUT_BG.png 
		/*
		if (!is_dir($path_without_bg)) {
			if (!mkdir($path_without_bg)) {
				header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
				$a['error'] = 'Error creating preview folder! ';
				echo json_encode($a);
				die();
			}
			else
				if ($d) $a['debug'] .= "| folder $path_without_bg created. ";
		} else {
			if ($d) $a['debug'] .= "| folder $path_without_bg exists. ";
		}

		$n = $path_without_bg . uniqid() . '.png'; // temp filename

		*/


		// get side 
		$side = (string)rawurldecode($_GET['side']);

		// get filename 
		$filename = (string)rawurldecode($_GET['filename']);

		$n = $path . $side . '/' . $filename .'-WITHOUT_BG.png';


		$a['filename'] = $n;

		if (!$error) {
			
			file_put_contents( $n, $data);
		}

		

		// log file
		if ($do_log) {
			$ip = @$_SERVER['REMOTE_ADDR'];
			$addr = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
			
			$log = fopen($path_main .'/without_bg.txt', 'a');
			flock($log, LOCK_EX);
			fputs($log, date('Y-m-d, H:i:s').', '.$ip.', '.$addr.', '.($error ? 'FAILED' : 'OK').', '.$n. ", ".@$_SERVER['HTTP_REFERER']." \r\n"  );
			fclose($log);
		}


	
} else {
	
	header('Content-type: application/json; charset=utf-8');
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 FORBIDDEN', true, 403);
	$a['error'] = 'No POST! '; //.@$_POST['ktoken'];
	echo json_encode($a);
	die();

}


//ob_end_flush();

