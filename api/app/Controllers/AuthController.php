<?php

namespace MintHCM\Api\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Psr7\Response;

class AuthController
{

    public function login(Request $request, Response $response, array $args): Response
    {
        $username = $request->getAttribute('username');
        $password = $request->getAttribute('password');

        chdir('../legacy/');
        require_once 'include/MVC/SugarApplication.php';
        $app = new \SugarApplication();
        $app->startSession();
        require_once 'modules/Users/authentication/SugarAuthenticate/SugarAuthenticateUser.php';
        require_once 'modules/Users/authentication/AuthenticationController.php';
        $sugar_auth = \AuthenticationController::getInstance();
        $loginSuccess = $sugar_auth->login($username, $password);
        chdir('../api/');

        if (!$loginSuccess) {
            throw new HttpUnauthorizedException($request);
        }

        $response = $response->withHeader('Content-type', 'application/json');
        $data = json_encode(['message' => 'Login success']);
        $response->getBody()->write($data);
        return $response;
    }

    public function logout(Request $request, Response $response, array $args): Response
    {
        session_start();
        session_destroy();
        ob_clean();
        sugar_cleanup(true);
        return $response;
    }

    public function forgetPassword(Request $request, Response $response, array $args): Response
    {
        global $timedate, $sugar_config;

        $response = $response->withHeader('Content-type', 'application/json');

        $username = $request->getAttribute('username');
        $email = $request->getAttribute('email');

        chdir('../legacy/');
        $user = new \User();
        $user_id = $user->retrieve_user_id($username);
        $user->retrieve($user_id);
        $is_primary_email = !empty($user) ? $user->isPrimaryEmail($email) : false;
        chdir('../api/');

        if (empty($user->id)
            || $user->id !== $user_id
            || !$is_primary_email
            || $user->portal_only
            || $user->is_group
        ) {
            $response = $response->withStatus(400);
            $response->getBody()->write(json_encode(array('message' => "LBL_PROVIDE_USERNAME_AND_EMAIL")));
            return $response;
        }

        $guid = create_guid();
        $emailTemp_id = $sugar_config['passwordsetting']['lostpasswordtmpl'];
        $url = $sugar_config['site_url'] . "/Users/Login?reset_token=$guid";
        $additionalData = array(
            'link' => true,
            'password' => '',
            'url' => $url,
        );

        $time_now = \TimeDate::getInstance()->nowDb();
        $q = "INSERT INTO users_password_link (id, username, date_generated) VALUES('" . $guid . "','" . $username . "','" . $time_now . "') ";

        chdir('../legacy/');
        $user->db->query($q);
        $result = $user->sendEmailForPassword($emailTemp_id, $additionalData);
        chdir('../api/');

        if (true !== $result['status']) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(array('message' => 'LBL_EMAIL_NOT_SENT')));
            return $response;
        }

        return $response;
    }

    public function validToken(Request $request, Response $response, array $args): Response
    {
        global $timedate, $sugar_config;

        $response = $response->withHeader('Content-type', 'application/json');

        $token = $request->getAttribute('reset_token');

        chdir('../legacy/');
        $db = \DBManagerFactory::getInstance();
        $query = "SELECT * FROM users_password_link WHERE id = '" . $db->quote($token) . "'";
        $row = $db->fetchOne($query);
        chdir('../api/');

        if (empty($row)) {
            $response = $response->withStatus(400);
            return $response;
        }

        $pwd_settings = $sugar_config['passwordsetting'];
        $expired = false;
        if ($pwd_settings['linkexpiration']) {
            $delay = $pwd_settings['linkexpirationtime'] * $pwd_settings['linkexpirationtype'];
            $stim = strtotime($row['date_generated']) + date('Z');
            $expiretime = \TimeDate::getInstance()->fromTimestamp($stim)->get("+$delay  minutes")->asDb();
            $timenow = \TimeDate::getInstance()->nowDb();
            if ($timenow > $expiretime) {
                $expired = true;
            }
        }

        if ($expired) {
            $response = $response->withStatus(403);
            $response->getBody()->write(json_encode(array('message' => 'LBL_TOKEN_EXPIRED')));
            return $response;
        }

        if ('1' == $row['deleted']) {
            $response = $response->withStatus(403);
            $response->getBody()->write(json_encode(array('message' => 'LBL_TOKEN_USED')));
            return $response;
        }

        $response->getBody()->write(json_encode(array(
            'username' => $row['username'],
            'password_settings' => array(
                "oneupper" => !empty($pwd_settings['oneupper']) ? true : false,
                "onelower" => !empty($pwd_settings['onelower']) ? true : false,
                "onenumber" => !empty($pwd_settings['onenumber']) ? true : false,
                "onespecial" => !empty($pwd_settings['onespecial']) ? true : false,
                "minpwdlength" => !empty($pwd_settings['minpwdlength']) ? (int) $pwd_settings['minpwdlength'] : false,
            ),

        )));

        return $response;
    }

    public function resetForgetPassword(Request $request, Response $response, array $args): Response
    {
        global $timedate, $sugar_config, $mod_strings, $current_language;

        $token = $request->getAttribute('reset_token');
        $username = $request->getAttribute('username');
        $new_password = $request->getAttribute('new_password');

        $response = $this->validToken($request, $response, $args);
        if ($response->getStatusCode() !== 200) {
            return $response;
        }

        $response = new Response();
        $response = $response->withHeader('Content-type', 'application/json');

        chdir('../legacy/');
        $user = new \User();
        $mod_strings = return_module_language($GLOBALS['current_language'], 'Users');
        $errors = $user->passwordValidationCheck($new_password);
        chdir('../api/');

        if (!empty($errors)) {
            $response = $response->withStatus(400);
            $response->getBody()->write(json_encode(array('message' => $errors)));
            return $response;
        }

        chdir('../legacy/');
        $user_id = $user->retrieve_user_id($username);
        $user->retrieve($user_id);
        $user->setNewPassword($new_password);

        $db = \DBManagerFactory::getInstance();
        $query = "UPDATE users_password_link SET deleted='1' where username='" . $db->quote($username) . "'";
        $db->query($query);
        chdir('../api/');

        return $response;
    }
}
