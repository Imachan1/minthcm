<?php

namespace MintMCP\Tools;

use Api\V8\Helper\ModuleListProvider;
use Mcp\Types\ToolInputSchema;
use Mcp\Types\CallToolResult;
use MintMCP\Tools\Exceptions\ModuleNotAllowedException;

class GetModuleNames extends AbstractMCPTool
{

    public function getName(): string
    {
        return 'get_module_names';
    }

    public function getDescription(): string
    {
        return 'Returns the names of all available modules in the system.';
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
        ]);
    }

    /**
     * Executes the tool: returns the names and labels of all modules available to the user.
     *
     * @param object $arguments Input arguments for the tool (not used)
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        chdir('../legacy');

        $provider = new ModuleListProvider();
        $allModules = $provider->getModuleList();

        chdir('../mcp');

        $filteredModules = [];
        foreach ($allModules as $moduleName => $moduleData) {
            try {
                $this->checkPermissions($moduleName, 'list');
                $filteredModules[$moduleName] = [
                    'label' => $moduleData['label'] ?? null,
                ];
            } catch (ModuleNotAllowedException $e) {
                // Skip modules that are not allowed
                continue;
            }
        }

        // Format result as a readable list
        if (empty($filteredModules)) {
            $resultText = "No modules available for this user.";
        } else {
            $resultText = "Available modules:\n";
            foreach ($filteredModules as $name => $data) {
                $label = $data['label'] ?? '';
                $resultText .= "- {$name}" . ($label ? " ({$label})" : "") . "\n";
            }
        }

        return $this->createResult([
            $this->createTextContent($resultText)
        ]);
    }
}
