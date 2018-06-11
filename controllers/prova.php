<?php

/**
 * 
 */
class Prova extends Controller {
	
	public function __construct() {
		parent::__construct();
	}

	public function executeMethod() {
		$method = $this->getMethod();
		$this->$method(); 
	}

	public function defaultMethod($data = null) {
		echo "Metodo deafult di Prova";
	}

	private function print() {
		//echo "Prova della funzione print";
		var_dump($this->getParams());
	}
}

?>