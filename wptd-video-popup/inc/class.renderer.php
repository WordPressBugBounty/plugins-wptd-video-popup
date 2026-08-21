<?php
/**
 * Shared video popup HTML renderer for shortcode and Gutenberg block.
 *
 * @package WPTD_Video_Popup
 * @since 1.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class WPTD_Video_Popup_Renderer {

	/**
	 * Incrementing ID for unique popup instance classes.
	 *
	 * @return int
	 */
	public static function get_random_id() {
		static $random = 1;
		return $random++;
	}

	/**
	 * Register frontend assets (called on wp_enqueue_scripts).
	 *
	 * @return void
	 */
	public static function register_assets() {
		$version = defined( 'WPTD_EVP_VERSION' ) ? WPTD_EVP_VERSION : '1.8.0';
		wp_register_style( 'magnific-popup', WPTD_EVP_URL . 'assets/css/magnific-popup.min.css', array(), '1.1.0', 'all' );
		wp_register_style( 'wptd-video-popup', WPTD_EVP_URL . 'assets/css/wptd-video-popup.css', array(), $version, 'all' );
		wp_register_script( 'magnific-popup', WPTD_EVP_URL . 'assets/js/jquery.magnific.popup.min.js', array( 'jquery' ), '1.1.0', true );
		wp_register_script( 'wptd-video-popup', WPTD_EVP_URL . 'assets/js/wptd-video-popup.js', array( 'jquery' ), $version, true );
	}

	/**
	 * Default attribute values.
	 *
	 * @return array
	 */
	public static function get_defaults() {
		return array(
			'url'         => '',
			'width'       => '',
			'trigger'     => 'text',
			'text'        => esc_html__( 'Click', 'wptd-video-popup' ),
			'icon'        => '',
			'img'         => '',
			'bg_color'    => 'rgba(0,0,0,0.5)',
			'autoplay'    => 1,
			'extra_class' => '',
		);
	}

	/**
	 * Normalize attributes from shortcode or block.
	 *
	 * @param array $atts Raw attributes.
	 * @return array
	 */
	public static function parse_atts( $atts ) {
		$atts = shortcode_atts( self::get_defaults(), $atts, 'wptd_video_popup' );

		return array(
			'url'         => isset( $atts['url'] ) ? $atts['url'] : '',
			'width'       => isset( $atts['width'] ) ? $atts['width'] : '',
			'trigger'     => isset( $atts['trigger'] ) && $atts['trigger'] ? $atts['trigger'] : 'text',
			'text'        => isset( $atts['text'] ) ? $atts['text'] : esc_html__( 'Click', 'wptd-video-popup' ),
			'icon'        => isset( $atts['icon'] ) ? $atts['icon'] : '',
			'img'         => isset( $atts['img'] ) ? $atts['img'] : '',
			'bg_color'    => isset( $atts['bg_color'] ) ? $atts['bg_color'] : 'rgba(0,0,0,0.5)',
			'autoplay'    => self::is_autoplay_enabled( isset( $atts['autoplay'] ) ? $atts['autoplay'] : 1 ) ? 1 : 0,
			'extra_class' => isset( $atts['extra_class'] ) ? $atts['extra_class'] : '',
		);
	}

	/**
	 * Build overlay background color with opacity.
	 *
	 * @param string $color   Hex or rgba color value.
	 * @param int    $opacity Opacity percentage (0-100).
	 * @return string
	 */
	public static function build_overlay_color( $color, $opacity = 50 ) {
		$opacity = max( 0, min( 100, (int) $opacity ) ) / 100;

		if ( empty( $color ) ) {
			return 'rgba(0,0,0,' . $opacity . ')';
		}

		if ( preg_match( '/rgba?\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/', $color, $matches ) ) {
			return 'rgba(' . $matches[1] . ',' . $matches[2] . ',' . $matches[3] . ',' . $opacity . ')';
		}

		$hex = ltrim( $color, '#' );

		if ( strlen( $hex ) === 3 ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}

		if ( strlen( $hex ) !== 6 || ! ctype_xdigit( $hex ) ) {
			return 'rgba(0,0,0,' . $opacity . ')';
		}

		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		return 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $opacity . ')';
	}

	/**
	 * Check whether autoplay is enabled.
	 *
	 * @param mixed $value Autoplay value.
	 * @return bool
	 */
	public static function is_autoplay_enabled( $value ) {
		if ( is_bool( $value ) ) {
			return $value;
		}

		if ( in_array( $value, array( '0', 0, 'false', 'no', 'off' ), true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Map Gutenberg block attributes to renderer attributes.
	 *
	 * @param array $attributes Block attributes.
	 * @return array
	 */
	public static function block_attributes_to_atts( $attributes ) {
		return self::parse_atts(
			array(
				'url'         => isset( $attributes['url'] ) ? $attributes['url'] : '',
				'width'       => isset( $attributes['width'] ) && $attributes['width'] ? (string) $attributes['width'] : '',
				'trigger'     => isset( $attributes['trigger'] ) ? $attributes['trigger'] : 'text',
				'text'        => isset( $attributes['text'] ) ? $attributes['text'] : '',
				'icon'        => isset( $attributes['icon'] ) ? $attributes['icon'] : '',
				'img'         => isset( $attributes['img'] ) ? $attributes['img'] : '',
				'bg_color'    => self::build_overlay_color(
					isset( $attributes['bgColor'] ) ? $attributes['bgColor'] : '#000000',
					isset( $attributes['bgOpacity'] ) ? $attributes['bgOpacity'] : 50
				),
				'autoplay'    => ! isset( $attributes['autoplay'] ) || $attributes['autoplay'],
			)
		);
	}

	/**
	 * Normalize YouTube/Vimeo URLs for Magnific Popup compatibility.
	 *
	 * @param string $url Video URL.
	 * @return string
	 */
	public static function normalize_video_url( $url ) {
		if ( preg_match( '#youtu\.be/([^?&/]+)#', $url, $matches ) ) {
			return 'https://www.youtube.com/watch?v=' . $matches[1];
		}

		return $url;
	}

	/**
	 * Render popup trigger markup.
	 *
	 * @param array $atts Popup attributes.
	 * @return string
	 */
	public static function render( $atts ) {
		$atts = self::parse_atts( $atts );

		wp_enqueue_style( 'magnific-popup' );
		wp_enqueue_style( 'wptd-video-popup' );
		wp_enqueue_script( 'magnific-popup' );
		wp_enqueue_script( 'wptd-video-popup' );

		$output        = '';
		$shortcode_css = '';
		$rand_class    = 'wptd-rand-' . self::get_random_id();
		$body_class    = 'body-' . $rand_class;

		if ( ! empty( $atts['width'] ) ) {
			$shortcode_css .= '.' . esc_attr( $body_class ) . ' .mfp-iframe-holder .mfp-content { max-width: ' . esc_attr( $atts['width'] ) . 'px; }';
		}

		if ( ! empty( $atts['bg_color'] ) ) {
			$shortcode_css .= '.' . esc_attr( $body_class ) . ' .mfp-bg { background-color: ' . esc_attr( $atts['bg_color'] ) . '; }';
		}

		$extra_class   = ! empty( $atts['extra_class'] ) ? ' ' . $atts['extra_class'] : '';
		$trigger_attrs = ' data-id="' . esc_attr( $body_class ) . '" data-autoplay="' . ( ! empty( $atts['autoplay'] ) ? '1' : '0' ) . '"';

		if ( $shortcode_css ) {
			$output .= '<span class="wptd-inline-css" data-css="' . htmlspecialchars( json_encode( $shortcode_css ), ENT_QUOTES, 'UTF-8' ) . '" data-width="900"></span>';
		}

		$url     = self::normalize_video_url( $atts['url'] );
		$trigger = $atts['trigger'];

		if ( empty( $url ) ) {
			return $output;
		}

		switch ( $trigger ) {
			case 'text':
				if ( ! empty( $atts['text'] ) ) {
					$output .= '<a href="' . esc_url( $url ) . '" class="wptd-popup-video wptd-popup-type-text' . esc_attr( $extra_class ) . '"' . $trigger_attrs . '>' . esc_html( $atts['text'] ) . '</a>';
				} else {
					$output .= esc_html__( 'You have to enter text on text="" parameter', 'wptd-video-popup' );
				}
				break;

			case 'icon':
				if ( ! empty( $atts['icon'] ) ) {
					$output .= '<a href="' . esc_url( $url ) . '" class="wptd-popup-video wptd-popup-type-icon' . esc_attr( $extra_class ) . '"' . $trigger_attrs . '><span class="' . esc_attr( $atts['icon'] ) . '"></span></a>';
				} else {
					$output .= esc_html__( 'You have to enter icon class on icon="" parameter', 'wptd-video-popup' );
				}
				break;

			case 'img':
				if ( ! empty( $atts['img'] ) ) {
					$output .= '<a href="' . esc_url( $url ) . '" class="wptd-popup-video wptd-popup-type-img' . esc_attr( $extra_class ) . '"' . $trigger_attrs . '><img src="' . esc_url( $atts['img'] ) . '" alt="' . esc_attr__( 'Popup', 'wptd-video-popup' ) . '"></a>';
				} else {
					$output .= esc_html__( 'You have to enter image path on img="" parameter', 'wptd-video-popup' );
				}
				break;
		}

		return $output;
	}

	/**
	 * Gutenberg block render callback.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render_block( $attributes ) {
		return self::render( self::block_attributes_to_atts( $attributes ) );
	}
}
