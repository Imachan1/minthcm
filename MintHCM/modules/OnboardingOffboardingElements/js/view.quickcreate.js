$( document ).ready( function () {
   setTypeList();
} );

function setTypeList() {
   var parent_type = $( 'input[name="parent_type"]' ).val();
   var form_selector = $( 'form#form_SubpanelQuickCreate_OnboardingOffboardingElements' );
   var element_type_selector = form_selector.find( '#type option[value=exit_interview]' );
   if ( parent_type == 'OnboardingTemplates' ) {
      element_type_selector.css( 'display', 'none' );
   }
}
