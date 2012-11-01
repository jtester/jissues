<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Formfield base class.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.email.html#input.email
 * @see         JFormRuleEmail
 * @since       11.1
 */
abstract class JFormFieldbase extends JFormField
{
	/**
	 * Method to get the field input markup.
	 *
	 * @param   boolean  $tooltip  Display a tooltip
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function xgetInput($tooltip = false)
	{
	}

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   11.1
	 */
	public function __get($name)
	{
		if ('inputTooltip' == $name)
		{
			return $this->getInput(true);
		}

		return parent::__get($name);
	}

	/**
	 * Method to get the field tooltip text.
	 *
	 * @param   string  $class  The CSS class.
	 *
	 * @return  string  The tooltip text.
	 *
	 * @since   11.1
	 */
	protected function getTooltip($class = 'hasTooltip')
	{
		return $this->description ? '<span class="' . $class . '">' . $this->description . '</span>' : '';
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   11.1
	 */
	protected function getLabel()
	{
		$label = '';
		$class = 'hasTooltip';

		if ($this->hidden)
		{
			return $label;
		}

		// Get the label text from the XML element, defaulting to the element name.
		$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
		$text = $this->translateLabel ? JText::_($text) : $text;

		// Build the class for the label.
		$class .= $this->required == true ? ' required' : '';
		$class .= !empty($this->labelClass) ? ' ' . $this->labelClass : '';

		// Add the opening label tag and main attributes attributes.
		$label .= '<label id="' . $this->id . '-lbl" for="' . $this->id . '" class="' . $class . '"';

		// If a description is specified, use it to build a tooltip.
		if (0)//!empty($this->description))
		{
			$label .= ' title="'
				. htmlspecialchars(
				// @todo-reference: trim($text, ':') . '::' . ($this->translateDescription ? JText::_($this->description) : $this->description),
					($this->translateDescription ? JText::_($this->description) : $this->description),
					ENT_COMPAT, 'UTF-8'
				) . '"';
		}

		// Add the label text and closing tag.
		if ($this->required)
		{
			$label .= '>' . $text . '<span class="star">&#160;*</span></label>';
		}
		else
		{
			$label .= '>' . $text . '</label>';
		}

		return $label;
	}
}
