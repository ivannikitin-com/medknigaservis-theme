( function( $ ) {
	$( function() {
		let formatPhoneNumber = ( str ) => {
			let match;
			str = ( '' + str ).replace( /[^\d\+]/g, '' );
			switch ( str.substring( 0, 4 ) ) {
				case "+374":
					match = str.match( /^(\+374)?(\d{2})(\d{3})(\d{3})$/ );
					if ( match ) {
						return match[ 1 ] + ' (' + match[ 2 ] + ') ' + match[ 3 ] + '-' + match[ 4 ];
					}
					break;
				case "+375":
					match = str.match( /^(\+375)?(\d{2})(\d{3})(\d{2})(\d{2})$/ );
					if ( match ) {
						console.log( match );
						return match[ 1 ] + ' (' + match[ 2 ] + ') ' + match[ 3 ] + '-' + match[ 4 ] + '-' + match[ 5 ];

					}
					break;
				case "+996":
					match = str.match( /^(\+996)?(\d{3})(\d{3})(\d{3})(\d{3})$/ );
					if ( match ) {
						return match[ 1 ] + ' (' + match[ 2 ] + ') ' + match[ 3 ] + '-' + match[ 4 ];
					}
					break;
				default:
					match = str.match( /^(\+\d)?(\d{3})(\d{3})(\d{2})(\d{2})$/ );
					if ( match ) {
						return match[ 1 ] + ' (' + match[ 2 ] + ') ' + match[ 3 ] + '-' + match[ 4 ] + '-' + match[ 5 ];
					}
			}
		}
		$( 'a[href^="tel:"]' ).each( function() {
			$( this ).text( formatPhoneNumber( $( this ).text() ) );
		} );
	} );
} )( jQuery );