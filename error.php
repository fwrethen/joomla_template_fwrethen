<?php
/**
* @package     Joomla.Site
* @subpackage  Templates.fwrethen3
*
* @copyright   Copyright (C) 2017 Martin Matthaei
* @license     GNU General Public License version 2 or later; see LICENSE.txt
*/

defined('_JEXEC') or die;
if (!isset($this->error)) {
  $this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
  $this->debug = false;
}

$app = JFactory::getApplication();
$sitename = $app->get('sitename');
$template_root = 'templates/' . $app->getTemplate('template')->template;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>"  dir="<?php echo $this->direction; ?>" >
<head>
  <title><?php echo $this->error->getCode(); ?> - <?php echo $this->title; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes" />
  <link rel="shortcut icon" href="<?php echo $template_root ?>/favicon.ico" type="image/vnd.microsoft.icon" />
  <link rel="shortcut icon" href="<?php echo $template_root ?>/favicon-96x96.png" type="image/png" />
  <link rel="stylesheet" href="<?php echo JUri::root(true); ?>/media/jui/css/bootstrap.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $template_root ?>/css/template.css" type="text/css" />
  <!--[if lt IE 9]>
    <script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script>
  <![endif]-->
</head>
<body>
  <header>
    <div id="head-wrapper">
      <div id="logo" unselectable="on">
        <div><h1 id="sitename"><?php echo $sitename; ?></h1></div>
      </div>
    </div>
  </header>
  <div id="content-wrapper">
    <div id="alert alert-danger">
      <h2><?php echo $this->error->getCode(); ?> - <?php echo $this->error->getMessage(); ?></h2>
    </div>
    <div id="errorboxbody">
      <p><strong><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></strong></p>
      <ol>
        <li><?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
        <li><?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
        <li><?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
        <li><?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
        <li><?php echo JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'); ?></li>
        <li><?php echo JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></li>
      </ol>

      <p><strong><?php echo JText::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES'); ?></strong></p>
      <ul>
        <li><a href="<?php echo $this->baseurl; ?>/index.php" title="<?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?>"><?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></li>
      </ul>

      <p><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?>.</p>
      <div id="techinfo">
        <p><?php echo $this->error->getMessage(); ?></p>
        <p>
          <?php if ($this->debug) :
            echo $this->renderBacktrace();
          endif; ?>
        </p>
      </div>
    </div>
  </div>
</body>
</html>
