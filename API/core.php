<?php
require_once 'loader.php';

class Core {

	private $_controller;
	
	public function __construct() {
		Loader::classmap();

		$rm = new RouteManager();
		$dis = new Dispatcher();
		$dis->setRouteManager($rm);
		$dis->setControllerPath(substr(__DIR__, 0, strpos(__DIR__, basename(__DIR__))).'controllers');
		$this->setController($dis->dispatch());
		if ($this->getController()->getMethod() != null) {
			$this->getController()->executeMethod();
		}
	}

	public function getController() {
		return $this->_controller;
	}

	private function setController($controller) {
		$this->_controller = $controller;
	}
}

?>