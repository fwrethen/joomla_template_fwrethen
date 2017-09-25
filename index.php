<?php
/**
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('jquery.framework', false);
JHtmlBootstrap::loadCss();

$doc = JFactory::getDocument();
// Doctype HTML5
$doc->setHtml5(true);

$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/lightbox.css', $type = 'text/css');
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/lightbox.min.js', 'text/javascript');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>"  dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />

<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />

<script>
jQuery(document).ready(function() {
	jQuery('a[href*=".png"], a[href*=".PNG"], a[href*=".gif"], a[href*=".GIF"], a[href*=".jpeg"], a[href*=".JPEG"], a[href*=".jpg"], a[href*=".JPG"]').not('[rel*="lightbox"]').attr('rel', 'lightbox');
});
</script>

<!--[if lt IE 9]>
	<script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script>
<![endif]-->

</head>
<body class="width_<?php echo $this->params->get('widthStyle'); ?>">
<a name="up" id="up"></a>
<div class="center" align="center">
	<div id="wrapper">
		<div id="wrapper_shadow">
			<div id="header">
				<div id="logo">
					<table height="100" border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="235" height="86"  valign="bottom" vspace=0 hspace=0><img border="0" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/balken_top1.gif" width="235" height="86"></td>
							<td valign="bottom" width="100%">
								<img border="0" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/balken_top2.gif" style="height: 84px; width: 100%;">
							</td>
							<td width="119" height="86" valign="bottom">
								<img border="0" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/balken_top3.gif" width="119" height="86">
							</td>
						</tr>
					</table>
				</div>
				<jdoc:include type="modules" name="top-a" />
			</div>

			<div id="tabarea">
				<nav id="pillmenu">
					<jdoc:include type="modules" name="top-b" />
				</nav>
			</div>

			<div id="search">
				<jdoc:include type="modules" name="search" />
			</div>

			<div id="pathway">
				<jdoc:include type="modules" name="breadcrumbs" />
			</div>

			<div class="clr"></div>

			<div id="whitebox">

				<div id="area">
					<jdoc:include type="message" />
					<div id="leftcolumn">
						<?php if($this->countModules('menu') || $this->countModules('sidebar-left')) : ?>
							<jdoc:include type="modules" name="menu" style="xhtml" />
							<jdoc:include type="modules" name="sidebar-left" style="xhtml" />
						<?php endif; ?>
					</div>

					<?php $id = ($this->countModules('menu') ? 'maincolumn' : 'maincolumn_full'); ?>
					<div id="<?php echo $id; ?>">
					<?php if($this->countModules('main-top')) : ?>
						<table class="nopad userx">
							<tr valign="top">
								<td class="row-fluid">
									<jdoc:include type="modules" name="main-top" style="xhtml" />
								</td>
							</tr>
						</table>
						<div id="maindivider"></div>
					<?php endif; ?>

					<table class="nopad">
						<tr valign="top">
							<td>
								<jdoc:include type="component" />
								<jdoc:include type="modules" name="main-bottom" style="xhtml"/>
							</td>
							<?php if($this->countModules('sidebar-right') and JRequest::getCmd('layout') != 'form') : ?>
								<td class="greyline">&nbsp;</td>
								<td width="170">
									<jdoc:include type="modules" name="sidebar-right" style="xhtml"/>
								</td>
							<?php endif; ?>
						</tr>
					</table>

					</div>
					<div class="clr"></div>
				</div>

				<?php if($this->countModules('bottom-a or bottom-b')) : ?>
					<div id="maindivider"></div>
					<table class="nopad userx">
						<tr valign="top">
							<?php if($this->countModules('bottom-a')) : ?>
								<td class="row-fluid">
									<jdoc:include type="modules" name="bottom-a" style="xhtml" />
								</td>
							<?php endif; ?>
							<?php if($this->countModules('bottom-a and bottom-b')) : ?>
								<td class="greyline">&nbsp;</td>
							<?php endif; ?>
							<?php if($this->countModules('bottom-b')) : ?>
								<td class="row-fluid">
									<jdoc:include type="modules" name="bottom-b" style="xhtml" />
								</td>
							<?php endif; ?>
						</tr>
					</table>
				<?php endif; ?>

				<div class="clr"></div>
			</div>

			<div id="footerspacer"></div>
			<div id="footer">
				<p id="syndicate">
					<jdoc:include type="modules" name="footer" />
				</p>
			</div>
		</div>
	</div>
</div>
<jdoc:include type="modules" name="debug" />

</body>
</html>
