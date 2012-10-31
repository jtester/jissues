<?php
/**
 * @package     JTracker
 * @subpackage  com_tracker
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

JToolbarHelper::save('projectSave');
JToolbarHelper::apply('projectApply');
JToolbarHelper::save2copy('projectSave2copy');
JToolbarHelper::save2new('projectSave2new');
JToolbarHelper::cancel('projectCancel');

JToolbarHelper::title('JTracker - Edit Project');

JHtmlBootstrap::tooltip();

JHtml::_('script', 'system/core.js', false, true);
?>
<form action="<?php echo JRoute::_('index.php?option=com_tracker'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-horizontal">

    <fieldset>

        <legend><?php echo JText::_('JDETAILS');?></legend>

		<?php foreach ($this->model->getForm()->getFieldSet() as $field) : ?>
        <div class="row-fluid">
            <div class="control-label">
	            <?= $field->label; ?>
            </div>
        <div class="controls">
		        <?= $field->getInputTooltip(); ?>
        </div>
        </div>
		<?php endforeach; ?>

    </fieldset>

    <div>
        <input type="hidden" name="task" value="saveproject"/>
        <input type="hidden" name="view" value="projects"/>
		<?php echo JHtml::_('form.token'); ?>
    </div>
</form>
