<?php
// not really an error, this is a success response
define('ERR_OK', 'OK');

// 100 range will be for request issues
define('ERR_MISSING_ACTION', 100);
define('ERR_MISSING_REQUEST_DATA', 101);

// 200 range is for permissions
define('ERR_DEACTIVATED_ACCOUNT', 200);
define('ERR_DEACTIVATED_PROGRAM', 201);
define('ERR_ACCESS_DENIED', 202);
define('ERR_EXPIRED_SESSION', 203);
define('ERR_SESSION_TERMINATED', 204);

// 300 range will be for update status codes
define('ERR_UPDATE_NOT_FOUND', 300);

// 900 will be reserved for internal errors
define('ERR_SYSTEM_ERROR', 900);

class ApiResponseXml {
	private $response = null;
	private $response_container = null;

	public function __construct() {
		$this->response = new DOMDocument('1.0', 'UTF-8');
		$this->response_container = $this->response->createElement('Response');
	}

	public function appendSection($section_title, $section_data) {
		$section = $this->response->createElement($section_title);
		foreach($section_data as $name => $value) {
			$section->appendChild( $this->response->createElement( $name, $value ) );
		}
		$this->response_container->appendChild($section);
	}
	
	public function error($code = -1, $message = 'Unknown error.') {
		$error_element = $this->response->createElement('AuthorizationResponse');
		$error_element->appendChild($this->response->createElement('ResponseCode', $code));
		$error_element->appendChild($this->response->createElement('Message', $message));
		$this->response_container->appendChild($error_element);
		$this->display();
	}

	public function display() {
		@header('Content-Type: text/xml');
		$this->response->appendChild($this->response_container);
		print $this->response->saveXML();
	}
}


class ApiResponseJson {
	private $response = array();

	public function __construct() {
		$this->response = array();
	}

	public function appendSection($section_title, $section_data) {
		$this->response[$section_title] = $section_data;
	}

	public function error($code = -1, $message = 'Unknown error.') {
		$this->response['AuthorizationResponse'] = array(
			'ResponseCode' => $code,
			'Message' => $message
		);
		$this->display();
	}

	public function display() {
		@header('Content-Type: application/json');
		print json_encode(array('Response' => $this->response));
	}
}