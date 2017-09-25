<?php
/**
* @package     Joomla.Site
* @subpackage  Templates.fwrethen3
*
* @copyright   Copyright (C) 2016 Martin Matthaei
* @license     GNU General Public License version 2 or later; see LICENSE.txt
*/

defined('_JEXEC') or die;

$doc = JFactory::getDocument();

$app = JFactory::getApplication();
$sitename = $app->get('sitename');

// Doctype HTML5
$doc->setHtml5(true);

// Javascript
JHtml::_('jquery.framework', false);
JHtml::_('bootstrap.framework');
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/lightbox.min.js', 'text/javascript');

// Stylesheets
JHtml::_('bootstrap.loadCss');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/lightbox.css', $type = 'text/css');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/template.css', $type = 'text/css');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>"  dir="<?php echo $this->direction; ?>" >
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes" />
	<jdoc:include type="head" />
	<script>
		jQuery(document).ready(function() {
			// add attribute rel=lightbox to direct links to image files
			jQuery('a[href*=".png"], a[href*=".PNG"], a[href*=".gif"], a[href*=".GIF"], a[href*=".jpeg"], a[href*=".JPEG"], a[href*=".jpg"], a[href*=".JPG"]').not('[rel*="lightbox"]').attr('rel', 'lightbox');

			// add class active to a in matching navbar header
			var menu = jQuery('.nav-header')[0].textContent;
			if (!menu == '') jQuery('#headernav').find('a:contains('+menu+')').parent().addClass('current active');
		});
	</script>
	<!--[if lt IE 9]>
		<script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>

<body>
<div id="page">
	<header>
		<div id="head-wrapper">
			<div id="logo" unselectable="on">
				<div><h1 id="sitename"><?php echo $sitename; ?></h1></div>
				<div id="icon" class="hidden-phone"><img src="<?php echo $this->baseurl . '/templates/' . $this->template . '/images/icon_tlf.svg'; ?>" width="42px" /></div>
			</div>
			<div class="nav-mobile visible-phone">
				<button type="button" class="btn-nav" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<div class="nav-collapse collapse">
					<jdoc:include type="modules" name="top-b" style="html5" />
					<jdoc:include type="modules" name="menu" style="html5" />
				</div>
			</div>
			<jdoc:include type="modules" name="top-a" />
			<nav id="headernav" class="hidden-phone">
				<jdoc:include type="modules" name="top-b" style="none" />
			</nav>
			<div id="search">
				<jdoc:include type="modules" name="search" />
			</div>
			<div id="pathway" class="hidden-phone">
				<jdoc:include type="modules" name="breadcrumbs" />
			</div>
		</div>
	</header>

	<?php if ($this->countModules('banner')) : ?>
		<div id="banner">
			<jdoc:include type="modules" name="banner" style="html5" />
		</div>
	<?php endif; ?>

	<div id="content-wrapper">
		<section class="row-fluid">
			<?php if ($this->countModules('menu') || $this->countModules('sidebar-left')) : ?>
				<div class="span2">
					<nav id="sidenav" class="hidden-phone">
						<jdoc:include type="modules" name="menu" style="html5" />
					</nav>
					<jdoc:include type="modules" name="sidebar-left" style="html5" />
				</div>
			<?php endif; ?>
			<?php if (($this->countModules('menu') || $this->countModules('sidebar-left')) && $this->countModules('sidebar-right')) : ?>
				<main class="span8">
			<?php elseif ($this->countModules('menu') || $this->countModules('sidebar-left') || $this->countModules('sidebar-right')) : ?>
				<main class="span10">
			<?php else: ?>
				<main class="span12">
			<?php endif; ?>
					<jdoc:include type="modules" name="main-top" style="xhtml" />
					<jdoc:include type="message" />
					<jdoc:include type="component" />
					<jdoc:include type="modules" name="main-bottom" style="xhtml" />
				</main>
			<?php if ($this->countModules('sidebar-right')) : ?>
				<aside class="span2">
					<jdoc:include type="modules" name="sidebar-right" style="xhtml" />
				</aside>
			<?php endif; ?>
		</section>

		<?php if ($this->countModules('bottom-a')) : ?>
			<aside id="bottom-a" class="row-fluid">
				<jdoc:include type="modules" name="bottom-a" style="html5" />
			</aside>
		<?php endif; ?>
		<?php if ($this->countModules('bottom-b')) : ?>
			<aside id="bottom-b" class="row-fluid">
				<jdoc:include type="modules" name="bottom-b" style="html5" />
			</aside>
		<?php endif; ?>
	</div>

	<footer class="text-center">
		<jdoc:include type="modules" name="footer" style="none" />
	</footer>
</div>

<jdoc:include type="modules" name="debug" style="none" />

</body>
</html>
