<?php
/**
 * Gutenberg block registration for WPTD Video Popup.
 *
 * @package WPTD_Video_Popup
 * @since 1.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class WPTD_Video_Popup_Gutenberg {

	/**
	 * Instance.
	 *
	 * @var WPTD_Video_Popup_Gutenberg|null
	 */
	private static $_instance = null;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ), 10 );
		add_filter( 'block_categories_all', array( $this, 'register_block_category' ), 10, 2 );
	}

	/**
	 * Block attribute schema.
	 *
	 * @return array
	 */
	private function get_block_attributes() {
		return array(
			'url'        => array(
				'type'    => 'string',
				'default' => '',
			),
			'width'      => array(
				'type'    => 'number',
				'default' => 0,
			),
			'trigger'    => array(
				'type'    => 'string',
				'default' => 'text',
			),
			'text'       => array(
				'type'    => 'string',
				'default' => 'Click',
			),
			'icon'       => array(
				'type'    => 'string',
				'default' => '',
			),
			'img'        => array(
				'type'    => 'string',
				'default' => '',
			),
			'imgId'      => array(
				'type'    => 'number',
				'default' => 0,
			),
			'bgColor'    => array(
				'type'    => 'string',
				'default' => '#000000',
			),
			'bgOpacity'  => array(
				'type'    => 'number',
				'default' => 50,
			),
			'autoplay'   => array(
				'type'    => 'boolean',
				'default' => true,
			),
		);
	}

	/**
	 * Register editor assets and the video popup block.
	 *
	 * @return void
	 */
	public function register_block() {
		$asset_version = defined( 'WPTD_EVP_VERSION' ) ? WPTD_EVP_VERSION : '1.8.0';

		WPTD_Video_Popup_Renderer::register_assets();

		wp_register_script(
			'wptd-video-popup-block-editor',
			WPTD_EVP_URL . 'blocks/video-popup/editor.js',
			array(
				'wp-blocks',
				'wp-element',
				'wp-block-editor',
				'wp-components',
				'wp-i18n',
			),
			$asset_version,
			true
		);

		wp_set_script_translations( 'wptd-video-popup-block-editor', 'wptd-video-popup', WPTD_EVP_DIR . 'languages' );

		wp_register_style(
			'wptd-video-popup-block-editor',
			WPTD_EVP_URL . 'blocks/video-popup/editor.css',
			array( 'wp-edit-blocks' ),
			$asset_version
		);

		$block_args = array(
			'api_version'     => 3,
			'title'           => esc_html__( 'Video Popup', 'wptd-video-popup' ),
			'description'     => esc_html__( 'Create a video lightbox popup for YouTube or Vimeo.', 'wptd-video-popup' ),
			'category'        => 'wptd',
			'icon'            => 'video-alt3',
			'keywords'        => array( 'video', 'popup', 'lightbox', 'youtube', 'vimeo' ),
			'attributes'      => $this->get_block_attributes(),
			'supports'        => array(
				'html'  => false,
				'align' => array( 'wide', 'full' ),
			),
			'editor_script'   => 'wptd-video-popup-block-editor',
			'editor_style'    => 'wptd-video-popup-block-editor',
			'view_script_handles' => array( 'magnific-popup', 'wptd-video-popup' ),
			'view_style_handles'  => array( 'magnific-popup', 'wptd-video-popup' ),
			'render_callback' => array( 'WPTD_Video_Popup_Renderer', 'render_block' ),
		);

		$block_json = WPTD_EVP_DIR . 'blocks/video-popup/build/block.json';

		if ( file_exists( $block_json ) ) {
			register_block_type(
				$block_json,
				array(
					'render_callback' => array( 'WPTD_Video_Popup_Renderer', 'render_block' ),
				)
			);
			return;
		}

		register_block_type( 'wptd/video-popup', $block_args );
	}

	/**
	 * Register custom block category.
	 *
	 * @param array                    $categories Existing categories.
	 * @param \WP_Block_Editor_Context $context    Editor context.
	 * @return array
	 */
	public function register_block_category( $categories, $context ) {
		return array_merge(
			array(
				array(
					'slug'  => 'wptd',
					'title' => esc_html__( 'Zelvigo', 'wptd-video-popup' ),
					'icon'  => null,
				),
			),
			$categories
		);
	}

	/**
	 * Get singleton instance.
	 *
	 * @return WPTD_Video_Popup_Gutenberg
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

WPTD_Video_Popup_Gutenberg::instance();
