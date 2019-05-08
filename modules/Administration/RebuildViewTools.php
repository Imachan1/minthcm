<?php
if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $beanList, $current_user, $db, $mod_strings;
$validBeans = array();
if ( !is_admin($current_user) ) {
   sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
}
$admin_mod_strings = return_module_language($current_language, 'Administration');
ob_start();
?>
<style>
    .vt_rebuild_response h1 {
        font-size: x-large;
    }
</style>
<div style="border-bottom: 1px solid #cccccc; border-top: 1px solid #cccccc; margin-bottom:5px; margin-top:5px;">
    <a class="vt_rebuild" style="cursor:pointer;"><?php echo $mod_strings['LBL_CONFIGURE_REBUILD_VIEWTOOLS_TITLE']; ?></a> | 
    <a href="index.php?module=Administration&action=Upgrade"><?php echo $admin_mod_strings['LBL_RETURN']; ?></a>
</div>
<div class="vt_rebuild_response">Loading </div>

<script type="text/javascript">
   $( document ).ready( function () {
      //Load actual Expression documentation
      animateLoading();
      getExpressionsDocumentation();
      //rebuild documentation
      $( '.vt_rebuild' ).on( 'click', function () {
         $( '.vt_rebuild_response' ).fadeOut( 500, function () {
            $( '.vt_rebuild_response' ).removeClass( 'loaded' ).html( '<div  id="rebuildMessage">Rebuilding</div>' ).fadeIn( 500 );
            animateLoading();
            viewTools.api.callCustomApi( {
               module: 'Home',
               action: 'rebuildLock',
               callback: function ( data ) {
                  if ( data.status == 'ok' ) {
                     checkRebuildFinish();
                  }
               }
            } );
         } );
      } );
   } );
   //Get generated html documentation based on class descriptions
   function getExpressionsDocumentation() {
      //Check if View Tools tools are already defined
      if ( window.viewTools !== undefined ) {
         viewTools.api.callCustomApi( {
            module: 'Home',
            action: 'getExpressionsDocumentation',
            callback: function ( data ) {
               if ( !$( '.vt_rebuild_response' ).hasClass( 'loaded' ) ) {
                  $( '.vt_rebuild_response' ).addClass( 'loaded' );
               }
               $( '.vt_rebuild_response' ).fadeOut( 500, function () {
                  $( '.vt_rebuild_response' ).html( data.documentation );
                  $( '.vt_rebuild_response' ).fadeIn( 500 );
                  //By default show all formulas
                  setFilter( 'vt_formula' );
               } );
            }
         } );
      } else {
         if ( !$( '.vt_rebuild_response' ).hasClass( 'loaded' ) ) {
            $( '.vt_rebuild_response' ).addClass( 'loaded' );
         }
         $( '.vt_rebuild_response' ).fadeOut( 500, function () {
            $( '.vt_rebuild_response' ).html( '' );
            $( '.vt_rebuild_response' ).fadeIn( 500 );
         } );
      }
   }
   //
   function checkRebuildFinish( rebuildLimit ) {
      if ( rebuildLimit === undefined ) {
         var rebuildLimit = 60 * 10;
      }
      if ( rebuildLimit > 0 ) {
         setTimeout( function () {
            viewTools.api.callCustomApi( {
               module: 'Home',
               action: 'rebuildCheckLock',
               callback: function ( data ) {
                  if ( data.lock !== undefined && data.lock != '' ) {
                     getExpressionsDocumentation();
                  } else {
                     checkRebuildFinish( rebuildLimit - 1 );
                  }
               }
            } );
         }, 1000 );
      } else {
         getExpressionsDocumentation();
      }
   }
   //Dispaly animated loading message
   function animateLoading() {
      if ( !$( '.vt_rebuild_response' ).hasClass( 'loaded' ) ) {
         $( '.vt_rebuild_response' ).append( '<span class="vt_dotAnimate"> &nbsp;.&nbsp; </span>' );
         $( '.vt_dotAnimate' ).each( function () {
            $( this ).removeClass( 'vt_dotAnimate' );
            $( this ).css( {'background-color': '#000'} ).animate( {'background-color': '#fff'}, 2000 );
         } );
         setTimeout( function () {
            animateLoading();
         }, 500 );
      }
   }
   //Set formula filter
   function setFilter( filter_name ) {
      $( 'div.vt_formula' ).each( function () {
         if ( $( this ).hasClass( filter_name ) ) {
            $( this ).fadeIn( 300 );
         } else {
            $( this ).fadeOut( 300 );
         }
      } );
      //Display filter selection
      $( 'a.filterButton' ).each( function () {
         if ( $( this ).hasClass( filter_name ) ) {
            $( this ).css( {'font-weight': 'bold', 'border-bottom': 'solid'} );
         } else {
            $( this ).css( {'font-weight': 'normal', 'border-bottom': 'none'} );
         }
      } );
   }
</script>
<?php
echo ob_get_clean();
