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
 * JTracker controller.
 *
 * @package     JTracker
 * @subpackage  com_tracker
 * @since       1.0
 */
class ComAdminTrackerControllerProjectSave extends JControllerFormSave
{
	/**
	 * Execute the controller.
	 *
	 * @return  boolean  True if controller finished execution, false if the controller did not
	 *                   finish execution. A controller might return false if some precondition for
	 *                   the controller to run has not been satisfied.
	 *
	 * @since            1.0
	 */
	public function execute()
	{
		if (parent::execute())
		{
			JFactory::getApplication()->enqueueMessage($this->_('The item has been saved.'));
		}
	}
}
