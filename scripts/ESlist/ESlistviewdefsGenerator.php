<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

class ESlistviewdefsGenerator
{
    // Skrypt odpalam tutaj, ale chce aby pliki automatycznie przeniosły się do repo postawionego na kontenerze, abym nie musiał tego robić ręcznie.
    protected $repo_path = "/var/www/minthcm_repo/MintHCM";
    protected $listviewdefs = 'listviewdefs.php';
    protected $searchdefs = 'searchdefs.php';
    protected $eslistviewdefs = 'eslistviewdefs.php';
    // Moduły, które mają ręcznie dodane eslistviewdefs.php. Chwilowe zabezpieczenie aby nie zepsuć tego co już jest w razie w.
    protected $excluded_modules = ['Calls', 'Tasks', 'Meetings', 'Positions', 'Candidates', 'Candidatures', 'Recruitments'];

    public function generate()
    {
        $modulesWithViewDefs = $this->getModulesWithViewDefs();
        $counter = 0;
        foreach ($modulesWithViewDefs as $module) {
            include "{$module['metadata_path']}/{$this->listviewdefs}";
            include "{$module['metadata_path']}/{$this->searchdefs}";
            $columns = $this->getColumns($listViewDefs[$module['module']]);
            $search = $this->getSearch($searchdefs[$module['module']]);
            $view_defs = [
                'columns' => $columns,
                'search' => $search,
            ];
            $parsed_view_defs = var_export($view_defs, true);
            file_put_contents("{$this->repo_path}/{$module['metadata_path']}/{$this->eslistviewdefs}", "<?php\n\n");
            file_put_contents("{$this->repo_path}/{$module['metadata_path']}/{$this->eslistviewdefs}", "\$module_name = '{$module['module']}';\n", FILE_APPEND);
            file_put_contents("{$this->repo_path}/{$module['metadata_path']}/{$this->eslistviewdefs}", "\$ESListViewDefs['{$module['module']}'] = {$parsed_view_defs};\n", FILE_APPEND);
            file_put_contents("{$this->repo_path}/{$module['metadata_path']}/{$this->eslistviewdefs}", "\n?>\n", FILE_APPEND);
            $counter++;
            echo "Dodano plik {$this->eslistviewdefs} dla {$module['module']}<br>";
        }
        echo "Liczba wygenerowanych plików: {$counter}";
    }

    protected function getModulesWithViewDefs()
    {
        global $beanList;
        $modulesWithViewDefs = [];
        foreach ($beanList as $module => $value) {
            if (file_exists("custom/modules/{$module}/metadata/{$this->listviewdefs}")
                && file_exists("custom/modules/{$module}/metadata/{$this->searchdefs}")
                // && !file_exists("custom/modules/{$module}/metadata/{$this->eslisviewdefs}")
                && !in_array($module, $this->excluded_modules)
            ) {
                $data = [
                    'module' => $module,
                    'metadata_path' => "custom/modules/{$module}/metadata",
                ];
                array_push($modulesWithViewDefs, $data);
            } else if (file_exists("modules/{$module}/metadata/{$this->listviewdefs}")
                && file_exists("modules/{$module}/metadata/{$this->searchdefs}")
                // && !file_exists("custom/modules/{$module}/metadata/{$this->eslisviewdefs}")
                && !in_array($module, $this->excluded_modules)
            ) {
                $data = [
                    'module' => $module,
                    'metadata_path' => "modules/{$module}/metadata",
                ];
                array_push($modulesWithViewDefs, $data);
            }
        }
        return $modulesWithViewDefs;
    }

    protected function getColumns($data)
    {
        $columns = [];
        foreach ($data as $key => $value) {
            $current_column = [];
            if (array_key_exists('link', $value) && true === $value['link']) {
                $current_column['link'] = true;
            }
            if (array_key_exists('default', $value) && true === $value['default']) {
                $current_column['default'] = true;
            }
            $columns[strtolower($key)] = $current_column;
        }
        return $columns;
    }

    protected function getSearch($data)
    {
        $search = [];
        foreach ($data['layout']['advanced_search'] as $key => $value) {
            if (is_array($value) && array_key_exists('name', $value)) {
                $search[strtolower($value['name'])] = [];
            } else if (is_string($value)) {
                $search[strtolower($value)] = [];
            }
        }
        return $search;
    }
}

$generator = new ESlistviewdefsGenerator();
$generator->generate();
