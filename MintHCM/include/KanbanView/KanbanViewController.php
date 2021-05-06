<?php

class KanbanViewController
{
    protected $bean;

    public function __construct($bean)
    {
        $this->bean = $bean;
        $this->defs = $this->loadDefs();
    }

    protected function loadDefs()
    {
        $defs = [];
        require_once 'include/MVC/View/views/view.kanban.php';
        $kv = new ViewKanban();
        $kv->type = 'kanban';
        $kv->module = $this->bean->module_name;
        $metadataFile = $kv->getMetaDataFile();
        if (file_exists($metadataFile)) {
            include $metadataFile;
            $defs = $kanbanViewDefs[$this->bean->module_name];
        }
        return $defs;
    }

    public function getItems()
    {
        $items = $this->getEmptyColumnsArray();
        $order_by = $this->getOrderBy();
        $where = $this->getWhere();
        $records  = $this->bean->get_full_list($order_by, $where);
        if($records == null){
            $where = $this->getWhereWithTable();
            $records  = $this->bean->get_full_list($order_by, $where);  
        }
        foreach ($records as $bean) {
            $items[$bean->{$this->defs['columns_field']}][] = $this->beanToArray($bean);
        }
        echo json_encode($items);
    }

    protected function beanToArray($bean)
    {
        return array_merge(
            $bean->toArray(true),
            [
                'module_name' => $bean->module_name,
                'editable' => $bean->ACLAccess('edit'),
                'detailview' =>  $bean->ACLAccess('view'),
            ]
        );
    }

    protected function getOrderBy()
    {
        return !empty($this->defs['order_field']) ? $this->defs['order_field'] . ' ASC' : '';
    }

    protected function getWhere()
    {
        
        return !empty($this->defs['black_list']) ? "({$this->defs['columns_field']} NOT IN ('" . implode("','", $this->defs['black_list']) . "'))" : "";
    }

    protected function getWhereWithTable()
    {
        return !empty($this->defs['black_list']) ? "({$this->bean->table_name}.{$this->defs['columns_field']} NOT IN ('" . implode("','", $this->defs['black_list']) . "'))" : "";
    }
    protected function getEmptyColumnsArray()
    {
        return array_fill_keys(
            array_diff(
                array_keys($this->defs['columns']),
                $this->defs['black_list'] ?: []
            ),
            []
        );
    }

    public function saveItem($args)
    {
        $result = false;
        if (!empty($args['module']) && !empty($args['id']) && !empty($args['fields'])) {
            $bean = BeanFactory::getBean($args['module'], $args['id']);
            if ($bean && !empty($bean->id)) {
                foreach ($args['fields'] as $field => $value) {
                    if (array_key_exists($field, $bean->field_defs)) {
                        if (!isset($bean->from_kanban)) {
                            $bean->from_kanban = true;
                        }
                        $bean->{$field} = $value;
                    }
                }
                if ($bean->save()) {
                    $result = json_encode($this->beanToArray($bean));
                }
            }
        }
        echo $result;
    }

    public function shouldReorder()
    {
        return !empty($this->defs['order_field']) && ($this->isNewRecord() || $this->statusChanged() || $this->orderChanged());
    }

    public function reorder()
    {
        $sql = $this->getReorderSQLs($this->bean->{$this->defs['columns_field']});
        if ($this->statusChanged()) {
            array_push(
                $sql,
                ...$this->getReorderSQLs($this->bean->fetched_row[$this->defs['columns_field']])
            );
        }
        $this->bean->db->query($sql);
    }

    protected function statusChanged()
    {
        return !empty($this->bean->fetched_row) && ($this->bean->fetched_row[$this->defs['columns_field']] != $this->bean->{$this->defs['columns_field']});
    }

    protected function orderChanged()
    {
        return !empty($this->bean->fetched_row) && ($this->bean->fetched_row[$this->defs['order_field']] != $this->bean->{$this->defs['order_field']});
    }

    protected function isNewRecord()
    {
        return empty($this->bean->fetched_row);
    }

    protected function getReorderSQLs($status)
    {
        return [
            "SET @ORDER = 0",
            "UPDATE {$this->bean->table_name}
            SET {$this->defs['order_field']} = (@ORDER := @ORDER + 1)
            WHERE {$this->defs['columns_field']} = '{$status}' AND deleted = 0
            ORDER BY {$this->defs['order_field']} ASC, date_modified " . $this->getDateModifiedOrderBy(),
        ];
    }

    protected function getDateModifiedOrderBy()
    {
        return (!$this->statusChanged() && ($this->bean->fetched_row[$this->defs['order_field']] < $this->bean->{$this->defs['order_field']})) ? 'ASC' : 'DESC';
    }

    public function setOrderFieldNull()
    {
        $this->bean->{$this->defs['order_field']} = '';
    }


}
