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

if (!$user->authorise('core.manage', 'com_users'))
{
	return;
}

$menu->addChild(
	new JMenuNode(
		JText::_('MOD_MENU_COM_USERS_USERS'),
		'#'
	),
	true
);

$createUser = $shownew && $user->authorise('core.create', 'com_users');
$createGrp  = $user->authorise('core.admin', 'com_users');

$menu->addChild(
	new JMenuNode(
		JText::_('MOD_MENU_COM_USERS_USER_MANAGER'),
		'index.php?option=com_users&view=users',
		'class:user'
	),
	$createUser
);

if ($createUser)
{
	$menu->addChild(
		new JMenuNode(
			JText::_('MOD_MENU_COM_USERS_ADD_USER'),
			'index.php?option=com_users&task=user.add',
			'class:newarticle'
		)
	);

	$menu->getParent();
}

if ($createGrp)
{
	$menu->addChild(
		new JMenuNode(
			JText::_('MOD_MENU_COM_USERS_GROUPS'),
			'index.php?option=com_users&view=groups',
			'class:groups'
		),
		$createUser
	);

	if ($createUser)
	{
		$menu->addChild(
			new JMenuNode(
				JText::_('MOD_MENU_COM_USERS_ADD_GROUP'),
				'index.php?option=com_users&task=group.add',
				'class:newarticle'
			)
		);

		$menu->getParent();
	}

	$menu->addChild(
		new JMenuNode(
			JText::_('MOD_MENU_COM_USERS_LEVELS'),
			'index.php?option=com_users&view=levels',
			'class:levels'
		),
		$createUser
	);

	if ($createUser)
	{
		$menu->addChild(
			new JMenuNode(
				JText::_('MOD_MENU_COM_USERS_ADD_LEVEL'),
				'index.php?option=com_users&task=level.add',
				'class:newarticle'
			)
		);

		$menu->getParent();
	}
}

$menu->addSeparator();
$menu->addChild(
	new JMenuNode(
		JText::_('MOD_MENU_COM_USERS_NOTES'),
		'index.php?option=com_users&view=notes',
		'class:user-note'
	),
	$createUser
);

if ($createUser)
{
	$menu->addChild(
		new JMenuNode(
			JText::_('MOD_MENU_COM_USERS_ADD_NOTE'),
			'index.php?option=com_users&task=note.add',
			'class:newarticle'
		)
	);

	$menu->getParent();
}

$menu->addChild(
	new JMenuNode(
		JText::_('MOD_MENU_COM_USERS_NOTE_CATEGORIES'),
		'index.php?option=com_categories&view=categories&extension=com_users',
		'class:category'
	),
	$createUser
);

if ($createUser)
{
	$menu->addChild(
		new JMenuNode(
			JText::_('MOD_MENU_COM_CONTENT_NEW_CATEGORY'),
			'index.php?option=com_categories&task=category.add&extension=com_users.notes',
			'class:newarticle'
		)
	);

	$menu->getParent();
}

$menu->addSeparator();
$menu->addChild(
	new JMenuNode(
		JText::_('MOD_MENU_MASS_MAIL_USERS'),
		'index.php?option=com_users&view=mail',
		'class:massmail'
	)
);

$menu->getParent();
