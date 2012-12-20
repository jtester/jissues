<?php
/**
 * @package     JTracker
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

/**
 * HTML Utility class for projects
 *
 * @package     JTracker
 * @subpackage  HTML
 * @since       1.0
 */
abstract class JHtmlProjects
{
	/**
	 * Get a select list.
	 *
	 * @param   string  $section   The section
	 * @param   string  $name      Name for the control
	 * @param   string  $selected  The selected field
	 * @param   string  $title     Title to show
	 * @param   string  $js        Javascript
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 */
	public static function select($section, $name, $selected = '', $title = '', $js = 'onchange="document.adminForm.submit();"')
	{
		$title = $title ? : JText::_('Select an Option');

		$options = JHtmlCategory::options($section);

		if (!$options)
		{
			return '';
		}

		$options = array_merge(array(JHtmlSelect::option('', $title)), $options);

		return JHtmlSelect::genericlist(
			$options,
			$name,
			$js,
			'value', 'text', // Hate it..
			$selected, 'select-' . $name
		);
	}

	/**
	 * Returns a HTML list of categories for the given extension.
	 *
	 * @param   string  $section   The extension option.
	 * @param   bool    $links     Links or simple list items.
	 * @param   string  $selected  The selected item.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public static function listing($section = '', $links = false, $selected = '')
	{
		$items = self::items($section);

		if (0 == count($items))
		{
			return '';
		}

		$html = array();

		$link = 'index.php?option=com_categories&extension=%s.%s';

		$html[] = '<ul class="unstyled">';

		foreach ($items as $item)
		{
			$selected    = ($selected == $item->id) ? ' selected' : '';
			$repeat      = ($item->level - 1 >= 0) ? $item->level - 1 : 0;
			$item->title = str_repeat('- ', $repeat) . $item->title;

			$html[] = '<li>';
			$html[] = $links
				? JHtml::link(sprintf($link, $section, $item->id), $item->title, array('class' => $selected))
				: $item->title;
			$html[] = '</li>';
		}

		$html[] = '</ul>';

		return implode("\n", $html);
	}

	/**
	 * Get the items list.
	 *
	 * @param   string  $section  A section
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public static function items($section)
	{
		static $sections = array();

		if (isset($sections[$section]))
		{
			return $sections[$section];
		}

		$db = JFactory::getDbo();

		$items = $db->setQuery(
			$db->getQuery(true)
				->select('id, title, alias, description, level, parent_id')
				->from('#__categories')
				->where('parent_id > 0')
				->where('extension = ' . $db->q($section))
				->order('lft')
		)->loadObjectList();

		$sections[$section] = $items;

		return $sections[$section];
	}

	public static function getName($id)
	{
		static $names = array();

		$id = (int) $id;

		if (isset($names[$id]))
		{
			return $names[$id];
		}

		$db = JFactory::getDbo();

		$item = $db->setQuery(
			$db->getQuery(true)
				->select($db->qn('title'))
				->from($db->qn('#__tracker_projects'))
				->where($db->qn('project_id') . ' = ' . $id)
		)->loadResult();

		$names[$id] = $item;

		return $names[$id];
	}

	/**
	 * Get a project selector.
	 *
	 * @param   string  $selected  The selected entry
	 * @param   string  $toolTip   The text for the tooltip.
	 *
	 * @return string
	 */
	public static function projectsSelect($selected = '', $toolTip = '')
	{
		$projects = self::projects();

		if (!$projects)
		{
			return '';
		}

		$options = array();
		$html    = array();

		$options[] = JHtmlSelect::option('', JText::_('Select a Project'));

		foreach ($projects as $project)
		{
			$options[] = JHtmlSelect::option($project->id, $project->title);
		}

		$js = 'onchange="document.adminForm.submit();"';

		$input = JHtmlSelect::genericlist($options, 'project', $js, 'value', 'text', $selected, 'select-project');

		if ($toolTip)
		{
			$html[] = '<div class="input-append">';
			$html[] = $input;
			$html[] = '<span class="add-on hasTooltip" data-placement="right" style="cursor: help;" title="'
				. htmlspecialchars($toolTip, ENT_COMPAT, 'UTF-8') . '">?</span>';
			$html[] = '</div>';
		}

		return implode("\n", $html);
	}

	/**
	 * Returns a HTML list of categories for the given extension.
	 *
	 * @param   bool    $links     Links or simple list items.
	 * @param   string  $selected  The selected item.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public static function projectsListing($links = false, $selected = '')
	{
		$items = self::projects();

		if (0 == count($items))
		{
			return '';
		}

		$html = array();

		$link = 'index.php?option=com_tracker&view=project&id=%d';

		$html[] = '<ul class="unstyled">';

		foreach ($items as $item)
		{
			$selected = ($selected == $item->id) ? ' selected' : '';

			$html[] = '<li>';
			$html[] = $links
				? JHtml::link(sprintf($link, $item->id), $item->title, array('class' => $selected))
				: $item->title;
			$html[] = '</li>';
		}

		$html[] = '</ul>';

		return implode("\n", $html);
	}

	/**
	 * Get the defined projects.
	 *
	 * @return array
	 */
	public static function projects()
	{
		static $projects = null;

		if (is_array($projects))
		{
			return $projects;
		}

		$model = new ComAdminTrackerModelProjects;

		$projects = $model->getItems();

		return $projects;
	}

	/**
	 * Draws a text input.
	 *
	 * @param   string  $name         Control name.
	 * @param   string  $value        The initial value.
	 * @param   string  $description  Description to be displayed in a tooltip.
	 *
	 * @todo moveme
	 *
	 * @return string
	 */
	public static function textfield($name, $value, $description = '')
	{
		$description = ($description) ? ' class="hasTooltip" title="' . htmlspecialchars($description, ENT_COMPAT, 'UTF-8') . '"' : '';

		return '<input type="text" name="fields[' . $name . ']" '
			. ' id="txt-' . $name . '" value="' . $value . '"' . $description . ' />';
	}

	/**
	 * Draws a checkbox
	 *
	 * @param   string   $name         Control name.
	 * @param   boolean  $checked      Initial state.
	 * @param   string   $description  Description to be displayed in a tooltip.
	 *
	 * @todo     moveme
	 *
	 * @return string
	 */
	public static function checkbox($name, $checked = false, $description = '')
	{
		$description = ($description) ? ' class="hasTooltip" title="' . htmlspecialchars($description, ENT_COMPAT, 'UTF-8') . '"' : '';
		$checked     = $checked ? ' checked="checked"' : '';

		return '<input type="checkbox" name="fields[' . $name . ']" '
			. ' id="chk-' . $name . '"' . $checked . $description . ' />';
	}
}
