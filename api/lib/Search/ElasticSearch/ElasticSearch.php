<?php

namespace MintHCM\Lib\Search\ElasticSearch;

use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\InvalidArgumentException;
use MintHCM\Lib\Search\Base\SearchManager;
use MintHCM\Lib\Search\Base\SearchResult;
use MintHCM\Lib\Search\ElasticSearch\ElasticQuery;
use MintHCM\Lib\Search\ElasticSearch\ElasticResult;

class ElasticSearch extends SearchManager
{

    protected $client, $result;

    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->setClient();
    }

    public function search($handle_acl = false): SearchResult
    {
        if (empty($this->query)) {
            throw new InvalidArgumentException();
        }
        $result = $this->client->search($this->query);
        $this->setResultManager($result, $handle_acl);
        return $this->result_manager;
    }

    public function setQuery(array $params): void
    {
        $this->params = $params;
        $this->query = (new ElasticQuery($params))->getQuery();
    }

    protected function setResultManager($result, $handle_acl): void
    {
        $this->result_manager = new ElasticResult($result, $this->params['from'], $this->params['items'], $handle_acl);
        if ($this->result_manager->shouldSearchAgain()) {
            $this->params['from'] = $this->result_manager->getNextOffset();
            $this->setQuery($this->params);
            $result = $this->client->search($this->query);
            $this->result_manager->nextSearch($result);
        }
    }

    protected function setClient()
    {
        global $mint_config;

        $hosts = $mint_config['search']['engines']['ElasticSearch'] ?? array();
        if (!is_array($hosts)) {
            throw new InvalidArgumentException();
        }

        foreach ($hosts as $host) {
            if (empty($host['host'])) {
                throw new InvalidArgumentException();
            }
        }

        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
    }

}
