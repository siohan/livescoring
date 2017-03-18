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
$aujourdhui = date('Y-m-d');
$ping = new Ping();
$saison = $ping->GetPreference('saison_en_cours');
$smarty->assign('add_edit_live',
		$this->CreateLink($id, 'add_edit_live', $returnid, 'Ajouter un live'));
$smarty->assign('import_from_ping',
				$this->CreateLink($id, 'import_from_ping', $returnid, 'Importer depuis le module Ping'));
$query = "SELECT renc_id, libelle, equa, equb, date_event FROM ".cms_db_prefix()."module_livescoring_rencontres WHERE saison = ? AND date_event >= ?";
$dbresult = $db->Execute($query, array('2016-2017',$aujourdhui));
$rowarray = array();
$rowclass = 'row1';
if($dbresult && $dbresult->RecordCount() >0)
{
	
	while($row = $dbresult->FetchRow())
	{
	
		$onerow = new StdClass();
		$onerow->rowclass = $rowclass;
		$onerow->renc_id= $row['renc_id'];
		$onerow->libelle= $row['libelle'];
		$onerow->equa= $row['equa'];
		$onerow->equb= $row['equb'];
		$onerow->date_event= $row['date_event'];
		$onerow->live= $this->CreateLink($id, 'gotolive', $returnid, $contents='Passez au live',array("renc_id"=>$row['renc_id']));//$row['closed'];
		
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
echo $this->ProcessTemplate('rencontres.tpl');

?>