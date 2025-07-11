<?php

namespace MintMCP\Tools\Traits;

trait ModuleQueryTrait
{

    const NOT_ALLOWED_RELATE_TYPES = [
        'link',
        'relate',
    ];

    /**
     * Loads the bean, table name, and field definitions for a given module.
     *
     * @param string $moduleName
     * @return array [$bean, $tableName, $fieldDefs]
     */
    protected function loadBeanAndDefs(string $moduleName)
    {
        chdir('../legacy');
        $bean = \BeanFactory::getBean($moduleName);
        $tableName = $bean->table_name ?? strtolower($moduleName);
        $fieldDefs = isset($bean->field_defs) ? $bean->field_defs : [];
        chdir('../mcp');
        return [$bean, $tableName, $fieldDefs];
    }

    /**
     * Validates that all requested fields exist in the module's field definitions.
     *
     * @param array $fields
     * @param array $fieldDefs
     * @param string $moduleName
     * @throws \InvalidArgumentException
     */
    protected function validateFields(array $fields, array $fieldDefs, string $moduleName): bool
    {
        $availableFields = array_keys($fieldDefs);
        foreach ($fields as $field) {
            if (!in_array($field, $availableFields)) {
                throw new \InvalidArgumentException(
                    "Field {$field} is not available in the {$moduleName} module. " .
                        "Use get_module_fields to get list of fields available in the module."
                );
            }
        }
        return true;
    }

    /**
     * Validates the value of a field based on its definition.
     *
     * @param string $field
     * @param mixed $value
     * @param array $fieldDefs
     * @throws \InvalidArgumentException
     */
    protected function validateFieldValue(string $field, $value, array $fieldDefs): bool
    {
        if (empty($fieldDefs[$field])) {
            throw new \InvalidArgumentException("Field {$field} is not defined in the module.");
        }

        $fieldType = $fieldDefs[$field]['type'] ?? '';
        if (in_array($fieldType, self::NOT_ALLOWED_RELATE_TYPES, true)) {
            throw new \InvalidArgumentException(
                "Field {$field} of type '{$fieldType}' cannot be used in filters. " .
                    "Use the field of type 'id' and ID of the related record instead."
            );
        }

        if (preg_match('/enum/i', $fieldType)) {

            $language = $GLOBALS['current_language'] ?? 'en_us';
            $appListStrings = return_app_list_strings_language($language);
            $optionsKey = $fieldDefs[$field]['options'] ?? null;

            if ($optionsKey && !empty($appListStrings[$optionsKey])) {
                $enumValues = array_keys($appListStrings[$optionsKey]);
                $values = (array)$value;
                foreach ($values as $v) {
                    if (!in_array($v, $enumValues, true)) {
                        throw new \InvalidArgumentException("Value '{$v}' is not valid for enum field '{$field}'.");
                    }
                }
            }
        }
        return true;
    }

    /**
     * Builds a SQL WHERE clause from a JSON string of filters.
     *
     * @param string $filtersJson JSON string with filters
     * @param array $fieldDefs
     * @param string $tableName
     * @param string $operator 'and' or 'or'
     * @return string SQL WHERE clause
     * @throws \InvalidArgumentException
     */
    protected function buildWhereClause(
        string $filtersJson,
        array $fieldDefs,
        string $tableName,
        string $operator
    ): string {
        $availableFields = array_keys($fieldDefs);
        $where = [];

        if ($filtersJson) {
            $filtersArr = json_decode($filtersJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException("Invalid JSON in filters: " . json_last_error_msg());
            }
            $queryFilters = $filtersArr['filters'] ?? [];
            foreach ($queryFilters as $field => $filter) {
                if (!in_array($field, $availableFields)) {
                    throw new \InvalidArgumentException(
                        "Field {$field} is not available in the module. " .
                            "Use get_module_fields to get list of fields available in the module."
                    );
                }
                $op = strtoupper($filter['operator'] ?? '=');
                $value = $filter['value'] ?? '';

                $this->validateFieldValue($field, $value, $fieldDefs);

                $where[] = $this->buildWhereCondition($tableName, $field, $op, $value);
            }
        }

        // Always exclude deleted records
        $where[] = "$tableName.deleted = 0";

        $glue = (strtoupper($operator) === 'OR') ? ' OR ' : ' AND ';
        return implode($glue, $where);
    }

    /**
     * Builds a single SQL condition for a field, operator, and value.
     *
     * @param string $tableName
     * @param string $field
     * @param string $op
     * @param mixed $value
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function buildWhereCondition(string $tableName, string $field, string $op, $value): string
    {
        // Handle IN and NOT IN operators
        if (in_array($op, ['IN', 'NOT IN'])) {
            $valuesArr = is_array($value) ? array_map('trim', $value) : array_map('trim', explode(',', $value));
            $valueStr = "'" . implode("','", $valuesArr) . "'";
            return "$tableName.$field $op ($valueStr)";
        }

        // Handle BETWEEN operator
        if ($op === 'BETWEEN') {
            $vals = is_array($value) ? array_map('trim', $value) : array_map('trim', explode(',', $value));
            if (count($vals) !== 2) {
                throw new \InvalidArgumentException("BETWEEN operator requires two values.");
            }
            return "$tableName.$field BETWEEN '{$vals[0]}' AND '{$vals[1]}'";
        }

        // Default: simple comparison
        return "$tableName.$field $op '{$value}'";
    }
}
