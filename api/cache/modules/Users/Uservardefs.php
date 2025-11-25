<?php 
 $GLOBALS["dictionary"]["User"]=array (
  'table' => 'users',
  'fields' => 
  array (
    'id' => 
    array (
      'name' => 'id',
      'vname' => 'LBL_ID',
      'type' => 'id',
      'required' => true,
    ),
    'user_name' => 
    array (
      'name' => 'user_name',
      'vname' => 'LBL_USER_NAME',
      'type' => 'user_name',
      'dbType' => 'varchar',
      'len' => '60',
      'importable' => 'required',
      'required' => true,
      'studio' => 
      array (
        'no_duplicate' => true,
        'editview' => false,
        'detailview' => true,
        'quickcreate' => false,
        'basic_search' => false,
        'advanced_search' => false,
      ),
      'api-visible' => false,
      'vt_validation' => 'AEM(isUnique(\'user_name\',$user_name),\'ERR_USER_NAME_EXISTS_3\')',
    ),
    'user_hash' => 
    array (
      'name' => 'user_hash',
      'vname' => 'LBL_USER_HASH',
      'type' => 'varchar',
      'len' => '255',
      'reportable' => false,
      'importable' => 'false',
      'sensitive' => true,
      'studio' => 
      array (
        'no_duplicate' => true,
        'listview' => false,
        'searchview' => false,
      ),
      'api-visible' => false,
    ),
    'system_generated_password' => 
    array (
      'name' => 'system_generated_password',
      'vname' => 'LBL_SYSTEM_GENERATED_PASSWORD',
      'type' => 'bool',
      'required' => true,
      'reportable' => false,
      'massupdate' => false,
      'studio' => 
      array (
        'listview' => false,
        'searchview' => false,
        'editview' => false,
        'quickcreate' => false,
      ),
      'api-visible' => false,
    ),
    'pwd_last_changed' => 
    array (
      'name' => 'pwd_last_changed',
      'vname' => 'LBL_PSW_MODIFIED',
      'type' => 'datetime',
      'required' => false,
      'massupdate' => false,
      'studio' => 
      array (
        'formula' => false,
      ),
      'api-visible' => false,
    ),
    'authenticate_id' => 
    array (
      'name' => 'authenticate_id',
      'vname' => 'LBL_AUTHENTICATE_ID',
      'type' => 'varchar',
      'len' => '100',
      'reportable' => false,
      'importable' => 'false',
      'studio' => 
      array (
        'listview' => false,
        'searchview' => false,
        'related' => false,
      ),
      'api-visible' => false,
    ),
    'sugar_login' => 
    array (
      'name' => 'sugar_login',
      'vname' => 'LBL_SUITE_LOGIN',
      'type' => 'bool',
      'default' => '1',
      'reportable' => false,
      'massupdate' => false,
      'importable' => false,
      'studio' => 
      array (
        'listview' => false,
        'searchview' => false,
        'formula' => false,
      ),
      'api-visible' => false,
    ),
    'first_name' => 
    array (
      'name' => 'first_name',
      'vname' => 'LBL_FIRST_NAME',
      'dbType' => 'varchar',
      'type' => 'name',
      'len' => '255',
    ),
    'last_name' => 
    array (
      'name' => 'last_name',
      'vname' => 'LBL_LAST_NAME',
      'dbType' => 'varchar',
      'type' => 'name',
      'len' => '255',
      'importable' => 'required',
      'required' => true,
    ),
    'date_indexed' => 
    array (
      'name' => 'date_indexed',
      'vname' => 'LBL_DATE_INDEXED',
      'type' => 'datetime',
      'comment' => 'Date record last indexed',
      'enable_range_search' => true,
      'options' => 'date_range_search_dom',
      'inline_edit' => false,
    ),
    'full_name' => 
    array (
      'name' => 'full_name',
      'rname' => 'full_name',
      'vname' => 'LBL_NAME',
      'type' => 'name',
      'fields' => 
      array (
        0 => 'first_name',
        1 => 'last_name',
      ),
      'source' => 'non-db',
      'sort_on' => 'last_name',
      'sort_on2' => 'first_name',
      'db_concat_fields' => 
      array (
        0 => 'first_name',
        1 => 'last_name',
      ),
      'len' => '510',
      'studio' => 
      array (
        'formula' => false,
      ),
    ),
    'name' => 
    array (
      'name' => 'name',
      'rname' => 'name',
      'vname' => 'LBL_NAME',
      'type' => 'varchar',
      'source' => 'non-db',
      'len' => '510',
      'db_concat_fields' => 
      array (
        0 => 'first_name',
        1 => 'last_name',
      ),
      'importable' => 'false',
    ),
    'is_admin' => 
    array (
      'name' => 'is_admin',
      'vname' => 'LBL_IS_ADMIN',
      'type' => 'bool',
      'default' => '0',
      'studio' => 
      array (
        'listview' => false,
        'searchview' => false,
        'related' => false,
      ),
      'api-visible' => false,
    ),
    'external_auth_only' => 
    array (
      'name' => 'external_auth_only',
      'vname' => 'LBL_EXT_AUTHENTICATE',
      'type' => 'bool',
      'reportable' => false,
      'massupdate' => false,
      'default' => '0',
      'studio' => 
      array (
        'listview' => false,
        'searchview' => false,
        'related' => false,
      ),
      'api-visible' => false,
    ),
    'receive_notifications' => 
    array (
      'name' => 'receive_notifications',
      'vname' => 'LBL_RECEIVE_NOTIFICATIONS',
      'type' => 'bool',
      'default' => '1',
      'massupdate' => false,
      'studio' => false,
    ),
    'description' => 
    array (
      'name' => 'description',
      'vname' => 'LBL_DESCRIPTION',
      'type' => 'text',
    ),
    'date_entered' => 
    array (
      'name' => 'date_entered',
      'vname' => 'LBL_DATE_ENTERED',
      'type' => 'datetime',
      'required' => true,
      'studio' => 
      array (
        'editview' => false,
        'quickcreate' => false,
      ),
    ),
    'date_modified' => 
    array (
      'name' => 'date_modified',
      'vname' => 'LBL_DATE_MODIFIED',
      'type' => 'datetime',
      'required' => true,
      'studio' => 
      array (
        'editview' => false,
        'quickcreate' => false,
      ),
    ),
    'modified_user_id' => 
    array (
      'name' => 'modified_user_id',
      'rname' => 'user_name',
      'id_name' => 'modified_user_id',
      'vname' => 'LBL_MODIFIED_BY_ID',
      'type' => 'assigned_user_name',
      'table' => 'users',
      'isnull' => 'false',
      'dbType' => 'id',
    ),
    'modified_by_name' => 
    array (
      'name' => 'modified_by_name',
      'vname' => 'LBL_MODIFIED_BY',
      'type' => 'varchar',
      'source' => 'non-db',
      'studio' => false,
    ),
    'created_by' => 
    array (
      'name' => 'created_by',
      'rname' => 'user_name',
      'id_name' => 'modified_user_id',
      'vname' => 'LBL_ASSIGNED_TO',
      'type' => 'assigned_user_name',
      'table' => 'users',
      'isnull' => 'false',
      'dbType' => 'id',
      'studio' => false,
    ),
    'created_by_name' => 
    array (
      'name' => 'created_by_name',
      'vname' => 'LBL_CREATED_BY_NAME',
      'type' => 'varchar',
      'source' => 'non-db',
      'importable' => 'false',
    ),
    'title' => 
    array (
      'name' => 'title',
      'vname' => 'LBL_TITLE',
      'type' => 'varchar',
      'len' => '50',
    ),
    'photo' => 
    array (
      'name' => 'photo',
      'vname' => 'LBL_PHOTO',
      'type' => 'image',
      'massupdate' => false,
      'comments' => '',
      'help' => '',
      'importable' => false,
      'reportable' => true,
      'len' => 255,
      'dbType' => 'varchar',
      'width' => '160',
      'height' => '160',
    ),
    'department' => 
    array (
      'name' => 'department',
      'vname' => 'LBL_DEPARTMENT',
      'type' => 'varchar',
      'len' => '50',
    ),
    'phone_home' => 
    array (
      'name' => 'phone_home',
      'vname' => 'LBL_HOME_PHONE',
      'type' => 'phone',
      'dbType' => 'varchar',
      'len' => '50',
    ),
    'phone_mobile' => 
    array (
      'name' => 'phone_mobile',
      'vname' => 'LBL_MOBILE_PHONE',
      'type' => 'phone',
      'dbType' => 'varchar',
      'len' => '50',
    ),
    'phone_work' => 
    array (
      'name' => 'phone_work',
      'vname' => 'LBL_WORK_PHONE',
      'type' => 'phone',
      'dbType' => 'varchar',
      'len' => '50',
    ),
    'phone_other' => 
    array (
      'name' => 'phone_other',
      'vname' => 'LBL_OTHER_PHONE',
      'type' => 'phone',
      'dbType' => 'varchar',
      'len' => '50',
    ),
    'phone_fax' => 
    array (
      'name' => 'phone_fax',
      'vname' => 'LBL_FAX_PHONE',
      'type' => 'phone',
      'dbType' => 'varchar',
      'len' => '50',
    ),
    'status' => 
    array (
      'name' => 'status',
      'vname' => 'LBL_STATUS',
      'type' => 'enum',
      'len' => 100,
      'options' => 'user_status_dom',
      'importable' => 'required',
      'required' => true,
      'api-visible' => false,
      'default' => 'Inactive',
    ),
    'primary_address_street' => 
    array (
      'name' => 'primary_address_street',
      'vname' => 'LBL_ADDRESS_STREET',
      'type' => 'varchar',
      'len' => '150',
    ),
    'primary_address_city' => 
    array (
      'name' => 'primary_address_city',
      'vname' => 'LBL_ADDRESS_CITY',
      'type' => 'varchar',
      'len' => '100',
    ),
    'primary_address_state' => 
    array (
      'name' => 'primary_address_state',
      'vname' => 'LBL_ADDRESS_STATE',
      'type' => 'varchar',
      'len' => '100',
    ),
    'primary_address_country' => 
    array (
      'name' => 'primary_address_country',
      'vname' => 'LBL_ADDRESS_COUNTRY',
      'type' => 'varchar',
      'len' => 100,
    ),
    'primary_address_postalcode' => 
    array (
      'name' => 'primary_address_postalcode',
      'vname' => 'LBL_ADDRESS_POSTALCODE',
      'type' => 'varchar',
      'len' => '20',
    ),
    'UserType' => 
    array (
      'name' => 'UserType',
      'vname' => 'LBL_USER_TYPE',
      'type' => 'enum',
      'len' => 50,
      'options' => 'user_type_dom',
      'source' => 'non-db',
      'import' => false,
      'reportable' => false,
      'studio' => 
      array (
        'formula' => false,
      ),
    ),
    'deleted' => 
    array (
      'name' => 'deleted',
      'vname' => 'LBL_DELETED',
      'type' => 'bool',
      'required' => false,
      'reportable' => false,
    ),
    'portal_only' => 
    array (
      'name' => 'portal_only',
      'vname' => 'LBL_PORTAL_ONLY_USER',
      'type' => 'bool',
      'massupdate' => false,
      'default' => '0',
      'studio' => 
      array (
        'listview' => false,
        'searchview' => false,
        'formula' => false,
      ),
    ),
    'show_on_employees' => 
    array (
      'name' => 'show_on_employees',
      'vname' => 'LBL_SHOW_ON_EMPLOYEES',
      'type' => 'bool',
      'massupdate' => true,
      'importable' => true,
      'default' => true,
      'studio' => 
      array (
        'formula' => false,
      ),
    ),
    'employee_status' => 
    array (
      'name' => 'employee_status',
      'vname' => 'LBL_EMPLOYEE_STATUS',
      'type' => 'enum',
      'function' => 
      array (
        'name' => 'getEmployeeStatusOptions',
        'include' => 'modules/Employees/EmployeeStatus.php',
      ),
      'len' => 100,
    ),
    'messenger_id' => 
    array (
      'name' => 'messenger_id',
      'vname' => 'LBL_MESSENGER_ID',
      'type' => 'varchar',
      'len' => 100,
    ),
    'messenger_type' => 
    array (
      'name' => 'messenger_type',
      'vname' => 'LBL_MESSENGER_TYPE',
      'type' => 'enum',
      'options' => 'messenger_type_dom',
      'len' => 100,
    ),
    'calls' => 
    array (
      'name' => 'calls',
      'type' => 'link',
      'relationship' => 'calls_users',
      'source' => 'non-db',
      'vname' => 'LBL_CALLS',
    ),
    'meetings' => 
    array (
      'name' => 'meetings',
      'type' => 'link',
      'relationship' => 'meetings_users',
      'source' => 'non-db',
      'vname' => 'LBL_MEETINGS',
    ),
    'tasks' => 
    array (
      'name' => 'tasks',
      'type' => 'link',
      'relationship' => 'tasks_assigned_user',
      'source' => 'non-db',
      'vname' => 'LBL_TASKS',
    ),
    'contacts_sync' => 
    array (
      'name' => 'contacts_sync',
      'type' => 'link',
      'relationship' => 'contacts_users',
      'source' => 'non-db',
      'vname' => 'LBL_CONTACTS_SYNC',
      'reportable' => false,
    ),
    'reports_to_id' => 
    array (
      'name' => 'reports_to_id',
      'vname' => 'LBL_REPORTS_TO_ID',
      'type' => 'id',
      'required' => false,
    ),
    'reports_to_name' => 
    array (
      'name' => 'reports_to_name',
      'rname' => 'last_name',
      'id_name' => 'reports_to_id',
      'vname' => 'LBL_REPORTS_TO_NAME',
      'type' => 'relate',
      'isnull' => 'true',
      'module' => 'Users',
      'table' => 'users',
      'link' => 'reports_to_link',
      'reportable' => false,
      'source' => 'non-db',
      'duplicate_merge' => 'disabled',
      'side' => 'right',
    ),
    'reports_to_link' => 
    array (
      'name' => 'reports_to_link',
      'type' => 'link',
      'relationship' => 'user_direct_reports',
      'link_type' => 'one',
      'side' => 'right',
      'source' => 'non-db',
      'vname' => 'LBL_REPORTS_TO',
    ),
    'reportees' => 
    array (
      'name' => 'reportees',
      'type' => 'link',
      'relationship' => 'user_direct_reports',
      'link_type' => 'many',
      'side' => 'left',
      'source' => 'non-db',
      'vname' => 'LBL_REPORTS_TO',
      'reportable' => false,
    ),
    'email1' => 
    array (
      'name' => 'email1',
      'vname' => 'LBL_EMAIL',
      'type' => 'varchar',
      'function' => 
      array (
        'name' => 'getEmailAddressWidget',
        'returns' => 'html',
      ),
      'source' => 'non-db',
      'group' => 'email1',
      'merge_filter' => 'enabled',
      'required' => true,
    ),
    'email_addresses' => 
    array (
      'name' => 'email_addresses',
      'type' => 'link',
      'relationship' => 'users_email_addresses',
      'module' => 'EmailAddress',
      'bean_name' => 'EmailAddress',
      'source' => 'non-db',
      'vname' => 'LBL_EMAIL_ADDRESSES',
      'reportable' => false,
      'required' => true,
    ),
    'email_addresses_primary' => 
    array (
      'name' => 'email_addresses_primary',
      'type' => 'link',
      'relationship' => 'users_email_addresses_primary',
      'source' => 'non-db',
      'vname' => 'LBL_EMAIL_ADDRESS_PRIMARY',
      'duplicate_merge' => 'disabled',
      'required' => true,
    ),
    'email_link_type' => 
    array (
      'name' => 'email_link_type',
      'vname' => 'LBL_EMAIL_LINK_TYPE',
      'type' => 'enum',
      'options' => 'dom_email_link_type',
      'importable' => false,
      'reportable' => false,
      'source' => 'non-db',
      'studio' => false,
    ),
    'editor_type' => 
    array (
      'name' => 'editor_type',
      'vname' => 'LBL_EDITOR_TYPE',
      'type' => 'enum',
      'options' => 'dom_editor_type',
      'importable' => false,
      'reportable' => false,
      'source' => 'non-db',
      'studio' => false,
    ),
    'business_role' => 
    array (
      'name' => 'business_role',
      'vname' => 'LBL_BUSINESS_ROLE',
      'label' => 'LBL_BUSINESS_ROLE',
      'type' => 'enum',
      'importable' => true,
      'reportable' => true,
      'audited' => true,
      'options' => 'business_role_list',
      'default' => '',
      'massupdate' => true,
      'required' => false,
    ),
    'aclroles' => 
    array (
      'name' => 'aclroles',
      'type' => 'link',
      'relationship' => 'acl_roles_users',
      'source' => 'non-db',
      'side' => 'right',
      'vname' => 'LBL_ROLES',
    ),
    'is_group' => 
    array (
      'name' => 'is_group',
      'vname' => 'LBL_GROUP_USER',
      'type' => 'bool',
      'massupdate' => false,
      'studio' => 
      array (
        'listview' => false,
        'searchview' => false,
        'formula' => false,
      ),
    ),
    'c_accept_status_fields' => 
    array (
      'name' => 'c_accept_status_fields',
      'rname' => 'id',
      'relationship_fields' => 
      array (
        'id' => 'accept_status_id',
        'accept_status' => 'accept_status_name',
      ),
      'vname' => 'LBL_LIST_ACCEPT_STATUS',
      'type' => 'relate',
      'link' => 'calls',
      'link_type' => 'relationship_info',
      'source' => 'non-db',
      'importable' => 'false',
      'studio' => 
      array (
        'listview' => false,
        'searchview' => false,
        'formula' => false,
      ),
    ),
    'm_accept_status_fields' => 
    array (
      'name' => 'm_accept_status_fields',
      'rname' => 'id',
      'relationship_fields' => 
      array (
        'id' => 'accept_status_id',
        'accept_status' => 'accept_status_name',
      ),
      'vname' => 'LBL_LIST_ACCEPT_STATUS',
      'type' => 'relate',
      'link' => 'meetings',
      'link_type' => 'relationship_info',
      'source' => 'non-db',
      'importable' => 'false',
      'studio' => 
      array (
        'listview' => false,
        'searchview' => false,
        'formula' => false,
      ),
    ),
    'accept_status_id' => 
    array (
      'name' => 'accept_status_id',
      'type' => 'varchar',
      'source' => 'non-db',
      'vname' => 'LBL_LIST_ACCEPT_STATUS',
      'importable' => 'false',
      'studio' => 
      array (
        'listview' => false,
        'searchview' => false,
        'formula' => false,
      ),
    ),
    'accept_status_name' => 
    array (
      'name' => 'accept_status_name',
      'type' => 'enum',
      'source' => 'non-db',
      'vname' => 'LBL_LIST_ACCEPT_STATUS',
      'options' => 'dom_meeting_accept_status',
      'massupdate' => false,
      'studio' => 
      array (
        'listview' => false,
        'searchview' => false,
        'formula' => false,
      ),
    ),
    'prospect_lists' => 
    array (
      'name' => 'prospect_lists',
      'type' => 'link',
      'relationship' => 'prospect_list_users',
      'module' => 'ProspectLists',
      'source' => 'non-db',
      'vname' => 'LBL_PROSPECT_LIST',
    ),
    'emails_users' => 
    array (
      'name' => 'emails_users',
      'type' => 'link',
      'relationship' => 'emails_users_rel',
      'module' => 'Emails',
      'source' => 'non-db',
      'vname' => 'LBL_EMAILS',
    ),
    'eapm' => 
    array (
      'name' => 'eapm',
      'type' => 'link',
      'relationship' => 'eapm_assigned_user',
      'vname' => 'LBL_ASSIGNED_TO_USER',
      'source' => 'non-db',
    ),
    'oauth_tokens' => 
    array (
      'name' => 'oauth_tokens',
      'type' => 'link',
      'relationship' => 'oauthtokens_assigned_user',
      'vname' => 'LBL_OAUTH_TOKENS',
      'link_type' => 'one',
      'module' => 'OAuthTokens',
      'bean_name' => 'OAuthToken',
      'source' => 'non-db',
      'side' => 'left',
    ),
    'project_resource' => 
    array (
      'name' => 'project_resource',
      'type' => 'link',
      'relationship' => 'projects_users_resources',
      'source' => 'non-db',
      'vname' => 'LBL_PROJECTS',
    ),
    'project_users_1' => 
    array (
      'name' => 'project_users_1',
      'type' => 'link',
      'relationship' => 'project_users_1',
      'source' => 'non-db',
      'module' => 'Project',
      'bean_name' => 'Project',
      'vname' => 'LBL_PROJECT_USERS_1_FROM_PROJECT_TITLE',
    ),
    'am_projecttemplates_resources' => 
    array (
      'name' => 'am_projecttemplates_resources',
      'type' => 'link',
      'relationship' => 'am_projecttemplates_users_resources',
      'source' => 'non-db',
      'vname' => 'LBL_PROJECTS',
    ),
    'am_projecttemplates_users_1' => 
    array (
      'name' => 'am_projecttemplates_users_1',
      'type' => 'link',
      'relationship' => 'am_projecttemplates_users_1',
      'source' => 'non-db',
      'module' => 'AM_ProjectTemplates',
      'bean_name' => 'AM_ProjectTemplates',
      'vname' => 'LBL_PROJECT_USERS_1_FROM_PROJECT_TITLE',
    ),
    'SecurityGroups' => 
    array (
      'name' => 'SecurityGroups',
      'type' => 'link',
      'relationship' => 'securitygroups_users',
      'source' => 'non-db',
      'module' => 'SecurityGroups',
      'bean_name' => 'SecurityGroup',
      'vname' => 'LBL_SECURITYGROUPS',
    ),
    'securitygroup_noninher_fields' => 
    array (
      'name' => 'securitygroup_noninher_fields',
      'rname' => 'id',
      'relationship_fields' => 
      array (
        'id' => 'securitygroup_noninherit_id',
        'noninheritable' => 'securitygroup_noninheritable',
        'primary_group' => 'securitygroup_primary_group',
      ),
      'vname' => 'LBL_USER_NAME',
      'type' => 'relate',
      'link' => 'SecurityGroups',
      'link_type' => 'relationship_info',
      'source' => 'non-db',
      'Importable' => false,
      'duplicate_merge' => 'disabled',
    ),
    'securitygroup_noninherit_id' => 
    array (
      'name' => 'securitygroup_noninherit_id',
      'type' => 'varchar',
      'source' => 'non-db',
      'vname' => 'LBL_securitygroup_noninherit_id',
    ),
    'securitygroup_noninheritable' => 
    array (
      'name' => 'securitygroup_noninheritable',
      'type' => 'bool',
      'source' => 'non-db',
      'vname' => 'LBL_SECURITYGROUP_NONINHERITABLE',
    ),
    'securitygroup_primary_group' => 
    array (
      'name' => 'securitygroup_primary_group',
      'type' => 'bool',
      'source' => 'non-db',
      'vname' => 'LBL_PRIMARY_GROUP',
    ),
    'factor_auth' => 
    array (
      'name' => 'factor_auth',
      'type' => 'bool',
      'vname' => 'LBL_FACTOR_AUTH',
      'massupdate' => false,
    ),
    'factor_auth_interface' => 
    array (
      'name' => 'factor_auth_interface',
      'vname' => 'LBL_FACTOR_AUTH_INTERFACE',
      'type' => 'enum',
      'options' => 'user_factor_auth_interface_dom',
    ),
    'position' => 
    array (
      'name' => 'position',
      'type' => 'link',
      'relationship' => 'positions_employees',
      'source' => 'non-db',
      'module' => 'Positions',
      'bean_name' => 'Positions',
      'vname' => 'LBL_POSITION_EMPLOYEES',
      'id_name' => 'position_id',
    ),
    'position_name' => 
    array (
      'name' => 'position_name',
      'type' => 'relate',
      'source' => 'non-db',
      'vname' => 'LBL_POSITION_NAME',
      'save' => true,
      'id_name' => 'position_id',
      'link' => 'position',
      'module' => 'Positions',
      'table' => 'positions',
      'rname' => 'name',
    ),
    'position_id' => 
    array (
      'name' => 'position_id',
      'relationship' => 'positions_employees',
      'type' => 'id',
      'vname' => 'LBL_POSITION_ID',
    ),
    'onboardingoffboardingelements' => 
    array (
      'name' => 'onboardingoffboardingelements',
      'type' => 'link',
      'relationship' => 'users_onboardingoffboardingelements',
      'source' => 'non-db',
      'module' => 'OnboardingOffboardingElements',
      'bean_name' => 'OnboardingOffboardingElements',
      'vname' => 'LBL_USERS_ONBOARDINGOFFBOARDINGELEMENTS',
      'side' => 'right',
    ),
    'candidates' => 
    array (
      'name' => 'candidates',
      'type' => 'link',
      'relationship' => 'candidates_employees',
      'source' => 'non-db',
      'module' => 'Candidates',
      'bean_name' => false,
      'vname' => 'LBL_CANDIDATE_EMPLOYEE_LINK_FROM_EMPLOYEE',
      'id_name' => 'candidate_id',
    ),
    'candidate_name' => 
    array (
      'name' => 'candidate_name',
      'type' => 'relate',
      'source' => 'non-db',
      'vname' => 'LBL_CANDIDATE_EMPLOYEE_RELATE_FROM_EMPLOYEE',
      'save' => true,
      'id_name' => 'candidate_id',
      'link' => 'candidates_employees',
      'table' => 'candidates',
      'module' => 'Candidates',
      'rname' => 'name',
    ),
    'candidate_id' => 
    array (
      'name' => 'candidate_id',
      'type' => 'link',
      'relationship' => 'candidates_employees',
      'source' => 'non-db',
      'reportable' => false,
      'side' => 'left',
      'vname' => 'LBL_CANDIDATE_EMPLOYEE_ID_FROM_EMPLOYEE',
      'audited' => true,
    ),
    'securitygroups_employees_link' => 
    array (
      'name' => 'securitygroups_employees_link',
      'type' => 'link',
      'relationship' => 'securitygroups_employees',
      'source' => 'non-db',
      'module' => 'SecurityGroups',
      'bean_name' => 'SecurityGroup',
      'vname' => 'LBL_SECURITYGROUPS_EMPLOYEES',
      'id_name' => 'securitygroup_id',
    ),
    'securitygroup_name' => 
    array (
      'name' => 'securitygroup_name',
      'type' => 'relate',
      'source' => 'non-db',
      'vname' => 'LBL_SECURITYGROUP_NAME',
      'save' => true,
      'id_name' => 'securitygroup_id',
      'link' => 'securitygroups_employees_link',
      'module' => 'SecurityGroups',
      'table' => 'securitygroups',
      'rname' => 'name',
    ),
    'securitygroup_id' => 
    array (
      'name' => 'securitygroup_id',
      'relationship' => 'securitygroups_employees',
      'type' => 'link',
      'vname' => 'LBL_SECURITYGROUP_ID',
      'dbType' => 'id',
      'join_name' => 'securitygroups_employees_link',
    ),
    'ideas' => 
    array (
      'name' => 'ideas',
      'type' => 'link',
      'relationship' => 'users_ideas',
      'source' => 'non-db',
      'module' => 'Ideas',
      'bean_name' => 'Ideas',
      'vname' => 'LBL_RELATIONSHIP_IDEAS_NAME',
      'side' => 'right',
    ),
    'users_forced_tabs_dashboards' => 
    array (
      'name' => 'users_forced_tabs_dashboards',
      'type' => 'link',
      'relationship' => 'users_forced_tabs_dashboards',
      'source' => 'non-db',
      'vname' => 'LBL_USERS_FORCED_TABS_DASHBOARDS',
      'module' => 'DashboardManager',
      'bean_name' => 'DashboardManager',
    ),
    'forced_tabs_dashboard_name' => 
    array (
      'name' => 'forced_tabs_dashboard_name',
      'type' => 'relate',
      'source' => 'non-db',
      'vname' => 'LBL_USERS_FORCED_TABS_DASHBOARDS',
      'id_name' => 'forced_tabs_dashboard_id',
      'link' => 'users_forced_tabs_dashboards',
      'table' => 'dashboardmanager',
      'module' => 'DashboardManager',
      'rname' => 'name',
    ),
    'forced_tabs_dashboard_id' => 
    array (
      'name' => 'forced_tabs_dashboard_id',
      'type' => 'id',
      'vname' => 'LBL_FORCED_TABS_DASHBOARD_ID',
      'link' => 'users_forced_tabs_dashboards',
      'table' => 'dashboardmanager',
      'module' => 'DashboardManager',
      'rname' => 'id',
      'reportable' => false,
      'massupdate' => false,
      'duplicate_merge' => 'disabled',
      'hideacl' => true,
    ),
    'users_locked_dashboards' => 
    array (
      'name' => 'users_locked_dashboards',
      'type' => 'link',
      'relationship' => 'users_locked_dashboards',
      'source' => 'non-db',
      'module' => 'DashboardManager',
      'bean_name' => false,
      'vname' => 'LBL_USERS_LOCKED_DASHBOARDS',
    ),
    'locked_dashboard_name' => 
    array (
      'name' => 'locked_dashboard_name',
      'type' => 'relate',
      'source' => 'non-db',
      'vname' => 'LBL_USERS_LOCKED_DASHBOARDS',
      'id_name' => 'locked_dashboard_id',
      'link' => 'users_locked_dashboards',
      'table' => 'dashboardmanager',
      'module' => 'DashboardManager',
      'rname' => 'name',
    ),
    'locked_dashboard_id' => 
    array (
      'name' => 'locked_dashboard_id',
      'type' => 'id',
      'vname' => 'LBL_LOCKED_DASHBOARD_ID',
      'link' => 'users_locked_dashboards',
      'table' => 'dashboardmanager',
      'module' => 'DashboardManager',
      'rname' => 'id',
      'reportable' => false,
      'massupdate' => false,
      'duplicate_merge' => 'disabled',
      'hideacl' => true,
    ),
    'users_one_time_default_dashboards' => 
    array (
      'name' => 'users_one_time_default_dashboards',
      'type' => 'link',
      'relationship' => 'users_one_time_default_dashboards',
      'source' => 'non-db',
      'module' => 'DashboardManager',
      'bean_name' => false,
      'vname' => 'LBL_USERS_ONE_TIME_DEFAULT_DASHBOARDS',
    ),
    'users_one_time_default_dashboards_name' => 
    array (
      'name' => 'users_one_time_default_dashboards_name',
      'type' => 'relate',
      'source' => 'non-db',
      'vname' => 'LBL_USERS_ONE_TIME_DEFAULT_DASHBOARDS',
      'id_name' => 'one_time_default_dashboard_id',
      'link' => 'users_one_time_default_dashboards',
      'table' => 'dashboardmanager',
      'module' => 'DashboardManager',
      'rname' => 'name',
    ),
    'one_time_default_dashboard_id' => 
    array (
      'name' => 'one_time_default_dashboard_id',
      'type' => 'id',
      'vname' => 'LBL_ONE_TIME_DEFAULT_DASHBOARD_ID',
      'link' => 'users_one_time_default_dashboards',
      'table' => 'dashboardmanager',
      'module' => 'DashboardManager',
      'rname' => 'id',
      'reportable' => false,
      'massupdate' => false,
      'duplicate_merge' => 'disabled',
      'hideacl' => true,
    ),
    'schedulereports' => 
    array (
      'name' => 'schedulereports',
      'type' => 'link',
      'relationship' => 'users_schedulereports',
      'source' => 'non-db',
      'module' => 'ScheduleReports',
      'bean_name' => 'ScheduleReports',
      'vname' => 'LBL_SCHEDULEREPORTS',
    ),
    'deputy' => 
    array (
      'name' => 'deputy',
      'type' => 'link',
      'relationship' => 'deputy_workschedules',
      'source' => 'non-db',
      'side' => 'left',
      'vname' => 'LBL_DEPUTY',
    ),
    'birthdate' => 
    array (
      'name' => 'birthdate',
      'label' => 'LBL_BIRTHDATE',
      'vname' => 'LBL_BIRTHDATE',
      'type' => 'date',
      'required' => false,
      'audited' => true,
      'mass_update' => false,
      'duplicate_merge' => '1',
      'reportable' => true,
      'importable' => true,
      'options' => 'date_range_search_dom',
      'enable_range_search' => '1',
    ),
    'app_tokens' => 
    array (
      'name' => 'app_tokens',
      'vname' => 'LBL_APP_TOKENS',
      'label' => 'LBL_APP_TOKENS',
      'type' => 'text',
      'rows' => 6,
      'cols' => 80,
      'size' => 30,
      'duplicate_merge' => 'enabled',
      'audited' => false,
      'massupdate' => false,
      'importable' => false,
      'reportable' => false,
    ),
    'workschedule_type' => 
    array (
      'name' => 'workschedule_type',
      'vname' => 'LBL_WORKSCHEDULE_TYPE',
      'label' => 'LBL_WORKSCHEDULE_TYPE',
      'type' => 'varchar',
      'source' => 'non-db',
      'audited' => false,
      'mass_update' => false,
      'reportable' => false,
      'importable' => false,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'userspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'idx_user_name',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'user_name',
        1 => 'is_group',
        2 => 'status',
        3 => 'last_name',
        4 => 'first_name',
        5 => 'id',
      ),
    ),
  ),
  'relationships' => 
  array (
    'user_direct_reports' => 
    array (
      'lhs_module' => 'Users',
      'lhs_table' => 'users',
      'lhs_key' => 'id',
      'rhs_module' => 'Users',
      'rhs_table' => 'users',
      'rhs_key' => 'reports_to_id',
      'relationship_type' => 'one-to-many',
    ),
    'users_users_signatures' => 
    array (
      'lhs_module' => 'Users',
      'lhs_table' => 'users',
      'lhs_key' => 'id',
      'rhs_module' => 'UserSignature',
      'rhs_table' => 'users_signatures',
      'rhs_key' => 'user_id',
      'relationship_type' => 'one-to-many',
    ),
    'users_users_password_link' => 
    array (
      'lhs_module' => 'Users',
      'lhs_table' => 'users',
      'lhs_key' => 'id',
      'rhs_table' => 'users_signatures',
      'rhs_key' => 'user_id',
      'relationship_type' => 'one-to-many',
    ),
    'users_email_addresses' => 
    array (
      'lhs_module' => 'Users',
      'lhs_table' => 'users',
      'lhs_key' => 'id',
      'rhs_module' => 'EmailAddresses',
      'rhs_table' => 'email_addresses',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'email_addr_bean_rel',
      'join_key_lhs' => 'bean_id',
      'join_key_rhs' => 'email_address_id',
      'relationship_role_column' => 'bean_module',
      'relationship_role_column_value' => 'Users',
    ),
    'users_email_addresses_primary' => 
    array (
      'lhs_module' => 'Users',
      'lhs_table' => 'users',
      'lhs_key' => 'id',
      'rhs_module' => 'EmailAddresses',
      'rhs_table' => 'email_addresses',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'email_addr_bean_rel',
      'join_key_lhs' => 'bean_id',
      'join_key_rhs' => 'email_address_id',
      'relationship_role_column' => 'primary_address',
      'relationship_role_column_value' => '1',
    ),
  ),
  'custom_fields' => false,
);