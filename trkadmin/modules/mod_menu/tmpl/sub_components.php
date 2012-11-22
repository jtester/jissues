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

// Get the authorised components and sub-menus.
$components = ModAdminMenuHelper::getComponents(true);

// Check if there are any components, otherwise, don't render the menu
if (!$components)
{
	return;
}

$menu->addChild(new JMenuNode(JText::_('MOD_MENU_COMPONENTS'), '#'), true);

foreach ($components as &$component)
{
	if (!empty($component->submenu))
	{
		// This component has a db driven submenu.
		$menu->addChild(new JMenuNode($component->text, $component->link, $component->img), true);

		foreach ($component->submenu as $sub)
		{
			$menu->addChild(new JMenuNode($sub->text, $sub->link, $sub->img));
		}

		$menu->getParent();

	}
	else
	{
		$menu->addChild(new JMenuNode($component->text, $component->link, $component->img));
	}
}

$menu->getParent();
