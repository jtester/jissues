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
class ComAdminTrackerControllerCanceledit extends JControllerDefault
{
	/**
	 * Cancel editing - clean the state.
	 *
	 * @since   1.0
	 *
	 * @return  void.
	 */
	public function execute()
	{
		// Clean the state
		JFactory::getApplication()
			->setUserState('com_tracker.edit.project.data', null);

		parent::execute();
	}
}
