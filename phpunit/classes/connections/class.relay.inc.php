<?php
namespace connections;

class relay
{
	private $get;
	private $post;

	private $headers = array();

	/**
	 * relay constructor.
	 */
	public function __construct()
	{
		/**
		 * Important to pass by reference
		 */
		$this->get = &$_GET;
		$this->post = &$_POST;
	}

	/**
	 * Generate current HTTP Referrer
	 * @return string
	 */
	private function http_referer()
	{
		$scheme = !empty($_SERVER["REQUEST_SCHEME"]) ? $_SERVER["REQUEST_SCHEME"] : "http";
		$port = !empty($_SERVER["SERVER_PORT"]) ? ":{$_SERVER["SERVER_PORT"]}" : "";
		$uri = !empty($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "/";
		$server = !empty($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : "localhost";
		$referer = "{$scheme}://{$server}{$port}{$uri}";

		return $referer;
	}

	/**
	 * @param string $url
	 * @param array $data
	 * @return string
	 */
	private function parse_merge($url="", $data=array())
	{
		$chunks = parse_url($url);
		#print_r($chunks);
		if(empty($chunks["scheme"])) $chunks["scheme"] = "http";
		if(empty($chunks["host"])) $chunks["host"] = "localhost";
		if(empty($chunks["port"])) $chunks["port"] = "";
		if(empty($chunks["user"])) $chunks["user"] = "";
		if(empty($chunks["pass"])) $chunks["pass"] = "";
		if(empty($chunks["path"])) $chunks["path"] = "/";
		if(empty($chunks["query"])) $chunks["query"] = "";
		if(empty($chunks["fragment"])) $chunks["fragment"] = "";

		#print_r($chunks);

		$chunks["pass"] = ($chunks["user"] || $chunks["pass"])?":{$chunks["pass"]}":"";
		$chunks["port"] = empty($chunks["port"])?"":":{$chunks["port"]}";

		$queries = array();
		parse_str($chunks["query"], $queries);
		#print_r($queries);
		#print_r(func_get_args());
		$queries = array_merge((array)$queries, (array)$data);

		$chunks["query"] = http_build_query($queries);
		$chunks["query"] = $chunks["query"]?"?{$chunks["query"]}":"";

		$chunks["fragment"] = $chunks["fragment"]?"#{$chunks["fragment"]}":"";

		$url = "{$chunks['scheme']}://{$chunks['user']}{$chunks['pass']}{$chunks['host']}{$chunks['port']}{$chunks['path']}{$chunks['query']}{$chunks['fragment']}";

		return $url;
	}

	/**
	 * @see https://en.wikipedia.org/wiki/List_of_HTTP_header_fields#Request_fields
	 * @param array $headers_assoc
	 */
	public function headers($headers_assoc=array())
	{
		/**
		 * Optional headers
		 */
		$headers = array();
		foreach($headers_assoc as $name => $value)
		{
			/**
			 * Custom headers must have DASHes, not UNDERSCOREs.
			 *
			 * X_PROTECTION_CODE
			 * X-Protection-Code
			 *
			 * HTTP_X_PROTECTION_CODE
			 */
			$name = str_replace("_", "-", $name);

			$headers[] = "{$name}: {$value}";
		}

		$this->headers = $headers;
	}

	/**
	 * @param string $url
	 * @return mixed
	 */
	public function fetch($url = "")
	{
		$_SERVER["HTTP_REFERER"] = $this->http_referer();

		/**
		 * @todo Sanitize merge process
		 * @todo Specifically prefer the HTTP verbs
		 */
		$url = $this->parse_merge($url, $this->get);

		/**
		 * GET parameters are already made with URL
		 * No need to use the variable
		 */
		//$get_parameters = http_build_query((array)$this->get); # Unused?


		#echo $url, $post_parameters, $get_parameters;
		#echo "Curl Fetch called.";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);

		// @see http://php.net/manual/en/function.curl-setopt.php
		// print_r($this); die();
		if(count($this->post))
		{
			/**
			 * @see http://php.net/manual/en/function.curl-setopt.php
			 * Passing an array to CURLOPT_POSTFIELDS will encode the data as multipart/form-data, while passing a URL-encoded string will encode the data as application/x-www-form-urlencoded.
			 * multipart/form-data
			 * application/x-www-form-urlencoded
			 * @todo Handle cases with files upload
			 */
			// @todo When uploading a file, do not build with http query
			curl_setopt($ch, CURLOPT_HTTPGET, false);
			curl_setopt($ch, CURLOPT_POST, true);

			//$post_parameters = http_build_query((array)$this->post);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->post);
			//curl_setopt($ch, CURLOPT_POSTFIELDS, $post_parameters);

			#echo "Sending POST data: ";
			#print_r($this->post);
		} # causes error on IP lookup
		else
		{
			curl_setopt($ch, CURLOPT_POST, false);
			curl_setopt($ch, CURLOPT_HTTPGET, true);
		}

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 50); # 2 for IP lookup
		curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
		curl_setopt($ch, CURLOPT_NOPROGRESS, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 50);
		curl_setopt($ch, CURLOPT_REFERER, $_SERVER["HTTP_REFERER"]); # sometimes selective
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]); # sometimes selective
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_HEADEROPT, CURLHEADER_UNIFIED);
		curl_setopt($ch, CURLOPT_ENCODING, "deflate");

		// CURLOPT_CUSTOMREQUEST
		// GET, HEAD, DELETE, POST, CONNECT

		/**
		 * Only for file uploads?
		 * [REQUEST_METHOD] => PUT
		 */
		#curl_setopt($ch, CURLOPT_UPLOAD, true);
		#curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true); // removed in PHP 7
		// CURLOPT_STDERR
		// CURLOPT_UPLOAD

		// file upload settings

		/**
		 * Additional custom headers
		 * eg. Protection Code
		 * eg. Authorization Token
		 */
		if(count($this->headers))
		{
			// CURLOPT_HEADEROPT
			// CURLOPT_HTTPHEADER
			// CURLOPT_PROXYHEADER
			// CURLOPT_CUSTOMREQUEST
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
			//curl_setopt_array($ch, $this->headers);
		}
		
		/**
		 * @todo Fix the file path
		 * @see https://curl.haxx.se/libcurl/c/CURLOPT_COOKIEJAR.html
		 */
		curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/cookie.jar");
		curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/cookie.jar");
		$content_extracted = curl_exec($ch);

		if($error = curl_errno($ch)) {
			$error_message = curl_strerror($error);
			echo "cURL error #{$error}:\n {$error_message}";
		}

		// @see http://php.net/curl_getinfo
		// CURLINFO_HEADER_OUT
		// CURLINFO_HTTP_CODE
		// CURLINFO_HEADER_SIZE
		// CURLINFO_CONTENT_TYPE
		// CURLINFO_RESPONSE_CODE

		$http_code	= curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$headers = curl_getinfo($ch,CURLINFO_HEADER_OUT);
		#echo $headers;
		
		curl_close($ch);
		return $content_extracted;
	}
}
