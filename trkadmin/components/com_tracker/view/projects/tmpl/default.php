<?php
/**
 * @package     JTracker
 * @subpackage  com_tracker
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') || die;

JToolbarHelper::addNew('project');

JToolbarHelper::title('JTracker: Projects');

$baseLink = 'index.php?option=com_tracker';
$editLink = $baseLink . '&task=project.edit&id=';
$deleteLink = $baseLink . '&task=project.delete&id=';

JHtml::_('script', 'system/core.js', false, true);
?>
<div class="row-fluid">

    <div class="span2">
		<?= JHtmlSidebar::render() ?>
    </div>

    <div class="span10">
        <form action="<?= $baseLink ?>" id="adminForm" method="post">

            <table class="table table-bordered table-striped table-hover">

                <thead>
                <tr>
                    <th style="width: 5%;"><?= $this->_('ID') ?></th>
                    <th><?= $this->_('Title') ?></th>
                    <th style="width: 20%;"><?= $this->_('GitHub') ?></th>
                    <th style="width: 5%;"><?= $this->_('Action') ?></th>
                </tr>
                </thead>

                <tbody>
				<?php foreach ($this->model->getItems() as $item) : ?>
                <tr>
                    <td><?= $item->id ?></td>
                    <td>
	                    <?= JHtml::link($editLink . $item->id, $item->title) ?>
	                    <span class="small">(Alias: <?= $item->alias ?>)</span>
                    </td>
                    <td>
	                    <? if ($item->gh_user && $item->gh_project) : ?>
	                    <div class="nowrap">
	                    <?= JHtml::link('https://github.com/' . $item->gh_user . '/' . $item->gh_project,
	                    $item->gh_user . '/' . $item->gh_project) ?>
                        </div>
						<? endif; ?>
					</td>
                    <td><?= JHtml::link($deleteLink . $item->id, $this->_('Delete')) ?></td>
                </tr>
				<?php endforeach; ?>
                </tbody>

            </table>

            <div>
                <input type="hidden" name="task" value=""/>
                <input type="hidden" name="option" value="com_tracker"/>
            </div>

        </form>
    </div>
</div>
