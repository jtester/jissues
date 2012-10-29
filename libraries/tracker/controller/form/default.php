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
	 * Instantiate the controller.
	 *
	 * @param   JInput            $input  The input object.
	 * @param   JApplicationBase  $app    The application object.
	 *
	 * @since  12.1
	 */
	public function __construct(JInput $input = null, JApplicationBase $app = null)
	{
		$this->option = 'com_' . strtolower($this->getName());

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

		parent::__construct($input, $app);
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
		if (empty($this->name))
		{
			$r = null;

			if (!preg_match('/ComAdmin(.*)Controller/i', get_class($this), $r))
			{
				if (!preg_match('/Com(.*)Controller/i', get_class($this), $r))
				{
					throw new Exception(JText::_('JLIB_APPLICATION_ERROR_CONTROLLER_GET_NAME'), 500);
				}
			}

			$this->name = strtolower($r[1]);
		}

		return $this->name;
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @return  object  The model.
	 *
	 * @since   9999
	 */
	abstract public function getModel();

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
}
