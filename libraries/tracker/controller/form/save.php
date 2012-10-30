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
 * Tracker form save controller class
 *
 * @package     JTracker
 * @subpackage  Controller
 * @since       1.0
 */
class JControllerFormSave extends JControllerFormDefault
{
	/**
	 * Controller type.
	 * Used for class name building.
	 *
	 * @var string
	 */
	protected $type = 'Save';

	/**
	 * Set up some internal variables and do the first pre check.
	 *
	 * @todo throw an exception and chain.
	 *
	 * @return bool|JControllerFormSave
	 */
	protected function precheck()
	{
		// Check the edit id
		if (0)//!$this->checkEditId($this->editContext, $this->id))
		{
			// Somehow the person just went to the form and tried to save it. We don't allow that.
			$this->app->enqueueMessage('Unheld ID', 'error');

			$this->redirect = JRoute::_($this->listLink, false);

			return false;
		}

		// Access check.
		if (!$this->allowSave($this->data))
		{
			$this->app->enqueueMessage(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'), 'error');

			$this->redirect = JRoute::_($this->listLink, false);

			return false;
		}

		return $this;
	}

	/**
	 * Execute the controller.
	 *
	 * @return  boolean  True if controller finished execution, false if the controller did not
	 *                   finish execution. A controller might return false if some precondition for
	 *                   the controller to run has not been satisfied.
	 *
	 * @since            9999
	 */
	public function execute()
	{
		// Check for request forgeries.
		JSession::checkToken() || jexit(JText::_('JINVALID_TOKEN'));

		$model = $this->getModel();

		if (!$this->precheck())
		{
			return false;
		}

		$validData = $this->saveItem();

		if (!$validData)
		{
			return false;
		}

		$this->setRedirect();

		// Invoke the postSave method to allow for the child class to access the model.
		$this->postSaveHook($model, $validData);

		return true;
	}

	/**
	 * Save the item.
	 *
	 * @return bool|array
	 */
	protected function saveItem()
	{
		$model = $this->getModel();

		$table = $model->getTable();

		$checkin = property_exists($table, 'checked_out');

		// Validate the posted data.
		// Sometimes the form needs some posted data, such as for plugins and modules.
		$form = $model->getForm($this->data, false);

		if (!$form)
		{
			$this->app->enqueueMessage('Can not get the form', 'error');

			return false;
		}

		// Test whether the data is valid.
		$validData = $model->validate($form, $this->data);

		// Check for validation errors.
		if ($validData === false)
		{
			// Save the data in the session.
			$this->app->setUserState($this->editContext . '.data', $this->data);

			// Redirect back to the edit screen.
			$this->redirect = JRoute::_($this->itemLink, false);

			return false;
		}

		// Attempt to save the data.
		if (!$model->save($validData))
		{
			// Save the data in the session.
			$this->app->setUserState($this->editContext . '.data', $validData);

			// Redirect back to the edit screen.

			$this->app->enqueueMessage('Save failed', 'warning');

			$this->redirect = JRoute::_($this->itemLink, false);

			return false;
		}

		// Save succeeded, so check-in the record.
		if ($checkin && $model->checkin($this->id) === false)
		{
			// Save the data in the session.
			$this->app->setUserState($this->editContext . '.data', $validData);

			// Check-in failed, so go back to the record and display a notice.
			$this->app->enqueueMessage('Checkin failed', 'error');

			$this->redirect = JRoute::_($this->itemLink, false);

			return false;
		}

		return $validData;
	}
}
