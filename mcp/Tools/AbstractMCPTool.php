<?php

namespace MintMCP\Tools;

use ACLController;
use Mcp\Types\Tool;
use Mcp\Types\CallToolResult;
use Mcp\Types\TextContent;
use Mcp\Types\ToolInputSchema;

abstract class AbstractMCPTool {
    
    abstract public function getName(): string;
    abstract public function getDescription(): string;
    abstract public function getInputSchema(): ToolInputSchema;
    abstract public function execute($arguments): CallToolResult;
    
    /**
     * Creates a Tool object for MCP
     */
    public function createMCPTool(): Tool {
        $name = $this->getName();
        $description = $this->getDescription();
        $inputSchema = $this->getInputSchema();
        $tool = new Tool(
            name: $name,
            inputSchema: $inputSchema,
            description: $description
        );

        return $tool;
    }
    
    /**
     * Checks user permissions for a module
     */
    protected function checkPermissions(string $module): bool {

        return true; 
        /* TODO
        global $current_user;
        
        if (!$current_user || !$current_user->id) {
            throw new \Exception('User not authenticated');
        }
        
        // Check permissions in MintHCM
        if (!ACLController::checkAccess($module, 'list', true)) {
            throw new \Exception("Insufficient permissions for module: {$module}");
        }
        
        return true;
        */
    }
    
    /**
     * Helper to create TextContent
     */
    protected function createTextContent(string $text): TextContent {
        $content = new TextContent($text);
        return $content;
    }
    
    /**
     * Helper to create CallToolResult
     */
    protected function createResult(array $content): CallToolResult {
        $result = new CallToolResult($content);
        return $result;
    }
}