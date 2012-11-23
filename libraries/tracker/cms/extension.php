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
 * Base class for extensions.
 *
 * @package     JTracker
 * @subpackage  CMS
 * @since       1.0
 */
abstract class JCmsExtension
{
	/**
	 * e.g. com_foo, mod_foo
	 *
	 * @var string
	 */
	protected $name = '';

	protected $extension = null;

	/**
	 * Constructor.
	 *
	 * @param   string  $name  Extension name.
	 */
	public function __construct($name)
	{
		$this->name = $name;

		$this->load();
	}

	/**
	 * Render the extension.
	 *
	 * @return string
	 */
	abstract public function render();

	/**
	 * Load the extension.
	 *
	 * @return JCmsExtension
	 */
	abstract protected function load();
}
