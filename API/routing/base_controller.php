<?php

/**
 * 
 */
class BaseController extends Controller {
	
	public function __construct() {
		parent::__construct();
	}

	public function executeMethod() {
		$this->getAction()(null);
	}

	public function defaultMethod($data = null) {
		echo "Metodo generico di Base Controller";
	}
}

?>