<?php

/**
 * 
 */
class Prova extends Controller {
	
	function __construct() {
		parent::__construct();
		echo "Classe Prova";
	}

	protected function action() {
		$this->getAction()('');
	}

	private function print($data) {
		echo "Prova della funzione print";
		//var_dump($this->getParams());
	}
}

?>