<?php
/**
 * The Akismet PHP5 Class
 * 
 * This class has been adapted to Bancha. http://getbancha.com
 * 
 * @author Nicholas Valbusa
*
 * @author		Alex Potsides
 * @link		http://www.achingbrain.net/
 */
class Akismet extends Core
{
	private $banchaApiKey;
	private $websiteURL;
	private $comment;
	private $apiPort;
	private $akismetServer;
	private $akismetVersion;
	
	private $ignore = array(
		'HTTP_COOKIE', 
		'HTTP_X_FORWARDED_FOR', 
		'HTTP_X_FORWARDED_HOST', 
		'HTTP_MAX_FORWARDS', 
		'HTTP_X_FORWARDED_SERVER', 
		'REDIRECT_STATUS', 
		'SERVER_PORT', 
		'PATH',
		'DOCUMENT_ROOT',
		'SERVER_ADMIN',
		'QUERY_STRING',
		'PHP_SELF'
	);
	
	public function __construct($config = array())
	{	
		$key = $this->settings->get('akismet_key', 'Services');
		if (is_string($key) && strlen($key)) {
			$this->banchaApiKey = $key;
		} else {
			log_message('error', 'Akismet key not set!');
			return FALSE;
		}

		if (isset($config['url'])) {
			$this->websiteURL = $config['url'];
		} else {
			$this->websiteURL = site_url();
		}

		if (isset($config['author'])) {
			$this->setCommentAuthor($config['author']);
		}

		if (isset($config['website'])) {
			$this->setCommentAuthorURL($config['website']);
		}

		if (isset($config['email'])) {
			$this->setCommentAuthorEmail($config['email']);
		}

		if (isset($config['message'])) {
			$this->setCommentContent($config['message']);
		}

		if (isset($config['permalink'])) {
			$this->setPermalink($config['permalink']);
		} else {
			$this->setPermalink(current_url());
		}
		
		// Set some default values
		$this->apiPort = 80;
		$this->akismetServer = 'rest.akismet.com';
		$this->akismetVersion = '1.1';
		
		// Start to populate the comment data
		$this->comment['blog'] = $this->websiteURL;
		$this->comment['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		
		if(isset($_SERVER['HTTP_REFERER'])) {
			$this->comment['referrer'] = $_SERVER['HTTP_REFERER'];
		}

		$this->comment['user_ip'] = $this->input->ip_address();
	}
	
	/**
	 * Makes a request to the Akismet service to see if the API key passed to the constructor is valid.
	 * 
	 * Use this method if you suspect your API key is invalid.
	 * 
	 * @return bool	True is if the key is valid, false if not.
	 */
	public function isKeyValid() {
		// Check to see if the key is valid
		$response = $this->sendRequest('key=' . $this->banchaApiKey . '&blog=' . $this->websiteURL, $this->akismetServer, '/' . $this->akismetVersion . '/verify-key');
		return $response[1] == 'valid';
	}
	
	// makes a request to the Akismet service
	private function sendRequest($request, $host, $path) {
		$http_request  = "POST " . $path . " HTTP/1.0\r\n";
		$http_request .= "Host: " . $host . "\r\n";
		$http_request .= "Content-Type: application/x-www-form-urlencoded; charset=utf-8\r\n";
		$http_request .= "Content-Length: " . strlen($request) . "\r\n";
		$http_request .= "User-Agent: Bancha " . BANCHA_VERSION . " | Akismet/1.11\r\n";
		$http_request .= "\r\n";
		$http_request .= $request;
		
		$socketWriteRead = new SocketWriteRead($host, $this->apiPort, $http_request);
		$socketWriteRead->send();
		
		return explode("\r\n\r\n", $socketWriteRead->getResponse(), 2);
	}
	
	// Formats the data for transmission
	private function getQueryString() {
		foreach($_SERVER as $key => $value) {
			if(!in_array($key, $this->ignore)) {
				if($key == 'REMOTE_ADDR') {
					$this->comment[$key] = $this->comment['user_ip'];
				} else {
					$this->comment[$key] = $value;
				}
			}
		}

		$query_string = '';
		
		foreach($this->comment as $key => $data) {
			if(!is_array($data)) {
				$query_string .= $key . '=' . urlencode(stripslashes($data)) . '&';
			}
		}
		
		return $query_string;
	}
	
	/**
	 *	Tests for spam.
	 *
	 *	Uses the web service provided by {@link http://www.akismet.com Akismet} to see whether or not the submitted comment is spam.  Returns a boolean value.
	 *
	 *	@return		bool	True if the comment is spam, false if not
	 *  @throws		Will throw an exception if the API key passed to the constructor is invalid.
	 */
	public function is_spam()
	{
		$response = $this->sendRequest($this->getQueryString(), $this->banchaApiKey . '.rest.akismet.com', '/' . $this->akismetVersion . '/comment-check');
		
		if($response[1] == 'invalid' && !$this->isKeyValid()) {
			log_message('error', 'The Bancha API key passed to the Akismet constructor is invalid.  Please obtain a valid one from http://akismet.com/get');
			return FALSE;
		}
		
		return ($response[1] == 'true');
	}
	
	/**
	 *	To override the user IP address when submitting spam/ham later on
	 *
	 *	@param string $userip	An IP address.  Optional.
	 */
	public function setUserIP($userip) {
		$this->comment['user_ip'] = $userip;
	}
	
	/**
	 *	To override the referring page when submitting spam/ham later on
	 *
	 *	@param string $referrer	The referring page.  Optional.
	 */
	public function setReferrer($referrer) {
		$this->comment['referrer'] = $referrer;
	}
	
	/**
	 *	A permanent URL referencing the blog post the comment was submitted to.
	 *
	 *	@param string $permalink	The URL.  Optional.
	 */
	public function setPermalink($permalink) {
		$this->comment['permalink'] = $permalink;
	}
	
	/**
	 *	The type of comment being submitted.  
	 *
	 *	May be blank, comment, trackback, pingback, or a made up value like "registration" or "wiki".
	 */
	public function setCommentType($commentType) {
		$this->comment['comment_type'] = $commentType;
	}
	
	/**
	 *	The name that the author submitted with the comment.
	 */
	public function setCommentAuthor($commentAuthor) {
		$this->comment['comment_author'] = $commentAuthor;
	}
	
	/**
	 *	The email address that the author submitted with the comment.
	 *
	 *	The address is assumed to be valid.
	 */
	public function setCommentAuthorEmail($authorEmail) {
		$this->comment['comment_author_email'] = $authorEmail;
	}
	
	/**
	 *	The URL that the author submitted with the comment.
	 */	
	public function setCommentAuthorURL($authorURL) {
		$this->comment['comment_author_url'] = $authorURL;
	}
	
	/**
	 *	The comment's body text.
	 */
	public function setCommentContent($commentBody) {
		$this->comment['comment_content'] = $commentBody;
	}
	
	/**
	 *	Defaults to 80
	 */
	public function setAPIPort($apiPort) {
		$this->apiPort = $apiPort;
	}
	
	/**
	 *	Defaults to rest.akismet.com
	 */
	public function setAkismetServer($akismetServer) {
		$this->akismetServer = $akismetServer;
	}
	
	/**
	 *	Defaults to '1.1'
	 */
	public function setAkismetVersion($akismetVersion) {
		$this->akismetVersion = $akismetVersion;
	}
}

/**
 *	Utility class used by Akismet
 *
 *  This class is used by Akismet to do the actual sending and receiving of data.  It opens a connection to a remote host, sends some data and the reads the response and makes it available to the calling program.
 *
 *  The code that makes up this class originates in the Akismet WordPress plugin, which is {@link http://akismet.com/download/ available on the Akismet website}.
 *
 *	N.B. It is not necessary to call this class directly to use the Akismet class.  This is included here mainly out of a sense of completeness.
 *
 *	@package	akismet
 *	@name		SocketWriteRead
 *	@version	0.1
 *  @author		Alex Potsides
 *  @link		http://www.achingbrain.net/
 */
class SocketWriteRead {
	private $host;
	private $port;
	private $request;
	private $response;
	private $responseLength;
	private $errorNumber;
	private $errorString;
	
	/**
	 *	@param	string	$host			The host to send/receive data.
	 *	@param	int		$port			The port on the remote host.
	 *	@param	string	$request		The data to send.
	 *	@param	int		$responseLength	The amount of data to read.  Defaults to 1160 bytes.
	 */
	public function __construct($host, $port, $request, $responseLength = 1160) {
		$this->host = $host;
		$this->port = $port;
		$this->request = $request;
		$this->responseLength = $responseLength;
		$this->errorNumber = 0;
		$this->errorString = '';
	}
	
	/**
	 *  Sends the data to the remote host.
	 *
	 * @throws	An exception is thrown if a connection cannot be made to the remote host.
	 */
	public function send() {
		$this->response = '';
		
		$fs = fsockopen($this->host, $this->port, $this->errorNumber, $this->errorString, 3);
		
		if($this->errorNumber != 0) {
			throw new Exception('Error connecting to host: ' . $this->host . ' Error number: ' . $this->errorNumber . ' Error message: ' . $this->errorString);
		}
		
		if($fs !== false) {
			@fwrite($fs, $this->request);
			
			while(!feof($fs)) {
				$this->response .= fgets($fs, $this->responseLength);
			}
			
			fclose($fs);
		}
	}
	
	/**
	 *  Returns the server response text
	 *
	 *  @return	string
	 */
	public function getResponse() {
		return $this->response;
	}
	
	/**
	 *	Returns the error number
	 *
	 *	If there was no error, 0 will be returned.
	 *
	 *	@return int
	 */
	public function getErrorNumber() {
		return $this->errorNumber;
	}
	
	/**
	 *	Returns the error string
	 *
	 *	If there was no error, an empty string will be returned.
	 *
	 *	@return string
	 */
	public function getErrorString() {
		return $this->errorString;
	}
}

?>