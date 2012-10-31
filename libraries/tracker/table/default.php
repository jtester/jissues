<?php
/**
 * @package     JTracker
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

/**
 * Table base class.
 *
 * @package     JTracker
 * @subpackage  Table
 * @since       1.0
 */
class JTableDefault extends JTable
{
	/**
	 * This is an "alias" for the tables primary key.
	 *
	 * @var int
	 */
	public $id = 0;


	/**
	 * Object constructor to set table and key fields.  In most cases this will
	 * be overridden by child classes to explicitly set the table and key fields
	 * for a particular database table.
	 *
	 * @param   string           $table  Name of the table to model.
	 * @param   mixed            $key    Name of the primary key field in the table or array of field names that compose the primary key.
	 * @param   JDatabaseDriver  $db     JDatabaseDriver object.
	 *
	 * @since   11.1
	 */
	public function __construct($table, $key, JDatabaseDriver $db = null)
	{
		if(is_null($db))
		{
			$db = JFactory::getDbo();
		}

		parent::__construct($table, $key, $db);
	}

		/**
	 * Method to load a row from the database by primary key and bind the fields
	 * to the JTable instance properties.
	 *
	 * @param   mixed    $keys   An optional primary key value to load the row by, or an array of fields to match.  If not
	 *                           set the instance property value is used.
	 * @param   boolean  $reset  True to reset the default values before loading the new row.
	 *
	 * @return  boolean  True if successful. False if row not found.
	 *
	 * @link    http://docs.joomla.org/JTable/load
	 * @since   9999
	 * @throws  RuntimeException
	 * @throws  UnexpectedValueException
	 */
	public function load($keys = null, $reset = true)
	{
		$legacyResult = parent::load($keys, $reset);

		if (false === $legacyResult)
		{
			return false;
		}

		$this->id = $this->{$this->_tbl_key};

		return $this;
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  JTable.
	 *
	 * @link    http://docs.joomla.org/JTable/bind
	 * @since   9999
	 * @throws  UnexpectedValueException
	 */
	public function bind($src, $ignore = array())
	{
		parent::bind($src, $ignore);

		if ($this->_tbl_key != 'id')
		{
			unset($this->id);
		}

		return $this;
	}

	/**
	 * Method to perform sanity checks on the JTable instance properties to ensure
	 * they are safe to store in the database.  Child classes should override this
	 * method to make sure the data they are storing in the database is safe and
	 * as expected before storage.
	 *
	 * @return  JTable
	 *
	 * @link    http://docs.joomla.org/JTable/check
	 * @since   9999
	 */
	public function check()
	{
		// We like chaining =;)
		return $this;
	}
}
