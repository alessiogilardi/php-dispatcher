<?php
/**
 * 
 */
class Loader
{

	// Folders
	private const ROUTING	= './API/routing';
	private const SECURITY 	= './API/security';
	private const DATABASE 	= './API/database'
	/*
	public static function register() {
		spl_autoload_register(function($className) {

		});
	}*/



	public static function classmap() {
		$routing = array(
			'./API/utils/utils_functions.php',
			ROUTING.'/route_manager.php',
			ROUTING.'/route.php',
			ROUTING.'/dispatcher.php',
			ROUTING.'/controller.php',
			ROUTING.'/base_controller.php'
		);
		$constants = array(
			'./API/constants/cookies.php',
			'./API/constants/database.php',
			'./API/constants/error_codes.php',
			'./API/constants/html_form.php',
			'./API/constants/session.php'
		);

		$security = array(
			SECURITY.'/security.php'
		);

		$database = array(
			DATABASE.'/db_adapter.php'
		);

		$classes = array_merge($routing, $constants, $security, $database);
		foreach ($classes as $className) {
			require_once $className;
		}
	}
}
?>