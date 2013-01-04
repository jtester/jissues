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
 * Login login controller.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_login
 * @since       1.5
 */
class ComAdminLoginControllerLogin extends JControllerBase
{
	/**
	 * Execute the controller.
	 *
	 * @return  boolean  True if controller finished execution, false if the controller did not
	 *                   finish execution. A controller might return false if some precondition for
	 *                   the controller to run has not been satisfied.
	 *
	 * @since   4.0
	 * @throws  LogicException
	 * @throws  RuntimeException
	 */
	public function execute()
	{
		// Check for request forgeries.
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();

		$model       = new ComAdminLoginModelLogin;

		$state = $model->getState();

		$credentials = $state->get('credentials');
		$return      = $state->get('return');

		$result = $app->login($credentials, array('action' => 'core.login.admin'));

		if (!($result instanceof Exception))
		{
			$app->redirect($return);
		}
	}
}
