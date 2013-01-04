<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

$langs	= ModAdminLoginHelper::getLanguageList();
$return	= ModAdminLoginHelper::getReturnURI();

require JCmsExtensionHelperModule::getLayoutPath('mod_login', $params->get('layout', 'default'));
