<?php
/**
 * @package     JTracker
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

/**
 * Tracker form controller class
 *
 * @package     JTracker
 * @subpackage  Controller
 * @since       1.0
 */
abstract class JControllerFormDefault extends JControllerDefault
{
	/**
	 * The context for storing internal data, e.g. record.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $context;

	/**
	 * Record id.
	 *
	 * @var integer
	 */
	protected $id;

	/**
	 * Form data.
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * The URL option for the component.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $option;

	/**
	 * Name of the list view.
	 *
	 * @var string
	 */
	protected $listView;

	/**
	 * Redirect URL.
	 *
	 * @var string
	 */
	protected $redirect;

	/**
	 * @var string
	 */
	protected $itemLink;

	/**
	 * @var string
	 */
	protected $listLink;

	/**
	 * @var string
	 */
	protected $editContext;

	/**
	 * Component name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Controller type.
	 * Used for class name building.
	 *
	 * @var string
	 */
	protected $type = 'Default';

	/**
	 * Instantiate the controller.
	 *
	 * @param   JInput            $input  The input object.
	 * @param   JApplicationBase  $app    The application object.
	 *
	 * @throws Exception
	 * @since  12.1
	 */
	public function __construct(JInput $input = null, JApplicationBase $app = null)
	{
		parent::__construct($input, $app);

		// Guess the context as the suffix, eg: Com[Admin]OptionControllerSaveContent.
		if (!preg_match('/Com[Admin]*(.*)Controller(.*)' . $this->type . '/i', get_class($this), $r))
		{
			throw new Exception(
				sprintf('%s - Cannot get or parse class name %s for controller type %s.',
					__METHOD__, get_class($this), $this->type
				),
				500
			);
		}

		$this->name    = strtolower($r[1]);
		$this->context = strtolower($r[2]);

		$this->option = 'com_' . strtolower($this->name);

		// @TODO Probably worth moving to an inflector class based on
		// http://kuwamoto.org/2007/12/17/improved-pluralizing-in-php-actionscript-and-ror/

		// Simple pluralisation based on public domain snippet by Paul Osman
		// For more complex types, just manually set the variable in your class.
		$plural = array(
			array('/(x|ch|ss|sh)$/i', "$1es"),
			array('/([^aeiouy]|qu)y$/i', "$1ies"),
			array('/([^aeiouy]|qu)ies$/i', "$1y"),
			array('/(bu)s$/i', "$1ses"),
			array('/s$/i', "s"),
			array('/$/', "s"));

		// Check for matches using regular expressions
		foreach ($plural as $pattern)
		{
			if (preg_match($pattern[0], $this->context))
			{
				$this->listView = preg_replace($pattern[0], $pattern[1], $this->context);
				break;
			}
		}

		$this->data = $this->input->post->get('jform', array(), 'array');

		$this->id = (isset($this->data['id'])) ? (int) $this->data['id'] : 0;
		$this->id = ($this->id) ? : $this->input->getInt('id');

		$this->editContext = $this->option . '.edit.' . $this->context;

		$this->itemLink = 'index.php?option=' . $this->option . '&view=' . $this->context;
		$this->itemLink .= ($this->id) ? '&id=' . $this->id : '';

		$this->listLink = 'index.php?option=' . $this->option . '&view=' . $this->listView;
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
	}

	/**
	 * Method to get the controller name
	 *
	 * The dispatcher name is set by default parsed using the classname, or it can be set
	 * by passing a $config['name'] in the class constructor
	 *
	 * @return  string  The name of the dispatcher
	 *
	 * @since   12.2
	 * @throws  Exception
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @since   9999
	 * @throws RuntimeException
	 * @return  JModel  The model.
	 */
	public function getModel()
	{
		static $model = null;

		if ($model)
		{
			return $model;
		}

		if (!preg_match('/(.*)Controller/i', get_class($this), $r))
		{
			throw new RuntimeException(JText::_('Unable to get the model name'), 500);
		}

		$ext = $r[1];

		$class = $ext . 'Model' . ucfirst($this->context);

		if (false == class_exists($class))
		{
			throw new RuntimeException(sprintf(JText::_('Model class %s not found'), $class), 500);
		}

		$model = new $class;

		return $model;
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 *
	 * @param   JModel  $model      The data model object.
	 * @param   array   $validData  The validated data.
	 *
	 * @todo    since the type hinting differs from that in the base class.
	 *          THIS WILL GENERATE A PHP STRICT warning..
	 *
	 * @return  void
	 *
	 * @since   9999
	 */
	protected function postSaveHook(JModel $model, $validData = array())
	{
	}

	/**
	 * Method to check whether an ID is in the edit list.
	 *
	 * @param   string   $context  The context for the session storage.
	 * @param   integer  $id       The ID of the record to add to the edit list.
	 *
	 * @return  boolean  True if the ID is in the edit list.
	 *
	 * @since   12.2
	 */
	protected function checkEditId($context, $id)
	{
		if (!$id)
		{
			// No id for a new item.
			return true;
		}

		$app    = JFactory::getApplication();
		$values = (array) $app->getUserState($context . '.id');

		$result = in_array((int) $id, $values);

		if (defined('JDEBUG') && JDEBUG)
		{
			JLog::add(
				sprintf(
					'Checking edit ID %s.%s: %d %s',
					$context,
					$id,
					(int) $result,
					str_replace("\n", ' ', print_r($values, 1))
				),
				JLog::INFO,
				'controller'
			);
		}

		return $result;
	}

	/**
	 * Method to add a record ID to the edit list.
	 *
	 * @param   string   $context  The context for the session storage.
	 * @param   integer  $id       The ID of the record to add to the edit list.
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	protected function holdEditId($context, $id)
	{
		$app    = JFactory::getApplication();
		$values = (array) $app->getUserState($context . '.id');

		// Add the id to the list if non-zero.
		if (!empty($id))
		{
			array_push($values, (int) $id);
			$values = array_unique($values);
			$app->setUserState($context . '.id', $values);

			if (defined('JDEBUG') && JDEBUG)
			{
				JLog::add(
					sprintf(
						'Holding edit ID %s.%s %s',
						$context,
						$id,
						str_replace("\n", ' ', print_r($values, 1))
					),
					JLog::INFO,
					'controller'
				);
			}
		}
	}

	/**
	 * Method to check whether an ID is in the edit list.
	 *
	 * @param   string   $context  The context for the session storage.
	 * @param   integer  $id       The ID of the record to add to the edit list.
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	protected function releaseEditId($context, $id)
	{
		$app    = JFactory::getApplication();
		$values = (array) $app->getUserState($context . '.id');

		// Do a strict search of the edit list values.
		$index = array_search((int) $id, $values, true);

		if (is_int($index))
		{
			unset($values[$index]);
			$app->setUserState($context . '.id', $values);

			if (defined('JDEBUG') && JDEBUG)
			{
				JLog::add(
					sprintf(
						'Releasing edit ID %s.%s %s',
						$context,
						$id,
						str_replace("\n", ' ', print_r($values, 1))
					),
					JLog::INFO,
					'controller'
				);
			}
		}
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * @return  boolean
	 *
	 * @since   12.2
	 */
	protected function allowAdd()
	{
		$user = JFactory::getUser();

		return ($user->authorise('core.create', $this->option)
			|| count($user->getAuthorisedCategories($this->option, 'core.create'))
		);
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * @return  boolean
	 *
	 * @since   12.2
	 */
	protected function allowEdit()
	{
		return JFactory::getUser()->authorise('core.edit', $this->option);
	}

	/**
	 * Method to check if you can save a new or existing record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   12.2
	 */
	protected function allowSave($data)
	{
		$recordId = isset($data['id']) ? $data['id'] : '0';

		if ($recordId)
		{
			return $this->allowEdit($data);
		}
		else
		{
			return $this->allowAdd($data);
		}
	}

	/**
	 * Set the redirect.
	 *
	 * @return JControllerFormSave
	 */
	protected function setRedirect()
	{
		// Clear the record id and data from the session.
		$this->releaseEditId($this->editContext, $this->id);
		$this->app->setUserState($this->editContext . '.data', null);

		// Redirect to the list screen.
		$this->redirect = JRoute::_('index.php?option=' . $this->option . '&view=' . $this->listView, false);

		return $this;
	}
}
