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
        'name' => 'name.name',
        'first_name' => 'name.first',
        'last_name' => 'name.last',
        'date_entered' => 'meta.created.date',
        'created_by' => 'meta.created.user_id',
        'date_modified' => 'meta.modified.date',
        'modified_user_id' => 'meta.modified.user_id',
        'assigned_user_id' => 'meta.assigned.user_id',
        'modified_by_name' => 'meta.modified.user_name',
        'created_by_name' => 'meta.created.user_name',
        'assigned_user_name' => 'meta.assigned.user_name',
        'primary_address_city' => 'address.primary.city',
        'primary_address_state' => 'address.primary.state',
        'primary_address_postalcode' => 'address.primary.postalcode',
        'primary_address_street' => 'address.primary.street',
        'primary_address_country' => 'address.primary.country',
        'phone_mobile' => '',
    ];
    protected $types = [
        'date' => [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss||yyyy-MM-dd',
        ],
        'text' => [
            'type' => 'text',
            'fields' => [
                'keyword' => [
                    'type' => 'keyword',
                    'ignore_above' => 256,
                ],
            ],
        ],
        'boolean' => [
            'type' => 'boolean',
        ],
        'long' => [
            'type' => 'long',
        ],
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
                if (!empty($this->not_standard_fields[$field])) {
                    $mappings = $this->handleNotStandardField($this->not_standard_fields[$field], $mappings, $key);
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

    protected function parseMappingsToYaml($mappings)
    {
        $yaml = Yaml::dump($mappings, 10, 2);
        file_put_contents($this->output_file_path, $yaml);
    }

    protected function handleNotStandardField($es_field, $mappings, $key)
    {
        $es_field_parts = explode('.', $es_field);
        $count = count($es_field_parts);
        $sub_mappings = &$mappings['mappings'][$key];
        foreach ($es_field_parts as $es_field_part) {
            if (!isset($sub_mappings['properties'][$es_field_part])) {
                $sub_mappings['properties'][$es_field_part] = [];
            }
            $sub_mappings = &$sub_mappings['properties'][$es_field_part];
            $count--;
            if ($count == 0) {
                $sub_mappings = $this->types['text'];
            }
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
