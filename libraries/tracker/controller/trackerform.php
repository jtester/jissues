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
 * Tracker form controller class
 *
 * @package     JTracker
 * @subpackage  Controller
 * @since       1.0
 */
class JControllerTrackerform extends JControllerForm
{
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   9999
	 */
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		if (empty($name))
		{
			$name = $this->context;
		}

		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   9999
	 */
	public function save($key = null, $urlVar = null)
	{
		if (!parent::save($key, $urlVar))
		{
			// Sort of Legacy handling...
			return false;
		}

		$option = $this->input->get('option');

		$view = $this->view_list;

		if (0 === strpos($view, 'save'))
		{
			$view = substr($view, 4);
		}

		$this->setRedirect(
			JRoute::_(
				'index.php?option=' . $option . '&view=' . $view
					. $this->getRedirectToListAppend(), false
			)
		);

		return $this;
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 *
	 * @param   JModel  $model      The data model object.
	 * @param   array   $validData  The validated data.
	 *
	 * @todo    since the type hinting differs from that in the base class.
	 *          THIS WILL GENERATE A PHP STRICT warning..
	 *
	 * @return  void
	 *
	 * @since   9999
	 */
	protected
	function postSaveHook(JModel $model, $validData = array())
	{
	}
}
