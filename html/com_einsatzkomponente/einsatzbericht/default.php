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

$template_dir = JURI::base() . 'templates/' . JFactory::getApplication()->getTemplate('template')->template;

$images = array();
$thumbs = array();
$titlepic = false;
// wenn Einsatz ein Attribut image hat, kommt dieses an die erste Stelle des Arrays
if (!(substr_count($this->item->image, 'nopic.png')) and ($this->item->image)):
	$images[] = $this->item->image;
	$titlepic = true;
endif;
// zugeordnete Bilder durchgehen
for ($i=0; $i<count($this->images); $i++):
	$img = $this->images[$i]->image;
	$thumb = $this->images[$i]->thumb;
	// wenn Titelbild darunter ist, volles Bild oder Thumb entspr zuordnen
	if (($titlepic) && (($images[0] == $img) or ($images[0] == $thumb))):
		$images[0] = $img;
		$thumbs[0] = $thumb;
		$titlepic = false;
	// ansonsten Bild an Array anhängen und Thumb in anderen Array mit gleichem Index
	else:
		//array_push returns size after push
		$thumbs[array_push($images, $img)-1] = $thumb;
	endif;
endfor;

// HTML basteln
$img_html = '';
// wenn kein erstes Element im Array, Platzhalterbild nutzen
if (!$images):
	$img_html = '<img src="'. $template_dir .'/images/default_image.jpg" id="title_image" />';
else:
	foreach($images as $i=>$img):
		$class = '';
		if ($i == 0):
			$class = ' id="title_image"';
		endif;
		if ($thumbs[$i]):
			$img_html .= '<a rel="lightbox[gallery]" href="'. $img .'"><img src="'. $thumbs[$i] .'" '. $class .' /></a>';
		else:
			$img_html .= '<a rel="lightbox[gallery]" href="'. $img .'"><img src="'. $img .'" '. $class .' /></a>';
		endif;
	endforeach;
endif;

//get Einsatzart from database
if ($this->item->data1):
	$db = JFactory::getDbo();
	$query	= $db->getQuery(true);
	$query
		->select('title')
		->from('`#__eiko_einsatzarten`')
		->where('id = "' .$this->item->data1.'" AND state="1" ORDER BY ordering');
	$db->setQuery($query);
	$results = $db->loadObjectList();
	$einsatzart = $results[0]->title;
endif;

// $this->item->vehicles is a JObject. getProperties() returns its content.
$orgas = implode(',', $this->item->auswahl_orga->getProperties());
$db = JFactory::getDbo();
$query	= $db->getQuery(true);
$query
	->select('name, link')
	->from('`#__eiko_organisationen`')
	->where('id IN (' .$orgas.') AND state="1" ORDER BY ordering');
$db->setQuery($query);
$results = $db->loadObjectList();
$data = array();
foreach($results as $result):
	if (($result->link) && ($this->params->get('display_detail_orga_links','1'))):
		$data[] = '<a target="_blank" href="'. $result->link .'">'. $result->name .'</a>';
	else:
		$data[] = $result->name;
	endif;
endforeach;
$auswahlorga = implode('<br />',$data);

// $this->item->vehicles is a JObject. getProperties() returns its content.
$vehicles = implode(',', $this->item->vehicles->getProperties());
$db = JFactory::getDbo();
$query	= $db->getQuery(true);
$query
	->select('name, link')
	->from('`#__eiko_fahrzeuge`')
	->where('id IN (' .$vehicles.') AND state="1" ORDER BY ordering');
$db->setQuery($query);
$results = $db->loadObjectList();
$data = array();
foreach($results as $result):
	if (($result->link) && ($this->params->get('display_detail_fhz_links','1'))):
		$data[] = '<a target="_blank" href="'. $result->link .'">'. $result->name .'</a>';
	else:
		$data[] = $result->name;
	endif;
endforeach;
$vehicles = implode('<br />',$data);

$presse1 = $this->item->presse;
$presse2 = $this->item->presse2;
$presse3 = $this->item->presse3;
$data = array();
for ($i=1;$i<=3;$i++):
	$var = 'presse'.$i;
	if ($$var):
		$data[] = '<a href="'.$$var.'" target="_blank" rel="nofollow"><img src="https://plus.google.com/_/favicon?domain='.parse_url($$var, PHP_URL_HOST).'" style="margin: 0px 8px 0px 0px; vertical-align: bottom;" />'.parse_url($$var, PHP_URL_HOST).'</a>';
	endif;
endfor;
$presse = implode('<br />',$data); ?>


<?php if( $this->item ) : ?>
	<style>
		div.einsatz {
			width: 66%;
			float: left;
		}

		div#einsatz_images {
			width: 33%;
			float: right;
		}

		div#einsatz_images img {
			width: 40%;
			padding: 5%;
/*			padding: 2px;
			-webkit-transition: all 0.5s ease;
			   -moz-transition: all 0.5s ease;
			     -o-transition: all 0.5s ease;
			    -ms-transition: all 0.5s ease;
			        transition: all 0.5s ease;*/
		}

		div#einsatz_images #title_image {
			width: 90%;
		}

		div#einsatz_images img:hover {
/*			background-color: #0C3A6D;*/
		}

		dl.half {
			width: 50%;
			float: left;
		}

		dl.einsatz dt {
			font-weight: bold;
		}

		dl.einsatz dt::after {
			content: ":";
		}

		dl.einsatz dd {
			margin-bottom: 0.8em;
		}

		hr.einsatz {
			border: 0;
			height: 1px;
			background-color: #CCC;
		}

		section.einsatz {
			margin-top: 1em;
			text-align: justify;
		}
	</style>

	<h3><?php echo $this->item->summary; ?></h3>
	<div id="einsatz_images"><?php echo $img_html; ?></div>
	<div class="einsatz">
	<dl class="einsatz half">
		<dt><?php echo JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_DATA1'); ?></dt>
		<dd><?php echo $einsatzart; ?></dd>
		<dt><?php echo JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_ADDRESS'); ?></dt>
		<dd><?php echo $this->item->address; ?></dd>
		<dt><?php echo JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_DATE1'); ?></dt>
		<dd><?php echo date('d.m.Y, H:i', strtotime($this->item->date1)); ?> Uhr</dd>
		<?php if ($this->item->boss): ?>
			<dt><?php echo JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_BOSS'); ?></dt>
			<dd><?php echo $this->item->boss; ?></dd>
		<?php endif; ?>
		<?php if ($this->item->people): ?>
			<dt><?php echo JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_PEOPLE'); ?></dt>
			<dd><?php echo $this->item->people; ?></dd>
		<?php endif; ?>
	</dl>
	<dl class="einsatz half">
		<dt><?php echo 'alarmierte Einheiten'; ?></dt>
		<dd><?php echo $auswahlorga; ?></dd>
		<?php if ($vehicles): ?>
			<dt><?php echo JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_VEHICLES'); ?></dt>
			<dd><?php echo $vehicles; ?></dd>
		<?php endif; ?>
	</dl>
	<div class="clr"></div>
	<?php if ($presse): ?>
	<hr class="einsatz" />
	<dl class="einsatz">
		<dt><?php echo 'Presseberichte'; ?></dt>
		<dd><?php echo $presse; ?></dd>
	</dl>
	<?php endif; ?>
	<?php if ($this->item->desc): ?>
	<hr class="einsatz" />
	<section class="einsatz"><?php echo $this->item->desc; ?></section>
	<?php endif; ?>
	<div>
	<div class="clr"></div>

	<script>document.getElementById("einsatzChart").parentNode.style.display = "none";</script>


<?php else: ?>
    Could not load the item
<?php endif; ?>
