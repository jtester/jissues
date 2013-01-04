<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_login
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Login Model
 *
 * @package     Joomla.Administrator
 * @subpackage  com_login
 * @since       1.5
 */
class ComAdminLoginModelLogin extends JModelBase
{
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since    1.6
	 *
	 * @return JRegistry
	 */
	protected function loadState()
	{
		$post = JFactory::getApplication()->input->post;

		$state = new JRegistry;

		$credentials = array(
			'username' => $post->getString('username'),
			'password' => $post->getString('passwd')
		);

		$state->set('credentials', $credentials);

		// Check for return URL from the request first
		$return = $post->getBase64('return');

		if ($return)
		{
			$return = base64_decode($return);

			if (!JURI::isInternal($return))
			{
				$return = '';
			}
		}

		// Set the return URL if empty.
		if (empty($return))
		{
			$return = 'index.php';
		}

		$state->set('return', $return);

		return $state;
	}
}
