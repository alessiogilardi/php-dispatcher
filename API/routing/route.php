<?php
class Route {
	private const SCHEME = array('area', 'controller', 'action', 'params');

	private $_uri = '';
	private $_area = '';
	private $_controller = '';
	private $_action = '';
	private $_params = array('POST' => array(), 'GET' => array());
/*
	public static function buildFromRoute(Route $route) {
		//$instance = new Route($route->getUri());
		$instance = new Route();
		$instance->clone($route);
		return $instance;
	}
*/
	public static function parseUri($aUri) {
		//$route = BASE_ROUTE;
		$temp = [];
		$urlSegments = explode('/', $aUri);
		if (count($urlSegments) > count(Route::SCHEME)) {
			throw new RuntimeException('Malformed URI', -15);
		}

		foreach ($urlSegments as $index => $segment) {
			//$route[Route::SCHEME[$index]] = $segment;
			$temp += [Route::SCHEME[$index] => $segment];
		}

		return $temp;
	}

	private function parseParams() {
		$this->_params['POST'] 	= $_POST;
		$this->_params['GET'] 	= $_GET;
	}

	public function __construct($aUri) {
		$route = Route::parseUri($aUri);
		$this->_uri 		= $aUri;
		$this->_area 		= $route['area'];
		$this->_controller 	= $route['controller'];
		$this->_action 		= isset($route['action'])?$route['action']:NULL;
		$this->parseParams();
	}

	public function getUri() {
		return $this->_uri;
	}

	public function getArea() {
		return $this->_area;
	}

	public function getController() {
		return $this->_controller;
	}

	public function getAction() {
		return $this->_action;
	}

	public function getParams() {
		return $this->_params;
	}

	public function clone($aRoute) {
		$this->_uri 		= $aRoute->getUri();
		$this->_area 		= $aRoute->getArea();
		$this->_controller 	= $aRoute->getController();
		$this->_action 		= $aRoute->getAction();
		$this->_params 		= $aRoute->getParams();
	}
}
?>