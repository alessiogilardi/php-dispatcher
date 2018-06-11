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
}

?>