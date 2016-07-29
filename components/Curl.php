<?php
namespace app\components;
class Curl{

	public $host;
	public $time_out         = 10;
	public $connect_time_out = 20;
	public $ssl_verifypeer   = FALSE;
	public $format           = 'json';
	public $decode_json      = TRUE;
	public static $boundary = '';

	public function __construct($config) {
		if(is_array($config)){
			if(!array_key_exists('host',$config)) {
				return 'no host';
			}
			$this->host             = $config['host'];
			$this->time_out         = (array_key_exists('time_out',$config)) ? intval($config['time_out']) : $this->time_out;
			$this->connect_time_out = (array_key_exists('connect_time_out',$config)) ? intval($config['connect_time_out']) : $this->connect_time_out;
			$this->ssl_verifypeer   = (array_key_exists('ssl_verifypeer',$config)) ? $config['ssl_verifypeer'] : $this->ssl_verifypeer;
			$this->decode_json      = (array_key_exists('decode_json',$config)) ? $config['decode_json'] : $this->decode_json;
		}else{
			$this->host = $config;
		}
	}

	public function get($url, $parameters = array()) {
		$response = $this->request($url, 'GET', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	public function post($url, $parameters = array(), $multi = false ,$cookie = false) {
		$response = $this->request($url, 'POST', $parameters, $multi ,$cookie);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	public function request($url, $method, $parameters, $multi = false , $cookie = false) {

		if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
			$url = "{$this->host}{$url}";
		}
		//error_log($url . '?' . http_build_query($parameters),3,"/home/sqxingruisheng/curl.log");
		switch ($method) {
			case 'GET':
				$url = $url . '?' . http_build_query($parameters);
				return $this->http($url, 'GET');
			default:
				$headers = array();
				if (!$multi && (is_array($parameters) || is_object($parameters)) ) {
					$body = http_build_query($parameters);
				} else {
					$body = self::build_http_query_multi($parameters);
					$headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
					if(!empty($cookie)) {
						if(is_bool($cookie)) {
							$headers[] = !empty($_SERVER['HTTP_COOKIE']) ? 'Cookie: '.$_SERVER['HTTP_COOKIE'] : '';
						}else{
							$headers[] = 'Cookie: '.$cookie;
						}
					}
				}
				return $this->http($url, $method, $body, $headers);
		}
	}

	public function http($url, $method, $postfields = NULL, $headers = array()) {
		$ci = curl_init();
		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connect_time_out);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->time_out);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_ENCODING, "");
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($ci, CURLOPT_HEADER, FALSE);

		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
					$this->postdata = $postfields;
				}
				break;
			case 'DELETE':
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if (!empty($postfields)) {
					$url = "{$url}?{$postfields}";
				}
		}
		//echo $url."<br>";
		// error_log($url."\r\n",3,'/tmp/apistat.log');
		curl_setopt($ci, CURLOPT_URL, $url );
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
		// curl_setopt($ci, CURLOPT_COOKIE , $_SERVER['HTTP_COOKIE']);
		error_log(date("Y-m-d H:i:s",time())."  ".$url."\r\n", 3, '/home/mosh/error.log');
		$response = curl_exec($ci);
		/*
		   echo "=====post data======\r\n";
		   var_dump($postfields);

		   echo '=====info====='."\r\n";
		   print_r( curl_getinfo($ci) );

		   echo '=====$response====='."\r\n";
		   print_r( $response );
		 */
        error_log(date("Y-m-d H:i:s",time())."  ".$url."\r\n", 3, '/home/mosh/error1.log');
        curl_close ($ci);
		return $response;
	}

	public static function build_http_query_multi($params) {
		if (!$params) return '';

		uksort($params, 'strcmp');

		$pairs = array();

		self::$boundary = $boundary = uniqid('------------------');
		$MPboundary = '--'.$boundary;
		$endMPboundary = $MPboundary. '--';
		$multipartbody = '';

		foreach ($params as $parameter => $value) {

			if( in_array($parameter, array('pic', 'image')) && $value{0} == '@' ) {
				$url = ltrim( $value, '@' );
				$content = file_get_contents( $url );
				$array = explode( '?', basename( $url ) );
				$filename = $array[0];

				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"'. "\r\n";
				$multipartbody .= "Content-Type: image/unknown\r\n\r\n";
				$multipartbody .= $content. "\r\n";
			} else {
				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
				$multipartbody .= $value."\r\n";
			}

		}

		$multipartbody .= $endMPboundary;
		return $multipartbody;
	}

}
?>
