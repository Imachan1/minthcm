<?php

namespace MintMCP;

use MintMCP\Auth\AuthManager;
use MintMCP\Handlers\Logger;
use MintMCP\Server\MintMCPServer;

class MCPApp {
    public function run() {
        $this->handleCORS();
        try {
            $headers   = getallheaders();
            $method    = $_SERVER['REQUEST_METHOD'];
            $ctype     = $_SERVER['CONTENT_TYPE']    ?? '';
            $accept    = $headers['Accept'] ?? $headers['accept'] ?? '';
            $rawInput  = file_get_contents('php://input');
            $this->logRequest($headers, $rawInput);
            $authManager = $this->authenticate($headers);
            if ($method === 'GET' && stripos($accept, 'text/event-stream') === false) {
                $this->handleProbe();
            }
            if ($method === 'POST') {
                $this->handlePost($rawInput);
            }
            $this->handleNotAllowed();
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    private function handleCORS() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept');
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }

    private function logRequest($headers, $rawInput) {
        Logger::getLogger()->info('MCP Request', [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $_SERVER['REQUEST_METHOD'],
            'headers' => $headers,
            'raw_input' => $rawInput,
            'raw_input_length' => strlen($rawInput),
            'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'not set',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
        ]);
    }

    private function authenticate($headers) {
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;
        if (!$auth || !preg_match('/Bearer\s+(\S+)/', $auth, $m)) {
            http_response_code(401);
            echo json_encode(['error' => 'missing bearer']);
            exit;
        }
        $authManager = AuthManager::getInstance($m[1]);
        if (!$authManager->validate()) {
            http_response_code(401);
            echo json_encode(['error' => 'token invalid']);
            exit;
        }
        return $authManager;
    }

    private function handleProbe() {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'online', 'version' => '1.0.0']);
        exit;
    }

    private function handlePost($rawInput) {
        header('Content-Type: application/json');
        $request = json_decode($rawInput, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode([
                'jsonrpc' => '2.0',
                'id'      => null,
                'error'   => ['code' => -32700, 'message' => 'parse error']
            ]);
            exit;
        }
        $response = (new MintMCPServer())->handleHTTPRequest($request);
        Logger::getLogger()->info('MCP Response', $response);
        echo json_encode($response);
        exit;
    }

    private function handleNotAllowed() {
        http_response_code(405);
        echo json_encode(['error' => 'method not allowed']);
        exit;
    }

    private function handleError($e) {
        Logger::getLogger()->critical('MCP Fatal', ['msg' => $e->getMessage()]);
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'jsonrpc' => '2.0',
            'id'      => null,
            'error'   => ['code' => -32603, 'message' => $e->getMessage()]
        ]);
    }
}
