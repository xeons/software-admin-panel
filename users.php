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
		$user_list = array();
		if( $result = $db->query('SELECT `id`, `username`, `active`, `last_login_ip`, `last_login_time` FROM `users`;')) {
			$i = 0;
			while($row = $result->fetch_assoc()) {
				$user_list[$i] = $row;
				$user_list[$i]['access_list'] = get_access_list($row['id']);
				$i++;
			}
		}
		$smarty->assign('user_list', $user_list);
		$smarty->display('users.tpl');
		break;

	case 'edit':

		$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
		if($result = $db->query("SELECT * FROM `users` WHERE `id` = $id")) {
			if(!($user = $result->fetch_assoc())) {
				display_error_page('Error', 'Invalid User ID.');
			}
		}
		$user['access_list'] = get_access_list($user['id']);

		$group_list = array('0' => 'None');
		if ($result = $db->query("SELECT * FROM `groups`")) {
			while($row = $result->fetch_assoc()) {
				$group_list[$row['id']] = $row['name'];
			}
		}
		$smarty->assign('group_list', $group_list);
		$smarty->assign('user', $user);

		$smarty->display('users_edit.tpl');
		break;

	case 'add':
		$group_list = array('0' => 'None');
		if ($result = $db->query("SELECT * FROM `groups`")) {
			while($row = $result->fetch_assoc()) {
				$group_list[$row['id']] = $row['name'];
			}
		}
		$smarty->assign('selected_group', 0);
		$smarty->assign('group_list', $group_list);
		$smarty->display('users_add.tpl');
		break;
	case 'password':
		$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
		$result = $db->query("SELECT `username` FROM `users` WHERE `id` = $id;");
		if(!($row = $result->fetch_assoc())) die('Error: Invalid User ID');
		$name = $row['username'];
		$smarty->assign('user_id', $id);
		$smarty->assign('username', $name);
		$smarty->display('users_changepassword.tpl');
		break;
	case 'submit':
		switch($subaction) {
			case 'add':
				$username = isset($_POST['username']) ? $db->real_escape_string($_POST['username']) : '';
				$password = isset($_POST['password']) ? $db->real_escape_string($_POST['password']) : '';
				$active = isset($_POST['active']) ? (int)($_POST['active'] == '1') : 0;
				$admin = isset($_POST['admin']) ? (int)($_POST['admin'] == '1') : 0;
				
				$error_list = array();
				if(!validate_username($username)) {
					$error_list[] = 'Invalid username provided.';
				}
				if(strlen($password) < 6 || strlen($password) > 20) {
					$error_list[] = 'Invalid password provided.';
				}
				
				if(count($error_list) == 0) {
					if(!$db->query("INSERT INTO `users` (`username`, `password`, `active`, `admin`) VALUES ('$username', MD5('$password'), $active, $admin);")) {
						$error_list[] = 'Problem adding to database. That username might already exist.';
					}
				}

				$error_message = '';
				if(count($error_list) > 0) {
					for($i = 0; $i < count($error_list); $i++) {
						$error_message .= $error_list[$i]."<br />\r\n";
					}
					$smarty->assign('error_message', $error_message);
					$smarty->display('users_add.tpl');
				} else {
					header('Location: users.php');
				}
				break;
			case 'edit':
				$id = isset($_POST['id']) ? (int)$_POST['id'] : -1;
				$username	= isset($_POST['username']) ? $db->real_escape_string($_POST['username']) : '';
				$group_id	= isset($_POST['group_id']) ? (int)$_POST['group_id'] : 0;
				$email_address	= isset($_POST['email_address']) ? $db->real_escape_string($_POST['email_address']) : '';
				$active		= isset($_POST['active']) ? (int)($_POST['active'] == '1') : 0;
				$admin		= isset($_POST['admin']) ? (int)($_POST['admin'] == '1') : 0;

				$error_list = array();
				if(!validate_username($username)) {
					$error_list[] = 'Invalid username provided.';
				}
				if(!empty($email_address)) {
					if(!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
						$error_list[] = 'Invalid email address provided. Either leave it blank or provide a valid email.';
					}
				}

				// check for validation errors
				if(count($error_list) == 0) {
					if(!$db->query(sprintf("UPDATE `users` SET `username` = '%s', `group_id` = %d, `email_address` = '%s', `active` = %d, `admin` = %d WHERE id = %d", 
						$username, $group_id, $email_address, $active, $admin, $id))) {
						$error_list[] = sprintf("Update query error (%d: %s).", $db->errno, $db->error);
					}
				}

				$error_message = '';
				if(count($error_list) > 0) {
					for($i = 0; $i < count($error_list); $i++) {
						$error_message .= $error_list[$i]."<br />\r\n";
					}
					$smarty->assign('error_message', $error_message);
					$smarty->display('users_edit.tpl');
				} else {
					header('Location: users.php');
				}

				break;

			case 'add-access':
				$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
				$program_id = isset($_POST['program_id']) ? (int)$_POST['program_id'] : 0;
				$max_session_count = isset($_POST['max_session_count']) ? (int)$_POST['max_session_count'] : 0;
				$expiration_time = isset($_POST['expiration_time']) ? (int)$_POST['expiration_time'] : 0;
				
				if($id == 0 || $program_id == 0) die('Invalid ID or Program ID.');

				if($expiration_time > 0) {
					$expiration_time = time() + $expiration_time;
				}
				
				$db->query("INSERT INTO `permissions` (`user_id`, `program_id`, `max_session_count`, `expiration_time`) VALUES ($id, $program_id, $max_session_count, $expiration_time);");
				header('Location: users.php');
				break;

			case 'change-password':
				$id = isset($_POST['id']) ? (int)$_POST['id'] : -1;
				$password = isset($_POST['password']) ? $_POST['password'] : '';
				$password2 = isset($_POST['password2']) ? $_POST['password2'] : '';

				$error_list = array();

				if($id == -1 || empty($password) || empty($password2) ) {
					$error_list[] = 'Password and confirmation password cannot be blank.';
				}
				if($password != $password2) {
					$error_list[] = 'Confirmation password must match the primary password..';
				}
				
				if(count($error_list) == 0) {
					$password_hash = md5($password);
					if(!$db->query('UPDATE `users` SET `password` = \''.$password_hash.'\' WHERE `id` = '.$id)) {
						$error_list[] = sprintf("Update query error (%d: %s).", $db->errno, $db->error);
					}
				}

				$error_message = '';
				if(count($error_list) > 0) {
					for($i = 0; $i < count($error_list); $i++) {
						$error_message .= $error_list[$i]."<br />\r\n";
					}
					$result = $db->query("SELECT `username` FROM `users` WHERE `id` = $id;");
					if(!($user = $result->fetch_assoc())) die('Error: Invalid User ID');
					$smarty->assign('user_id', $id);
					$smarty->assign('username', $user['username']);
					$smarty->assign('error_message', $error_message);
					$smarty->display('users_changepassword.tpl');
				} else {
					header('Location: users.php');
				}

				break;
		}
		break;
	case 'delete':
		$uid = isset($_GET['id']) ? (int)$_GET['id'] : -1;
		if($db->query("DELETE FROM `users` WHERE `id` = $uid;")) {
			$db->query("DELETE FROM `permissions` WHERE `user_id` = $uid;");
			echo 'OK';
		}
		break;
	case 'addaccess':
		$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;

		if(($user = fetch_partial_user($id)) === FALSE) {
			display_error_page('Error', 'Invalid User ID.');
		}

		$program_list = fetch_program_list();

		$smarty->assign('user_id', $id);
		$smarty->assign('username', $user['username']);
		$smarty->assign('program_list', $program_list);
		$smarty->assign('selected_program', 0);
		$smarty->assign('max_session_count', 1);

		$expiration_list = array(0 => 'Never', 86400 => '1 day', 2592000 => '30 days', 5184000 => '60 days', 7776000 => '90 days', 15552000 => '180 days', 31536000 => '365 days');
		foreach($expiration_list as $seconds => &$option_text) {
			// skip infinite
			if($seconds == 0) continue;
			// generate the expiration dates
			$option_text = $option_text . ' ('.date("F j, Y, g:i a", time() + $seconds).')';
		}
			
		$smarty->assign('expiration_list', $expiration_list);
		$smarty->display('users_addaccess.tpl');

		break;
	case 'removeaccess':
		$pid = isset($_GET['pid']) ? (int)$_GET['pid'] : 0;
		if($db->query("DELETE FROM `permissions` WHERE `id` = $pid;")) {
			echo 'OK';
		}
		break;
}
?>