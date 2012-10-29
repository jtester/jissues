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
class ComAdminTrackerControllerDeleteproject extends JControllerDefault
{
	/**
	 * Delete a record.
	 *
	 * @since   1.0
	 *
	 * @return  void.
	 */
	public function execute()
	{
		$model = new ComAdminTrackerModelProject;

		$model->delete(JFactory::getApplication()->input->getInt('id'));

		$app = JFactory::getApplication();
		$app->enqueueMessage($this->_('The item has been deleted'));
		$app->redirect('index.php?option=com_tracker&view=projects');
	}
}
