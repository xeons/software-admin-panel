<?php
//@error_reporting(E_ALL | E_NOTICE);
//@ini_set('display_errors', 1);

require('./include/config.php');
require('./include/functions.php');
require('./include/apiresponse.class.php');
require('./include/authmanager.class.php');

$action = isset($_POST['action']) ? $_POST['action'] : '';
$format = isset($_REQUEST['format']) ? $_REQUEST['format'] : 'xml';

if($format == 'json') {
	$response = new ApiResponseJson();
} else {
	$response = new ApiResponseXml();
}

$db = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
if ($db->connect_error) {
	$response->error(900, 'Database connection failure.');
	exit;
}

$auth = new AuthManager($db);

/****************** GLOBAL SESSION HANDLING ********************/
$session = array();
if( isset($_POST['sessionId']) ) {
	$session_id = clean($db, $_POST['sessionId']);
	if(($session = $auth->getSession($session_id)) == NULL) {
		$response->error(201, 'Expired or nonexistent session!');
		exit;
	}
	if($session['terminated'] == 1) {
		$response->error(202, 'Session terminated!');
		exit;
	}
} else {
	// only create session can be called without a session id.
	if($action != 'createSession') {
		$response->error(100, 'Session ID required to call this command.');
		exit;
	}
}
/***************************************************************/

switch($action) {
	case 'createSession':
	
		if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['programId'])) {
			
			$username 		= clean($db, $_POST['username']);
			$password 		= clean($db, $_POST['password']);
			$program_id 	= (int)clean($db, $_POST['programId']);

			if(($userdata = $auth->getUser($username, $password)) != NULL) {
				
				if(($programdata = $auth->getProgram($program_id)) != NULL) {
					$result = $db->query(sprintf("SELECT * FROM  `permissions` 
						WHERE  (`permissions`.`program_id` = %d OR `permissions`.`program_id` = -1) AND  `permissions`.`user_id` = %d;", $program_id, $userdata['id']));
					
					if($permission = $result->fetch_assoc()) {
					
						$session_id = $auth->generateSessionId();
						$auth->createSession($session_id, $programdata['id'], $permission['user_id'], $_SERVER['REMOTE_ADDR']);
						$auth->logActivity($session_id, $programdata['id'], $permission['user_id'], $_SERVER['REMOTE_ADDR'], 'create_session');
						
						$response->appendSection('AuthorizationResponse', array(
							'ResponseCode' 			=> 'OK',
							'ProgramID' 			=> $programdata['id'],
							'LatestMajorVersion'	=> $programdata['major_version'],
							'LatestMinorVersion'	=> $programdata['minor_version'],
							'LatestRevisionVersion'	=> $programdata['revision_version'],
							'SessionID' 			=> $session_id,
							'KeepAlive' 			=> 900
						));
						
						$response->display();
						
					} else {
						$auth->logActivity('', $program_id, 0, $_SERVER['REMOTE_ADDR'], 'denied_access');
						$response->error(102, 'Access denied.');
					}
				} else {
					$auth->logActivity('', $program_id, 0, $_SERVER['REMOTE_ADDR'], 'bad_pid');
					$response->error(101, 'Invalid or deactivated program.');
				}
				
			} else {
				$auth->logActivity('', $program_id, 0, $_SERVER['REMOTE_ADDR'], 'login_error');
				$response->error(101, 'Account deactivated or login invalid.');
			}
		} else {
			$auth->logActivity('', 0, 0, $_SERVER['REMOTE_ADDR'], 'bad_request');
			$response->error(100, 'Missing required variable.');
		}
		break;
	case 'destroySession':
		// set the last ping time to 0 so it expires instantly
		$db->query('UPDATE `sessions` SET `last_ping_time` = 0 WHERE `session_id` = \''.$session['session_id'].'\';');
		$auth->logActivity($session['session_id'], $session['program_id'], $session['user_id'], $_SERVER['REMOTE_ADDR'], 'destroy_session');
		$response->appendSection('AuthorizationResponse', array(
			'ResponseCode' => 'OK',
			'Message' => 'Session has been destroyed.',
			'LastPingTime' => 0,
			'ProgramID' => $session['program_id'],
			'SessionID' => $session['session_id']
		));
		$response->display();
		break;
		
	case 'ping':
		$os_username = clean($db, $_POST['os_username']);
		$db->query('UPDATE `sessions` SET `last_ping_time` = UNIX_TIMESTAMP(), `os_username` = \''.$os_username.'\' WHERE `session_id` = \''.$session['session_id'].'\';');

		$auth->logActivity($session['session_id'], $session['program_id'], $session['user_id'], $_SERVER['REMOTE_ADDR'], 'ping');

		$response->appendSection('AuthorizationResponse', array(
			'ResponseCode' => 'OK',
			'LastPingTime' => $session['last_ping_time'],
			'ProgramID' => $session['program_id'],
			'SessionID' => $session['session_id']
		));
		$response->display();

		break;
		
	case 'link':
		if( isset( $_POST['url'] ) ) {
			
			$url = clean($db, $_POST['url']);
			
			$auth->logActivity($session['session_id'], $session['program_id'], $session['user_id'], $_SERVER['REMOTE_ADDR'], 'link');

			// insert all the id's for the link log
			$db->query( sprintf("INSERT INTO `link_logs` (`session_id`, `user_id`, `program_id`, `server_time`, `url`) VALUES ('%s', %d, %d, UNIX_TIMESTAMP(), '%s')",
				$session['session_id'], $session['user_id'], $session['program_id'], $url) );
			
			$response->appendSection('AuthorizationResponse', array(
				'ResponseCode' => 'OK',
				'ProgramID' => $session['program_id'],
				'SessionID' => $session['session_id']
			));
			
			$response->display();

		} else {
			$response->error(100, 'Missing required variable.');
		}
		break;
		
	case 'update':
		$auth->logActivity($session['session_id'], $session['program_id'], $session['user_id'], $_SERVER['REMOTE_ADDR'], 'update');
		$program_file = get_program_filename($session['program_id']);
		if($zp = gzopen('./update/'.$program_file, 'r')) {
			header('Content-Disposition: attachment; filename=update.exe');
			header('Content-Type: application/octet-stream');
			while (!gzeof($zp)) {
				echo gzread($zp, 8192);
			}
			gzclose($zp);
		} else {
			$response->error(300, 'No update file found.');
		}
		break;
		
	default:
		$response->error(100, 'Missing required action.');
		break;
}
?>