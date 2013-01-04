<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for the Joomla Platform.
 * Supports a one line text field.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.text.html#input.text
 * @since       11.1
 */
class JFormFieldText extends JFormFieldbase
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'Text';

	/**
	 * Method to get the field input markup.
	 *
	 * @param   boolean  $tooltip  The tooltip.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput($tooltip = false)
	{
		$html = array();

		// Initialize some field attributes.
		$size      = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class     = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$readonly  = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled  = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		$input = '<input type="text" name="' . $this->name . '" id="' . $this->id . '"'
			. ' value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"'
			. $class . $size . $disabled . $readonly . $onchange . $maxLength . '/>';

		$description = (string) $this->element['description'];

		if ($description)
		{
			$html[] = '<div class="input-append">';
			$html[] = $input;
			$html[] = '<span class="add-on hasTooltip" style="cursor: help;" data-placement="left" title="'
				. htmlspecialchars(JText::_($description), ENT_COMPAT, 'UTF-8') . '">?</span>';
			$html[] = '</div>';
		}
		else
		{
			$html[] = $input;
		}

		return implode("\n", $html);
	}
}
