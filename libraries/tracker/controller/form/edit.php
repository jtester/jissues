<?php
/**
 * @package     JTracker
 * @subpackage  com_tracker
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

/**
 * Tracker form save2copy controller class
 *
 * @package     JTracker
 * @subpackage  Controller
 * @since       1.0
 */
class JControllerFormEdit extends JControllerFormDefault
{
	/**
	 * Controller type.
	 * Used for class name building.
	 *
	 * @var string
	 */
	protected $type = 'Edit';

	/**
	 * Method to edit an existing record.
	 *
	 * @return  boolean  True if access level check and checkout passes, false otherwise.
	 *
	 * @since   12.2
	 */
	public function execute()
	{
		$app   = JFactory::getApplication();
		$model = $this->getModel();
		$table = $model->getTable();

		// Get the previous record id (if any) and the current record id.
		$checkin = property_exists($table, 'checked_out');

		// Access check.
		if (!$this->allowEdit())
		{
			$this->app->enqueueMessage(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'), 'error');

			$this->redirect = JRoute::_($this->listLink, false);

			return false;
		}

		// Attempt to check-out the new record for editing and redirect.
		if ($checkin && !$model->checkout($this->id))
		{
			$this->app->enqueueMessage(JText::_('JLIB_APPLICATION_ERROR_CHECKOUT_FAILED'), 'error');

			// Check-out failed, display a notice but allow the user to see the record.
			$this->redirect = JRoute::_($this->itemLink, false);

			return false;
		}

		// Check-out succeeded, push the new record id into the session.
		$this->holdEditId($this->context, $this->id);
		$app->setUserState($this->editContext . '.data', null);

		$this->redirect = JRoute::_($this->itemLink, false);

		return true;
	}
}
