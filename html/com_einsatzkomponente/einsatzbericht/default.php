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
JHtml::_('stylesheet', $template_dir.'/css/lightbox.css');
JHtml::_('jquery.framework', false);
JHtml::_('script', $template_dir.'/js/lightbox.min.js');

if ((substr_count($this->item->image, 'nopic.png')) or !($this->item->image)):
	$image = $template_dir .'/images/default_image.jpg';
else:
	$image = $this->item->image;
endif;

$images = '';
$image_full = '';
for ($i=0; $i<count($this->images); $i++):
	$img_thumb = $this->images[$i]->thumb;
	$img_full = $this->images[$i]->image;
	if ($image == $img_thumb):
		$image_full = $img_full;
	elseif ($image == $img_full):
		$image = $img_thumb;
	else:
		$images = $images .'<a rel="lightbox[gallery]" href="'. $img_full .'"><img src="'. $img_thumb .'" /></a>';
	endif;
endfor;
if ($image_full):
	$image = '<a rel="lightbox[gallery]" href="'. $image_full .'"><img src="'. $image .'" id="title_image" /></a>';
else:
	$image = '<img src="'. $image .'" id="title_image" />';
endif;
$images = $image . $images;

$array = array();
foreach((array)$this->item->auswahlorga as $value):
	if(!is_array($value)):
		$array[] = $value;
	endif;
endforeach;
$data = array();
foreach($array as $value):
	$db = JFactory::getDbo();
	$query	= $db->getQuery(true);
	$query
		->select('name, link')
		->from('`#__eiko_organisationen`')
		->where('name = "' .$value.'" AND state="1" ORDER BY ordering');
	$db->setQuery($query);
	$results = $db->loadObjectList();
	if (($results[0]->link) && ($this->params->get('display_detail_orga_links','1'))):
		$data[] = '<a target="_blank" href="'. $results[0]->link .'">'. $results[0]->name .'</a>';
	else:
		$data[] = $results[0]->name;
	endif;
endforeach;
$auswahlorga = implode('<br />',$data);

$array = array();
foreach((array)$this->item->vehicles as $value):
	if(!is_array($value)):
		$array[] = $value;
	endif;
endforeach;
$data = array();
foreach($array as $value):
	$db = JFactory::getDbo();
	$query	= $db->getQuery(true);
	$query
		->select('name, link')
		->from('`#__eiko_fahrzeuge`')
		->where('id = "' .$value.'" AND state="1" ORDER BY ordering');
	$db->setQuery($query);
	$results = $db->loadObjectList();
	if (($results[0]->link) && ($this->params->get('display_detail_fhz_links','1'))):
		$data[] = '<a target="_blank" href="'. $results[0]->link .'">'. $results[0]->name .'</a>';
	else:
		$data[] = $results[0]->name;
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
		$data[] = '<a href="'.$$var.'" target="_blank"><img src="https://plus.google.com/_/favicon?domain='.parse_url($$var, PHP_URL_HOST).'" style="margin: 0px 8px 0px 0px; vertical-align: bottom;" />'.parse_url($$var, PHP_URL_HOST).'</a>';
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
			background-image: -webkit-linear-gradient(left, #eee, #999, #eee);
			background-image:    -moz-linear-gradient(left, #eee, #999, #eee);
			background-image:     -ms-linear-gradient(left, #eee, #999, #eee);
			background-image:      -o-linear-gradient(left, #eee, #999, #eee);
		}

		section.einsatz {
			margin-top: 1em;
		}
	</style>

	<h3><?php echo $this->item->summary; ?></h3>
	<div id="einsatz_images"><?php echo $images; ?></div>
	<div class="einsatz">
	<dl class="einsatz half">
		<dt><?php echo JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_DATA1'); ?></dt>
		<dd><?php echo $this->item->data1; ?></dd>
		<dt><?php echo JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_ADDRESS'); ?></dt>
		<dd><?php echo $this->item->address; ?></dd>
		<dt><?php echo JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_DATE1'); ?></dt>
		<dd><?php echo date('d.m.Y, H:i', strtotime($this->item->date1)); ?> Uhr</dd>
		<dt><?php echo JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_BOSS'); ?></dt>
		<dd><?php echo $this->item->boss; ?></dd>
		<dt><?php echo JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_PEOPLE'); ?></dt>
		<dd><?php echo $this->item->people; ?></dd>
	</dl>
	<dl class="einsatz half">
		<dt><?php echo 'alarmierte Einheiten'; ?></dt>
		<dd><?php echo $auswahlorga; ?></dd>
		<dt><?php echo JText::_('COM_EINSATZKOMPONENTE_FORM_LBL_EINSATZBERICHT_VEHICLES'); ?></dt>
		<dd><?php echo $vehicles; ?></dd>
	</dl>
	<div class="clr"></div>
	<?php if ($presse): ?>
	<hr class="einsatz" />
	<dl class="einsatz">
		<dt><?php echo 'Presseberichte'; ?></dt>
		<dd><?php echo $presse; ?></dd>
	</dl>
	<?php endif; ?>
	<hr class="einsatz" />
	<section class="einsatz"><?php echo $this->item->desc; ?></section>
	<div>
	<div class="clr"></div>


<?php else: ?>
    Could not load the item
<?php endif; ?>
