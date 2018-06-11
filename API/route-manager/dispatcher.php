<?php
/**
 * Il Dispatcher deve solo occuparsi di fornire il file controller con i vari controlli del caso, il file si occuperà di eseguire i comandi * giusti
 */
//define('DIRECTORY_SEPARATOR', '/');
//define('BASE_CONTROLLER', 'Controller');

class Dispatcher {
    private const DEFAULT_CONTROLLER = 'controller';

	private $_rm; // RouteManager
	private $_headers = array();
	//private $_controllerPath = .DIRECTORY_SEPARATOR.'controllers';
    // !!!!!ATTENZIONE DA MODIFICARE!!!!!! Il percorso deve essere ricavato!!!!!!
    private $_controllerPath; /*= 'C:\\xampp\\htdocs\\login-system'.DIRECTORY_SEPARATOR.'controllers'; */
	private $_delimiter = '-';

	private function loadClass($classPath) {
		require_once $classPath;
	}
	
	public function getClassPath($controller) {
		return $this->getControllerPath().DIRECTORY_SEPARATOR.str_replace($this->_delimiter, '_', $controller).'.php';
	}

	public function getClassName($controller) {
        //echo "<br>".str_replace($this->_delimiter, '', ucwords($controller, $this->_delimiter))."<br>";
		return str_replace($this->_delimiter, '', ucwords($controller, $this->_delimiter));
	}

	public function getMethodName($action) {
        //echo "<br>".str_replace($this->_delimiter, '', lcfirst(ucwords($action, $this->_delimiter)))."<br>";
		return str_replace($this->_delimiter, '', lcfirst(ucwords($action, $this->_delimiter))); // Va ritornata la action in camel case con la prima lettera minuscola
	}

	public function __construct() {
		//
	}

	public function setRouteManager(RouteManager $rm) {
		$this->_rm = $rm;
	}

	public function getRouteManager() {
		return $this->_rm;
	}

	public function setControllerPath($path) {
        $this->_controllerPath = $path;
    }

    public function getControllerPath() {
    	return $this->_controllerPath;
    }
    
    public function dispatch() {
        if ($this->getRouteManager() == NULL) {
            throw new RuntimeException('RouteManager not set', 1);
        }

        if ($this->getControllerPath() == NULL) {
            throw new RuntimeException('Controllers path not set', 1);
        }

    	$route             = $this->getRouteManager()->getRoute();
    	$controllerName    = $route->getController();
    	$classPath 	       = $this->getClassPath($controllerName);
    	$className 	       = $this->getClassName($controllerName);

    	if (!file_exists($classPath)) {
            $this->setControllerPath(__DIR__);
    		$classPath = $this->getClassPath(Dispatcher::DEFAULT_CONTROLLER);
    		$className = $this->getClassName(Dispatcher::DEFAULT_CONTROLLER);
    	} else {
    		$this->loadClass($classPath);
    	}

    	$controller = new $className;
    	if ($route->getAction() !== NULL) {
            $method = $this->getMethodName($route->getAction());
            if (!method_exists($controller, $method)) {
                throw new RuntimeException('Page not found '.$classPath.'->'.$method, 404);
            }
            $controller->setParams($route->getParams());
	    	$controller->setAction($method);
	    	return $controller;
    	}
    }

	public function sendHeaders() {
        $headers = $this->getHeaders();
        foreach ($headers as $header) {
            header($header['string'], $header['replace'], $header['code']);
        }
    }

    public function clearHeaders() {
        $this->$headers = array();
    }

    public function addHeader($key, $value, $httpCode = 200, $replace  = true) {
        $this->_headers[] = array('string' => "{$key}:{$value}", 'replace'=> $replace, 'code' => (int)$httpCode);
    }

    public function getHeaders() {
        return $this->_headers;
    }
}

?>