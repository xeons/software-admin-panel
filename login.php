<?php
require_once('include/global.php');

$action = isset($_GET['action']) ? $_GET['action'] : 'login-form';
switch($action) {
	case 'login-form':
		if($authenticated) {
			header('Location: index.php');
			exit;
		}

		if ( $config['recaptcha_enabled'] ) {
			$smarty->assign('recaptcha_html', recaptcha_get_html($config['recaptcha_publickey']));
		}

		$smarty->display('login.tpl');
		break;

	case 'login':
		if($authenticated) {
			header('Location: index.php');
			exit;
		}

		if ( $config['recaptcha_enabled'] ) {
			$smarty->assign('recaptcha_html', recaptcha_get_html($config['recaptcha_publickey']));

			if ( !empty($_POST['recaptcha_challenge_field']) && !empty($_POST['recaptcha_response_field']) ) {
				$resp = recaptcha_check_answer ('6LdLR8kSAAAAAJNRC2iD3gzIQ_OYvCL8msKh4CcG', $_SERVER['REMOTE_ADDR'], 
					$_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
					
				if (!$resp->is_valid) {
					$error_message = "The reCAPTCHA wasn't entered correctly. Go back and try it again. (ReCAPTCHA said: {$resp->error})";
				}
			} else {
				$error_message = "Missing recaptcha fields.";
			}
		}

		if( empty($error_message) ) {
			if( !empty($_POST['username']) && !empty($_POST['password']) ) {
				if(check_login($_POST['username'], $_POST['password'])) {
					set_login_cookies($_POST['username'], md5($_POST['password']), true);
					header('Location: index.php');
					exit;
				} else {
					$error_message = "Invalid login.";
				}
			} else {
				$error_message = "Missing required fields.";
			}
		}

		$smarty->assign('error_message', $error_message);
		
		if(isset($_POST['username'])) {
			$smarty->assign('username', $_POST['username']);
		}
			
		$smarty->display('login.tpl');
		break;

	case 'logout':
		clear_login_cookies();
		break;
}
?>