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
$smarty->assign('retour',
$this->CreateLink($id, 'ordre_parties', $returnid, '<< Retour à la feuille de match', array("renc_id"=>$renc_id)));
$query = "SELECT renc_id, partie FROM ".cms_db_prefix()."module_livescoring_parties WHERE statut = 1";
$dbresult = $db->Execute($query);
$rowarray = array();
$rowclass = 'row1';
if($dbresult && $dbresult->RecordCount()>0)
{
	while($row = $dbresult->FetchRow())
	{
		$renc_id = $row['renc_id'];
		$partie = $row['partie'];
		
		//on fait une nouvelle requete
		$query2 = "SELECT renc_id,joueur1, joueur2, scoreA, scoreW, numero_set,set_end, statut FROM ".cms_db_prefix()."module_livescoring_live_parties WHERE renc_id = ? AND partie = ? AND statut = 1";
		$dbresult2 = $db->Execute($query2, array($renc_id, $partie));
		
		if($dbresult2)
		{
			if($dbresult2->RecordCount()>0)
			{
				while($row2 = $dbresult2->FetchRow())
				{
					$onerow = new StdClass();
					$onerow->rowclass = $rowclass;
					$onerow->renc_id = $row2['renc_id'];
					$onerow->partie = $partie;
					$onerow->joueur1 = $row2['joueur1'];
					$onerow->joueur2 = $row2['joueur2'];
					$onerow->numero_set = $row2['numero_set'];
					$onerow->scoreA = $row2['scoreA'];
					$onerow->scoreW = $row2['scoreW'];
					$onerow->statut = $row2['statut'];
					$onerow->set_end = $row2['set_end'];
					$onerow->plusA1 = $this->CreateLink($id, 'score', $returnid, '+', array("objet"=>"set", "renc_id"=>$row['renc_id'],"partie"=>$row['partie'], "joueur"=>"A","sens"=>"plus","numero_set"=>$row2['numero_set'], "scoreA"=>$row2['scoreA'],"scoreW"=>$row2['scoreW']));
					$onerow->plusW1 = $this->CreateLink($id, 'score', $returnid, '+', array("objet"=>"set", "renc_id"=>$row['renc_id'],"partie"=>$row['partie'], "joueur"=>"W","sens"=>"plus","numero_set"=>$row2['numero_set'], "scoreA"=>$row2['scoreA'],"scoreW"=>$row2['scoreW']));
					$onerow->moinsA1 = $this->CreateLink($id, 'score', $returnid, '-', array("objet"=>"set", "renc_id"=>$row['renc_id'],"partie"=>$row['partie'], "joueur"=>"A","sens"=>"moins","numero_set"=>$row2['numero_set'], "scoreA"=>$row2['scoreA'],"scoreW"=>$row2['scoreW']));
					$onerow->moinsW1 = $this->CreateLink($id, 'score', $returnid, '-', array("objet"=>"set", "renc_id"=>$row['renc_id'],"partie"=>$row['partie'], "joueur"=>"W","sens"=>"moins","numero_set"=>$row2['numero_set'], "scoreA"=>$row2['scoreA'],"scoreW"=>$row2['scoreW']));
					$onerow->fin_set = $this->CreateLink($id, 'score', $returnid, 'Fin du set ?', array("objet"=>"fin_set", "renc_id"=>$row['renc_id'],"partie"=>$row['partie'], "numero_set"=>$row2['numero_set'], "scoreA"=>$row2['scoreA'],"scoreW"=>$row2['scoreW'], "joueur1"=>$row2['joueur1'], "joueur2"=>$row2['joueur2']));

					($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
					$rowarray[]= $onerow;
				}
				$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
				$smarty->assign('itemcount', count($rowarray));
				$smarty->assign('items', $rowarray);
			}
			else
			{
				echo "Pas de live en ce moment !";
			}
			
		}
		else
		{
			echo $this->ErrorMsg();
		}
		
	}
	echo $this->ProcessTemplate('live_set.tpl');
}
