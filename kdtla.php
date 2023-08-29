<?php
/*
Plugin Name: KDTLA
Plugin URI: kdtla
Description: Customized WooCommerce for DTLAPrint (multiple colors, quantities, print options, pricing etc.).
Version: 1.27
Author: Konrad G
Author URI: www.kgretk.com
Copyright: dtlaprint.com, 2023
*/

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
error_reporting(E_ALL);

// secret for file uploads
$secret = 'seCRet kkxx';

// for WP cron, to move old artwork files. Run interval defined in line 165, daily
$artwork_folder = plugin_dir_path( __FILE__ ) . '/../../uploads/artwork/'; //with / at the end
$artwork_folder_old = plugin_dir_path( __FILE__ ) . '/../../uploads/artwork-old/'; //with / at the end
$older_than = '-1 month'; // with minus - files older than 1 month
// for WP-CLI defined in lines 347, 348 - '/opt/bitnami/wordpress/wp-content/uploads/artwork/'


define( 'KDTLA_PATH', plugin_dir_path( __FILE__ ) );

require_once( 'k_custom/k_custom_fields_def.php' );
require_once( 'k_custom/k_custom_fields_wc1.php' ); //wc1 - all functions

require_once( 'k_custom/k_wc_backend.php' ); //TODO only for backend?
require_once( 'k_functions.php' );


if ( ! class_exists( 'kdtla' ) ) {
	class kdtla
	{
		/**
		 * Tag identifier used by file includes and selector attributes.
		 * @var string
		 */
		public $tag = 'kdtla';

		/**
		 * User friendly name used to identify the plugin.
		 * @var string
		 */
		public $name = 'kdtla';

		/**
		 * Current version of the plugin.
		 * @var string
		 */
		public $version = '1.27';

		/**
		 * List of options to determine plugin behaviour.
		 * @var array
		 */
		protected $options = array();
		
		public function settings() {

			return true;
		}
		
		

		/**
		 * Initiate the plugin by setting the default values and assigning any
		 * required actions and filters.
		 *
		 * @access public
		 */
		public function __construct() {
			if ( $options = get_option( $this->tag ) ) {
				$this->options = $options;
			}

			//first shortcode: kdtla - not needed
			//add_shortcode( $this->tag, array( &$this, 'shortcode' ) ); // [kdtla]

			if ( is_admin() ) {
				add_action( 'admin_init', array( &$this, 'settings' ) );
			}
            	
            add_action( 'admin_menu', array( __CLASS__, 'setup_admin_page') );
			add_action( 'admin_menu', array( __CLASS__, 'setup_kcart_page') );

			// define cron hook
			add_action( 'kdtla_artwork_cron_hook', 'kdtla_artwork_cron_exec' );

		}
		

		/**
		 * Add the setting page to the Dashboard.
		 *
		 * @access public
		 */
		
		public static function setup_admin_page() {

			if (isset($_POST['kdtla_save'])) {

				$options = get_option('kdtla');

				$options['CustomCss'] = stripslashes($_POST['CustomCss']);
				$options['t1'] = stripslashes($_POST['t1']);
				if (isset($_POST['artwork_cron']))
					$options['artwork_cron'] = stripslashes($_POST['artwork_cron']);
					else
				$options['artwork_cron'] = 0;

				update_option('kdtla', $options);
				$_POST['message'] = "Settings Updated";
			}

			add_menu_page(
				'kdtla plugin', // page tite
				'KDTLA', // menu title
				'update_plugins', // capability
				'kdtla', // menu slug
				array('kdtla', 'render_admin_page') // function
				//plugins_url('/images/admin-page-icon.gif', __FILE__ ) // icon url
				
			);

		}

		/**
		 * Add the kcart page to the Dashboard.
		 *
		 * @access public
		 */

		public static function setup_kcart_page() {

			add_submenu_page(
				'kdtla', // parent slug
				'kdtla-user cart', // page tite
				'user cart', // menu title
				'update_plugins', // capability
				'kcart', // menu slug
				array('kdtla', 'render_kcart_page') // function
				
			);
		}

		
		// admin page
    	public static function render_admin_page() {
			global $artwork_folder, $artwork_folder_old, $older_than;

        	$options = get_option('kdtla'); 

        	require_once( KDTLA_PATH . 'admin/kdtla_admin.php');


			// cron functionality - moving or removing old files in /artwork
			echo ' Cron status:  ';

			if ( 1 == $options['artwork_cron']) {

				if ( ! wp_next_scheduled( 'kdtla_artwork_cron_hook' ) ) {
					wp_schedule_event( time(), 'daily', 'kdtla_artwork_cron_hook' ); //, [], true
					echo ' scheduled.';
				} else
					echo ' scheduled already.';

				$timestamp = wp_next_scheduled( 'kdtla_artwork_cron_hook' );
				//date_default_timezone_set('America/Los_Angeles');
				echo '<br /> Next run: '. get_date_from_gmt( date('Y-m-d H:i:s', $timestamp) ) . '  (PT)';

				
			} else {
				// unschedule task
				$timestamp = wp_next_scheduled( 'kdtla_artwork_cron_hook' );
				wp_unschedule_event( $timestamp, 'kdtla_artwork_cron_hook' );

				echo ' - not scheduled.';
			}

			echo '<br />';


			// to test
			//kdtla_moveOldFolders($artwork_folder, $artwork_folder_old, $older_than);

    	}

		// kcart page
		public static function render_kcart_page() {
			
			require_once( KDTLA_PATH . 'admin/wc_user_cart.php');
	
		}
		
	    // Plugin - main function  - NOT USED IF NO SHORTCODE
		public function kdtla($pos = '') {
		    

		} //kdtla
		

	} // end class

	new kdtla;

} // end if class



// enqueue scripts, new way
function kdtla_enqueue_scripts($hook) {

	// Define the URL path
	$plugin_path = plugin_dir_url( __FILE__ );
				
	if ( class_exists('kdtla') ) {
		$kdtla = new kdtla();

		// if only for /product/ urls :
		//if ( !wp_style_is( $kdtla->tag, 'enqueued' ) && substr($_SERVER["REQUEST_URI"], 0, 9) == '/product/' ) {

			wp_enqueue_style(
				'kdtla',
				$plugin_path . 'kdtla.css',
				array(),
				$kdtla->version
			);
		//}


		// Enqueue the scripts - only for /product urls (both /product/ and /products/ should work, first 8 letters )
		if ( !wp_script_is( $kdtla->tag, 'enqueued' ) && substr($_SERVER["REQUEST_URI"], 0, 8) == '/product' ) {
			wp_enqueue_script( 'jquery' );

			// for artwork
			//	wp_enqueue_script( 'fabric', 'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/2.4.5/fabric.min.js', array('jquery'), '2.4.5', true);
			wp_enqueue_script( 'fabric', $plugin_path . '/fabric.min.js', array('jquery'), '2.4.5', true);

			wp_enqueue_script( 
				$kdtla->tag, 
				$plugin_path . 'kdtla.js', 
				array(), 
				$kdtla->version, 
				true 
			);

			// Make the options available to JavaScript... but options must be public
			/*
			$options = array_merge( array(
				'selector' => '.' . $kdtla->tag
			), $kdtla->options );
			wp_localize_script( $kdtla->tag, $kdtla->tag, $options );

			*/
			
			
			
		};
	}
}
function kdtla_enqueue_scripts_mini($hook) {
	$plugin_path = plugin_dir_url( __FILE__ );

	if ( class_exists('kdtla') ) {
		$kdtla = new kdtla();

		// enqueue small script for all pages
		wp_enqueue_script( 
			'kdtla-mini', 
			$plugin_path . 'kdtla-mini.js', 
			array(), 
			$kdtla->version, 
			true 
		);
	}
}

add_action('wp_enqueue_scripts', 'kdtla_enqueue_scripts'); // wp for front-end
add_action('wp_enqueue_scripts', 'kdtla_enqueue_scripts_mini'); // wp for front-end
add_action('admin_enqueue_scripts', 'kdtla_enqueue_scripts_mini'); // admin_ for front-end



//enqueue Code Mirror editor files
function codemirror_enqueue_scripts($hook) {
	$cm_settings['ce_html'] = wp_enqueue_code_editor(array('type' => 'text/html'));
	$cm_settings['ce_css'] = wp_enqueue_code_editor(array('type' => 'text/css'));
	wp_localize_script('jquery', 'cm_settings', $cm_settings);
	

	wp_enqueue_script('wp-theme-plugin-editor');
	wp_enqueue_style('wp-codemirror');
}

add_action('admin_enqueue_scripts', 'codemirror_enqueue_scripts'); // admin_ for admin




// SESSION FOR UPLOAD AND TOKEN

//session id from WC cookie
function k_get_session() {
	$values = array('');
	if (isset($_COOKIE) && sizeof($_COOKIE)>0)
		foreach( $_COOKIE as $key => $value ) {
			if( stripos( $key, 'wp_woocommerce_session_' ) === false ) {
				continue;
			}
			$values = explode( '||', $value );
		}

	return $values[0];
}


// token for uploads
function k_token( $s ) {
	global $secret;

	// calculate token
	$ip = isset($_SERVER["HTTP_CF_CONNECTING_IP"])? $_SERVER["HTTP_CF_CONNECTING_IP"]: $_SERVER["REMOTE_ADDR"];
	$ktoken = md5( $s . $secret . $ip );
	//$ktoken = md5( $secret  ); // temp
	//$ktoken = md5( $s . $secret ); // less safe, without user IP

	return $ktoken;
}

// cron - artwork deletion/moving
function kdtla_artwork_cron_exec() {
	global $artwork_folder, $artwork_folder_old, $older_than;

	kdtla_moveOldFolders($artwork_folder, $artwork_folder_old, $older_than);
	return true;
}


// cron - move old folders
function kdtla_moveOldFolders($dir1, $dir_old, $older_than) {

	// for WP-CLI
	if (strlen($dir1)==0) {
		$dir1 		= '/opt/bitnami/wordpress/wp-content/uploads/artwork/'; //with / at the end
		$dir_old 	= '/opt/bitnami/wordpress/wp-content/uploads/artwork-old/'; //with / at the end
	}
	if (strlen($older_than)==0) {
		$older_than = '-1 month';
	}

	// check if folder names contain "uploads"
	if ( strpos($dir1, 'uploads')>0 && strpos($dir_old, 'uploads')>0 ) {

		$timeThreshold = strtotime($older_than); // Get the timestamp for 1 day ago

		// Get a list of folders in the source directory
		$folders = glob($dir1 . '/*', GLOB_ONLYDIR);

		foreach ($folders as $folder) {
			$folderName = basename($folder);

			// Get the folder's last modified timestamp
			$modifiedTime = filemtime($folder);

			// Check if the folder is older than the time threshold
			if ($modifiedTime < $timeThreshold) {

				// START of part to move folders - MOVE block

				// Create the corresponding directory in the destination directory
				$destination = $dir_old . '/' . $folderName;
				if (!is_dir($destination)) {
					mkdir($destination);
				}

				// Move the folder to the destination directory
				$success = rename($folder, $destination);

				if ($success) {
					echo "Moved folder '$folderName' to '$destination' ".'<br />';
				} else {
					echo "Failed to move folder '$folderName' ".'<br />';
				}
				// END of part to move folders

				// OR DELETE folders
				//kdtla_deleteFolder($folder);
				//echo "Deleted folder '$folderName'.\n";

				// to delete folders: comment out MOVE block and uncomment the previous 2 lines
			}
		}
	} else 
		echo ' - ERROR: No uploads in folder names! $dir1='.$dir1;
}

function kdtla_deleteFolder($folder) {
    if (!is_dir($folder)) {
        return;
    }

    $files = array_diff(scandir($folder), array('.', '..'));

    foreach ($files as $file) {
        $path = $folder . '/' . $file;

        if (is_dir($path)) {
            kdtla_deleteFolder($path);
        } else {
            unlink($path);
        }
    }

    rmdir($folder);
}



