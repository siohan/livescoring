<?php

if(!isset($gCms)) exit;
//on vérifie les permissions
if(!$this->CheckPermission('Live use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$db =& $this->GetDb();
global $themeObject;
debug_display($params, 'Parameters');

$renc_id = '';
if(isset($params['renc_id']) && $params['renc_id'] != '')
{
	$renc_id = $params['renc_id'];
}
else
{
	$this->SetMessage('Le numéro de la rencontre est manquant !');
	$this->RedirectToAdminTab('lives');
}
$query = "SELECT renc_id, partie, joueur1, joueur2, vicA, vicW, statut FROM ".cms_db_prefix()."module_livescoring_parties WHERE renc_id = ? ORDER BY id ASC";
$dbresult = $db->Execute($query, array($renc_id));
$rowarray = array();
$rowclass = 'row1';
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
		$onerow->plus1 = $this->CreateLink($id, 'score', $returnid, '+', array( "renc_id"=>$row['renc_id'],"partie"=>$row['partie'], "joueur"=>"A"));
		
		if($statut == 1)
		{
			$onerow->live = $this->CreateLink($id, 'live_parties', $returnid, 'Accèdér au live', array("renc_id"=>$row['renc_id']));
		}
		elseif($statut == 0)
		{
			$onerow->live = $this->CreateLink($id, 'score', $returnid, 'Démarrez le set live !', array("objet"=>"debut_partie", "renc_id"=>$row['renc_id'],"partie"=>$row['partie'],"numero_set"=>"0", "joueur1"=>$row['joueur1'],"joueur2"=>$row['joueur2']));
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