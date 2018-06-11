<?php

/**
 * 
 */
class Utils {
	
	public static function toCamelCase($string, $delimiter, $ucfirst = true) {
		if ($ucfirst)
			return str_replace($delimiter, '', ucwords($string, $delimiter));
		return str_replace($delimiter, '', lcfirst(ucwords($string, $delimiter)));
	}

	public static function destroyCookie($name, $path = '/') {
		if (isset($_COOKIE[$name])) {
			unset($_COOKIE[$name]);
			setcookie($name, '', time() - 3600, $path);
		}
	}
}

?>