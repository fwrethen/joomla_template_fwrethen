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

// Adjusting content width
$span = 0;
if ($this->countModules('user41'))
{
	$span++;
}
if ($this->countModules('user42'))
{
	$span++;
}
if ($this->countModules('user43'))
{
	$span++;
}
if ($span > 0)
{
	$span = "span" . 12 / $span;
} else {
	$span = "span12";
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>"  dir="<?php echo $this->direction; ?>" >
<head>
	<jdoc:include type="head" />
	<script>
		jQuery(document).ready(function() {
			// add attribute rel=lightbox to direct links to image files
			jQuery('a[href*=".png"], a[href*=".PNG"], a[href*=".gif"], a[href*=".GIF"], a[href*=".jpeg"], a[href*=".JPEG"], a[href*=".jpg"], a[href*=".JPG"]').not('[rel*="lightbox"]').attr('rel', 'lightbox');

			// add class active to a in matching navbar header
			var menu = jQuery('.nav-header').text();
			if !(menu == '') jQuery('a:contains('+menu+')').parent().addClass('current active');
		});
	</script>
	<!--[if lt IE 9]>
		<script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>

<body>
<div id="page">
	<header>
		<div id="head-wrapper" style="margin: 0px auto; max-width: 1200px;">
			<div id="logo" unselectable="on">
				<h1 id="sitename"><?php echo $sitename; ?></h1>
			</div>
			<jdoc:include type="modules" name="top" />
			<nav id="headernav">
				<jdoc:include type="modules" name="pillmenu" style="none" />
			</nav>
			<div id="search">
				<jdoc:include type="modules" name="search" />
			</div>
			<div id="pathway">
				<jdoc:include type="modules" name="breadcrumb" />
			</div>
		</div>
	</header>

	<div id="content-wrapper" style="max-width: 1080px; margin: 0px auto;">
		<section class="row-fluid">
			<?php if ($this->countModules('left')) : ?>
				<nav id="sidenav" class="span2">
					<jdoc:include type="modules" name="left" style="html5" />
				</nav>
			<?php endif; ?>
			<?php if ($this->countModules('left') && $this->countModules('right')) : ?>
				<main class="span8">
			<?php elseif ($this->countModules('left') || $this->countModules('right')) : ?>
				<main class="span10">
			<?php else: ?>
				<main class="span12">
			<?php endif; ?>
					<jdoc:include type="modules" name="user11" style="xhtml" />
					<jdoc:include type="message" />
					<jdoc:include type="component" />
					<jdoc:include type="modules" name="footer" style="xhtml" />
				</main>
			<?php if ($this->countModules('right')) : ?>
				<aside class="span2">
					<jdoc:include type="modules" name="right" style="xhtml" />
				</aside>
			<?php endif; ?>
		</section>

		<?php if ($this->countModules('user41 + user42 + user43') > 0) : ?>
			<aside class="row-fluid">
				<?php if ($this->countModules('user41')) : ?>
					<div class="<?php echo $span; ?>">
						<jdoc:include type="modules" name="user41" style="html5" />
					</div>
				<?php endif; ?>
				<?php if ($this->countModules('user42')) : ?>
					<div class="<?php echo $span; ?>">
						<jdoc:include type="modules" name="user42" style="html5" />
					</div>
				<?php endif; ?>
				<?php if ($this->countModules('user43')) : ?>
					<div class="<?php echo $span; ?>">
						<jdoc:include type="modules" name="user43" style="html5" />
					</div>
				<?php endif; ?>
			</aside>
		<?php endif; ?>
	</div>

	<footer class="span6 offset3 text-center">
		<jdoc:include type="modules" name="syndicate" style="none" />
	</footer>
</div>
<div class="clearfix"></div>
<jdoc:include type="modules" name="debug" style="none" />

</body>
</html>
