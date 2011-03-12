<?php

abstract class ModelAbstract {

	/**
	 * Convert a string (or array of strings) from camelCase to underscore_naming
	 * @param mixed $str
	 * @return mixed
	 */
	public function fromCamelCase($str)
	{
		$str[0] = strtolower($str[0]);
		$func = create_function('$c', 'return "_" . strtolower($c[1]);');
		return preg_replace_callback('/([A-Z])/', $func, $str);
	}

	/**
	 * Convert a string (or array of strings) from underscores to camelCase
	 * @param mixed $str
	 * @param boolean $capitalise_first_char
	 * @return mixed
	 */
	public function toCamelCase($str, $capitalise_first_char = false)
	{
		if ($capitalise_first_char) {
			$str[0] = strtoupper($str[0]);
		}
		$func = create_function('$c', 'return strtoupper($c[1]);');
		return preg_replace_callback('/_([a-z])/', $func, $str);
	}
}