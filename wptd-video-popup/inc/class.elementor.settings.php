<?php
/**
 * WPTD Video Popup Main Class
 * The main class that initiates and runs the plugin. 
 * @since 1.0.0
 */
final class WPTD_Elementor_Addon {
	
	/**
	 * Plugin Version
	 * @since 1.0.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '5.0';

	/**
	 * Instance
	 * @since 1.0.0
	 * @access private
	 * @static
	 * @var Addon The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Constructor
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		
		$this->init();

	}
	
	public function init() {

		// Elementor widget is optional — load only when Elementor is available.
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		require_once WPTD_EVP_DIR . 'inc/class.video-popup.php';
	}

	/**
	 * Admin notice
	 * Warning when the site doesn't have a minimum required Elementor version.
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'wptd-video-popup' ),
			'<strong>' . esc_html__( 'WPTD Video Popup', 'wptd-video-popup' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'wptd-video-popup' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}
	
	/**
	 * Creates and returns an instance of the class
	 * @since 2.6.8
	 * @access public
	 * return object
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}	
	
} 
WPTD_Elementor_Addon::instance();