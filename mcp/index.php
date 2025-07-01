<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}
chdir('../legacy/');
require_once 'include/entryPoint.php';
require_once 'include/SugarLogger/LoggerManager.php';

chdir('../mcp/');

require __DIR__ . '/vendor/autoload.php';

use MintMCP\Auth\AuthManager;
use MintMCP\Server\MintMCPServer;

try {

    // Set proper headers for CORS
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept');

    // Handle preflight OPTIONS request
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    // Log request details for debugging
    $headers = getallheaders();
    $rawInput = file_get_contents('php://input');
    $method      = $_SERVER['REQUEST_METHOD'];
    $accept      = $headers['Accept'] ?? $headers['accept'] ?? '';

        // Check and validate authentication - temporarily bypassed for initialize requests
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;
    if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode([
            'jsonrpc' => '2.0',
            'error' => [
                'code' => -32001,
                'message' => 'Missing or invalid Authorization header'
            ],
            'id' => ($request['id'] ?? null)
        ]);
        exit;
    }

    $token = $matches[1];

    // Validate token
    $auth = new AuthManager($token);
    if (!$auth->validate()) {
        http_response_code(401);
        echo json_encode([
            'jsonrpc' => '2.0',
            'error' => [
                'code' => -32001,
                'message' => 'Invalid token'
            ],
            'id' => ($request['id'] ?? null)
        ]);
        exit;
    }

    $isSseGet = $method === 'GET' && str_contains($accept, 'text/event-stream');

    if ($isSseGet) {
        chdir('../legacy/');
        LoggerManager::getLogger()->fatal('MCP Request SSE received.');
        chdir('../mcp/');

        // --- SSE headers ---
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');     // nginx – wyłącza buforowanie

        // ---  "ping" that keeps the connection alive ---"
        while (true) {
            echo ": keep-alive\n\n";      // SSE comment
            @ob_flush();
            flush();
            sleep(15);
        }

        exit; // Stop further processing for SSE requests
    }

    $logData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'method' => $_SERVER['REQUEST_METHOD'],
        'headers' => $headers,
        'raw_input' => $rawInput,
        'raw_input_length' => strlen($rawInput),
        'accept_header' => $acceptHeader,
        'using_sse' => $useSSE ? 'true' : 'false',
        'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'not set',
        'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
    ];

    chdir('../legacy/');
    LoggerManager::getLogger()->fatal('MCP Request Log: ' . json_encode($logData));
    chdir('../mcp/');

    // Simple health check for GET requests
    if ($method === 'GET') {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'online', 'version' => '1.0.0']);
        exit;
    }

    // Special handling for initialize request from VS Code


    if ($method == 'POST') {
        $server = new MintMCPServer();
        $response = $server->handleHTTPRequest($rawInput);
        header('Content-Type: application/json');
        chdir('../legacy/');
        LoggerManager::getLogger()->fatal('MCP Response Log: ' . json_encode($response));
        chdir('../mcp/');
        echo json_encode($response);
        exit;
    }

    // If we reach here, it means the request method is not recognized
    http_response_code(405);
    $errorResponse = [
        'jsonrpc' => '2.0',
        'id' => null,
        'error' => [
            'code' => -32601,
            'message' => 'Method not found'
        ]
    ];
    header('Content-Type: application/json');
    echo json_encode($errorResponse);
    exit;
    
} catch (Throwable $e) {
    http_response_code(500);
    $errorResponse = [
        'jsonrpc' => '2.0',
        'id' => null,
        'error' => [
            'code' => -32603,
            'message' => 'Internal server error: ' . $e->getMessage()
        ]
    ];

    header('Content-Type: application/json');
    echo json_encode($errorResponse);

    // Log error
    chdir('../legacy/');
    LoggerManager::getLogger()->fatal('MCP Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
    chdir('../mcp/');
}
