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
		$group_list = array();
		if( $result = $db->query('SELECT * FROM `groups`')) {
			while($row = $result->fetch_assoc()) {
				$group_list[$row['id']] = $row;
			}
		}
		$smarty->assign('group_list', $group_list);
		$smarty->display('groups.tpl');
		break;

	case 'edit':
		$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
		if($result = $db->query("SELECT * FROM `groups` WHERE `id` = $id")) {
			if(!($group = $result->fetch_assoc())) {
				display_error_page('Error', 'Invalid Group ID.');
			}
		}
		$smarty->assign('group', $group);
		$smarty->display('groups_edit.tpl');
		break;

	case 'add-group':
		$smarty->assign('group',  array('name' => '', 
					'description' => '', 
					'program_limit' => 0, 
					'update_limit' =>0, 
					'support_ticket_limit'=>0));
		$smarty->display('groups_add.tpl');
		break;

	
	case 'delete':
		if(!isset($_GET['id'])) die('Error: Missing `id` variable.');
		$id = (int)$_GET['id'];
		if( $db->query(sprintf('DELETE FROM `groups` WHERE `id` = %d;', $id))) {
			if($db->affected_rows > 0) {
				echo 'DEL-OK';
			} else {
				echo 'ERR';
			}
		}
		break;

	case 'submit':
		$id = isset($_POST['id']) ? (int)$_POST['id'] : -1;
		$name = isset($_POST['name']) ? $db->real_escape_string($_POST['name']) : '';
		$description = isset($_POST['description']) ? $db->real_escape_string($_POST['description']) : '';
		$program_limit = isset($_POST['program_limit']) ? (int)$_POST['program_limit'] : 0;
		$update_limit = isset($_POST['update_limit']) ? (int)$_POST['update_limit'] : 0;
		$support_ticket_limit = isset($_POST['support_ticket_limit']) ? (int)$_POST['support_ticket_limit'] : 0;

		$error_list = array();
		if(empty($name)) {
			$error_list[] = 'Invalid name provided.';
		}
		if(empty($description)) {
			$error_list[] = 'Invalid description provided.';
		}

		if(count($error_list) == 0) {
			switch($subaction) {
				case 'add-group':
					$db->query(sprintf('INSERT INTO `groups` (`name`, `description`, `program_limit`, `update_limit`, `support_ticket_limit`) VALUES (\'%s\', \'%s\', %d, %d, %d);', 
							$name, $description, $program_limit, $update_limit, $support_ticket_limit)); 
					if(!($db->insert_id > 0)) {
						$error_list[] = 'Error adding group.';
					}
					break;
				case 'modify-group':
					$db->query(sprintf('UPDATE `groups` SET `name` = \'%s\', `description` = \'%s\', `program_limit` = %d, `update_limit` = %d, `support_ticket_limit` = %d WHERE id = %d;',
						$name, $description, $program_limit, $update_limit, $support_ticket_limit, $id)); 
					break;
			}
		}
		
		$error_message = '';
		if(count($error_list) > 0) {
			for($i = 0; $i < count($error_list); $i++) {
				$error_message .= $error_list[$i]."<br />\r\n";
			}

			$smarty->assign('error_message', $error_message);
			// repopulate form fields
			$smarty->assign('group', array('name' => $name, 
					'description' => $description, 
					'program_limit' => $program_limit, 
					'update_limit' => $update_limit, 
					'support_ticket_limit'=>$support_ticket_limit));

			if($subaction == 'add-group') {
				$smarty->display('groups_add.tpl');
			} else {
				$smarty->display('groups_edit.tpl');
			}

		} else {
			header('Location: groups.php');
		}

		break;

}