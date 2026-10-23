<?php
/**
 * WPTD Video Popup Elementor integration.
 *
 * @package WPTD_Video_Popup
 * @since 1.0.0
 */
final class Elementor_Video_Popup_Shortcode {

	/**
	 * Instance
	 *
	 * @var Elementor_Video_Popup_Shortcode|null
	 */
	private static $_instance = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Register Elementor hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'elementor/elements/categories_registered', [ $this, 'create_wptd_elementor_category' ] );
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
	}

	/**
	 * Register plugin widget category.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
	 * @return void
	 */
	public function create_wptd_elementor_category( $elements_manager ) {
		if ( ! $elements_manager ) {
			return;
		}

		$elements_manager->add_category(
			'wptd',
			array(
				'title' => esc_html__( 'WPTD', 'wptd-video-popup' ),
			),
			1
		);
	}

	/**
	 * Register widget scripts and styles.
	 *
	 * @return void
	 */
	public function widget_scripts() {
		$version = defined( 'WPTD_EVP_VERSION' ) ? WPTD_EVP_VERSION : '1.8.0';

		wp_register_style( 'magnific-popup', WPTD_EVP_URL . 'assets/css/magnific-popup.min.css', array(), '1.1.0', 'all' );
		wp_register_style( 'wptd-video-popup', WPTD_EVP_URL . 'assets/css/wptd-video-popup.css', array(), $version, 'all' );
		wp_register_script( 'magnific-popup', WPTD_EVP_URL . 'assets/js/jquery.magnific.popup.min.js', array( 'jquery' ), '1.1.0', true );
		wp_register_script( 'wptd-video-popup', WPTD_EVP_URL . 'assets/js/wptd-video-popup.js', array( 'jquery' ), $version, true );
	}

	/**
	 * Register Elementor widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	public function register_widgets( $widgets_manager ) {
		require_once WPTD_EVP_DIR . 'widgets/video-popup-elementor.php';

		if ( method_exists( $widgets_manager, 'register' ) ) {
			$widgets_manager->register( new \Elementor_Video_Popup_Widget() );
			return;
		}

		$widgets_manager->register_widget_type( new \Elementor_Video_Popup_Widget() );
	}

	/**
	 * @return int
	 */
	public static function get_random_series() {
		static $eptd_evp_rand = 1;
		return $eptd_evp_rand++;
	}

	/**
	 * @return Elementor_Video_Popup_Shortcode
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

Elementor_Video_Popup_Shortcode::instance();
