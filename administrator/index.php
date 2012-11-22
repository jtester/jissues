<?php
/**
 * @package    Joomla.Administrator
 *
 * @copyright  Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// Set error reporting for development
error_reporting(-1);

if (version_compare(PHP_VERSION, '5.3.1', '<'))
{
	die('Your host needs to use PHP 5.3.1 or higher to run this version of Joomla!');
}

/**
 * Constant that is checked in included files to prevent direct access.
 */
const _JEXEC = 1;

// Bootstrap the application
require_once __DIR__ . '/includes/bootstrap.php';

// Execute the application
JApplicationWeb::getInstance('JApplicationAdmin')
	->execute();
