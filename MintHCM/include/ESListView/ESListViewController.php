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

    public function getResults($options) {
        $get_records = new ESListViewGetRecords($options['module'], $options['itemsPerPage'], $options['offset'], $options['page'], $options['sortBy'], $options['sortOrder'], [
            'myObjects' => $options['myObjects'],
            'searchPhrase' => $options['searchPhrase'] ?? '',
            'filters' => $options['filters'] ?? [],
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

    public function savePreferences($data) {
        global $current_user;
        $module = $data['module'];
        $preferences = $data['preferences'];
        if (!empty($preferences) && is_array($preferences) && !empty($module)) {
            (new UserPreference($current_user))->setPreference($module, $preferences, 'eslist');
        }
    }
}
