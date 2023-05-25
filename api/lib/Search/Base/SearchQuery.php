<?php

namespace MintHCM\Lib\Search\Base;

abstract class SearchQuery
{
    protected $params, $query, $sort, $size, $from;

    public function __construct(array $params)
    {
        $this->params = $params;
        $this->setSize();
        $this->setFrom();
        $this->setSort();
        $this->setQuery();
    }

    public function getQuery()
    {
        return $this->query;
    }

    abstract protected function setSort();

    abstract protected function setQuery();

    protected function setSize()
    {
        global $mint_config;

        $this->size = $this->params['items'] ?? ($mint_config['search']['default_page_size'] ?? 25);
        $this->size += 1; //Add one more to check exists next page
    }

    protected function setFrom()
    {
        $this->from = $this->params['from'] ?? -1;
    }

}
