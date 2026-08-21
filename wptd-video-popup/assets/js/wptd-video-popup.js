
(function( $ ) {

	"use strict";

	function wptd_is_autoplay_enabled( value ) {
		if ( value === undefined || value === null || value === '' ) {
			return true;
		}

		return value === 1 || value === '1' || value === true || value === 'true';
	}

	function wptd_get_iframe_patterns( autoplay ) {
		var play = autoplay ? '1' : '0';

		return {
			youtube: {
				index: 'youtube.com/',
				id: 'v=',
				src: 'https://www.youtube.com/embed/%id%?autoplay=' + play
			},
			youtu: {
				index: 'youtu.be/',
				id: '/',
				src: 'https://www.youtube.com/embed/%id%?autoplay=' + play
			},
			vimeo: {
				index: 'vimeo.com/',
				id: '/',
				src: '//player.vimeo.com/video/%id%?autoplay=' + play + '&autopause=0'
			},
		};
	}

	function wptd_get_popup_config( getBodyClass, autoplay ) {
		return {
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
			fixedContentPos: false,
			iframe: {
				markup: '<div class="mfp-iframe-scaler">' +
					'<div class="mfp-close"></div>' +
					'<iframe class="mfp-iframe" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>' +
					'</div>',
				patterns: wptd_get_iframe_patterns( autoplay ),
			},
			callbacks: {
				open: function() {
					var bodyClass = typeof getBodyClass === 'function' ? getBodyClass() : getBodyClass;

					if ( bodyClass ) {
						$( 'body' ).addClass( bodyClass );
					}
				},
				close: function() {
					var bodyClass = typeof getBodyClass === 'function' ? getBodyClass() : getBodyClass;

					if ( bodyClass ) {
						$( 'body' ).removeClass( bodyClass );
					}
				}
			}
		};
	}

	function wptd_init_popup_trigger( $trigger, bodyClass ) {
		var autoplay = wptd_is_autoplay_enabled( $trigger.data( 'autoplay' ) );
		var activeBodyClass = bodyClass || '';

		$trigger.on( 'click', function() {
			if ( $( this ).data( 'id' ) ) {
				activeBodyClass = $( this ).data( 'id' );
			}
		});

		$trigger.magnificPopup( wptd_get_popup_config( function() {
			return activeBodyClass;
		}, autoplay ) );
	}

	/* Shortcode CSS Append */
	if ( $( document ).find( '.wptd-inline-css' ).length ) {
		var css_out = '';
		$( document ).find( '.wptd-inline-css' ).each( function() {
			var shortcode = $( this );
			if ( shortcode.attr( 'data-css' ) ) {
				var shortcode_css = shortcode.attr( 'data-css' );
				css_out += ( $ ).parseJSON( shortcode_css );
			}
		});
		if ( css_out !== '' ) {
			$( 'head' ).append( '<style id="wptd-shortcode-styles">' + css_out + '</style>' );
		}
	}

	// Shortcode and Gutenberg block triggers.
	if ( $( document ).find( '.wptd-popup-video' ).length ) {
		$( document ).find( '.wptd-popup-video' ).each( function() {
			wptd_init_popup_trigger( $( this ) );
		});
	}

	// Elementor Video popup handler.
	var wptd_video_popup_handler = function( $scope, $ ) {
		if ( $scope.find( '.wptd-video-popup-trigger' ).length ) {
			$scope.find( '.wptd-video-popup-trigger' ).each( function() {
				wptd_popup_fun( this, $scope.attr( 'data-id' ) );
			});
		}
	};

	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wptd_video_popup.default', wptd_video_popup_handler );
	});

	function wptd_popup_fun( _ele, _scope_id ) {
		/* Shortcode CSS Append */
		if ( $( document ).find( '.wptd-video-popup-inline-css' ).length ) {
			var css_out = '';
			$( '#wptd-elementor-shortcode-styles' ).remove();
			$( document ).find( '.wptd-video-popup-inline-css' ).each( function() {
				var shortcode = $( this );
				if ( shortcode.attr( 'data-css' ) ) {
					var shortcode_css = shortcode.attr( 'data-css' );
					css_out += ( $ ).parseJSON( shortcode_css );
					shortcode.remove();
				}
			});
			if ( css_out !== '' ) {
				$( 'head' ).append( '<style id="wptd-elementor-shortcode-styles">' + css_out + '</style>' );
			}
		}

		var $ele = $( _ele );
		var bodyClass = 'wptd-video-popup-' + _scope_id;

		wptd_init_popup_trigger( $ele, bodyClass );
	}

})( jQuery );
