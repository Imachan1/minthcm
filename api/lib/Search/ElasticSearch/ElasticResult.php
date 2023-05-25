<?php

namespace MintHCM\Lib\Search\ElasticSearch;

use MintHCM\Data\BeanFactory;
use MintHCM\Data\MintBean;
use MintHCM\Lib\Search\Base\SearchResult;

class ElasticResult extends SearchResult
{
    protected $next_offset, $next_page_exists;

    public function __construct($result, $current_offset, $size, $handle_acl = false)
    {
        parent::__construct($result, $current_offset, $size, $handle_acl);
        $this->setNextData();
    }

    public function shouldSearchAgain(): bool
    {
        return $this->next_offset < $this->total && count($this->hits) <= $this->size;
    }

    public function getNextOffset()
    {
        return $this->next_offset;
    }

    public function getNextPageExists()
    {
        return $this->next_page_exists;
    }

    public function nextSearch($result)
    {
        $beans = $this->beans;
        $hits = $this->hits;

        $this->beans = null;

        $this->setData($result, $this->next_offset);

        $this->hits = array_merge($hits, $this->hits);
        $this->beans = isset($beans) ? array_merge($beans, $this->beans) : $this->beans;

        $this->setNextData();
    }

    protected function setNextOffset()
    {
        $last_id = count($this->hits) > $this->size ? $this->hits[$this->size - 1]['id'] : end($this->hits)['id'];
        $next_offset = $this->current_offset;
        if (null !== $last_id && count($this->hits) > $this->size) {
            foreach ($this->result["hits"]["hits"] as $key => $hit) {
                $next_offset += 1;
                if ($hit['_id'] === $last_id) {
                    break;
                }
            }
        } else {
            $next_offset += $this->size;
        }
        $this->next_offset = $next_offset;
    }

    protected function setNextData(): void
    {
        $this->setNextOffset();
        $this->next_page_exists = count($this->hits) > $this->size;
    }

    protected function setResultGroupedIds(): void
    {
        $this->grouped_ids = array();
        if (empty($this->result)) {
            return;
        }

        foreach ($this->result["hits"]["hits"] as $hit) {
            $this->grouped_ids[$hit["_type"]][] = $hit['_id'];
        }
    }

    protected function setHits(): void
    {
        $this->hits = array();
        if (empty($this->result)) {
            return;
        }

        foreach ($this->result["hits"]["hits"] as $hit) {
            if (!in_array($hit['_id'], $this->grouped_ids[$hit["_type"]] ?? array())) {
                continue;
            }
            $this->hits[] = array(
                'id' => $hit['_id'],
                'module' => $hit["_type"],
            );
        }
    }

    protected function setBeans(): void
    {
        $this->beans = array();

        $beans_unsorted = array();
        foreach ($this->grouped_ids as $module => $ids) {
            $focus = BeanFactory::newBean($module);
            $beans = $focus->get_full_list('', " {$focus->table_name}.id IN ('" . implode("','", $ids) . "')");

            foreach ($beans as $bean) {
                $bean = new MintBean($bean);
                if (empty($bean->id)) {
                    continue;
                }

                $bean->load_relationships();
                $bean->acl_access = [
                    'edit' => $bean->ACLAccess('edit'),
                    'view' => $bean->ACLAccess('view'),
                    'delete' => $bean->ACLAccess('delete'),
                ];

                $beans_unsorted[] = $bean;
            }
        }

        foreach ($this->result["hits"]["hits"] as $hit) {
            if (!in_array($hit['_id'], $this->grouped_ids[$hit["_type"]] ?? array())) {
                continue;
            }
            $id = $hit['_id'];
            $bean = array_filter($beans_unsorted, function ($bean) use ($id) {
                return $bean->id === $id;
            });

            $bean = reset($bean) ?? null;
            if (empty($bean)) {
                continue;
            }

            $this->beans[] = $bean;
        }

    }

}
