<?php

if ( $_REQUEST['launch'] == true ) {
   rebuildAllJavascripts();
}

function rebuildAllJavascripts() {
   rebuildJavascriptLanguages();
   rebuildJSCompressedFiles();
   rebuildJSGroupingFiles();
   rebuildMinifiedJSFiles();
   repairJSFile();
}

function rebuildJavascriptLanguages() {
   LanguageManager::removeJSLanguageFiles();
   LanguageManager::clearLanguageCache();
}

function rebuildJSCompressedFiles() {
   $_REQUEST['js_admin_repair'] = 'replace';
   $_REQUEST['root_directory'] = getcwd();
   include 'modules/Administration/callJSRepair.php';
}

function rebuildJSGroupingFiles() {
   $_REQUEST['js_admin_repair'] = 'concat';
   $_REQUEST['root_directory'] = getcwd();
   include 'modules/Administration/callJSRepair.php';
}

function rebuildMinifiedJSFiles() {
   $_REQUEST['js_admin_repair'] = 'mini';
   $_REQUEST['root_directory'] = getcwd();
   include 'modules/Administration/callJSRepair.php';
}

function repairJSFile() {
   $_REQUEST['js_admin_repair'] = 'repair';
   $_REQUEST['root_directory'] = getcwd();
   include 'modules/Administration/callJSRepair.php';
}
