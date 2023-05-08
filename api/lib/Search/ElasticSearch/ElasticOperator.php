<?php

namespace MintHCM\Lib\Search\ElasticSearch;

use Elasticsearch\Common\Exceptions\BadRequest400Exception;

abstract class ElasticOperator
{
    protected $field, $value, $not, $boost;

    public function __construct(array $data)
    {
        global $list_config;

        $this->field = $data['field'] ?? null;
        if (isset($list_config['fields_mappigs'][$this->field])) {
            $this->field = $list_config['fields_mappigs'][$this->field];
        }
        $this->value = $data['value'] ?? null;
        $this->not = $data['not'] ?? false;
        $this->boost = $data['boost'] ?? 1.0;
    }

    public function getData()
    {
        if (!$this->validateData()) {
            throw new BadRequest400Exception;
        }

        return $this->getDataArray();
    }

    public function getArrayKey()
    {
        if (false !== $this->not) {
            return 'must_not';
        }

        return 'filter';
    }

    abstract protected function getDataArray(): array;

    abstract protected function validateData(): bool;

}
