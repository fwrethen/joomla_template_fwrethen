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
//* The following line loads the MooTools JavaScript Library */
//JHtml::_('behavior.framework', true);
JHtml::_('jquery.framework', false);

$doc = JFactory::getDocument();
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/lightbox.css', $type = 'text/css');
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/lightbox.min.js', 'text/javascript');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />

<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />

<script>
jQuery(document).ready(function() {
	jQuery('a[href*=".png"], a[href*=".gif"], a[href*=".jpg"]').not('[rel*="lightbox"]').attr('rel', 'lightbox');
});
</script>

<!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
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
						      <img border="0" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/balken_top2.gif" width="100%" height="86"></td>
						      <td width="119" height="86" valign="bottom"><img border="0" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/balken_top3.gif" width="119" height="86"></td>
						    </tr>
						  </table>
						</div>
						<jdoc:include type="modules" name="top" />
			</div>

			<div id="tabarea">
        <nav id="pillmenu">
          <jdoc:include type="modules" name="pillmenu" />
        </nav>
			</div>

			<div id="search">
				<jdoc:include type="modules" name="search" />
			</div>

			<div id="pathway">
				<jdoc:include type="modules" name="breadcrumb" />
			</div>

			<div class="clr"></div>

			<div id="whitebox">

					<div id="area">
									<jdoc:include type="message" />

						<div id="leftcolumn">
						<?php if($this->countModules('left')) : ?>
							<jdoc:include type="modules" name="left" style="xhtml" />
						<?php endif; ?>
						</div>

						<?php if($this->countModules('left')) : ?>
						<div id="maincolumn">
						<?php else: ?>

						<div id="maincolumn_full">
						<?php endif; ?>
							<?php if($this->countModules('user11 or user12 or user13')) : ?>
								<table class="nopad userx">
									<tr valign="top">
										<?php if($this->countModules('user11')) : ?>
											<td>
												<jdoc:include type="modules" name="user11" style="xhtml" />
											</td>
										<?php endif; ?>
										<?php if($this->countModules('user11 and user12')) : ?>
											<td class="greyline">&nbsp;</td>
										<?php endif; ?>
										<?php if($this->countModules('user12')) : ?>
											<td>
												<jdoc:include type="modules" name="user12" style="xhtml" />
											</td>
										<?php endif; ?>
										<?php if($this->countModules('user12 and user13') or $this->countModules('user11 and user13')) : ?>
											<td class="greyline">&nbsp;</td>
										<?php endif; ?>
										<?php if($this->countModules('user13')) : ?>
											<td>
												<jdoc:include type="modules" name="user13" style="xhtml" />
											</td>
										<?php endif; ?>
									</tr>
								</table>

								<div id="maindivider"></div>
							<?php endif; ?>
							
							<?php if($this->countModules('user21 or user22 or user23')) : ?>
								<table class="nopad userx">
									<tr valign="top">
										<?php if($this->countModules('user21')) : ?>
											<td>
												<jdoc:include type="modules" name="user21" style="xhtml" />
											</td>
										<?php endif; ?>
										<?php if($this->countModules('user21 and user22')) : ?>
											<td class="greyline">&nbsp;</td>
										<?php endif; ?>
										<?php if($this->countModules('user22')) : ?>
											<td>
												<jdoc:include type="modules" name="user22" style="xhtml" />
											</td>
										<?php endif; ?>
										<?php if($this->countModules('user22 and user23') or $this->countModules('user21 and user23')) : ?>
											<td class="greyline">&nbsp;</td>
										<?php endif; ?>
										<?php if($this->countModules('user23')) : ?>
											<td>
												<jdoc:include type="modules" name="user23" style="xhtml" />
											</td>
										<?php endif; ?>
									</tr>
								</table>

								<div id="maindivider"></div>
							<?php endif; ?>

							<table class="nopad">
								<tr valign="top">
									<td>
										<jdoc:include type="component" />
										<jdoc:include type="modules" name="footer" style="xhtml"/>
									</td>
									<?php if($this->countModules('right') and JRequest::getCmd('layout') != 'form') : ?>
										<td class="greyline">&nbsp;</td>
										<td width="170">
											<jdoc:include type="modules" name="right" style="xhtml"/>
										</td>
									<?php endif; ?>
								</tr>
							</table>
							
							<?php if($this->countModules('user31 or user32 or user33')) : ?>
								<div id="maindivider"></div>

								<table class="nopad userx">
									<tr valign="top">
										<?php if($this->countModules('user31')) : ?>
											<td>
												<jdoc:include type="modules" name="user31" style="xhtml" />
											</td>
										<?php endif; ?>
										<?php if($this->countModules('user31 and user32')) : ?>
											<td class="greyline">&nbsp;</td>
										<?php endif; ?>
										<?php if($this->countModules('user32')) : ?>
											<td>
												<jdoc:include type="modules" name="user32" style="xhtml" />
											</td>
										<?php endif; ?>
										<?php if($this->countModules('user32 and user33') or $this->countModules('user31 and user33')) : ?>
											<td class="greyline">&nbsp;</td>
										<?php endif; ?>
										<?php if($this->countModules('user33')) : ?>
											<td>
												<jdoc:include type="modules" name="user33" style="xhtml" />
											</td>
										<?php endif; ?>
									</tr>
								</table>
							<?php endif; ?>

						</div>
						<div class="clr"></div>
					</div>
					
					<?php if($this->countModules('user41 or user42 or user43')) : ?>
						<div id="maindivider"></div>

						<table class="nopad userx">
							<tr valign="top">
								<?php if($this->countModules('user41')) : ?>
									<td>
										<jdoc:include type="modules" name="user41" style="xhtml" />
									</td>
								<?php endif; ?>
								<?php if($this->countModules('user41 and user42')) : ?>
									<td class="greyline">&nbsp;</td>
								<?php endif; ?>
								<?php if($this->countModules('user42')) : ?>
									<td>
										<jdoc:include type="modules" name="user42" style="xhtml" />
									</td>
								<?php endif; ?>
								<?php if($this->countModules('user42 and user43') or $this->countModules('user41 and user43')) : ?>
									<td class="greyline">&nbsp;</td>
								<?php endif; ?>
								<?php if($this->countModules('user43')) : ?>
									<td>
										<jdoc:include type="modules" name="user43" style="xhtml" />
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
        <jdoc:include type="modules" name="syndicate" />
      </p>
		</div>

	</div>
	</div>
</div>
<jdoc:include type="modules" name="debug" />

</body>
</html>
