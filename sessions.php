<?php
require_once('include/global.php');

if(!$authenticated) {
	$smarty->assign('recaptcha_html', recaptcha_get_html($config['recaptcha_publickey']));
	$smarty->display('login.tpl');
	exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$subaction = isset($_GET['subaction']) ? $_GET['subaction'] : 'none';

switch($action) {
	case 'list':
		$session_list = array();
		if( $result = $db->query('SELECT  `users`.`username` , `programs`.`name` AS  `program_name` , `sessions`.`session_id` ,  `sessions`.`user_id` ,  `sessions`.`program_id` , INET_NTOA(  `sessions`.`ip` ) AS  `ip` ,  `sessions`.`last_ping_time` ,  `sessions`.`creation_time` 
			FROM  `sessions` 
			LEFT JOIN  `users` ON  `users`.`id` =  `sessions`.`user_id` 
			LEFT JOIN  `programs` ON  `programs`.`id` =  `sessions`.`program_id` 
			WHERE `sessions`.`last_ping_time` > UNIX_TIMESTAMP() - 900 AND `terminated` = 0;')) {
			$i = 0;
			while($row = $result->fetch_assoc()) {
				$session_list[$i] = $row;
				$session_list[$i]['session_age'] = gmdate("H:i:s", time() - $row['creation_time']);
				$session_list[$i]['expiration_time'] = gmdate("H:i:s", 900 - (time() - $row['last_ping_time']));
				$i++;
			}
		}
		$smarty->assign('session_list', $session_list);
		$smarty->display('active_sessions.tpl');
		break;
	case 'terminate':
		$sid = isset($_GET['id']) ? $_GET['id'] : -1;
		if($db->query("UPDATE `sessions` SET `terminated` = 1 WHERE `session_id` = '$sid';")) {
			echo 'OK';
		}
		break;
}

?>