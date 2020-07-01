<div id='organizational-structure' style="height:{$height}" ></div>
<link rel="stylesheet" href="modules/Home/Dashlets/OrganizationStructureDashlet/css/organizational-structure.css">
{literal}
<div class="chart Treant" id="organizational-structure"></div>
<script src="modules/Home/Dashlets/OrganizationStructureDashlet/js/raphael.js"></script>
<script src="modules/Home/Dashlets/OrganizationStructureDashlet/js/Treant.js"></script>
<script src="modules/Home/Dashlets/OrganizationStructureDashlet/js/jquery.min.js"></script>
<script src="modules/Home/Dashlets/OrganizationStructureDashlet/js/jquery.easing.js"></script>
<script>
 $( document ).ready( function () {
    var chart_config = {
    chart: {
        container: "#organizational-structure",
        // scrollbar: "fancy",
        connectors: {
            type: 'step'
        },
        node: {
            HTMLclass: 'nodeExample1',
            collapsable: true
        }
    },
    nodeStructure: {
        text: {
            name: "Mark Hill",
            title: "Chief executive officer",
            contact: "Tel: 01 213 123 134",
        },
        children: {/literal} {$jsonTree} {literal}
        }
    };
    new Treant( chart_config );
});
{/literal}
</script>
