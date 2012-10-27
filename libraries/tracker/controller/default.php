<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Default controller.
 *
 * @package     Joomla.Platform
 * @subpackage  Controller
 * @since       9999
 */
class JControllerDefault extends JControllerBase
{
	protected $scope = '';

	protected $redirect = '';

	/**
	 * Instantiate the controller.
	 *
	 * @param   JInput            $input  The input object.
	 * @param   JApplicationBase  $app    The application object.
	 *
	 * @since  12.1
	 */
	public function __construct(JInput $input = null, JApplicationBase $app = null)
	{
		parent::__construct($input, $app);

		$this->scope = substr($this->app->scope, 4);
	}

	/**
	 * Execute the controller.
	 *
	 * @return  boolean  True if controller finished execution, false if the controller did not
	 *                   finish execution. A controller might return false if some precondition for
	 *                   the controller to run has not been satisfied.
	 *
	 * @since            9999
	 * @throws  LogicException
	 * @throws  RuntimeException
	 */
	public function execute()
	{
		// Get the application
		$app = $this->getApplication();

		$scopePrefix = $app->isAdmin() ? 'Admin' : '';
		$viewName    = $app->input->getWord('view', 'default');
		$viewFormat  = JFactory::getDocument()->getType();
		$layoutName  = $app->input->getWord('layout', 'default');

		$app->input->set('view', $viewName);

		// Register the layout paths for the view
		$paths = new SplPriorityQueue;
		$paths->insert(JPATH_COMPONENT . '/view/' . $viewName . '/tmpl', 'normal');

		$viewClass  = 'Com' . $scopePrefix . ucfirst($this->scope) . 'View' . ucfirst($viewName) . ucfirst($viewFormat);
		$modelClass = 'Com' . $scopePrefix . ucfirst($this->scope) . 'Model' . ucfirst($viewName);

		if (false == class_exists($modelClass))
		{
			$modelClass = 'JModelDefault';
		}

		if (false == class_exists($viewClass))
		{
			$viewClass = 'JViewDefault' . ucfirst($viewFormat);
		}

		$view = new $viewClass(new $modelClass, $paths);
		$view->setLayout($layoutName);

		// Render our view.
		echo $view->render();

		return true;
	}

	/**
	 * Redirects the browser or returns false if no redirect is set.
	 *
	 * @return  boolean  False if no redirect exists.
	 *
	 * @since   9999
	 */
	public function redirect()
	{
		if ($this->redirect)
		{
			$app = JFactory::getApplication();
			$app->redirect($this->redirect, $this->message, $this->messageType);
		}

		return false;
	}
}
