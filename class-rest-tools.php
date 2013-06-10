<?php

class RestToolsException extends Exception {
	public function __construct($message, $code, Exception $previous=NULL) {
		parent::__construct($message, $code, $previous);
	}
	
	public function __toString() {
		return get_class($this) . " '{$this->message}' in {$this->file}:{$this->line}\n"
			. "{$this->getTraceAsString()}";
	}
	
}	// end-class RestToolsException

class RestToolsStatus {
	// Inspired by Kris Jordan's Recess! framework code
	// http://krisjordan.com/php-class-for-http-response-status-codes
	
	// Informational 1xx
	const HTTP_CONTINUE = 100;
	const HTTP_SWITCHING_PROTOCOLS = 101;
	const HTTP_PROCESSING = 102;
	// Successful 2xx
	const HTTP_OK = 200;
	const HTTP_CREATED = 201;
	const HTTP_ACCEPTED = 202;
	const HTTP_NONAUTHORITATIVE_INFORMATION = 203;
	const HTTP_NO_CONTENT = 204;
	const HTTP_RESET_CONTENT = 205;
	const HTTP_PARTIAL_CONTENT = 206;
	const HTTP_MULTI_STATUS = 207;
	const HTTP_ALREADY_REPORTED = 208;
	const HTTP_IM_USED = 226;
	// Redirection 3xx
	const HTTP_MULTIPLE_CHOICES = 300;
	const HTTP_MOVED_PERMANENTLY = 301;
	const HTTP_FOUND = 302;
	const HTTP_SEE_OTHER = 303;
	const HTTP_NOT_MODIFIED = 304;
	const HTTP_USE_PROXY = 305;
	const HTTP_UNUSED= 306;
	const HTTP_TEMPORARY_REDIRECT = 307;
	const HTTP_PERMANENT_REDIRECT = 308;
	// Client Error 4xx
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
	const HTTP_IM_A_TEAPOT = 418;
	const HTTP_ENHANCE_YOUR_CALM = 420;
	const HTTP_UNPROCESSABLE_ENTITY = 422;
	const HTTP_LOCKED = 423;
	const HTTP_FAILED_DEPENDENCY = 424;
	//const HTTP_METHOD_FAILURE = 424;
	const HTTP_UNORDERED_COLLECTION = 425;
	const HTTP_UPGRADE_REQUIRED = 426;
	const HTTP_PRECONDITION_REQUIRED = 428;
	const HTTP_TOO_MANY_REQUESTS = 429;
	const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
	const HTTP_NO_RESPONSE = 444;
	const HTTP_RETRY_WITH = 449;
	const HTTP_PARENTAL_CONTROLS = 450;
	const HTTP_CLIENT_CLOSED_REQUEST = 499;
	// Server Error 5xx
	const HTTP_INTERNAL_SERVER_ERROR = 500;
	const HTTP_NOT_IMPLEMENTED = 501;
	const HTTP_BAD_GATEWAY = 502;
	const HTTP_SERVICE_UNAVAILABLE = 503;
	const HTTP_GATEWAY_TIMEOUT = 504;
	const HTTP_VERSION_NOT_SUPPORTED = 505;
	const HTTP_VARIANT_ALSO_NEGOTIATES = 506;
	const HTTP_INSUFFICIENT_STORAGE = 507;
	const HTTP_LOOP_DETECTED = 508;
	const HTTP_BANDWIDTH_EXCEEDED = 509;
	const HTTP_NOT_EXTENDED = 510;
	const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;
	const HTTP_NETWORK_READ_TIMEOUT = 598;
	const HTTP_NETWORK_CONNECT_TIMEOUT = 599;
		
	private static $messages = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		// [Successful 2xx]
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		208 => 'Already Reported',
		226 => 'IM Used',
		// [Redirection 3xx]
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Switch Proxy (Unused)',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		// [Client Error 4xx]
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m A Teapot',
		420 => 'Enhance Your Calm',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency / Method Failure',
		425 => 'Unordered Collection',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		444 => 'No Response',
		449 => 'Retry With',
		450 => 'Blocked By Windows Parental Controls',
		499 => 'Client Closed Request',
		// [Server Error 5xx]
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		508 => 'Loop Detected',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended',
		511 => 'Network Authentication Required',
		598 => 'Network Read Timeout Error',
		599 => 'Network Connect Timeout Error'
		);
		
	public static function get_message($code) {
		return self::$messages[$code];
	}
	
	public static function is_error($code) {
		return((is_numeric ($code)) && ($code >= self::HTTP_BAD_REQUEST));
	}

}	// end-class RestToolsStatus

class RestTools {
	const GET = 'GET';
	const POST = 'POST';
	const PUT = 'PUT';
	const OPTIONS = NULL;
	const TIMEOUT = 30;

	static $instance;

	private $options = NULL;
	
	public function __construct() {
		self::$instance = $this;

		$this->options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => self::TIMEOUT,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_VERBOSE => true,		// enable for debug
			CURLOPT_HEADER => true,			// enable for debug
			//CURLINFO_HEADER_OUT => true,	// enable for debug
			CURLOPT_USERAGENT => 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1'
			);
	}
	
	public function get($url, $params=NULL, $headers=NULL, $options=NULL) {
		return $this->request($url, $params, $headers, $options, self::GET);
	}
	
	public function put($url, $data=NULL, $params=NULL, $headers=NULL, $options=NULL) {
		if (!empty($data) && $data) {
			$options = array(CURLOPT_POSTFIELDS => $data);
		}
		return $this->request($url, $params, $headers, $options, self::PUT);
	}
	
	public function post($url, $params=NULL, $headers=NULL, $options=NULL) {
		return $this->request($url, $params, $headers, $options, self::POST);
	}
	
	private function make_url($url, $params) {
		if (!empty($params) && $params) {
			$key_value = array();
			
			foreach ($params as $key => $value) {
				$key_value[] = $key . '=' . $value;
			}	// end-foreach
			
			$url_params = str_replace(' ', '+', implode ('&', $key_value));
			$url = trim($url) . '?' .$url_params;
		}
		
		return $url;
	}
	
	private function make_post_fields($params) {
		$fields = array();
		foreach ($params as $key => $value) {
			//$fields[] = $key . '=' . urlencode ($value);
			$fields[] = $key . '=' .  $value;
		}
		$post_fields = implode('&', $fields);
		return $post_fields;
	}
	
	private function request($url, $params=NULL, $headers=NULL, $options=NULL, $type=self::GET) {
		if (self::GET === $type) {
			$url = $this->make_url($url, $params);
		}
		
		if (!empty($options) && $options) {
			$curl_opts = array_merge($this->options, $options);
		}
		else {
			$curl_opts = $this->options;
		}
		
		$handle = curl_init();
		if (!empty($headers) && $headers) {
			$curl_opts[CURLOPT_HTTPHEADER] = $headers;
		}
		$curl_opts[CURLOPT_URL] = $url;
		curl_setopt_array($handle, $curl_opts);
		
		if (self::POST === $type) {
			curl_setopt($handle, CURLOPT_POST, true);
			if (!empty($params) && $params) {
				$fields = $this->make_post_fields($params);
				//echo 'post_fields: ' . $fields . PHP_EOL;
				curl_setopt($handle, CURLOPT_POSTFIELDS, $fields);
			}
		}
		elseif (self::PUT === $type) {
			curl_setopt($handle, CURLOPT_CUSTOMREQUEST, self::PUT);
		}
		
		$result = curl_exec($handle);
		$status = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		curl_close($handle);
		if (RestToolsStatus::is_error($status)) {
			$message = 'HTTP ' . $status . ' (' .
				RestToolsStatus::get_message($status) .
				') during ' . $type . ' of "' . $url . '"';
			throw new RestToolsException($message, $status);
		}
		return $result;
	}
	
}	// end-class RestTools
?>