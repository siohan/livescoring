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
				
				$query2 = "SELECT renc_id FROM ".cms_db_prefix()."module_livescoring_compositions WHERE renc_id = ?";
				$dbresult2 = $db->Execute($query2, array($renc_id));
				if($dbresult2->RecordCount() >0)
				{
					$compo = 1;
				}
				
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
				
				if($compo == 1)
				{
					$onerow->feuille = $this->CreateLink($id, 'fe_ordre_parties', $returnid, 'Feuille de match', array("renc_id"=>$renc_id));
				}
				
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

//on met aussi les parties en cours
$rowarray3 = array();
$rowclass3 = 'row1';
$query3 = "SELECT renc_id,joueur1, joueur2, scoreA, scoreW, numero_set,set_end, statut,partie, score FROM ".cms_db_prefix()."module_livescoring_live_parties WHERE statut = 1";
$dbresult3 = $db->Execute($query3);
if($dbresult3 && $dbresult3->RecordCount()>0)
{
	while($row3 = $dbresult3->FetchRow())
	{
		$onerow3 = new StdClass();
		$onerow3->rowclass = $rowclass3;
		$onerow3->renc_id = $row3['renc_id'];
		$onerow3->partie = $row3['partie'];
		$onerow3->joueur1 = $row3['joueur1'];
		$onerow3->joueur2 = $row3['joueur2'];
		$onerow3->numero_set = $row3['numero_set'];
		$onerow3->scoreA = $row3['scoreA'];
		$onerow3->scoreW = $row3['scoreW'];
		$onerow3->statut = $row3['statut'];
		$onerow3->set_end = $row3['set_end'];
		($rowclass3 == "row1" ? $rowclass3= "row2" : $rowclass3= "row1");
		$rowarray3[]= $onerow3;
	}
	$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
	$smarty->assign('itemcount', count($rowarray3));
	$smarty->assign('items', $rowarray3);
}
echo $this->ProcessTemplate('fe_live_set.tpl');

#
?>