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
 * Copyright (C) 2018-2019 MintHCM
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
$module_name              = 'Rooms';
$dictionary[$module_name] = array(
    'table' => 'rooms',
    'audited' => true,
    'inline_edit' => false,
    'duplicate_merge' => true,
    'fields' => array(
        'number_of_seats' => 
        array (
            'required' => false,
            'name' => 'number_of_seats',
            'vname' => 'LBL_NUMBER_OF_SEATS',
            'type' => 'int',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => '255',
            'size' => '20',
            'enable_range_search' => false,
            'disable_num_format' => '',
            'min' => false,
            'max' => false,
        ),
        'room_surface' => 
        array (
            'required' => false,
            'name' => 'room_surface',
            'vname' => 'LBL_ROOM_SURFACE',
            'type' => 'float',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => '18',
            'size' => '20',
            'enable_range_search' => false,
            'precision' => '2',
            'vt_validation' => "AEM(ifElse(or(empty(\$room_surface),equals(0,\$room_surface)),true,ifElse(greaterThan(\$room_surface, 0),true,false)),'LBL_NEGATIVE_SURFACE')",
        ),
        'room_plan' => 
        array (
            'required' => false,
            'name' => 'room_plan',
            'vname' => 'LBL_ROOM_PLAN',
            'type' => 'image',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 255,
            'size' => '20',
            'studio' => 'visible',
            'dbType' => 'varchar',
            'border' => '',
            'width' => '120',
            'height' => '',
        ),
        'reservation_type' =>
        array(
            'required' => true,
            'name' => 'reservation_type',
            'vname' => 'LBL_RESERVATION_TYPE',
            'type' => 'enum',
            'massupdate' => '1',
            'default' => 'false',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '1',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'reservation_type_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'availability' => array(
            'required' => true,
            'name' => 'availability',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'massupdate' => 'false',
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 100,
            'size' => '20',
            'options' => 'workplace_room_status',
            'studio' => 'visible',
        ),
        "securitygroups_rooms" => array(                  // nazwa relacji
            'name' => 'securitygroups_rooms',                 // nazwa relacji
            'type' => 'link',
            'relationship' => 'securitygroups_rooms',         // nazwa relacji
            'source' => 'non-db',
            'module' => 'SecurityGroups',                         // nazwa pierwszego modułu
            'bean_name' => 'SecurityGroup',                       // nazwa bean'a pierwszego modułu
            'vname' => 'LBL_RELATIONSHIP_SECURITY_GROUP_NAME',     // etykieta relacji (może być taka sama jak nazwa pola relacyjnego pierwszego modułu)
            'id_name' => 'security_group_id',                      // nazwa pola id, które będzie reprezentować relacja
         ),
         "security_group_name" => array(                        // nazwa pola name
            'required' => true,
            'name' => 'security_group_name',                       // nazwa pola name
            'type' => 'relate',                              // typ pola: relacja
            'source' => 'non-db',
            'vname' => 'LBL_RELATIONSHIP_SECURITY_GROUP_NAME',     // etykieta pola relacji (może być taka sama jak nazwa pola relacyjnego pierwszego modułu)
            'id_name' => 'security_group_id',                      // nazwa pola id, które będzie reprezentować relacja
            'link' => 'securitygroups_rooms',                 // nazwa relacji
            'module' => 'SecurityGroups',                         // nazwa pierwszego modułu
            'table' => 'securitygroups',                          // nazwa tabeli pierwszego modułu 
            'rname' => 'name',
            'vt_validation' => "AEM(callCustomApi(Rooms,canSelectSecurityGroup,\$security_group_id),'LBL_ERR_CANT_SELECT_SEC_GROUP')",
         ),
         "security_group_id" => array(
            'name' => 'security_group_id',                         // nazwa pola id, które będzie reprezentować relacja
            'relationship' => 'securitygroups_rooms',         // nazwa relacji
            'type' => 'id',                                  // typ pola: id
            'vname' => 'LBL_RELATIONSHIP_SECURITY_GROUP_ID',       // etykieta id relacji
         ),
         "rooms_resources" => array ( // nazwa relacji
            'name' => 'rooms_resources',                              // nazwa relacji
            'type' => 'link',
            'relationship' => 'rooms_resources',                     // nazwa relacji
            'source' => 'non-db',
            'module' => 'Resources',                                    // nazwa przeciwnego modułu
            'bean_name' => 'Resources',                                   // nazwa przeciwnego bean'a
            'vname' => 'LBL_ROOMS_RESOURCES_TITLE',                  // nazwa etykiety relacji, może to być np. Powiązany błąd
            'id_name' => 'resource_id',                                 // pole które będzie definiowało ID rekordu po drugiej stronie
         ),
          "resource_name" => array (  // nazwa pola z nazwą
            'name' => 'resource_name',                          // nazwa pola z nazwą
            'type' => 'relate',
            'source' => 'non-db',                         // pole nie musi być przechowywane w bazie - odpowiada za to tabela pośrednia
            'vname' => 'LBL_RESOURCE_NAME',                    // etykieta dla pola z nazwą, pole będzie głównie widocznym polem w widokach (np. Powiązany błąd)
            'save' => true,
            'id_name' => 'resource_id',                       // pole które będzie definiowało ID rekordu po drugiej stronie
            'link' => 'rooms_resources',                     // nazwa relacji
            'table' => 'resources',                              // nazwa tabeli dla przeciwnego modułu
            'module' => 'Resources',                             // nazwa przeciwnego modułu
            'rname' => 'name',                              // pole po drugiej stronie, które jest powiązane z tym polem
          ),
          "resource_id" => array ( // pole które będzie definiowało ID rekordu po drugiej stronie
            'name' => 'resource_id',                                  // pole które będzie definiowało ID rekordu po drugiej stronie
            'type' => 'link',
            'relationship' => 'rooms_resources',                   // nazwa relacji
            'source' => 'non-db',                                // pole nie musi być przechowywane w bazie - odpowiada za to tabela pośrednia
            'reportable' => false,
            'side' => 'left',
            'vname' => 'LBL_RESOURCE_ID',                             // etykieta dla pola z id, może to być np. Powiązany błąd (ID)
          ),
    ),
    'relationships' => array(
        "securitygroups_rooms" => array(                  // nazwa relacji
            'lhs_module' => 'SecurityGroups',                     // nazwa pierwszego modułu
            'lhs_table' => 'securitygroups',                      // nazwa tablicy pierwszego modułu
            'lhs_key' => 'id',                               // pole id po którym ma zostać wybrany rekord z pierwszego modułu
            'rhs_module' => 'Rooms',                     // nazwa drugiego modułu
            'rhs_table' => 'rooms',                      // nazwa tablicy drugiego modułu
            'rhs_key' => 'security_group_id',                      // nazwa pola id (kolumny), które zostanie utworzone w tablicy drugiego modułu, aby przechowywać id powiązanego rekordu pierwszego modułu
            'relationship_type' => 'one-to-many',            // typ relacji
         ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}

VardefManager::createVardef($module_name, $module_name,
    array(
    'basic',
    'assignable',
    'security_groups'
));

$dictionary['Rooms']['fields']['assigned_user_name']['required'] = true;
$dictionary['Rooms']['fields']['assigned_user_name']['audited'] = true;
$dictionary['Rooms']['fields']['assigned_user_id']['audited'] = false;
$dictionary['Rooms']['fields']['name']['audited'] = true;

$dictionary["Rooms"]["fields"]["rooms_workplaces"] = array (
    'name' => 'rooms_workplaces',
    'type' => 'link',
    'relationship' => 'rooms_workplaces',        // nazwa relacji
    'source' => 'non-db',
    'module' => 'Workplaces',                        // nazwa drugiego modułu
    'bean_name' => 'Workplaces',                       // nazwa bean'a drugiego modułu
    'vname' => 'LBL_RELATIONSHIP_WORKPLACES_NAME',   // etykieta nazwy relacji (może być taka sama jak nazwa subpanelu drugiego modułu)
    'side' => 'right',
 );