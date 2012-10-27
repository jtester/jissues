<?php
/**
 * @package     JTracker
 * @subpackage  com_tracker
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JToolbarHelper::save('saveproject');
JToolbarHelper::cancel('projects');

JToolbarHelper::title('JTracker - Edit Project');

JHtmlBootstrap::tooltip();

?>
<form action="<?php echo JRoute::_('index.php?option=com_tracker&task=saveproject'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-horizontal">

    <fieldset>

        <legend><?php echo JText::_('JDETAILS');?></legend>

		<?php foreach ($this->model->getForm()->getFieldSet() as $field) : ?>
        <div class="row-fluid">
            <div class="control-label">
				<?php echo $field->label; ?>
            </div>
            <div class="controls">
				<?php echo $field->input; ?>
            </div>
        </div>
		<?php endforeach; ?>

    </fieldset>

    <div>
        <input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>
    </div>
</form>
