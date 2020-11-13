<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
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

$dictionary["rooms_resources"] = array(        // nazwa relacji
    'true_relationship_type' => 'one-to-one',      // prawdziwy typ relacji (może się różnić od 'relationship_type' w innych relacjach)
    'from_studio' => true,
    'relationships' => array(
       'rooms_resources' => array(                    // nazwa relacji
          'lhs_module' => 'Rooms',                   // nazwa modułu lewej strony (JOIN)
          'lhs_table' => 'rooms',                    // nazwa tablicy w/w modułu
          'lhs_key' => 'id',                            // nazwa kolumny w/w tablicy będącej kluczem w relacji
          'rhs_module' => 'Resources',                   // nazwa modułu prawej strony (JOIN)
          'rhs_table' => 'resources',                    // nazwa tablicy w/w modułu
          'rhs_key' => 'id',                            // nazwa kolumny w/w tablicy będącej kluczem w relacji
          'relationship_type' => 'one-to-one',        // typ relacji
          'join_table' => 'rooms_resources',          // nazwa nowej tabeli relacyjnej
          'join_key_lhs' => 'room_id',           // nazwa kolumny w/w tabeli, w której przechowywane będą wartości kolumny podanej w 'lhs_key'
          'join_key_rhs' => 'resource_id',           // nazwa kolumny w/w tabeli, w której przechowywane będą wartości kolumny podanej w 'rhs_key'
       ),
    ),
    'table' => 'rooms_resources',                  // nazwa nowej tabeli relacyjnej
    'fields' => array(                               // pola w/w tabeli
       array(
          'name' => 'id',
          'type' => 'varchar',
          'len' => 36,
       ),
       array(
          'name' => 'date_modified',
          'type' => 'datetime',
       ),
       array(
          'name' => 'deleted',
          'type' => 'bool',
          'len' => '1',
          'default' => '0',
          'required' => true,
       ),
       array(
          'name' => 'room_id',                      // nazwa kolumny z 'join_key_lhs'
          'type' => 'varchar',
          'len' => 36,
       ),
       array(
          'name' => 'resource_id',                      // nazwa kolumny z 'join_key_rhs'
          'type' => 'varchar',
          'len' => 36,
       ),
    ),
    'indices' => array(                              // założone indeksy na w/w tabelę
       array(
          'name' => 'rooms_resources_spk',               // indeks kolumny id
          'type' => 'primary',
          'fields' => array(
             'id',
          ),
       ),
       array(
          'name' => 'rooms_lhs_alt',                    // indeks kolumny account_lhs_id
          'type' => 'index',
          'fields' => array(
             'room_id',
          ),
       ),
       array(
          'name' => 'resources_rhs_alt',                    // indeks kolumny account_rhs_id
          'type' => 'index',
          'fields' => array(
             'resource_id',
          ),
       ),
    ),
 );
