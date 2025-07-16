<?php

namespace MintMCP\Tools;

use DBManagerFactory;
use Mcp\Types\CallToolResult;
use Mcp\Types\ToolInputSchema;
use MintMCP\Tools\Traits\ModuleQueryTrait;

class CountRecords extends AbstractMCPTool
{
    use ModuleQueryTrait;

    public function getName(): string
    {
        return 'count_records';
    }

    public function getDescription(): string
    {
        return 'Count the number of records in a MintHCM module. Always use get_module_fields first if you are unsure about the available fields for filtering.';
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'module_name' => [
                    'type' => 'string',
                    'description' => 'Name of the module in Mint in which the information is to be counted.',
                ],
                'filters' => [
                    'type' => 'string',
                    'description' => 'JSON with filters to apply to the query. Example: { "filters": { "date_start": { "operator": ">", "value": "2022-01-01" }, "assigned_user_id": { "operator": "=", "value": "1" } } }.
                                        Important: Use get_module_fields to get available fields in the module. Also you cannot use fields of type "link" or "relate" in filters, instead use the ID of the related record.',
                ],
                'operator' => [
                    'type' => 'string',
                    'enum' => ['and', 'or'],
                    'description' => "Operator to use to join all filters. Possible values: 'and','or'. Defaults to 'and'.",
                    'default' => 'and',
                ],
            ],
            'required' => ['module_name'],
        ]);
    }

    /**
     * Executes the count operation.
     *
     * @param object $arguments Input arguments for the tool
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            $this->checkPermissions($arguments->module_name);

            [$bean, $tableName, $fieldDefs] = $this->loadBeanAndDefs($arguments->module_name);
            $filters = $arguments->filters ?? '';
            $operator = $arguments->operator ?? 'and';
            $where = $this->buildWhereClause($filters, $fieldDefs, $tableName, $operator);

            chdir('../legacy');
            $query = $bean->create_new_list_query('', $where);
            $countQuery = $bean->create_list_count_query($query);
            $db = DBManagerFactory::getInstance();
            $result = $db->query($countQuery);
            $row = $db->fetchByAssoc($result);
            chdir('../mcp');

            $count = isset($row['c']) ? (int)$row['c'] : 0;
            $msg = "Count for module '{$arguments->module_name}': $count";
            return $this->createResult([
                $this->createTextContent($msg)
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent("Error while counting records: " . $e->getMessage())
            ]);
        }
    }
}
