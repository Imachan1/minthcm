$( document ).ready( function () {
   if ( $( '#year' ).val() == '' ) {
      var date = new Date();
      var month_names = [ "january", "february", "march", "april", "may", "june",
         "july", "august", "september", "october", "november", "december"
      ];
      $( '#year' ).val( date.getFullYear() );
      $( '#months' ).val( month_names[date.getMonth()] );
   }
   $( '#working_days' ).on( 'change', workingDaysChange );
   $( '#working_hours' ).on( 'change', workingHoursChange );
} );

function workingDaysChange( ) {
   $( '#working_hours' ).val( parseInt( $( '#working_days' ).val() ) * 8 );
}
function workingHoursChange( ) {
   $( '#working_days' ).val( Math.round( parseInt( $( '#working_hours' ).val() ) / 8, 0 ) );
}
