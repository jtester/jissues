<?php
/**
 * @package     JTracker
 * @subpackage  com_tracker
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JToolbarHelper::title('JTracker Administration');

ComAdminTrackerHelper::addSubmenu();

JHTML::_('addIncludePath', JPATH_LIBRARIES . '/tracker/html');

// "load" a class with the _() function so we can call it directly later.
JHtml::_('projects.select', 'x', 'y');
