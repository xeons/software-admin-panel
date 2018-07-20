<?php
function clean($db, $data) {
	return get_magic_quotes_gpc() ? $data : $db->real_escape_string($data);
}

function check_login($username, $password) {
	global $db;
	if( $result = $db->query("SELECT id FROM `users` WHERE `username` = '$username' AND `password` = MD5('$password');")) {
		if($row = $result->fetch_assoc()) {
			$result->close();
			return $row;
		}
	}
	return FALSE;
}

function update_program($tmp_file = NULL, $id = 0) {
	/*
	global $db;
	if($stmt = $db->prepare("UPDATE `programs` SET `latest_executable` = ?, `last_updated` = ? WHERE `id` = ?;")) {
		$null = NULL;
		$time = time();
		$stmt->bind_param("bii", $null, $time, $id);
		if($fp = fopen($tmp_file, 'r')) {
			while (!feof($fp)) {
				$stmt->send_long_data(0, fread($fp, 8192));
			}
			fclose($fp);
		}
		$stmt->execute();
		$stmt->close(); 
	}
	*/
	//@move_uploaded_file($tmp_file, '.\\update\\'.get_program_filename($id));

	if($fp = @fopen($tmp_file, 'r')) {
		$program_file = get_program_filename($id);
		if($zp = @gzopen('./update/'.$program_file, 'wb9')) {
			while (!@feof($fp)) {
				$read_bytes = fread($fp, 8192);
				@gzwrite($zp, $read_bytes, strlen($read_bytes));
			}
			@gzclose($zp);
		}
		@fclose($fp);
	}
}

function get_access_list($user_id = 0) {
	global $db;
	$access_list = array();
	if( $result = $db->query("SELECT `permissions`.`id` AS `permissions_id`, `permissions`.`user_id`, `permissions`.`program_id`, `permissions`.`expiration_time`, `users`.`username`, `users`.`id`, `programs`.`name` FROM `permissions`
		LEFT JOIN `users` ON `users`.`id` =  `permissions`.`user_id`
		LEFT JOIN `programs` ON `programs`.`id` = `permissions`.`program_id`
		WHERE `permissions`.`user_id` = $user_id;")) {
		while($row = $result->fetch_assoc()) {
			$access_list[] = $row;
		}
		$result->close();
	}
	return $access_list;
}

function get_program($db, $program_id = 0) {
	$data = '';
	if( $result = $db->query("SELECT latest_executable FROM `programs` WHERE `id` = $program_id;")) {
		if($row = $result->fetch_assoc()) {
			$data = $row['latest_executable'];
			$result->close();
		}
	}
	return $data;
}

function fetch_program_list() {
	global $db;
	$program_list = array(0 => 'None');
	if( $result = $db->query("SELECT `id`, `name` FROM `programs`;")) {
		while($row = $result->fetch_assoc()) {
			$program_list[$row['id']] = $row['name'];
		}
	}
	return $program_list;
}

function fetch_partial_user($id = 0) {
	global $db;
	if($result = $db->query("SELECT `id`, `username`, `active`, `admin` FROM `users` WHERE `id` = $id;")) {
		if($user = $result->fetch_assoc()) {
			return $user;
		}
	}
	return FALSE;
}

function fetch_permission($pid = 0) {
	global $db;
	if($result = $db->query("SELECT `permissions`.*, `users`.`id`, `users`.`username` FROM `permissions`".
		" LEFT JOIN `users` ON `users`.`id` =  `permissions`.`user_id` WHERE `permissions`.`id` = $pid;")) {
		if($perm = $result->fetch_assoc()) {
			return $perm;
		}
	}
	return FALSE;
}

function get_program_filename($program_id = 0) {
	return strtoupper(substr(md5('program'.$program_id), 0, 16)).'.GZ';
}

function display_error_page($error_title = '', $error_message = '') {
	global $smarty;
	$smarty->assign('error_title', $error_title);
	$smarty->assign('error_message', $error_message);
	$smarty->display('error.tpl');
	exit;
}

function set_login_cookies($username, $password, $save_password = FALSE) {
	$expires = time() + ($save_password ? 31536000 : 10800);
	setcookie("username", $username, $expires);
	setcookie("password", $password, $expires);
	setcookie("authenticated", '1', $expires);
}

function clear_login_cookies() {
	$expires = time() - 3600;
	setcookie("username", 		'', $expires);
	setcookie("password", 		'', $expires);
	setcookie("authenticated", 	'', $expires);
	header("Location: index.php");
}

function qfetch($sql, $db) {
	$data = NULL;
	if($result = $db->query($sql)) {
		if($row = $result->fetch_assoc())
			$data = $row;
		$result->free();
	}
	return $data;
}

function validate_username($username) {
	if (strlen($username) < 3 || strlen($username) > 18) {
		return FALSE;
	}
	if (preg_match("/[^A-Za-z0-9 ]/", $username)) {	
		return FALSE;
	}
	return TRUE;
}
?>