<?php

namespace MintHCM\Lib\Search;

abstract class SearchManager
{
    protected $params;

    abstract public function getResult(): array;

    abstract public function getResultBeans(): array;

    abstract public function execute(array $params): void;
}
