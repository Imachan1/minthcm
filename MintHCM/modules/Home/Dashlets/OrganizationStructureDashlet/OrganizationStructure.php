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
 * the terms of the GNU Affero General Public License version 3 AS published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 AS permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
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
 * of this program must display Appropriate Legal Notices, AS required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM"
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo.
 * If the display of the logos is not reasonably feasible for technical reasons, the
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

class OrganizationStructure
{
    protected function getQuery()
    {
        //organizationalunits -> securitygroups
        //ou.type -> group_type ='department', 'team'
        $siteURL = $GLOBALS['sugar_config']['site_url'];
        return "SELECT
                concat('{\"name\":\"',ou.name,'\"}') as text
                , ou.group_type AS 'HTMLclass'
                , concat('_',md5(ou.id)) AS oid
                , if(ou.parent_id is null or ou.parent_id='', '', concat('_',md5(ou.parent_id))) AS parent_id
                , if(ou.parent_id is null or ou.parent_id='', '', concat('_',md5(ou.parent_id))) AS parent_id2
                , if(ou.parent_id='', false, ou.group_type='department')  as  collapsed
            FROM securitygroups ou
            WHERE ou.deleted=0 AND ou.group_type IN ('department', 'team')
        UNION ALL
            SELECT
                -- concat('{\"name\":\"',p.name,'\",\"title\":\"',u.first_name, ' ',u.last_name,'\"}') as text
                concat('{\"name\":\"',p.name,'\",\"title\": {\"val\": \"',u.first_name, ' ',u.last_name,'\", \"href\":\"{$siteURL}/index.php?module=Employees&action=DetailView&record=',u.id,'\"}}') as text
                , ' '  AS 'HTMLclass'
                , concat('_',md5(concat(ou.id,u.id))) AS oid
                , concat('_',md5( ou.id)) AS parent_id
                , concat('_',md5( ou.id)) AS parent_id2
                , '' as  collapsed
            FROM securitygroups ou
            INNER JOIN users u ON ou.current_manager_id=u.id and u.deleted=0
            INNER JOIN positions p on u.position_id = p.id
            WHERE ou.deleted=0  AND ou.group_type IN ('department', 'team')
        UNION ALL
            SELECT
                -- concat('{\"name\":\"',p.name,'\",\"title\":\"',u.first_name, ' ',u.last_name,'\"}') as text
                concat('{\"name\":\"',p.name,'\",\"title\": {\"val\": \"',u.first_name, ' ',u.last_name,'\", \"href\":\"{$siteURL}/index.php?module=Employees&action=DetailView&record=',u.id,'\"}}') as text
                , '-' AS 'HTMLclass'
                , concat('_',md5(concat(ou.id,u.id))) AS oid
                , concat('_',md5(concat(ou.id,u.reports_to_id))) AS parent_id
                , concat('_',md5(concat(ou.id,ou.current_manager_id))) AS parent_id2
                , '' as  collapsed
            FROM
                securitygroups ou
            INNER JOIN users u
                ON u.organizationalunit_id = ou.id  and u.id !=ou.current_manager_id
            INNER JOIN positions p on u.position_id = p.id
            WHERE u.status='Active'  AND ou.group_type IN ('department', 'team')
";

    }
    protected function getDateBySQL()
    {
        global $db;
        $sql = $this->getQuery();
        $sql_result = $db->query($sql);
        $ous = [];
        while ($row = $db->fetchByAssoc($sql_result)) {
            $ous[] = $row;
        };
        return $ous;
    }
    protected function getDateArray()
    {
        $organizationalunits = array(
            array('text' => '{"name":"Software Development department"}', 'HTMLclass' => 'department', 'oid' => '_4ad74fd8236adfb21b863cb54b05850a', 'parent_id' => '_fe7ad33874a5099ae352785feab92fd0'),
            array('text' => '{"name":"HR & Office team"}', 'HTMLclass' => 'team', 'oid' => '_396c8b014c3e1b0c2a2c5ce55faceed9', 'parent_id' => '_a9eb6d584e6e5b7c622f42d3ac7d2ef8'),
            array('text' => '{"name":"Sales & Marketing department"}', 'HTMLclass' => 'department', 'oid' => '_7b59b958e99c45aaf4b7d44d463ce4bb', 'parent_id' => '_fe7ad33874a5099ae352785feab92fd0'),
            array('text' => '{"name":"Business Support department"}', 'HTMLclass' => 'department', 'oid' => '_a9eb6d584e6e5b7c622f42d3ac7d2ef8', 'parent_id' => '_fe7ad33874a5099ae352785feab92fd0'),
            array('text' => '{"name":"IT Business Analysis department"}', 'HTMLclass' => 'department', 'oid' => '_44494baf2a517539dd1d90c5e5dcaa90', 'parent_id' => '_fe7ad33874a5099ae352785feab92fd0'),
            array('text' => '{"name":"Marketing team"}', 'HTMLclass' => 'team', 'oid' => '_a2d75ae596ff0e82b7c897252fe1d878', 'parent_id' => '_7b59b958e99c45aaf4b7d44d463ce4bb'),
            array('text' => '{"name":"Sales team"}', 'HTMLclass' => 'team', 'oid' => '_8e0ff4a2d63ee7c9dd36d53fe751cd42', 'parent_id' => '_7b59b958e99c45aaf4b7d44d463ce4bb'),
            array('text' => '{"name":"Management Board"}', 'HTMLclass' => 'department', 'oid' => '_fe7ad33874a5099ae352785feab92fd0', 'parent_id' => ''),

            array('text' => '{"name":"Head of Software Development","title":"Aleksandra Mazurek"}', 'HTMLclass' => '', 'oid' => '_9154eeb07b8f569cbc8c99ec1f2cce5c', 'parent_id' => '_4ad74fd8236adfb21b863cb54b05850a'),
            array('text' => '{"name":"Chief Executive Officer","title":"Marcin Różański"}', 'HTMLclass' => '', 'oid' => '_75012a6d800bd4a3406161b09566362f', 'parent_id' => '_396c8b014c3e1b0c2a2c5ce55faceed9'),
            array('text' => '{"name":"Chief Executive Officer","title":"Marcin Różański"}', 'HTMLclass' => '', 'oid' => '_00ae2fe1b0ae3084b98dd2d18a7db54f', 'parent_id' => '_7b59b958e99c45aaf4b7d44d463ce4bb'),
            array('text' => '{"name":"Chief Executive Officer","title":"Marcin Różański"}', 'HTMLclass' => '', 'oid' => '_e5fe6fa1418df82e2f5644111c43d11a', 'parent_id' => '_a9eb6d584e6e5b7c622f42d3ac7d2ef8'),
            array('text' => '{"name":"Chief Operating Officer","title":"Magdalena Ziębińska"}', 'HTMLclass' => '', 'oid' => '_2cbe02f85ea572d7ca8341e9bb65a481', 'parent_id' => '_44494baf2a517539dd1d90c5e5dcaa90'),
            array('text' => '{"name":"Chief Executive Officer","title":"Marcin Różański"}', 'HTMLclass' => '', 'oid' => '_976c9f9def696dab7ab324a2ec388afa', 'parent_id' => '_a2d75ae596ff0e82b7c897252fe1d878'),
            array('text' => '{"name":"Head of Sales","title":"Sławomir Wnuk"}', 'HTMLclass' => '', 'oid' => '_e650d9bc3e15d01648bf3a64cbe9c766', 'parent_id' => '_8e0ff4a2d63ee7c9dd36d53fe751cd42'),
            array('text' => '{"name":"Chief Executive Officer","title":"Marcin Różański"}', 'HTMLclass' => '', 'oid' => '_c93eda1f3435b148da7f3e59248e571a', 'parent_id' => '_fe7ad33874a5099ae352785feab92fd0'),

            array('text' => '{"name":"Sales Support Specialist","title":"Kamil Ograbisz"}', 'HTMLclass' => ' ', 'oid' => '_4ddd7b24f7b299cec0259693374d982e', 'parent_id' => '_e650d9bc3e15d01648bf3a64cbe9c766', 'parent_id2' => '_e650d9bc3e15d01648bf3a64cbe9c766'),
            array('text' => '{"name":"Presales Engineer","title":"Maciej Jankiewicz"}', 'HTMLclass' => ' ', 'oid' => '_4caf753420663e97db0767db62d53fac', 'parent_id' => '_e650d9bc3e15d01648bf3a64cbe9c766', 'parent_id2' => '_e650d9bc3e15d01648bf3a64cbe9c766'),
            array('text' => '{"name":"IT Business Analysis Team Leader","title":"Anna Łakoma"}', 'HTMLclass' => ' ', 'oid' => '_a082b67fea1914e51e8e52801181219c', 'parent_id' => '_2cbe02f85ea572d7ca8341e9bb65a481', 'parent_id2' => '_2cbe02f85ea572d7ca8341e9bb65a481'),
            array('text' => '{"name":"PHP/JS Developer","title":"Szymon Rydza"}', 'HTMLclass' => ' ', 'oid' => '_b0a6081ef274f93d90da87306d26ece4', 'parent_id' => '_ec6b7b5b28ea32825581e69820091ec4', 'parent_id2' => '_9154eeb07b8f569cbc8c99ec1f2cce5c'),
            array('text' => '{"name":"Junior Tester","title":"Michał Michalak"}', 'HTMLclass' => ' ', 'oid' => '_97246a4b2248fa0d1d5c8061ef5e27f1', 'parent_id' => '_9154eeb07b8f569cbc8c99ec1f2cce5c', 'parent_id2' => '_9154eeb07b8f569cbc8c99ec1f2cce5c'),
            array('text' => '{"name":"Junior PHP/JS Developer","title":"Marcin Gawronek"}', 'HTMLclass' => ' ', 'oid' => '_2a66ce0631c0a391b22a56c018c4560b', 'parent_id' => '_d7abc9468993879dfecc7882e3e9a804', 'parent_id2' => '_9154eeb07b8f569cbc8c99ec1f2cce5c'),
            array('text' => '{"name":"Office Manager","title":"Katarzyna Myszka"}', 'HTMLclass' => ' ', 'oid' => '_f67b050bc5fbba6432f2cf5feef5ede2', 'parent_id' => '_75012a6d800bd4a3406161b09566362f', 'parent_id2' => '_75012a6d800bd4a3406161b09566362f'),
            array('text' => '{"name":"Chief Operating Officer","title":"Magdalena Ziębińska"}', 'HTMLclass' => ' ', 'oid' => '_e5acc386540b39702694ef2679dbf20e', 'parent_id' => '_fe7ad33874a5099ae352785feab92fd0', 'parent_id2' => '_c93eda1f3435b148da7f3e59248e571a'),
            array('text' => '{"name":"HR Business Partner","title":"Jakub Zieliński"}', 'HTMLclass' => ' ', 'oid' => '_53a9f67df4686f917135da358b8ac695', 'parent_id' => '_75012a6d800bd4a3406161b09566362f', 'parent_id2' => '_75012a6d800bd4a3406161b09566362f'),
            array('text' => '{"name":"Software Development Team Leader","title":"Michał Nowacki"}', 'HTMLclass' => ' ', 'oid' => '_ec6b7b5b28ea32825581e69820091ec4', 'parent_id' => '_9154eeb07b8f569cbc8c99ec1f2cce5c', 'parent_id2' => '_9154eeb07b8f569cbc8c99ec1f2cce5c'),
            array('text' => '{"name":"Web Developer","title":"Michał Dudziński"}', 'HTMLclass' => ' ', 'oid' => '_9042dc79a2c4f298053afa2957481ae5', 'parent_id' => '_cfb2d3109155c9b30c4a8e8c7ce30e01', 'parent_id2' => '_976c9f9def696dab7ab324a2ec388afa'),
            array('text' => '{"name":"PHP/JS Developer","title":"Marek Domagalski"}', 'HTMLclass' => ' ', 'oid' => '_e86b21e7965f8ec53a6d8a591179c8b7', 'parent_id' => '_ec6b7b5b28ea32825581e69820091ec4', 'parent_id2' => '_9154eeb07b8f569cbc8c99ec1f2cce5c'),
            array('text' => '{"name":"Marketing Team Leader","title":"Joanna Radecka"}', 'HTMLclass' => ' ', 'oid' => '_cfb2d3109155c9b30c4a8e8c7ce30e01', 'parent_id' => '_976c9f9def696dab7ab324a2ec388afa', 'parent_id2' => '_976c9f9def696dab7ab324a2ec388afa'),
            array('text' => '{"name":"Junior Tester","title":"Ewelina Milecka"}', 'HTMLclass' => ' ', 'oid' => '_e83f8e98a34b9659bd61a83b73a7bece', 'parent_id' => '_9154eeb07b8f569cbc8c99ec1f2cce5c', 'parent_id2' => '_9154eeb07b8f569cbc8c99ec1f2cce5c'),
            array('text' => '{"name":"Sales Support Specialist","title":"Kamil Ograbisz"}', 'HTMLclass' => ' ', 'oid' => '_cd1d8f1a0f421d0f1454a23476f6504b', 'parent_id' => '_e650d9bc3e15d01648bf3a64cbe9c766', 'parent_id2' => '_e650d9bc3e15d01648bf3a64cbe9c766'),
            array('text' => '{"name":"HR & Employer Branding Specialist","title":"Marta Mazurek"}', 'HTMLclass' => ' ', 'oid' => '_b4f1592757296874073dd1d74b5668d9', 'parent_id' => '_75012a6d800bd4a3406161b09566362f', 'parent_id2' => '_75012a6d800bd4a3406161b09566362f'),
            array('text' => '{"name":"Senior PHP/JS Developer","title":"Łukasz Kończak"}', 'HTMLclass' => ' ', 'oid' => '_ae47d93cf9718e5f5a7d9a6e33a57be9', 'parent_id' => '_ec6b7b5b28ea32825581e69820091ec4', 'parent_id2' => '_9154eeb07b8f569cbc8c99ec1f2cce5c'),
            array('text' => '{"name":"IT Business Analyst","title":"Bartosz Burzyński"}', 'HTMLclass' => ' ', 'oid' => '_0e67370d2e855e9d4b9b50d7b9b6e991', 'parent_id' => '_a082b67fea1914e51e8e52801181219c', 'parent_id2' => '_2cbe02f85ea572d7ca8341e9bb65a481'),
            array('text' => '{"name":"IT Account Manager","title":"Janusz Sobczak"}', 'HTMLclass' => ' ', 'oid' => '_f0776ed9ac6bf127b91e8450749f0cb4', 'parent_id' => '_e650d9bc3e15d01648bf3a64cbe9c766', 'parent_id2' => '_e650d9bc3e15d01648bf3a64cbe9c766'),
            array('text' => '{"name":"PHP/JS Developer","title":"Dawid Brezwan"}', 'HTMLclass' => ' ', 'oid' => '_d7abc9468993879dfecc7882e3e9a804', 'parent_id' => '_ec6b7b5b28ea32825581e69820091ec4', 'parent_id2' => '_9154eeb07b8f569cbc8c99ec1f2cce5c'),
            array('text' => '{"name":"Senior PHP/JS Developer","title":"Tomasz Szykuła"}', 'HTMLclass' => ' ', 'oid' => '_eead692e224b139f18e8d629eabadaf7', 'parent_id' => '_ec6b7b5b28ea32825581e69820091ec4', 'parent_id2' => '_9154eeb07b8f569cbc8c99ec1f2cce5c'),
            array('text' => '{"name":"IT Administrator","title":"Szymon Nitka"}', 'HTMLclass' => ' ', 'oid' => '_8c5f5a4b84ffe7759101ff8af4f133d2', 'parent_id' => '_e5fe6fa1418df82e2f5644111c43d11a', 'parent_id2' => '_e5fe6fa1418df82e2f5644111c43d11a'),
            array('text' => '{"name":"Lead Generation & Social Media Specialist","title":"Sebastian Osses"}', 'HTMLclass' => ' ', 'oid' => '_c9c1a8fca91f093d7c522f9ef4c3b94b', 'parent_id' => '_cfb2d3109155c9b30c4a8e8c7ce30e01', 'parent_id2' => '_976c9f9def696dab7ab324a2ec388afa'),

        );
        return $organizationalunits;
    }
    protected function getDate()
    {
        return $this->getDateBySQL();
        return $this->getDateArray();
    }

    protected function buildTree(array &$elements, $parentId = '', $parent2 = false)
    {
        $branch = array();

        foreach ($elements as $k => $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['oid']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
                $elements[$k]['assigned'] = 1;
            }
        }
        if ($parentId == '' && $parent2 === false && array_sum(array_column($elements, 'assigned')) != count($elements)) {
            foreach ($elements as $k => $element) {
                if (!empty($element['parent_id2']) && $element['parent_id2'] == $parentId) {
                    $children = $this->buildTree($elements, $element['oid']);
                    if ($children) {
                        $element['children'] = $children;
                    }
                    $branch[] = $element;
                    $elements[$k]['assigned'] = 1;
                }
            }
        }

        return $branch;
    }

    public function getTree()
    {
        $organizationalunits = $this->getDate();
        array_walk($organizationalunits, function (&$element, $key) {
            $t = json_decode(stripslashes(htmlspecialchars_decode($element["text"])));
            if ($t) {
                $element["text"] = $t;
            }

            if (empty($element["collapsed"])) {
                unset($element["collapsed"]);
            }

        });
        $tree = $this->buildTree($organizationalunits);
        return json_encode($tree);
    }
}
