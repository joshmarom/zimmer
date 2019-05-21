jQuery( window ).on( 'elementor/frontend/init', function() {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/posts-gallery.default', function( $element ) {
		$element.find( '.elementor-widget-container' ).Mosaic();
	} );
} );
