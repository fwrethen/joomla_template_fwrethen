<?php
/**
 * @version     3.0.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2013 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <webmaster@feuerwehr-veenhusen.de> - http://einsatzkomponente.de
 */
// no direct access
defined('_JEXEC') or die;

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_einsatzkomponente', JPATH_ADMINISTRATOR);

$url = 'index.php?option=com_einsatzkomponente&view=einsatzberichte';
$url_bericht = 'index.php?option=com_einsatzkomponente&view=einsatzbericht';
$template_dir = 'templates/' . JFactory::getApplication()->getTemplate('template')->template;

?>
<style>
	table#einsaetze {
		margin: 10px 0 0 0;
	}

	table#einsaetze h3 {
		margin: 0;
	}

	table#einsaetze tr.link:nth-of-type(odd) {
		background-color: #f9f9f9;
	}

	table#einsaetze tr.link:hover {
		background-color: #e9f5ff;
		cursor: pointer;
	}

	table#einsaetze td, table#einsaetze th {
		padding: 10px;
	}

	@media(max-width:767px) {
		table#einsaetze td:nth-of-type(n+2), table#einsaetze th:nth-of-type(n+2) {
			display: inline-block;
		}
	}
</style>
<div>

<!--RSS-Feed Imag-->
<?php if ($this->params->get('display_home_rss','1')) : ?>
<div style="float:right; height:16px" class="eiko_rss" ><a href="<?php echo $url; ?>&format=feed&type=rss"><img src="<?php echo $template_dir;?>/images/rss.svg" alt="RSS-Feed abonieren" height="16px" width="16px" style="height: 16px; width: 16px;"></a></div>
<?php endif; ?>


<!--Page Heading-->
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
<?php endif;?>


<?php // Filter ------------------------------------------------------------------------------------

$url = $url . '&list=1';

?><div style="text-align: center; padding: 5px;"><form action="<?php echo JRoute::_($url); ?>" method="post" name="adminForm" id="adminForm"><?php


if (!$this->params->get('anzeigejahr','0') and $this->params->get('display_filter_jahre','1')) :
	$years = array();
	foreach ($this->years as $year)
		array_push($years, $year->id);

	/* Reference: JHtmlDropdown (libraries/cms/html/dropdown.php) */
	echo JHTML::_('dropdown.init'); ?>
	<div class="btn-group" style="<?php if (!in_array($this->selectedYear - 1, $years)) echo 'visibility:hidden;'; ?>">
		<button name="year" value="<?php echo $this->selectedYear - 1; ?>" class="btn btn-large">
			<a href="<?php echo JRoute::_($url); ?>">
				<i class="icon-backward"></i>
			</a>
		</button>
	</div>
	<div class="btn-group">
		<a href="#" data-toggle="dropdown" class="btn btn-large dropdown-toggle" style="min-width:122px;">
			<i class=" icon-calendar"></i>
			<b style="margin:4px;"><?php echo ($this->selectedYear == 9999) ? 'alle' : $this->selectedYear; ?></b>
			<i class="icon-chevron-down"></i>
		</a>
		<ul class="dropdown-menu">
			<li class="text-center">
				<a href="<?php echo JRoute::_($url . '&year=9999'); ?>">
					Alle Einsätze
				</a>
			</li>
			<?php foreach ($years as $year): ?>
				<li class="text-center">
					<a href="<?php echo JRoute::_($url . '&year=' . $year); ?>">
						<?php echo $year; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="btn-group" style="<?php if (!in_array($this->selectedYear + 1, $years)) echo 'visibility:hidden;'; ?>">
		<button name="year" value="<?php echo $this->selectedYear + 1; ?>" class="btn btn-large">
			<a href="<?php echo JRoute::_($url); ?>">
				<i class="icon-forward"></i>
			</a>
		</button>
	</div>

    <?php
endif;
if ($this->params->get('display_filter_einsatzarten','1')) :
	$einsatzarten[] = JHTML::_('select.option', '', JTEXT::_('alle Einsatzarten')  , 'id', 'title');
	$einsatzarten = array_merge($einsatzarten, (array)$this->einsatzarten);
	?><?php
	echo JHTML::_('select.genericlist',  $einsatzarten, 'selectedEinsatzart', ' onchange=submit(); ', 'id', 'title', $this->selectedEinsatzart);?>
    <?php
	endif;
	if (!$this->params->get('abfragewehr','0') and $this->params->get('display_filter_organisationen','1')) :
	$organisationen[] = JHTML::_('select.option', '', JTEXT::_('alle Organisationen')  , 'id', 'name');
	$organisationen = array_merge($organisationen, (array)$this->organisationen);
	?><?php
	echo JHTML::_('select.genericlist',  $organisationen, 'selectedOrga', ' onchange=submit(); ', 'id', 'name', $this->selectedOrga);
	endif;?>
	</form></div>
</div>
<?php // Filter ENDE   -------------------------------------------------------------------------------

?>

<?php // Table-Header zusammenbasteln
$theader = '';
$col ='0';
if ($this->params->get('display_home_number','1') or $this->params->get('display_home_alertimage_num','0')) :
	if ($this->params->get('display_home_number','1')):
		$theader = $theader . '<th>Nr.</th>';
	else:
		$theader = $theader . '<th></th>';
	endif;
	$col =$col+1;
endif;
if ($this->params->get('display_home_alertimage','0')) :
	$theader = $theader . '<th>Alarm über</th>';
	$col =$col+1;
endif;
$theader = $theader . '<th>Datum</th>';
$theader = $theader . '<th colspan="2">Einsatzbeschreibung</th>';
$theader = $theader . '<th>Einsatzort</th>';
$col =$col+4;
if ($this->params->get('display_home_orga','0')) :
	$theader = $theader . '<th>Organisationen</th>';
	$col =$col+1;
endif;
if ($this->params->get('display_home_image')) :
	$theader = $theader . '<th>Bild</th>';
	$col =$col+1;
endif;

?>


<table id="einsaetze" class="table table-bordered" width="100%" cellspacing="0" cellpadding="0">
 <tbody>
	 <?php
$show = false;
if ($this->params->get('display_home_pagination')) :
     $i=$this->pagination->total - $this->pagination->limitstart+1;
	 else:
     $i=count($this->reports)+1;
	 endif;
	 $m = '';
	 if ($this->reports) :
     foreach ($this->reports as $item) :
		   $i--;
		   $curTime = strtotime($item->date1);
		   ?>
          <?php /* -- Filter Einsatzart -- */ ?>
		  <?php if(preg_match('/\b'.$this->selectedOrga.'\b/',$item->auswahl_orga)==true or $this->selectedOrga == '0' or $this->selectedOrga == ''): ?>
		  <?php if ($this->selectedEinsatzart == $item->data1 or $this->selectedEinsatzart == '' ) : ?>
          <?php $show = true;?>

           <?php /* -- Anzeige des Monatsnamens -- */ ?>
		   <?php if ($this->params->get('display_home_monat','1')) : ?>
           <?php if (date('n', $curTime) != $m) : ?>
		   <tr><td colspan="<?php echo $col;?>">
           <?php $m=date('n', $curTime);?>
		   <?php echo '<h3>';?>
           <?php echo (new JDate)->monthToString($m);?>
		   <?php if ($this->selectedYear == '9999') : echo date('Y', $curTime); endif;?>
           <?php echo '</h3>';?>
           </td></tr>
           <?php echo $theader; ?>
           <?php endif;?>
           <?php endif;?>
           <?php /* -- Anzeige des Monatsnamens ENDE -- */ ?>

		   <?php if ($this->params->get('display_home_links','1')) : ?>
		   <tr class="link" onClick="parent.location='<?php echo JRoute::_($url_bericht . $this->layout_detail_link.'&id=' . (int)$item->id); ?>'">
		   <?php else:?>
		   <tr>
           <?php endif;?>

           <?php if ($this->params->get('display_home_number','1') or $this->params->get('display_home_alertimage_num','0')) : ?>
           <?php if ($this->params->get('display_home_marker','1')) : ?>
		   <td style="background-color:<?php echo $item->marker;?>;" >
           <?php else:?>
		   <td>
           <?php endif;?>
           <?php if ($this->params->get('display_home_number','1')) : ?>
            <?php echo $i;?>
           <?php endif;?>
           <?php if ($this->params->get('display_home_alertimage_num','0')) : ?>
           <br/><img class="img-rounded" style="width:30px; height:30px;" src="<?php echo $item->image;?>" title="<?php echo $item->alarmierungsart;?>" />
           <?php endif;?>
            </td>
           <?php endif;?>

           <?php if ($this->params->get('display_home_alertimage','0')) : ?>
		   <td style=" text-align:center;" >
           <img class="img-rounded" style="width:50px; height:50px;margin-right:2px;" src="<?php echo $item->image;?>" title="<?php echo $item->alarmierungsart;?>" />
           </td>
           <?php endif;?>

           <?php if ($this->params->get('display_home_date_image')) : ?>
		   <td>
			<div class="home_cal_icon">
			<div class="home_cal_monat"><?php echo date('M', $curTime);?></div>
			<div class="home_cal_tag"><?php echo date('d', $curTime);?></div>
			<div class="home_cal_jahr"><span style="font-size:10px;"><?php echo date('Y', $curTime);?></span></div>
			</div>
           </td>
           <?php endif;?>
           <?php if (!$this->params->get('display_home_date_image')) : ?>
		   <td> <?php echo date('d.m.Y', $curTime);?></td>
           <?php endif;?>

		   <td>

		   <?php if ($this->params->get('display_list_icon')) : ?>
           <img class="img-rounded" style="float:<?php echo $this->params->get('float_list_icon');?>;width:50px; height:50px;margin-right:2px;" src="<?php echo $item->list_icon;?>" />
           <?php endif;?>
		   <?php echo $item->einsatzart; ?>

           </td>

			<?php if ($this->params->get('display_home_links','1')) : ?>
			<td><a href="<?php echo JRoute::_($url_bericht . $this->layout_detail_link.'&id=' . (int)$item->id); ?>">
			<?php echo $item->summary;?>
			</a></td>
			<?php else: ?>
			<td><?php echo $item->summary;?></td>
			<?php endif; ?>

			<td><?php echo $item->address;?></td>

           <?php if ($this->params->get('display_home_orga','0')) : ?>
           <?php
					$data = array();
					foreach(explode(',',$item->auswahl_orga) as $value):
						$db = JFactory::getDbo();
						$query	= $db->getQuery(true);
						$query
							->select('name')
							->from('`#__eiko_organisationen`')
							->where('id = "' .$value.'"');
						$db->setQuery($query);
						$results = $db->loadObjectList();
						if(count($results)){
							$data[] = $results[0]->name;
						}
					endforeach;
					$auswahl_orga=  implode('</br>',$data);
?>
		   <td nowrap> <?php echo $auswahl_orga;?></td>
           <?php endif;?>

           <?php if ($this->params->get('display_home_image')) : ?>
		   <?php if ($item->foto) : ?>
		   <td class="mobile_image"> <img  class="img-rounded" style="width:<?php echo $this->params->get('display_home_image_width','80px');?>;" src="<?php echo $item->foto;?>"/></td>
           <?php endif;?>
		   <?php if (!$item->foto) : ?>
           <?php if ($this->params->get('display_home_image_nopic','0')) : ?>
		   <td class="mobile_image"> <img  class="img-rounded" style="width:<?php echo $this->params->get('display_home_image_width','80px');?>;" src="<?php echo 'images/com_einsatzkomponente/einsatzbilder/nopic.png';?>"/></td>
           <?php else:?>
			<td class="mobile_image">&nbsp;</td>
		   <?php endif;?>
           <?php endif;?>
           <?php endif;?>
		   </tr>

           <?php endif;?><?php endif;?><?php /* -- Filter Einsatzart -- */ ?>

    <?php endforeach; ?>
  <?php endif; ?>

<?php if(!$this->reports or !$show): ?>
<span class="label label-info"><b>Es können zu dieser Auswahl keine Daten in der Datenbank gefunden werden</b></span>
<?php endif; ?>


    <?php if ($this->params->get('display_home_map')) : ?>
    <tr><td colspan="<?php echo $col;?>">
    <h4>Einsatzgebiet</h4>
			<?php if ($this->params->get('gmap_action','0') == '1') :?>
  			<div id="map-canvas" style="width:100%; height:<?php echo $this->params->get('home_map_height','300px');?>;">
    		<noscript>Dieser Teil der Seite erfordert die JavaScript Unterstützung Ihres Browsers!</noscript>
			</div>
            <?php endif;?>
			<?php if ($this->params->get('gmap_action','0') == '2') :?>
<body onLoad="drawmap();">
   				<div id="map" style="width:100%; height:<?php echo $this->params->get('home_map_height','300px');?>;"></div>
    		<noscript>Dieser Teil der Seite erfordert die JavaScript Unterstützung Ihres Browsers!</noscript>
            <?php endif;?>
            </td></tr>
    <?php endif;?>
 </tbody>


    <tfoot>
    				<!--Prüfen, ob Pagination angezeigt wrden soll-->
    				<?php if ($this->params->get('display_home_pagination')) : ?>
    				<!--Prüfen, ob Einsatzart ausgwählt ist -->
                    <?php if ($this->selectedEinsatzart == '' or $this->selectedEinsatzart == 'alle Einsatzarten') : ?>
					<tr><td colspan="<?php echo $col;?>">
                    	<form action="#" method=post>
						<?php echo $this->pagination->getListFooter(); ?><!--Pagination anzeigen-->
						</form>
					</td></tr>
		   			<?php endif;?><!--Prüfen, ob Einsatzart ausgwählt ist ENDE-->
		   			<?php endif;?><!--Prüfen, ob Pagination angezeigt wrden soll   ENDE -->


</tfoot>
</table>

<?php /* Hidden input to keep compatibility with mod_einsatz_stats */ ?>
<input id="year" name="year" value="<?php echo $this->selectedYear; ?>" type="hidden">

<?php if(JFactory::getUser()->authorise('core.create','com_einsatzkomponente.einsatzbericht'.$item->id)): ?><a href="<?php echo JRoute::_('index.php?option=com_einsatzkomponente&view=einsatzberichtform&layout=edit&id=0'); ?>">Einsatz eintragen</a>
	<?php endif; ?>
