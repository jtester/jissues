<?php
/**
 * @package     JTracker
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

/**
 * Tracker form save2copy controller class
 *
 * @package     JTracker
 * @subpackage  Controller
 * @since       1.0
 */
class JControllerFormSave2new extends JControllerFormSave
{
	/**
	 * Controller type.
	 * Used for class name building.
	 *
	 * @var string
	 */
	protected $type = 'Save2new';

	/**
	 * Set the redirect.
	 *
	 * @return JControllerFormSave
	 */
	protected function setRedirect()
	{
		// Clear the record id and data from the session.
		$this->releaseEditId($this->editContext, $this->id);
		$this->app->setUserState($this->editContext . '.data', null);

		// Redirect back to the edit screen.
		$this->redirect = JRoute::_('index.php?option=' . $this->option . '&task=' . $this->context, false);

		return $this;
	}
}
