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
 * HTML Utility class for languages.
 *
 * @package     JTracker
 * @subpackage  HTML
 * @since       1.0
 */
class JHtmlLanguage
{
	/**
	 * Display a language selector.
	 *
	 * @param   array  $attribs  Attributes.
	 *
	 * @return string
	 */
	public static function selector($attribs = array())
	{
		$items = array();

		$attribs = array_merge(
			$attribs,
			array(
				'name'        => 'lang',
				'list.attr'   => 'class="span2" onchange="document.getElementById(\'frm-lang\').submit();"',
				'list.select' => JFactory::getLanguage()->getTag(),
				'id'          => 'select-lang',
				'frm.id'      => 'frm-lang'
			)
		);

		foreach (JLanguage::getKnownLanguages(JPATH_ADMINISTRATOR) as $lang)
		{
			$items[] = JHtml::_('select.option', $lang['tag'], $lang['tag']); // $lang['name']);
		}

		return JHtmlSelect::genericlist(
			$items,
			$attribs['name'],
			$attribs
		);
	}
}
