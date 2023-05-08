<?php

namespace MintHCM\Lib\Search\ElasticSearch;

use MintHCM\Lib\Search\ElasticSearch\ElasticQueryOperatorsManager;
use MintHCM\Utils\CustomLoader;

class ElasticParams
{

    protected $params;

    /**
     *
     *
     * @var array
     */
    public function __construct(array $params)
    {
        $this->setParams($params);
    }

    public function getParams()
    {
        return $this->params;
    }

    protected function setParams(array $params)
    {
        $this->params = array();
        $this->params['index'] = $this->getIndex();
        $this->params['body'] = $this->getBody($params);
        $this->params['type'] = $params['type'] ?? null;
    }

    private function getIndex()
    {
        if (isset($GLOBALS['sugar_config']['unique_key'])) {
            return $GLOBALS['sugar_config']['unique_key'] . '_shared';
        }
        return null;
    }

    protected function getBody(array &$params)
    {
        global $mint_config;

        $size = $params['items'] ?? ($mint_config['search']['default_page_size'] ?? 25);

        return array(
            "query" => (CustomLoader::getObject(ElasticQueryOperatorsManager::class, $params['filters'] ?? []))->getQuery(),
            "size" => $size,
            "from" => $params['offset'] ?? -1,
            "sort" => $this->getSort($params)
        );
    }

    protected function getSort(array $params)
    {
        global $list_config;

        $field = !empty($params["sort_by"]) ? $params['sort_by'] : '_score';
        if (isset($list_config['sort_mappings'][$field])) {
            $field = $list_config['sort_mappings'][$field];
        }
        return array(
            $field => array(
                "order" => !empty($params["sort_order"]) ? $params['sort_order'] : 'asc',
            ),
        );
    }

}
