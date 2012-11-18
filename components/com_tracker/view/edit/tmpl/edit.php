<?php
/**
 * @package     JTracker
 * @subpackage  com_tracker
 *
 * @copyright   Copyright (C) 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/* @var TrackerViewEditHtml $this */

defined('_JEXEC') or die;

JHtml::_('formbehavior.chosen', 'select');

// Set up the options array for the priority field
$priorityOptions = array();
$priorityOptions['id'] = 'jform_priority';
$priorityOptions['size'] = '5';
?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<div class="container-fluid">
		<h3><?php echo JText::_('Edit Item') . ' [#' . $this->item->id . ']'; ?></h3>
		<div class="row-fluid">
			<div class="span12">
				<div class="input-prepend">
				<span class="add-on"><strong><?php echo JText::_('COM_TRACKER_HEADING_SUMMARY'); ?></strong></span>
				<input type="text" name="jform[title]" id="jform_title" class="input-xxlarge" value="<?php echo htmlspecialchars($this->item->title, ENT_COMPAT, 'UTF-8'); ?>" maxlength="100">
			</div>
		</div>

		<div class="row-fluid">
			<div class="span12">
				<h4><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_INFO'); ?></h4>
				<table class="table">
					<tr class="issue-info-row">
						<td>
							<label><?php echo JText::_('JSTATUS'); ?></label>
							<?php echo JHtmlStatus::options($this->item->status); ?>
						</td>
						<td>
		    	    	    <label><?php echo JText::_('COM_TRACKER_HEADING_PRIORITY'); ?></label>
							<?php echo JHtmlSelect::integerlist(1, 5, 1, 'jform[priority]', $priorityOptions, $this->item->priority); ?>
		    	    	</td>
					</tr>
					<tr class="issue-info-row">
						<td>
							<label><?php echo JText::_('COM_TRACKER_HEADING_GITHUB_ID'); ?></label>
							<input type="text" name="jform[gh_id]" id="jform_gh_id" class="input-small" value="<?php echo htmlspecialchars($this->item->gh_id, ENT_COMPAT, 'UTF-8'); ?>" maxlength="5">
						</td>
						<td>
							<label><?php echo JText::_('COM_TRACKER_HEADING_JOOMLACODE_ID'); ?></strong></label>
							<input type="text" name="jform[jc_id]" id="jform_jc_id" class="input-small" value="<?php echo htmlspecialchars($this->item->jc_id, ENT_COMPAT, 'UTF-8'); ?>" maxlength="5">
						</td>
					</tr>
					<tr class="issue-info-row">
						<td>
							<label><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_PATCH_URL'); ?></label>
							<input type="text" name="jform[patch_url]" id="jform_patch_url" value="<?php echo htmlspecialchars($this->item->patch_url, ENT_COMPAT, 'UTF-8'); ?>">
						</td>
						<td>
							<label><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_DATABASE_TYPE') ?></label>
							<?php echo $this->fieldList[$this->fields['database']->alias]; ?>
						</td>
					</tr>
					<tr class="issue-info-row">
						<td>
							<label><?php echo JText::_('COM_TRACKER_HEADING_DATE_OPENED'); ?></label>
	                    	<?php echo JHtml::_('date', $this->item->opened, 'DATE_FORMAT_LC2'); ?>
						</td>
	                    <td>
	                        <label><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_WEBSERVER') ?></label>
							<?php echo $this->fieldList[$this->fields['web-server']->alias]; ?>
	                    </td>
					</tr>
					<tr class="issue-info-row">
						<td>
							<label><?php echo JText::_('COM_TRACKER_HEADING_DATE_CLOSED'); ?></label>
							<?php if ($this->item->closed) : ?>
								<?php echo JHtml::_('date', $this->item->closed_date, 'DATE_FORMAT_LC2'); ?>
							<?php endif; ?>
						</td>
						<td>
							<label><?php echo JText::_('COM_TRACKER_HEADING_PHP_VERSION'); ?></label>
							<?php echo $this->fieldList[$this->fields['php-version']->alias]; ?>
						</td>
					</tr>
					<tr class="issue-info-row">
						<td>
							<label><?php echo JText::_('COM_TRACKER_HEADING_LAST_MODIFIED'); ?></label>
							<?php if ($this->item->modified != '0000-00-00 00:00:00') : ?>
								<?php echo JHtml::_('date', $this->item->modified, 'DATE_FORMAT_LC2'); ?>
							<?php endif; ?>
						</td>
						<td>
							<label><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_BROWSER'); ?></label>
							<?php echo $this->fieldList[$this->fields['browser']->alias]; ?>
						</td>
					</tr>
					<tr class="issue-info-row">
						<td>
							<label><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_SUCCESSFUL_TEST_COUNT') ?></label>
						</td>
						<td>

						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span12">
				<h4><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_DESC'); ?></h4>
				<div class="well issue">
					<?php echo $this->editor->display('jform[description]', $this->item->description, '100%', 300, 10, 10, false, 'jform_description', null, null, $this->editorParams); ?>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span12">
				<h4><?php echo JText::_('COM_TRACKER_LABEL_ISSUE_TEST_INSTRUCTIONS'); ?></h4>
					<div class="well well-small issue">
						<p>Test Instructions Go Here</p>
					</div>
			</div>
		</div>

	</div>
	<input type="hidden" name="task" />
	<?php echo JHtml::_('form.token'); ?>
</form>
