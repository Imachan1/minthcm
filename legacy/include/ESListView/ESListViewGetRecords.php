<?php

use SuiteCRM\Search\SearchQuery;
use SuiteCRM\Search\SearchWrapper;

class ESListViewGetRecords {

    protected $engine = 'ESElasticSearchEngine';
    protected $options;
    protected $itemsPerPage;
    protected $offset;
    protected $module;
    protected $page;
    private $results = [];
    private $add_to_offset = 0;
    private $selected_records = 0;

    public function __construct($module, $itemsPerPage = 10, $offset = 1, $page = 1, $sort_by = '', $sort_order = 'asc', $arguments = []) {
        $options = [
            'filter_by_module' => true,
            'module' => $module,
            'myObjects' => isset($arguments['myObjects']) ? $arguments['myObjects'] : '',
            'searchPhrase' => isset($arguments['searchPhrase']) ? $arguments['searchPhrase'] : '',
            'sorting' => [
                'column' => $sort_by,
                'direction' => $sort_order
            ],
            'filters' => $arguments['filters'] ?? []
        ];
        if ($arguments['myObjects'] === true) {
            global $current_user;
            $options['filters']['filter'][] = ['term' => ['meta.assigned.user_name' => $current_user->user_name]];
        }
        if (strlen($options['searchPhrase'])) {
            $searchPhrase = str_replace('+', '', $options['searchPhrase']);
            $searchPhrase = strtolower($searchPhrase) . '*';
            $options['filters']['filter'][] = ['wildcard' => ['_all' => $searchPhrase]];
        }
        if (!empty($arguments['defaultFilters']['filter'])) {
            array_push($options['filters']['filter'], ...$arguments['defaultFilters']['filter']);
        }
        if (!empty($arguments['defaultFilters']['must_not'])) {
            array_push($options['filters']['must_not'], ...$arguments['defaultFilters']['must_not']);
        }
        $this->options = $options;
        $this->itemsPerPage = $itemsPerPage;
        $this->offset = $offset;
        $this->module = $module;
        $this->page = $page;
    }

    public function get() {
        $number_of_request_into_elasticsearch = 0;
        $total = $this->itemsPerPage;
        while ($this->selected_records < $this->itemsPerPage + 1 && $total >= $this->itemsPerPage) {
            list($beans, $query_results) = $this->getRecordsFromElasticSearch('', $this->itemsPerPage + 1, $this->offset + ($this->itemsPerPage * $number_of_request_into_elasticsearch), $this->engine, $this->options);
            $total = $query_results->getTotal();
            $this->add_to_offset = 0;
            foreach ($beans[$this->module] as $item) {
                $this->parseBeanItem($item);
            }
            $number_of_request_into_elasticsearch++;
        }
        if ($this->selected_records > $this->itemsPerPage) {
            $next_page_exists = true;
            array_pop($this->results);
        }
        $total_records = ($this->page - 1) * $this->itemsPerPage + count($this->results) + $next_page_exists;
        $offset = $this->offset + ($this->itemsPerPage * ($number_of_request_into_elasticsearch - 1)) + $this->add_to_offset + 1;
        return [$total_records, $offset, array_values($this->results)];
    }

    protected function parseBeanItem($item) {
        if (count($this->results) < $this->itemsPerPage) {
            $this->add_to_offset++;
        }
        if (count($this->results) <= $this->itemsPerPage && $item) {
            $row = $this->parseElasticSearchResultsToArray($item);
            if (!isset($this->results[$row['id']])) {
                $this->results[$row['id']] = $row;
                $this->selected_records++;
            }
        }
    }

    protected function parseElasticSearchResultsToArray($item) {
        $columns = $item->column_fields;
        $row = [];
        foreach ($columns as $column) {
            $row[$column] = $item->$column;
            if (isset($item->{$column . "_link"})) {
                $row[$column . "_link"] = $item->{$column . "_link"};
            }
        }
        $row['acl_access'] = $item->acl_access;
        return $row;
    }

    /**
     * @param string $query A string containing the search query.
     * @param int $per_page The number of results
     * @param int $offset The results offset (for pagination)
     * @param string|null $engine Name of the search engine to use. Use default if `null`
     * @param array|null $options Array with options (optional)
     *
     * @return array
     */
    protected function getRecordsFromElasticSearch($query, $per_page, $offset, $engine, $options) {
        $search_query = SearchQuery::fromString($query, $per_page, $offset, $engine, $options);
        $results = SearchWrapper::search($search_query->getEngine(), $search_query);
        $beans = $results->getHitsAsBeans();
        return [$beans, $results];
    }

}
