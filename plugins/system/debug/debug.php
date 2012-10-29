<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Debug
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Joomla! Debug plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  System.Debug
 * @since       1.5
 */
class PlgSystemDebug extends JPlugin
{
	protected $linkFormat = '';

	/**
	 * True if debug lang is on.
	 *
	 * @var    boolean
	 * @since  3.0
	 */
	protected $debugLang = false;

	protected $menu = array();

	protected $content = array();

	protected $javascript = array();

	protected $selectQueryTypeTicker = array();

	protected $otherQueryTypeTicker = array();

	protected $logEntries = array();

	protected $filterTables = array();

	/**
	 * Constructor.
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An array that holds the plugin configuration
	 *
	 * @since 1.5
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$this->linkFormat = ini_get('xdebug.file_link_format');
		$this->debugLang  = JFactory::getApplication()->getCfg('debug_lang');

		JLog::addLogger(array('logger' => 'callback', 'callback' => array($this, 'addLogEntry')));

		// Log the deprecated API.
		if ($this->params->get('log-deprecated'))
		{
			// JLog::addLogger(array('text_file' => 'deprecated.php'), JLog::ALL, array('deprecated'));
		}

		// Only if debugging or language debug is enabled
		if (JDEBUG || $this->debugLang)
		{
			JFactory::getConfig()->set('gzip', 0);

			/*
						JHtmlJquery::ui();

						JFactory::getDocument()->addScriptDeclaration(
							"jQuery(document).ready(function($) {
							$('#ecrDebugBoxConsole').resizable({
								modifiers: {x: false, y: 'height'},
								limit: {y: [1, 600]},
								invert: true,
								handle: 'pollStatusGrip'
							});
						});");
			*/

			// 'issues', 'tracker_fields_values');
			$this->filterTables = array();

			ob_start();
			ob_implicit_flush(false);
		}
	}

	/**
	 * Log a deprecated function call.
	 *
	 * @param   JLogEntry  $entry  The log entry.
	 *
	 * @return void
	 */
	public function addLogEntry(JLogEntry $entry)
	{
		if ('deprecated' == $entry->category)
		{
			$trace         = debug_backtrace();
			$caller        = new JRegistry($trace[6]);
			$entry->caller = $caller->get('class') . $caller->get('type') . $caller->get('function') . '()';
		}

		$this->logEntries[$entry->category][] = $entry;
	}

	/**
	 * Add the CSS for debug. We can't do this in the constructor because stuff breaks.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	public function onBeforeCompileHead()
	{
		// Only if debugging or language debug is enabled
		if ((JDEBUG || $this->debugLang) && $this->isAuthorisedDisplayDebug())
		{
			JHtml::_('stylesheet', 'cms/debug.css', array(), true);
		}
	}

	/**
	 * Show the debug info.
	 *
	 * @since  1.6
	 */
	public function __destruct()
	{
		// Do not render if debugging or language debug is not enabled
		if (!JDEBUG && !$this->debugLang)
		{
			return;
		}

		// User has to be authorised to see the debug information
		if (false === $this->isAuthorisedDisplayDebug())
		{
			return;
		}

		// Only render for HTML output
		if ('html' !== JFactory::getDocument()->getType())
		{
			return;
		}

		// Capture output
		$contents = ob_get_contents();

		if (ob_get_level())
		{
			ob_end_clean();
		}

		// No debug for Safari and Chrome redirection
		if (
			false !== strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'webkit')
			&& substr($contents, 0, 50) == '<html><head><meta http-equiv="refresh" content="0;')
		{
			echo $contents;

			return;
		}

		// Load language
		$this->loadLanguage();

		$this->javascript[] = 'var buttons = [];';

		if (JDEBUG)
		{
			if (1) // !$this->params->get('log-deprecated'))
			{
				// @todo "old" errors - log only if not in deprecation mode
				$errors = JError::getErrors();

				if ($errors)
				{
					$this->process('errors', $errors);
				}
			}

			$this->process('session');

			if ($this->params->get('profile', 1))
			{
				$this->process('profile');
			}

			if ($this->params->get('queries', 1))
			{
				$this->process('queries');
				$this->process('query_types');
			}

			if ($this->params->get('log-deprecated'))
			{
				$entries = (isset($this->logEntries['deprecated']) ? $this->logEntries['deprecated'] : array());

				if ($entries)
				{
					$this->process('log_deprecated', $entries);
				}
			}
		}

		if ($this->debugLang)
		{
			if ($this->params->get('language_errorfiles', 1))
			{
				$this->process('language_errorfiles', JFactory::getLanguage()->getErrorFiles());
			}

			if ($this->params->get('language_files', 1))
			{
				$this->process('language_files');
			}

			if ($this->params->get('language_strings', 1))
			{
				$this->process('language_strings', JFactory::getLanguage()->getOrphans());
			}
		}

		$i = 1;

		foreach ($this->logEntries as $k => $entries)
		{
			if ('databasequery' == $k || 'deprecated' == $k)
			{
				continue;
			}

			$this->process('user_log', $entries, 'user-' . $i);

			$i++;
		}

		$output = array();

		$output[] = '<div id="jdebug-container" class="resizable navbar">';

		$activePane = JFactory::getApplication()->input->cookie->get('jdebug_activepane');

		$output[] = '<div class="jdebug-pane">';
		$output[] = implode("\n", $this->content);
		$output[] = '</div>';

		$output[] = '<div class="jdebug-menu btn-group">';

		// 'dbgChangeOrientation(jQuery);';
		$js       = '';

		$output[] = '<a class="btn btn-mini btn-inverse" href="javascript:;" onclick="' . $js . '"><tt>JDebug ::</tt></a>';

		// $output[] = '<a class="btn btn-mini btn-inverse disabled"><tt>JDebug ::</tt></a>';

		$output[] = implode("\n", $this->menu);
		$output[] = '</div>';

		$output[] = '</div>';

		$this->javascript[] = "function jdbg_toggle_pane(id) {
			buttons.forEach(function(item) {
				jQuery('#dbgContainer-'+item).css('display', 'none');
				jQuery('#dbgButton-'+item).removeClass('active');
				//jQuery('.jdebug-button').each(function(i, e){ e.removeClass('active'); });
			});

			jQuery('#dbgContainer-'+id).css('display', 'block');
			jQuery('#dbgButton-'+id).addClass('active');

			document.cookie = 'jdebug_activepane=' + id;
			//jQuery.cookie('jdebug_activepane', id);
			//Cookie.write('jdebug_activepane',id);
		}

		var dbgOrientation = 'b';

		function dbgChangeOrientation($) {
			switch(dbgOrientation)
			{
				case 'b' :
					dbgOrientation = 'l';
					$('#jdebug-container').css('width', '200px');
					$('.jdebug-pane').css('height', '100%');
					$('.jdebug-pane').css('overflow', 'scroll');
				break;

				default :
					dbgOrientation = 'b';
					$('#jdebug-container').css('width', '100%');
					$('.jdebug-pane').css('height', '160px');
					$('.jdebug-pane').css('overflow-y', 'scroll');

			}
		}";

		if ($activePane)
		{
			// Set the active pane from a cookie
			$this->javascript[] = "jQuery(document).ready(function() { jdbg_toggle_pane('{$activePane}'); });";
		}

		$output[] = '<script>' . implode("\n", $this->javascript) . '</script>';

		echo str_replace('</body>', implode("\n", $output) . '</body>', $contents);
	}

	/**
	 * Process an action.
	 *
	 * @param   string  $action  The action to perform.
	 * @param   mixed   $errors  Errors that happened..
	 * @param   string  $id      HTML id.
	 *
	 * @return void
	 */
	protected function process($action, $errors = false, $id = '')
	{
		$id                 = ($id) ? : $action;
		$this->menu[]       = $this->getMenuItem($action, $errors, $id);
		$this->content[]    = $this->display($action, $errors, $id);
		$this->javascript[] = 'buttons.push("' . $id . '");';
	}

	/**
	 * Get a menu entry.
	 *
	 * @param   string         $item    The item name.
	 * @param   array|boolean  $errors  Errors that happened..
	 * @param   string         $id      HTML id.
	 *
	 * @return string
	 */
	protected function getMenuItem($item, $errors = false, $id = '')
	{
		$html = array();

		$id = ($id) ? : $item;

		$title = JText::_('PLG_DEBUG_' . strtoupper($item));

		$class = ($errors) ? ' btn-warning' : ' btn-inverse';

		$js = "jdbg_toggle_pane('" . $id . "', this);";

		$html[] = '<a class="jdebug-button btn-mini btn' . $class . '" id="dbgButton-' . $id . '" onclick="' . $js . '" href="javascript:;">' . $title . '</a>';

		return implode("\n", $html);
	}

	/**
	 * General display method.
	 *
	 * @param   string      $item    The item to display
	 * @param   array|bool  $errors  Errors occurred during execution
	 * @param   string      $id      HTML id.
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	protected function display($item, $errors = false, $id = '')
	{
		$html = array();

		$id = ($id) ? : $item;

		$fncName = 'display' . ucfirst(str_replace('_', '', $item));

		if (false == method_exists($this, $fncName))
		{
			return __METHOD__ . ' -- Unknown method: ' . $fncName . '<br />';
		}

		// @todo set with js.. ?
		$style = ' style="display: none;"';

		$html[] = '<div ' . $style . ' class="dbgContainer" id="dbgContainer-' . $id . '">';
		$html[] = $this->$fncName($errors);
		$html[] = '</div>';

		return implode("\n", $html);
	}

	/**
	 * Method to check if the current user is allowed to see the debug information or not.
	 *
	 * @return  boolean  True is access is allowed
	 *
	 * @since   3.0
	 */
	private function isAuthorisedDisplayDebug()
	{
		static $result = null;

		if (!is_null($result))
		{
			return $result;
		}

		// If the user is not allowed to view the output then end here
		$filterGroups = (array) $this->params->get('filter_groups', null);

		if (!empty($filterGroups))
		{
			$userGroups = JFactory::getUser()->get('groups');

			if (!array_intersect($filterGroups, $userGroups))
			{
				$result = false;

				return false;
			}
		}

		$result = true;

		return true;
	}

	/**
	 * Display session information.
	 *
	 * Called recursive.
	 *
	 * @param   string  $key      A session key
	 * @param   mixed   $session  The session array, initially null
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	protected function displaySession($key = '', $session = null)
	{
		if (!$session)
		{
			$session = $_SESSION;
		}

		static $html = '';

		if (!is_array($session))
		{
			$html .= $key . ' &rArr;' . $session . PHP_EOL;
		}
		else
		{
			foreach ($session as $sKey => $entries)
			{
				$display = true;

				if (is_array($entries) && $entries)
				{
					$display = false;
				}

				$className = '';

				if (is_object($entries))
				{
					$o = JArrayHelper::fromObject($entries);

					$className = get_class($entries);

					if ($o)
					{
						$tmp = array();

						foreach (array_keys($o) as $k)
						{
							$tmp[$k] = $entries->$k;
						}

						$entries = $tmp;
						$display = false;
					}
					else
					{
						if (!method_exists($entries, '__toString'))
						{
							$x       = get_class($entries);
							$x       = $x ? : 'unknown';
							$entries = '<em>' . $x . ' object</em>';
						}
					}
				}

				if (!$display)
				{
					$js = "this.nextSibling.style.display = (this.nextSibling.style.display == 'none') ? 'block' : 'none'";

					$html .= '<div class="dbgHeader" onclick="' . $js . '"><a href="javascript:void(0);"><h3>' . $sKey
						. ($className ? ' (' . $className . ')' : '')
						. '</h3></a></div>';

					// @todo set with js.. ?
					$style = ' style="display: none;"';

					$html .= '<div ' . $style . ' class="dbgContainer">';

					// Recurse...
					$this->displaySession($sKey, $entries);

					$html .= '</div>';

					continue;
				}

				if (is_array($entries))
				{
					$entries = implode($entries);
				}

				$html .= '<code>';
				$html .= $sKey . ' &rArr; ' . $entries . '<br />';
				$html .= '</code>';
			}
		}

		return $html;
	}

	/**
	 * Display errors.
	 *
	 * @return  string
	 *
	 * @deprecated since JError is deprecated
	 *
	 * @since   2.5
	 */
	protected function displayErrors()
	{
		$html = '';

		$html .= '<ol>';

		while ($error = JError::getError(true))
		{
			$col = (E_WARNING == $error->get('level')) ? 'red' : 'orange';

			$html .= '<li>';
			$html .= '<b style="color: ' . $col . '">' . $error->getMessage() . '</b><br />';

			$info = $error->get('info');

			if ($info)
			{
				$html .= '<pre>' . print_r($info, true) . '</pre><br />';
			}

			$html .= $this->renderBacktrace($error);
			$html .= '</li>';
		}

		$html .= '</ol>';

		return $html;
	}

	/**
	 * Display profile information.
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	protected function displayProfile()
	{
		$bytes = memory_get_usage();

		$html = array();

		$html[] = '<code>';
		$html[] = JHtml::_('number.bytes', $bytes);
		$html[] = ' (' . number_format($bytes) . ' Bytes)';
		$html[] = '</code>';

		foreach (JProfiler::getInstance('Application')->getBuffer() as $mark)
		{
			$html[] = '<div>' . $mark . '</div>';
		}

		return implode("\n", $html);
	}

	/**
	 * Display logged queries.
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	protected function displayQueries()
	{
		$db = JFactory::getDbo();

		$log = $db->getLog();

		if (!$log)
		{
			return '';
		}

		$html = '';
		$cnt  = 0;

		$html .= '<ol>';

		$selectQueryTypeTicker = array();
		$otherQueryTypeTicker  = array();

		$pattern = '/' . JFactory::getDbo()->getPrefix() . '([a-z_0-9]+)/';

		foreach ($log as $sql)
		{
			if ($this->filterTables && preg_match($pattern, $sql, $matches))
			{
				if (false == in_array($matches[1], $this->filterTables))
				{
					continue;
				}
			}

			// Start Query Type Ticker Additions
			$fromStart  = stripos($sql, 'from');
			$whereStart = stripos($sql, 'where', $fromStart);

			if ($whereStart === false)
			{
				$whereStart = stripos($sql, 'order by', $fromStart);
			}

			if ($whereStart === false)
			{
				$whereStart = strlen($sql) - 1;
			}

			$fromString = substr($sql, 0, $whereStart);
			$fromString = str_replace("\t", " ", $fromString);
			$fromString = str_replace("\n", " ", $fromString);
			$fromString = trim($fromString);

			// Initialize the select/other query type counts the first time:
			if (!isset($selectQueryTypeTicker[$fromString]))
			{
				$selectQueryTypeTicker[$fromString] = 0;
			}

			if (!isset($otherQueryTypeTicker[$fromString]))
			{
				$otherQueryTypeTicker[$fromString] = 0;
			}

			// Increment the count:
			if (stripos($sql, 'select') === 0)
			{
				$selectQueryTypeTicker[$fromString] = $selectQueryTypeTicker[$fromString] + 1;
				unset($otherQueryTypeTicker[$fromString]);
			}
			else
			{
				$otherQueryTypeTicker[$fromString] = $otherQueryTypeTicker[$fromString] + 1;
				unset($selectQueryTypeTicker[$fromString]);
			}

			$text = $this->highlightQuery($sql);

			$html .= '<li><code>' . $text . '</code></li>';

			$cnt++;
		}

		$html .= '</ol>';

		$html = '<h4>' . JText::sprintf('PLG_DEBUG_QUERIES_LOGGED', $cnt) . '</h4>'
			. $html;

		$this->selectQueryTypeTicker = $selectQueryTypeTicker;
		$this->otherQueryTypeTicker  = $otherQueryTypeTicker;

		return $html;
	}

	/**
	 * Display logged queries.
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	protected function displayQueryTypes()
	{
		$html                  = '';
		$selectQueryTypeTicker = $this->selectQueryTypeTicker;
		$otherQueryTypeTicker  = $this->otherQueryTypeTicker;

		if (!$this->params->get('query_types', 1))
		{
			return $html;
		}

		// Get the totals for the query types:
		$totalSelectQueryTypes = count($selectQueryTypeTicker);
		$totalOtherQueryTypes  = count($otherQueryTypeTicker);
		$totalQueryTypes       = $totalSelectQueryTypes + $totalOtherQueryTypes;

		$html .= '<h4>' . JText::sprintf('PLG_DEBUG_QUERY_TYPES_LOGGED', $totalQueryTypes) . '</h4>';

		if ($totalSelectQueryTypes)
		{
			$html .= '<h5>' . JText::sprintf('PLG_DEBUG_SELECT_QUERIES') . '</h5>';

			arsort($selectQueryTypeTicker);

			$html .= '<ol>';

			foreach ($selectQueryTypeTicker as $query => $occurrences)
			{
				$html .= '<li><code>'
					. JText::sprintf('PLG_DEBUG_QUERY_TYPE_AND_OCCURRENCES', $this->highlightQuery($query), $occurrences)
					. '</code></li>';
			}

			$html .= '</ol>';
		}

		if ($totalOtherQueryTypes)
		{
			$html .= '<h5>' . JText::sprintf('PLG_DEBUG_OTHER_QUERIES') . '</h5>';

			arsort($otherQueryTypeTicker);

			$html .= '<ol>';

			foreach ($otherQueryTypeTicker as $query => $occurrences)
			{
				$html .= '<li><code>'
					. JText::sprintf('PLG_DEBUG_QUERY_TYPE_AND_OCCURRENCES', $this->highlightQuery($query), $occurrences)
					. '</code></li>';
			}
			$html .= '</ol>';
		}

		return $html;
	}

	/**
	 * Display the deprecated log.
	 *
	 * @param   array  $entries  Log entries.
	 *
	 * @return string
	 */
	protected function displayLogDeprecated($entries)
	{
		$html = array();

		$html[] = '<ul class="unstyled">';

		/* @var JLogEntry $entry */
		foreach ($entries as $entry)
		{
			$caller = (isset($entry->caller)) ? ' Called in <tt>' . $entry->caller . '</tt>' : '';
			$html[] = '<li><tt style="color: orange;">' . $entry->message . '</tt>' . $caller . '</li>';
		}

		$html[] = '</ul>';

		return implode("\n", $html);
	}

	/**
	 * Display the deprecated log.
	 *
	 * @param   array  $entries  Log entries.
	 *
	 * @return string
	 */
	protected function displayUserLog($entries)
	{
		$html = array();

		$html[] = '<ul class="unstyled">';

		/* @var JLogEntry $entry */
		foreach ($entries as $entry)
		{
			$caller = (isset($entry->caller)) ? ' Called in <tt>' . $entry->caller . '</tt>' : '';
			$html[] = '<li><tt style="color: orange;">' . $entry->message . '</tt>' . $caller . '</li>';
		}

		$html[] = '</ul>';

		return implode("\n", $html);
	}

	/**
	 * Displays errors in language files.
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	protected function displayLanguageErrorfiles()
	{

		$errorfiles = JFactory::getLanguage()->getErrorFiles();

		if (!count($errorfiles))
		{
			return '<p style="color: lime;">' . JText::_('JNONE') . '</p>';
		}

		$html = array();

		$html[] = '<ul class="unstyled">';

		foreach ($errorfiles as $file => $error)
		{
			$html[] = '<li>' . $this->formatLink($file) . str_replace($file, '', $error) . '</li>';
		}

		$html[] = '</ul>';

		return implode("\n", $html);
	}

	/**
	 * Display loaded language files.
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	protected function displayLanguageFiles()
	{
		$html = array();

		$html[] = '<table style="width: 100%">';
		$html[] = '<tr>';

		$html[] = '<th>Extension</th>';
		$html[] = '<th><span class="label label-white">' . JText::_('PLG_DEBUG_LANG_LOADED') . '</span></th>';
		$html[] = '<th><span class="label label-warning">' . JText::_('PLG_DEBUG_LANG_NOT_LOADED') . '</span></th>';
		$html[] = '</tr>';

		foreach (JFactory::getLanguage()->getPaths() as $extension => $files)
		{
			$e = '';

			foreach ($files as $file => $status)
			{
				if (!$e)
				{
					$html[] = '<tr style="border-top: 1px solid silver;">';
					$html[] = '<td>' . $extension . '</td>';

					$e = $extension;
				}
				else
				{
					$html[] = '<tr>';
					$html[] = '<td>&nbsp;</td>';
				}

				$html[] = ($status)
					? '<td><span class="label label-white">' . $this->formatLink($file) . '</span></td><td>&nbsp;</td>'
					: '<td>&nbsp;</td><td><span class="label label-warning">' . $this->formatLink($file, '', false) . '</span></td>';
			}

			$html[] = '</tr>';
		}

		$html[] = '</table>';

		return implode("\n", $html);
	}

	/**
	 * Display untranslated language strings.
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	protected function displayLanguageStrings()
	{
		$stripFirst = $this->params->get('strip-first');
		$stripPref  = $this->params->get('strip-prefix');
		$stripSuff  = $this->params->get('strip-suffix');

		$orphans = JFactory::getLanguage()->getOrphans();

		$html = '';

		if (!count($orphans))
		{
			$html .= '<p>' . JText::_('JNONE') . '</p>';

			return $html;
		}

		ksort($orphans, SORT_STRING);

		$guesses = array();

		foreach ($orphans as $key => $occurance)
		{
			if (is_array($occurance) && isset($occurance[0]))
			{
				$info = $occurance[0];
				$file = ($info['file']) ? $info['file'] : '';

				if (!isset($guesses[$file]))
				{
					$guesses[$file] = array();
				}

				// Prepare the key

				if (($pos = strpos($info['string'], '=')) > 0)
				{
					$parts = explode('=', $info['string']);
					$key   = $parts[0];
					$guess = $parts[1];
				}
				else
				{
					$guess = str_replace('_', ' ', $info['string']);

					if ($stripFirst)
					{
						$parts = explode(' ', $guess);
						if (count($parts) > 1)
						{
							array_shift($parts);
							$guess = implode(' ', $parts);
						}
					}

					$guess = trim($guess);

					if ($stripPref)
					{
						$guess = trim(preg_replace(chr(1) . '^' . $stripPref . chr(1) . 'i', '', $guess));
					}

					if ($stripSuff)
					{
						$guess = trim(preg_replace(chr(1) . $stripSuff . '$' . chr(1) . 'i', '', $guess));
					}
				}

				$key = trim(strtoupper($key));
				$key = preg_replace('#\s+#', '_', $key);
				$key = preg_replace('#\W#', '', $key);

				// Prepare the text
				$guesses[$file][] = $key . '="' . $guess . '"';
			}
		}

		foreach ($guesses as $file => $keys)
		{
			$html .= "\n\n# " . ($file ? $this->formatLink($file) : JText::_('PLG_DEBUG_UNKNOWN_FILE')) . "\n\n";
			$html .= implode("\n", $keys);
		}

		return '<pre>' . $html . '</pre>';
	}

	/**
	 * Simple highlight for SQL queries.
	 *
	 * @param   string  $sql  The query to highlight
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	protected function highlightQuery($sql)
	{
		$newlineKeywords = '#\b(FROM|LEFT|INNER|OUTER|WHERE|SET|VALUES|ORDER|GROUP|HAVING|LIMIT|ON|AND|CASE)\b#i';

		$sql = htmlspecialchars($sql, ENT_QUOTES);

		$sql = preg_replace($newlineKeywords, '<br />&#160;&#160;\\0', $sql);

		$regex = array(

			// Tables are identified by the prefix
			'/(=)/'
			=> '<b class="dbgOperator">$1</b>',

			// All uppercase words have a special meaning
			'/(?<!\w|>)([A-Z_]{2,})(?!\w)/x'
			=> '<span class="dbgCommand">$1</span>',

			// Tables are identified by the prefix
			'/(' . JFactory::getDbo()->getPrefix() . '[a-z_0-9]+)/'
			=> '<span class="dbgTable">$1</span>'

		);

		$sql = preg_replace(array_keys($regex), array_values($regex), $sql);

		$sql = str_replace('*', '<b style="color: red;">*</b>', $sql);

		return $sql;
	}

	/**
	 * Render the backtrace.
	 *
	 * Stolen from JError to prevent it's removal.
	 *
	 * @param   integer  $error  The error
	 *
	 * @return  string  Contents of the backtrace
	 *
	 * @since   2.5
	 */
	protected function renderBacktrace($error)
	{
		$backtrace = $error->getTrace();

		$html = '';

		if (is_array($backtrace))
		{
			$j = 1;

			$html .= '<table cellpadding="0" cellspacing="0">';

			$html .= '<tr>';
			$html .= '<td colspan="3"><strong>Call stack</strong></td>';
			$html .= '</tr>';

			$html .= '<tr>';
			$html .= '<th>#</th>';
			$html .= '<th>Function</th>';
			$html .= '<th>Location</th>';
			$html .= '</tr>';

			for ($i = count($backtrace) - 1; $i >= 0; $i--)
			{
				$link = '&#160;';

				if (isset($backtrace[$i]['file']))
				{
					$link = $this->formatLink($backtrace[$i]['file'], $backtrace[$i]['line']);
				}

				$html .= '<tr>';
				$html .= '<td>' . $j . '</td>';

				if (isset($backtrace[$i]['class']))
				{
					$html .= '<td>' . $backtrace[$i]['class'] . $backtrace[$i]['type'] . $backtrace[$i]['function'] . '()</td>';
				}
				else
				{
					$html .= '<td>' . $backtrace[$i]['function'] . '()</td>';
				}

				$html .= '<td>' . $link . '</td>';

				$html .= '</tr>';
				$j++;
			}

			$html .= '</table>';
		}

		return $html;
	}

	/**
	 * Replaces the Joomla! root with "JROOT" to improve readability.
	 * Formats a link with a special value xdebug.file_link_format
	 * from the php.ini file.
	 *
	 * @param   string   $file        The full path to the file.
	 * @param   string   $line        The line number.
	 * @param   boolean  $createLink  Create a clickable link or just display the file.
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	protected function formatLink($file, $line = '', $createLink = true)
	{
		$link = str_replace(JPATH_ROOT, 'JROOT', $file);
		$link .= ($line) ? ':' . $line : '';

		if ($this->linkFormat)
		{
			$href = $this->linkFormat;
			$href = str_replace('%f', $file, $href);
			$href = str_replace('%l', $line, $href);

			$html = ($createLink)
				? '<a href="' . $href . '">' . $link . '</a>'
				: $link;
		}
		else
		{
			$html = $link;
		}

		return $html;
	}

}
