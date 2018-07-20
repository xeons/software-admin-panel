<?php
require_once('include/global.php');

if(!$authenticated) {
	$smarty->assign('recaptcha_html', recaptcha_get_html($config['recaptcha_publickey']));
	$smarty->display('login.tpl');
	exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : 'settings';
$subaction = isset($_GET['subaction']) ? $_GET['subaction'] : 'none';

switch($action) {
	case 'settings':
		$smarty->assign('timezone_identifiers', timezone_identifiers_list());
		$smarty->assign('bool_options', array('0' => 'disabled', '1' => 'enabled'));

		$settings_list = array();
		if ($result = $db->query("SELECT * FROM `settings`")) {
			while($row = $result->fetch_assoc()) {
				$settings_list[] = $row;
			}
		}
		$smarty->assign('settings_list', $settings_list);

		$smarty->display('settings.tpl');
		break;

	case 'submit':
		$updated_fields = array();
		foreach($global_settings as $key => $value) {
			if ( !empty($_POST[$key]) && $_POST[$key] != $value) {
				$s_val = $db->real_escape_string($_POST[$key]);
				if($db->query("UPDATE `settings` SET `value` = '$s_val' WHERE `name` = '$key';")) {
					$updated_fields[$key] = true;
				}
			}
		}
		header('Location: settings.php');
		break;
}

?>