<?php

namespace MintHCM\Lib\Search\ElasticSearch\Operators;

use MintHCM\Lib\Search\ElasticSearch\ElasticOperator;

class Equals extends ElasticOperator
{

    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    protected function getDataArray(): array
    {
        return array(
            'term' => array(
                $this->field => $this->value,
            ),
        );
    }

    protected function validateData(): bool
    {
        return !empty($this->field)
        && !empty($this->value)
        ;
    }
}
