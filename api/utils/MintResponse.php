<?php

namespace MintHCM\Api\utils;

use Slim\Psr7\Response as BaseResponse;

class MintResponse extends BaseResponse
{
    public function withJson($data, $status = null, $encodingOptions = JSON_INVALID_UTF8_IGNORE): MintResponse {
        $json = json_encode($data, $encodingOptions);

        if ($json === false) {
            throw new \RuntimeException(json_last_error_msg());
        }
        $this->getBody()->write($json);

        $responseWithJson = $this->withHeader('Content-Type', 'application/json;charset=utf-8');
        if (isset($status)) {
            return $responseWithJson->withStatus($status);
        }
        return $responseWithJson;
    }
}