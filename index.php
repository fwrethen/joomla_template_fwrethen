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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />

<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
<!--[if lte IE 6]>
<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/ieonly.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
<![endif]-->

</head>
<body id="page_bg" class="width_<?php echo $this->params->get('widthStyle'); ?>">
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
          <jdoc:include type="modules" name="user3" />
        </nav>
			</div>

			<div id="search">
				<jdoc:include type="modules" name="user4" />
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
							<?php if($this->countModules('user1 or user2')) : ?>
								<table class="nopad user1user2">
									<tr valign="top">
										<?php if($this->countModules('user1')) : ?>
											<td>
												<jdoc:include type="modules" name="user1" style="xhtml" />
											</td>
										<?php endif; ?>
										<?php if($this->countModules('user1 and user2')) : ?>
											<td class="greyline">&nbsp;</td>
										<?php endif; ?>
										<?php if($this->countModules('user2')) : ?>
											<td>
												<jdoc:include type="modules" name="user2" style="xhtml" />
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

						</div>
						<div class="clr"></div>
					</div>
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
