<div id='organizational-structure-{$id}' style="height:{$height}px;">
    <link rel="stylesheet" href="modules/Home/Dashlets/OrganizationStructureDashlet/css/organizational-structure.css">
    {literal}
        <script src="modules/Home/Dashlets/OrganizationStructureDashlet/js/raphael.js"></script>
        <script src="modules/Home/Dashlets/OrganizationStructureDashlet/js/Treant.js"></script>
        <script src="modules/Home/Dashlets/OrganizationStructureDashlet/js/jquery.min.js"></script>
        <script src="modules/Home/Dashlets/OrganizationStructureDashlet/js/jquery.easing.js"></script>
        <script>
            $( document ).ready( function () {
               var chart_config = {
                  chart: {
                     container: "#organizational-structure-{/literal}{$id}{literal}",
                     // scrollbar: "fancy",
                     connectors: {
                        type: 'step'
                     },

                     node: {
                        HTMLclass: 'osNode',
                        collapsable: false
                     }
                  },
                  nodeStructure: {
        {/literal} {$rootElement}{literal}
                     , children:  {/literal} {$jsonTree}{literal}
                  }
               };
               new Treant( chart_config );
            } );
        {/literal}
    </script>
</div>