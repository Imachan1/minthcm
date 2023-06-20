<?php
if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once 'lib/Search/ElasticSearch/ESlistviewdefsGenerator.php';

class ESlistLegacyViewsGeneartor extends ESlistviewdefsGenerator
{
    public function generate()
    {
        $modules = $this->getModulesWithViewDefs();
        $data = [];
        foreach ($modules as $module) {
            $data[$module['module']] = [
                'list' => false,
                'record' => true,
            ];
        }
        $data = var_export($data, true);
        file_put_contents('lib/Search/ElasticSearch/modules_config.php', "<?php\n\n");
        file_put_contents('lib/Search/ElasticSearch/modules_config.php', "return {$data};", FILE_APPEND);
        // Plik trzeba jeszcze ładnie sformatować
        return $modules;
    }
}

$generator = new ESlistLegacyViewsGeneartor();
$generator->generate();
