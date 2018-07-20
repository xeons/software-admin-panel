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
		$links_list = array();
		
		$addon_options = '';
		if(isset($_POST['username'])) {
			$addon_options = ' WHERE `users`.`username` LIKE \''.$_POST['username'].'\'';
		}
		
		$result = $db->query('SELECT COUNT(*) / 20 FROM link_logs;');
		$count = $result->fetch_row();
		//echo $count[0] / 20;
		$selected_page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
		$smarty->assign('page_min', max($selected_page - 5, 0));
		$smarty->assign('page_max', min($selected_page + 5, floor($count[0])));
		//for($i = $selected_page; $i < $selected_page + 10; $i++) $page[$i] = $i;
		//$smarty->assign('page', $page);
		
		if( $result = $db->query('SELECT `link_logs`.`url`, `link_logs`.`server_time`, `users`.`username` , `programs`.`name` AS `program_name` ,`sessions`.`session_id`, `sessions`.`user_id` ,  `sessions`.`program_id`, INET_NTOA(`sessions`.`ip` ) AS `ip` 
			FROM `link_logs` 
			LEFT JOIN `sessions` ON `sessions`.`session_id` = `link_logs`.`session_id`
			LEFT JOIN  `users` ON  `users`.`id` =  `sessions`.`user_id` 
			LEFT JOIN  `programs` ON  `programs`.`id` =  `sessions`.`program_id`'. $addon_options .' ORDER BY `link_logs`.`server_time` DESC
			LIMIT '.($selected_page*20).',20;')) {
			$i = 0;
			while($row = $result->fetch_assoc()) {
				$links_list[$i] = $row;
				$i++;
			}
		}
		$smarty->assign('links_list', $links_list);
		$smarty->display('links.tpl');
		break;
}
?>