<?php
namespace Api\V8\OAuth2\Grant;

use BeanFactory;  // MintHCM #136592
use DateInterval; // MintHCM #136592
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Grant\PasswordGrant; // MintHCM #131001
use League\OAuth2\Server\RequestAccessTokenEvent;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\RequestRefreshTokenEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;

#[\AllowDynamicProperties]
class MobileGrant extends PasswordGrant
{
    public function getIdentifier()
    {
        return 'mobile';
    }

    // MintHCM #131001 start
    public function canRespondToAccessTokenRequest(ServerRequestInterface $request)
    {
        $requestParameters = (array) $request->getParsedBody();

        return array_key_exists('grant_type', $requestParameters)
        && in_array($requestParameters['grant_type'], ['password', 'mobile'])
        && array_key_exists('client_id', $requestParameters)
        && $requestParameters['client_id'] == 'mobile';
    }
    // MintHCM #131001 end

    // MintHCM #136592 start
    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $user = parent::validateUser($request, $client);
        $device_id = $this->getRequestParameter('device_id', $request);
        $firebase_token = $this->getRequestParameter('firebase_token', $request);
        if (!empty($device_id) && !empty($firebase_token)) {
            $user_bean = BeanFactory::getBean('Users', $user->getIdentifier()); /** @var User $user_bean */
            $app_tokens_array = json_decode(html_entity_decode($user_bean->app_tokens), 1);
            if (empty($app_tokens_array[$device_id]) || $app_tokens_array[$device_id] != $firebase_token) {
                $app_tokens_array[$device_id] = $firebase_token;
                $user_bean->app_tokens = json_encode($app_tokens_array);
                $user_bean->skip_vt_validation = true;
                $user_bean->save();
            }
        }
        return $user;
    }
    // MintHCM #136592 end

    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        DateInterval $accessTokenTTL
    ) {
        // Validate request
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));
        $user = $this->validateUser($request, $client);

        // Finalize the requested scopes
        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier()); // MintHCM #170401

        // Issue and persist new access token
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), $finalizedScopes); // MintHCM #170401
        $this->getEmitter()->emit(new RequestAccessTokenEvent(RequestEvent::ACCESS_TOKEN_ISSUED, $request, $accessToken));
        $responseType->setAccessToken($accessToken);

        // Issue and persist new refresh token if given
        $refreshToken = $this->issueRefreshToken($accessToken);

        if ($refreshToken !== null) {
            $this->getEmitter()->emit(new RequestRefreshTokenEvent(RequestEvent::REFRESH_TOKEN_ISSUED, $request, $refreshToken));
            $responseType->setRefreshToken($refreshToken);
        }

        return $responseType;
    }
}
