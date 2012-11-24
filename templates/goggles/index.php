<?php
/*** @copyright Copyright Pixel Praise LLC © 2012. All rights reserved. */
// no direct access
defined('_JEXEC') or die;

$app   = JFactory::getApplication();
$doc   = JFactory::getDocument();
$input = $app->input;
$user  = JFactory::getUser();

// Detecting Active Variables
$option = $input->getCmd('option', '');
$view   = $input->getCmd('view', '');
$layout = $input->getCmd('layout', '');
$task   = $input->getCmd('task', '');

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add Template Stylesheet
$doc->addStyleSheet('templates/' . $this->template . '/css/template.css');

$showSubmenu = false;
$this->submenumodules = JModuleHelper::getModules('submenu');
foreach ($this->submenumodules as $submenumodule)
{
	$output = JModuleHelper::renderModule($submenumodule);
	if (strlen($output))
	{
		$showSubmenu = true;
		break;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<jdoc:include type="head" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<style type="text/css">
		.navbar-inverse .navbar-inner{
			background: #13294A;
		}
		.sidebar-nav h3{
			color: #13294A;
		}
	</style>
</head>

<body class="site <?php echo $option . " view-" . $view . " layout-" . $layout . " task-" . $task;?>">
	<!-- Top Navigation -->
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="<?php echo $this->baseurl; ?>">Joomla! CMS Issue Tracker</a>

				<div class="nav-collapse">
					<jdoc:include type="modules" name="position-1" style="none" />
					<ul class="nav">
						<li <?php if ($option == 'com_tracker') echo 'class="active"'; ?>><a href="<?php echo JRoute::_('index.php?option=com_tracker'); ?>">Tracker</a></li>
						<li <?php if ($option == 'com_users') echo 'class="active"'; ?>><a href="<?php echo JRoute::_('index.php?option=com_users'); ?>">Users</a></li>
					</ul>
					<?php if ($user->id) : ?>
					<ul class="nav pull-right">
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<?php echo $user->name; ?> <b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a href="<?php echo JRoute::_('index.php?option=com_users&view=profile');?>">
										<?php echo JText::_('TPL_GOGGLES_PROFILE');?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="<?php echo JRoute::_('index.php?option=com_users&task=logout&'. JSession::getFormToken() .'=1');?>">
										<?php echo JText::_('TPL_GOGGLES_LOGOUT');?>
									</a>
								</li>
							</ul>
						</li>
					</ul>
					<?php endif; ?>
				</div>
				<!--/.nav-collapse -->
			</div>
		</div>
	</div>
	<!-- Header -->
	<div class="header">
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span2">
					<a class="logo" href="<?php echo $this->baseurl; ?>"></a>
				</div>
				<div class="span10 navbar-search">
					<jdoc:include type="modules" name="searchload" style="none" />
					<jdoc:include type="modules" name="position-0" style="none" />
				</div>
			</div>
		</div>
	</div>
	<!-- Subheader -->
	<div class="subhead-collapse">
		<div class="subhead">
			<div class="container-fluid">
				<div id="container-collapse" class="container-collapse"></div>
				<div class="row-fluid">
					<div class="span12">
						<jdoc:include type="modules" name="toolbar" style="no" />
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Container -->
	<div class="container-fluid">
		<jdoc:include type="modules" name="top" style="..." />
		<div class="row-fluid">
		<?php if ($showSubmenu) : ?>
			<div class="span2">
				<jdoc:include type="modules" name="submenu" style="..." />
			</div>
			<div class="span10">
		<?php else : ?>
			<div class="span12">
		<?php endif; ?>
				<!-- Begin Content -->
				<jdoc:include type="message" />
				<jdoc:include type="component" />
				<!-- End Content -->
			</div>
		</div>
		<jdoc:include type="modules" name="bottom" style="..." />
		<hr />
		<div class="footer">
			<p>&copy; Joomla! <?php echo date('Y');?></p>
		</div>
	</div>
	<jdoc:include type="modules" name="debug" style="none" />
	<script>
		(function($){
			$('*[rel=tooltip]').tooltip()

			// fix sub nav on scroll
			var $win = $(window)
			  , $nav = $('.subhead')
			  , navTop = $('.subhead').length && $('.subhead').offset().top - 40
			  , isFixed = 0

			processScroll()

			// hack sad times - holdover until rewrite for 2.1
			$nav.on('click', function () {
				if (!isFixed) setTimeout(function () {  $win.scrollTop($win.scrollTop() - 47) }, 10)
			})

			$win.on('scroll', processScroll)

			function processScroll() {
				var i, scrollTop = $win.scrollTop()
				if (scrollTop >= navTop && !isFixed) {
					isFixed = 1
					$nav.addClass('subhead-fixed')
				} else if (scrollTop <= navTop && isFixed) {
					isFixed = 0
					$nav.removeClass('subhead-fixed')
				}
			}

			// Turn radios into btn-group
		    $('.radio.btn-group label').addClass('btn');
		    $(".btn-group label:not(.active)").click(function() {
		        var label = $(this);
		        var input = $('#' + label.attr('for'));

		        if (!input.prop('checked')) {
		            label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
		            if(input.val()== '') {
		                    label.addClass('active btn-primary');
		             } else if(input.val()==0) {
		                    label.addClass('active btn-danger');
		             } else {
		            label.addClass('active btn-success');
		             }
		            input.prop('checked', true);
		        }
		    });
		    $(".btn-group input[checked=checked]").each(function() {
				if($(this).val()== '') {
		           $("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
		        } else if($(this).val()==0) {
		           $("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
		        } else {
		            $("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
		        }
		    });
		})(jQuery);
	</script>
</body>

</html>
