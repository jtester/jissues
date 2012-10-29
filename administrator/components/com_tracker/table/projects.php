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
 * JTracker table.
 *
 * @package     JTracker
 * @subpackage  com_tracker
 * @since       1.0
 */
class ComAdminTrackerTableProjects extends JTableTrackertable
{
	/**
	 * Get a table.
	 *
	 * @return ComAdminTrackerTableProjects
	 */
	public static function getTable()
	{
		return new ComAdminTrackerTableProjects('#__tracker_projects', 'project_id', JFactory::getDbo());
	}
}
