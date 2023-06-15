<?php

namespace MintHCM\MintCLI\Commands;

use MintHCM\MintCLI\Installer\Installer;
use MintHCM\MintCLI\Services\DatabaseService;
use MintHCM\MintCLI\Services\ServerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Install extends Command
{
    protected static $defaultName = 'install';
    protected static $defaultDescription = 'Install MintHCM system';

    protected function configure()
    {
        $this
            ->setHelp('This command allows you to install MintHCM system.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $DBService = new DatabaseService();

        $io->title("Welcome to MintHCM Installer.\nProvide all information to start installation process.");

        $userData = $this->collectUserData($input, $output);
        
        $output->writeln('');
        $io->section('Veryfing Database Connection...');
        $connectionStatus = $DBService->testConnection($userData['databaseHost'], $userData['databasePort'], $userData['databaseUsername'], $userData['databasePassword']);
        if (!$connectionStatus['status']) {
            $io->error('Could not connect to the database.');
            return Command::FAILURE;
        }

        $io->section('Veryfing Database Existance...');
        $existanceStatus = $DBService->testDatabaseExistance($userData['databaseHost'], $userData['databasePort'], $userData['databaseUsername'], $userData['databasePassword'], $userData['databaseName']);
        if (!$existanceStatus['status']) {
            $io->error('Database "' . $userData['databaseName'] . '" already exists.');
            return Command::FAILURE;
        }

        $installer = new Installer($userData['rootDirectory']);

        $io->section('Installing system core...');

        $installer->prepareConfigurationFile($userData);
        $installer->setupFilesPermissions();
        $backendInstallationStatus = $installer->installBackendApplication();
        if (!$backendInstallationStatus) {
            $io->error("An error occured while installing MintHCM. Check install.log file for more details.");
            return Command::FAILURE;
        }

        $io->section('Installing UX...');
        $frontendInstallationStatus = $installer->installFrontendApplication();
        if (!$frontendInstallationStatus) {
            $io->error("An error occured while installing MintHCM. Check install.log file for more details.");
            return Command::FAILURE;
        }

        $io->section('Setting up .htaccess files...');
        $installer->setupHtaccess();

        $io->section('Setting up files permissions...');
        $installer->setupFilesPermissions();

        $io->success('Installation finished successfuly');
        return Command::SUCCESS;
    }

    private function collectUserData($input, $output)
    {
        $QH = $this->getHelper('question');

        $question = new \MintHCM\MintCLI\Questions\SystemAdminName($QH, $input, $output);
        $systemAdminName = $question->ask();

        $question = new \MintHCM\MintCLI\Questions\SystemAdminPassword($QH, $input, $output);
        $systemAdminPassword = $question->ask();

        $question = new \MintHCM\MintCLI\Questions\DatabaseHost($QH, $input, $output);
        $databaseHost = $question->ask();

        $question = new \MintHCM\MintCLI\Questions\DatabasePort($QH, $input, $output);
        $databasePort = $question->ask();

        $question = new \MintHCM\MintCLI\Questions\DatabaseUsername($QH, $input, $output);
        $databaseUsername = $question->ask();

        $question = new \MintHCM\MintCLI\Questions\DatabasePassword($QH, $input, $output);
        $databasePassword = $question->ask();

        $question = new \MintHCM\MintCLI\Questions\DatabaseName($QH, $input, $output);
        $databaseName = $question->ask();

        $question = new \MintHCM\MintCLI\Questions\DatabaseCollation($QH, $input, $output);
        $databaseCollation = $question->ask();

        $question = new \MintHCM\MintCLI\Questions\DemoData($QH, $input, $output);
        $demoData = $question->ask();

        $question = new \MintHCM\MintCLI\Questions\SSL($QH, $input, $output);
        $ssl = $question->ask();

        $serverService = new ServerService();
        $protocl = $ssl ? 'https://' : 'http://';
        $url = $protocl . $serverService->getHostName() . DIRECTORY_SEPARATOR . $serverService->getScriptDirectory();

        $question = new \MintHCM\MintCLI\Questions\SiteURL($QH, $input, $output);
        $question->setDefaultValue($url);
        $siteUrl = $question->ask();

        $question = new \MintHCM\MintCLI\Questions\ApplicationRoot($QH, $input, $output);
        $rootDirectory = $question->ask();

        return [
            'systemAdminName' => $systemAdminName,
            'systemAdminPassword' => $systemAdminPassword,
            'databaseHost' => $databaseHost,
            'databasePort' => $databasePort,
            'databaseUsername' => $databaseUsername,
            'databasePassword' => $databasePassword,
            'databaseName' => $databaseName,
            'databaseCollation' => $databaseCollation,
            'demoData' => $demoData,
            'ssl' => $ssl,
            'siteUrl' => $siteUrl,
            'rootDirectory' => $rootDirectory,
        ];
    }
}
