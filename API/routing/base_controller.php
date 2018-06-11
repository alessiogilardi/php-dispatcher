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
}

?>