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
 * JTracker controller.
 *
 * @package     JTracker
 * @subpackage  com_tracker
 * @since       1.0
 */
class JControllerFormDelete extends JControllerFormDefault
{
	/**
	 * Controller type.
	 * Used for class name building.
	 *
	 * @var string
	 */
	protected $type = 'Delete';

	/**
	 * Delete a record.
	 *
	 * @since   1.0
	 *
	 * @return  void.
	 */
	public function execute()
	{
		$model = $this->getModel();

		$model->delete($this->id);

		$this->redirect = $this->listLink;
	}
}
