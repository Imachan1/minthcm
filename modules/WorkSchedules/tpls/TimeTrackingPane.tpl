<link rel="stylesheet" type="text/css" href="modules/WorkSchedules/tpls/TimeTrackingPane.css" />
<div class="evTimePanel">
    <table style="width:100%;margin-top:10px;" cellspacing="0">
        <tr>
            <td class="evTimePanelLeft" style="z-index:1; width: 40px;">-</td>
            <td class="evTimePanelMiddle">&nbsp;</td>
            <td class="evTimePanelRight" style="width: 40px;">-</td>
        </tr>
    </table>
</div>
<script type="text/javascript">
    {literal}
        var field_label = $( ".evTimePanel" ).parent().parent().prev( '.label' );
        if ( field_label.length > 0 ) {
           field_label.remove();
        }
        var field_container = $( ".evTimePanel" ).parent().parent( '.col-sm-10' );
        if ( field_container.length > 0 ) {
           field_container.addClass( 'col-sm-12' ).removeClass( 'col-sm-10' );
        }
        var panel_header = $( ".evTimePanel" ).parent().parent().parent().parent().parent().parent().prev( '.panel-heading' );
        if ( panel_header.length > 0 ) {
           panel_header.remove();
        }
        var timetracking_panels_all = $( '.evTimePanel' );
        new evTimePanel( timetracking_panels_all[timetracking_panels_all.length - 1] );
    {/literal}
</script>
