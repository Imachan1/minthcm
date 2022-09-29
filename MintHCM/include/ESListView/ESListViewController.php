<?php

use SuiteCRM\Search\SearchQuery;
use SuiteCRM\Search\SearchWrapper;
use SuiteCRM\Search\UI\SearchThrowableHandler;
use MassUpdate;

class ESListViewController
{
    protected $bean, $query, $per_page, $page, $engine, $options;

    public function __construct($bean)
    {
        $this->bean = $bean;
        $this->defs = $this->loadDefs();
    }

    protected function loadDefs()
    {
        $defs = [];
        require_once 'include/MVC/View/views/view.eslistview.php';
        $kv = new ViewEslistview();
        $kv->type = 'ESlist';
        $kv->module = $this->bean->module_name;
        $metadataFile = $kv->getMetaDataFile();
        if (file_exists($metadataFile)) {
            include $metadataFile;
            $defs = $ESListViewDefs[$this->bean->module_name];
        }

        return $defs;
    }

    public function getMappings()
    {
        $module = $this->bean->module_name;
        $mappings = file_get_contents('http://10.8.0.103:9205/ecc3aab136efd8f791a90c11b95afad8_shared/_mappings/' . $module);
        echo $mappings;
    }

    public function massUpdate()
    {
        require_once 'include/MassUpdate.php';
        $_POST['mass'] = $_POST['IDs'];
        $_REQUEST['massupdate'] = true;
        $updater = new MassUpdate();
        $updater->setSugarBean($this->bean);
        if ($_POST['action_name'] === 'delete') {
            $_POST['Delete'] = true;
        }
        $updater->handleMassUpdate();

        echo json_encode(['success' => true]);
    }

    // public function getIDsForMassUpdate()
    // {
    //     global $current_user;
    //     $query = null;
    //     $engine = 'ElasticSearchEngine';

    //     $per_page = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    //     $page = isset($_GET['page']) ? $_GET['page'] : 1;
    //     $module = isset($_GET['module']) ? $_GET['module'] : '';
    //     $column = isset($_GET['sortBy']) ? $_GET['sortBy'] : '';
    //     $direction = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'asc';

    //     $options = [
    //         'filter_by_module' => true,
    //         'module' => $module,
    //         'myObjects' => isset($_GET['myObjects']) ? $_GET['myObjects'] : '',
    //         'searchPhrase' => isset($_GET['searchPhrase']) ? $_GET['searchPhrase'] : '',
    //         'sorting' => [
    //             'column' => $column,
    //             'direction' => $direction
    //         ],
    //         'filters' => []
    //     ];

    //     if ($options['myObjects'] === 'true') {
    //         array_push($options['filters'], ['term' => ['meta.assigned.user_id' => $current_user->id]]);
    //     }

    //     if (strlen($options['searchPhrase'])) {
    //         array_push($options['filters'], ['match' => ['_all' => $options['searchPhrase']]]);
    //     }

    //     try {
    //         $query = SearchQuery::fromString($query, $per_page, $page, $engine, $options);
    //         $results = SearchWrapper::search($query->getEngine(), $query);
    //         $IDs = $results->getHits();

    //         echo json_encode($IDs);
    //     } catch (Exception $exception) {
    //         $this->handleThrowable($exception, $query);
    //     } catch (Throwable $throwable) {
    //         $this->handleThrowable($throwable, $query);
    //     }
    // }

    public function getResults()
    {
        global $current_user;
        $query = null;
        $engine = 'ESElasticSearchEngine';

        $per_page = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 1;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $module = isset($_GET['module']) ? $_GET['module'] : '';
        $column = isset($_GET['sortBy']) ? $_GET['sortBy'] : '';
        $direction = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'asc';
        
        $options = [
            'filter_by_module' => true,
            'module' => $module,
            'myObjects' => isset($_GET['myObjects']) ? $_GET['myObjects'] : '',
            'searchPhrase' => isset($_GET['searchPhrase']) ? $_GET['searchPhrase'] : '',
            'sorting' => [
                'column' => $column,
                'direction' => $direction
            ],
            'filters' => $_REQUEST['filters'] ?? []
        ];

        if ($options['myObjects'] === 'true') {
            array_push($options['filters'], ['term' => ['meta.assigned.user_id' => $current_user->id]]);
        }

        if (strlen($options['searchPhrase'])) {
            array_push($options['filters'], ['wildcard' => ['name.name' => $options['searchPhrase']]]);
        }
        try {
            $i = 0;
            $selected_records = 0;
            $results = [];
            $add_to_offset = 0;
            $total = $per_page;
            while ($selected_records < $per_page + 1 && $total >= $per_page) {
                list($beans, $query_results) = $this->getRecordsFromElasticSearch($query, $per_page +1, $offset + ($per_page * $i), $engine, $options);
                $total = $query_results->getTotal();
                $add_to_offset = 0;
                foreach ($beans as $bean => $data) {
                    foreach ($data as $item) {
                        if (count($results) < $per_page){
                            $add_to_offset++;
                        }
                        if (count($results) <= $per_page && $item) {
                            $columns = $item->column_fields;
                            $row = [];
                            foreach ($columns as $column) {
                                $row[$column] = $item->$column;
                                if(isset($item->{$column."_link"})){
                                    $row[$column."_link"] = $item->{$column."_link"};
                                }
                            }
                            $row['acl_access'] = $item->acl_access;
                            if (!isset($results[$row['id']])) {
                                $results[$row['id']] = $row;
                                $selected_records++;
                            }
                        }
                    }
                }
                $i++;
            }
            if($selected_records > $per_page){
                $next_page_exists = true;
            }
            if (count($results) > $per_page) {
                array_pop($results);
            }


            $data = [];
            $data['total'] = ($page - 1) * $per_page + count($results) + $next_page_exists;
            $data['offset'] = $offset + ($per_page * ($i-1)) + $add_to_offset;
            $data['results'] = array_values($results);

            echo json_encode($data);
            exit;
        } catch (Exception $exception) {
            $this->handleThrowable($exception, $query);
        } catch (Throwable $throwable) {
            $this->handleThrowable($throwable, $query);
        }
    }

    protected function getRecordsFromElasticSearch($query, $per_page, $offset, $engine, $options) {
        $search_query = SearchQuery::fromString($query, $per_page , $offset, $engine, $options);
        $results = SearchWrapper::search($search_query->getEngine(), $search_query);
        $beans = $results->getHitsAsBeans();
        return [$beans, $results];
    }

    public function handleThrowable($throwable, SearchQuery $query)
    {
        $handler = new SearchThrowableHandler($throwable, $query);
        $handler->handle();
    }
}
