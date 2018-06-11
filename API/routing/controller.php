<?php
/**
 * 
 */
abstract class Controller {
	private const DEFAULT_METHOD = 'defaultMethod';

	private $_params;
	private $_method;
	

	abstract public function executeMethod();
	abstract public function defaultMethod($data = null); // questo metodo viene chiamato sempre nel caso in cui non sia specificato alcun metodo ed è implementato dall'utente
	
	public function __construct() {
		// echo '<br>Controller started.<br>';
	}

	public function setParams($params) {
		$this->_params = $params;
	}

	public function getParams() {
		return $this->_params;
	}
/*
	public function init() {

	}
*/
	public function setMethod($method) {
		// set the method to execute
		$this->_method = $method;
	}


	public function getMethod() {
		return $this->_method;
	}

	public function setMethodToDefault() {
		$this->setMethod(self::DEFAULT_METHOD);
	}

/*
	public function action() {
		// calls the method saved in action
		//$this->_action($this->_params);
		$this->getAction()();
	}

	private function getAction() {
		return $this->_action;
	}
*/
}

?>