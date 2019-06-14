$( document ).ready( function () {
   var OnboardingOffboardingElementsViewQuickCreate = function () {
      this.construct = function () {
         run();
      };

      var run = function () {
         beforeSave();
         setTypeList();
      };

      var beforeSave = function () {
         viewTools.form.beforeSave( function () {
            var result = validateTaskDuration();
            if ( result === false ) {
               viewTools.form.focusOnFirstError();
            }
            return result;
         } );
      };

      var validateTaskDuration = function () {
         var result = true;
         var task_duration_hours = $( '#task_duration_hours' );
         var task_duration_minutes = $( '#task_duration_minutes' );
         var task_duration_hours_val = parseInt( task_duration_hours.val() );
         var task_duration_minutes_val = parseInt( task_duration_minutes.val() );
         if ( task_duration_hours_val < 0 || task_duration_minutes_val < 0 ) {
            result = false;
            viewTools.GUI.fieldErrorMark( task_duration_hours, viewTools.language.get( 'OnboardingOffboardingElements', 'LBL_ERR_NOTICE_DURATION_TIME' ) );
         }
         return result;
      };

      var setTypeList = function() {
         var parent_type = $( 'input[name="parent_type"]' ).val();
         var form_selector = $( 'form#form_SubpanelQuickCreate_OnboardingOffboardingElements' );
         var element_type_selector = form_selector.find( '#type option[value=exit_interview]' );
         if ( parent_type == 'OnboardingTemplates' ) {
            element_type_selector.css( 'display', 'none' );
         }
      };

      this.construct();
   };

   if ( typeof onboarding_offboarding_elements_view_quickcreate === 'undefined' ) {
      onboarding_offboarding_elements_view_quickcreate = new OnboardingOffboardingElementsViewQuickCreate();
   }
} );
