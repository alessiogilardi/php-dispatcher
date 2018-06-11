<?php
/**
 * 
 */
class Security {

	private const MASTER_KEY =
		'iCRZxINfYS9j69IJ7Ns8jr3iIMmBwrgLHfuUZTaVPtOTtQ6QLkIFMSAAtKRiaDa6a02yhXAsMmhZOQ60v5xXlX3wlPoI5YMXcSvVUhqdasMHIrKmIgynV9mnoJbllFbF';
	private const SEPARATOR = ':';
	private $_key = '';
	private $_algo = 'sha256';
/*
	public static function generateToken($length = 128) {
		return bin2hex(random_bytes($length));
		
		$token = '';
     	$codeAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $codeAlphabet.= 'abcdefghijklmnopqrstuvwxyz';
	    $codeAlphabet.= '0123456789';
	    $max = strlen($codeAlphabet);

	    for ($i=0; $i < $length; $i++) {
	        $token .= $codeAlphabet[random_int(0, $max-1)];
	    }

	    return $token;
	}
*/
	public function __construct($keyLength, $algo = 'sha256') {
		//$this->_key 	= Security::generateToken($keyLength);
		$this->_key 	= $this->generateToken($keyLength);
		$this->_algo 	= $algo;
	}

	public function getMasterKey() {
		return Security::MASTER_KEY;
	}

	public function generateToken($length) {
		return bin2hex(random_bytes($length));
	}

	public function setKey($aKey) {
		$this->_key = $aKey;
	}

	public function getKey() {
		return $this->_key;
	}

	public function setAlgo($aAlgo) {
		$this->_algo = $aAlgo;
	}

	public function getAlgo() {
		return $this->_algo;
	}

	public function sign($value) {
		$algo 	= $this->getAlgo();
		$key 	= $this->getKey();
		$master = $this->getMasterKey();
		return $value.Security::SEPARATOR.$algo.Security::SEPARATOR.hash_hmac($algo, $value, $key.$master);
		//return $value.hash_hmac($algo, $value, $key.$master);
	}

	public function checkSign($signed) {
		$master = $this->getMasterKey();
		$key 	= $this->getKey();
		$data 	= $this->extractData($signed);

		if (hash_equals(hash_hmac($data['algo'], $data['value'], $key.$master), $data['mac'])) {
			return $data['value'];
		}
		return false;

	}

	private function extractData($signed) {
		if (Security::isWellFormed($signed)) {
			return array_fill_keys(array('value', 'algo', 'mac'), explode(Security::SEPARATOR, $signed));
		}
		throw new RuntimeException("The signed value is badly formed", 1);
	}

	/**
	* Checks if a signed string is well formed and can be extracted to data, algo, signature
	*/
	private static function isWellFormed($signed) {
		return (substr_count($signed, Security::SEPARATOR) == 2);
	}
/*
	public static function hashSign($value, $signature = '') {
  		return $value.hash_hmac('sha256', $value, $signature.$masterKey);
	}

	public static function verifyHashSign($signed, $signature = '') {
		$val = substr($signed, 0, strlen($signed)-64);
	  	$mac = substr($signed, strlen($signed)-64, strlen($signed)-1);
	  	if (hash_equals(hash_hmac('sha256', $val, $signature.$masterKey), $mac)) {
	    	return $val;
	  	}
	 	return false;
	}
*/
}


?>	