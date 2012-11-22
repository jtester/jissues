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

$menu->addChild(new JMenuNode(JText::_('MOD_MENU_SYSTEM'), '#'), true);

$menu->addChild(new JMenuNode(JText::_('MOD_MENU_CONTROL_PANEL'), 'index.php', 'class:cpanel'));

if ($user->authorise('core.admin'))
{
	$menu->addSeparator();

	$menu->addChild(
		new JMenuNode(
			JText::_('MOD_MENU_CONFIGURATION'),
			'index.php?option=com_config',
			'class:config'
		)
	);
}

//$chm = $user->authorise('core.admin', 'com_checkin');
//$cam = $user->authorise('core.manage', 'com_cache');

if (0) //$chm || $cam )
{
	// Keep this for when bootstrap supports submenus?
	/* $menu->addChild(
		new JMenuNode(JText::_('MOD_MENU_MAINTENANCE'), 'index.php?option=com_checkin', 'class:maintenance'), true
	);*/

	if ($chm)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_GLOBAL_CHECKIN'), 'index.php?option=com_checkin', 'class:checkin'));
		$menu->addSeparator();
	}
	if ($cam)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_CLEAR_CACHE'), 'index.php?option=com_cache', 'class:clear'));
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_PURGE_EXPIRED_CACHE'), 'index.php?option=com_cache&view=purge', 'class:purge'));
	}

	//$menu->getParent();
}

//$menu->addSeparator();

if (0)//$user->authorise('core.admin'))
{
	$menu->addChild(
		new JMenuNode(JText::_('MOD_MENU_SYSTEM_INFORMATION'), 'index.php?option=com_admin&view=sysinfo', 'class:info')
	);
}

$menu->getParent();
