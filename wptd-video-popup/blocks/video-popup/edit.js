/**
 * Video Popup block editor component.
 */
import { __ } from '@wordpress/i18n';
import {
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	useBlockProps,
	PanelColorSettings,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	SelectControl,
	Button,
	Placeholder,
	__experimentalNumberControl as NumberControl,
	RangeControl,
	ToggleControl,
} from '@wordpress/components';

function getHexFromColor( color ) {
	if ( ! color ) {
		return '#000000';
	}

	if ( color.indexOf( '#' ) === 0 ) {
		return color;
	}

	const match = color.match( /rgba?\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/ );

	if ( ! match ) {
		return '#000000';
	}

	return (
		'#' +
		[ match[1], match[2], match[3] ]
			.map( ( value ) => {
				const hex = parseInt( value, 10 ).toString( 16 );
				return hex.length === 1 ? `0${ hex }` : hex;
			} )
			.join( '' )
	);
}

function getOpacityFromColor( color, fallback = 50 ) {
	const match = color && color.match(
		/rgba\(\s*[\d.]+\s*,\s*[\d.]+\s*,\s*[\d.]+\s*,\s*([\d.]+)\s*\)/
	);

	if ( ! match ) {
		return fallback;
	}

	return Math.round( parseFloat( match[1] ) * 100 );
}
import ServerSideRender from '@wordpress/server-side-render';

function renderEditorPreview( attributes ) {
	const { trigger, text, icon, img } = attributes;

	if ( trigger === 'icon' && icon ) {
		return (
			<span className="wptd-popup-video wptd-popup-type-icon">
				<span className={ icon } />
			</span>
		);
	}

	if ( trigger === 'img' && img ) {
		return (
			<span className="wptd-popup-video wptd-popup-type-img">
				<img src={ img } alt={ __( 'Popup', 'wptd-video-popup' ) } />
			</span>
		);
	}

	return (
		<span className="wptd-popup-video wptd-popup-type-text">
			{ text || __( 'Click', 'wptd-video-popup' ) }
		</span>
	);
}

export default function Edit( { attributes, setAttributes } ) {
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

	const triggerOptions = [
		{ label: __( 'Text', 'wptd-video-popup' ), value: 'text' },
		{ label: __( 'Icon', 'wptd-video-popup' ), value: 'icon' },
		{ label: __( 'Image', 'wptd-video-popup' ), value: 'img' },
	];

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Video Settings', 'wptd-video-popup' ) }
					initialOpen={ true }
				>
					<TextControl
						label={ __( 'Video URL', 'wptd-video-popup' ) }
						help={ __(
							'YouTube or Vimeo URL. Example: https://www.youtube.com/watch?v=LXb3EKWsInQ',
							'wptd-video-popup'
						) }
						value={ url }
						onChange={ ( value ) =>
							setAttributes( { url: value } )
						}
					/>
					<ToggleControl
						label={ __( 'Autoplay Video', 'wptd-video-popup' ) }
						help={ __(
							'Start playing the video when the popup opens.',
							'wptd-video-popup'
						) }
						checked={ autoplay !== false }
						onChange={ ( value ) =>
							setAttributes( { autoplay: value } )
						}
					/>
					<SelectControl
						label={ __( 'Trigger Type', 'wptd-video-popup' ) }
						value={ trigger }
						options={ triggerOptions }
						onChange={ ( value ) =>
							setAttributes( { trigger: value } )
						}
					/>
					{ trigger === 'text' && (
						<TextControl
							label={ __( 'Trigger Text', 'wptd-video-popup' ) }
							value={ text }
							onChange={ ( value ) =>
								setAttributes( { text: value } )
							}
						/>
					) }
					{ trigger === 'icon' && (
						<TextControl
							label={ __( 'Icon Class', 'wptd-video-popup' ) }
							help={ __(
								'CSS class for the icon, e.g. fa fa-play',
								'wptd-video-popup'
							) }
							value={ icon }
							onChange={ ( value ) =>
								setAttributes( { icon: value } )
							}
						/>
					) }
					{ trigger === 'img' && (
						<MediaUploadCheck>
							<MediaUpload
								onSelect={ ( media ) =>
									setAttributes( {
										img: media.url,
										imgId: media.id,
									} )
								}
								allowedTypes={ [ 'image' ] }
								value={ imgId }
								render={ ( { open } ) => (
									<div className="wptd-video-popup-media">
										{ img && (
											<img
												src={ img }
												alt={ __(
													'Popup trigger',
													'wptd-video-popup'
												) }
											/>
										) }
										<Button
											variant="secondary"
											onClick={ open }
										>
											{ img
												? __(
														'Replace Image',
														'wptd-video-popup'
												  )
												: __(
														'Select Image',
														'wptd-video-popup'
												  ) }
										</Button>
										{ img && (
											<Button
												variant="link"
												isDestructive
												onClick={ () =>
													setAttributes( {
														img: '',
														imgId: 0,
													} )
												}
											>
												{ __(
													'Remove Image',
													'wptd-video-popup'
												) }
											</Button>
										) }
									</div>
								) }
							/>
						</MediaUploadCheck>
					) }
				</PanelBody>
				<PanelBody
					title={ __( 'Popup Style', 'wptd-video-popup' ) }
					initialOpen={ false }
				>
					<NumberControl
						label={ __( 'Popup Width (px)', 'wptd-video-popup' ) }
						value={ width || '' }
						min={ 0 }
						step={ 1 }
						onChange={ ( value ) =>
							setAttributes( {
								width: value ? parseInt( value, 10 ) : 0,
							} )
						}
					/>
				</PanelBody>
				<PanelColorSettings
					title={ __(
						'Overlay Background Color',
						'wptd-video-popup'
					) }
					initialOpen={ false }
					colorSettings={ [
						{
							value: overlayColor,
							onChange: ( value ) =>
								setAttributes( {
									bgColor: value || '#000000',
								} ),
							label: __(
								'Overlay Color',
								'wptd-video-popup'
							),
						},
					] }
				/>
				<PanelBody
					title={ __( 'Overlay Opacity', 'wptd-video-popup' ) }
					initialOpen={ false }
				>
					<RangeControl
						label={ __(
							'Overlay Opacity (%)',
							'wptd-video-popup'
						) }
						value={ overlayOpacity }
						onChange={ ( value ) =>
							setAttributes( {
								bgOpacity:
									typeof value === 'number' ? value : 50,
							} )
						}
						min={ 0 }
						max={ 100 }
						step={ 1 }
						allowReset
						resetFallbackValue={ 50 }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ ! url ? (
					<Placeholder
						icon="video-alt3"
						label={ __( 'Video Popup', 'wptd-video-popup' ) }
						instructions={ __(
							'Add a YouTube or Vimeo URL in the block settings.',
							'wptd-video-popup'
						) }
					>
						<TextControl
							label={ __( 'Video URL', 'wptd-video-popup' ) }
							value={ url }
							onChange={ ( value ) =>
								setAttributes( { url: value } )
							}
						/>
					</Placeholder>
				) : (
					renderEditorPreview( attributes )
				) }
			</div>
		</>
	);
}
