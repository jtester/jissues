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
	 * Method to instantiate the view.
	 *
	 * @param   JModel            $model  The model object.
	 * @param   SplPriorityQueue  $paths  The paths queue.
	 *
	 * @throws RuntimeException
	 * @since   12.1
	 */
	public function __construct(JModel $model, SplPriorityQueue $paths = null)
	{
		parent::__construct($model, $paths);

		// Guess the context as the suffix, eg: (Com[Admin])<Option>ModelSave.
		if (!preg_match('/(Com[Admin]*)*(.*)View(.*)Html*/i', get_class($this), $r))
		{
			throw new RuntimeException(
				sprintf('%s - Cannot get or parse class name %s.',
					__METHOD__, get_class($this)
				),
				500
			);
		}

		$this->name = strtolower($r[3]);

		// Setup dependencies.
		$this->paths = isset($paths) ? $paths : $this->loadPaths();
	}

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
