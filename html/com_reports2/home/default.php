<?php
defined('_JEXEC') or die('Illegal Access');

// Funktion : GMap-Konigurationsdaten abrufen
    function reports_gmapconfig() {
$mainframe =& JFactory::getApplication();    // ### 110420 +
$option = JRequest::getCMD('option');        // ### 110420 +
$db		=& JFactory::getDBO();
$user = & JFactory::getUser();
$query = 'SELECT * FROM `#__reports_gmap` LIMIT 1';
$db->setQuery($query);
$gmapconfig = $db->loadObject();
        return $gmapconfig;
    }

// Funktion : reports_display Daten abrufen
    function reports_display() {
$db =& JFactory::getDBO();
$query = 'SELECT * FROM `#__reports_display`';
$db->setQuery($query);
$reports_display = $db->loadObject();
        return $reports_display;
    }
	
// Funktion : reports_config Daten abrufen
    function reports_config() {
$db =& JFactory::getDBO();
$query ='SELECT * FROM `#__reports_config` LIMIT 1';
$db->setQuery($query);
$config = $db->loadObject();
        return $config;
    }
	
// Funktion : Feuerwehrliste aus DB holen
    function feuerwehrliste() {
$db =& JFactory::getDBO();
$query = 'SELECT id, name as title,gmap_latitude as lat,gmap_longitude as lon,link as lin,detail1 FROM `#__reports_departments` WHERE published=1 ORDER BY `id`';
$db->setQuery($query);
$departmentDb = $db->loadObjectList();
        return $departmentDb;
    }
// Funktion : Liste der Einsatzarten aus DB holen
    function einsatzartenliste() {
$query = 'SELECT id, title as title FROM `#__reports_data` WHERE published=1 ORDER BY `id`';
$db =& JFactory::getDBO();
$db->setQuery($query);
$datasdb = $db->loadObjectList();
        return $datasdb;
    }
// Funktion : Anzahl der Einsätze für ein bestimmtes Jahr zählen
    function einsatz_anzahl_bestimmtes_jahr ($selectedYear) {
$database			=& JFactory::getDBO();
$query = 'SELECT COUNT(id) as total FROM #__reports WHERE date1 LIKE "'.$selectedYear.'%" AND published = "1" ' ;
$database->setQuery( $query );
$total = $database->loadObjectList();
        return $total;
    }
// Funktion : Einsatzdaten für ein bestimmtes Jahr aus der DB holen
    function einsatz_daten_bestimmtes_jahr ($selectedYear) {
$query = 'SELECT COUNT(r.id) as total,r.id,rd.marker,r.address,r.summary,r.date1,r.data1,r.counter,r.alerting,r.presse,re.image FROM #__reports r JOIN #__reports_data rd ON r.data1 = rd.title LEFT JOIN #__reports_alerting re ON re.id = r.alerting WHERE r.date1 LIKE "'.$selectedYear.'%" AND r.published = "1" GROUP BY r.id ORDER BY r.date1 DESC' ;
$database		=& JFactory::getDBO();
$database->setQuery( $query );
$reports = $database->loadObjectList();
        return $reports;
    }

// Funktion : Anzahl der Einsätze aller Jahre zählen
    function einsatz_anzahl_aller_jahre () {
$database			=& JFactory::getDBO();
$query = 'SELECT COUNT(id) as total FROM #__reports WHERE date1 LIKE "2___%" AND published = "1" ' ;
$database->setQuery( $query );
$total = $database->loadObjectList();	
        return $total;
    }
// Funktion : Einsatzdaten für alle Jahre aus der DB holen
    function einsatz_daten_aller_jahre () {
$database			=& JFactory::getDBO();
$query = 'SELECT COUNT(r.id) as total,r.id,rd.marker,r.address,r.summary,r.date1,r.data1,r.counter,r.alerting,r.presse,re.image FROM #__reports r JOIN #__reports_data rd ON r.data1 = rd.title LEFT JOIN #__reports_alerting re ON re.id = r.alerting WHERE r.date1 LIKE "2___%" AND r.published = "1" GROUP BY r.id ORDER BY r.date1 DESC' ;
$database->setQuery( $query );
$reports = $database->loadObjectList();
        return $reports;
    }
// Funktion : Das erste Bild aus der Gallerie als Bild in der Übersicht
    function startbild ($reports) {
$i = 0;
while($i < count($reports))
{
$db =& JFactory::getDBO();
$query = 'SELECT image FROM `#__reports_images` WHERE report_id='.$reports[$i]->id;
$db->setQuery($query);
$foto[] = $db->loadresult();
if ($foto[$i] =='') {$foto[$i]='0';} 
$i++; 
} 
        return $foto;
    }
	
// Funktion : Modul aufrufen mit Position $modulepos
    function module ($modulepos) {
$document = &JFactory::getDocument();
$renderer = $document->loadRenderer( 'modules' );
$options = array( 'style' => 'xhtml' );
echo $renderer->render( $modulepos, $options, null); 
        
    }







//-------------------------------------------------------------------------------------------------------------------------------------------------------------------

$mainframe =& JFactory::getApplication();    // ### 110420 +
$option = JRequest::getCMD('option');        // ### 110420 +
$path=JURI::base();
$Itemid=JRequest::getInt('Itemid');
$link= JRoute::_('index.php?option='.$option);
//$params = &JComponentHelper::getParams(com_reports2); // ### 110521 -  Parameter werden jetzt über $menuid->query[anzeigejahr]; geholt
$menus  = &JApplication::getMenu('site', array());
$menuid = $menus->getActive();
JHTML::_('stylesheet','report.css',$path.'templates/fwrethen_2.5/html/com_reports2/assets/css/');

$prevMonth ='';




//----------------------------------------------------------------
$config = reports_config(); // Funktion : reports_config Daten abrufen
$baseUploadDir = !empty($config->imagepath) ? $config->imagepath : 'images'.DS.$option.DS.'gallery'; // Bilder-Verzeichniss
//----------------------------------------------------------------
$reports_display = reports_display(); // Funktion : reports_display Daten abrufen



$selectedYear='2009';

$actmonth = $menuid->query[actmonth]; #120304 Menüparameter : Nur Einsätze des aktuellen Monats anzeigen lassen ?

//----Monat-Parameter aus Übergabe auslesen----
$Monat='';
if(!isset($_GET['Monat'])) $_GET['Monat']=$Monat;
if($Monat == '')
    {
if ($actmonth=='1')
{		
$datum = getdate(); #120304 aktuelles Datum auslesen, um nur aktuelle Einsätze des Montas anzeigen zu lassen
$Monat = JRequest::getInt('Monat',$datum[mon]); 
}
else
{
$Monat = JRequest::getInt('Monat');
	}

	}

$selectedYear = $menuid->query[anzeigejahr];

if($selectedYear == '')
    {
$selectedYear = ($this->total) ? JRequest::getInt('year', date('Y')) : 0;
if(!isset($_GET['Monat'])) $_GET['Monat']=$Monat;
    }
	
// ---------------RSS - PDF ---------------------------------------------
if($config->rss_feed=='1') //  ## 110202 RSS-link geändert 
echo '<div style="float:right;" ><a href="'.$path.'index.php?option=com_reports2&view=home&format=feed&type=rss"><img src="'.$path.'components/com_reports2/assets/livemarks.png" border="0" alt="feed-image"></a></div>';


//---------------Daten für Hauptkomponente holen--------------------------
if ($selectedYear != "9999")
{
$total = einsatz_anzahl_bestimmtes_jahr ($selectedYear); // Funktion : Anzahl der Einsätze für ein bestimmtes Jahr zählen
$totalRecords = $total[0]->total;

if($totalRecords < '1') // Falls keine Einsätze in dem Jahr vorhanden sind, dann Jahr -1 und neue Daten laden
{
$selectedYear = $selectedYear-1;
$total = einsatz_anzahl_bestimmtes_jahr ($selectedYear); // Funktion : Anzahl der Einsätze für ein bestimmtes Jahr zählen
$totalRecords = $total[0]->total;
}


$reports = einsatz_daten_bestimmtes_jahr ($selectedYear); // Funktion : Einsatzdaten für ein bestimmtes Jahr aus der DB holen
}
else
{
$total = einsatz_anzahl_aller_jahre (); // Funktion : Anzahl der Einsätze aller Jahre zählen
$totalRecords = $total[0]->total;
$reports = einsatz_daten_aller_jahre (); // Funktion : Einsatzdaten für alle Jahre aus der DB holen
}

$foto = startbild ($reports); // Funktion : Das erste Bild aus der Gallerie als Bild in der Übersichtliste	

//----------------------------------------------------------------------------



//----Monatsnamen auf Deutsch---- 
$monate = array(  '0'=>JTEXT::_('COM_REPORTS2_H_M00'),
				  '1'=>JTEXT::_('COM_REPORTS2_H_M01'),
                  '2'=>JTEXT::_('COM_REPORTS2_H_M02'),
                  '3'=>JTEXT::_('COM_REPORTS2_H_M03'),
                  '4'=>JTEXT::_('COM_REPORTS2_H_M04'),
                  '5'=>JTEXT::_('COM_REPORTS2_H_M05'),
                  '6'=>JTEXT::_('COM_REPORTS2_H_M06'),
                  '7'=>JTEXT::_('COM_REPORTS2_H_M07'),
                  '8'=>JTEXT::_('COM_REPORTS2_H_M08'),
                  '9'=>JTEXT::_('COM_REPORTS2_H_M09'),
                  '10'=>JTEXT::_('COM_REPORTS2_H_M10'),
                  '11'=>JTEXT::_('COM_REPORTS2_H_M11'),
				  '12'=>JTEXT::_('COM_REPORTS2_H_M12'));
echo '<table class="homelogo" bgcolor="#'.$config->farbe4.'">';



//----Logo Einsatzjahr----
if ($config->home1 == 1)
{
if($selectedYear == '0' or $selectedYear == '9999')
{
echo '<tr><td class="homelogo"><img src="'.$path.'components/'.$option.'/images/years/home0.png" alt="home0" width="'.$config->logow.'" height="'.$config->logoh.'" /></td></tr>';
}
else
{
echo '<tr><td class="homelogo"><img src="'.$path.'components/'.$option.'/images/years/home'.$selectedYear.'.png" alt="'.$selectedYear.'" width="'.$config->logow.'" height="'.$config->logoh.'" /></td></tr>';
}
}
// ---------------FILTER--------------------------------------------
if ($menuid->query[abfragewehr] != "1")
{
$selectedDepartment = JRequest::getInt('department', '');
}
else
{
$selectedDepartment = $menuid->query[anzeigewehr];
}
$departmentDb = feuerwehrliste(); // Funktion : Feuerwehrliste aus DB holen
$departments[] = JHTML::_('select.option', '', JTEXT::_('COM_REPORTS2_H_UNIT') , 'id', 'title');
if (count($departmentDb)) $departments = array_merge($departments, $departmentDb);
$departmentList = JHTML::_('select.genericlist', $departments, 'departments[]', ' id="input_department" ', 'id', 'title');

$selectedData = JRequest::getInt('data', '');
$datasdb = einsatzartenliste (); // Funtkion : Liste der Einsatzarten aus der DB holen
$datas[] = JHTML::_('select.option', '', JTEXT::_('COM_REPORTS2_H_MISSIONDESCRIPTION') , 'id', 'title');
$datas = array_merge($datas, $datasdb);

//----Auswahlbox Jahre----
if ($menuid->query[anzeigejahr] != "" )
{
echo '<td class="homefilter" ><form action='.$link.' method=post>';
}
else
{
if ($config->hall != "1")
{
$years[] = JHTML::_('select.option', '', JTEXT::_('COM_REPORTS2_H_YEAR')  , 'id', 'title');
}
else
{
$years[] = JHTML::_('select.option', '9999', JTEXT::_('COM_REPORTS2_H_YEAR') , 'id', 'title');
}
//$years[] = JHTML::_('select.option', '9999', ' - alle Jahre - ', 'id', 'title');

$years = array_merge($years, (array)$this->years);
echo '<td class="homefilter"><form action='.$link.' method=post>';
echo '<strong>'.JText::_('COM_REPORTS2_H_SELECT').'</strong> &nbsp; <p></p>'.JHTML::_('select.genericlist',  $years, 'year', ' onchange=submit(); ', 'id', 'title', $selectedYear);

if ($selectedYear != "9999")
{
  $Mona[] = JHTML::_('select.option', '0', JTEXT::_('COM_REPORTS2_H_M00'),'id','title');
  $Mona[] = JHTML::_('select.option', '1', JTEXT::_('COM_REPORTS2_H_M01'),'id','title' ); 
  $Mona[] = JHTML::_('select.option', '2', JTEXT::_('COM_REPORTS2_H_M02'),'id','title' ); 
  $Mona[] = JHTML::_('select.option', '3', JTEXT::_('COM_REPORTS2_H_M03'),'id','title' ); 
  $Mona[] = JHTML::_('select.option', '4', JTEXT::_('COM_REPORTS2_H_M04'),'id','title' ); 
  $Mona[] = JHTML::_('select.option', '5', JTEXT::_('COM_REPORTS2_H_M05'),'id','title' ); 
  $Mona[] = JHTML::_('select.option', '6', JTEXT::_('COM_REPORTS2_H_M06'),'id','title' ); 
  $Mona[] = JHTML::_('select.option', '7', JTEXT::_('COM_REPORTS2_H_M07'),'id','title' ); 
  $Mona[] = JHTML::_('select.option', '8', JTEXT::_('COM_REPORTS2_H_M08'),'id','title' ); 
  $Mona[] = JHTML::_('select.option', '9', JTEXT::_('COM_REPORTS2_H_M09'),'id','title' ); 
  $Mona[] = JHTML::_('select.option', '10', JTEXT::_('COM_REPORTS2_H_M10'),'id','title' ); 
  $Mona[] = JHTML::_('select.option', '11', JTEXT::_('COM_REPORTS2_H_M11'),'id','title' ); 
  $Mona[] = JHTML::_('select.option', '12', JTEXT::_('COM_REPORTS2_H_M12'),'id','title' ); 
  $olistt = JHTML::_('select.genericlist', $Mona, 'Monat', ' onchange=submit(); ','id', 'title', $Monat);
echo $olistt;
}
else {$all='1';}
}
if ($menuid->query[abfragewehr] != "1")
{
if ($config->ffwFilter != 0) 
{ 	
echo ''.JHTML::_('select.genericlist',  $departments, 'department', ' onchange=submit(); ', 'id', 'title', $selectedDepartment);
}
}
else
{
}
if ($config->einsatzFilter != 0) 
{
echo ''.JHTML::_('select.genericlist',  $datas, 'data', ' onchange=submit(); ', 'id', 'title', $selectedData);
}

echo '</form></td>';
echo '</table>';



//----Modulposition 'reportstats' für Statistikanzeige----
if($selectedYear == '0' or $totalRecords < '1')
{
?>
<div style="font-size:14px;color:#000000;background-color:#ff0000;"><?php echo JTEXT::_('COM_REPORTS2_H_TEXT1').' '.$selectedYear.' '.JTEXT::_('COM_REPORTS2_H_TEXT2').' '.$config->feuerwehr.' '.JTEXT::_('COM_REPORTS2_H_TEXT3') ?></div>
<?php	
}
else
{
	?>
    <table class="homemodule" cellspacing=0 cellpadding=0><tr><td class="homemodule">
    <?php
$modulepos = 'reportstats'; // Modulposition für Statistikanzeige 
module ($modulepos); // Funktion : Modul aufrufen mit Position $modulepos
?>
</td></tr></table>
<?php
}

//----Einsatzkomponente----

    $i = 0; // zum Zählen der Gesamtdatensätze
    $a = 0; // zum Zählen der Datensätze des jeweiligen Monats
	while($i < count($reports))
   {
		$curTime = strtotime($reports[$i]->date1);
		$curMonth = date('M', $curTime);
		if ($selectedYear != "9999")
            {$curYear= '';}
        else
{
		     $curYear = date('Y', $curTime);}
			 
    	     $monat = date('n', $curTime);
if ($monat == $Monat or $Monat == 0)
		{
		//----Daten für selektierte Feuerwehr laden -----
        $query = 'SELECT department_id FROM `#__reports_departments_link` WHERE report_id = '.$reports[$i]->id.' AND department_id ='.$selectedDepartment.' LIMIT 1';
        $db =& JFactory::getDBO();
        $db->setQuery($query);
        $selectwehr = $db->loadresult();
		
	    if ($selectwehr == $selectedDepartment) 
	    {   // Anfang Schleife Feuerwehr-Abfrage
	
		//----Daten für selektierte Einsätze laden -----
        $query = 'SELECT title FROM `#__reports_data` WHERE id = '.$selectedData.' LIMIT 1';
        $db->setQuery($query);
        $selecteinsatz = $db->loadresult();
		if ($selectedData == '0' or $selecteinsatz == $reports[$i]->data1) 
		{   // Anfang Schleife Einsatz-Abfrage
		
		if (($curMonth != $prevMonth) || ($curYear != $prevYear))
		{
					if (($i>0)&&($a>0)) // wenn schon Datensätze generell ($i) und des vorigen Monats ($a) vorhanden, dann schließe Tabelle vor Start des nächsten Monats
					{
						$a = 0; // $a auf 0 und beim aktuellen Monat wieder neu zählen
						?>
						</table>
						<?php
					}
		if ($config->counter == 1)
		{
		if ($config->home2 == 1)
		{
			?>
            <table class="homeview" width=<?php echo $config->hometab;?> bgcolor="#<?php echo $config->farbe3;?>" cellspacing="0" cellpadding="5">
			<tr><th class="homeview" height="30px" bgcolor="#<?php echo $config->farbe1;?>" colspan="7" style="font-size:140%"><?php echo $monate[$monat];?>&nbsp;<?php echo $curYear;?></th></tr>
			<tr bgcolor="#<?php echo $config->farbe2;?>"><th class="homeview"><?php echo JText::_('COM_REPORTS2_H_ITEM'); ?></th><th class="homeview"><?php echo JText::_('COM_REPORTS2_H_ALERTING'); ?></th>  <th class="homeview" colspan=2><?php echo JText::_('COM_REPORTS2_H_DESCRIPTION'); ?></th> <th class="homeview"><?php echo JText::_('COM_REPORTS2_H_LOCATION'); ?></th> <th class="homeview">&nbsp;</th> <th class="homeview"><?php echo JTEXT::_('COM_REPORTS2_H_VIEWS') ?></th> </tr>
            <?php
			}
			else
			{
			?>
			<table class="homeview" width=<?php echo $config->hometab;?> bgcolor="#<?php echo $config->farbe3;?>" cellspacing="0" cellpadding="4">
			<tr><th class="homeview" height="24px" bgcolor="#<?php echo $config->farbe1;?>" colspan="6" style="font-size:20px"><?php echo $monate[$monat];?>&nbsp;<?php echo $curYear;?></th></tr>
			<tr bgcolor="#<?php echo $config->farbe2;?>"><th class="homeview" ><?php echo JText::_('COM_REPORTS2_H_ITEM'); ?></th><th class="homeview" ><?php echo JText::_('COM_REPORTS2_H_ALERTING'); ?></th>  <th class="homeview" colspan=2><?php echo JText::_('COM_REPORTS2_H_DESCRIPTION'); ?></th> <th class="homeview"><?php echo JText::_('COM_REPORTS2_H_LOCATION'); ?></th> <th class="homeview"><?php echo JTEXT::_('COM_REPORTS2_H_VIEWS') ?></th> </tr>
            <?php
			}
			}
			else
			{
			if ($config->home2 == 1)
			{
			?>
			<table class="homeview" width=<?php echo $config->hometab;?> bgcolor="#<?php echo $config->farbe3;?>" cellspacing="0" cellpadding="4">
			<tr><th class="homeview" height="24px" bgcolor="#<?php echo $config->farbe1;?>" colspan="6" style="font-size:20px"><?php echo $monate[$monat];?>&nbsp;<?php echo $curYear;?></th></tr>
			<tr bgcolor="#<?php echo $config->farbe2;?>"><th class="homeview"><?php echo JText::_('COM_REPORTS2_H_ITEM'); ?></th><th class="homeview" ><?php echo JText::_('COM_REPORTS2_H_ALERTING'); ?></th>  <th class="homeview" colspan=2><?php echo JText::_('COM_REPORTS2_H_DESCRIPTION'); ?></th> <th class="homeview" ><?php echo JText::_('COM_REPORTS2_H_LOCATION'); ?></th> <th class="homeview" >&nbsp;</th> </tr>
            <?php
			}
			else
			{
			?>
			<table class="homeview" width=<?php echo $config->hometab;?> bgcolor="#<?php echo $config->farbe3;?>" cellspacing="0" cellpadding="3">
			<tr><th class="homeview" height="24px" bgcolor="#<?php echo $config->farbe1;?>" colspan="6" style="font-size:20px"><?php echo $monate[$monat];?>&nbsp;<?php echo $curYear;?></th></tr>
			<tr bgcolor="#<?php echo $config->farbe2;?>"><th class="homeview" ><?php echo JText::_('COM_REPORTS2_H_ITEM'); ?></th><th class="homeview" ><?php echo JText::_('COM_REPORTS2_H_ALERTING'); ?></th>  <th class="homeview" colspan=2><?php echo JText::_('COM_REPORTS2_H_DESCRIPTION'); ?></th> <th class="homeview"><?php echo JText::_('COM_REPORTS2_H_LOCATION'); ?></th> <th class="homeview" >&nbsp;</th> </tr>
            <?php
			}
			}
			$prevMonth = $curMonth;
			$prevYear = $curYear;
					$a++;  // Einsätze pro Monat hochzählen
		}
        $rSummary = $reports[$i]->summary;
	    $rSummary = (strlen($rSummary) > $config->maxchar) ? substr($rSummary,0,strrpos(substr($rSummary,0,$config->maxchar+1),' ')) : $rSummary;
		echo  '<tr>';
		$marker=$reports[$i]->marker;
		if ($config->hmarker == 1) 
		{
		?>
		<td class="homemarker" width=3%  style="color:#<?php echo $config->farbe5;?>;" bgcolor="#<?php echo $marker;?>"><?php echo $totalRecords;?></td>
        <?php
		}
		else
		{
		?>
		<td class="homemarker" width=3% style="color:#<?php echo $config->farbe5;?>;"><?php echo $totalRecords;?></td>
        <?php
		}
				if ($config->home_time == 0) 
				{
				?>
		        <td class="homealarm"><?php echo date('d.m.Y ', $curTime); ?> 
                <?php
				}
				else
				{
				?>
		        <td class="homealarm"><?php echo date('d.m.Y H:i', $curTime); ?> 
                <?php
		        }
				if(($reports_display->alerting !='0') and ($reports[$i]->alerting !=''))
		        {
		        if($reports[$i]->image !='')
		        {
				?>
				<img src="<?php echo $path;?><?php echo $config->alertingupload;?>/<?php echo $reports[$i]->image;?>" width="32px" height="32px" />
                <?php
				}
		        }
				?>
				
		        </td>
<td class="homelink" width=20%><?php echo $reports[$i]->data1;?></td>
<td class="homelink" width=40%><?php echo $reports[$i]->summary;?></td>
<td class="homeaddress" width=25%><?php echo $reports[$i]->address;?></td>
<td class="homelink"><?php echo '<a href="'.JRoute::_('index.php?option='.$option.'&view=show&Itemid='.$menuid->id.'&id='.$reports[$i]->id).'&Monat='.$Monat.'&department='.$selectedDepartment.'&data='.$selectedData.'&all='.$all.'">Details</a>';?></td>
<?php
			
//----wenn Fotos vorhanden, dann das 1.Bild aus Gallerie als Thumbnail anzeigen----------------------------------------------------
if ($config->home2 == 1)
{
if($config->vpresse == 1)
{
if($reports[$i]->presse =='')
{
if($foto[$i] > '0')
{

echo  '<td class="homefoto" width=50px nowrap="nowrap"><a href="'.JRoute::_('index.php?option=com_reports2&view=show&Itemid='.$menuid->id.'&id='.$reports[$i]->id).'&Monat='.$Monat.'&department='.$selectedDepartment.'&data='.$selectedData.'&all='.$all.'">';
?>
<img border=0 src="<?php echo $baseUploadDir; ?>/<?php echo $foto[$i];?>" width="50" height="40" /></a></td>
<?php
}
else
{
echo  '<td class="homefoto" width=50px nowrap="nowrap"><a href="'.JRoute::_('index.php?option=com_reports2&view=show&Itemid='.$menuid->id.'&id='.$reports[$i]->id).'&Monat='.$Monat.'&department='.$selectedDepartment.'&data='.$selectedData.'&all='.$all.'">';
?>
<img border=0 src="<?php echo $path;?>components/<?php echo $option; ?>/images/noimage.png" width="50" height="40" /></td>
<?php 
}
}
else
{
?>
<td class="homefoto" width=50px nowrap="nowrap"><a href="<?php echo $reports[$i]->presse; ?>" target="_blank"><img border=0 src="<?php echo $path;?>components/<?php echo $option; ?>/images/presse.png" width="50" height="40" /></a></td>
<?php
}
}
else
{
if($foto[$i] =='0')
{
if($reports[$i]->presse !='')
{
?>
<td class="homefoto" width=50px nowrap="nowrap"><a target="_blank" href="<?php echo $reports[$i]->presse; ?>"><img border=0 src="<?php echo $path;?>components/<?php echo $option; ?>/images/presse.png" width="50" height="40" /></a></td>
<?php
}
else
{
echo  '<td class="homefoto" width=50px nowrap="nowrap"><a href="'.JRoute::_('index.php?option=com_reports2&view=show&Itemid='.$menuid->id.'&id='.$reports[$i]->id).'&Monat='.$Monat.'&department='.$selectedDepartment.'&data='.$selectedData.'&all='.$all.'">';
?>
<img border=0 src="<?php echo $path;?>components/<?php echo $option; ?>/images/noimage.png" width="50" height="40" /></td>
<?php
}
}
else
{
echo  '<td class="homefoto" width=50px nowrap="nowrap"><a href="'.JRoute::_('index.php?option='.$option.'&view=show&Itemid='.$menuid->id.'&id='.$reports[$i]->id).'&Monat='.$Monat.'&department='.$selectedDepartment.'&data='.$selectedData.'&all='.$all.'">';
?>
<img border=0 src="<?php echo $baseUploadDir; ?>/<?php echo $foto[$i];?>" width="50" height="40" /></a></td>
<?php
}
}
}
// --------------- Zugriffe --------------------------------------------------------------------------------- 	
if ($config->counter == 1)
{
?>
<td class="homecounter" width=5% nowrap="nowrap"><?php echo $reports[$i]->counter;?></td>
<?php
}
else
{
}
		}}
		
		}// Ende Feuerwehr-Abfrage-Schleife und Einsatzart-Abfrage-Schleife
		$i++;$totalRecords=$totalRecords-1;
?></tr><?php // ###110920+ Bug behoben -> Tabellezeile wurde nicht richtig geschlossen

		}
if ($a>0) // wenn Monatsdatensätze vorhanden, muß Tabelle noch geschlossen werden
{
	?>
	</table>
	<?php
} else if (($i>0)&& ($a==0)) {
	?>
	<table class="homeview" width=<?php echo $config->hometab;?> bgcolor="#<?php echo $config->farbe3;?>" cellspacing="0" cellpadding="4">
	<tr><th class="homeview" height="30px" bgcolor="#<?php echo $config->farbe1;?>" style="font-size:140%">F&uuml;r diesen Zeitraum sind keine Daten vorhanden</th></tr></table>
	<br /><br />
	<?php	
}


//---------------- Google Map -------------------------


if ($config->hmap == 1) {
if ($config->homehtml != "")
{
echo $config->homehtml;
}
else
{
//----------------------------------------------------------------
$gmapconfig = reports_gmapconfig();  // Funktion : GMAP - Konfigurationsdaten abrufen 
$alarmareas1  = $gmapconfig->gmap_alarmarea; // Einsatzgebiet  ---->
$alarmareas = explode('|', $alarmareas1);
$stralarmarea='[ ';
	$n=0;
	for($i = 0; $i < count($alarmareas); $i++)
	{
		if($i==$n-1)
		{
			$stralarmarea=$stralarmarea.'new google.maps.LatLng('.$alarmareas[$i].')';
		}
		else
		{
			$stralarmarea=$stralarmarea.'new google.maps.LatLng('.$alarmareas[$i].'),';
		}
	}
$stralarmarea=substr($stralarmarea,0,strlen($stralarmarea)-1);
$stralarmarea=$stralarmarea.' ];';
 
$feuerwehren='['; // Feuerwehr Details  ------>
	$n=0;
	for($i = 0; $i < count($departmentDb); $i++)
	{
		if($i==$n-1)
		{
			$feuerwehren=$feuerwehren.'["'.$departmentDb[$i]->title.'",'.$departmentDb[$i]->lat.','.$departmentDb[$i]->lon.','.$i.','.$departmentDb[$i]->lin.']';
		}
		else
		{
			$feuerwehren=$feuerwehren.'["'.$departmentDb[$i]->title.'",'.$departmentDb[$i]->lat.','.$departmentDb[$i]->lon.','.$i;
			if ($departmentDb[$i]->lin !='') {
			$feuerwehren=$feuerwehren.',"'.$departmentDb[$i]->detail1.'<p></p><a href='.$departmentDb[$i]->lin.'>zur Webseite</a>"'; }
			else
			{$feuerwehren=$feuerwehren.',"'.$departmentDb[$i]->detail1.'"'; }
			$feuerwehren=$feuerwehren.'],';
		}
	}
$feuerwehren=substr($feuerwehren,0,strlen($feuerwehren)-1);
$feuerwehren=$feuerwehren.' ];';
 //----------------------------------------------------------------------
?>
<table class="homemap" width=<?php echo $config->hometab;?> cellspacing=0 cellpadding=0><tr><td class="homemap"><?php echo JTEXT::_('COM_REPORTS2_H_MAP').' '.$config->feuerwehr;?></td></tr>





<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3&sensor=false"></script>
<script type="text/javascript"> 
var FFVMOD = {
  map: null,
  markers: [],
  standorte: [],
  polyline: null,
  polygon: null
};
var alarmarea;
  
/**
 * Shows or hides all marker overlays on the map.
 */
FFVMOD.toggleMarkers = function(opt_enable) {
  if (typeof opt_enable == 'undefined') {
    opt_enable = !FFVMOD.markers[0].getMap();
  }
  for (var n = 0, marker; marker = FFVMOD.markers[n]; n++) {
    marker.setMap(opt_enable ? FFVMOD.map : null);
  }
};

FFVMOD.toggleStandorte = function(opt_enable) {
  if (typeof opt_enable == 'undefined') {
    opt_enable = !FFVMOD.standorte[0].getMap();
  }
  for (var n = 0, marker; marker = FFVMOD.standorte[n]; n++) {
    marker.setMap(opt_enable ? FFVMOD.map : null);
  }
};

/**
 * Shows or hides the polyline overlay on the map.
 */
FFVMOD.togglePolyline = function(opt_enable) {
  if (typeof opt_enable == 'undefined') {
    opt_enable = !FFVMOD.polyline.getMap();
  }
  FFVMOD.polyline.setMap(opt_enable ? FFVMOD.map : null);
};
 
/**
 * Shows or hides the polygon overlay on the map.
 */
FFVMOD.togglePolygon = function(opt_enable) {
  if (typeof opt_enable == 'undefined') {
    opt_enable = !FFVMOD.polygon.getMap();
  }
  FFVMOD.polygon.setMap(opt_enable ? FFVMOD.map : null);
};
 
FFVMOD.toggleAllOverlays = function() {
  var enable = true;
  if (FFVMOD.markers[0].getMap() ||
      FFVMOD.polyline.getMap() ||
      FFVMOD.polygon.getMap()) {
    enable = false;
  }
//  FFVMOD.toggleMarkers(enable);
  FFVMOD.togglePolyline(enable);
  FFVMOD.togglePolygon(enable);
};
 
/**
 * Called only once on initial page load to initialize the map.
 */
FFVMOD.init = function() {
  // Create single instance of a Google Map.
  FFVMOD.map = new google.maps.Map(document.getElementById('map-canvas'), {
      zoom: <?php echo $config->gmap_zoom_level_home;?>,
      center: new google.maps.LatLng(<?php echo $gmapconfig->start_lat;?>, <?php echo $gmapconfig->start_lang;?>),
      mapTypeControl: true,
      scrollwheel: false,
      mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
      navigationControl: true,
      navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
<?php if ($config->hhybrid == 0) { ?>
        mapTypeId: google.maps.MapTypeId.ROADMAP <?php } else {?>
        mapTypeId: google.maps.MapTypeId.HYBRID <?php }?>  });
  // Create multiple Marker objects at various positions.
  var markerPositions = <?php echo $stralarmarea;?>
  var alarmarea = <?php echo $stralarmarea;?>
  
  for (var n = 0, latLng; latLng = markerPositions[n]; n++) {
    var marker = new google.maps.Marker({
      position: latLng
    });
    
    // Add marker to collection.
    FFVMOD.markers.push(marker);
  }
  
  // Create a polyline connected alarmarea.
  FFVMOD.polyline = new google.maps.Polyline({
    path: alarmarea,
    strokeColor: '#ff0000',
    strokeWeight: 3
  });
  
  // Create a polyline connected alarmarea.
  FFVMOD.polygon = new google.maps.Polygon({
    path: alarmarea,
    fillOpacity: 0.2,
    strokeColor: '#ff0000',
    strokeWeight: 3,
    fillColor: '#f00000'
  });
  
function createMarker(latlng, label, html,index,image) {
    var contentString = '<div class="infowindow"><span class="infowindowlabel">'+label+'</span><br>'+html+'</div>';
    var marker = new google.maps.Marker({
        position: latlng,
        map: FFVMOD.map,
        icon: image,
        title: label,
        zIndex: index
        });
 
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(contentString); 
        infowindow.open(FFVMOD.map,marker);
        });
    FFVMOD.standorte.push(marker);
}



var homes = <?php echo $feuerwehren;?>  
var infowindow = new google.maps.InfoWindow(
  { 
    size: new google.maps.Size(150,50)
  });
  var image = new google.maps.MarkerImage('<?php echo JURI::root()."components/com_reports2/images/map/haus_rot.png";?>');
  for (var i = 0; i < homes.length; i++) {
    var homi = homes[i];
    var myLatLng = new google.maps.LatLng(homi[1], homi[2]);
    var marker = createMarker(myLatLng,homi[0],homi[4],homi[3],image);
    
  }

//var image = '<?php echo JURI::root()."components/com_reports2/images/map/haus_rot.png";?>';
//var ffhome = new google.maps.Marker({       
//									position: new google.maps.LatLng(<?php echo $gmapconfig->start_lat;?>, <?php echo $gmapconfig->start_lang;?>),     
//									title:"Hello World!",
//									map: FFVMOD.map,
//									icon: image
//									});      
//// To add the marker to the map, call setMap();   
//ffhome.setMap(FFVMOD.map);   
  
  
  
  // Initially show all overlays.
  FFVMOD.toggleAllOverlays();
      google.maps.event.addListener(FFVMOD.map, 'click', function() {
        infowindow.close();
        });
};
 
// Call the init function when the page loads.
google.maps.event.addDomListener(window, 'load', FFVMOD.init);
</script>
<!--  <div>
    Toggle on/off:
    <input onclick="FFVMOD.toggleStandorte();" type=button value="Standorte"/>
    <input onclick="FFVMOD.toggleMarkers();" type=button value="Markers"/>
    <input onclick="FFVMOD.togglePolyline();" type=button value="Polyline"/>
    <input onclick="FFVMOD.togglePolygon();" type=button value="Polygon"/>
    <input onclick="FFVMOD.toggleAllOverlays();" type=button value="All Overlays"/>
  </div>
--> 
<tr><td class="homemap"><div id="map-canvas" style="height: <?php echo $config->hgmap_height;?>px; border: 2px solid;"></div></td></tr>












<!--<script src='http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=<?php echo $api;?>' type="text/javascript"></script> 



<script type="text/javascript"> 
  <?php echo $stralarmarea;?>
    var map = null;
    var geocoder = null;
	var ffgmap = new GLatLng(<?php echo $gmapconfig->start_lat;?>, <?php echo $gmapconfig->start_lang;?>);
	


function mapload() {
	 if (GBrowserIsCompatible()) {
		var gmap = new GMap2(document.getElementById('map'), {mapTypes:[G_NORMAL_MAP, G_SATELLITE_MAP, G_HYBRID_MAP]});
		gmap.addControl(new GSmallMapControl());
		gmap.addControl(new GOverviewMapControl());
		gmap.setCenter(ffgmap, <?php echo $gmap_zoom_level_home;?>);
		//gmap.addOverlay(createMarker( new GLatLng(<?php echo $gmapconfig->start_lat;?>,<?php echo $gmapconfig->start_lang;?>), "<?php echo $config->feuerwehr;?>"));
		gmap.addOverlay(alarmarea);
	 }
}

function createMarker(point, mtext) {
	var marker = new GMarker(point);
	GEvent.addListener(marker, 'click', function() {
		marker.openInfoWindowHtml( mtext );
	});
	return marker;
}
window.setTimeout(mapload,1000);

    
</script>
-->
<?php }} ?>
</table>
<?php

//----Modulposition 'reportstats2' für Statistikanzeige----
if($selectedYear == '0')
{
}
else
{
$modulepos = 'reportstats2'; // Modulposition für Statistikanzeige 
module ($modulepos); // Funktion : Modul aufrufen mit Position $modulepos
}




//------------------ Link ---------------------------
$db = JFactory::getDbo(); #120314
$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "reports2"');
$params = json_decode( $db->loadResult(), true );

?>
<!-- Link protected. DO NOT REMOVE! --><p class="homefooter"><a target="_blank" href="http://www.einsatzkomponente.de/" style="color:#000000;font-size:10px;">Einsatzkomponente <?php echo $params[version];?> (www.einsatzkomponente.de)</a></p>

<?php

?>

