<?php

namespace MintHCM\MintCLI\Services;

class OAuth2Service
{
    const KEY_DIR = 'api/configs';


    public function generateNewKeys(): void
    {
        $private_key_path = $this->getKeyPath('private');
        if (file_exists($private_key_path)) {
            unlink($private_key_path);
        }

        $public_key_path = $this->getKeyPath('public');
        if (file_exists($public_key_path)) {
            unlink($public_key_path);
        }

        exec("openssl genrsa -out " . $private_key_path . " 2048");
        exec("openssl rsa -in " . $private_key_path . " -pubout -out " . $public_key_path);
        exec("chmod 600 " . $private_key_path . " " . $public_key_path);
        exec("chown www-data:www-data " . $private_key_path . " " . $public_key_path);
    }


    public function repairFrontendToken(): bool
    {
        try {
            chdir('legacy');
            require_once 'include/entryPoint.php';
            $secret = bin2hex(openssl_random_pseudo_bytes(32));
            $this->createOrUpdateClient($secret);
            $this->updateFrontendEnv($secret);
            $this->updateFrontedBuiltFiles($secret);
            chdir('..');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function getKeyPath(string $type): string
    {
        return self::KEY_DIR . '/' . $type . '.key';
    }

    private function createOrUpdateClient(string $secret): void
    {
        $db = \DBManagerFactory::getInstance();
        $hash = hash('sha256', $secret);

        $db->query(<<<SQL
            INSERT INTO `oauth2clients` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `secret`, `redirect_url`, `is_confidential`, `allowed_grant_type`, `duration_value`, `duration_amount`, `duration_unit`, `assigned_user_id`) 
            VALUES
                ('frontend', 'Frontend Token Client',	NULL,	NULL,	NULL,	NULL,	NULL,	0, '{$hash}',	NULL,	1,	'frontend',	60,	1,	'minute',	NULL)
            ON DUPLICATE KEY UPDATE
                `secret` = {$db->quoted($hash)};
        SQL);
    }

    private function updateFrontendEnv(string $secret): void
    {
        $env = '../vue/.env';
        $env_local = '../vue/.env.example';

        if (!file_exists($env)) {
            if (file_exists($env_local)) {
                copy($env_local, $env);
            } else {
                file_put_contents($env, '');
            }
        }

        $content = file_get_contents($env);
        $content = preg_replace('/^CLIENT_SECRET=.*$/m', '', $content);
        $content .= "\nCLIENT_SECRET=" . $secret;

        file_put_contents(
            $env,
            $content,
            LOCK_EX
        );
    }

    private function updateFrontedBuiltFiles(string $secret): void
    {
        $files = [
            $this->getFrontendIndexFile('../vue/dist/assets/'),
            $this->getFrontendIndexFile('../assets/'),
        ];

        foreach ($files as $file) {
            if ($file === null || !file_exists($file)) {
                continue;
            }

            $content = file_get_contents($file);
            $content = preg_replace('/client_secret:[\'"]([^\'"]*)[\'"]/m', 'client_secret:"' . $secret . '"', $content);
            file_put_contents(
                $file,
                $content,
                LOCK_EX
            );
        }
    }

    private function getFrontendIndexFile(string $dir): ?string
    {
        $file_starts_with = 'index';
        $file_extension = '.js';
        if (!is_dir($dir)) {
            return null;
        }

        $files = scandir($dir);
        foreach ($files as $file) {
            if (str_starts_with($file, $file_starts_with) && str_ends_with($file, $file_extension)) {
                return $dir . $file;
            }
        }

        return null;
    }
}
