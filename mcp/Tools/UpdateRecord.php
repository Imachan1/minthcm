<?php

namespace MintMCP\Tools;

use Mcp\Types\ToolInputSchema;
use Mcp\Types\CallToolResult;
use MintMCP\Tools\Traits\ModuleQueryTrait;

class UpdateRecord extends AbstractMCPTool
{
    use ModuleQueryTrait;

    public function getName(): string
    {
        return 'update_record';
    }

    public function getDescription(): string
    {
        return "Update a record in MintHCM module.";
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'module_name' => [
                    'type' => 'string',
                    'description' => 'Name of the module in Mint in which the record is to be updated.',
                ],
                'id' => [
                    'type' => 'string',
                    'description' => 'ID number of the record to update.',
                ],
                'attributes' => [
                    'type' => 'object',
                    'description' => "Attributes to update in key-value format. e.g. {'description': 'New Description'}",
                ],
            ],
            'required' => ['module_name', 'id', 'attributes'],
        ]);
    }

    /**
     * Executes the tool: updates a record in the specified module.
     *
     * @param object $arguments Input arguments for the tool
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            $moduleName = $arguments->module_name;
            $recordId = $arguments->id;
            $attributes = (array)($arguments->attributes ?? []);

            $this->checkPermissions($moduleName, 'edit');
            [$bean, $tableName, $fieldDefs] = $this->loadBeanAndDefs($moduleName);

            chdir('../legacy');
            $record = $bean->retrieve($recordId);
            if (!$record || empty($record->id)) {
                throw new \Exception("Record with ID {$recordId} not found in module {$moduleName}.");
            }

            // Update attributes
            foreach ($attributes as $field => $value) {
                if (array_key_exists($field, $fieldDefs)) {
                    $record->$field = $value;
                }
            }

            $id = $record->save();
            chdir('../mcp');

            if (empty($id)) {
                throw new \Exception("Failed to update record with ID {$recordId} in module {$moduleName}.");
            }

            $recordUrl = $this->getRecordUrl($moduleName, $recordId);

            $result = [
                "status" => "success",
                "url" => $recordUrl,
            ];

            return $this->createResult([
                $this->createJsonContent($result)
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent("Error while updating record: " . $e->getMessage())
            ]);
        }
    }
}
