<?php

function install_mint_dashlets($db)
{
	include 'install/config/dashlets/dashlets_definitions.php';
	include 'install/config/dashlets/pages_definitions.php';


	$sql_insert = 'INSERT IGNORE INTO  `dashboardmanager` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `assigned_user_id`, `encoded_pages`, `encoded_dashlets`, `is_loaded`, `business_role`) VALUES';

	$dashlets_configuration_payrole =  array($main, $employee, $settlements);
	$sql_payrole = "('payrole', 'Payrole', '2020-02-28 16:00:03', '2020-02-28 15:26:09', '1', '1', NULL, 0, '1', '" . base64_encode(serialize($dashlets_configuration_payrole)) . "', '" . base64_encode(serialize($dashlets_definitions)) . "', 1, NULL)";
	$db->query($sql_insert . $sql_payrole);


	$dashlets_configuration_hr =  array($main, $employee, $hr_actions,$hr_manager);
	$sql_hr = "('hr', 'HR', '2020-02-28 16:00:03', '2020-02-28 15:26:09', '1', '1', NULL, 0, '1', '" . base64_encode(serialize($dashlets_configuration_hr)) . "', '" . base64_encode(serialize($dashlets_definitions)) . "', 1, NULL)";
	$db->query($sql_insert . $sql_hr);
	

	$dashlets_configuration_employee =  array($main, $employee);
	$sql_employee = "('employee', 'Employee', '2020-02-28 16:20:03', '2020-02-28 15:26:09', '1', '1', NULL, 0, '1', '" . base64_encode(serialize($dashlets_configuration_employee)) . "', '" . base64_encode(serialize($dashlets_definitions)) . "', 1, NULL)";
	$db->query($sql_insert . $sql_employee);


	$dashlets_configuration_manager =  array($main,  $employee, $my_team, $hr_actions,$hr_manager);
	$sql_manager = "('manager', 'Manager', '2020-02-28 16:24:03', '2020-02-28 18:26:09', '1', '1', NULL, 0, '1', '" . base64_encode(serialize($dashlets_configuration_manager)) . "', '" . base64_encode(serialize($dashlets_definitions)) . "', 1, NULL)";
	
	$db->query($sql_insert . $sql_manager);
}


function deploy_mint_dashlets()
{
	$role_payroll = array('millera', 'brookse', 'Kate', 'clarkek');
	deploy_mint_dashlets_for_users('payrole',$role_payroll);
	$role_hr =  array('ellism', 'novakm');
	deploy_mint_dashlets_for_users('hr',$role_hr);
	$role_employee = array('leej', 'hartc', 'stewardb', 'owena', 'whitej', 'rosss', 'lewisa', 'smithc');
	deploy_mint_dashlets_for_users('employee',$role_employee);
	$role_manager = array('westj', 'howardr', 'woodd', 'blacko');
	deploy_mint_dashlets_for_users('manager',$role_manager);
}

function deploy_mint_dashlets_for_users($dashboardmanager_id,$users)
{
	$dm_object = BeanFactory::getBean('DashboardManager', $dashboardmanager_id);
	$dd = new DashboardDeployer($dm_object);

    foreach ($users as $user_id) {
		$user = BeanFactory::getBean('Users', $user_id);
        if ($user) {
            $dd->deployForRole($user);
        }
    }	

}