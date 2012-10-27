<?php
/**
 * @package     JTracker
 * @subpackage  com_tracker
 *
 * @copyright   Copyright (C) 2012 Open Source Matters. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JToolbarHelper::addNew('project');

JToolbarHelper::title('JTracker: Projects');

$baseLink = 'index.php?option=com_tracker';
$editLink = $baseLink . '&view=project&id=';
$deleteLink = $baseLink . '&task=deleteproject&id=';

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
                    <th style="width: 5%;">ID</th>
                    <th>Title</th>
                    <th>Alias</th>
                    <th style="width: 5%;">Action</th>
                </tr>
                </thead>

                <tbody>
				<?php foreach ($this->model->getItems() as $item) : ?>
                <tr>
                    <td><?= $item->id ?></td>
                    <td><?= JHtml::link($editLink . $item->id, $item->title) ?></td>
                    <td><?= $item->alias ?></td>
                    <td><?= JHtml::link($deleteLink . $item->id, 'Delete') ?></td>
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
