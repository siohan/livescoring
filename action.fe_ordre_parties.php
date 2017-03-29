<?php

if(!isset($gCms)) exit;
//on vérifie les permissions

$db =& $this->GetDb();
global $themeObject;
//debug_display($params, 'Parameters');

$renc_id = '';
if(isset($params['renc_id']) && $params['renc_id'] != '')
{
	$renc_id = $params['renc_id'];
}
$smarty->assign('retour',
		$this->CreateLink($id, 'default',$returnid, '<< Retour'));
$query = "SELECT renc_id, partie, joueur1, joueur2, vicA, vicW, statut,score FROM ".cms_db_prefix()."module_livescoring_parties WHERE renc_id = ? ORDER BY id ASC";
$dbresult = $db->Execute($query, array($renc_id));
$rowarray = array();
$rowclass = 'row1';
$nomorelive = '<img src="modules/livescoring/images/lock.png" class="systemicon" alt="Live terminé" title="Live terminé">';
$comingsoon = '<img src="modules/livescoring/images/stop.png" class="systemicon" alt="Live non commencé" title="live non commencé">';
if($dbresult && $dbresult->RecordCount() >0)
{
	while($row = $dbresult->FetchRow())
	{
		$statut = $row['statut'];
		$onerow = new StdClass();
		$onerow->rowclass = $rowclass;
		$onerow->renc_id = $row['renc_id'];
		$onerow->partie = $row['partie'];
		$onerow->joueur1 = $row['joueur1'];
		$onerow->joueur2 = $row['joueur2'];
		$onerow->statut = $row['statut'];
		$onerow->vicA = $row['vicA'];
		$onerow->vicW = $row['vicW'];
		$onerow->score = $row['score'];
		//$onerow->display= $this->Createlink($id, 'live_parties', $returnid,$themeObject->DisplayImage('icons/system/true.gif', 'En live', '', '', 'systemicon');
		
		if($statut == '0')
		{
			$onerow->live= $comingsoon;
		}
		
		elseif($statut == '1')
		{
			$onerow->live= $this->CreateLink($id, 'fe_live_parties',$returnid,'<img src="modules/livescoring/images/play.png" class="systemicon" alt="Regarder le live" title="Regarder le live">',array("renc_id"=>$renc_id));
		}
		else
		{
			$onerow->live= $nomorelive;
		}
		
		
		($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
		$rowarray[]= $onerow;
		
	}
	$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
	$smarty->assign('itemcount', count($rowarray));
	$smarty->assign('items', $rowarray);
}
echo $this->ProcessTemplate('feuille4joueurs.tpl');
?>