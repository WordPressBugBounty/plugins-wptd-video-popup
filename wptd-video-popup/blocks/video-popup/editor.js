/**
 * Video Popup block editor (no build step required).
 */
( function ( wp ) {
	if ( ! wp || ! wp.blocks || ! wp.element || ! wp.blockEditor || ! wp.components ) {
		return;
	}

	const { registerBlockType } = wp.blocks;
	const { createElement: el, Fragment } = wp.element;
	const { __ } = wp.i18n;
	const {
		InspectorControls,
		MediaUpload,
		MediaUploadCheck,
		useBlockProps,
		PanelColorSettings,
	} = wp.blockEditor;
	const {
		PanelBody,
		TextControl,
		SelectControl,
		Button,
		Placeholder,
		BaseControl,
		RangeControl,
		ToggleControl,
	} = wp.components;

	function getHexFromColor( color ) {
		if ( ! color ) {
			return '#000000';
		}

		if ( color.indexOf( '#' ) === 0 ) {
			return color;
		}

		const match = color.match(
			/rgba?\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/
		);

		if ( ! match ) {
			return '#000000';
		}

		return (
			'#' +
			[ match[1], match[2], match[3] ]
				.map( function ( value ) {
					const hex = parseInt( value, 10 ).toString( 16 );
					return hex.length === 1 ? '0' + hex : hex;
				} )
				.join( '' )
		);
	}

	function getOpacityFromColor( color, fallback ) {
		if ( typeof fallback !== 'number' ) {
			fallback = 50;
		}

		const match = color && color.match( /rgba\(\s*[\d.]+\s*,\s*[\d.]+\s*,\s*[\d.]+\s*,\s*([\d.]+)\s*\)/ );

		if ( ! match ) {
			return fallback;
		}

		return Math.round( parseFloat( match[1] ) * 100 );
	}

	const triggerOptions = [
		{ label: __( 'Text', 'wptd-video-popup' ), value: 'text' },
		{ label: __( 'Icon', 'wptd-video-popup' ), value: 'icon' },
		{ label: __( 'Image', 'wptd-video-popup' ), value: 'img' },
	];

	function renderEditorPreview( attributes ) {
		const { trigger, text, icon, img } = attributes;

		if ( trigger === 'icon' && icon ) {
			return el(
				'span',
				{ className: 'wptd-popup-video wptd-popup-type-icon' },
				el( 'span', { className: icon } )
			);
		}

		if ( trigger === 'img' && img ) {
			return el(
				'span',
				{ className: 'wptd-popup-video wptd-popup-type-img' },
				el( 'img', {
					src: img,
					alt: __( 'Popup', 'wptd-video-popup' ),
				} )
			);
		}

		return el(
			'span',
			{ className: 'wptd-popup-video wptd-popup-type-text' },
			text || __( 'Click', 'wptd-video-popup' )
		);
	}

	function Edit( props ) {
		const { attributes, setAttributes } = props;
		const {
			url,
			width,
			trigger,
			text,
			icon,
			img,
			imgId,
			bgColor,
			bgOpacity,
			autoplay,
		} = attributes;

		const overlayColor = getHexFromColor( bgColor );
		const overlayOpacity =
			typeof bgOpacity === 'number'
				? bgOpacity
				: getOpacityFromColor( bgColor, 50 );

		const blockProps = useBlockProps();

		return el(
			Fragment,
			null,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{
						title: __( 'Video Settings', 'wptd-video-popup' ),
						initialOpen: true,
					},
					el( TextControl, {
						label: __( 'Video URL', 'wptd-video-popup' ),
						help: __(
							'YouTube or Vimeo URL. Example: https://www.youtube.com/watch?v=LXb3EKWsInQ',
							'wptd-video-popup'
						),
						value: url,
						onChange: function ( value ) {
							setAttributes( { url: value } );
						},
					} ),
					ToggleControl
						? el( ToggleControl, {
								label: __(
									'Autoplay Video',
									'wptd-video-popup'
								),
								help: __(
									'Start playing the video when the popup opens.',
									'wptd-video-popup'
								),
								checked: autoplay !== false,
								onChange: function ( value ) {
									setAttributes( { autoplay: value } );
								},
						  } )
						: null,
					el( SelectControl, {
						label: __( 'Trigger Type', 'wptd-video-popup' ),
						value: trigger,
						options: triggerOptions,
						onChange: function ( value ) {
							setAttributes( { trigger: value } );
						},
					} ),
					trigger === 'text' &&
						el( TextControl, {
							label: __( 'Trigger Text', 'wptd-video-popup' ),
							value: text,
							onChange: function ( value ) {
								setAttributes( { text: value } );
							},
						} ),
					trigger === 'icon' &&
						el( TextControl, {
							label: __( 'Icon Class', 'wptd-video-popup' ),
							help: __(
								'CSS class for the icon, e.g. fa fa-play',
								'wptd-video-popup'
							),
							value: icon,
							onChange: function ( value ) {
								setAttributes( { icon: value } );
							},
						} ),
					trigger === 'img' &&
						el(
							MediaUploadCheck,
							null,
							el( MediaUpload, {
								onSelect: function ( media ) {
									setAttributes( {
										img: media.url,
										imgId: media.id,
									} );
								},
								allowedTypes: [ 'image' ],
								value: imgId,
								render: function ( renderProps ) {
									return el(
										'div',
										{ className: 'wptd-video-popup-media' },
										img &&
											el( 'img', {
												src: img,
												alt: __(
													'Popup trigger',
													'wptd-video-popup'
												),
											} ),
										el(
											Button,
											{
												variant: 'secondary',
												onClick: renderProps.open,
											},
											img
												? __(
														'Replace Image',
														'wptd-video-popup'
												  )
												: __(
														'Select Image',
														'wptd-video-popup'
												  )
										),
										img &&
											el(
												Button,
												{
													variant: 'link',
													isDestructive: true,
													onClick: function () {
														setAttributes( {
															img: '',
															imgId: 0,
														} );
													},
												},
												__(
													'Remove Image',
													'wptd-video-popup'
												)
											)
									);
								},
							} )
						)
				),
				el(
					PanelBody,
					{
						title: __( 'Popup Style', 'wptd-video-popup' ),
						initialOpen: false,
					},
					el( BaseControl, {
						label: __( 'Popup Width (px)', 'wptd-video-popup' ),
						id: 'wptd-video-popup-width',
					},
					el( 'input', {
						type: 'number',
						id: 'wptd-video-popup-width',
						className: 'components-text-control__input',
						value: width || '',
						min: 0,
						step: 1,
						onChange: function ( event ) {
							const nextWidth = event.target.value;
							setAttributes( {
								width: nextWidth ? parseInt( nextWidth, 10 ) : 0,
							} );
						},
					} )
					)
				),
				PanelColorSettings
					? el( PanelColorSettings, {
							title: __(
								'Overlay Background Color',
								'wptd-video-popup'
							),
							initialOpen: false,
							colorSettings: [
								{
									value: overlayColor,
									onChange: function ( value ) {
										setAttributes( {
											bgColor: value || '#000000',
										} );
									},
									label: __(
										'Overlay Color',
										'wptd-video-popup'
									),
								},
							],
					  } )
					: null,
				RangeControl
					? el(
							PanelBody,
							{
								title: __(
									'Overlay Opacity',
									'wptd-video-popup'
								),
								initialOpen: false,
							},
							el( RangeControl, {
								label: __(
									'Overlay Opacity (%)',
									'wptd-video-popup'
								),
								value: overlayOpacity,
								onChange: function ( value ) {
									setAttributes( {
										bgOpacity:
											typeof value === 'number'
												? value
												: 50,
									} );
								},
								min: 0,
								max: 100,
								step: 1,
								allowReset: true,
								resetFallbackValue: 50,
							} )
					  )
					: null
			),
			el(
				'div',
				blockProps,
				! url
					? el(
							Placeholder,
							{
								icon: 'video-alt3',
								label: __( 'Video Popup', 'wptd-video-popup' ),
								instructions: __(
									'Add a YouTube or Vimeo URL in the block settings.',
									'wptd-video-popup'
								),
							},
							el( TextControl, {
								label: __( 'Video URL', 'wptd-video-popup' ),
								value: url,
								onChange: function ( value ) {
									setAttributes( { url: value } );
								},
							} )
					  )
					: renderEditorPreview( attributes )
			)
		);
	}

	registerBlockType( 'wptd/video-popup', {
		edit: Edit,
		save: function () {
			return null;
		},
	} );
} )( window.wp );
