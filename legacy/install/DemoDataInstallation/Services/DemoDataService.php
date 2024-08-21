<?php

namespace DemoDataInstallation\Services;

class DemoDataService
{
    protected $config;
    protected $tables;

    const CONFIG_FILE_PATH = 'install/DemoDataInstallation/Configs/demo_data.php';
    const SQL_FILES_PATH = 'install/demo_data';

    public function __construct()
    {   
        $this->loadConfig();
        $this->loadTablesWithSQLFilesInfo();
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getTables() {
        return $this->tables;
    }

    protected function loadConfig(){
        require static::CONFIG_FILE_PATH;
        $this->config = $config;
    }

    protected function loadTablesWithSQLFilesInfo() {
        foreach(array_filter(glob(static::SQL_FILES_PATH.'/*.sql'), 'is_file') as $file) {
            $this->tables[] = 
            [
                'file_name' => pathinfo($file, PATHINFO_FILENAME), 
                'file_path' => $file,
            ];
        }
    }
}
