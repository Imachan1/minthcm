#!/bin/bash
remote_path=/x/www/contrain_prod

cp ../MintHCM/include/ESListView/eslist-view.min.js $remote_path/include/ESListView/eslist-view.min.js;
cp ../MintHCM/include/ESListView/eslist-view.min.js ../../contrain/MintHCM/include/ESListView/eslist-view.min.js;

# cp ../MintHCM/include/ESListView $remote_path/include/ -r;
# cp ../MintHCM/include/MVC/View/views/view.eslistview.php $remote_path/include/MVC/View/views/view.eslistview.php;
# cp ../MintHCM/modules/Calls/metadata/eslistviewdefs.php $remote_path/modules/Calls/metadata/eslistviewdefs.php;
# cp ../MintHCM/modules/Candidates/metadata/eslistviewdefs.php $remote_path/modules/Candidates/metadata/eslistviewdefs.php;
# cp ../MintHCM/include/language/en_us.lang.php $remote_path/include/language/en_us.lang.php;
# cp ../MintHCM/lib/Search/ElasticSearch/defaultParams.yml $remote_path/lib/Search/ElasticSearch/defaultParams.yml;
# cp ../MintHCM/lib/Search/ElasticSearch/ElasticSearchEngine.php $remote_path/lib/Search/ElasticSearch/ElasticSearchEngine.php;
