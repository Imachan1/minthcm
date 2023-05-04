<?php

namespace MintHCM\Api\Controllers\Module;

use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Common\Exceptions\InvalidArgumentException;
use MintHCM\Lib\Search\Search;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class ListInitController
{
    const METADATA_FILES = array(
        '../legacy/custom/modules/{module}/metadata/eslistviewdefs.php',
        '../legacy/modules/{module}/metadata/eslistviewdefs.php',
    );


    private $request;
    private $module, $metadata, $bean;

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->setData();
        $response = $response->withHeader('Content-type', 'application/json');

        $data = array(
            'config' => $this->prepareConfig(),
            'defs' => $this->prepareDefs(),
            'module' => $this->module,
            'preferences' => $this->prepareUserPreferences(),
        );

        $response->getBody()->write(json_encode($data));
        return $response;
    }

    private function setData()
    {
        $routeContext = RouteContext::fromRequest($this->request);
        $route = $routeContext->getRoute();
        $this->module = str_replace('/', '', $route->getPattern());
        chdir('../legacy/');
        $this->bean =  \BeanFactory::newBean($this->module);
        chdir('../api/');
        foreach(static::METADATA_FILES as $file) {
            $file = str_replace('{module}', $this->module, $file);
            if(file_exists($file)) {
                include $file;
                $this->metadata = $ESListViewDefs[$this->module];
                break;
            }
        }
        if(empty($this->metadata) || empty($this->bean)) {
            throw new HttpNotFoundException($this->request); 
        }
    }

    private function prepareUserPreferences()
    {
        global $current_user;
        chdir('../legacy/');
        $preferences = (new \UserPreference($current_user))->getPreference($this->bean->module_name, 'eslist');
        chdir('../api/');
        return $preferences;
    }

    private function prepareConfig()
    {
        global $list_config, $sugar_config;
        $variables = $list_config['variables'];
        $theme = $list_config['theme'];

        foreach ($theme as $property => $objects) {
            foreach ($objects as $object => $value) {
                $theme[$property][$object] = $variables[$property][$value];
            }
        }

        $config = $list_config['config'];
        $config['defaultMaxItemsPerPage'] = $sugar_config['list_max_entries_per_page'] ?? $list_config['config']['defaultMaxItemsPerPage'];
        foreach ($config['itemsPerPageOptions'] as $key=>$amount) {
            if ($amount > $config['defaultMaxItemsPerPage']) {
                unset($config['itemsPerPageOptions'][$key]);
            }
        }
        
        return array(
            'config' => $config,
            'theme' => $theme
        );
    }

    private function prepareDefs()
    {
        return [
            'columns' => $this->prepareDefsType("columns"),
            'search' => $this->prepareDefsType("search"),
        ];
    }

    protected function prepareDefsType($type)
    {
        global $mod_strings, $app_strings;

        if(empty($mod_strings)) {
            chdir('../legacy/');
            $mod_strings = return_module_language($GLOBALS['current_language'], $this->module);
            chdir('../api/');
        }
        $data = $this->metadata[$type];
        if (empty($data)) {
            throw new HttpNotFoundException($this->request);
        }
        $data = array_change_key_case($data, CASE_LOWER);
        foreach ($data as $field => $defs) {
            $field_defs = $this->bean->field_name_map[$field];

            if (empty($field_defs)) {
                unset($data[$field]);
                continue;
            }
            if (
                !empty($field_defs['has_access']['function'])
                && function_exists($field_defs['has_access']['function'])
                && !$field_defs['has_access']['function']()
            ) {
                unset($data[$field]);
                continue;
            }
            $data[$field]['name'] = $defs['name'] ?? $field;
            $data[$field]['type'] = $defs['type'] ?? $field_defs['type'];
            $data[$field]['options'] = $field_defs['options'];
            $label = $defs['label'] ?? $field_defs['label'] ?? $field_defs['vname'];
            $data[$field]['label'] = $this->prepareLabel($mod_strings[$label] ?? $app_strings[$label] ?? $label);
        }
        return $data;
    }

    private function prepareLabel($label) {
        $label = trim($label);
        if (in_array(substr($label, -1), [':', '.'])) {
            $label = substr($label, 0, -1);
        }
        return $label;
    }

}
