<?php

if(!isset($gCms)) exit;
$db =& $this->GetDb();

if(!$this->CheckPermission('Live use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
/*
$smarty->assign('form_start',
		$this->CreateFormStart());
*/		

$ping = cms_utils::get_module('Ping');
$nom_equipes = $ping->GetPreference('nom_equipes');
//echo $nom_equipes;
$parms = array();
$saison = $ping->GetPreference('saison_en_cours');
$aujourdhui = date('Y-m-d');
//echo "la saison est : ".$saison;
$query = "SELECT renc_id, saison, libelle, date_event, equa, equb, lien FROM ".cms_db_prefix()."module_ping_poules_rencontres WHERE saison = ? AND date_event > ?";
$query.= " AND club = 1"; 
$parms['saison'] = $saison;
$parms['date_event'] = $aujourdhui;

if(isset($nom_equipes) && $nom_equipes !='')
{
	$query.=" AND equa LIKE ?";
	$parms['equa'] = "%".$nom_equipes."%"; 
}
//echo $query;
$dbresult = $db->Execute($query, $parms);//array($saison, $aujourdhui,$nom_equipes));
$i = 0; //on instancie un compteur
if($dbresult && $dbresult->RecordCount()>0)
{
	while($row = $dbresult->FetchRow())
	{
		
		$renc_id = $row['renc_id'];
		$libelle = $row['libelle'];
		$date = $row['date_event'];
		$equa = $row['equa'];
		$equb = $row['equb'];
		$lien = $row['lien'];
		//var_dump($renc_id);
		if($renc_id !='' || TRUE === is_null($renc_id ))
		{
			parse_str($lien,$output);
			$renc_id = $output['renc_id'];
			//echo $renc_id;
		}
		$query2 = "INSERT INTO ".cms_db_prefix()."module_livescoring_rencontres (id, renc_id, saison, libelle, date_event, equa, equb, lien) VALUES('', ?, ?, ?, ?, ?, ?, ?)";
		$dbresult2 = $db->Execute($query2, array($renc_id, $saison, $libelle, $date, $equa, $equb, $lien));
		$i++;
		if(!$dbresult2)
		{
			echo $this->ErrorMsg();
		}
	}
}
$message = $i. "rencontre(s) insérée(s)";
$this->SetMessage($message);
$this->RedirectToAdminTab('lives');





#
#EOF
#
?>