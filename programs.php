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
		$program_list = array();
		if( $result = $db->query("SELECT `id`, `name`, `description`, `major_version`, `minor_version`, `revision_version`, `last_updated` FROM `programs`;")) {
			while($row = $result->fetch_assoc()) {
				$program_list[] = $row;
			}
		}
		$smarty->assign('program_list', $program_list);
		$smarty->display('programs.tpl');
		break;

	case 'edit':
		if(!isset($_GET['id'])) die('Error: Missing `id` variable.');
		
		$id = (int)$_GET['id'];
		if($result = $db->query("SELECT `id`, `name`, `active`, `description`, `major_version`, `minor_version`, `revision_version`, `last_updated` FROM `programs` WHERE `id` = $id;")) {
			if($row = $result->fetch_assoc()) {
				$smarty->assign('program', $row);
				$smarty->display('programs_edit.tpl');
			}
		}
		break;

	case 'add':
		$smarty->assign('program', array('name' => 'New Program Name', 'description' => '', 'major_version'=>1, 'minor_version'=>0, 'revision_version'=>0, 'active'=> 1));
		$smarty->display('programs_add.tpl');
		break;
		
	case 'submit':
		$id = isset($_POST['id']) ? (int)$_POST['id'] : -1;
		$name = isset($_POST['name']) ? $db->real_escape_string($_POST['name']) : '';
		$description = isset($_POST['description']) ? $db->real_escape_string($_POST['description']) : '';
		$major_version = isset($_POST['major_version']) ? (int)$_POST['major_version'] : 0;
		$minor_version = isset($_POST['minor_version']) ? (int)$_POST['minor_version'] : 0;
		$revision_version = isset($_POST['revision_version']) ? (int)$_POST['revision_version'] : 0;
		$active = isset($_POST['active']) ? (int)($_POST['active'] == '1') : 0;
		$last_updated = time();
		
		$error_list = array();
		if(empty($name)) {
			$error_list[] = 'Invalid name provided.';
		}
		if($major_version == -1 || $minor_version == -1) {
			$error_list[] = 'Invalid version provided.';
		}
		
		if(count($error_list) == 0) {
			switch($subaction) {
				case 'add':
					$db->query(sprintf('INSERT INTO `programs` (`name`, `description`, `active`, `major_version`, `minor_version`, `revision_version`, `last_updated`) 
						VALUES (\'%s\', \'%s\', %d, %d, %d, %d, %d);', 
							$name, $description, $active, $major_version, $minor_version, $revision_version, $last_updated)); 
					if($db->insert_id > 0) {
						if($_FILES['exe']['error'] === UPLOAD_ERR_OK && $_FILES['exe']['size'] > 0) {
							update_program($_FILES['exe']['tmp_name'], $db->insert_id);		
						}
					} else {
						$error_list[] = 'Error adding program.';
					}
					break;
				case 'edit':
					$db->query(sprintf('UPDATE `programs` SET `name` = \'%s\', `description` = \'%s\', `active` = %d, 
						`major_version` = %d, `minor_version` = %d, `revision_version` = %d, `last_updated` = %d WHERE id = %d;',
						$name, $description, $active, $major_version, $minor_version, $revision_version, $last_updated, $id)); 
					//if($db->affected_rows > 0) {
						if($_FILES['exe']['error'] === UPLOAD_ERR_OK && $_FILES['exe']['size'] > 0) {
							update_program($_FILES['exe']['tmp_name'], $id);		
						}
					//} else {
					//	$error_list[] = 'Error updating program.';
					//}
					break;
			}
		}
		
		$error_message = '';
		if(count($error_list) > 0) {
			for($i = 0; $i < count($error_list); $i++) {
				$error_message .= $error_list[$i]."<br />\r\n";
			}
			$smarty->assign('error_message', $error_message);
			if($subaction == 'add') {
				$smarty->assign('program', array('name' => $name, 'description' => $description, 'major_version'=>$major_version, 'minor_version'=>$minor_version, 'revision_version'=>$revision_version, 'active'=> $active));
				$smarty->display('programs_add.tpl');
			} else {
				$smarty->assign('program', array('id'=> $id, 'name' => $name, 'description' => $description, 'major_version'=>$major_version, 'minor_version'=>$minor_version, 'revision_version'=>$revision_version, 'active'=> $active));
				$smarty->display('programs_edit.tpl');
			}
		} else {
			header('Location: programs.php');
		}
		break;

	case 'delete':
		if(!isset($_GET['id'])) die('Error: Missing `id` variable.');
		$id = (int)$_GET['id'];
		if( $db->query(sprintf('DELETE FROM `programs` WHERE `id` = %d;', $id))) {
			if($db->affected_rows > 0) {
				echo 'DEL-OK';
			}
		}
		break;
}
?>