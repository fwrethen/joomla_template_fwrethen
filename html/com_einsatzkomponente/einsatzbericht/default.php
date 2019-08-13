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

$template_dir = 'templates/' . JFactory::getApplication()->getTemplate('template')->template;

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
	$img_html = '<li class="span12"><img src="'. $template_dir .'/images/default_image.jpg" class="img-rounded" /></li>';
else:
	foreach($images as $i=>$img):
		$class = ($i == 0) ? 'class="span12"' : 'class="span6"';
		if (array_key_exists($i, $thumbs)):
			$img_html .= '<li '. $class .'><a rel="lightbox[gallery]" href="'. $img .'"><img src="'. $thumbs[$i] .'" class="img-rounded" /></a></li>';
		else:
			$img_html .= '<li '. $class .'><a rel="lightbox[gallery]" href="'. $img .'"><img src="'. $img .'" class="img-rounded" /></a></li>';
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

if ($this->item->auswahl_orga):
	// if $this->item->auswahl_orga is a JObject, getProperties() returns its content.
	if (is_a($this->item->auswahl_orga, 'JOBject'))
		$orgas = implode(',', $this->item->auswahl_orga->getProperties());
	else
		$orgas = $this->item->auswahl_orga;
	$db = JFactory::getDbo();
	$query	= $db->getQuery(true);
	$query
		->select('id, name, link')
		->from('`#__eiko_organisationen`')
		->where('id IN (' .$orgas.') AND state="1" ORDER BY ordering');
	$db->setQuery($query);
	$results = $db->loadObjectList();
	$data = array();
	foreach($results as $result):
		$data[$result->id] = new StdClass;
		$data[$result->id]->name = $result->name;
		if (($result->link) && ($this->params->get('display_detail_orga_links','1'))):
			$data[$result->id]->link = $result->link;
		endif;
		$data[$result->id]->vehicles = array();
	endforeach;
	$orga_vehicles = $data;
endif;

if (!empty($orga_vehicles) && !empty($this->item->vehicles)):
	// if $this->item->vehicles is a JObject, getProperties() returns its content.
	if (is_a($this->item->vehicles, 'JObject'))
		$vehicles = implode(',', $this->item->vehicles->getProperties());
	else
		$vehicles = $this->item->vehicles;
	$db = JFactory::getDbo();
	$query	= $db->getQuery(true);
	$query
		->select('name, link, department')
		->from('`#__eiko_fahrzeuge`')
		->where('id IN (' .$vehicles.') AND state="1" ORDER BY ordering');
	$db->setQuery($query);
	$results = $db->loadObjectList();
	foreach($results as $result):
		$vehicle = new stdClass();
		$vehicle->name = $result->name;
		if (($result->link) && ($this->params->get('display_detail_fhz_links','1'))):
			$vehicle->link = $result->link;
		endif;
		if ($result->department):
			array_push($orga_vehicles[$result->department]->vehicles, $vehicle);
		else:
			if (!array_key_exists(-1, $orga_vehicles)):
				$orga_vehicles[-1] = new StdClass;
				$orga_vehicles[-1]->name = 'sonstige';
				$orga_vehicles[-1]->vehicles = array();
			endif;
			array_push($orga_vehicles[-1]->vehicles, $vehicle);
		endif;
	endforeach;
endif;

$presse1 = $this->item->presse;
$presse2 = $this->item->presse2;
$presse3 = $this->item->presse3;
$data = array();
for ($i=1;$i<=3;$i++):
	$var = 'presse'.$i;
	if ($$var):
		$data[] = '<a href="'.$$var.'" target="_blank" rel="nofollow"><img src="https://www.google.com/s2/favicons?domain='.parse_url($$var, PHP_URL_HOST).'" style="margin: 0px 8px 0px 0px; vertical-align: bottom;" />'.parse_url($$var, PHP_URL_HOST).'</a>';
	endif;
endfor;
$presse = implode('<br />',$data); ?>


<?php if( $this->item ) : ?>
	<style>
		.thumbnails [class*="span"]:nth-child(even){
			margin-left: 0;
		}

		.thumbnails img{
			width: 100%;
		}
	</style>

	<h3><?php echo $this->item->summary; ?></h3>
	<div class="row-fluid">
	<div class="span9">
	<div class="row-fluid">
	<div class="span6">
		<dl class="dl-horizontal">
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
	</div>
	<div class="span6">
		<?php if (!empty($orga_vehicles)): ?>
			<h5 style="margin:14px 0 0 0;"><?php echo JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_ORGAS'); ?></h5>
			<dl style="margin:0;">
				<?php foreach ($orga_vehicles as $orga): ?>

					<dt style="font-weight:inherit;">
						<?php if (property_exists($orga, 'link')): ?>
							<a target="_blank" href="<?php echo $orga->link; ?>">
								<?php echo $orga->name; ?>
							</a>
						<?php else: ?>
							<?php echo $orga->name; ?>
						<?php endif; ?>
					</dt>

					<dd style="margin-left:20px;">
						<?php foreach ($orga->vehicles as $vehicle): ?>
							<?php if (property_exists($vehicle, 'link')): ?>
								<a target="_blank" href="<?php echo $vehicle->link; ?>">
									<?php echo $vehicle->name; ?>
								</a>
							<?php else: ?>
								<?php echo $vehicle->name; ?>
							<?php endif; ?>
							<br />
						<?php endforeach; ?>
					</dd>

				<?php endforeach; ?>
			</dl>
		<?php endif; ?>
	</div>
	</div>
	<div class="row-fluid">
	<div class="span12">
		<?php if ($presse): ?>
			<hr />
			<dl>
				<dt><?php echo 'Presseberichte'; ?></dt>
				<dd><?php echo $presse; ?></dd>
			</dl>
		<?php endif; ?>
		<?php if ($this->item->desc): ?>
			<hr />
			<section><?php echo $this->item->desc; ?></section>
		<?php endif; ?>
	</div>
	</div>
	</div>
	<div class="span3">
		<ul class="thumbnails">
			<?php echo $img_html; ?>
		</ul>
	</div>
	</div>

	<script>document.getElementById("einsatzChart").parentNode.style.display = "none";</script>


<?php else: ?>
	<span class="label label-important">Einsatz kann nicht angezeigt werden</span>
<?php endif; ?>
