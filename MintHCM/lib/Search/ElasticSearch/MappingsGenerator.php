<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

use Symfony\Component\Yaml\Yaml;

class MappingsGenerator
{
    protected $metadata_file = 'eslistviewdefs.php';
    protected $output_file_path = 'lib/Search/ElasticSearch/defaultParams.yml';
    protected $not_standard_fields = [
        'name',
        'first_name',
        'last_name',
        'date_entered',
        'created_by',
        'date_modified',
        'modified_user_id',
        'assigned_user_id',
        'modified_by_name',
        'created_by_name',
        'assigned_user_name',
        'phone_mobile',
        'primary_address_city',
        'primary_address_state',
        'primary_address_postalcode',
        'primary_address_street',
        'primary_address_country',
    ];
    protected $types = [
        'date' => [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss||yyyy-MM-dd'
        ],
        'text' => [
            'type' => 'text',
            'fields' => [
                'keyword' => [
                    'type' => 'keyword',
                    'ignore_above' => 256
                ]
            ]
                ],
        'boolean' => [
            'type' => 'boolean'
        ],
        'long' => [
            'type' => 'long'
        ]
    ];

    protected function getModulesWithElastic()
    {
        global $beanList;
        $modulesWithElastic = [];
        foreach ($beanList as $module => $value) {
            if (file_exists("custom/modules/{$module}/metadata/{$this->metadata_file}")) {
                $data = [
                    'module' => $module,
                    'path' => "custom/modules/{$module}/metadata/{$this->metadata_file}",
                ];
                array_push($modulesWithElastic, $data);
            } else if (file_exists("modules/{$module}/metadata/{$this->metadata_file}")) {
                $data = [
                    'module' => $module,
                    'path' => "modules/{$module}/metadata/{$this->metadata_file}",
                ];
                array_push($modulesWithElastic, $data);
            }
        }

        return $modulesWithElastic;
    }

    public function generateMappings()
    {
        $modulesWithElastic = $this->getModulesWithElastic();
        $mappings = [];
        foreach ($modulesWithElastic as $module) {
            include $module['path'];
            $bean = BeanFactory::newBean($module['module']);
            $data = $ESListViewDefs[$module['module']];
            $fields_to_map = $this->setFieldsToMap($data);
            $defs = $bean->field_defs;
            $key = $data['es_module'] ? $data['es_module'] : $module['module'];

            foreach ($fields_to_map as $field) {
                if (in_array($field, $this->not_standard_fields)) {
                    $mappings = $this->handleNotStandardField($field, $mappings, $key);
                } else {
                    if (in_array($defs[$field]['type'], ['date', 'datetime', 'datetimecombo'])) {
                        $mappings['mappings'][$key]['properties'][$field] = $this->types['date'];
                    } else if ('bool' == $defs[$field]['type']) {
                        $mappings['mappings'][$key]['properties'][$field] = $this->types['boolean'];
                    } else {
                        $mappings['mappings'][$key]['properties'][$field] = $this->types['text'];
                    }
                }
            }
        }

        $this->parseMappingsToYaml($mappings);
    }

    protected function parseMappingsToYaml($mappings) {
        $yaml = Yaml::dump($mappings, 10, 2);
        file_put_contents($this->output_file_path, $yaml);
    }

    protected function handleNotStandardField($field, $mappings, $key)
    {
        if (strpos($field, 'address')) {
            $address = explode('_', $field);
            $mappings['mappings'][$key]['properties']['address']['properties'][$address[0]]['properties'][$address[2]] = $this->types['text'];
        } elseif ('phone_mobile' == $field) {
            $mappings['mappings'][$key]['properties']['phone']['properties']['mobile'] = $this->types['text'];
        }

        return $mappings;
    }

    protected function setFieldsToMap($data)
    {
        $fields_to_map = [];
        $columns = array_map('strtolower', array_keys($data['columns'] ? $data['columns'] : []));
        $search = array_map('strtolower', array_keys($data['search'] ? $data['search'] : []));

        $fields_to_map = array_unique(array_merge($columns, $search));

        return $fields_to_map;
    }
}
