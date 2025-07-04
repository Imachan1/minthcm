<?php

namespace MintMCP\Auth;

class AuthManager
{
    private static ?AuthManager $instance = null;
    private string $jwt;
    private bool $authenticated = false;

    private function __construct(string $jwt)
    {
        $this->jwt = $jwt;
    }

    public static function getInstance(string $jwt): AuthManager
    {
        if (self::$instance === null) {
            self::$instance = new self($jwt);
        }
        return self::$instance;
    }

    public function validate(): bool
    {
        $this->authenticated = false;
        $jti = $this->getJtiFromJwt();

        chdir('../legacy/');
        $tokenBean = \BeanFactory::newBean('OAuth2Tokens');
        $foundToken = $tokenBean->retrieve_by_string_fields(['access_token' => $jti]);
        if (empty($foundToken) || empty($foundToken->id) || $foundToken->access_token !== $jti) {
            return false;
        }

        $this->authenticated = true;
        $this->setCurrentUser($foundToken->assigned_user_id);

        chdir('../mcp/');

        // TODO: Check if the token is expired



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

    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    protected function setCurrentUser(string $userId): void
    {
        $userBean = \BeanFactory::getBean('Users', $userId);
        if (!empty($userBean->id)) {
            $GLOBALS['current_user'] = $userBean;
        } else {
            throw new \Exception("User with ID {$userId} not found");
        }
    }
}