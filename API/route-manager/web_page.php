<?php 

class WebPage { // le varie pagine possono estendere questa
	private $title;
	private $styles;
	private $scripts;
	private $head;
	private $body;
	private $footer;

	// forse meglio definire:
	private $_params = array('title' => '', 'styles' => '');

	private $mRoute;

	private static function getControllerData($controller) {
		if (isset(CONTROLLER_DATA[$controller]))
			return CONTROLLER_DATA[$controller];
		return CONTROLLER_DATA['404'];
	}
/*
	public function __construct($title = '', $styles = array(), $scripts = array(), $file = '') {
		$this->title 	= $title;
		$this->styles 	= $styles;
		$this->scripts 	= $scripts;
		$this->file 	= $file;
	}
*/

	public function __construct($aRoute) {
		$this->mRoute = $aRoute;
		$data = WebPage::getControllerData($aRoute->getController());
		$this->title 	= $data['title'];
		$this->styles 	= $data['styles'];
		$this->scripts 	= $data['scripts'];
		$this->head		= $data['files']['head'];
		$this->body		= $data['files']['body'];
		$this->footer 	= $data['files']['footer'];
	}

	public function getTitle() {
		return $this->title;
	}

	public function getStyles() {
		return $this->styles;
	}

	public function getScripts() {
		return $this->scripts;
	}

	public function getHead() {
		return $this->head;
	}

	public function getBody() {
		return $this->body;
	}

	public function getFooter() {
		return $this->footer;
	}

	public function buildPage() {

	}
}

?>