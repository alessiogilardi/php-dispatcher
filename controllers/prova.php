<?php

/**
 * 
 */
class Prova extends Controller {
	
	function __construct() {
		parent::__construct();
		//echo "Classe Prova";
	}

	public function executeMethod() {
		$method = $this->getMethod();
		$this->$method(); 

		//$this->($this->getAction())();
	}

	private function print() {
		//echo "Prova della funzione print";
		var_dump($this->getParams());
	}
}

?>