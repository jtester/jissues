<?php
/**
 * @package     JTracker
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

/**
 * Base HTML view class.
 *
 * @package     JTracker
 * @subpackage  Model
 * @since       1.0
 */
class JViewDefaultHtml extends JViewHtml
{
	/**
	 * Translator function.
	 *
	 * @param   string  $string  The string to translate.
	 *
	 * @return string
	 */
	protected function _($string)
	{
		// We add a prefix here

		// $string = 'prefix' . $string;

		// ... later

		// For now we just do a:

		return JText::_($string);
	}
}
