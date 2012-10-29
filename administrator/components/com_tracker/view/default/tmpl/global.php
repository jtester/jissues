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
<div class="row-fluid">
    <div class="span6">
        <h2>Projects</h2>
		<?= JHtml::link('index.php?option=com_tracker&view=project', 'Add a Project', $buttonStyles) ?>
        <div class="well well-small">
			<?= JHtmlProjects::projectsListing() ?>
        </div>
    </div>

    <div class="span6">
        <h2>Categories</h2>
		<?= JHtml::link($baseLinkAdd . '.categories', 'Add a Category', $buttonStyles) ?>
        <div class="well well-small">
			<?= JHtmlProjects::listing('com_tracker.categories') ?>
        </div>
    </div>
</div>

<h2>Global fields</h2>
<div class="row-fluid">
    <div class="span4">
        <h3>Textfields</h3>
		<?= JHtml::link($baseLinkAdd . '.textfields', 'Add a Textfield', $buttonStyles) ?>
        <div class="well well-small">
			<?= JHtmlProjects::listing('com_tracker.textfields') ?>
        </div>
    </div>

    <div class="span4">
        <h3>Selectlists</h3>
		<?= JHtml::link($baseLinkAdd . '.fields', 'Add a Selectlist', $buttonStyles) ?>
        <div class="well well-small">
			<?= JHtmlProjects::listing('com_tracker.fields', true) ?>
        </div>
    </div>

    <div class="span4">
        <h3>Checkboxes</h3>
		<?= JHtml::link($baseLinkAdd . '.checkboxes', 'Add a Checkbox', $buttonStyles) ?>
        <div class="well well-small">
			<?= JHtmlProjects::listing('com_tracker.checkboxes') ?>
        </div>
    </div>
</div>
