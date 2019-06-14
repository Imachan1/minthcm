$( document ).ready( function () {
   setTypeList();
} );

function setTypeList() {
   var element_id = $( 'input[type="hidden"][name="record"]' ).val();
   if ( typeof element_id !== 'undefined' && !_.isEmpty( element_id ) ) {
      viewTools.api.callCustomApi( {
         module: 'OnboardingOffboardingElements',
         action: 'isRelatedToOnboardingTemplates',
         dataPOST: {
            element_id: element_id
         },
         callback: function ( data ) {
            if ( data ) {
               var form_selector = $( 'form#EditView' );
               var element_type_selector = form_selector.find( '#type option[value=exit_interview]' );
               element_type_selector.css( 'display', 'none' );
            }
         }
      } );
   }
}
