<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
$user  = JFactory::getUser();
// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add Stylesheets
$doc->addStyleSheet('templates/bugtrap/css/template.css');
$doc->addStyleSheet('templates/bugtrap/css/menus.css');

// Load optional rtl Bootstrap css and Bootstrap bugfixes
JHtmlBootstrap::loadCss(false, $this->direction);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<jdoc:include type="head" />
	<!--[if lt IE 9]>
		<script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>

<body class="site">
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container">
			<!--Begin Menu-->
				<ul id="menu">
					<li class="home_item">
						<a href="http://www.joomla.org/" class="drop">
							<span class="logo">Joomla!<span class="sup">®</span></span>
						</a>
						<!-- Begin Home Item -->
						<div class="dropdown_2columns">
							<!-- Begin 2 columns container -->
							<div class="col_2">
								<h2>Welcome to Joomla!</h2>
							</div>
							<div class="col_2">
								<p>Joomla! is an award-winning open source CMS for building powerful websites.</p>
							</div>
							<div class="col_2">
								<h2>Recent Joomla! News</h2>
							</div>
							<div class="col_2"> <img src="http://developer.joomla.org/templates/joomla12/images/menu/joomla.jpg" class="img_left imgshadow" width="70" height="70" alt="Joomla!">
								<ul class="img_left">
									<li><a href="http://www.joomla.org/announcements.html">Announcements</a></li>
									<li><a href="http://community.joomla.org/blogs/community.html">Joomla! Blogs</a></li>
									<li><a href="http://magazine.joomla.org/">Joomla! Magazine</a></li>
								</ul>
							</div>
							<div class="col_2">
								<h2>Support Joomla!</h2>
							</div>
							<div class="col_1">
								<ul class="greybox">
									<li class="blue"><a href="http://shop.joomla.org/">Shop Joomla! Gear</a></li>
								</ul>
							</div>
							<div class="col_1">
								<ul class="greybox">
									<li class="blue"><a href="http://opensourcematters.org/support-joomla.html">Contribution</a></li>
								</ul>
							</div>
						</div>
						<!-- End 2 columns container -->
					</li>
					<!-- End Home Item -->
					<li>
						<a href="http://www.joomla.org/about-joomla.html" class="drop">About</a>
						<!-- Begin 5 columns Item -->
						<div class="dropdown_4columns">
							<!-- Begin 5 columns container -->
							<div class="col_4">
								<h2>What Is Joomla?</h2>
								<p>Joomla is an award-winning content management system (CMS), which enables you to build Web sites and powerful online applications.<br><a href="http://www.joomla.org/about-joomla.html">Read more...</a></p>
							</div>
							<div class="col_2">
								<h3>Joomla! for Business</h3>
								<p>Joomla! is a extremely customizable and adaptable for Enterprise, SMBs, NPOs and beyond. <a href="http://www.joomla.org/core-features.html">Read more...</a></p>
							</div>
							<div class="col_2">
								<h3>Joomla! for Developers</h3>
								<p class="black_box">No matter what level of experience you hold, Joomla! has strengths to match; programmers, designers and end users alike!<a href="http://developer.joomla.org/">Read more...</a></p>
							</div>
							<div class="col_4">
								<h2>Learn more about Joomla!</h2>
							</div>
							<div class="col_1">
								<ul class="greybox">
									<li><a href="http://www.joomla.org/about-joomla.html">The Software</a></li>
								</ul>
							</div>
							<div class="col_1">
								<ul class="greybox">
									<li><a href="http://www.joomla.org/about-joomla/the-project.html">The Project</a></li>
								</ul>
							</div>
							<div class="col_1">
								<ul class="greybox">
									<li><a href="http://www.joomla.org/about-joomla/the-project/leadership-team.html">The Leadership</a></li>
								</ul>
							</div>
							<div class="col_1">
								<ul class="greybox">
									<li><a href="http://opensourcematters.org/index.php">Open Source Matters</a></li>
								</ul>
							</div>
						</div>
						<!-- End 5 columns container -->
					</li>
					<!-- End 5 columns Item -->
					<li>
						<a href="http://community.joomla.org/" class="drop">Community &amp; Support</a>
						<!-- Begin 4 columns Item -->
						<div class="dropdown_3columns">
							<!-- Begin 4 columns container -->
							<div class="col_1">
								<h3>Connect!</h3>
								<ul>
									<li><a href="http://people.joomla.org/">Joomla! People Site</a></li>
									<li><a href="http://events.joomla.org/">Joomla! Events</a></li>
									<li><a href="http://ux.joomla.org/">Joomla! UX</a></li>
									<li><a href="http://community.joomla.org/user-groups.html">Joomla! User Groups</a></li>
								</ul>
							</div>
							<div class="col_1">
								<h3>Support!</h3>
								<ul>
									<li><a href="http://forum.joomla.org/">Joomla! Forum</a></li>
									<li><a href="http://docs.joomla.org/">Joomla! Documentation</a></li>
									<li><a href="http://resources.joomla.org/">Joomla! Resources</a></li>
								</ul>
							</div>
							<div class="col_1">
								<h3>Read!</h3>
								<ul>
									<li><a href="http://magazine.joomla.org/">Joomla! Magazine</a></li>
									<li><a href="http://community.joomla.org/connect.html">Joomla! Connect</a></li>
									<li><a href="http://www.joomla.org/mailing-lists.html">Joomla! Mailing Lists</a></li>
								</ul>
							</div>
							<div class="col_3">
								<h2>Get involved with Joomla!</h2> <img src="http://developer.joomla.org/templates/joomla12/images/menu/joomla.jpg" alt="Joomla!" width="70" height="70" class="img_left imgshadow">
								<p>Joomla is an open source project and contributions from the community are essential to its growth and success. Anyone can contribute on any level, even newcomers can contribute to Joomla. <a href="http://www.joomla.org/about-joomla/contribute-to-joomla.html">Read more...</a></p>
							</div>
						</div>
						<!-- End 4 columns container -->
					</li>
					<!-- End 4 columns Item -->
					<li>
						<a href="http://extensions.joomla.org/" class="drop">Extend</a>
						<!-- Begin 4 columns Item -->
						<div class="dropdown_2columns">
							<!-- Begin 4 columns container -->
							<div class="col_1">
								<h3>Directories</h3>
								<ul>
									<li><a href="http://extensions.joomla.org/">Extension Directory</a></li>
									<li><a href="http://community.joomla.org/showcase/">Showcase Directory</a></li>
									<li><a href="http://resources.joomla.org/">Resource Directory</a></li>
									<li><a href="http://community.joomla.org/translations.html">Translations</a></li>
									<li><a href="http://ideas.joomla.org/">Idea Pool</a></li>
								</ul>
							</div>
							<div class="col_1">
								<h3>Developers</h3>
								<ul>
									<li><a href="http://developer.joomla.org/">Developer Site</a></li>
									<li><a href="http://docs.joomla.org/">Documentation</a></li>
									<li><a href="http://docs.joomla.org/Bug_Squad">Joomla! Bug Squad</a></li>
									<li><a href="http://api.joomla.org/">Joomla! API</a></li>
									<li><a href="http://joomlacode.org/">JoomlaCode</a></li>
									<li><a href="https://github.com/joomla/joomla-platform">Joomla! Platform</a></li>
								</ul>
							</div>
						</div>
						<!-- End 4 columns container -->
					</li>
					<!-- End 4 columns Item -->
					<li class="download">
						<a href="http://www.joomla.org/download.html" class="drop">Download</a>
						<!-- Begin 3 columns Item -->
						<div class="dropdown_3columns">
							<!-- Begin 3 columns container -->
							<div class="col_3">
								<h2>Download Joomla! 2.5</h2>
							</div>
							<div class="col_1">
								<ul class="greybox">
									<li class="orange"><a href="http://www.joomla.org/download.html">2.5 Full Package</a></li>
								</ul>
							</div>
							<div class="col_1">
								<ul class="greybox">
									<li><a href="http://joomlacode.org/gf/project/joomla/frs/?action=index">Update Packages</a></li>
								</ul>
							</div>
							<div class="col_1">
								<ul class="greybox">
									<li><a href="http://demo.joomla.org/">Demo Joomla! 2.5</a></li>
								</ul>
							</div>
							<div class="col_3">
								<h2>Download Joomla! 3.0 </h2>
							</div>
							<div class="col_1">
								<ul class="greybox">
									<li class="blue"><a href="http://www.joomla.org/download.html#j3">3.0 Full Package</a></li>
								</ul>
							</div>
							<div class="col_1">
								<ul class="greybox">
									<li><a href="http://joomlacode.org/gf/project/joomla/frs/?action=index">Update Packages</a></li>
								</ul>
							</div>
							<div class="col_1">
								<ul class="greybox">
									<li><a href="http://demo.joomla.org/">Demo Joomla! 3.0</a></li>
								</ul>
							</div>
							<div class="col_3">
								<h2>Getting Started with Joomla!</h2>
							</div>
							<div class="col_3">
								<ul class="nowrap">
									<li><a href="http://docs.joomla.org/Beginners">Beginner Documentation</a></li>
									<li><a href="http://www.joomla.org/technical-requirements.html">Technical Requirements</a></li>
									<li><a href="http://opensourcematters.org/index.php?option=com_content&amp;view=article&amp;id=56&amp;Itemid=155">License &amp; Usage</a></li>
								</ul>
							</div>
						</div>
						<!-- End 3 columns container -->
					</li>
					<li class="menu_right language">
						<a href="http://community.joomla.org/translations.html" class="drop language_link">Internationalization</a>
						<!-- Begin 3 columns Item -->
						<div class="dropdown_1column align_right">
							<!-- Begin 3 columns container -->
							<div class="col_1">
								<h2>Internationalization</h2>
							</div>
							<div class="col_1">
								<ul class="nowrap">
									<li><a href="http://community.joomla.org/translations.html">Translations</a></li>
									<li><a href="http://multilingual-joomla-demo.cloudaccess.net/">Multilingual Demo</a></li>
									<li><a href="http://docs.joomla.org/Translations_Working_Group">Translation Working Group</a></li>
									<li><a href="http://forum.joomla.org/viewforum.php?f=11">Translations Forum</a></li>
								</ul>
							</div>
						</div>
						<!-- End 3 columns container -->
					</li>
					<!-- End 3 columns Item -->
				</ul>
			<!--End Menu-->
			</div>
		</div>
	</div>
	<!-- Header -->
	<div id="header">
		<div class="container">
			<div class="row">
				<div id="title" class="span12 clearfix">
					<div class="row">
						<div class="span7">
							<h1 title="Joomla! CMS Issue Tracker">Joomla! CMS Issue Tracker</h1>
						</div>
						<div class="span5 hidden-phone">
							<div class="downdemo pull-right">
								<p class="download-16">
									<a href="http://www.joomla.org/download.html">Download</a>
								</p>
								<p class="demo">
									<a href="http://demo.joomla.org">Demo</a>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Site Menu -->
	<div class="navbar navbar-inverse">
		<div class="navbar-inner">
			<div id="inset" class="container">
			<!--Begin Menu-->
				<ul class="nav">
					<li><a href="<?php echo JRoute::_('index.php'); ?>">Tracker</a></li>
					<li>
					<?php if ($user->id) :  ?>
						<a href="<?php echo JRoute::_('index.php?option=com_users') ?>"><?php echo $user->name; ?></a>
					<?php else : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_users') ?>">Login</a>
					<?php endif; ?>
					</li>
				</ul>
				<div class="pull-right">
					<jdoc:include type="modules" name="toolbar" style="no" />
				</div>
			<!--End Menu-->
			</div>
		</div>
	</div>
	<!-- Body -->
	<div class="body">
		<div class="container">
			<div class="row-fluid">
				<div id="content" class="span12">
					<!-- Begin Content -->
					<jdoc:include type="message" />
					<jdoc:include type="component" />
					<!-- End Content -->
				</div>
			</div>
		</div>
	</div>
	<!-- Footer -->
	<div class="footer">
		<div class="container-fluid">
			<hr />
			<p class="pull-right"><a href="#top" id="back-top"><?php echo JText::_('TPL_BUGTRAP_BACKTOTOP'); ?></a></p>
		</div>
	</div>
</body>
</html>
