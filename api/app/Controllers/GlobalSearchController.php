<?php


/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

namespace MintHCM\Api\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use Slim\Psr7\Response;
use MintHCM\Lib\Search\Search;
use Psr\Http\Message\ServerRequestInterface as Request;
use MintHCM\Utils\ConstantsLoader;


class GlobalSearchController
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        // Workaround for api/lib/Search/Base/SearchResult.php
        // Passing EntityManager by constructor could make a mess with class structure
        // It should be replaced with a normal solution
        global $entityManager;
        $entityManager = $this->entityManager;
    }

    public function getData(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        try {
            global $current_user;
            $query = $request->getAttribute('query');
            $itemsPerPage = $request->getAttribute('itemsPerPage') ?? 5;
            $page = $request->getAttribute('page') ?? 1;
            $is_unified_search = $itemsPerPage != 5;

            $search_manager = Search::getManager();
            $search_manager->setElasticACL(!is_admin($current_user));
            
            $search_manager->setQuery(array(
                "search" => 'global',
                "fields" => array("*__last^5", "*__first^4", "*__name.*^3", "*"),
                "items" => $itemsPerPage,
                "query" => $request->getAttribute('query'),
                "sort_order" => "desc",
                "from" => $itemsPerPage * ($page - 1),
            ));
            $search_result = $search_manager->search(false);


        } catch (BadRequest400Exception) {
            throw new HttpBadRequestException($this->request);
        } catch (InvalidArgumentException) {
            throw new HttpInternalServerErrorException($this->request);
        }
        
        $data = array(
            'query' => $query,
            'next_page_exists' => $search_result->getNextPageExists(),
            'results' => $this->getBeans($search_result->getBeans(), $is_unified_search),
            'total' => $search_result->getTotal() ?? 0,
        );

        $response->getBody()->write(json_encode($data));
        return $response;
    }

    protected function getBeans($beans, $is_unified_search = false)
    {
        $response = array();
        foreach ($beans as $bean) {
            if(!$is_unified_search) {
                $response[] = array(
                    "id" => $bean->id,
                    "module" => $bean->module_name,
                    "name" => $bean->name,
                    "meta" => $this->getAdditionalMetaData($bean),
                );
                continue;
            }

            $response[] = array(
                "id" => $bean->id ?? '',
                "module" => $bean->module_name ?? '',
                "name" => $bean->name ?? $bean->document_name ?? '',
                "date_entered" => $bean->date_entered ?? '',
                "date_modified" => $bean->date_modified ?? '',
                "meta_array" => $this->getAdditionalMetaDataForUnifiedSearch($bean) ?? '',
            );
        }

        return $response;
    }

    protected function getAdditionalMetaData($bean)
    {
        $meta_field_name = $GLOBALS['dictionary'][$bean->object_name]['full_text_search_meta_field'] ?? null;
        if (!empty($meta_field_name) && isset($bean->field_defs[$meta_field_name])) {
            $field = $bean->field_defs[$meta_field_name];
        } else {
            $field = $bean->field_defs['date_entered'];
        }
        return [
            "def" => $field,
            "value" => $bean->{$field['name']},
        ];
    }

    protected function getAdditionalMetaDataForUnifiedSearch($bean)
    {
        $meta = $this->getAdditionalMetaData($bean);
        return [
            'value' => $meta['value'],
            'label' => $meta['def']['vname'] ?? $meta['def']['label'] ?? '',
        ];
    }
}
