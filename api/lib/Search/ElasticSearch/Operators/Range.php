<?php

namespace MintHCM\Lib\Search\ElasticSearch\Operators;

use MintHCM\Lib\Search\ElasticSearch\ElasticOperator;

class Range extends ElasticOperator
{
    const SIGNS = ['lte', 'lt', 'gt', 'gte'];

    protected $operators, $format;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->format = $data['format'] ?? false;
        $this->operators = array();
        foreach ($this::SIGNS as $sign) {
            if (!empty($data[$sign])) {
                $this->operators[$sign] = $data[$sign];
            }
        }
    }

    protected function getDataArray(): array
    {
        $response = array(
            'range' => array(
                $this->field => array(
                ),
            ),
        );

        foreach ($this->operators as $sign => $value) {
            $response['range'][$this->field][$sign] = $value;
        }

        if (!empty($this->format)) {
            $response['range'][$this->field]['format'] = $this->format;
        }

        return $response;
    }

    protected function validateData(): bool
    {
        return !empty($this->field)
        && !empty($this->operators)
        ;
    }
}
