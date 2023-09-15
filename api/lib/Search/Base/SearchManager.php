<?php

namespace MintHCM\Lib\Search\Base;

use MintHCM\Lib\Search\Base\SearchResult;

abstract class SearchManager
{
    protected $params,$query, $result_manager ;
    protected $elastic_acl = true;

    public function __construct()
    {
        $this->params = $params;
    }

    abstract public function setQuery(array $params): void;

    abstract protected function setResultManager($result, $handle_acl): void;

    abstract public function search($handle_acl): SearchResult;

}
