<?php

namespace MintMCP\Tools;

use Mcp\Types\ToolInputSchema;

class ListMeetingsTool extends AbstractMCPTool
{

    public function getName(): string
    {
        return 'list_meetings';
    }

    public function getDescription(): string
    {
        return 'Retrieves a list of meetings from the MintHCM system.';
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'limit' => [
                    'type' => 'integer',
                    'description' => 'Maximum number of meetings to retrieve',
                    'default' => 20
                ],
                'offset' => [
                    'type' => 'integer',
                    'description' => 'Offset for pagination',
                    'default' => 0
                ],
                'search' => [
                    'type' => 'string',
                    'description' => 'Search phrase in meeting name'
                ],
                'date_from' => [
                    'type' => 'string',
                    'description' => 'Start date (YYYY-MM-DD)',
                    'format' => 'date'
                ],
                'date_to' => [
                    'type' => 'string',
                    'description' => 'End date (YYYY-MM-DD)',
                    'format' => 'date'
                ]
            ]
        ]);
    }

    public function execute($arguments): \Mcp\Types\CallToolResult
    {
        try {
            chdir('../legacy');
            $this->checkPermissions('Meetings');

            $searchParams = [
                'module' => 'Meetings',
                'limit' => $arguments->limit ?? 20,
                'offset' => $arguments->offset ?? 0,
            ];

            $where = $this->buildWhereClause($arguments);
            if (!empty($where)) {
                $searchParams['where'] = implode(' AND ', $where);
            }

            $meetings = $this->getMeetingsList($searchParams);

            chdir('../mcp');

            $resultText = $this->formatMeetings($meetings['list'] ?? []);

            return $this->createResult([
                $this->createTextContent($resultText)
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent("Error while retrieving the list of meetings: " . $e->getMessage())
            ]);
        }
    }

    private function formatMeetings(array $meetings): string
    {
        if (empty($meetings)) {
            return "No meetings found.";
        }

        $resultText = "Found " . count($meetings) . " meetings:\n\n";
        foreach ($meetings as $meeting) {
            $resultText .= "ID: " . $meeting->id . "\n" .
                "Name: " . $meeting->name . "\n" .
                "Description: " . $meeting->description . "\n" .
                "Assigned User: " . ($meeting->assigned_user_name ?? '') . " (" . $meeting->assigned_user_id . ")\n" .
                "Location: " . $meeting->location . "\n" .
                "Start: " . $meeting->date_start . "\n" .
                "End: " . $meeting->date_end . "\n" .
                "Duration: " . $meeting->duration_hours . "h " . $meeting->duration_minutes . "m\n" .
                "Status: " . ($meeting->status ?? 'Not specified') . "\n" .
                "Join URL: " . ($meeting->join_url ?? '') . "\n" .
                "Creator: " . ($meeting->creator ?? '') . "\n" .
                "Modified: " . $meeting->date_modified . "\n\n";
        }
        return $resultText;
    }

    private function buildWhereClause($arguments): array
    {
        $where = [];

        $db = \DBManagerFactory::getInstance();

        if (!empty($arguments->search)) {
            $where[] = "meetings.name LIKE '%" .
                $db->quote($arguments->search) . "%'";
        }
        if (!empty($arguments->date_from)) {
            $where[] = "meetings.date_start >= '" .
                $db->quote($arguments->date_from) . " 00:00:00'";
        }
        if (!empty($arguments->date_to)) {
            $where[] = "meetings.date_start <= '" .
                $db->quote($arguments->date_to) . " 23:59:59'";
        }

        return $where;
    }

    private function getMeetingsList(array $searchParams): array
    {
        try {
            $bean = \BeanFactory::getBean('Meetings');
            $limit = $searchParams['limit'] ?? 20;
            $offset = $searchParams['offset'] ?? 0;
            $where = $searchParams['where'] ?? '';

            $list = $bean->get_full_list('', $where, $offset, $limit);

            return [
                'list' => $list ?? [],
                'total_count' => count($list ?? [])
            ];
        } catch (\Exception $e) {
            return [
                'list' => [],
                'total_count' => 0
            ];
        }
    }
}
