<?php

namespace MintHCM\Lib\Search\ElasticSearch;

use ACLController;
use BeanFactory;
use DBManagerFactory;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\InvalidArgumentException;
use MintHCM\Lib\Search\ElasticSearch\ElasticParams;
use MintHCM\Lib\Search\SearchManager;
use MintHCM\Utils\CustomLoader;
use SecurityGroup;

class ElasticSearch extends SearchManager
{
    protected $index;

    protected $client;

    /** @var ElasticParams */
    protected $params;

    protected $result;

    protected $size;

    protected $hits, $search_data;

    public function __construct()
    {
        $this->setClient();
    }

    public function getResult(): array
    {
        return $this->hits;
    }

    public function getResultBeans($group_by_type = false): array
    {
        $beans = $this->getHitsAsBeans();

        $size = $this->size;
        $size_with_one_more = $this->params['body']['size'];

        $total = $this->result["hits"]["total"];
        $result_amount = count($this->result["hits"]["hits"]);

        $offset = -1 === $this->params['body']['from'] ? 0 : $this->params['body']['from'];
        $next_page_exists = count($beans) > $size ? true : false;
        $next_offset = $offset + $result_amount;

        while (count($beans) < $size_with_one_more && $next_offset < $total) {
            $this->params['body']['from'] = $next_offset;
            $this->search();

            $current_amount = count($this->result["hits"]["hits"]);
            $result_amount += $current_amount;
            $offset = $next_offset;
            $beans = array_merge($beans, $this->getHitsAsBeans());
            if (count($beans) > $size) {
                $next_page_exists = true;
            }
            $next_offset = $offset + $current_amount;

        }

        $next_offset = $this->getNextOffset($beans, $offset);
        $beans = array_slice($beans, 0, $size);

        return array(
            'size' => $size,
            'total' => $total,
            'next_page_exists' => $next_page_exists,
            "offset" => $this->params['body']['from'],
            "next_offset" => $next_offset,
            'beans' => $beans,
        );
    }

    public function execute(array $params): void
    {
        $parsed_params = (CustomLoader::getObject(ElasticParams::class, $params))->getParams();
        $this->setParams($parsed_params);
        $this->search();
    }

    protected function setParams(array $params)
    {
        $this->size = $params["body"]["size"];
        $params["body"]["size"] += 1;
        $this->params = $params;
    }

    protected function search()
    {
        $this->result = $this->client->search($this->params);
        $this->setHits();
    }

    protected function getNextOffset($beans, $offset)
    {
        $bean_id = end($beans)->id;
        $next_offset = count($beans) === $this->params['body']['size'] ? $offset : $offset + 1;
        foreach ($this->result["hits"]["hits"] as $key => $hit) {
            if ($hit['_id'] === $bean_id) {
                $next_offset += $key;
            }
        }
        return $next_offset;

    }

    protected function getHitsAsBeans()
    {
        $response = array();

        foreach ($this->hits as $module => $ids) {
            $handled_ids = $this->handleSG($ids, $module);
            foreach ($handled_ids as $id) {
                chdir('../legacy/');
                $bean = BeanFactory::getBean($module, $id);
                chdir('../api/');

                if (empty($bean->id)) {
                    continue;
                }

                chdir('../legacy/');
                $bean->load_relationships();
                $bean->acl_access = [
                    'edit' => $bean->ACLAccess('edit'),
                    'view' => $bean->ACLAccess('view'),
                    'delete' => $bean->ACLAccess('delete'),
                ];
                chdir('../api/');

                $owner = $this->handleOwner($bean);
                if (!$owner) {
                    continue;
                }

                $response[] = $bean;
            }
        }
        return $response;
    }

    protected function handleOwner($bean)
    {
        global $current_user;

        $response = true;
        chdir('../legacy/');
        if ($bean->bean_implements('ACL') && ACLController::requireOwner($bean->module_dir, 'list')) {
            $is_owner = $bean->isOwner($current_user->id);
            if (!$is_owner) {
                $response = false;
            }
        }
        chdir('../api/');
        return $response;
    }

    protected function handleSG($ids, $module)
    {
        global $current_user;

        if (count($ids) == 0) {
            return $ids;
        }
        chdir('../legacy/');
        $obj = \BeanFactory::newBean($module);
        if (!$obj->bean_implements('ACL') || !ACLController::requireSecurityGroup($obj->module_dir, 'list')) {
            chdir('../api/');
            return $ids;
        }

        require_once 'modules/SecurityGroups/SecurityGroup.php';

        $where = SecurityGroup::getGroupWhere($obj->table_name, $obj->module_dir, $current_user->id);
        $owner_where = $obj->getOwnerWhere($current_user->id);
        if (!empty($owner_where)) {
            $where = empty($where) ? $owner_where : " ({$owner_where} OR {$group_where}) ";
        }

        $query = "SELECT id FROM {$obj->table_name} WHERE {$where} AND {$obj->table_name}.id IN ('" . implode("','", $ids) . "')";
        $db = DBManagerFactory::getInstance();
        $result = $db->query($query);
        $parsed_ids = array();
        while ($row = $db->fetchByAssoc($result)) {
            $parsed_ids[] = $row['id'];
        }
        chdir('../api/');

        $response = array();
        foreach ($ids as $id) {
            if (in_array($id, $parsed_ids)) {
                $response[] = $id;
            }
        }

        return $parsed_ids;
    }

    protected function setHits()
    {
        $this->hits = array();
        if (empty($this->result)) {
            return;
        }

        foreach ($this->result["hits"]["hits"] as $hit) {
            $this->hits[$hit["_type"]][] = $hit['_id'];
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
