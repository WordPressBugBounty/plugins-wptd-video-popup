<?php 
/*
Plugin Name: Video Popup for Elementor
Description: Create beautiful video lightbox popups with Gutenberg, shortcode, or Elementor. Supports YouTube and Vimeo videos using lightweight Magnific Popup.
Author: Zelvigo
Author URI: https://zelvigo.com
Plugin URI: https://wordpress.org/plugins/wptd-video-popup/
Version: 1.8.1
Requires at least: 6.0
Requires PHP: 7.4
Tested up to: 7.1
Text Domain: wptd-video-popup
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'WPTD_EVP_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPTD_EVP_URL', plugin_dir_url( __FILE__ ) );
define( 'WPTD_EVP_VERSION', '1.8.1' );

/*
* Intialize and Sets up the plugin
*/
class WPTD_Elementor_Video_Popup {
	
	private static $_instance = null;
	
	public static $version = '1.8.1';
	
	/**
	* Sets up needed actions/filters for the plug-in to initialize.
	* @since 1.0.0
	* @access public
	* @return void
	*/
	public function __construct() {

		//WPTD video popup setup page
		add_action( 'plugins_loaded', array( $this, 'wptd_elementor_video_popup_setup') );
		
		// Load shortcode, block, and optional Elementor widget
		add_action( 'plugins_loaded', array( $this, 'wptd_elementor_video_init_addons' ), 20 );
		
	}
	
	/**
	* Installs translation text domain
	* @since 1.0.0
	* @access public
	* @return void
	*/
	public function wptd_elementor_video_popup_setup() {
		//Load text domain
		$this->wptd_elementor_video_load_domain();
	}
	
	/**
	 * Load plugin translated strings using text domain
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function wptd_elementor_video_load_domain() {
		load_plugin_textdomain( 'wptd-video-popup', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
	}
		
	/**
	* Load required file for addons integration
	* @return void
	*/
	public function wptd_elementor_video_init_addons() {
		// Settings
		require_once WPTD_EVP_DIR . 'admin/wptd-settings.php';

		// Shared renderer, shortcode, and Gutenberg block (no Elementor required)
		require_once WPTD_EVP_DIR . 'inc/class.renderer.php';
		require_once WPTD_EVP_DIR . 'widgets/video-popup-wp-shortcodes.php';
		require_once WPTD_EVP_DIR . 'inc/class.gutenberg.php';

		// Elementor widget (only when Elementor is active)
		require_once WPTD_EVP_DIR . 'inc/class.elementor.settings.php';
	}
	
	/**
	 * Creates and returns an instance of the class
	 * @since 2.6.8
	 * @access public
	 * return object
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

}
WPTD_Elementor_Video_Popup::get_instance();