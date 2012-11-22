<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/* @var $menu JAdminCSSMenu */

$shownew  = (boolean) $params->get('shownew', 1);
$showhelp = $params->get('showhelp', 0);
$user     = JFactory::getUser();
$lang     = JFactory::getLanguage();

//
// Site SubMenu
//
require JModuleHelper::getLayoutPath('mod_menu', 'sub_system');

//
// Users Submenu
//
require JModuleHelper::getLayoutPath('mod_menu', 'sub_users');

//
// Menus Submenu
//
require JModuleHelper::getLayoutPath('mod_menu', 'sub_menus');

//
// Content Submenu
//
require JModuleHelper::getLayoutPath('mod_menu', 'sub_content');

//
// Components Submenu
//
require JModuleHelper::getLayoutPath('mod_menu', 'sub_components');

//
// Extensions Submenu
//
require JModuleHelper::getLayoutPath('mod_menu', 'sub_extensions');

//
// Help Submenu
//
require JModuleHelper::getLayoutPath('mod_menu', 'sub_help');

