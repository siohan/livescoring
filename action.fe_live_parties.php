<?php

if(!isset($gCms)) exit;
//on vérifie les permissions
$db =& $this->GetDb();
global $themeObject;

$renc_id = '';
if(isset($params['renc_id']) && $params['renc_id'] != '')
{
	$renc_id = $params['renc_id'];
}

$smarty->assign('retour',
	$this->CreateLink($id, 'fe_ordre_parties', $returnid, '<< Retour', array("renc_id"=>$renc_id)));
$image_service = '<img src="modules/livescoring/images/balle_ping.jpg" class="systemicon" alt="Au service" title="Au service">';
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
		$query2 = "SELECT renc_id,joueur1, joueur2, scoreA, scoreW, numero_set,set_end, statut, affichage_service FROM ".cms_db_prefix()."module_livescoring_live_parties WHERE renc_id = ? AND partie = ? AND set_end = 0";
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
					$onerow->affichage_service = $row2['affichage_service'];
					$onerow->image_service = $image_service;
					
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
	echo $this->ProcessTemplate('fe_live_set.tpl');
}
