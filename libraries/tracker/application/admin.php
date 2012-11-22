<?php
/**
 * @package     JTracker
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

/**
 * Joomla! Issue Tracker Admin Application class
 *
 * @package     JTracker
 * @subpackage  Application
 * @since       1.0
 */
final class JApplicationAdmin extends JApplicationTracker
{
	/**
	 * Class constructor.
	 *
	 * @param   mixed  $input   An optional argument to provide dependency injection for the application's
	 *                          input object.  If the argument is a JInput object that object will become
	 *                          the application's input object, otherwise a default input object is created.
	 * @param   mixed  $config  An optional argument to provide dependency injection for the application's
	 *                          config object.  If the argument is a JRegistry object that object will become
	 *                          the application's config object, otherwise a default config object is created.
	 * @param   mixed  $client  An optional argument to provide dependency injection for the application's
	 *                          client object.  If the argument is a JApplicationWebClient object that object will become
	 *                          the application's client object, otherwise a default client object is created.
	 *
	 * @since   1.0
	 */
	public function __construct(JInput $input = null, JRegistry $config = null, JApplicationWebClient $client = null)
	{
		// Set the client ID
		$this->clientId = 1;

		// We assume the administrator folder to be one level above the Joomla! root.
		$adminFolder = trim(str_replace(JPATH_SITE, '', JPATH_ADMINISTRATOR), DIRECTORY_SEPARATOR);

		// Set the app name - With a fallback - Just in case ;)
		$this->name = $adminFolder ? : 'administrator';

		// Set the root in the URI based on the application name
		JURI::root(false, str_ireplace('/' . $this->getName(), '', JURI::base(true)));

		// Run the parent constructor
		parent::__construct($input, $config, $client);
	}

	/**
	 * Returns the application JMenu object.
	 *
	 * @param   string  $name     The name of the application/client.
	 * @param   array   $options  An optional associative array of configuration settings.
	 *
	 * @return  JMenu  JMenu object.
	 *
	 * @since   1.0
	 */
	public function getMenu($name = null, $options = array())
	{
		return parent::getMenu('admin', $options);
	}

	/**
	 * Returns the application JRouter object.
	 *
	 * @param   string  $name     The name of the application.
	 * @param   array   $options  An optional associative array of configuration settings.
	 *
	 * @return  JRouter  A JRouter object
	 *
	 * @since   1.0
	 */
	public function getRouter($name = null, array $options = array())
	{
		// TODO: Probably need to build a proper JRouter class...
		$router = parent::getRouter('tracker', $options);

		return $router;
	}

	/**
	 * Get the template information
	 *
	 * @param   boolean  $params  True to return the template params
	 *
	 * @return  mixed  String with the template name or an object containing the name and params
	 *
	 * @since   1.0
	 */
	public function getTemplate($params = false)
	{
		static $template;

		if (!$template)
		{
			// Build the object
			$template           = new stdClass;
			$template->template = 'isis';
			$template->params   = new JRegistry;
		}

		if ($params)
		{
			return $template;
		}

		return $template->template;
	}

	/**
	 * Dummy function.
	 *
	 * @return bool
	 */
	public function getLanguageFilter()
	{
		return false;
	}

	/**
	 * Returns a property of the object or the default value if the property is not set.
	 *
	 * @param   string  $key      The name of the property.
	 * @param   mixed   $default  The default value (optional) if none is set.
	 *
	 * @deprecated use get()
	 *
	 * @return  mixed   The value of the configuration.
	 *
	 * @since      11.3
	 */
	public function getCfg($key, $default = null)
	{
		return $this->get($key, $default);
	}

	/**
	 * Method to run the Web application routines.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function doExecute()
	{
		try
		{
			// Load the document to the API
			$this->loadDocument();

			// Register the document object with JFactory
			JFactory::$document = $this->document;

			$this->loadTemplate();

			// Set metadata
			$this->document->setTitle('Joomla! CMS Issue Tracker - Admin');

			// Load the component
			$component = new JCmsExtensionComponent($this->findOption());

			$contents = $component->render();

			$this->document->setBuffer($contents, 'component');
		}
		catch (Exception $e)
		{
			// Mop up any uncaught exceptions.
			echo $e->getMessage();

			if (JDEBUG)
			{
				echo '<pre>';
				echo '<h2>Exception trace</h2>';
				echo $e->getTraceAsString();
				echo '<h2>call stack</h2>';
				debug_print_backtrace();
				echo '</pre>';
			}

			$this->close($e->getCode());
		}
	}

	/**
	 * Return the application option string [main component].
	 *
	 * @return  string  The component to access.
	 *
	 * @since   1.5
	 */
	protected function findOption()
	{
		$app    = JFactory::getApplication();
		$option = strtolower($app->input->get('option'));

		$app->loadIdentity();
		$user = $app->getIdentity();

		if ($user->get('guest') || !$user->authorise('core.login.admin'))
		{
			$option = 'com_login';
			$this->input->set('view', 'default');
			$this->input->set('layout', 'default');
			$this->set('themeFile', 'login.php');
		}

		if (empty($option))
		{
			$option = 'com_cpanel';
		}

		$app->input->set('option', $option);

		return $option;
	}

	/**
	 * Load the template.
	 *
	 * @return JApplicationAdmin
	 */
	protected function loadTemplate()
	{
		// Register the template to the config
		$template = $this->getTemplate(true);

		$this->set('theme', $template->template);
		$this->set('themeParams', $template->params);

		if ('com_login' == $this->input->get('option'))
		{
			$this->set('themeFile', 'login.php');
		}
		else
		{
			$this->set('themeFile', $this->input->get('tmpl', 'index') . '.php');
		}

		// Load template language files.
		$tName = $template->template;

		$lang = JFactory::getLanguage();

		$lang->load('tpl_' . $tName, JPATH_BASE, null, false, false)
			|| $lang->load('tpl_' . $tName, JPATH_THEMES . '/' . $tName, null, false, false)
			|| $lang->load('tpl_' . $tName, JPATH_BASE, $lang->getDefault(), false, false)
			|| $lang->load('tpl_' . $tName, JPATH_THEMES . '/' . $tName, $lang->getDefault(), false, false);

		return $this;
	}

	/**
	 * Method to load a PHP configuration class file based on convention and return the instantiated data object.  You
	 * will extend this method in child classes to provide configuration data from whatever data source is relevant
	 * for your specific application.
	 *
	 * @param   string  $file   The path and filename of the configuration file. If not provided, configuration.php
	 *                          in JPATH_BASE will be used.
	 * @param   string  $class  The class name to instantiate.
	 *
	 * @throws RuntimeException
	 *
	 * @since   11.3
	 *
	 * @return  mixed   Either an array or object to be loaded into the configuration object.
	 */
	protected function fetchConfigurationData($file = '', $class = 'JConfig')
	{
		// Instantiate variables.
		$config = array();

		if (empty($file) && defined('JPATH_CONFIGURATION'))
		{
			$file = JPATH_CONFIGURATION . '/configuration.php';

			// Applications can choose not to have any configuration data
			// by not implementing this method and not having a config file.
			if (!file_exists($file))
			{
				$file = '';
			}
		}

		if (!empty($file))
		{
			JLoader::register($class, $file);

			if (class_exists($class))
			{
				$config = new $class;
			}
			else
			{
				throw new RuntimeException('Configuration class does not exist.');
			}
		}

		return $config;
	}

	/**
	 * Login authentication function
	 *
	 * @param   array  $credentials  Array('username' => string, 'password' => string)
	 * @param   array  $options      Array('remember' => boolean)
	 *
	 * @see        JApplication::login
	 * @since      1.5
	 *
	 * @return    boolean True on success.
	 */
	public function login($credentials, $options = array())
	{
		// The minimum group
		$options['group'] = 'Public Backend';

		// Make sure users are not autoregistered
		$options['autoregister'] = false;

		// Set the application login entry point
		if (!array_key_exists('entry_url', $options))
		{
			$options['entry_url'] = JURI::base() . 'index.php?option=com_users&task=login';
		}

		// Set the access control action to check.
		$options['action'] = 'core.login.admin';

		return parent::login($credentials, $options);
	}
}
