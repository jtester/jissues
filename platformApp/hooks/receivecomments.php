#!/usr/bin/env php
<?php
/**
 * @package     JTracker
 * @subpackage  Hooks
 *
 * @copyright   Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// We are a valid entry point.
const _JEXEC = 1;

// Bootstrap the hook application
require_once __DIR__ . '/bootstrap.php';

/**
 * Web application to receive and inject issue comments from GitHub
 *
 * @package     JTracker
 * @subpackage  Hooks
 * @since       1.0
 */
final class TrackerReceiveComments extends JApplicationHooks
{
	/**
	 * Class constructor.
	 *
	 * @since   1.0
	 */
	public function __construct()
	{
		// Set the type of hook
		$this->hookType = 'comments';

		// Run the parent constructor
		parent::__construct();

		// Get the project data
		$this->getProjectData();
	}

	/**
	 * Method to run the application routines.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function doExecute()
	{
		// Initialize the database
		$query = $this->db->getQuery(true);

		// Get the comment ID
		$commentID = $this->hookData->comment->id;

		// Check to see if the comment is already in the database
		$query->select($this->db->quoteName('id'));
		$query->from($this->db->quoteName('#__activity'));
		$query->where($this->db->quoteName('gh_comment_id') . ' = ' . (int) $commentID);
		$this->db->setQuery($query);

		try
		{
			$comment = $this->db->loadResult();
		}
		catch (RuntimeException $e)
		{
			JLog::add('Error checking the database for comment ID:' . $e->getMessage(), JLog::INFO);
			$this->close();
		}

		// If the item is already in the databse, update it; else, insert it
		if ($comment)
		{
			$this->updateComment($comment);
		}
		else
		{
			$this->insertComment();
		}
	}

	/**
	 * Method to insert data for acomment from GitHub
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.0
	 */
	protected function insertComment()
	{
		// Initialize the database
		$query = $this->db->getQuery(true);

		// First, make sure the issue is already in the database
		$query->select($this->db->quoteName('id'));
		$query->from($this->db->quoteName('#__issues'));
		$query->where($this->db->quoteName('gh_id') . ' = ' . (int) $this->hookData->issue->number);
		$query->where($this->db->quoteName('project_id') . ' = ' . $this->project->project_id);
		$this->db->setQuery($query);

		try
		{
			$issueID = $this->db->loadResult();
		}
		catch (RuntimeException $e)
		{
			JLog::add('Error checking the database for GitHub ID:' . $e->getMessage(), JLog::INFO);
			$this->close();
		}

		// If we don't have an ID, we need to insert the issue
		if (!$issueID)
		{
			$issueID = $this->insertIssue();
		}

		// Try to render the comment with GitHub markdown
		try
		{
			$text = $this->github->markdown->render($this->hookData->comment->body, 'gfm', $this->project->gh_user . '/' . $this->project->gh_project);
		}
		catch (DomainException $e)
		{
			JLog::add(sprintf('Error parsing comment %s with GH Markdown: %s', $this->hookData->comment->id, $e->getMessage()), JLog::INFO);
			$this->close();
		}

		// Initialize our JTableActivity instance to insert the new record
		$table = JTable::getInstance('Activity');

		$table->gh_comment_id = $this->hookData->comment->id;
		$table->issue_id      = (int) $issueID;
		$table->user          = $this->hookData->comment->user->login;
		$table->event         = 'comment';
		$table->text          = $text;
		$table->created       = JFactory::getDate($this->hookData->comment->created_at)->toSql();

		if (!$table->store())
		{
			JLog::add(sprintf('Error storing new comment %s in the database: %s', $this->hookData->comment->id, $table->getError()), JLog::INFO);
			$this->close();
		}

		// Store was successful, update status
		JLog::add(sprintf('Added GitHub comment %s to the tracker.', $this->hookData->comment->id), JLog::INFO);

		return true;
	}

	/**
	 * Method to insert data for an issue from GitHub
	 *
	 * @return  integer  Issue ID
	 *
	 * @since   1.0
	 */
	protected function insertIssue()
	{
		// Try to render the description with GitHub markdown
		try
		{
			$issue = $this->github->markdown->render($this->hookData->issue->body, 'gfm', $this->project->gh_user . '/' . $this->project->gh_project);
		}
		catch (DomainException $e)
		{
			JLog::add(sprintf('Error parsing issue text for ID %s with GH Markdown: %s', $this->hookData->issue->number, $e->getMessage()), JLog::INFO);
			$this->close();
		}

		$table = JTable::getInstance('Issue');
		$table->gh_id       = $this->hookData->issue->number;
		$table->title       = $this->hookData->issue->title;
		$table->description = $issue;
		$table->status		= ($this->hookData->issue->state) == 'open' ? 1 : 10;
		$table->opened      = JFactory::getDate($this->hookData->issue->created_at)->toSql();
		$table->modified    = JFactory::getDate($this->hookData->issue->updated_at)->toSql();
		$table->project_id  = $this->project->project_id;

		// Add the diff URL if this is a pull request
		if ($this->hookData->issue->pull_request->diff_url)
		{
			$table->patch_url = $this->hookData->issue->pull_request->diff_url;
		}

		// Add the closed date if the status is closed
		if ($this->hookData->issue->closed_at)
		{
			$table->closed_date = $this->hookData->issue->closed_at;
		}

		// If the title has a [# in it, assume it's a Joomlacode Tracker ID
		// TODO - Would be better suited as a regex probably
		if (strpos($this->hookData->issue->title, '[#') !== false)
		{
			$pos = strpos($this->hookData->issue->title, '[#') + 2;
			$table->jc_id = substr($this->hookData->issue->title, $pos, 5);
		}

		if (!$table->store())
		{
			JLog::add(sprintf('Error storing new item %s in the database: %s', $this->hookData->issue->number, $table->getError()), JLog::INFO);
			$this->close();
		}

		// Get the ID for the new issue
		$query = $this->db->getQuery(true);
		$query->select('id');
		$query->from($this->db->quoteName('#__issues'));
		$query->where($this->db->quoteName('gh_id') . ' = ' . (int) $this->hookData->issue->number);
		$this->db->setQuery($query);

		try
		{
			$issueID = $this->db->loadResult();
		}
		catch (RuntimeException $e)
		{
			JLog::add(sprintf('Error retrieving ID for GitHub issue %s in the database: %s', $this->hookData->issue->number, $e->getMessage()), JLog::INFO);
			$this->close();
		}

		// Add an open record to the activity table
		$activity = new JTableActivity($this->db);
		$activity->issue_id = (int) $issueID;
		$activity->user     = $this->hookData->issue->user->login;
		$activity->event    = 'open';
		$activity->created  = $table->opened;

		if (!$activity->store())
		{
			JLog::add(sprintf('Error storing open activity for issue %s in the database: %s', $issueID, $activity->getError()), JLog::INFO);
			$this->close();
		}

		// Add a close record to the activity table if the status is closed
		if ($this->hookData->issue->closed_at)
		{
			$activity = new JTableActivity($this->db);
			$activity->issue_id = (int) $issueID;
			$activity->user     = $this->hookData->issue->user->login;
			$activity->event    = 'close';
			$activity->created  = $table->closed_date;

			if (!$activity->store())
			{
				JLog::add(sprintf('Error storing reopen activity for issue %s in the database: %s', $issueID, $activity->getError()), JLog::INFO);
				$this->close();
			}
		}

		// Store was successful, update status
		JLog::add(sprintf('Added GitHub issue %s to the tracker.', $this->hookData->issue->number), JLog::INFO);

		return $issueID;
	}

	/**
	 * Method to update data for an issue from GitHub
	 *
	 * @param   integer  $id  The comment ID
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.0
	 */
	protected function updateComment($id)
	{
		// Try to render the comment with GitHub markdown
		try
		{
			$text = $this->github->markdown->render($this->hookData->comment->body, 'gfm', $this->project->gh_user . '/' . $this->project->gh_project);
		}
		catch (DomainException $e)
		{
			JLog::add(sprintf('Error parsing comment %s with GH Markdown: %s', $this->hookData->comment->id, $e->getMessage()), JLog::INFO);
			$this->close();
		}

		// Only update fields that may have changed, there's no API endpoint to show that so make some guesses
		$query = $this->db->getQuery(true);
		$query->update($this->db->quoteName('#__activity'));
		$query->set($this->db->quoteName('text') . ' = ' . $this->db->quote($text));
		$query->where($this->db->quoteName('id') . ' = ' . $id);

		try
		{
			$this->db->setQuery($query);
			$this->db->execute();
		}
		catch (RuntimeException $e)
		{
			JLog::add('Error updating the database for comment ' . $id . ':' . $e->getMessage(), JLog::INFO);
			$this->close();
		}

		// Store was successful, update status
		JLog::add(sprintf('Updated comment %s in the tracker.', $id), JLog::INFO);

		return true;
	}
}

JApplicationWeb::getInstance('TrackerReceiveComments')->execute();
