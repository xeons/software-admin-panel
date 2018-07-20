<?php
class AuthManager {
	private $db;
	
	public function __construct($db) {
		$this->db = $db;
	}
	
	public function generateSessionId() {
		return md5(time() + rand() * 0.100);
	}
	
	public function getProgram($program_id = 0) {
		$program_data = NULL;
		if($result = $this->db->query('SELECT * FROM `programs` WHERE `id` = \''.$program_id.'\' AND `active` = 1;')) {
			if($row = $result->fetch_assoc()) {
				$program_data = $row;
			}
			$result->free();
		}
		return $program_data;
	}
	
	public function getUser($username = NULL, $password = NULL) {
		$user_data = NULL;
		if($result = $this->db->query('SELECT * FROM `users` WHERE `username` = \''.$username.'\' AND `password` = \''.$password.'\' AND `active` = 1;')) {
			if($row = $result->fetch_assoc()) {
				$user_data = $row;
			}
			$result->free();
		}
		return $user_data;
	}
	
	public function createSession($session_id, $program_id, $user_id, $ip) {
		$ip = ip2long($ip);
		if($this->db->query('INSERT INTO `sessions` (`session_id`, `program_id`, `user_id`, `creation_time`, `last_ping_time`, `ip`) 
			VALUES (\''.$session_id.'\', '.$program_id.', '.$user_id.', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '.$ip.');')) {
			return $this->db->affected_rows > 0;
		}
		return FALSE;
	}
	
	public function getSession($session_id = NULL) {
		$session_data = NULL;
		if($result = $this->db->query('SELECT * FROM `sessions` WHERE `session_id` = \''.$session_id.'\' AND `last_ping_time` > UNIX_TIMESTAMP() - 900;')) {
			if($row = $result->fetch_assoc()) {
				$session_data = $row;
			}
			$result->free();
		}
		return $session_data;
	}
	
	public function logActivity($session_id, $program_id, $user_id, $ip, $action) {
		$ip = ip2long($ip);
		if($this->db->query("INSERT INTO `activity_logs` (`session_id`, `program_id`, `user_id`, `ip`, `activity_time`, `action`) 
			VALUES ('$session_id', $program_id, $user_id, $ip, UNIX_TIMESTAMP(), '$action');")) {
			if($this->db->affected_rows > 0) {
				return TRUE;
			}
		}
		return FALSE;
	}
	
}
?>