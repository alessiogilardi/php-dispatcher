<?php

/**
 * 
 */
class BaseController extends Controller {
	
	public function __construct() {
		# code...
	}

	public function action() {
		$this->getAction()(null);
	}
}

?>