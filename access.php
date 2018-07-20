<?php
require_once('include/global.php');

if(!$authenticated) {
	$smarty->assign('recaptcha_html', recaptcha_get_html($config['recaptcha_publickey']));
	$smarty->display('login.tpl');
	exit;
}

$action		= isset($_GET['action']) ? $_GET['action'] : 'add-access';
$subaction	= isset($_GET['subaction']) ? $_GET['subaction'] : 'none';

switch($action) {
	case 'add-access':
		$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

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
	
	case 'remove-access':
		break;

	case 'edit-access':
		$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

		$program_list = fetch_program_list();
		if(($permission = fetch_permission($id)) === FALSE) {
			display_error_page('Error', 'Invalid permission ID.');
		}
		$smarty->assign('permission_id', $permission['id']);
		$smarty->assign('user_id', $permission['user_id']);
		$smarty->assign('username', $permission['username']);
		$smarty->assign('program_list', $program_list);
		$smarty->assign('selected_program', $permission['program_id']);
		$smarty->assign('max_session_count', $permission['max_session_count']);
		$smarty->assign('expiration_time', $permission['expiration_time']);

		$smarty->display('access_edit.tpl');
		break;

	case 'submit':
		submit_handler($subaction);
		break;

	default:
		break;
}


function submit_handler($subaction) {
	global $db, $smarty;
	switch($subaction) {
		case 'add-access':
			$id					= isset($_POST['id']) ? (int)$_POST['id'] : 0;
			$program_id			= isset($_POST['program_id']) ? (int)$_POST['program_id'] : 0;
			$max_session_count	= isset($_POST['max_session_count']) ? (int)$_POST['max_session_count'] : 0;
			$expiration_time	= isset($_POST['expiration_time']) ? (int)$_POST['expiration_time'] : 0;
			
			if($id == 0 || $program_id == 0) {
				display_error_page('Error', 'Invalid ID or program ID.');
			}

			if($expiration_time > 0) {
				$expiration_time = time() + $expiration_time;
			}
			
			$db->query("INSERT INTO `permissions` (`user_id`, `program_id`, `max_session_count`, `expiration_time`) VALUES ($id, $program_id, $max_session_count, $expiration_time);");
			
			header('Location: users.php');
			break;

		default:
			display_error_page('Error', 'Unhandled subaction. '.print_r($_POST,TRUE));
			break;
	}
}