<?php
/**
 * 
 */
class Loader
{

	// Folders
	private const ROUTING	= './API/routing';
	private const SECURITY 	= './API/security';
	private const DATABASE 	= './API/database';
	private const UTILS		= './API/utils';
	/*
	public static function register() {
		spl_autoload_register(function($className) {

		});
	}*/

	public static function loadClass($classPath) {
		require_once $classPath;
	}


	public static function classmap() {

		// TODO: usa un ciclo per generare l'array
		$routing = array(
			'/route_manager.php',
			'/route.php',
			'/dispatcher.php',
			'/controller.php',
			'/base_controller.php'
		);
		$utils = array(
			'/utils.php'
		);
		$security = array(
			'/security.php'
		);
		$database = array(
			'/db_adapter.php'
		);

		foreach ($routing as &$r) {
			$r = self::ROUTING.$r;
		}

		foreach ($utils as &$u) {
			$u = self::UTILS.$u;
		}

		foreach ($security as &$s) {
			$s = self::SECURITY.$s;
		}

		foreach ($database as &$d) {
			$d = self::DATABASE.$d;
		}

		$classes = array_merge($routing, $utils, $security, $database);
		foreach ($classes as $className) {
			require_once $className;
		}
	}
}
?>