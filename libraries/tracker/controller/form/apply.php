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
class JControllerFormApply extends JControllerFormSave
{
	protected $type = 'Apply';

	/**
	 * Set the redirect.
	 *
	 * @return JControllerFormSave
	 */
	protected function setRedirect()
	{
		$model = $this->getModel();

		// Set the record data in the session.
		// $id = $model->getState()->get($this->context . '.id');
		$this->holdEditId($this->editContext, $this->id);
		$this->app->setUserState($this->editContext . '.data', null);
		$model->checkout($this->id);

		// Redirect back to the edit screen.
		$this->redirect = JRoute::_($this->itemLink, false);

		return $this;
	}
}
