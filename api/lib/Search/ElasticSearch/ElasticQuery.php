<?php

namespace MintHCM\Lib\Search\ElasticSearch;

use Elasticsearch\Common\Exceptions\InvalidArgumentException;
use MintHCM\Lib\Search\Base\SearchQuery;
use MintHCM\Lib\Search\ElasticSearch\ElasticQueryOperatorsManager;
use MintHCM\Utils\CustomLoader;
use MintHCM\Utils\LegacyConnector;
use MintHCM\Data\BeanFactory;
class ElasticQuery extends SearchQuery
{
    const DEFAULT_SORT_FIELD = "_score";
    const DEFAULT_SORT_ORDER = "asc";
    const SORT_KEYWORD = "_keyword";

    const DEFAULT_TYPE = null;

    const ALL_FIELDS = "*";

    protected $exclude_modules = [];

    protected $add_acl_filters  = false;
    protected $indice_module_map;

    protected function setQuery()
    {
        $this->query = array(
            "body" => $this->getBody(),
            "index" => $this->getIndex(),
        );
    }

    public function setACLFilters($add_acl_filters)
    {
        $this->add_acl_filters = $add_acl_filters;
        return $this;
    }

    protected function setSort()
    {
        global $list_config;

        $field = !empty($this->params["sort_by"]) ? $this->params['sort_by'] : static::DEFAULT_SORT_FIELD;
        if (isset($list_config['sort_mappings'][$field])) {
            $field = $list_config['sort_mappings'][$field];
        } else if (static::DEFAULT_SORT_FIELD !== $field) {
            $field .= self::SORT_KEYWORD;
        }

        $this->sort = array(
            $field => array(
                "order" => !empty($this->params["sort_order"]) ? $this->params['sort_order'] : 'asc',
            ),
        );
    }

    private function getIndex()
    {
        if (isset($GLOBALS['sugar_config']['unique_key'])) {
            
            $searchModules = array_map('strtolower', $this->search_modules);
            $searchModules = substr_replace($searchModules, $GLOBALS['sugar_config']['unique_key'].'_', 0, 0);
            $indexes = implode(',', $searchModules);
            $this->indice_module_map = array_combine($searchModules,$this->search_modules);

            return $indexes;
        }
        return null;
    }


    private function getBody()
    {
        return array(
            "query" => $this->getBodyQuery(),
            "size" => $this->size,
            "from" => $this->from,
            "sort" => $this->sort,
        );
    }

    private function getBodyQuery()
    {
        switch (strtolower($this->params['search'])) {
            case "global":
                $this->search_modules = $search_modules = $this->getGlobalSearchModuleList();
                return $this->getGlobalQuery($search_modules);
                break;
            case "list":
                return $this->getListQuery();
                break;
            default:
                throw new InvalidArgumentException();
        }
    }

    private function getGlobalQuery($search_modules)
    {

        if($this->add_acl_filters){
            $uniq = $GLOBALS['sugar_config']['unique_key'];
            $main_acl["bool"]["must"] = $this->noAclGlobalQuery();
            $main_acl["bool"]["filter"]["bool"]["should"] = [];
            
            
            foreach($search_modules as $module_to_search)
            {
                $bean = BeanFactory::newBean($module_to_search);
                $acl_controller = new LegacyConnector('ACLController');
                if( $bean->bean_implements('ACL') &&  ($acl_controller::requireOwner($bean->module_dir, 'list') || $acl_controller::requireSecurityGroup($bean->module_dir, 'list')) ) { 
                  $module_filters = $this->getACLForModule($module_to_search);
                }
                else {
                    $module_filters['bool']['must']['term']['_index'] = $uniq.'_'.strtolower($module_to_search);
                }
                
                if(is_array($module_filters)){
                    $main_acl["bool"]["filter"]["bool"]["should"][] = $module_filters;
		            $module_filters = [];
                }                
            }

            if(count($this->exclude_modules)){
               $main_acl["bool"]["filter"]["bool"]['must_not'] = $this->getExcludeModules();
            }

            return $main_acl;

        } else {
            return $this->noAclGlobalQuery();
        }
        
    }

    private function noAclGlobalQuery(){
        $fields = !empty($this->params['fields']) ? $this->params['fields'] : array(static::ALL_FIELDS);
        return array(
            'query_string' => array(
                'query' => $this->params['query'],
                'fields' => $fields,
                'analyzer' => 'standard',
                'default_operator' => 'OR',
                'minimum_should_match' => '66%',
            ),
        );
    }

    private function getListQuery()
    {
        return (CustomLoader::getObject(ElasticQueryOperatorsManager::class, $this->params['filters'] ?? []))->getQuery();
    }

    protected function getExcludeModules(){
        $uniq = $GLOBALS['sugar_config']['unique_key'];
        $excluded_queries = [];
        foreach($this->exclude_modules as $module){
            $excluded_queries[]['term']['_index'] = $uniq.'_'.strtolower($module);
        }
        return $excluded_queries;
    }

    protected function getACLForModule($module)
    {
        global $current_user;
        $uniq = $GLOBALS['sugar_config']['unique_key'];
        $acl = $this->getACLClassForModule($module);
        $restriction_filter = $acl->getAccessRestrictionFilter($current_user->id);
       
        $single_module['bool']['must'][]['term']['_index'] = $uniq.'_'.strtolower($module);
    	if(!empty($restriction_filter[0]['bool']['should'])){
        	$single_module['bool']['must'][]['bool']['should']  =  $restriction_filter[0]['bool']['should'];
        }
        return $single_module;

    }

    protected function getACLClassForModule(string $module)
    {
        $variants = [
            [ 'className' => "Custom{$module}ListACL", 'path' => "custom/modules/{$module}/{$module}ListACL.php" ],
            [ 'className' => "{$module}ListACL", 'path' => "modules/{$module}/{$module}ListACL.php" ],
            [ 'className' => 'BaseListACL', 'path' => "include/ESListView/BaseListACL.php" ],
        ];

        foreach ($variants as $variant) {
            if (file_exists('../legacy/'.$variant['path'])) {
                require_once $variant['path'];
                $acl_class = new LegacyConnector($variant['className'],$variant['path'],[$module]);
                return $acl_class;
            }
        }
    }
    public function getIndiceToModuleMapping(){
        return $this->indice_module_map;
    }

    protected function getGlobalSearchModuleList(){
        include '../legacy/custom/modules/unified_search_modules_display.php';
        
        $search_modules = [];
        $exclude_hardcode = ["Connectors","Currencies","OAuthTokens","OAuthKeys","ACLRoles","ACLActions","EmailMan","Schedulers","SchedulersJobs","CampaignLog","EmailMarketing","AOW_WorkFlow"];
        global $beanList;
        if(!empty($unified_search_modules_display)){
            $search_modules = array_filter(array_map(function($row){ return $row['visible'] === true;},$unified_search_modules_display));    
            foreach(array_keys($search_modules) as $module_name){
                if(!isset($beanList[$module_name])){
                    unset($search_modules[$module_name]);
                }
            }
            $exclude = array_filter(array_map(function($row){ return $row['visible'] === false;},$unified_search_modules_display));      
            $this->exclude_modules = array_merge($exclude_hardcode,array_keys($exclude));
            return array_diff(array_keys($search_modules),$this->exclude_modules);
        }

        return $search_modules;
    }
}
