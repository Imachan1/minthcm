<?php

namespace MintHCM\Lib\Search\ElasticSearch\Operators;

use MintHCM\Lib\Search\ElasticSearch\ElasticOperator;

class MatchOperator extends ElasticOperator
{

    protected $operator;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->operator = $data['operator'] ?? 'and';
        if (empty($this->field)) {
            $this->field = "*";
        }
    }

    protected function getDataArray(): array
    {
        return array(
            'match' => array(
                $this->field => array(
                    "query" => $this->value,
                    "operator" => $this->operator,
                ),
            ),
        );
    }

    protected function validateData(): bool
    {
        return !empty($this->field)
        && !empty($this->value)
        && !empty($this->operator)
        ;
    }
}
