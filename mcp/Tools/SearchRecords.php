<?php

namespace MintMCP\Tools;

use MintMCP\Tools\Traits\ModuleQueryTrait;
use Mcp\Types\ToolInputSchema;
use Mcp\Types\CallToolResult;

class SearchRecords extends AbstractMCPTool
{
    use ModuleQueryTrait;

    public function getName(): string
    {
        return 'search_records';
    }

    public function getDescription(): string
    {
        return 'Retrieve a list of records from a MintHCM module matching given filters. You should use get_module_names to get available modules and get_module_fields to get fields available in the module.';
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'module_name' => [
                    'type' => 'string',
                    'description' => 'Name of the module in Mint in which the information is to be read.',
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
                'fields' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                    'description' => "List of fields to retrieve from the module. Example: ['id','name','date_start','status']."
                ],
            ],
            'required' => ['module_name', 'fields'],
        ]);
    }

    /**
     * Executes the tool: retrieves records from a module based on filters and selected fields.
     *
     * @param object $arguments Input arguments for the tool
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            $this->checkPermissions($arguments->module_name);

            [$bean, $tableName, $fieldDefs] = $this->loadBeanAndDefs($arguments->module_name);

            $fields = $arguments->fields ?? [];
            $this->validateFields($fields, $fieldDefs, $arguments->module_name);

            $whereClause = $this->buildWhereClause(
                $arguments->filters ?? '',
                $fieldDefs,
                $tableName,
                $arguments->operator ?? 'and'
            );

            chdir('../legacy');
            $list = $bean->get_full_list('', $whereClause);
            chdir('../mcp');

            $returnData = $this->formatRecords($list, $fields);

            $result = empty($returnData)
                ? ["status" => "success", "message" => "No records found in module {$arguments->module_name} with given filters"]
                : ["status" => "success", "data" => $returnData];

            return $this->createResult([
                $this->createJsonContent($result)
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent("Error while searching records: " . $e->getMessage())
            ]);
        }
    }

    /**
     * Formats the list of beans into an array of associative arrays with selected fields.
     *
     * @param array|null $list List of beans/records
     * @param array $fields Fields to include in the result
     * @return array
     */
    protected function formatRecords($list, array $fields): array
    {
        $returnData = [];
        if ($list) {
            foreach ($list as $row) {
                $record = ['id' => $row->id];
                foreach ($fields as $field) {
                    if ($field !== 'id') {
                        $record[$field] = $row->$field ?? null;
                    }
                }
                $returnData[] = $record;
            }
        }
        return $returnData;
    }
}
