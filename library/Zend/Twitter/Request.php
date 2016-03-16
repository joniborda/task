<?php

class Zend_Twitter_Request {
	protected $parameters;
	protected $httpMethod;
	protected $httpUrl;
	public static $version = '1.0';

	/**
	 * Constructor
	 *
	 * @param string     $httpMethod
	 * @param string     $httpUrl
	 * @param array|null $parameters
	 */
	public function __construct($httpMethod, $httpUrl, array $parameters = []) {
		$parameters = array_merge(Zend_Twitter_Util::parseParameters(parse_url($httpUrl, PHP_URL_QUERY)), $parameters);
		$this->parameters = $parameters;
		$this->httpMethod = $httpMethod;
		$this->httpUrl = $httpUrl;
	}

	/**
	 * pretty much a helper function to set up the request
	 *
	 * @param Consumer $consumer
	 * @param Token    $token
	 * @param string   $httpMethod
	 * @param string   $httpUrl
	 * @param array    $parameters
	 *
	 * @return Request
	 */
	public static function fromConsumerAndToken(
		Zend_Twitter_Consumer $consumer,
		Zend_Twitter_Token $token = null,
		$httpMethod,
		$httpUrl,
		array $parameters = []
	) {
		$defaults = [
			"oauth_version" => Zend_Twitter_Request::$version,
			"oauth_nonce" => Zend_Twitter_Request::generateNonce(),
			"oauth_timestamp" => time(),
			"oauth_consumer_key" => $consumer->key,
		];
		if (null !== $token) {
			$defaults['oauth_token'] = $token->key;
		}

		$parameters = array_merge($defaults, $parameters);

		return new Zend_Twitter_Request($httpMethod, $httpUrl, $parameters);
	}

	/**
	 * @param string $name
	 * @param string $value
	 */
	public function setParameter($name, $value) {
		$this->parameters[$name] = $value;
	}

	/**
	 * @param $name
	 *
	 * @return string|null
	 */
	public function getParameter($name) {
		return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
	}

	/**
	 * @return array
	 */
	public function getParameters() {
		return $this->parameters;
	}

	/**
	 * @param $name
	 */
	public function removeParameter($name) {
		unset($this->parameters[$name]);
	}

	/**
	 * The Zend_Twitter_request parameters, sorted and concatenated into a normalized string.
	 *
	 * @return string
	 */
	public function getSignableParameters() {
		// Grab all parameters
		$params = $this->parameters;

		// Remove oauth_signature if present
		// Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
		if (isset($params['oauth_signature'])) {
			unset($params['oauth_signature']);
		}

		return Zend_Twitter_Util::buildHttpQuery($params);
	}

	/**
	 * Returns the base string of this Zend_Twitter_request
	 *
	 * The base string defined as the method, the url
	 * and the parameters (normalized), each urlencoded
	 * and the concated with &.
	 *
	 * @return string
	 */
	public function getSignatureBaseString() {
		$parts = [
			$this->getNormalizedHttpMethod(),
			$this->getNormalizedHttpUrl(),
			$this->getSignableParameters(),
		];

		$parts = Zend_Twitter_Util::urlencodeRfc3986($parts);

		return implode('&', $parts);
	}

	/**
	 * Returns the HTTP Method in uppercase
	 *
	 * @return string
	 */
	public function getNormalizedHttpMethod() {
		return strtoupper($this->httpMethod);
	}

	/**
	 * parses the url and rebuilds it to be
	 * scheme://host/path
	 *
	 * @return string
	 */
	public function getNormalizedHttpUrl() {
		$parts = parse_url($this->httpUrl);

		$scheme = $parts['scheme'];
		$host = strtolower($parts['host']);
		$path = $parts['path'];

		return "$scheme://$host$path";
	}

	/**
	 * Builds a url usable for a GET Zend_Twitter_request
	 *
	 * @return string
	 */
	public function toUrl() {
		$postData = $this->toPostdata();
		$out = $this->getNormalizedHttpUrl();
		if ($postData) {
			$out .= '?' . $postData;
		}
		return $out;
	}

	/**
	 * Builds the data one would send in a POST Zend_Twitter_request
	 *
	 * @return string
	 */
	public function toPostdata() {
		return Zend_Twitter_Util::buildHttpQuery($this->parameters);
	}

	/**
	 * Builds the Authorization: header
	 *
	 * @return string
	 * @throws TwitterOAuthException
	 */
	public function toHeader() {
		$first = true;
		$out = 'Authorization: OAuth';
		foreach ($this->parameters as $k => $v) {
			if (substr($k, 0, 5) != "oauth") {
				continue;
			}
			if (is_array($v)) {
				throw new TwitterOAuthException('Arrays not supported in headers');
			}
			$out .= ($first) ? ' ' : ', ';
			$out .= Zend_Twitter_Util::urlencodeRfc3986($k) . '="' . Zend_Twitter_Util::urlencodeRfc3986($v) . '"';
			$first = false;
		}
		return $out;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->toUrl();
	}

	/**
	 * @param SignatureMethod $signatureMethod
	 * @param Consumer        $consumer
	 * @param Token           $token
	 */
	public function signRequest(Zend_Twitter_SignatureMethod $signatureMethod, Zend_Twitter_Consumer $consumer, Zend_Twitter_Token $token = null) {
		$this->setParameter("oauth_signature_method", $signatureMethod->getName());
		$signature = $this->buildSignature($signatureMethod, $consumer, $token);
		$this->setParameter("oauth_signature", $signature);
	}

	/**
	 * @param SignatureMethod $signatureMethod
	 * @param Consumer        $consumer
	 * @param Token           $token
	 *
	 * @return string
	 */
	public function buildSignature(Zend_Twitter_SignatureMethod $signatureMethod, Zend_Twitter_Consumer $consumer, Zend_Twitter_Token $token = null) {
		return $signatureMethod->buildSignature($this, $consumer, $token);
	}

	/**
	 * @return string
	 */
	public static function generateNonce() {
		return md5(microtime() . mt_rand());
	}
}
