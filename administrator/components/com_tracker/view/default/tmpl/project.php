<?php
/**
 * @package     JTracker
 * @subpackage  com_tracker
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;
?>

<h2><?= sprintf($this->_('Project %s'), JHtmlProjects::getName($project)) ?></h2>

<div class="row">
    <div class="span12">
        <h3><?= $this->_('Categories') ?></h3>
	    <?= JHtml::link($baseLinkAdd . '.' . $project . '.categories', $this->_('Add a Category'), $buttonStyles) ?>
        <div class="well well-small">
	        <?= JHtmlProjects::listing('com_tracker.' . $project . '.categories') ? : $this->_('Use global') ?>
        </div>

    </div>
</div>
<div class="row">
    <div class="span4">
        <h3><?= $this->_('Textfields') ?></h3>
	    <?= JHtml::link($baseLinkAdd . '.' . $project . '.textfields', $this->_('Add a Textfield'), $buttonStyles) ?>
        <div class="well well-small">
		<?= JHtmlProjects::listing('com_tracker.' . $project . '.textfields') ? : $this->_('Use global') ?>
        </div>
    </div>
    <div class="span4">
        <h3><?= $this->_('Selectlists') ?></h3>
	    <?= JHtml::link($baseLinkAdd . '.' . $project . '.fields', $this->_('Add a Selectlist'), $buttonStyles) ?>
        <div class="well well-small">
	    <?= JHtmlProjects::listing('com_tracker.' . $project . '.fields') ? : $this->_('Use global') ?>
        </div>
    </div>
    <div class="span4">
        <h3><?= $this->_('Checkboxes') ?></h3>
	    <?= JHtml::link($baseLinkAdd . '.' . $project . '.checkboxes', $this->_('Add a Checkbox'), $buttonStyles) ?>
        <div class="well well-small">
	    <?= JHtmlProjects::listing('com_tracker.' . $project . '.checkboxes') ? : $this->_('Use global') ?>
        </div>
    </div>
</div>

