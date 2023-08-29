<?php
/*
Author: Konrad G
Author URI: www.kgretk.com
File: Artwork files upload
Changelog:
2023-02-18 start
2023-03-23 canvas preview saving
2023-04-20 added conversion via ImageMagick

*/

// define some things

$path_main = '../../uploads/artwork/'; //with / at the end - ERROR in Imagemagick?

$max_res = 10000; // max pixels
$max_size_mb = 12; // max file size, MB
$secret = 'seCRet kkxx';
// allowed types: GIF, JPG, PNG - checked by getimagesize , line 185
// allowed but converted: WEBP, SVG, PDF, PSD, TIFF, EPS
$allowed_ext = ['gif', 'jpg', 'png', 'webp', 'jpeg'];
$allowed_ext_convert = [ 'svg', 'pdf', 'psd', 'tiff', 'eps', 'ai'];

// for canvas uploads
$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF); // HEIC not accepted

$d = 0; //temp debug
$do_log = 1; // log upload info to text file



// ============= don't change below
$s = '';
$a = array();
$error = false;
$n = '-';
$sides = array('front', 'back', 'left', 'right', 'neck'); // also folder names
$side = '';


if ($d) $a['debug'] = '| DEBUG |';



header('Content-type: application/json; charset=utf-8');



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

// get session 
$s = (string) trim(rawurldecode($_GET['s']));


// calculate token
$ip = isset($_SERVER["HTTP_CF_CONNECTING_IP"])? $_SERVER["HTTP_CF_CONNECTING_IP"]: $_SERVER["REMOTE_ADDR"];
$ktoken = md5( $s . $secret . $ip ); // TODO add user agent?
//$ktoken = md5( $secret  ); // temp
//$ktoken = md5( $s . $secret ); // less safe, without user IP



//security - check token
//if ( !isset($_POST['ktoken']) ) {
if ( !isset($_GET['ktoken']) ) { // temp
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 FORBIDDEN', true, 403);
	$a['error'] = 'No token! '; //.@$_POST['ktoken'];
	echo json_encode($a);
	die();
}

//if ( isset($_POST['ktoken']) && $_POST['ktoken'] != $ktoken ) {
if ( isset($_GET['ktoken']) && $_GET['ktoken'] !== $ktoken ) {  // temp
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 FORBIDDEN', true, 403);
	$a['error'] = 'Wrong token! '; //.@$_POST['ktoken'];
	echo json_encode($a);
	die();
}

// c>0 then upload from canvas
$from_c = 0;
if (isset($_GET['c']))
	$from_c = (string)$_GET['c']; 

if ($d) $a['debug'] .= "| from_c=$from_c ";


// get side 
$side = (string)rawurldecode($_GET['side']);

// path to save artwork
$path  = $path_main . $s .'/';

//create session folder
if (!is_dir($path)) {
	if (!mkdir($path)) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		$a['error'] = 'Error creating folder! ';
		echo json_encode($a);
		die();
	}
	else
		if ($d) $a['debug'] .= "| folder $path created. ";
} else {
	if ($d) $a['debug'] .= "| folder $path exists. ";
}


// side folder
$path_side = $path . $side . '/';

if (!is_dir($path_side)) {
	if (!mkdir($path_side)) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		$a['error'] = 'Error creating folder! ';
		echo json_encode($a);
		die();
	}
	else
		if ($d) $a['debug'] .= "| folder $path_side created. ";
} else {
	if ($d) $a['debug'] .= "| folder $path_side exists. ";
}





// upload the file(s)

if (isset($_FILES['f1']) ) {
	$file = $_FILES['f1']['tmp_name'];
	if ($from_c) {
		$n = 'canvas.png'; // not needed...
	}
	else {
		$n = $_FILES['f1']['name'];
	}
	
	$error = false;
	$size = array(); 

	// remove bad chars from file name
	for ($i=0;$i<strlen($n);$i++) {
		if (ord($n[$i])>126) $n[$i] = '-';
	}

  
   //$ext = strtolower(substr($n, strlen($n)-3, strlen($n))); // -3 for png, -4 for .png (with .)

   $n_info = pathinfo($n);
   $name =  $n_info['filename'];
   $ext  =  strtolower($n_info['extension']);

   //if ($ext == '.jpg') $n = uniqid().$ext; else $n = uniqid().'.'.$ext; // image name to unique
   //$nn = $n;

   	$n = $path_side .$n; // or $path
   	
 
	if (!is_uploaded_file($file) || ($_FILES['f1']['size'] > $max_size_mb * 1024 * 1024 ) )	{
		$error = 'Upload max file size exceeded!';
		$size[0] = 1; $size[1] = 1;
	}

	// check if image or if allowed extension
	/*
	if (!$error && !(in_array($ext, $allowed_ext)) ) {
		$error = 'Upload only allowed file types!';
		$size[0] = 1; $size[1] = 2;

	} else
	*/
	
	if ( !$error && !(in_array($ext, $allowed_ext)) && !(in_array($ext, $allowed_ext_convert))  )
	{
		$error = 'Upload only images or allowed file types!';
		$size[0] = 1; $size[1] = 1;

	} 
	/* TODO other sec check?
	else {
		if ( !$error && !($size = @getimagesize($file)) ) {
			$error = 'Upload only images!';
			$size[0] = 1; $size[1] = 1;

		}
	}
	*/

	
	/* no resolution check .. 
	if (!$error && ( ($size[0] < 50) || ($size[1] < 50) || ($size[0] > $max_res) || ($size[1] > $max_res) )) {
		$error = 'Upload max pixels!';
	}
	*/

	$size[0] = 1; $size[1] = 1;
	if ( in_array($ext, $allowed_ext))  {
		$size = @getimagesize($file);
		if ( is_bool( $size ) ) {
			$size[0] = 2; $size[1] = 2;
		}
	}

	if ($d) $a['debug'] .= '|' . $error;
	

	// log file
	if ($do_log) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$addr = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
		$log = fopen($path_main .'/uploads.txt', 'a');
		flock($log, LOCK_EX);
		fputs($log, date('Y-m-d, H:i:s').', '.$ip.', '.$addr.', '.($error ? 'FAILED:'.$error : 'OK').', '.$n. ",{$_FILES['f1']['size']} bytes, $size[0]x$size[1], ".$_SERVER['HTTP_REFERER'].',ext='.$ext." \r\n"  );
		fclose($log);
		// .$size[0] .' '.$size[1] .' '.$size[2] .' '.$size[3] .' '.$size[5]
	}

	//move temp file
	if (!$error) {
		if (! @copy( $file, $n))
		{
			if ( ! @move_uploaded_file( $file , $n))
			{
				if ($d) $a['debug'] .= ' | error moving file' .$file.' -> '.$n ; //.' session:'.session_id(); //.', '.$_GET['path'];
			}
		}
		else
			unlink($file);
	}

	$a['converted'] = '';


	// convert uploaded file
	if ( in_array($ext, $allowed_ext_convert) ) {

		if ($d) $a['debug'] .= ' | CONVERTING... name='.$name.'| ext=.'.$ext.'|';


		// src = $n , make path ... dst: $path_side / $filename . new extension ...

		if (!is_uploaded_file($file) || ($_FILES['f1']['size'] > $max_size_mb * 1024 * 1024 ) )	{
			$error = 'Upload max file size exceeded !!';
			$size[0] = 1; $size[1] = 1;
		}

		switch ($ext) {
			case 'pdf': 
				try {

					$image = new Imagick();
					$image->readImage( $n.'[0]' ); // pdf first page
					$image->setResolution( 500, 500 );
					$image->setImageFormat( "png" );
					
					$image->writeImage($path_side . $name . '.png');
					
				}
				catch(Exception $e) {
					if ($d) $a['debug'] .= ' ERROR: '.$e->getMessage();
					$a['error'] = ' ERROR in conversion: '.$e->getMessage();
					header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
					echo json_encode($a);
					die;
				}

				break;
			case 'svg':
			case 'eps':
			case 'ai':
			case 'tiff':
				try {

					$image = new Imagick();
					$image->readImage( $n );
					$image->setResolution( 400, 400 );
					$image->setImageFormat( "png" );
					
					$image->writeImage($path_side . $name . '.png');
					
				}
				catch(Exception $e) {
					if ($d) $a['debug'] .= ' ERROR: '.$e->getMessage();
					$a['error'] = ' ERROR in conversion: '.$e->getMessage();
					header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
					echo json_encode($a);
					die;
				}

				break;
			case 'psd':
				
				try {
					//$convertString = ' convert "'.$n.'" -background white -flatten -resample "'. $path_side . 'new-xxxx.png" ';
					//exec($convertString); // . " > /dev/null &" );
					//if ($d) $a['debug'] .= $convertString;

					$image = new Imagick(); //if ($d) $a['debug'] .= $n;
					$image->readImage( $n );
					$image->setResolution( 300, 300 );
					$image->setImageFormat( "png" );
					
					$image->writeImage($path_side . $name . '.png');
					
				}
				catch(Exception $e) {
					if ($d) $a['debug'] .= ' ERROR: '.$e->getMessage();
					$a['error'] = ' ERROR in conversion: '.$e->getMessage();
					header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
					echo json_encode($a);
					die();
				}

				break;

		} // end switch

		//$name = 'ee'; // TEMP
		$a['converted'] = 'data:image/png;base64,'.base64_encode(file_get_contents($path_side . $name . '.png'));
		
		// log file
		if ($do_log) {
			$ip = $_SERVER['REMOTE_ADDR'];
			$addr = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
			
			$log = fopen($path_main .'/converted.txt', 'a');
			flock($log, LOCK_EX);
			fputs($log, date('Y-m-d, H:i:s').', '.$ip.', '.$addr.', '.($error ? 'FAILED:'.$error : 'OK').', '.$n. ",{$_FILES['f1']['size']} bytes, $size[0]x$size[1], ".$_SERVER['HTTP_REFERER'].',ext='.$ext." \r\n"  );
			fclose($log);
			// .$size[0] .' '.$size[1] .' '.$size[2] .' '.$size[3] .' '.$size[5]
		}


	} // end convert



	$a['filename'] = $n;

} // end upload





// canvas preview folder
$path_preview = $path . 'preview' . '/';


// canvas upload
if ($from_c > 0) {

	$data = base64_decode(file_get_contents('php://input')); //$_POST['data']);

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

	// create preview folder
	if (!is_dir($path_preview)) {
		if (!mkdir($path_preview)) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			$a['error'] = 'Error creating preview folder! ';
			echo json_encode($a);
			die();
		}
		else
			if ($d) $a['debug'] .= "| folder $path_preview created. ";
	} else {
		if ($d) $a['debug'] .= "| folder $path_preview exists. ";
	}
	

	$n = $path_preview . 'canvas-' . $side . '.png';
	$a['filename'] = 'canvas-' . $side . '.png';

	if (!$error) {
		
		file_put_contents( $n, $data);
	}

	

	// log file
	if ($do_log) {
		$ip = @$_SERVER['REMOTE_ADDR'];
		$addr = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
		$log = fopen($path_main .'/uploads_canvas.txt', 'a');
		flock($log, LOCK_EX);
		fputs($log, date('Y-m-d, H:i:s').', '.$ip.', '.$addr.', '.($error ? 'FAILED:'.$error : 'OK').', '.$n. ", ".@$_SERVER['HTTP_REFERER']." \r\n"  );
		fclose($log);
	}

} // end canvas upload




if (!$error) {
	$a['result'] = 'OK';
	//$a['filename'] = $n;
}
	
else
	$a['error'] = $error;

//$a['path'] = $path;




echo json_encode($a);
