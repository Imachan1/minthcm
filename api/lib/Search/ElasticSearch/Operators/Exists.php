<?php

namespace MintHCM\Lib\Search\ElasticSearch\Operators;

use MintHCM\Lib\Search\ElasticSearch\ElasticOperator;

class Exists extends ElasticOperator
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    protected function getDataArray(): array
    {
        return array(
            'exists' => array(
                "field" => $this->field,
            ),
        );
    }

    protected function validateData(): bool
    {
        return !empty($this->field)
        ;
    }
}
