<?php
/**
* @package     Joomla.Site
* @subpackage  Templates.fwrethen3
*
* @copyright   Copyright (C) 2016-2021 Martin Matthaei
* @license     GNU General Public License version 2 or later; see LICENSE.txt
*/

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$sitename = $app->get('sitename');
$template = $app->getTemplate(true);
$template_root = 'templates/' . $template->template;
$params = $template->params;

$doc = JFactory::getDocument();

// Doctype HTML5
$doc->setHtml5(true);

// Javascript
$js = <<<JS
  jQuery(document).ready(function() {
    // add attribute rel=lightbox to direct links to image files
    jQuery('a[href*=".png"], a[href*=".PNG"], a[href*=".gif"], a[href*=".GIF"], a[href*=".jpeg"], a[href*=".JPEG"], a[href*=".jpg"], a[href*=".JPG"]').not('[rel*="lightbox"]').attr('rel', 'lightbox');

    // add class active to a in matching navbar header
    let menu = jQuery('.nav-header');
    if (menu.length > 0 && menu[0].textContent != '') {
        const title = menu[0].textContent;
        const nav = jQuery('#headernav');
        
        nav.find('li.active').removeClass('current active');
        nav.find('a:contains('+title+')').parent().addClass('current active');
    }

    // if page is called with parameter 'templateStyle', append it to all links
    let url = new URL(window.location.href);
    let templateId = url.searchParams.get('templateStyle');
    if (templateId !== null) {
      jQuery('a[href^="/"]').each(function() {
        let target = new URL(jQuery(this).attr('href'), url.origin);
        target.searchParams.append('templateStyle', templateId);
        jQuery(this).attr('href', target.href);
      });
    }
  });
JS;
JHtml::_('jquery.framework', false);
JHtml::_('bootstrap.framework');
$doc->addScript($template_root . '/js/lightbox.min.js');
$doc->addScriptDeclaration($js);
$doc->addScript(JUri::root(true) . '/media/jui/js/html5.js', array('conditional' => 'lt IE 9'));

// Stylesheets
JHtml::_('bootstrap.loadCss');
$doc->addStyleSheet($template_root . '/css/lightbox.min.css');
$doc->addStyleSheet($template_root . '/css/template.css');

$spanLeft = $this->countModules('menu') || $this->countModules('sidebar-left') ? $params->get('sidebarLeftWidth', 2) : 0;
$spanRight = $this->countModules('sidebar-right') ? $params->get('sidebarRightWidth', 2) : 0;
$spanMain = 12 - $spanLeft - $spanRight;

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>"  dir="<?php echo $this->direction; ?>" >
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes" />
  <jdoc:include type="head" />
  <link rel="icon" href="<?php echo $template_root; ?>/favicon-16.png" type="image/png" sizes="16x16" />
  <link rel="icon" href="<?php echo $template_root; ?>/favicon-32.png" type="image/png" sizes="32x32" />
  <link rel="icon" href="<?php echo $template_root; ?>/favicon-96.png" type="image/png" sizes="96x96" />
  <link rel="apple-touch-icon" href="<?php echo $template_root; ?>/apple-touch-icon.png" sizes="180x180" />
</head>

<body>
<div>
  <header>
    <div id="head-wrapper">
      <div id="logo" unselectable="on">
        <div><h1 id="sitename"><?php echo $sitename; ?></h1></div>
        <div id="icon" class="hidden-phone"><img src="<?php echo $template_root . '/images/icon_tlf.svg'; ?>" width="70px" /></div>
      </div>
      <div class="nav-mobile visible-phone">
        <button type="button" class="btn-nav" data-toggle="collapse" data-target=".nav-collapse">
          Menü &nbsp; &#9776;
        </button>
        <div class="nav-collapse collapse text-right">
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
        <?php if ($spanLeft) : ?>
          <div id="sidebar-left" class="span<?= $spanLeft; ?>">
            <nav id="sidenav" class="hidden-phone">
              <jdoc:include type="modules" name="menu" style="html5" />
            </nav>
            <jdoc:include type="modules" name="sidebar-left" style="html5" />
          </div>
        <?php endif; ?>
        <main class="span<?= $spanMain; ?>">
          <jdoc:include type="modules" name="main-top" style="xhtml" />
          <jdoc:include type="message" />
          <jdoc:include type="component" />
          <jdoc:include type="modules" name="main-bottom" style="xhtml" />
        </main>
        <?php if ($spanRight) : ?>
          <aside class="span<?= $spanRight; ?>">
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
