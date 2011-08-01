<?php
class APP_Helper_View {

	/**
	 * Escape a value for output
	 *
	 * @param string $var
	 * @return string
	 */
	function escape($var) {
		return htmlentities($var, ENT_QUOTES, 'UTF-8');
	}

}