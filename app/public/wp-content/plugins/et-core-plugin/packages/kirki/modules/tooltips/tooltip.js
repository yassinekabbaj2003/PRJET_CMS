/* global kirkiTooltips */
jQuery( document ).ready( function() {

	function kirkiTooltipAdd( control ) {
		_.each( kirkiTooltips, function( tooltip ) {
			let trigger,
				controlID,
				content;

			if ( tooltip.id !== control.id ) {
				return;
			}

			// fix for skipping adding dimensions tooltips
			if ( jQuery.inArray(control.params.type, ['kirki-dimensions', 'repeater']) > -1 ) {
				if ( control.container.find( '> label > .customize-control-title > .tooltip-wrapper').length )
					return;
			}
			else if ( control.container.find( '.tooltip-content' ).length ) {
				return;
			}

			trigger   = '<span class="tooltip-trigger" data-setting="' + tooltip.id + '"><span class="dashicons dashicons-editor-help"></span></span>';
			controlID = '#customize-control-' + tooltip.id + ' > label .customize-control-title,' +
				'#customize-control-' + tooltip.id + ' > .kirki-input-container > label .customize-control-title,' +
				'#customize-control-' + tooltip.id + '.customize-control-kirki-multicolor > .customize-control-title,'+
				'#customize-control-' + tooltip.id + '.customize-control-kirki-multicheck > .customize-control-title,'+
				'#customize-control-' + tooltip.id + '.customize-control-upload > .customize-control-title,' +
				'#customize-control-' + tooltip.id + '.customize-control-kirki-radio-buttonset > .customize-control-title';
			content   = '<span class="tooltip-content" data-setting="' + tooltip.id + '">' + tooltip.content + '</span>';

			// Add the trigger & content.
			jQuery( '<span class="tooltip-wrapper">' + trigger + content + '</span>' ).appendTo( controlID );

			// Handle onclick events.
			// jQuery( '.tooltip-trigger[data-setting="' + tooltip.id + '"]' ).on( 'click', function() {
			// 	jQuery( '.tooltip-content[data-setting="' + tooltip.id + '"]' ).toggleClass( 'hidden' );
			// } );
		} );

		// Close tooltips if we click anywhere else.
		// jQuery( document ).mouseup( function( e ) {
		//
		// 	if ( ! jQuery( '.tooltip-content' ).is( e.target ) ) {
		// 		if ( ! jQuery( '.tooltip-content' ).hasClass( 'hidden' ) ) {
		// 			jQuery( '.tooltip-content' ).addClass( 'hidden' );
		// 		}
		// 	}
		// } );
	}

	wp.customize.control.each( function( control ) {
		wp.customize.section( control.section(), function( section ) {
			if ( section.expanded() || wp.customize.settings.autofocus.control === control.id ) {
				kirkiTooltipAdd( control );
			} else {
				section.expanded.bind( function( expanded ) {
					if ( expanded ) {
						kirkiTooltipAdd( control );
					}
				} );
			}
		} );
	} );
} );
