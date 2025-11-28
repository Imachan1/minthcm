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

namespace MintHCM\Api\Controllers;

use BeanFactory as LegacyBeanFactory;
use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Data\BeanFactory;
use MintHCM\Data\BeanFactory as MintBeanFactory;
use MintHCM\Data\MintBean;
use MintHCM\Lib\MintLogic\MintLogic;
use MintHCM\Utils\CyclicRecordsSaver;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

#[\AllowDynamicProperties]
class ModuleController
{

    public function __construct(protected EntityManagerInterface $entityManager)
    {
        global $app_list_strings, $current_language;
        if (!$app_list_strings) {
            $app_list_strings = return_app_list_strings_language($current_language);
        }
    }

    public function detail(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $data = ['message' => 'detail'];
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    function list(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $data = ['message' => 'list'];
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $module = $this->getModuleFromRoute($request);
        chdir('../legacy/');
        $links = $request->getAttribute("links") ?? [];

        $current_time_zone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $disable_date_format = $GLOBALS['disable_date_format'];
        $GLOBALS['disable_date_format'] = true;

        $bean = BeanFactory::newBean($module);
        if (empty($bean)) {
            return $response->withStatus(404);
        }
        if (!$bean->ACLAccess('edit')) {
            return $response->withStatus(403);
        }
        $record_data = $request->getAttribute("record_data");
        foreach ($record_data as $field_name => $value) {
            if (isset($bean->field_defs[$field_name])) {
                if ('id' === $field_name && !empty($value)) {
                    $bean->new_with_id = true;
                }
                if ('multienum' === $bean->field_defs[$field_name]['type'] && is_array($value)) {
                    $value = '^' . implode('^,^', $value) . '^';
                }
                $bean->$field_name = $value;
            }
        }
        $bean->save(false);
        if (!empty($bean->repeat_type) && '' != $bean->repeat_type) {
            $this->handleCyclicalRecords($bean);
        }
        $this->handleLinks($bean, $links);
        $bean->retrieve();

        date_default_timezone_set($current_time_zone);
        $GLOBALS['disable_date_format'] = $disable_date_format;

        if (!empty($bean) && !empty($bean->id)) {
            $record_data = $this->mergeRecordData($bean);
        }

        chdir('../api/');
        $response = $response->withStatus(201);
        $response->getBody()->write(json_encode($record_data));
        return $response;
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $module = $this->getModuleFromRoute($request);
        chdir('../legacy/');
        $record_data = $request->getAttribute("record_data");
        $files = $request->getAttribute("files") ?? [];
        $links = $request->getAttribute("links") ?? [];
        $record_id = $request->getAttribute("id");

        $current_time_zone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $disable_date_format = $GLOBALS['disable_date_format'];
        $GLOBALS['disable_date_format'] = true;

        if (!empty($record_id)) {
            $bean = BeanFactory::getBean($module, $record_id);
        } else {
            $bean = BeanFactory::newBean($module);
        }

        if (empty($bean) || $bean->id !== $record_id) {
            return $response->withStatus(404);
        }
        if (!$bean->ACLAccess('edit')) {
            return $response->withStatus(403);
        }
        foreach ($record_data as $field_name => $value) {
            if (isset($bean->field_defs[$field_name]) && "id" !== $field_name) {
                if ('multienum' === $bean->field_defs[$field_name]['type'] && is_array($value)) {
                    $value = '^' . implode('^,^', $value) . '^';
                }
                $bean->$field_name = $value;
            }
        }
        $validationResult = (new MintLogic($bean))->validateBean();
        if (!$validationResult['isValid']) {
            $response = $response->withStatus(422);
            $response->getBody()->write(json_encode($validationResult));
            return $response;
        }
        $this->handleFiles($bean, $files);
        $bean->save(false);
        if (!empty($bean->repeat_type) && '' != $bean->repeat_type) {
            $this->handleCyclicalRecords($bean);
        }
        $this->handleLinks($bean, $links);
        BeanFactory::unregisterBean($bean->module_name, $bean->id);
        $bean = BeanFactory::getBean($bean->module_name, $bean->id);
        // $bean->retrieve();

        date_default_timezone_set($current_time_zone);
        $GLOBALS['disable_date_format'] = $disable_date_format;

        if (!empty($bean) && ($bean->id === $record_id || empty($record_id))) {
            $record_data = $this->mergeRecordData($bean);
        }

        chdir('../api/');
        $response = $response->withStatus(200);
        $response->getBody()->write(json_encode($record_data));
        return $response;
    }

    public function getRecord(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $module = $this->getModuleFromRoute($request);
        chdir('../legacy/');
        $record_id = $request->getAttribute("id");

        $current_time_zone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $disable_date_format = $GLOBALS['disable_date_format'];
        $GLOBALS['disable_date_format'] = true;

        if (!empty($record_id)) {
            $bean = BeanFactory::getBean($module, $record_id);
        } else {
            $bean = BeanFactory::newBean($module);
        }

        date_default_timezone_set($current_time_zone);
        $GLOBALS['disable_date_format'] = $disable_date_format;

        if (empty($bean) || $bean->id !== $record_id) {
            return $response->withStatus(404);
        }
        if (!$bean->ACLAccess('view')) {
            return $response->withStatus(403);
        }

        if (!empty($bean) && $bean->id === $record_id) {
            $record_data = $this->mergeRecordData($bean);
        }
        chdir('../api/');
        $response->getBody()->write(json_encode($record_data));
        return $response;
    }

    public function getRecordLogic(Request $request, Response $response, array $args): Response
    {
        $module = $this->getModuleFromRoute($request);
        $record_id = $request->getAttribute("id");
        $attributes = $request->getAttribute("attributes");
        $triggerFields = $request->getAttribute("triggerFields");
        chdir('../legacy/');
        if (!empty($record_id)) {
            $bean = BeanFactory::getBean($module, $record_id);
            if (empty($bean->id)) {
                $response = $response->withStatus(404);
                return $response;
            }
        } else {
            $bean = BeanFactory::newBean($module);
        }

        foreach ($attributes as $field => $value) {
            $bean->{$field} = $value;
        }
        $result = (new MintLogic($bean))->getChanged($triggerFields);
        chdir('../api/');
        $response->getBody()->write(json_encode($result));
        return $response;
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $module = $this->getModuleFromRoute($request);
        $id = $request->getAttribute('id');
        chdir('../legacy/');
        $f = BeanFactory::getBean($module, $id);
        if (empty($f->id)) {
            $response = $response->withStatus(404);
            return $response;
        } else {
            if (!$f->ACLAccess('delete')) {
                $response = $response->withStatus(403);
                return $response;
            }
        }
        $f->mark_deleted($id);
        chdir('../api/');
        $response = $response->withStatus(200);
        return $response;
    }

    protected function getModuleFromRoute(Request $request): ?string
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        return explode('/', $route->getPattern())[1] ?? null;
    }

    public function subpanelRecords(Request $request, Response $response, array $args): Response
    {
        $module = $this->getModuleFromRoute($request);
        $id = $request->getAttribute('id');
        chdir('../legacy/');
        $focus = LegacyBeanFactory::getBean($module, $id);
        if (empty($focus->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        $related_name = $request->getAttribute('relation_name');
        $page = $request->getQueryParams()['page'] ?? 0;
        $records_per_page = $request->getQueryParams()['paginate_by'] ?? -1;
        require_once 'include/SubPanel/SubPanelDefinitions.php';
        $spd = new \SubPanelDefinitions($focus, $module);
        if (isset($spd->layout_defs['subpanel_setup'][$related_name])) {

            $target_module = $spd->layout_defs['subpanel_setup'][$related_name]['module'];
            $target_bean = LegacyBeanFactory::getBean($target_module);
            if (!$target_bean || !$target_bean->ACLAccess('list')) {
                return $response->withStatus(403);
            }

            require_once 'include/ListView/ListViewSubPanel.php';
            $list_view = new \ListViewSubPanel();
            $subpanel_def = $spd->load_subpanel($related_name);
            $data = $list_view->process_dynamic_listview($module, $focus, $subpanel_def, true, $page, $records_per_page);
            $list = $data['list'];
            chdir('../api/');
            $response = $response->withStatus(200);
            $return_list = [];
            foreach ($list as $record_id => $record) {
                $record->fill_in_additional_detail_fields();
                $return_list[$record_id] = [
                    'id' => $record->id,
                    'module' => $record->module_name,
                    'attributes' => $record->toArray(),
                    'acl_access' => [
                        'edit' => $record->ACLAccess('edit'),
                        'delete' => $record->ACLAccess('delete'),
                        'view' => $record->ACLAccess('view'),
                    ],
                ];
            }
            $return_list['total'] = $data['row_count'];
            $return_list['page'] = (int) $page;
            $response->getBody()->write(json_encode($return_list));
            return $response;
        }
        chdir('../api/');
        $response = $response->withStatus(404);
        return $response;
    }

    public function link(Request $request, Response $response, array $args): Response
    {
        $module = $this->getModuleFromRoute($request);
        $id = $request->getAttribute('id');
        $link_name = $request->getAttribute('link_name');

        chdir('../legacy/');
        $focus = BeanFactory::getBean($module, $id);
        if (empty($focus->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        if (!$focus->ACLAccess('edit')) {
            $response = $response->withStatus(403);
            return $response;
        }
        $ids = $request->getAttribute('ids');

        if (!$focus->load_relationship($link_name) || empty($ids)) {
            $response = $response->withStatus(400);
            return $response;
        }
        $errors = [];
        foreach ($ids as $related_id) {
            $result = $focus->$link_name->add($related_id);
            if (!$result) {
                $errors[] = 'Failed to link ' . $related_id . ' to ' . $focus->id . ' via ' . $link_name;
            }
        }

        chdir('../api/');

        if (!empty($errors)) {
            $response = $response->withStatus(400);
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response;
        }

        $response = $response->withStatus(200);
        return $response;
    }

    public function unlink(Request $request, Response $response, array $args): Response
    {
        $module = $this->getModuleFromRoute($request);
        $id = $request->getAttribute('id');
        $link_name = $request->getAttribute('link_name');

        chdir('../legacy/');
        $focus = BeanFactory::getBean($module, $id);
        if (empty($focus->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        if (!$focus->ACLAccess('edit')) {
            $response = $response->withStatus(403);
            return $response;
        }
        $ids = $request->getAttribute('ids');

        if (!$focus->load_relationship($link_name) || empty($ids)) {
            $response = $response->withStatus(400);
            return $response;
        }
        $errors = [];
        foreach ($ids as $related_id) {
            $result = $focus->$link_name->delete($id, $related_id);
            if (!$result) {
                $errors[] = 'Failed to unlink ' . $related_id . ' from ' . $focus->id . ' via ' . $link_name;
            }
        }

        chdir('../api/');

        if (!empty($errors)) {
            $response = $response->withStatus(400);
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response;
        }

        $response = $response->withStatus(200);
        return $response;
    }

    protected function mergeRecordData($bean)
    {
        return [
            'id' => $bean->id,
            'module' => $bean->module_name,
            'attributes' => $bean->toArray(),
            'acl_access' => [
                'edit' => $bean->ACLAccess('edit'),
                'delete' => $bean->ACLAccess('delete'),
                'view' => $bean->ACLAccess('view'),
                'admin' => $bean->ACLAccess('admin'),
            ],
            'logic' => (new MintLogic($bean))->getInitial(),
        ];
    }
    protected function handleFiles($bean, $files = [])
    {
        if (!empty($files) && is_array($files)) {
            global $sugar_config;
            if (empty($bean->id)) {
                $bean->id = create_guid();
                $bean->new_with_id = true;
            }
            $current_dir = getcwd();
            chdir('../legacy/');
            require_once 'include/SugarObjects/templates/file/File.php';
            $upload_dir = $sugar_config['upload_dir'] ?? 'upload/';
            foreach ($files as $field_name => $base64) {
                $field_type = $bean->field_defs[$field_name]['type'] ?? '';
                if (empty($bean->id) || !in_array($field_type, ['file', 'image'])) {
                    continue;
                }
                $file_name = $bean->id;
                if ('image' === $field_type) {
                    $file_name .= "_{$field_name}";
                }
                $file_name = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $file_name); // Sanitize file name
                if (empty($base64)) {
                    unlink($upload_dir . $file_name);
                } else {
                    $base64_prefix = '';
                    if (strpos($base64, 'data:') === 0) {
                        $base64_prefix = substr($base64, 0, strpos($base64, ';base64,') + 8);
                    }
                    $base64_decoded = base64_decode(str_replace($base64_prefix, '', $base64), true);

                    $tmp_file = tmpfile();
                    fwrite($tmp_file, $base64_decoded);
                    $tmp_file_path = stream_get_meta_data($tmp_file)['uri'];

                    $_FILES[$field_name] = [
                        'name' => $file_name,
                        'type' => 'application/octet-stream',
                        'tmp_name' => $tmp_file_path,
                        'error' => 0,
                        'size' => strlen($base64_decoded),
                    ];
                    $_FILES['filename_file'] = $file_name;
                    $upload_file = new \UploadFile($field_name);
                    $upload_file->set_is_http_upload(false);
                    if ($upload_file->confirm_upload()) {
                        $upload_file->final_move($file_name, $field_name);
                    }
                    fclose($tmp_file);
                }
            }
            chdir($current_dir);
        }
    }

    protected function handleLinks(MintBean $bean, array $links = [])
    {
        if (!empty($links)) {
            $current_dir = getcwd();
            chdir('../legacy/');
            foreach ($links as $link_name => $link_data) {
                if (empty($link_data)) {
                    continue;
                }
                if (!$bean->load_relationship($link_name)) {
                    $GLOBALS['log']->error("Failed to load relationship {$link_name} for module {$bean->module_name} and record {$bean->id}");
                    continue;
                }
                if (!empty($link_data['beansToAdd']) && is_array($link_data['beansToAdd'])) {
                    foreach ($link_data['beansToAdd'] as $related_id => $related_bean) {
                        $additionalValues = $related_bean['additionalValues'] ?? [];
                        $bean->$link_name->add($related_id, $additionalValues);
                    }
                }
                if (!empty($link_data['beansToRemove']) && is_array($link_data['beansToRemove'])) {
                    foreach ($link_data['beansToRemove'] as $related_id) {
                        $bean->$link_name->delete($bean->id, $related_id);
                    }
                }
            }
            chdir($current_dir);
        }
    }

    public function getChecklistItems(Request $request, Response $response, array $args): Response
    {
        $module = $this->getModuleFromRoute($request);
        $id = $request->getAttribute('id');
        $focus = MintBeanFactory::getBean($module, $id);
        if (empty($focus->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        $response = $response->withHeader('Content-type', 'application/json');
        if (isset($focus->checklist) && !empty($focus->checklist)) {
            if (!array($focus->checklist)) {
                $checklistRaw = html_entity_decode($focus->checklist, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $data = json_decode($checklistRaw, true);
            } else {
                $data = $focus->checklist;
            }
            $response->getBody()->write($data);
            return $response;
        }
        $data = [
        ];
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    protected function handleCyclicalRecords(MintBean $bean)
    {
        (new CyclicRecordsSaver($bean, $this->entityManager))->run();
    }
}
