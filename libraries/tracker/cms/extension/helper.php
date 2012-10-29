<?php
/**
 * User: elkuku
 * Date: 28.10.12
 * Time: 15:11
 */

class JCmsExtensionHelper
{
	/**
	 * Translator function.
	 *
	 * @param   string  $string  The string to translate.
	 *
	 * @return string
	 */
	protected static function _($string)
	{
		// We add a prefix here

		// $string = 'prefix' . $string;

		// ... later

		// For now we just do a:

		return JText::_($string);
	}
}
