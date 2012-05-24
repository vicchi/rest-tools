<?php

class RestToolsException extends Exception {
	public function __construct ($message, $code=0, Exception $previous=null) {
		parent::__construct ($message, $code, $previous);
	}
}	// end-class RestToolsException

class RestToolsStatusCodes {
	// Inspired by Kris Jordan's Recess! framework code
	// http://krisjordan.com/php-class-for-http-response-status-codes
	
	// Informational 1xx
	const HTTP_CONTINUE = 100;
	const HTTP_SWITCHING_PROTOCOLS = 101;
	// Successful 2xx
	const HTTP_OK = 200;
	const HTTP_CREATED = 201;
	const HTTP_ACCEPTED = 202;
	const HTTP_NONAUTHORITATIVE_INFORMATION = 203;
	const HTTP_NO_CONTENT = 204;
	const HTTP_RESET_CONTENT = 205;
	const HTTP_PARTIAL_CONTENT = 206;
	// Redirection 3xx
	const HTTP_MULTIPLE_CHOICES = 300;
	const HTTP_MOVED_PERMANENTLY = 301;
	const HTTP_FOUND = 302;
	const HTTP_SEE_OTHER = 303;
	const HTTP_NOT_MODIFIED = 304;
	const HTTP_USE_PROXY = 305;
	const HTTP_UNUSED= 306;
	const HTTP_TEMPORARY_REDIRECT = 307;
	// Client Error 4xx
	const errorCodesBeginAt = 400;
	const HTTP_BAD_REQUEST = 400;
	const HTTP_UNAUTHORIZED  = 401;
	const HTTP_PAYMENT_REQUIRED = 402;
	const HTTP_FORBIDDEN = 403;
	const HTTP_NOT_FOUND = 404;
	const HTTP_METHOD_NOT_ALLOWED = 405;
	const HTTP_NOT_ACCEPTABLE = 406;
	const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
	const HTTP_REQUEST_TIMEOUT = 408;
	const HTTP_CONFLICT = 409;
	const HTTP_GONE = 410;
	const HTTP_LENGTH_REQUIRED = 411;
	const HTTP_PRECONDITION_FAILED = 412;
	const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
	const HTTP_REQUEST_URI_TOO_LONG = 414;
	const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
	const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
	const HTTP_EXPECTATION_FAILED = 417;
	// Server Error 5xx
	const HTTP_INTERNAL_SERVER_ERROR = 500;
	const HTTP_NOT_IMPLEMENTED = 501;
	const HTTP_BAD_GATEWAY = 502;
	const HTTP_SERVICE_UNAVAILABLE = 503;
	const HTTP_GATEWAY_TIMEOUT = 504;
	const HTTP_VERSION_NOT_SUPPORTED = 505;
		
	private static $messages = array (
		100 => 'HTTP 100 Continue',
		101 => 'HTTP 101 Switching Protocols',
		// [Successful 2xx]
		200 => 'HTTP 200 OK',
		201 => 'HTTP 201 Created',
		202 => 'HTTP 202 Accepted',
		203 => 'HTTP 203 Non-Authoritative Information',
		204 => 'HTTP 204 No Content',
		205 => 'HTTP 205 Reset Content',
		206 => 'HTTP 206 Partial Content',
		// [Redirection 3xx]
		300 => 'HTTP 300 Multiple Choices',
		301 => 'HTTP 301 Moved Permanently',
		302 => 'HTTP 302 Found',
		303 => 'HTTP 303 See Other',
		304 => 'HTTP 304 Not Modified',
		305 => 'HTTP 305 Use Proxy',
		306 => 'HTTP 306 (Unused)',
		307 => 'HTTP 307 Temporary Redirect',
		// [Client Error 4xx]
		400 => 'HTTP 400 Bad Request',
		401 => 'HTTP 401 Unauthorized',
		402 => 'HTTP 402 Payment Required',
		403 => 'HTTP 403 Forbidden',
		404 => 'HTTP 404 Not Found',
		405 => 'HTTP 405 Method Not Allowed',
		406 => 'HTTP 406 Not Acceptable',
		407 => 'HTTP 407 Proxy Authentication Required',
		408 => 'HTTP 408 Request Timeout',
		409 => 'HTTP 409 Conflict',
		410 => 'HTTP 410 Gone',
		411 => 'HTTP 411 Length Required',
		412 => 'HTTP 412 Precondition Failed',
		413 => 'HTTP 413 Request Entity Too Large',
		414 => 'HTTP 414 Request-URI Too Long',
		415 => 'HTTP 415 Unsupported Media Type',
		416 => 'HTTP 416 Requested Range Not Satisfiable',
		417 => 'HTTP 417 Expectation Failed',
		// [Server Error 5xx]
		500 => 'HTTP 500 Internal Server Error',
		501 => 'HTTP 501 Not Implemented',
		502 => 'HTTP 502 Bad Gateway',
		503 => 'HTTP 503 Service Unavailable',
		504 => 'HTTP 504 Gateway Timeout',
		505 => 'HTTP 505 HTTP Version Not Supported'
		);

	public static function get_message ($code) {
		return self::$messages[$code];
	}
	
	public static function is_error ($code) {
		return ((is_numeric ($code)) && ($code >= self::HTTP_BAD_REQUEST));
	}

}	// end-class RestToolsStatusCodes

class RestToolsResult {
	private	$status = 0;
	private	$data = null;
	
	public function __construct ($status, $data=null) {
		$this->status = $status;
		if (!empty ($data) && $data) {
			$this->data = $data;
		}
	}
	
	public function status () {
		return $this->status;
	}
	
	public function data ($data=null) {
		if (!empty ($data) && $data) {
			$this->data = $data;
		}
		
		return $this->data;
	}
}	// end-class RestToolsResult

class RestTools {
	const GET = 'GET';
	const POST = 'POST';
	const OPTIONS = null;
	const TIMEOUT = 30;

	static $instance;

	private $options = null;
	
	public function __construct () {
		self::$instance = $this;

		$this->options = array (
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => self::TIMEOUT,
			CURLOPT_SSL_VERIFYPEER => false
			);
	}
	
	public function get ($url, $params=null, $headers=null, $options=null) {
		return $this->request ($url, $params, $headers, $options, self::GET);
	}
	
	public function post ($url, $params=null, $headers=null, $options=null) {
		return $this->request ($url, $params, $headers, $options, self::POST);
	}
	
	private function make_url ($url, $params) {
		if (!empty ($params) && $params) {
			$key_value = array ();
			
			foreach ($params as $key => $value) {
				$key_value[] = $key . '=' . $value;
			}	// end-foreach
			
			$url_params = str_replace (' ', '+', implode ('&', $key_value));
			$url = trim ($url) . '?' .$url_params;
		}
		
		return $url;
	}
	
	private function request ($url, $params=null, $headers=null, $options=null, $type=self::GET) {
		if (self::GET === $type) {
			$url = $this->make_url ($url, $params);
		}
		
		if (!empty ($options) && $options) {
			$curl_opts = array_merge ($this->options, $options);
		}
		else {
			$curl_opts = $this->options;
		}
		
		$handle = curl_init ();
		if (!empty ($headers) && $headers) {
			$curl_opts[CURLOPT_HTTPHEADER] = $headers;
		}
		$curl_opts[CURLOPT_URL] = $url;
		curl_setopt_array ($handle, $curl_opts);
		
		if (self::POST === $type) {
			curl_setopt ($handle, CURLOPT_POST, true);
			if (!empty ($params) && $params) {
				curl_setopt ($handle, CURLOPT_POSTFIELDS, $params);
			}
		}
		
		$data = curl_exec ($handle);
		$status = curl_getinfo ($handle, CURLINFO_HTTP_CODE);
		curl_close ($handle);
		$result = new RestToolsResult ($status, $data);
		return $result;
	}
	
}	// end-class RestTools
?>