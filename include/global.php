<?php
@error_reporting(E_ALL | E_NOTICE);
@ini_set('display_errors', 1);

require('./vendor/autoload.php'); // composer autoloader

require('./include/config.php');
require('./include/functions.php');
require('./include/recaptchalib.php');

// create the database connection
$db = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
if (mysqli_connect_error()) {
	die('Database connection error encountered.');
}

// load global settings from the database, if this fails then the proper tables aren't installed.
if ($result = $db->query("SELECT * FROM `settings`")) {
	while($row = $result->fetch_assoc()) {
		$global_settings[$row['name']] = $row['value'];
	}
} else {
	die('There was an issue loading the global settings. Are the database tables loaded properly?');
}

// setup the timezone for everything
if (isset($global_settings['timezone'])) {
	date_default_timezone_set($global_settings['timezone']);
} else {
	date_default_timezone_set('America/North_Dakota/Center');
}

// setup smarty
$smarty = new Smarty();
$smarty->template_dir = $_SERVER['DOCUMENT_ROOT'].'/activate/templates/';
$smarty->compile_dir  = $_SERVER['DOCUMENT_ROOT'].'/activate/templates_c/';
$smarty->config_dir   = $_SERVER['DOCUMENT_ROOT'].'/activate/configs/';
$smarty->cache_dir    = $_SERVER['DOCUMENT_ROOT'].'/activate/cache/';

// check for cookies indicating that the user is already authenticated.
$authenticated = FALSE;
$user = array();
if( isset($_COOKIE['authenticated'], $_COOKIE['username'], $_COOKIE['password']) ) {
	$c_username = $db->real_escape_string($_COOKIE['username']);
	$c_password = $db->real_escape_string($_COOKIE['password']);
	if($result = $db->query("SELECT * FROM `users` WHERE `username` = '$c_username' AND `password` = '$c_password' AND `admin` = 1;")) {
		if($user = $result->fetch_assoc()) {
			$authenticated = TRUE;
		}
	}
}

$smarty->assign('authenticated', $authenticated);
