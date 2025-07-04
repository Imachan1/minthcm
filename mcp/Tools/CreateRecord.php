<?php

namespace MintMCP\Tools;

use Mcp\Types\ToolInputSchema;
use Mcp\Types\CallToolResult;
use MintMCP\Tools\Traits\ModuleQueryTrait;

class CreateRecord extends AbstractMCPTool
{
    use ModuleQueryTrait;

    public function getName(): string
    {
        return 'create_record';
    }

    public function getDescription(): string
    {
        return "Create a new record in MintHCM modules, for example new employees, new candidates etc. Don't use this tool for meetings. Use add_meeting for meetings.";
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'module_name' => [
                    'type' => 'string',
                    'description' => 'Name of the module in Mint in which the record is to be created.',
                ],
                'attributes' => [
                    'type' => 'object',
                    'description' => 'Attributes of new record in key-value format.',
                ],
            ],
            'required' => ['module_name', 'attributes'],
        ]);
    }

    /**
     * Executes the tool: creates a new record in the specified module.
     *
     * @param object $arguments Input arguments for the tool
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            $moduleName = $arguments->module_name;
            $attributes = (array)($arguments->attributes ?? []);

            // Prevent using this tool for meetings
            if (strtolower($moduleName) === 'meetings') {
                return $this->createResult([
                    $this->createTextContent("Error: Use the add_meeting tool to create meetings.")
                ]);
            }

            $this->checkPermissions($moduleName, 'edit');
            [$bean, $tableName, $fieldDefs] = $this->loadBeanAndDefs($moduleName);

            // Validate required fields
            $requiredFields = [];
            foreach ($fieldDefs as $field => $def) {
                if (!empty($def['required'] && $def['name'] !== 'id')) {
                    $requiredFields[] = $field;
                }
            }
            $missingFields = array_diff($requiredFields, array_keys($attributes));
            if (!empty($missingFields)) {
                return $this->createResult([
                    $this->createTextContent("Error: Missing required fields: " . implode(', ', $missingFields))
                ]);
            }

            // Set attributes on bean
            foreach ($attributes as $field => $value) {
                if (array_key_exists($field, $fieldDefs) && $this->validateFieldValue($field, $value, $fieldDefs)) {
                    $bean->$field = $value;
                }
            }

            chdir('../legacy');
            $id = $bean->save();
            chdir('../mcp');

            if ($id) {
                $recordUrl = $this->getRecordUrl($moduleName, $id);
                $result = [
                    "status" => "Record created successfully",
                    "id" => $id,
                    "url" => $recordUrl,
                ];
                return $this->createResult([
                    $this->createJsonContent($result)
                ]);
            } else {
                return $this->createResult([
                    $this->createTextContent("Error: Failed to create the record.")
                ]);
            }
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent("Error while creating record: " . $e->getMessage())
            ]);
        }
    }
}
