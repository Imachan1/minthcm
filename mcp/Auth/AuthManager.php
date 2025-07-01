<?php

namespace MintMCP\Auth;

use DBManagerFactory;

class AuthManager
{
    private string $jwt;
    private bool $authenticated = false;

    public function __construct(string $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * Checks if the sha256 hash of the access token exists in the oauth2tokens table.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $jti = $this->getJtiFromJwt();
        chdir('../legacy/');
        
        $sql = "SELECT id FROM oauth2tokens WHERE access_token = '{$jti}' AND deleted = 0";

        $db = DBManagerFactory::getInstance();
        $result = $db->query($sql);

        $this->authenticated = false;
        while ($row = $db->fetchByAssoc($result)) {
            if (!empty($row['id'])) {
                $this->authenticated = true;
                break;
            }
        }

        chdir('../api/');
        return $this->authenticated;
    }

    protected function getJtiFromJwt()
    {
        $parts = explode('.', $this->jwt);
        if (count($parts) !== 3) {
            return null;
        }
        $payload = $parts[1];
        $payload = strtr($payload, '-_', '+/'); // base64url → base64
        $payload = base64_decode($payload);
        if ($payload === false) {
            return null;
        }
        $data = json_decode($payload, true);
        return $data['jti'] ?? null;
    }



    /**
     * Returns authentication status.
     */
    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }
}
