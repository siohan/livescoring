<?php
if (!isset($gCms)) exit;
//debug_display($params, 'Parameters');
//require_once(dirname(__FILE__).'/include/prefs.php');

$db =& $this->GetDb();
global $themeObject;

$i=0;
$query = "SELECT * FROM ".cms_db_prefix()."module_livescoring_lives WHERE actif =1 OR closed = 1";
$dbresult = $db->Execute($query);

$rowarray = array();
$rowclass = 'row1';
if($dbresult) 
{
		if($dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{	

				$renc_id = $row['id_live'];
				//on fait une requete pour savoir si une feuille de match est active ou pas
				$onerow = new StdClass();
				$onerow->rowclass = $rowclass;
				$onerow->id_live= $row['id_live'];
				$onerow->maj = $row['datemaj'];
				$onerow->date= $row['date_compet'];
				$onerow->niveau= $row['niveau'];
				$onerow->locaux= $row['locaux'];
				$onerow->adversaires= $row['adversaires'];
				$onerow->score_locaux = $row['score_locaux'];
				$onerow->score_adversaires= $row['score_adversaires'];
				$onerow->actif= $row['actif'];
				$onerow->closed= $row['closed'];


				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;

			}
			
			$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
			$smarty->assign('itemcount', count($rowarray));
			$smarty->assign('items', $rowarray);
		}
		else
		{
			echo "Pas encore de lives...";
		}
		
}

echo $this->ProcessTemplate('displaylives.tpl');

#
?>