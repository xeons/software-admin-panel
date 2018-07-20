<?php
require_once('include/global.php');

if(!$authenticated) {
	if ( $config['recaptcha_enabled'] ) {
		$smarty->assign('recaptcha_html', recaptcha_get_html($config['recaptcha_publickey']));
	}
	$smarty->display('login.tpl');
} else {
	
	// Login count (today)
	$data = qfetch('SELECT COUNT( * ) as `login_count`
		FROM  `sessions` 
		WHERE  `creation_time` > UNIX_TIMESTAMP( CURDATE( ) );', $db);
	$smarty->assign('login_count_today', $data['login_count']);
	
	// Last user logged in
	$data = qfetch('SELECT `sessions`.`user_id`, `sessions`.`creation_time`, `users`.`username`
		FROM  `sessions` 
		LEFT JOIN `users` ON `sessions`.`user_id` = `users`.`id`
		ORDER BY `creation_time` DESC;', $db);
	$smarty->assign('last_user', $data['username']);
	$smarty->assign('last_user_time', $data['creation_time']);

	// active users
	$data = qfetch('SELECT COUNT(*) as `active_sessions`
		FROM `sessions` 
		WHERE (`last_ping_time` > UNIX_TIMESTAMP() - 900 AND `terminated` = 0);', $db);
	$smarty->assign('active_sessions', $data['active_sessions']);
	
	// active sessions
	$data = qfetch('SELECT COUNT(DISTINCT `user_id`) as `active_users`
		FROM  `sessions` 
		WHERE (`last_ping_time` > UNIX_TIMESTAMP() - 900 AND `terminated` = 0);', $db);
	$smarty->assign('active_users', $data['active_users']);
	
	// failed login attempts
	$data = qfetch('SELECT COUNT(*) as `failed_login_attempts`
		FROM  `activity_logs` 
		WHERE  `action` = \'login_error\' AND `activity_time` > UNIX_TIMESTAMP( CURDATE( ) );', $db);
	$smarty->assign('failed_login_attempts', $data['failed_login_attempts']);
	
	// total failed login attempts
	$data = qfetch('SELECT COUNT(*) as `failed_login_attempts`
		FROM  `activity_logs` 
		WHERE  `action` = \'login_error\';', $db);
	$smarty->assign('total_failed_login_attempts', $data['failed_login_attempts']);
	
	// program count
	$data = qfetch('SELECT COUNT(*) as `program_count` FROM  `programs`;', $db);
	$smarty->assign('program_count', $data['program_count']);
	
	// user count
	$data = qfetch('SELECT COUNT(*) as `user_count` FROM `users`;', $db);
	$smarty->assign('user_count', $data['user_count']);
		
	$smarty->display('index.tpl');
}
?>