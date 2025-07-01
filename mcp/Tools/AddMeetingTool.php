<?php

namespace MintMCP\Tools;

use Mcp\Types\CallToolResult;
use Mcp\Types\ToolInputSchema;

class AddMeetingTool extends AbstractMCPTool {
    
    public function getName(): string {
        return 'add_meeting';
    }
    
    public function getDescription(): string {
        return 'Adds a new meeting to the MintHCM system';
    }
    
    public function getInputSchema(): ToolInputSchema {
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'name' => [
                    'type' => 'string',
                    'description' => 'Meeting name',
                    'maxLength' => 255
                ],
                'description' => [
                    'type' => 'string',
                    'description' => 'Meeting description'
                ],
                'date_start' => [
                    'type' => 'string',
                    'description' => 'Start date and time (YYYY-MM-DD HH:MM:SS)',
                    'format' => 'date-time'
                ],
                'date_end' => [
                    'type' => 'string',
                    'description' => 'End date and time (YYYY-MM-DD HH:MM:SS)',
                    'format' => 'date-time'
                ],
                'location' => [
                    'type' => 'string',
                    'description' => 'Meeting location',
                    'maxLength' => 50
                ],
                'duration_hours' => [
                    'type' => 'integer',
                    'description' => 'Duration in hours',
                    'minimum' => 0
                ],
                'duration_minutes' => [
                    'type' => 'integer',
                    'description' => 'Duration in minutes',
                    'minimum' => 0,
                    'maximum' => 59
                ]
            ],
            'required' => ['name', 'date_start']
        ]);
    }
    
    public function execute($arguments): CallToolResult {
        try {
            $this->checkPermissions('Meetings');
            $this->validateArguments($arguments);
            
            chdir('../legacy');
            $meeting = $this->createMeeting($arguments);
            $meetingId = $meeting->save();
            chdir('../mcp');
            
            if ($meetingId) {
                $resultText = $this->formatSuccessMessage($meeting, $meetingId);
                return $this->createResult([
                    $this->createTextContent($resultText)
                ]);
            } else {
                throw new \Exception("Failed to save the meeting");
            }
            
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent("❌ Error while creating the meeting: " . $e->getMessage())
            ]);
        }
    }
    
    private function validateArguments($arguments): void {
        if (empty($arguments->name)) {
            throw new \InvalidArgumentException("Meeting name is required");
        }
        if (empty($arguments->date_start)) {
            throw new \InvalidArgumentException("Start date is required");
        }
    }
    
    private function createMeeting($arguments) {
        $meeting = \BeanFactory::getBean('Meetings');
        
        $meeting->name = $arguments->name;
        $meeting->description = $arguments->description ?? '';
        $meeting->assigned_user_id = $arguments->assigned_user_id ?? $GLOBALS['current_user']->id;
        $meeting->location = $arguments->location ?? '';
        $meeting->password = $arguments->password ?? '';
        $meeting->join_url = $arguments->join_url ?? '';
        $meeting->host_url = $arguments->host_url ?? '';
        $meeting->displayed_url = $arguments->displayed_url ?? '';
        $meeting->creator = $arguments->creator ?? $GLOBALS['current_user']->user_name;
        $meeting->external_id = $arguments->external_id ?? '';
        $meeting->duration_hours = $arguments->duration_hours ?? 1;
        $meeting->duration_minutes = $arguments->duration_minutes ?? 0;
        $meeting->date_start = $arguments->date_start;
        
        // Calculate date_end if not provided
        if (empty($arguments->date_end)) {
            $startTime = strtotime($arguments->date_start);
            $duration = ($meeting->duration_hours * 3600) + ($meeting->duration_minutes * 60);
            $meeting->date_end = date('Y-m-d H:i:s', $startTime + $duration);
        } else {
            $meeting->date_end = $arguments->date_end;
        }

        $id = $meeting->save();
        if (!$id) {
            throw new \Exception("Failed to create the meeting");
        }
        
        return $meeting;
    }
    
    private function formatSuccessMessage($meeting, string $meetingId): string {
        $resultText = "**Meeting has been successfully created!**\n\n";
        $resultText .= "ID: " . $meetingId . "\n";
        $resultText .= "Name: " . $meeting->name . "\n";
        $resultText .= "Description: " . $meeting->description . "\n";
        $resultText .= "Assigned User: " . ($meeting->assigned_user_name ?? '') . " (" . $meeting->assigned_user_id . ")\n";
        $resultText .= "Location: " . $meeting->location . "\n";
        $resultText .= "Start: " . $meeting->date_start . "\n";
        $resultText .= "End: " . $meeting->date_end . "\n";
        $resultText .= "Duration: " . $meeting->duration_hours . "h " . $meeting->duration_minutes . "m\n";
        $resultText .= "Status: " . ($meeting->status) . "\n";
        $resultText .= "Join URL: " . ($meeting->join_url ?? '') . "\n";
        $resultText .= "Creator: " . ($meeting->creator ?? $GLOBALS['current_user']->user_name) . "\n";
        $resultText .= "Modified: " . $meeting->date_modified . "\n";
        
        return $resultText;
    }
}