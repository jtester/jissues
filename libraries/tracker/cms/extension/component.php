<?php
/**
 * @package     JTracker
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

/**
 * Class for extensions type component.
 *
 * @package     JTracker
 * @subpackage  CMS
 * @since       1.0
 */
class JCmsExtensionComponent extends JCmsExtension
{
	/**
	 * Constructor.
	 *
	 * @param   string  $name  The extension name. e.g. com_foo
	 */
	public function __construct($name)
	{
		parent::__construct($name);

		$this->load();
	}

	/**
	 * Render the extension.
	 *
	 * @throws RuntimeException
	 * @return string
	 */
	public function render()
	{
		$app = JFactory::getApplication();

		if (empty($this->name))
		{
			throw new RuntimeException(JText::_('JLIB_APPLICATION_ERROR_COMPONENT_NOT_FOUND'), 404);
		}

		// Record the scope
		$scope = $app->scope;

		// Set scope to component name
		$app->scope = $this->name;

		// Build the component path.
		// $option = preg_replace('/[^A-Z0-9_\.-]/i', '', $this->name);
		$option = $this->name;

		// Define component path.
		define('JPATH_COMPONENT', JPATH_BASE . '/components/' . $this->name);
		define('JPATH_COMPONENT_SITE', JPATH_COMPONENT);
		define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR . '/components/' . $this->name);

		// If component is disabled throw error
		if (!$this->isEnabled($this->name))
		{
			throw new RuntimeException(JText::_('JLIB_APPLICATION_ERROR_COMPONENT_NOT_FOUND') . ' (The component is not enabled)', 404);
		}

		$lang = JFactory::getLanguage();

		// Load common and local language files.
		$lang->load($option, JPATH_BASE, null, false, false) || $lang->load($option, JPATH_COMPONENT, null, false, false)
			|| $lang->load($option, JPATH_BASE, $lang->getDefault(), false, false)
			|| $lang->load($option, JPATH_COMPONENT, $lang->getDefault(), false, false);

		$contents = null;

		$file = substr($option, 4);
		$path = JPATH_COMPONENT . '/' . $file . '.php';

		if (file_exists($path))
		{
			// !! This identifies a "legacy component" !!!

			// Execute the component.
			$contents = $this->executeComponent($path);
		}
		else
		{
			$path = JPATH_COMPONENT . '/component.php';

			if (file_exists($path))
			{
				include $path;
			}

			// NOTE: The ternary instead of the default prevents empty strings.
			// NOTE: strtolower is used to allow tasks like "saveFoo"
			$task = $app->input->get('task') ? : 'default';

			// Set the view name based on the task
			$parts = explode('.', $task);

			if (count($parts) > 1)
			{
				$view = $parts[0];
				$task = implode('', array_map('ucfirst', $parts));
			}
			else
			{
				$view = $task;
			}

			$app->input->set('view', $view);

			// $app->input->set('view', $app->input->get('view', $task));

			// Strip com_ off the component
			$base = substr($option, 4);

			$prefix = ($app->isAdmin()) ? 'Admin' : '';

			// Set the controller class name based on the task
			$class = 'Com' . $prefix . ucfirst($base) . 'Controller' . ucfirst($task);

			$controllerClass = (class_exists($class)) ? $class : 'JControllerDefault';

			/* @var JControllerDefault $controller */
			$controller = new $controllerClass;

			ob_start();
			$controller->execute();
			$contents = ob_get_contents();
			ob_end_clean();

			$controller->redirect();
		}

		// Revert the scope
		$app->scope = $scope;

		return $contents;
	}

	/**
	 * Checks if the component is enabled
	 *
	 * @return  boolean
	 *
	 * @since   11.1
	 */
	public function isEnabled()
	{
		return $this->extension->enabled;
	}

	/**
	 * Get the component information.
	 *
	 * @since   1.0
	 *
	 * @return  object   An object with the information for the component.
	 */
	public function getComponent()
	{
		return $this->extension;
	}

	/**
	 * Load the extension.
	 *
	 * @since  1.0
	 *
	 * @throws RuntimeException
	 *
	 * @return JCmsExtension
	 */
	protected function load()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('extension_id AS id, element AS "option", params, enabled');
		$query->from('#__extensions');
		$query->where($query->qn('type') . ' = ' . $db->quote('component'));
		$query->where($query->qn('element') . ' = ' . $db->quote($this->name));
		$db->setQuery($query);

		$this->extension = $db->loadObject();

		if (!$this->extension)
		{
			throw new RuntimeException(sprintf('Component %s not loading', $this->name));
		}

		$this->extension->params = new JRegistry($this->extension->params);

		return $this;
	}

	/**
	 * Execute the component.
	 *
	 * @param   string  $path  The component path.
	 *
	 * @deprecated
	 *
	 * @return  string  The component output
	 *
	 * @since   11.3
	 */
	protected static function executeComponent($path)
	{
		ob_start();
		require_once $path;
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}
}
