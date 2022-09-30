<?php

use SuiteCRM\Search\SearchQuery;
use SuiteCRM\Search\SearchWrapper;
use SuiteCRM\Search\UI\SearchThrowableHandler;
use MassUpdate;

require_once 'include/ESListView/ESListViewGetRecords.php';

class ESListViewController {

    protected $bean, $query, $per_page, $page, $engine, $options;

    public function __construct($bean) {
        $this->bean = $bean;
        $this->defs = $this->loadDefs();
    }

    protected function loadDefs() {
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

    public function getMappings() {
        $module = $this->bean->module_name;
        $mappings = file_get_contents('http://10.8.0.103:9205/ecc3aab136efd8f791a90c11b95afad8_shared/_mappings/' . $module);
        echo $mappings;
    }

    public function massUpdate() {
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



    public function getResults() {
        $get_records = new ESListViewGetRecords($_GET['module'], $_GET['itemsPerPage'], $_GET['offset'], $_GET['page'], $_GET['sortBy'], $_GET['sortOrder'], [
            'myObjects' => $_GET['myObjects'] ?? '',
            'searchPhrase' => $_GET['searchPhrase'] ?? '',
            'filters' => $_REQUEST['filters'] ?? [],
        ]);
        try {
            list($total, $offset, $results) = $get_records->get();
            echo json_encode(['total' => $total,'offset' => $offset,'results' => $results]);
            exit;
        } catch (Exception $exception) {
            $this->handleThrowable($exception, $query);
        } catch (Throwable $throwable) {
            $this->handleThrowable($throwable, $query);
        }
    }

    public function handleThrowable($throwable, SearchQuery $query) {
        $handler = new SearchThrowableHandler($throwable, $query);
        $handler->handle();
    }

}
