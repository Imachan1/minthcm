<?php

namespace MintHCM\Lib\Search\ElasticSearch\Operators;

use MintHCM\Lib\Search\ElasticSearch\ElasticOperator;

class Wildcard extends ElasticOperator
{

    public function __construct(array $data)
    {
        parent::__construct($data);
        if (empty($this->field)) {
            $this->field = "*";
        }
    }

    protected function getDataArray(): array
    {
        return array(
            'wildcard' => array(
                $this->field => array(
                    "value" => $this->value,
                    "boost" => $this->boost ?? 1.0,
                ),
            ),
        );
    }

    protected function validateData(): bool
    {
        return !empty($this->field)
        && !empty($this->value)
            && (
            empty($this->boost) || is_float($this->boost)
        )
        ;
    }
}
