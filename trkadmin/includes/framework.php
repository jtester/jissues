<?php
/**
 * @package    Joomla.Administrator
 *
 * @copyright  Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Import the application libraries, load it before the rest of the Platform to allow class overrides
require_once JPATH_LIBRARIES . '/tracker.php';

// System includes.
require_once JPATH_LIBRARIES.'/import.legacy.php';

// Botstrap the CMS libraries.
require_once JPATH_LIBRARIES.'/cms.php';

// Register The universal CMS extension autoloader.
spl_autoload_register(array('JCmsLoader', 'load'));
