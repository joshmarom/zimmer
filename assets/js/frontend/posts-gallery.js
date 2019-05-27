jQuery( window ).on( 'elementor/frontend/init', function() {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/posts-gallery.default', function( $element ) {
		const handler = new elementorModules.frontend.handlers.Base( { $element: $element } ),
			settings = handler.getElementSettings();

		$element.find( '.elementor-widget-container' ).Mosaic(
			{
				maxRowHeight: settings.max_row_height,
				refitOnResize: true,
				defaultAspectRatio: 0.5,
				maxRowHeightPolicy: settings.max_row_height_policy,
				highResImagesWidthThreshold: 850,
				responsiveWidthThreshold: 500,
				innerGap: settings.inner_gap,
				outerMargin: settings.outer_margin
			}
		);
	} );
} );
