<?php
require_once('include/global.php');

if(!$authenticated) {
	header('Location: index.php');
	exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$subaction = isset($_GET['subaction']) ? $_GET['subaction'] : 'none';

switch($action) {
	case 'list':

		$activity_log = array();

		$action_list = array('all'=>'all');
		$result = $db->query('SELECT DISTINCT `action` FROM `activity_logs`;');
		while($row = $result->fetch_assoc()) {
			$action_list[$row['action']] = $row['action'];
		}
		$smarty->assign('action_list', $action_list);
		
		// generate the user dropdown
		$user_id_list = array('all'=>'-');
		$result = $db->query('SELECT `id`, `username` FROM `users`;');
		while($row = $result->fetch_assoc()) {
			$user_id_list[$row['id']] = $row['username'];
		}
		$smarty->assign('user_id_list', $user_id_list);

		$addon_options = '';

		$filter_user_id = !empty($_GET['filter_user_id']) ? (int)$_GET['filter_user_id'] : 0;
		if($filter_user_id != 0) {
			if(!empty($addon_options)) {
				$addon_options .= " AND `user_id` = '$filter_user_id'";
			} else {
				$addon_options .= " WHERE `user_id` = '$filter_user_id'";
			}
		}

		$filter_action = !empty($_GET['filter_action']) ? $db->real_escape_string($_GET['filter_action']) : 'all';
		if($filter_action != 'all') {
			if(!empty($addon_options)) {
				$addon_options .= " AND `action` = '$filter_action'";
			} else {
				$addon_options .= " WHERE `action` = '$filter_action'";
			}
		}

		$smarty->assign('selected_user_id', $filter_user_id);
		$smarty->assign('selected_action', $filter_action);


		// add the addon options to the count too
		$page_max = 0;
		if($result = $db->query('SELECT COUNT(*) / 20 FROM `activity_logs`'.$addon_options)) {
			if($count = $result->fetch_row()) {
				$page_max = $count[0];
			}
		}

		$selected_page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
		$smarty->assign('page_min', 0);
		$smarty->assign('page_max', $page_max);

		if( $result = $db->query('SELECT `activity_logs`.*, INET_NTOA(`activity_logs`.`ip`) as `ipaddr`, `users`.`username`, `programs`.`name` AS `program_name`
			FROM `activity_logs` 
			LEFT JOIN  `users` ON  `users`.`id` =  `activity_logs`.`user_id` 
			LEFT JOIN  `programs` ON  `programs`.`id` =  `activity_logs`.`program_id`'.
			$addon_options .
			' ORDER BY `activity_time` DESC LIMIT '.($selected_page * 20).',20;')) {

			while($row = $result->fetch_assoc()) {
				$activity_log[] = $row;
			}
		} else {
			die($db->error);
		}

		$smarty->assign('activity_log', $activity_log);
		$smarty->display('activity.tpl');
		break;
}
?>