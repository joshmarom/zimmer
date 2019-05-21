jQuery( window ).on( 'elementor/frontend/init', function() {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/posts-gallery.default', function( $element ) {
		const handler = new elementorModules.frontend.handlers.Base( { $element: $element } ),
			settings = handler.getElementSettings();

		$element.find( '.elementor-widget-container' ).Mosaic();
	} );
} );
