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

$query = "SELECT id_live,date_compet,niveau, locaux, adversaires, score_locaux, score_adversaires,actif,closed FROM ".cms_db_prefix()."module_livescoring_lives";
$dbresult = $db->Execute($query);
$rowarray = array();
$rowclass = 'row1';
if($dbresult && $dbresult->RecordCount() >0)
{
	
	while($row = $dbresult->FetchRow())
	{
		$actif = $row['actif'];
		$closed = $row['closed'];
		$onerow = new StdClass();
		$onerow->rowclass = $rowclass;
		$onerow->id_live= $row['id_live'];
		$onerow->date= $row['date_compet'];
		$onerow->niveau= $row['niveau'];
		$onerow->locaux= $row['locaux'];
		$onerow->adversaires= $row['adversaires'];
		$onerow->score_locaux = $row['score_locaux'];
		$onerow->score_adversaires= $row['score_adversaires'];
		if($actif==1)
		{
			$onerow->plusA1 = $this->CreateLink($id, 'score', $returnid, '+', array( "objet"=>"partie","renc_id"=>$row['renc_id'],"partie"=>$row['partie'], "vic"=>"A"));
			$onerow->plus_locaux= $this->CreateLink($id, 'score', $returnid, $contents='+', array("objet"=>"partie","renc_id"=>$row['id_live'],"partie"=>"AA", "score"=>"Plus", "locaux"=>"1"));
			$onerow->moins_locaux= $this->CreateLink($id, 'admin_majscore', $returnid, $contents='-', array("id_live"=>$row['id_live'], "score"=>"Moins", "locaux"=>"1"));
			$onerow->plus_adversaires= $this->CreateLink($id, 'admin_majscore', $returnid, $contents='+', array("id_live"=>$row['id_live'], "score"=>"Plus", "locaux"=>"0"));
			$onerow->moins_adversaires= $this->CreateLink($id, 'admin_majscore', $returnid, $contents='-', array("id_live"=>$row['id_live'], "score"=>"Moins", "locaux"=>"0"));
			$onerow->actif= $row['actif'];
		}
		elseif($actif==0 && $closed ==0)
		{
			$onerow->actif = $this->Createlink($id, 'admin_activelive', $returnid,$contents='démarrez le live', array("id_live"=>$row['id_live']));
		}
		
		$onerow->closed = $row['closed'];
		$onerow->live= $this->CreateLink($id, 'admin_teamLiveClosure', $returnid, $contents='Arrêter le live',array("id_live"=>$row['id_live']));//$row['closed'];
		$onerow->composition= $this->CreateLink($id, 'composition', $returnid, $contents='Détail composition',array("renc_id"=>$row['id_live']));//$row['closed'];
		
		($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
		$rowarray[]= $onerow;
		
	}
	$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
	$smarty->assign('itemcount', count($rowarray));
	$smarty->assign('items', $rowarray);
}
elseif(!$dbresult)
{
	echo $db->ErrorMsg();
}
else
{
	echo "Pas de résultats";
}
//$query.=" ORDER BY date_compet";
echo $this->ProcessTemplate('lives.tpl');

?>