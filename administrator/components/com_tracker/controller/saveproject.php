<?php
/**
 * @package     JTracker
 * @subpackage  com_tracker
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * JTracker controller.
 *
 * @package     JTracker
 * @subpackage  com_tracker
 * @since       1.0
 */
class ComAdminTrackerControllerSaveproject extends JControllerTrackerform
{
	/**
	 * This will call the "save" method of the parent class.
	 *
	 * @param   string  $task  NOT USED. @todo legacy
	 *
	 * @since   1.0
	 * @throws  Exception
	 *
	 * @return  mixed   The value returned by the called method, false in error case.
	 */
	public function execute($task = '')
	{
		return parent::save();
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional. @todo legacy
	 * @param   string  $prefix  The class prefix. Optional. @todo legacy
	 * @param   array   $config  Configuration array for model. Optional. @todo legacy
	 *
	 * @return  object  The model.
	 *
	 * @since   1.0
	 */
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return new ComAdminTrackerModelProject;
	}

	/**
	 * Gets the URL arguments to append to a list redirect.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   1.0
	 */
	protected function getRedirectToListAppend()
	{
		return '';
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   1.0
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		return '';
	}
}
