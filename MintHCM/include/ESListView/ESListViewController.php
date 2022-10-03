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

    public function savePreferences() {
        global $current_user;
        $module = $_REQUEST['module'];
        $preferences = $_REQUEST['preferences'];
        if (!empty($preferences) && is_array($preferences) && !empty($module)) {
            (new UserPreference($current_user))->setPreference($module, $preferences, 'eslist');
        }
    }
}
