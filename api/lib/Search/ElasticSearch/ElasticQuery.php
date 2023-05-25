<?php

namespace MintHCM\Lib\Search\ElasticSearch;

use Elasticsearch\Common\Exceptions\InvalidArgumentException;
use MintHCM\Lib\Search\Base\SearchQuery;
use MintHCM\Lib\Search\ElasticSearch\ElasticQueryOperatorsManager;
use MintHCM\Utils\CustomLoader;

class ElasticQuery extends SearchQuery
{
    const DEFAULT_SORT_FIELD = "_score";
    const DEFAULT_SORT_ORDER = "asc";
    const SORT_KEYWORD = "_keyword";

    const DEFAULT_TYPE = null;

    const ALL_FIELDS = "_all";

    protected function setQuery()
    {
        $this->query = array(
            "index" => $this->getIndex(),
            "body" => $this->getBody(),
            "type" => $this->getType(),
        );
    }

    protected function setSort()
    {
        global $list_config;

        $field = !empty($this->params["sort_by"]) ? $this->params['sort_by'] : static::DEFAULT_SORT_FIELD;
        if (isset($list_config['sort_mappings'][$field])) {
            $field = $list_config['sort_mappings'][$field];
        } else if (static::DEFAULT_SORT_FIELD !== $field) {
            $field .= self::SORT_KEYWORD;
        }

        $this->sort = array(
            $field => array(
                "order" => !empty($this->params["sort_order"]) ? $this->params['sort_order'] : 'asc',
            ),
        );
    }

    private function getIndex()
    {
        if (isset($GLOBALS['sugar_config']['unique_key'])) {
            return $GLOBALS['sugar_config']['unique_key'] . '_shared';
        }
        return null;
    }

    private function getType()
    {
        return $this->params['type'] ?? static::DEFAULT_TYPE;
    }

    private function getBody()
    {
        return array(
            "query" => $this->getBodyQuery(),
            "size" => $this->size,
            "from" => $this->from,
            "sort" => $this->sort,
        );
    }

    private function getBodyQuery()
    {
        switch (strtolower($this->params['search'])) {
            case "global":
                return $this->getGlobalQuery();
                break;
            case "list":
                return $this->getListQuery();
                break;
            default:
                throw new InvalidArgumentException();
        }
    }

    private function getGlobalQuery()
    {
        $fields = !empty($this->params['fields']) ? $this->params['fields'] : array(static::ALL_FIELDS);

        return array(
            'query_string' => array(
                'query' => $this->params['query'],
                'fields' => $fields,
                'analyzer' => 'standard',
                'default_operator' => 'OR',
                'minimum_should_match' => '66%',
            ),
        );
    }

    private function getListQuery()
    {
        return (CustomLoader::getObject(ElasticQueryOperatorsManager::class, $this->params['filters'] ?? []))->getQuery();
    }

}
