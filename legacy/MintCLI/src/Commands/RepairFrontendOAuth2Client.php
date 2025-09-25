<?php

namespace MintHCM\MintCLI\Commands;

if (! defined('sugarEntry')) {
    define('sugarEntry', true);
}

chdir('legacy/');
include 'vendor/autoload.php';
require_once 'include/utils/sugar_file_utils.php';
require_once 'include/utils/file_utils.php';
include_once 'include/database/DBManagerFactory.php';
include_once 'include/utils.php';
require_once 'include/entryPoint.php';
chdir('../');

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RepairFrontendOAuth2Client extends Command
{
    protected static $defaultName = 'oauth2client:repairFrontend';
    protected static $defaultDescription = 'Repait Frontend OAuth2 Client';

    protected function configure()
    {
        $this
            ->setHelp('This command add oauth2 client and create new client secrect for him.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        chdir('legacy/');
        $io = new SymfonyStyle($input, $output);

        $io->title("Repair Frontend OAuth2 Client\n");

        try {
            if (!$this->checkKeysExists()) {
                $io->warning("
                    Private and public keys for OAuth2 not found. Please generate them first in /api/configs.\n
                    You can generate them by running the following commands:\n
                    cd api/configs\n
                    openssl genrsa -out private.key 2048\n
                    openssl rsa -in private.key -pubout -out public.key\n
                    sudo chmod 600 private.key public.key\n
                    sudo chown www-data:www-data private.key public.key\n
                ");
            }

            $secret = $this->getNewSecret();
            $this->createOrUpdateClient($secret);
            $this->updateFrontendEnv($secret);
            $this->updateFrontedBuiltFiles($secret);
            $io->success('Createing or updating OAuth2 client was successful.');
            chdir('../');
        } catch (\Exception $e) {
            $io->error("There was an error during execution: " . $e . "\n");
            chdir('../');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function checkKeysExists(): bool
    {
        $key_dir = '../api/configs';
        return file_exists($key_dir . '/private.key') && file_exists($key_dir . '/public.key');
    }

    private function getNewSecret(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(32));
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
