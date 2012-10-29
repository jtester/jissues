<?php
/**
 * @package     JTracker
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

/**
 * This is a universal CMS extension loader.
 *
 * It expects the classes to be named like this:
 *
 * ComFooModelBar
 *   JROOT/components/com_foo/model/bar.php
 *
 * ComAdminFooModelBar
 *   JROOT/administrator/components/com_foo/model/bar.php
 *
 * @package     JTracker
 * @subpackage  CMS
 * @since       1.0
 */
abstract class JCmsLoader
{
	/**
	 * Load a class.
	 *
	 * @param   string  $class  The class name.
	 *
	 * @return void
	 */
	public static function load($class)
	{
		// Split the class name into parts separated by camelCase.
		$parts = preg_split('/(?<=[a-z0-9])(?=[A-Z])/x', $class);

		$knownExtensions = array(
			'Com' => 'components',
			'Mod' => 'modules',
			'Plg' => 'plugins',
			'Tpl' => 'templates'
		);

		if (count($parts) < 3)
		{
			// Too short..
			return;
		}

		if (false == array_key_exists($parts[0], $knownExtensions))
		{
			// Not our business.
			return;
		}

		if ('Admin' == $parts[1])
		{
			$base      = JPATH_ADMINISTRATOR;
			$extension = $parts[2];
			unset($parts[1]);
		}
		else
		{
			$base      = JPATH_SITE;
			$extension = $parts[1];
		}

		$base .= '/' . $knownExtensions[$parts[0]] . '/' . strtolower($parts[0]) . '_' . strtolower($extension);

		array_shift($parts);
		array_shift($parts);

		// If there is only one part we want to duplicate that part for generating the path.
		$parts = (count($parts) === 1) ? array($parts[0], $parts[0]) : $parts;

		// Generate the path based on the class name parts.
		$path = $base . '/' . implode('/', array_map('strtolower', $parts)) . '.php';

		// Load the file if it exists.
		if (file_exists($path))
		{
			include $path;
		}
	}
}
