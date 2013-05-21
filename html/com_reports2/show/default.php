<?php
defined('_JEXEC') or die('Illegal Access');




$option = JRequest::getCMD('option');        // ### 110420 +
$link= JRoute::_('index.php?option='.$option);
$path=JURI::base();
$task='task=home';
$gmaplink = JRequest::getVar('gmaplink', 0);
if ($gmaplink == '1')
{ $task = 'view=gmap';}
JHTML::_('stylesheet','report.css',$path.'templates/fwrethen_2.5/html/com_reports2/assets/css/');
JHTML::_('stylesheet', 'highslide.css',$path.'components/'.$option.'/assets/highslide/');
JHTML::_('script', 'highslide-with-gallery.js',$path.'components/'.$option.'/assets/highslide/');
JHTML::_('script', 'highslide.config.js',$path.'components/'.$option.'/assets/highslide/');
?>


<script type="text/javascript" src="<?php echo $path; ?>components/com_reports2/js/wz_tooltip.js"></script>



    <script type="text/javascript">
// override Highslide settings here
    // instead of editing the highslide.js file
    hs.graphicsDir = '<?php echo $path; ?>components/com_reports2/assets/highslide/graphics/';
    </script>
<?php

// Funktion : GMap-Konigurationsdaten abrufen
    function reports_gmapconfig() {
global $mainframe, $Itemid,$option;
$db		=& JFactory::getDBO();
$user = & JFactory::getUser();
$query = 'SELECT * FROM `#__reports_gmap` LIMIT 1';
$db->setQuery($query);
$gmapconfig = $db->loadObject();
        return $gmapconfig;
    }
// Funktion : Feuerwehrliste aus DB holen
    function feuerwehrliste() {
$db =& JFactory::getDBO();
$query = 'SELECT id, name as title,gmap_latitude as lat,gmap_longitude as lon,link as lin,detail1 FROM `#__reports_departments` WHERE published=1 ORDER BY `id`';
$db->setQuery($query);
$departmentDb = $db->loadObjectList();
        return $departmentDb;
    }
	
// Funktion : Standard-Feuerwehr ermitteln
    function standardfeuerwehr() {
$db =& JFactory::getDBO();
$query = 'SELECT id, name as title,link as lin,detail1,gmap_latitude,gmap_longitude FROM `#__reports_departments` WHERE ffw="1" AND published=1 LIMIT 1';
$db->setQuery($query);
$standardffw = $db->loadObjectList();
        return $standardffw;
    }


$users = JFactory::getUser();

$db =& JFactory::getDBO();
$query = 'SELECT id, link 
FROM `#__menu` 
WHERE `link` LIKE "%index.php?option=com_reports2&view=home&hauptlink=1%" AND `published` =1';
$db->setQuery($query);
$menuid = $db->loadObject();

//Daten für GMap vorbereiten
$db =& JFactory::getDBO();
$query = 'SELECT * FROM `#__reports_gmap` LIMIT 1';
$db->setQuery($query);
$gmapconfig = $db->loadObject();
$gmap_zoom_level=$gmapconfig->gmap_zoom_level;

$gmap_zoom_level=$gmap_zoom_level+1;
$alarmareas1  = $gmapconfig->gmap_alarmarea;
$alarmareas = explode("|", $alarmareas1);
$stralarmarea='var alarmarea = new GPolygon([ ';
	for($i = 0; $i < count($alarmareas); $i++)
	{
		if($i==$n-1)
		{
			$stralarmarea=$stralarmarea.'new GLatLng('.$alarmareas[$i].')';
		}
		else
		{
			$stralarmarea=$stralarmarea.'new GLatLng('.$alarmareas[$i].'),';
		}
	}
$stralarmarea=$stralarmarea.' ],"#f33f00", 3, 1, "#ff0000", 0.2);';





//----Monat-Parameter aus Übergabe auslesen----
$selectedDepartment = $_GET['department'];
$selectedData = $_GET['data'];
$Monat = $_GET['Monat'];
$db =& JFactory::getDBO();
$query = 'SELECT * FROM `#__reports_config` LIMIT 1';
$db->setQuery($query);
$config = $db->loadObject();
$reportData = $this->result;
$db =& JFactory::getDBO();
$query = 'SELECT feuerwehr FROM `#__reports_config` LIMIT 1';
$db->setQuery($query);
$feuerwehr = $db->loadObject();
if ($reportData)
{
  $display = $this->display;
  if (!$display) {
    $display->data1 = 1;
	$display->alerting = 1;
    $display->image = 1;
    $display->address = 1;
    $display->date1 = 1;
    $display->date2 = 1;
    $display->date3 = 1;
    $display->summary = 1;
    $display->boss = 1;
	$display->boss2 = 1;
    $display->people = 1;
    $display->department = 1;
    $display->desc = 1;
	$display->dauer = 1;
	$display->mapshow = 1;

  }
	$rData1 = $reportData->data1;
	$rImage = $reportData->image;
	$rAlerting = $reportData->alerting;
	$rAddress = stripslashes($reportData->address);
	$rDate1 = $reportData->date1;
	$rDate2 = $reportData->date2;
	$rDate3 = $reportData->date3;
	$rSummary = stripslashes($reportData->summary);
	$rBoss = stripslashes($reportData->boss);
	$rBoss2 = stripslashes($reportData->boss2);
	$rPeople = $reportData->people;
	$rDesc = stripslashes($reportData->desc);
	$rPublished = $reportData->published;
	$rVehicles = $this->rVehicles;
	$rDepartments = $this->rDepartments;
	$lat = $reportData->gmap_report_latitude;
	$long = $reportData->gmap_report_longitude;
	$presse = $reportData->presse;
	$presse2 = $reportData->presse2;
	$presse3 = $reportData->presse3;
	
    //----Jahr Parameter aus Alarmierungsdatum für Link-Variabeln übergabe vorbereiten
$all = JRequest::getVar('all');
    if ($all !='1')
    { list($jahr) = explode("-", $rDate1); }
    else 
    { $jahr = '9999'; }
	
	
	$query = 'SELECT id, image FROM `#__reports_images` WHERE report_id='.$reportData->id;
	$db->setQuery($query);
	$rImages = $db->loadResultArray();
	$rImagesName = $db->loadResultArray(1);
    $baseUploadDir = !empty($config->imagepath) ? $config->imagepath : 'images'.DS.$option.DS.'gallery';
    $baseUploadDir = '../' . $baseUploadDir;
}

echo '<table class="shownavi" width='.$config->showtab.' style="display:none" >';
?>
<tr>
<?php
$count=$reportData->counter;
?>
<?php

//----------------------------- URL-Daten für Twitterlink ---------------------------------------
        $url = JURI::base();
        $url = new JURI($url);
        $root= $url->getScheme() ."://" . $url->getHost();
        
        $url = JRoute::_('index.php?option='.$option.'&Itemid='.$menuid->id.'&view=show&id=').$reportData->id;
        $url = $root.$url;
//--------------- Seitentitel und Beschreibung im Metadaten ändern (für Facebook notwendig !!) --------------------
       $document = & JFactory::getDocument();
       $document->setTitle('Einsatzinfo: '.$rData1);
       //$document->setTitle('Einsatzinfo: '.$rData1.' ( '.$config->feuerwehr.' )');
       $document->setMetadata( 'description' , 'Am '.date('d.m.Y', strtotime($rDate1)).' '.$rSummary.' in '.$rAddress);
       $document->setGenerator('Einsatzkomponente f&uuml;r Joomla 2.5');
//----------------------------- Navigationsflächen ---------------------------------------
//$params2 = &JComponentHelper::getParams(com_reports2); ### 110521 -
$menus  = &JApplication::getMenu('site', array());
$menuparam = $menus->getActive();

$selectedDepartment = $_GET['department'];

if ($selectedDepartment != 0)
{
// Abfrage: Wechen Einsatz hatte eine Wehr X VOR dem momentan ausgwählten
$query = 'SELECT * FROM `#__reports` LEFT JOIN `#__reports_departments_link` ON #__reports_departments_link.report_id=#__reports.id   WHERE `date1` < "'.$rDate1.'"  AND `department_id` LIKE "'.$selectedDepartment.'" AND `#__reports`.`published`="1" ORDER BY `#__reports`.`date1` desc LIMIT 1' ;//## 110302 published hinzugefügt
$db->setQuery($query);
$prev = $db->loadobject();

}
else
{
// Abfrage: Falls keine Wehr ausgewählt ist, dann wird der Einsatz der genau vor diesem Einsatz stattgefunden hat, ohne Beachtung der Wehrbeteiligung
$db =& JFactory::getDBO();
$query = "SELECT * FROM `#__reports` WHERE `date1` < '".$rDate1."' AND `published`='1' ORDER BY `#__reports`.`date1` DESC LIMIT 1";//## 110302 published hinzugefügt
$db->setQuery($query);
$prev = $db->loadobject();
}
?>
<tr><td class="shownavi1">
<?php
if ($prev->id != 0) {
echo '<a title="zur&uuml;ck" class="readon" href='.JRoute::_('index.php?option='.$option.'&view=show&Itemid='.$menuid->id.'&id='.$prev->id.'&selectedYear=').$jahr.'&year='.$jahr.'&Monat='.$Monat.'&data='.$selectedData.'&department='.$selectedDepartment.'&gmaplink='.$gmaplink.'>zur&uuml;ck</a> ';}

echo '<a title="&Uuml;bersicht" class="readon" href='.JRoute::_('index.php?option='.$option.'&'.$task.'&Itemid='.$menuid->id.'&selectedYear=').$jahr.'&year='.$jahr.'&Monat='.$Monat.'&data='.$selectedData.'&department='.$selectedDepartment.'&gmaplink='.$gmaplink.'>&Uuml;bersicht</a> ';
if ($selectedDepartment != 0)
{
// Abfrage: Welchen Einsatz hatte eine Wehr X NACH dem momentan ausgwählten
$query = 'SELECT * FROM `#__reports` LEFT JOIN `#__reports_departments_link` ON #__reports_departments_link.report_id=#__reports.id   WHERE `date1` > "'.$rDate1.'"  AND `department_id` LIKE "'.$selectedDepartment.'" AND `published`="1" ORDER BY `#__reports`.`date1` asc LIMIT 1' ;//## 110302 published hinzugefügt
$db->setQuery($query);
$next = $db->loadobject();
}
else
{
// Abfrage: Falls keine Wehr ausgewählt ist, dann wird der Einsatz der genau nach diesem Einsatz stattgefunden hat, ohne Beachtung der Wehrbeteiligung
$db =& JFactory::getDBO();
			$query = "SELECT * FROM `#__reports` WHERE `date1` > '".$rDate1."' AND `published`='1' ORDER BY `#__reports`.`date1` asc LIMIT 1"; //## 110302 published hinzugefügt
			$db->setQuery($query);
			$next = $db->loadobject();
}			
if ($next->id != 0) {
echo '<a class="readon" title="vor" href='.JRoute::_('index.php?option='.$option.'&view=show&Itemid='.$menuid->id.'&id='.$next->id.'&selectedYear=').$jahr.'&year='.$jahr.'&Monat='.$Monat.'&department='.$selectedDepartment.'&gmaplink='.$gmaplink.'>vor</a> ';}

//--------Counter----------------------------------------------------

if ($config->countershow == 1)
{
?>
</td>
<td class="shownavi2"><div><?php echo '<b>'; echo JText::_( 'COM_REPORTS2_S_REPORTVIEW1' ); echo ' ' .$count. ' '; echo JText::_( 'COM_REPORTS2_S_REPORTVIEW2' ); echo '</b>'; ?>
<?php } 
else
{
}

?>
<?php // -------------Twitter-Button-------------             // ###110417
$tweet = 'Einsatzinfo: '.$rData1.' - '.$rSummary.'';
$tweet  = htmlentities($tweet, ENT_QUOTES, "UTF-8");
$tweet = strlen($tweet) > 100 ? substr($tweet, 0, 100).'...' : $tweet;
echo '<br/>';
if ($config->FacebookShow == 1)
{
echo '<span style="float:right;" class="itp-share-fbsh">
                <a name="fb_share" 
   share_url="'.$url.'" title="Diesen Einsatzbericht bei Facebook posten">
   Posten</a>

   <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
        type="text/javascript">
</script>
	
                </span>';
}
if ($config->TwitterShow == 1)
{
echo '
<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>


  <span style=" padding-left:2px;margin-left:2px;float:right"><a href="http://twitter.com/share" class="twitter-share-button"
     data-url="'.$url.'"
     data-text="'.$tweet.'"
	 data-via="'.$config->TwitterID.'"
     data-counturl="'.$url.'"
     data-count="horizontal">Tweet</a></span>';
}
?>
<?php // --------------Counter und Twitter Ende --------------------


//-------------Einsatznummer darstellen------------------------------------

if ($menuparam->query[anzeigejahr] == '')
{
$selectedYear = ''.date('Y', strtotime($rDate1)).'';
}
else
{
if ($menuparam->query[anzeigejahr] == ''.date('Y', strtotime($rDate1)).'')
{
$selectedYear = $menuparam->query[anzeigejahr];
}
// Notwendig falls man mit den Navigationsbutton in ein anderes Jahr wechselt
else
{
$selectedYear = ''.date('Y', strtotime($rDate1)).'';
}
}

$database			=& JFactory::getDBO();
// Anzahl der bisherigen Einsätze im Jahr x
$query = 'SELECT COUNT(id) as total FROM #__reports WHERE date1 LIKE "'.$selectedYear.'%" AND published = "1" ' ;
$database->setQuery( $query );
$total = $database->loadObjectList();	
$totalRecords = $total[0]->total;
// Einsatznummer des betrachteten Einsatzes im Jahr x
$query = 'SELECT COUNT(id) as total FROM #__reports WHERE date1  <= "'.$rDate1.'" AND date1 LIKE "'.$selectedYear.'%"  AND published = "1" ' ;
$database->setQuery( $query );
$total = $database->loadObjectList();	
$totalRecords2 = $total[0]->total;
// Anzahl aller Einsätze einer bestimmten Wehr in diesem Jahr
$query = 'SELECT COUNT(id) as total FROM `#__reports` LEFT JOIN `#__reports_departments_link` ON #__reports_departments_link.report_id=#__reports.id WHERE date1 LIKE "'.$selectedYear.'%" AND published = "1" AND `department_id` LIKE "'.$selectedDepartment.'"' ;
$database->setQuery( $query );
$total = $database->loadObjectList();	
$totalRecords3 = $total[0]->total;
// Nummer dieses Einsatzes einer bestimmten Wehr in diesem Jahr
$query = 'SELECT COUNT(id) as total FROM `#__reports` LEFT JOIN `#__reports_departments_link` ON #__reports_departments_link.report_id=#__reports.id WHERE date1  <= "'.$rDate1.'" AND date1 LIKE "'.$selectedYear.'%"  AND published = "1" AND `department_id` LIKE "'.$selectedDepartment.'"' ;
$database->setQuery( $query );
$total = $database->loadObjectList();	
$totalRecords4 = $total[0]->total;
// Ausgabe welche Wehr im Menü verlinkt ist (um von der ID auf den Namen zu kommen)
$query = "SELECT * FROM `#__reports_departments` WHERE `id` LIKE '".$selectedDepartment."'";
$db->setQuery($query);
$selectedWehr = $db->loadobject();

if ($config->show20 == 1)
{
if ($menuparam->query[abfragewehr] == '0' or $gmaplink == '1')
{
?>
<tr><td class="shownavi3" colspan=1><?php echo JText::_('COM_REPORTS2_S_ID1').' '.$totalRecords2.' '.JText::_('COM_REPORTS2_S_ID2').' '.$totalRecords.' '.JText::_('COM_REPORTS2_S_ID3').' '.JText::_('COM_REPORTS2_S_ID4').' '.$selectedYear ?> </td>
<?php
}
else
{
if ($config->show21 == 0 or $config->show21 == 2)
{
?>
<tr><td class="shownavi3" colspan=1><?php echo JText::_('COM_REPORTS2_S_ID1').' '.$totalRecords2.' '.JText::_('COM_REPORTS2_S_ID2').' '.$totalRecords.' '.JText::_('COM_REPORTS2_S_ID3').' '.JText::_('COM_REPORTS2_S_ID5').' '.JText::_('COM_REPORTS2_S_ID4').' '.$selectedYear ?> </td>
<?php
}
if ($config->show21 == 1 or $config->show21 == 2)
{
?>
<td class="shownavi3" colspan=1><?php echo JText::_('COM_REPORTS2_S_ID1').' '.$totalRecords4.' '.JText::_('COM_REPORTS2_S_ID2').' '.$totalRecords3.' '.JText::_('COM_REPORTS2_S_ID3').' '.JText::_('COM_REPORTS2_S_ID6').' '.$selectedWehr->name.' '.JText::_('COM_REPORTS2_S_ID4').' '.$selectedYear ?> </td>
<?php
}
}
}

echo '<td class="shownavi4">';

//---------------------------- USER - RECHTE -----------------------------------------------------


$us = $users->username;
$db =& JFactory::getDBO();
if ($us !='admin')
{
$query = 'SELECT count(title) as total,r.title,r.departments,rd.department_id,rd.report_id FROM #__reports_usergroup r JOIN #__reports_departments_link rd ON r.departments = rd.department_id WHERE rd.report_id='.$reportData->id.' AND r.title="'.$us.'" AND r.published = "1" GROUP BY r.title' ;
$db->setQuery($query);
$aa = $db->loadobject();
}

if($aa->title != '' or $us=='admin')
{
echo '<a title="Einsatz bearbeiten" href='.JRoute::_('index.php?option='.$option.'&Itemid='.$menuid->id.'&view=submit&id=').$reportData->id.'><img src="'.$path.'components/'.$option.'/images/buttons/edit.png" width="40" height="20" /></a> ';
  }
//------------------------- USER - RECHTE - ENDE -------------------------------------------------
	
?>
</td></tr>
</table>

<?php
echo '<table class="showtable" width='.$config->showtab.' cellspacing=0 cellpadding=0 bgcolor='.$config->farbe7.'>';
?>

<?php  // -------------------------- Einsatzlogo -----------------------------------------------------------
if ($config->show6 == 1)
{
if ($display->logo != 0) { ?>
  <tr style="background-color:#<?php echo $config->farbe7 ?>;">
    <td class="showlogo" colspan="2">
<?php  
$db =& JFactory::getDBO();
$query = 'SELECT title,beschr,marker FROM `#__reports_data` WHERE title="'.$rData1.'"';
$db->setQuery($query);
$reportsdata = $db->loadObject();
	 
echo '<img class="show" src="'.$path.'/'.$config->missionupload.'/'.$reportsdata->beschr.'" width=100% alt="'.$rData1.'"/>';
	 
}
else
{
}
 ?>
</td>
  </tr>
<?php     } ?> 
  


<?php  //----------------------- Einsatzart -------------------------------------------------------------
if ($config->show11 == 1)
{
if ($display->summary != 0) { ?>
       <tr style="background-color:#<?php echo $config->farbe7 ?>;">
       <td class="showtab" style="padding: 10px;"><?php echo JText::_('COM_REPORTS2_S_ABRIDGEREPORT') ?></td>
       <td class="showtab" style="padding: 10px;" colspan="2"><?php echo $rSummary; ?></td>
       </tr>
       <tr>
       <td style="border-style:solid; border-width:0px 0px 0px 1px; border-color:#999;" rowspan="6" height="112"></td>
       </tr>
<?php     }} ?>
  
<?php
if ($config->show1 == 1)
{
if ($display->data1 != 0) { ?>
       <tr style="background-color:#<?php echo $config->farbe7 ?>;">
       <td class="showtab" ><?php echo JText::_('COM_REPORTS2_S_MISSIONDESCRIPTION') ?></td>
<?php
if ($config->showeinsatzartfarbe == 0)
{
?>
       <td class="showart" ><?php echo $rData1; ?></td>
<?php
}
else
{

?>
	<td class="showart"><font color="#<?php echo $reportsdata->marker; ?>"><?php echo $rData1; ?></font></td>

       </tr>
<?php   }  }} ?>

<?php  
if ($config->show2 == 1)
{
if ($display->address != 0) { ?>
       <tr style="background-color:#<?php echo $config->farbe7 ?>;">
       <td class="showtab"><?php echo JText::_('COM_REPORTS2_S_MISSIONLOCATION') ?></td>
       <td class="showtab"><?php echo $rAddress; ?></td>
       </tr>
<?php     }} ?>
  
<?php  
if ($config->show7 == 1)
{
if ($display->date1 != 0) { ?>
       <tr style="background-color:#<?php echo $config->farbe7 ?>;">
       <td class="showtab"><?php echo JText::_('COM_REPORTS2_S_ALERTING1'); ?>:</td>
       <td class="showtab"><?php
             if(strtotime($rDate1) == "-62169987600")
             {
               echo JText::_('COM_REPORTS2_S_EMTY');
             }
            else
			
             {
            		 
    	$al=$reportData->alerting;
				$db =& JFactory::getDBO();
	            $query = 'SELECT id, title, image FROM `#__reports_alerting` WHERE id='.$al;
				$db->setQuery($query);
				$alertimage = $db->loadObject();
				$baseUploadDir = !empty($config->imagepath) ? $config->imagepath : 'images'.DS.$option.DS.'gallery';
		        if($alertimage->image !="")
		        {
				echo '<span title="'.$alertimage->title.'" >
Alarmierung per&nbsp;<img src="'.$path.$config->alertingupload.'/'.$alertimage->image.'" width="'.$config->alarm.'" alt="'.$alertimage->title.'"/></span>';

				}
 		        
			   echo 'am '.date('d.m.Y', strtotime($rDate1)).' um';	 
               echo date(' H:i', strtotime($rDate1)).' Uhr';
		 
}			 
			 
			 
			 
         ?>
         </td>
  </tr>
  <?php     }} ?>

<?php  
if ($config->show8 == 1) {
if ($display->date2 != 0) { ?>
       <tr style="background-color:#<?php echo $config->farbe7 ?>;">
       <td class="showtab"><?php echo JText::_('COM_REPORTS2_S_ALERTING6'); ?></td>
       <td class="showtab"><?php
             if(strtotime($rDate2) == "-62169987600")
             {
             echo JText::_('COM_REPORTS2_S_EMTY');
             }
             else
             {
             echo date('H:i', strtotime($rDate2)).' '.JText::_('COM_REPORTS2_S_ALERTING5');
             }
         ?></td>
       </tr>
<?php     }} ?>

<?php  
if ($config->show9 == 1) {
if ($display->date3 != 0) { ?>
       <tr style="background-color:#<?php echo $config->farbe7 ?>;">
       <td class="showtab"><?php echo JText::_('COM_REPORTS2_S_ALERTING7'); ?></td>
       <td class="showtab"> <?php
            if(strtotime($rDate3) == "-62169987600")
            {
            echo JText::_('COM_REPORTS2_S_EMTY');
            }
            else
            {
             echo date('H:i', strtotime($rDate3)).' '.JText::_('COM_REPORTS2_S_ALERTING5');
			}
          ?></td>
      </tr>
<?php     }} ?>

  
<?php  
if ($config->show10 == 1) {
if ($display->dauer != 0) {
	   if(strtotime($rDate3) == "-62169987600" or strtotime($rDate1) == "-62169987600" )
            {
          
            }
            else
            {?>
       <tr style="background-color:#<?php echo $config->farbe7 ?>;">
       <td class="showtab"><?php echo JText::_('COM_REPORTS2_S_ALERTING8'); ?></td>
       <td class="showtab"> <?php
            
			$diff =  strtotime($rDate3)- strtotime($rDate1);
            $diff = $diff/60;
			if ($diff<60) {
	
            echo $diff.' Min.';
			}
			else {
            $diffDate = strtotime($rDate3)- strtotime($rDate1);

            $days = floor($diffDate / 24 / 60 / 60 ); // Anzahl Tage = Sekunden /24/60/60
            $diffDate = $diffDate - ($days*24*60*60); // den verbleibenden Rest berechnen = Stunden
            $hours = floor($diffDate / 60 / 60); // den Stundenanteil herausrechnen
            $diffDate = ($diffDate - ($hours*60*60));
            $minutes = floor($diffDate/60); // den Minutenanteil
            $diffDate = $diffDate - ($minutes*60);
            $seconds = floor($diffDate); // die verbleibenden Sekunden
            if ($days>0) {
            echo $days.' Tag(e), ';
}
            echo $hours.' Std. und '.$minutes.' Min.';
			
			}
            
          ?></td>
        </tr>
<?php    } } } ?>
        
<?php  
if ($config->show12 == 1) {
if ($display->boss2 != 0) { ?>
       <tr style="background-color:#<?php echo $config->farbe7 ?>;">
       <td class="showtab"><?php echo JText::_('COM_REPORTS2_S_LEADER'); ?></td>
       <td class="showtab"> <?php
            if($rBoss2 == "")
            {
            echo JText::_('COM_REPORTS2_S_EMTY');
            }
            else
            {
            echo $rBoss2;
            }
          ?></td>
       </tr>
<?php    } } ?>

<?php  
if ($config->show13 == 1) {
if ($display->boss != 0) { ?>
       <tr style="background-color:#<?php echo $config->farbe7 ?>;">
       <td class="showtab"><?php echo JText::_('COM_REPORTS2_S_MISSIONSQUADLEADER'); ?></td>
       <td class="showtab"> <?php
            if($rBoss == "")
            {
            echo JText::_('COM_REPORTS2_S_EMTY');
            }
            else
            {
            echo $rBoss;
            }
          ?></td>
       </tr>
<?php    } } ?>
 
 <?php  
 if ($config->show14 == 1) {
 if ($display->people != 0) { ?>
        <tr style="background-color:#<?php echo $config->farbe7 ?>;">
        <td class="showtab"><?php echo JText::_('COM_REPORTS2_S_CREW'); ?></td>
        <td class="showtab"> <?php
            if($rPeople == "0")
            {
            echo JText::_('COM_REPORTS2_S_EMTY');
            }
            else
            {
            echo $rPeople;
            }
          ?></td>
       </tr>
<?php    } } ?>


 <?php  
 if ($config->show15 == 1) {
 if ($display->vehicle != 0) { ?>
        <tr style="background-color:#<?php echo $config->farbe7 ?>;">
        <td class="showtab" style="border-width:1px 0px 1px 1px;"><?php echo JText::_('COM_REPORTS2_S_VEHICLE'); ?></td>
        <td class="showtab" style="border-width:1px 0px 1px 1px;" colspan="2"><?php
		
        if (count($rVehicles))
        {
	    foreach ($rVehicles as $rVehicle)
	    {
        //----Bilder eingesetzte Fahrzeuge----
		{
        if($rVehicle->image !="")
	   

        {
if ($config->vview != 0) 
{
 if ($rVehicle->link != '') {
 echo '<a href="'.JRoute::_($rVehicle->link).'">';}
?>

<a href="<?php echo $rVehicle->link?>" onmouseover="Tip('<strong>Fahrzeuginfo:</strong><br><?php echo $rVehicle->detail2;?><br><img src=\'<?php echo $path;?><?php echo $config->vehicleupload;?>/<?php echo $rVehicle->image;?>\'  width=\'100\'><br><?php echo $rVehicle->name;?><br><?php echo $rVehicle->detail1;?><br>Baujahr : <?php echo $rVehicle->detail3;?>',OPACITY, 100)">
<img  src="<?php echo $path;?><?php echo $config->vehicleupload;?>/<?php echo $rVehicle->image;?>" width="<?php echo $config->picow;?>"  alt="<?php echo $rVehicle->name;?>"/>
</a>
<?php
 if ($rVehicle->link != '') {
 echo '</a>';}
        }
		else
		{
		 echo '<div style="border-bottom:0" >'; 
 if ($rVehicle->link != '') {
		 echo '<a class="link" href="'.JRoute::_($rVehicle->link).'">';}
		 ?>
<a href="<?php echo $rVehicle->link?>" onmouseover="Tip('<strong>Fahrzeuginfo:</strong><br><?php echo $rVehicle->detail2;?><br><img src=\'<?php echo $path;?><?php echo $config->vehicleupload;?>/<?php echo $rVehicle->image;?>\'  width=\'100\'><br><?php echo $rVehicle->name;?><br><?php echo $rVehicle->detail1;?><br>Baujahr : <?php echo $rVehicle->detail3;?>',OPACITY, 100)">
<img  src="<?php echo $path;?><?php echo $config->vehicleupload;?>/<?php echo $rVehicle->image;?>" width="<?php echo $config->picow;?>"  alt="<?php echo $rVehicle->name;?>"/>
</a><?php echo $rVehicle->name;?>
         
	<?php	 
 if ($rVehicle->link != '') {
         echo'</a>';}
		 echo '</div>';}
        }
        else
        {echo '<div style="border-bottom:0" >';
		 if ($rVehicle->link != '') {
		echo '<a class="link" href="'.JRoute::_($rVehicle->link).'">'; }
		echo ''.$rVehicle->name.''; 
		 if ($rVehicle->link != '') {
		echo '</a>'; }
		echo '</div>';}
			
		}
	    }
        }
        else
        {
	    echo JText::_('COM_REPORTS2_S_VEHICLENO');
        }
        ?></td>
  </tr>
<?php    } } ?>

<?php  
if ($config->show16 == 1) {
if ($display->department != 0) { ?>
         <tr style="background-color:#<?php echo $config->farbe7 ?>;">
         <td class="showtab"><?php echo JText::_('COM_REPORTS2_S_UNITS'); ?></td>
         <td class="showtab" colspan="2"><?php
         if (count($rDepartments) && $display->department)
         {
       	 foreach ($rDepartments as $rDepartment)
	     {
	     if($rDepartment->link =="")
	  	 {echo '<div style="padding-bottom:2px;">'.$rDepartment->name.'</div>';}
			else
		   {echo '<div style="padding-bottom:2px;">'.'<a class="link" target="_blank" href="'.JRoute::_($rDepartment->link).'">'.$rDepartment->name.'</a>'.'</div>';}
		   //echo '<div class="showVehicleName">'.'<a href="'.JRoute::_('index.php?option='.$option.'&task=vehicle&id='.$rVehicle->id).'">'.$rVehicle->name.'</a>'.' </div>';
	    }
        }
        else
        {
	    echo JTEXT::_('COM_REPORTS2_S_UNITSNO');
        }
        ?></td>
        </tr>
<?php    } } ?>
<?php 
if ($config->show17 == 1) {
if ($presse != '' or $presse2 != '' or $presse3 != '')
{
	echo '<tr style="background-color:#'.$config->farbe7.';">';
	echo '<td class="showpresse" colspan = 2>';
	//echo ''.JText::_('COM_REPORTS2_S_PRESS');

if ($presse != '')
{
	echo '<a href="'.$presse.'" target="_blank"><img border=0 src="'.$path.'components/'.$option.'/images/presse1.png" title="'.$presse.'" width="80" height="40" style=" padding:1px;margin:2px;" /></a>';
}
if ($presse2 != '')
{
	echo '<a href="'.$presse2.'" target="_blank"><img border=0 src="'.$path.'components/'.$option.'/images/presse2.png" title="'.$presse2.'" width="80" height="40" style=" padding:1px;margin:2px;" /></a>';
}
if ($presse3 != '')
{
	echo '<a href="'.$presse3.'" target="_blank"><img border=0 src="'.$path.'components/'.$option.'/images/presse3.png" title="'.$presse3.'" width="80" height="40" style=" padding:1px;margin:2px;" /></a>';
}

echo '</td></tr>';
}
}
?>
<?php  
if ($config->show3 == 1) {
if ($display->desc != 0) { if ($rDesc != '') {?>
           <?php jimport('joomla.html.content');   // Plugin-support
                 $rDesc = JHTML::_('content.prepare', $rDesc); // Plugin-support
		   ?>
       <tr>
       <td class="showtab" colspan="3"  bgcolor="#<?php echo $config->farbe6 ?>"><?php echo '<p></p><b>'.JText::_('COM_REPORTS2_S_REPORT').'</b><p></p><p></p>'.$rDesc; ?></td>
       </tr>
<?php     }}}
if ($config->show18 == 1) { ?>

  <tr style="background-color:#<?php echo $config->farbe8; ?>">
    <td class="showmaptd" colspan="3"><?php
$baseUploadDir = !empty($config->imagepath) ? $config->imagepath : 'images/'.$option.'/gallery';
echo '<table class="showmap" cellspacing=0 cellpading=0>';
echo '<tr style="background-color:#'.$config->farbe8.' ">';
for ($i = 0; $i < count($rImages); ++$i)
{
	//list($width, $height) = getimagesize($baseUploadDir.'/'.$rImagesName[$i]);   
	//$correctHeight = ceil($config->imggw * $height / $width);
	
	echo '<td class="showimage" class="highslide-gallery" >';
    echo '<a href="'.$baseUploadDir.'/'.$rImagesName[$i].'"class="highslide" onclick="return hs.expand(this, config1 )">';
	echo  '<img src="'.$path.$baseUploadDir.'/'.$rImagesName[$i].'" width='.$config->imggtw.'px alt="'.$rData1.' vom '.date('d.m.Y', strtotime($rDate1)).'  |  '.$config->copyright.' ('.date('Y', strtotime($rDate1)).')" title=""/>';
	echo '</a>';
	echo '</td>';
	if ((($i+1) % $config->imgr == 0))
	{
		echo '</tr><tr>';
	}
}
echo '</tr>';
echo '';


//---------------- Google Map -------------------------

} ?>
<?php 
$user =& JFactory::getUser();

if (!$user->guest or $config->allowmap < 1) {


if ($config->show4 == 1) {
if ($display->mapshow != 0) { 
if ($reportData->gmap_report_latitude != 0) {
echo '<tr height="10px"><td></td></tr>';
echo '<tr style="background-color:#'.$config->farbe9.';">';
echo '<td class="showmap1" colspan="'.$config->imgr.'">'.JText::_('COM_REPORTS2_S_LOCATION').'</td><tr></tr>';

//----------------------------------------------------------------
$gmapconfig = reports_gmapconfig();  // Funktion : GMAP - Konfigurationsdaten abrufen 
$gmap_zoom_level_home=$gmapconfig->gmap_zoom_level_home;
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

$departmentDb = feuerwehrliste(); 
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

$standardffw = standardfeuerwehr(); 
//$standardffw = $this->rDepartments;
 //----------------------------------------------------------------------
?>





<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3&sensor=false"></script>
<script type="text/javascript"> 
var FFVMOD = {
  map: null,
  markers: [],
  standorte: [],
  polyline: null,
  polygon: null
};
  
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
	  maxZoom: <?php echo $config->gmap_max_level_show;?>,
      zoom: <?php echo $config->gmap_zoom_level_show;?>,
      center: new google.maps.LatLng(<?php echo $lat;?>, <?php echo $long;?>),
      mapTypeControl: true,
      scrollwheel: false,
      disableDoubleClickZoom: true,
	  streetViewControl: false,
      keyboardShortcuts: false,
      mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
		  <?php
		  if ($config->gmap_zoom_show == '1')
		  { ?>
		  navigationControl: true, 
		  <?php }
		  else
		  { ?>
          navigationControl: false, 
		  <?php }
		  ?>
      navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
      
		<?php if ($config->shybrid == 0) { ?>
        mapTypeId: google.maps.MapTypeId.ROADMAP <?php } else {?>
        mapTypeId: google.maps.MapTypeId.HYBRID <?php }?>
  });
  
  
  
// Route anzeigen ANFANG ------------------------------------------------------------------
		directionsService = new google.maps.DirectionsService();
		directionsDisplay = new google.maps.DirectionsRenderer(
		{
			suppressMarkers: true,      // Anfang und Endmarker ausblenden
			suppressInfoWindows: true,
			<?php if ($config->sdistanceZ == 1) { ?>
            preserveViewport:false,     // zoom-faktor auf auto stellen
		    <?php } else {?>
            preserveViewport:true     // zoom-faktor auf auto stellen
		    <?php } ?>
			<?php if ($config->sdistance1 == 0) { ?>
            ,suppressPolylines:true
		    <?php } ?>
        });
		directionsDisplay.setMap(FFVMOD.map);
  
var request = {
			destination:new google.maps.LatLng(<?php echo $lat;?>, <?php echo $long;?>),
			origin: new google.maps.LatLng(<?php echo $standardffw[0]->gmap_latitude;?>, <?php echo $standardffw[0]->gmap_longitude;?>),
			travelMode: google.maps.DirectionsTravelMode.DRIVING
		};
		directionsService.route(request, function(response, status) 
		{
		if (status == google.maps.DirectionsStatus.OK) 
			{
				directionsDisplay.setDirections(response);
<?php if ($config->sdistance2 == 1) { ?>
	distance = "Der Anfahrtsweg betrug ca. "+response.routes[0].legs[0].distance.text;
				/*distance += "<br/>Fahrtzeit ca. "+response.routes[0].legs[0].duration.text;*/
				document.getElementById("distance_road").innerHTML = distance;
				<?php } ?>
			}
		});
// Route anzeigen ENDE ----------------------------------------------------------------
  
  
  
  
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
  
<?php if ($config->sarea == 1) { ?>
  // Create a polyline connected alarmarea.
  FFVMOD.polygon = new google.maps.Polygon({
    path: alarmarea,
fillOpacity: 0.2,
strokeColor: '#ff0000',
    strokeWeight: 3,
    fillColor: '#f00000'
  });
<?php } ?>
  
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

//var image = '<?php echo JURI::root()."components/com_reports2/images/map/icons/circle.png";?>';
var image = new google.maps.MarkerImage('<?php echo JURI::root()."components/com_reports2/images/map/icons/circle.png";?>',       
// This marker is 20 pixels wide by 32 pixels tall.       
new google.maps.Size(60, 60),       
// The origin for this image is 0,0.       
new google.maps.Point(0,0),       
// The anchor for this image is the base of the flagpole at 0,32.       
new google.maps.Point(20, 25)); 

var einsatz = new google.maps.Marker({       
									position: new google.maps.LatLng(<?php echo $lat;?>, <?php echo $long;?>),     
									title:"Einsatz: <?php echo $rSummary;?>",
									map: FFVMOD.map,
									icon: image
									});      
//// To add the marker to the map, call setMap();   
einsatz.setMap(FFVMOD.map);   
  
  
  
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
<tr><td class="showmap2" colspan="<?php echo $config->imgr;?>"><div id="map-canvas" style="height:<?php echo $config->sgmap_height;?>px;border:2;"></div><div style="width:100%; height:10%" id="distance_direct"></div><div style="width:100%; height:10%" title ="Die Angabe kann vom tats&auml;chlichen Streckenverlauf abweichen, da diese Angabe automatisch von Google Maps errechnet wurde !" id="distance_road"></div></td></tr>


<?php        }  } } 
} // end of map, if nor logged in
else
{
echo JTEXT::_('COM_REPORTS2_S_ALLOWMAP');

	}
?>
</table></td></tr></table>

<?php
// -------------------- COUNTER -------------------------------- ##110402 Counter->IP->SQL hinzugefügt
$db =& JFactory::getDBO();
$query = 'SELECT COUNT(counter_id) as total FROM #__reports_counter WHERE counter_ip = "' . $_SERVER['REMOTE_ADDR'] . '" AND counter_rp_id = "'.$reportData->id.'" AND counter_time > "'.(time() - $config->iptime).'" ' ;
$db->setQuery($query);
$counter_check = $db->loadObjectList();
//print_r ($counter_check);
//echo $query;
 
 if ($counter_check[0]->total > '0')
 {
 // Counter wird für Ip-Adresse auf bestimme Zeit gesperrt
 }
 else
 {
// Ip-Adresse hat diesen Bericht noch nicht gelesen. Es folgt Counter-Zählung und Speicherung der IP für eine bestimmte Zeit
// außerdem werden Zeit abgelaufende gesperrte Ip-Adressen wieder freigegeben.	 
$i=$count;
$i=$i+1;
global $option;
$db =& JFactory::getDBO();
$query = 'UPDATE `#__reports` SET `counter` = "'.$i.'" WHERE `#__reports`.`id` ='.$reportData->id.' LIMIT 1' ;
$db->setQuery($query);
if ($db->query()) {
} else {
echo 'Fehler !!' . $db->getErrorMsg();
}
global $option;
$db =& JFactory::getDBO();
$query = 'DELETE FROM `#__reports_counter` WHERE counter_time < "'.(time() - $config->iptime).'"' ;
$db->setQuery($query);
if ($db->query()) {
} else {
echo 'Fehler !!' . $db->getErrorMsg();
}
global $option;
$db =& JFactory::getDBO();
$query = 'INSERT INTO `#__reports_counter` (`counter_ip`,`counter_rp_id`,`counter_time`) VALUES ("' . $_SERVER['REMOTE_ADDR'] . '","'.$reportData->id.'","'.time().'")' ;
$db->setQuery($query);
if ($db->query()) {
} else {
echo 'Fehler !!' . $db->getErrorMsg();
}

 }
 

?>
