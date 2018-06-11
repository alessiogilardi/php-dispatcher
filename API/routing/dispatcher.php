<?php
/**
 * Il Dispatcher deve solo occuparsi di fornire il file controller con i vari controlli del caso, il file si occuperà di eseguire i comandi * giusti
 */
//define('DIRECTORY_SEPARATOR', '/');
//define('BASE_CONTROLLER', 'Controller');

class Dispatcher {
    private const DEFAULT_CONTROLLER = 'base-controller';

	private $_rm; // RouteManager
	private $_headers = array();
	//private $_controllerPath = .DIRECTORY_SEPARATOR.'controllers';
    private $_controllerPath; /*= 'C:\\xampp\\htdocs\\login-system'.DIRECTORY_SEPARATOR.'controllers'; */
	private $_delimiter = '-';

	public function getClassPath($controller) {
		return $this->getControllerPath().DIRECTORY_SEPARATOR.str_replace($this->_delimiter, '_', $controller).'.php';
	}

	public function getClassName($controller) {
        //echo Utils::toCamelCase($controller, $this->_delimiter).'<br';
        return Utils::toCamelCase($controller, $this->_delimiter);
	}

	public function getMethodName($action) {
        //echo Utils::toCamelCase($action, $this->_delimiter, false).'<br>';
        return Utils::toCamelCase($action, $this->_delimiter, false);
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
            Loader::loadClass($classPath);
    	}

    	$controller = new $className;


        // TODO: Se non specifico un metodo devo chiamarne uno generico alrimenti eseguo solo il costruttore 
        //  e non faccio altro
    	if ($route->getAction() !== null) {
            $method = $this->getMethodName($route->getAction());
            if (!method_exists($controller, $method)) {
                throw new RuntimeException('Page not found '.$classPath.'->'.$method, 404);
            }
	    	$controller->setMethod($method);
	    	//return $controller;
    	}
        //var_dump($route->getParams());
        //var_dump($route->getAction());
        $controller->setParams($route->getParams());
        //var_dump($controller->getParams());
        return $controller;
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