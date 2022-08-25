<?php

use SuiteCRM\Search\SearchQuery;
use SuiteCRM\Search\SearchWrapper;
use SuiteCRM\Search\UI\SearchThrowableHandler;

class ESListViewController
{
    protected $bean, $query, $per_page, $page, $engine, $options;

    public function __construct($bean)
    {
        $this->bean = $bean;
        $this->defs = $this->loadDefs();

        $this->query = null;
    }

    protected function loadDefs()
    {
        $defs = [];
        require_once 'include/MVC/View/views/view.ESListView.php';
        $kv = new ViewESList();
        $kv->type = 'ESlist';
        $kv->module = $this->bean->module_name;
        $metadataFile = $kv->getMetaDataFile();
        if (file_exists($metadataFile)) {
            include $metadataFile;
            $defs = $ESListViewDefs[$this->bean->module_name];
        }

        return $defs;
    }

    public function getResults()
    {
        $query = null;
        $per_page = $_GET['itemsPerPage'] ? $_GET['itemsPerPage'] : 10;
        $page = $_GET['page'] ? $_GET['page'] : 1;
        $engine = 'ElasticSearchEngine';

        $module = $_GET['module'];
        $column = $_GET['sortBy'];
        $direction = $_GET['sortOrder'];
        $options = [
            'filter_by_module' => true,
            'module' => $module,
            'sorting' => [
                'column' => $column,
                'direction' => $direction
            ],
            'filters' => [
                'filter' => [
                    // ['term' => ['named' => 'interview']],
                    // ['term' => ['meta.assigned.user_id' => $user_id]],
                    // ['term' => ['parent_type' => 'candidates']],
                ]
            ]
        ];

        try {
            $query = SearchQuery::fromString($query, $per_page, $page, $engine, $options);
            $results = SearchWrapper::search($query->getEngine(), $query);
            $beans = $results->getHitsAsBeans();
            $total = $results->getTotal();

            $results = [];
            foreach ($beans as $bean => $data) {
                foreach ($data as $item) {
                    // array_push($results, array_slice($item->fetched_row, 1, 6));
                    array_push($results, $item->fetched_row);
                }
            }

            $data = [];
            $data['total'] = $total;
            $data['results'] = $results;

            echo json_encode($data);
        } catch (Exception $exception) {
            handleThrowable($exception, $query);
        } catch (Throwable $throwable) {
            handleThrowable($throwable, $query);
        }
    }
}
