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
 * Copyright (C) 2018-2024 MintHCM
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

namespace MintHCM\Api\Controllers\Module;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use UserPreference;

class RecordViewController
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        global $entityManager;
        $entityManager = $this->entityManager;
    }

    public function savePreference(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        global $current_user;
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        preg_match('/(?<=\/)([^\/]+)/m', $route->getPattern(), $matches);
        $module = $matches[0];
        $preference = $request->getAttribute('preference');
        $category = $request->getAttribute('category');
        global $beanList;
        if (!isset($beanList[$module]) || empty($beanList[$module])) {
            throw new HttpBadRequestException($request, "Module not found");
        }
        $current_preferences = (new UserPreference($current_user))->getPreference($module, 'recordview');
        if (!is_array($current_preferences)) {
            $current_preferences = [];
        }
        $current_preferences[$category] = $preference;
        if (!empty($preference) && is_array($preference) && !empty($module)) {
            (new UserPreference($current_user))->setPreference($module, $current_preferences, 'recordview');
        }
        $response->getBody()->write(json_encode(true));
        return $response;
    }

    public function getPreference(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        global $current_user;
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        preg_match('/(?<=\/)([^\/]+)/m', $route->getPattern(), $matches);
        $module = $matches[0];
        $category = ($request->getQueryParams())['category'] ?? '';
        global $beanList;
        if (!isset($beanList[$module]) || empty($beanList[$module])) {
            throw new HttpBadRequestException($request, "Module not found");
        }
        $current_preferences = (new UserPreference($current_user))->getPreference($module, 'recordview');
        $response->getBody()->write(json_encode($current_preferences[$category]));
        return $response;
    }
}
