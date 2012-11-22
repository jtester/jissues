<?php
/**
 * @package     JTracker
 * @subpackage  com_tracker
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

/* @var JViewDefaultHtml $this */

JToolbarHelper::preferences('com_tracker');

JHtmlBootstrap::tooltip();

$baseLinkAdd = 'index.php?option=com_categories&view=category&layout=edit&task=category.add&extension=com_tracker';
$buttonStyles = array('class' => 'btn btn-small btn-success');

$project = JFactory::getApplication()->input->get('project');

$layout = ($project) ? 'project' : 'global';

?>
<div class="row-fluid">
    <div class="span2"><?= JHtmlSidebar::render() ?></div>

    <div class="span10">
        <form class="form" name="adminForm" id="adminForm" method="post">
            <div class="row">
                <div class="span12 well well-small">
					<?= JHtmlprojects::projectsSelect($project, 'Select a project to define project specific items.'); ?>
                </div>
            </div>

			<? include $this->getPath($layout) ?>

            <div>
                <input type="hidden" name="option" value="com_tracker"/>
            </div>

        </form>
    </div>
</div>
