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

if (0) //$user->authorise('core.manage', 'com_menus'))
{
	$menu->addChild(
		new JMenuNode(JText::_('MOD_MENU_MENUS'), '#'), true
	);

	$createMenu = $shownew && $user->authorise('core.create', 'com_menus');

	$menu->addChild(
		new JMenuNode(JText::_('MOD_MENU_MENU_MANAGER'), 'index.php?option=com_menus&view=menus', 'class:menumgr'), $createMenu
	);

	if ($createMenu)
	{
		$menu->addChild(
			new JMenuNode(JText::_('MOD_MENU_MENU_MANAGER_NEW_MENU'), 'index.php?option=com_menus&view=menu&layout=edit', 'class:newarticle')
		);

		$menu->getParent();
	}

	$menu->addSeparator();

	// Menu Types
	foreach (ModAdminMenuHelper::getMenus() as $menuType)
	{
		$alt = '*' . $menuType->sef . '*';

		if ($menuType->home == 0)
		{
			$titleicon = '';
		}
		elseif ($menuType->home == 1 && $menuType->language == '*')
		{
			$titleicon = ' <i class="icon-home"></i>';
		}
		elseif ($menuType->home > 1)
		{
			$titleicon = ' <span>' . JHtml::_('image', 'mod_languages/icon-16-language.png', $menuType->home, array('title' => JText::_('MOD_MENU_HOME_MULTIPLE')), true) . '</span>';
		}
		else
		{
			$image = JHtml::_('image', 'mod_languages/' . $menuType->image . '.gif', null, null, true, true);

			if (!$image)
			{
				$titleicon = ' <span>' . JHtml::_('image', 'mod_languages/icon-16-language.png', $alt, array('title' => $menuType->title_native), true) . '</span>';
			}
			else
			{
				$titleicon = ' <span>' . JHtml::_('image', 'mod_languages/' . $menuType->image . '.gif', $alt, array('title' => $menuType->title_native), true) . '</span>';
			}
		}

		$menu->addChild(
			new JMenuNode($menuType->title, 'index.php?option=com_menus&view=items&menutype=' . $menuType->menutype, 'class:menu', null, null, $titleicon), $createMenu
		);

		if ($createMenu)
		{
			$menu->addChild(
				new JMenuNode(JText::_('MOD_MENU_MENU_MANAGER_NEW_MENU_ITEM'), 'index.php?option=com_menus&view=item&layout=edit&menutype=' . $menuType->menutype, 'class:newarticle')
			);
			$menu->getParent();
		}
	}

	$menu->getParent();
}
