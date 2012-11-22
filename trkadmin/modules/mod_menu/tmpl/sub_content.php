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

// DISABLED !
return;

if (!$user->authorise('core.manage', 'com_content'))
{
	return;
}

$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_COM_CONTENT'), '#'), true
);

$createContent = $shownew && $user->authorise('core.create', 'com_content');
$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_ARTICLE_MANAGER'), 'index.php?option=com_content', 'class:article'), $createContent
);

if ($createContent)
{
	$menu->addChild(
		new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_NEW_ARTICLE'), 'index.php?option=com_content&task=article.add', 'class:newarticle')
	);
	$menu->getParent();
}

$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_CATEGORY_MANAGER'), 'index.php?option=com_categories&extension=com_content', 'class:category'), $createContent
);

if ($createContent)
{
	$menu->addChild(
		new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_NEW_CATEGORY'), 'index.php?option=com_categories&task=category.add&extension=com_content', 'class:newarticle')
	);
	$menu->getParent();
}

$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_FEATURED'), 'index.php?option=com_content&view=featured', 'class:featured')
);

$menu->addSeparator();

if ($user->authorise('core.manage', 'com_media'))
{
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_MEDIA_MANAGER'), 'index.php?option=com_media', 'class:media'));
}

$menu->getParent();
