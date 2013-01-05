<?php
/**
 * @package     JTracker
 * @subpackage  com_tracker
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

/**
 * JTracker helper class.
 *
 * @package     JTracker
 * @subpackage  com_tracker
 * @since       1.0
 */
abstract class ComAdminTrackerHelper extends JCmsExtensionHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @throws RuntimeException
	 * @return  void
	 *
	 * @since   1.6
	 */
	public static function addSubmenu()
	{
		$input = JFactory::getApplication()->input;

		$task = $input->get('task');

		$project   = $input->getInt('project');
		$extension = $input->getString('extension');
		$option = $input->get('option');

		if ('com_categories' == $option)
		{
			// Set the Toolbar title in com_categories
			self::setCategoriesTitle($extension);
		}

		JHtmlSidebar::addEntry(
			self::_('JTracker'),
			'index.php?option=com_tracker',
			'com_tracker' == $option && '' == $task
		);

		$baseLink = 'index.php?option=com_categories&extension=com_tracker';

		JHtmlSidebar::addEntry(self::_('Projects'), 'index.php?option=com_tracker&task=projects', $option == 'com_tracker' && $task == 'projects');
		JHtmlSidebar::addEntry(self::_('Categories'), $baseLink . '.categories', preg_match('/com_tracker.categories[.0-9]*/', $extension));
		JHtmlSidebar::addEntry(self::_('Textfields'), $baseLink . '.textfields', $extension == 'com_tracker.textfields');
		JHtmlSidebar::addEntry(self::_('Selectlists'), $baseLink . '.fields', $extension == 'com_tracker.fields');
		JHtmlSidebar::addEntry(self::_('Checkboxes'), $baseLink . '.checkboxes', $extension == 'com_tracker.checkboxes');

		/*
		 * Select fields
		 */

		preg_match('/com_tracker.fields.([0-9]+)/', $extension, $matches);

		if (isset($matches[1]))
		{
			$item = new JTableCategory(JFactory::getDbo());
			$item->load((int) $matches[1]);

			JHtmlSidebar::addEntry(
				sprintf(self::_('Selectlists %s'), $item->title),
				'index.php?option=com_categories&extension=com_tracker.fields.' . $matches[1],
				true
			);
		}

		/*
		 * Global fields
		 */
		if ($project || ($extension && $extension !== 'com_tracker'))
		{
			$p = $project;

			if (!$p)
			{
				preg_match('/com_tracker.([0-9]+)./', $extension, $matches);

				if (isset($matches[1]))
				{
					$p = $matches[1];
				}
			}

			if (!$p)
			{
				return;
			}

			if (false == class_exists('JTableCategory'))
			{
				// @legacy
				JLoader::register('JTableCategory', JPATH_LIBRARIES . '/legacy/table/category.php');
			}

			$item = new JTableCategory(JFactory::getDbo());
			$item->load($p);

			JHtmlSidebar::addEntry(
				sprintf(self::_('Project %s'), JHtmlProjects::getName($p)),
				'index.php?option=com_tracker&project=' . $p,
				(boolean) $project
			);

			JHtmlSidebar::addEntry(
				sprintf(self::_('%s Categories'), JHtmlProjects::getName($p)),
				sprintf($baseLink . '.%s.%s', $p, 'categories'),
				preg_match('/com_tracker.[0-9]+.categories/', $extension)
			);

			JHtmlSidebar::addEntry(
				sprintf(self::_('%s Textfields'), JHtmlProjects::getName($p)),
				sprintf($baseLink . '.%s.%s', $p, 'textfields'),
				preg_match('/com_tracker.[0-9]+.textfields/', $extension)
			);

			JHtmlSidebar::addEntry(
				sprintf(self::_('%s Selectlists'), JHtmlProjects::getName($p)),
				sprintf($baseLink . '.%s.%s', $p, 'fields'),
				preg_match('/com_tracker.[0-9]+.fields/', $extension)
			);

			JHtmlSidebar::addEntry(
				sprintf(self::_('%s Checkboxes'), JHtmlProjects::getName($p)),
				sprintf($baseLink . '.%s.%s', $p, 'checkboxes'),
				preg_match('/com_tracker.[0-9]+.checkboxes/', $extension)
			);
		}
	}

	/**
	 * Set the Toolbar title in com_categories.
	 *
	 * @param   string  $extension  The extension section.
	 *
	 * @return void
	 */
	private static function setCategoriesTitle($extension)
	{
		$parts = explode('.', $extension);

		$section    = '';
		$subSection = '';

		if (2 == count($parts))
		{
			$section = ucfirst($parts[1]);
		}

		if (3 == count($parts))
		{
			$item = new JTableCategory(JFactory::getDbo());
			$item->load((int) $parts[2]);

			$section    = $item->title;
			$subSection = ucfirst($parts[1]);
		}

		JToolbarHelper::title(sprintf(self::_('JTracker: %2$s %1$s'), $section, $subSection));
	}
}
