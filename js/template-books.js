( function( $ ) {
  $( function() {
    console.log( 'href' );
    $( "a" ).each( function() {

      var i = $( this ).attr( "href" );
      var n = i.replace( "https://", "http://" );
      $( this ).attr( "href", function() {
        return n;
      } )
    } )

    $( 'body' ).on( 'click', '.added_to_cart', function( e ) {
      e.preventDefault();
      var i = $( this ).attr( "href" );
      var n = i.replace( "https://", "http://" );
      $( this ).attr( "href", function() {
        return n;
      } )
      $( this ).attr( "target", "_blank" );
      console.log( this );
      parent.location.href = n;
    } )

  } );
} )( jQuery );