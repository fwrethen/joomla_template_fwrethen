<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */


defined('JPATH_BASE') or die;

$app = JFactory::getApplication();
$params = $app->getParams('com_einsatzkomponente');

$url = JUri::getInstance();
$url->setQuery('');
$template_dir = 'templates/' . $app->getTemplate('template')->template;


$data = $displayData;

// Receive overridable options
$data['options'] = !empty($data['options']) ? $data['options'] : array();

// Set some basic options
$customOptions = array(
    'filtersHidden' => isset($data['options']['filtersHidden']) ? $data['options']['filtersHidden'] : empty($data['view']->activeFilters),
    'defaultLimit' => isset($data['options']['defaultLimit']) ? $data['options']['defaultLimit'] : JFactory::getApplication()->get('list_limit', 20),
    'searchFieldSelector' => '#filter_search',
    'orderFieldSelector' => '#list_fullordering'
);


$data['options'] = array_unique(array_merge($customOptions, $data['options']));

$formSelector = !empty($data['options']['formSelector']) ? $data['options']['formSelector'] : '#adminForm';
$filters = false;
if (isset($data['view']->filterForm)) {
    $filters = $data['view']->filterForm->getGroup('filter');
}

?>


<!--RSS-Feed Imag-->
<?php if ($params->get('display_home_rss','1')) : ?>
<div style="height:16px" class="eiko_rss_main_1"><a href="<?php echo $url; ?>&amp;format=feed&amp;type=rss"><img src="<?php echo $template_dir;?>/images/rss.svg" alt="RSS-Feed abonieren" height="16px" width="16px" style="height: 16px; width: 16px;"></a></div>
<?php endif; ?>

<?php

if ($params->get('show_filter','1')) {

// Load search tools
JHtml::_('searchtools.form', $formSelector, $data['options']);
?>

<div class="js-stools clearfix">
    <div class="clearfix">
        <div class="js-stools-container-bar">
		

		<?php if ($params->get('show_filter_search','1')) : ?>
            <!--<label for="filter_search" class="element-invisible" aria-invalid="false"><?php echo JText::_('COM_EINSATZKOMPONENTE_SUCHEN'); ?></label> -->

            <div class="btn-wrapper input-append">
                <?php echo $filters['filter_search']->input; ?>
                <button type="submit" class="btn " title="" data-original-title="<?php echo JText::_('COM_EINSATZKOMPONENTE_SUCHEN'); ?>">
                    <i class="icon-search"></i>
                </button>
            </div>
		<?php endif; ?>
		
		<?php if ($filters) : ?>
            <div class="btn-wrapper hidden-phone">
                <button type="button" class="btn  js-stools-btn-filter" title=""
                        data-original-title="<?php echo JText::_('COM_EINSATZKOMPONENTE_FILTER_AUSWAEHLEN'); ?>">
                    <?php echo JText::_('COM_EINSATZKOMPONENTE_FILTER_AUSWAEHLEN'); ?> <i class="caret"></i>
                </button>
            </div>
            <?php endif; ?>
            <div class="btn-wrapper hidden-phone">
                <button type="button" class="btn  js-stools-btn-clear" title="" data-original-title="<?php echo JText::_('COM_EINSATZKOMPONENTE_ALLE_FILTER_ZURUECKSETZEN'); ?>">
                    <?php echo JText::_('COM_EINSATZKOMPONENTE_ALLE_FILTER_ZURUECKSETZEN'); ?>
                </button>
            </div>
        </div>
    </div>
	
	
    <!-- Filters div -->
    <div class="js-stools-container-filters hidden-phone clearfix" style="">
        <?php // Load the form filters ?>
        <?php if ($filters) : ?>
			
		<div class="js-stools-field-filter">
		
		<?php if ($params->get('show_filter_auswahl_orga','1')) : ?>
		<?php echo $filters['filter_auswahl_orga']->input; ?>
		<?php echo '<br/><br/>';?>
		<?php endif; ?>

		<?php if ($params->get('show_filter_year','1') && false) : ?>
		<?php echo $filters['filter_year']->input; ?>
		<?php if ($params->get('show_filter_linebreak','0')) :echo '<br/>'; endif;?>
		<?php endif; ?>

		<?php if ($params->get('show_filter_data1','1')) : ?>
		<?php echo $filters['filter_data1']->input; ?>
		<?php if ($params->get('show_filter_linebreak','0')) :echo '<br/>'; endif;?>
		<?php endif; ?>

		<?php if ($params->get('show_filter_tickerkat','1')) : ?>
		<?php echo $filters['filter_tickerkat']->input; ?>
		<?php if ($params->get('show_filter_linebreak','0')) :echo '<br/>'; endif;?>
		<?php endif; ?>

		<?php if ($params->get('show_filter_alerting','1')) : ?>
		<?php echo $filters['filter_alerting']->input; ?>
		<?php if ($params->get('show_filter_linebreak','0')) :echo '<br/>'; endif;?>
		<?php endif; ?>

		<?php if ($params->get('show_filter_vehicles','1')) : ?>
		<?php echo $filters['filter_vehicles']->input; ?>
		<?php endif; ?>
		
		</div>
		

		<?php endif; ?>
    </div>
	

 
		<div>
		<?php $active_name = ''; ?>
		<?php $active = $data['view']->activeFilters;?>
		<?php if($active): ?>
		<?php $active_name = 'Aktive Filter : '; ?>
            <?php foreach ($active as $fieldName => $field) : ?>
						
				<?php switch ($fieldName) 
				 { 
				 	case 'vehicles': $active_name .= '<span class="label label-info">'.JText::_('COM_EINSATZKOMPONENTE_FAHRZEUG').'</span> ';break; 
				 	case 'alerting': $active_name .= '<span class="label label-info">'.JText::_('COM_EINSATZKOMPONENTE_ALARMIERUNGSART').'</span> ';break; 
				 	case 'data1': $active_name .= '<span class="label label-info">'.JText::_('COM_EINSATZKOMPONENTE_EINSATZART').'</span> ';break; 
					case 'tickerkat': $active_name .= '<span class="label label-info">'.JText::_('COM_EINSATZKOMPONENTE_KATEGORIE').'</span> ';break; 
				 	case 'auswahl_orga': $active_name .= '<span class="label label-info">'.JText::_('COM_EINSATZKOMPONENTE_ORGANISATION').'</span> ';break;
				 	case 'year': $active_name .= '<span class="label label-info">'.JText::_('COM_EINSATZKOMPONENTE_JAHR').'</span> ';break; 
				 	default: $active_name = '';break; 
				}  ?>

            <?php endforeach; ?>
			<?php echo $active_name;?>
		<?php endif; ?>
		</div>

</div>

<?php } ?>

<div style="text-align: center; padding: 5px;">
<?php if ($params->get('show_filter_year','1')) :
  /* Retrieve years from database */
  $db = JFactory::getDbo();
  $query = $db->getQuery(true);
  $query->select('YEAR(date1) AS year');
  $query->from($db->quoteName('#__eiko_einsatzberichte'));
  $query->where($db->quoteName('state') . ' = ' . $db->quote('1'), 'OR');
  $query->where($db->quoteName('state') . ' = ' . $db->quote('2'));
  $query->group($db->quoteName('year'));
  $query->order('year DESC');
  $db->setQuery($query);

  $years = array();
  foreach ($db->loadObjectList() as $result)
    array_push($years, $result->year);

  $selectedYear = $filters['filter_year']->value;
  $selectedYear = ($selectedYear ? $selectedYear : $years[0]);

  /* Reference: JHtmlDropdown (libraries/cms/html/dropdown.php) */
  echo JHTML::_('dropdown.init'); ?>
  <div class="btn-group" style="<?php if (!in_array($selectedYear - 1, $years)) echo 'visibility:hidden;'; ?>">
    <button name="filter[year]" value="<?php echo $selectedYear - 1; ?>" class="btn btn-large">
      <a href="<?php echo JRoute::_($url); ?>">
        <i class="icon-backward"></i>
      </a>
    </button>
  </div>
  <div class="btn-group">
    <a href="#" data-toggle="dropdown" class="btn btn-large dropdown-toggle" style="min-width:122px;">
      <i class="icon-calendar"></i>
      <b style="margin:4px;"><?php echo ($selectedYear == 9999) ? 'alle' : $selectedYear; ?></b>
      <i class="icon-chevron-down"></i>
    </a>
    <ul class="dropdown-menu">
      <?php foreach ($years as $year): ?>
        <li class="text-center" data="<?php echo $year; ?>">
          <a href="<?php echo JRoute::_($url . '?filter[year]=' . $year) ?>"><?php echo $year; ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div class="btn-group" style="<?php if (!in_array($selectedYear + 1, $years)) echo 'visibility:hidden;'; ?>">
    <button name="filter[year]" value="<?php echo $selectedYear + 1; ?>" class="btn btn-large">
      <a href="<?php echo JRoute::_($url); ?>">
        <i class="icon-forward"></i>
      </a>
    </button>
  </div>

<?php endif; ?>
</div>

<?php /* Hidden input to keep compatibility with mod_einsatz_stats */ ?>
<input id="year" name="year" value="<?php echo $selectedYear; ?>" type="hidden">
