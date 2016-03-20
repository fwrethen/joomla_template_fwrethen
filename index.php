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
	<jdoc:include type="head" />
	<script>
		// add attribute rel=lightbox to direct links to image files
		jQuery(document).ready(function() {
			jQuery('a[href*=".png"], a[href*=".PNG"], a[href*=".gif"], a[href*=".GIF"], a[href*=".jpeg"], a[href*=".JPEG"], a[href*=".jpg"], a[href*=".JPG"]').not('[rel*="lightbox"]').attr('rel', 'lightbox');
		});
	</script>
	<!--[if lt IE 9]>
		<script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>

<body>
<div id="page">
	<header>
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
	</header>

	<section class="row-fluid" style="max-width: 960px; margin: 0px auto;">
		<?php if ($this->countModules('left')) : ?>
			<nav class="span2">
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

	<aside class="row-fluid">
		<?php if ($this->countModules('user41 + user42 + user43') > 0) : ?>
			<?php $span = 'span' . 12 / $this->countModules('user41 + user42 + user43'); ?>
		<?php endif; ?>
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

	<footer class="span6 offset3">
		<jdoc:include type="modules" name="syndicate" />
	</footer>
</div>
<div class="clearfix"></div>
<jdoc:include type="modules" name="debug" style="none" />

</body>
</html>
