<?php
/**
 * @package     JTracker
 * @subpackage  com_tracker
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

/**
 * JTracker model.
 *
 * @package     JTracker
 * @subpackage  com_tracker
 * @since       1.0
 */
class ComAdminTrackerModelProject extends JModelTrackerform
{
	/**
	 * Get a form object.
	 *
	 * @return JForm
	 */
	public function getForm()
	{
		$form = $this->loadForm('project');

		$id = JFactory::getApplication()->input->getInt('id');

		$id = $id ? : (int) $this->getState()->get('com_tracker.edit.project.id');

		if ($id)
		{
			$table = $this->getTable()
				->load($id);

			$form->bind($table);
		}

		return $form;
	}

	/**
	 * Get the corresponding table.
	 *
	 * @return ComAdminTrackerTableProjects
	 */
	public function getTable()
	{
		return new ComAdminTrackerTableProjects;
	}

	/**
	 * Bind and store the item.
	 *
	 * @param   array  $data  The VALIDATED data.
	 *
	 * @return ComAdminTrackerModelProject
	 */
	public function save($data)
	{
		$table = $this->getTable();

		$id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState()->get('com_tracker.id');

		if ($id > 0)
		{
			$table->load($id);
		}

		$table->bind($data)
			->check()
			->store();

		return $this;
	}

	/**
	 * Delete a record.
	 *
	 * @param   integer  $id  The item id.
	 *
	 * @throws RuntimeException
	 *
	 * @return ComAdminTrackerModelProject
	 */
	public function delete($id)
	{
		$table = $this->getTable();

		$legacyReturn = $table->delete($id);

		if (false === $legacyReturn)
		{
			throw new RuntimeException($table->getError());
		}

		return $this;

		// @todo after legacy removals this will be:

		$this->getTable()->delete($id);

		return $this;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array  The default data is an empty array.
	 *
	 * @since   1.0
	 */
	public function loadFormData()
	{
		return JFactory::getApplication()
			->getUserState('com_tracker.edit.project.data');
	}
}
