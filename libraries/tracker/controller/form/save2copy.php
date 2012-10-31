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
class JControllerFormSave2copy extends JControllerFormSave
{
	/**
	 * Controller type.
	 * Used for class name building.
	 *
	 * @var string
	 */
	protected $type = 'Save2copy';

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

		$model   = $this->getModel();
		$checkin = property_exists($model->getTable(), 'checked_out');
		$id      = $this->input->getInt('id');

		// Check-in the original row.
		if ($checkin && $model->checkin($id) === false)
		{
			// Check-in failed. Go back to the item and display a notice.
			$this->app->enqueueMessage('Checkin failed');

			$this->redirect = JRoute::_($this->itemLink, false);

			return false;
		}

		// Reset the ID and then treat the request as for Apply.
		$this->data['id'] = 0;

		$validData = $this->saveItem();

		if (!$validData)
		{
			return false;
		}

		$this->setRedirect();

		return $this;
	}

	/**
	 * Set the redirect.
	 *
	 * @return JControllerFormSave
	 */
	protected function setRedirect()
	{
		$model = $this->getModel();

		// Set the record data in the session.
		// $model->getState()->get($this->context . '.id');
		$id = 0;
		$this->holdEditId($this->editContext, $id);
		$this->app->setUserState($this->editContext . '.data', $this->data);

		// $model->checkout($id);

		// Redirect back to the edit screen.
		$this->redirect = JRoute::_('index.php?option=' . $this->option . '&view=' . $this->context, false);

		return $this;
	}
}
