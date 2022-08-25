{if empty($columns)}<p class="error">{$APP.ERR_ESLIST_COL_ERROR}</p>
    {else}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <script type="text/javascript" src='{sugar_getjspath file="include/ESListView/eslist-view.min.js"}'></script>
        <script type="text/javascript" src='{sugar_getjspath file="include/ESListView/ESList.js"}'></script>
        <es-list />
            <script type="text/javascript">
                window.ESList = new ESList({$json},'{$module}');
                window.ESList.init();
            </script>
    {/if}