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
	 * Instantiate the controller.
	 *
	 * @param   JInput            $input  The input object.
	 * @param   JApplicationBase  $app    The application object.
	 *
	 * @throws Exception
	 * @since  9999
	 */
	public function __construct(JInput $input = null, JApplicationBase $app = null)
	{
		// Guess the context as the suffix, eg: OptionControllerSaveContent.
		if (!preg_match('/(.*)ControllerSave(.*)/i', get_class($this), $r))
		{
			throw new Exception(JText::_('JLIB_APPLICATION_ERROR_CONTROLLER_GET_NAME'), 500);
		}

		$this->context = strtolower($r[2]);

		parent::__construct($input, $app);
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @since   9999
	 * @throws Exception
	 * @return  object  The model.
	 */
	public function getModel()
	{
		static $model = null;

		if ($model)
		{
			return $model;
		}

		if (!preg_match('/(.*)ControllerSave/i', get_class($this), $r))
		{
			throw new Exception(JText::_('Unable to get the model name'), 500);
		}

		$ext = $r[1];

		$class = $ext . 'Model' . ucfirst($this->context);

		if (false == class_exists($class))
		{
			throw new Exception(sprintf(JText::_('Model class %s not found'), $class), 500);
		}

		$model = new $class;

		return $model;
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

		$app = JFactory::getApplication();

		$input = $app->input;
		$data  = $input->post->get('jform', array(), 'array');

		$model = $this->getModel();
		$table = $model->getTable();

		$checkin = property_exists($table, 'checked_out');
		$context = $this->option . '.edit.' . $this->context;

		$task = 'save';

		$id = $input->getInt('id');

		$itemLink = 'index.php?option=' . $this->option . '&view=' . $this->context;
		$itemLink .= ($id) ? '&id=' . $id : '';

		$listLink = 'index.php?option=' . $this->option . '&view=' . $this->listView;

		if (!$this->checkEditId($context, $id))
		{
			// Somehow the person just went to the form and tried to save it. We don't allow that.
			$app->enqueueMessage('Unheld ID', 'error');

			$this->redirect = JRoute::_($listLink, false);

			return false;
		}

		// The save2copy task needs to be handled slightly differently.
		if ($task == 'save2copy')
		{
			// Check-in the original row.
			if ($checkin && $model->checkin($id) === false)
			{
				// Check-in failed. Go back to the item and display a notice.
				$app->enqueueMessage('Checkin failed');

				$this->redirect = JRoute::_($itemLink, false);

				return false;
			}

			// Reset the ID and then treat the request as for Apply.
			$data['id'] = 0;
			$task       = 'apply';
		}

		// Access check.
		if (!$this->allowSave($data))
		{
			$app->enqueueMessage(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'), 'error');

			$this->redirect = JRoute::_($listLink, false);

			return false;
		}

		// Validate the posted data.
		// Sometimes the form needs some posted data, such as for plugins and modules.
		$form = $model->getForm($data, false);

		if (!$form)
		{
			$app->enqueueMessage('Can not get the form', 'error');

			return false;
		}

		// Test whether the data is valid.
		$validData = $model->validate($form, $data);

		// Check for validation errors.
		if ($validData === false)
		{
			// Save the data in the session.
			$app->setUserState($context . '.data', $data);

			// Redirect back to the edit screen.
			$this->redirect = JRoute::_($itemLink, false);

			return false;
		}

		// Attempt to save the data.
		if (!$model->save($validData))
		{
			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);

			// Redirect back to the edit screen.

			$app->enqueueMessage('Save failed', 'warning');

			$this->redirect = JRoute::_($itemLink, false);

			return false;
		}

		// Save succeeded, so check-in the record.
		if ($checkin && $model->checkin($id) === false)
		{
			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);

			// Check-in failed, so go back to the record and display a notice.
			$app->enqueueMessage('Checkin failed', 'error');

			$this->redirect = JRoute::_($itemLink, false);

			return false;
		}

		/*
		 $lang = JFactory::getLanguage();

		$this->setMessage(
			JText::_(
				($lang->hasKey($this->text_prefix . ($id == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS')
					? $this->text_prefix
					: 'JLIB_APPLICATION') . ($id == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
			)
		);
		*/

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Set the record data in the session.
				$id = $model->getState($this->context . '.id');
				$this->holdEditId($context, $id);
				$app->setUserState($context . '.data', null);
				$model->checkout($id);

				// Redirect back to the edit screen.
				$this->redirect = JRoute::_('index.php?option=' . $this->option . '&view=' . $this->context, false);
				break;

			case 'save2new':
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $id);
				$app->setUserState($context . '.data', null);

				// Redirect back to the edit screen.
				$this->redirect = JRoute::_('index.php?option=' . $this->option . '&view=' . $this->context, false);
				break;

			default:
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $id);
				$app->setUserState($context . '.data', null);

				// Redirect to the list screen.
				$this->redirect = JRoute::_('index.php?option=' . $this->option . '&view=' . $this->listView, false);
				break;
		}

		// Invoke the postSave method to allow for the child class to access the model.
		$this->postSaveHook($model, $validData);

		return true;
	}
}
