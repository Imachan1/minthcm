<?php

namespace MintHCM\Lib\Search\ElasticSearch;

use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use MintHCM\Lib\Search\ElasticSearch\Operators\Equals;
use MintHCM\Lib\Search\ElasticSearch\Operators\Exists;
use MintHCM\Lib\Search\ElasticSearch\Operators\Match;
use MintHCM\Lib\Search\ElasticSearch\Operators\Range;
use MintHCM\Lib\Search\ElasticSearch\Operators\Wildcard;

class ElasticQueryOperatorsManager
{
    const OPERATORS_MAPPER = array(
        'equals' => Equals::class,
        'exists' => Exists::class,
        'match' => Match::class,
        'range' => Range::class,
        'wildcard' => Wildcard::class,
    );

    protected $query, $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
        $this->setQuery();
    }

    public function getQuery()
    {
        return $this->query;
    }

    protected function setQuery()
    {
        $this->query = array(
            'bool' => array(
                'filter' => array(),
                'must_not' => array(),
            ),
        );

        foreach ($this->filters as $filter) {
            if (empty($filter['type']) || !in_array($filter['type'], array_keys($this::OPERATORS_MAPPER))) {
                throw new BadRequest400Exception();
            }
            $class = $this::OPERATORS_MAPPER[$filter['type']];
            $operator = new $class($filter);
            $data = $operator->getData();
            $this->query['bool'][$operator->getArrayKey()][] = $data;
        }
    }
}
