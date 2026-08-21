<?php
/**
 * WPTD Video Popup shortcode.
 *
 * @package WPTD_Video_Popup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPTD_Shortcodes {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wptd_scripts' ) );
		add_shortcode( 'wptd_video_popup', array( $this, 'wptd_video_popup_fun' ) );
	}

	/**
	 * Shortcode callback.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function wptd_video_popup_fun( $atts ) {
		return WPTD_Video_Popup_Renderer::render( $atts );
	}

	/**
	 * Register frontend assets.
	 *
	 * @return void
	 */
	public function wptd_scripts() {
		WPTD_Video_Popup_Renderer::register_assets();
	}
}

new WPTD_Shortcodes();
