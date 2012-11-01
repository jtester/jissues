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
 * Text field for passwords
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.password.html#input.password
 * @note        Two password fields may be validated as matching using JFormRuleEquals
 * @since       11.1
 */
class JFormFieldPassword extends JFormFieldbase
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Password';

	/**
	 * Method to get the field input markup for password.
	 *
	 * @param bool $tooltip
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput($tooltip = false)
	{
		// Initialize some field attributes.
		$size      = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class     = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$auto      = ((string) $this->element['autocomplete'] == 'off') ? ' autocomplete="off"' : '';
		$readonly  = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled  = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$meter     = ((string) $this->element['strengthmeter'] == 'true');
		$threshold = $this->element['threshold'] ? (int) $this->element['threshold'] : 66;

		$script = '';

		if ($meter)
		{
			JHtml::_('script', 'system/passwordstrength.js', true, true);
			$script = '<script type="text/javascript">new Form.PasswordStrength("' . $this->id . '",
				{
					threshold: ' . $threshold . ',
					onUpdate: function(element, strength, threshold) {
						element.set("data-passwordstrength", strength);
					}
				}
			);</script>';
		}

		$input = '<input type="password" name="' . $this->name . '" id="' . $this->id . '"' .
			' value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' .
			$auto . $class . $readonly . $disabled . $size . $maxLength . '/>' . $script;

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
