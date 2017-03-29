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
//debug_display($params, 'Parameters');


$renc_id = '';
if(isset($params['renc_id']) && $params['renc_id'] != '')
{
	$renc_id = $params['renc_id'];
}
//y a t-il déjà une composition d'entrée ?
//on va chercher
$query = "SELECT renc_id, id_joueur, joueur FROM ".cms_db_prefix()."module_livescoring_compositions WHERE renc_id = ?";
$dbresult = $db->Execute($query, array($renc_id));

if($dbresult)
{
	
	while($row = $dbresult->FetchRow())
	{
		$id_joueur = $row['id_joueur'];
	//	$joueur = ''$row['joueur'];
		$tableau1 = array('A', 'B', 'C','D', 'W','X','Y','Z');
		foreach($tableau1 as $valeur1)
		{
			if($id_joueur == $valeur1)
			{
				${'joueur'.$valeur1} = $row['joueur'];
			}
		}
	}
	
	/*
	$joueurB = $row['joueurB'];
	$joueurC = $row['joueurC'];
	$joueurD = $row['joueurD'];
	$joueurW = $row['joueurW'];
	$joueurX = $row['joueurX'];
	$joueurY = $row['joueurY'];
	$joueurZ = $row['joueurZ'];
	*/
	//on a une composition en bdd
$smarty->assign('retour',
 		$this->CreateLink($id, 'defaultadmin',$returnid, '<< Retour'));	
$smarty->assign('next_step', 
		$this->CreateLink($id, 'ordre_parties', $returnid, 'Etape suivante >>',array("renc_id"=>$renc_id)));
$smarty->assign('formstart',
		    $this->CreateFormStart( $id, 'do_add_composition', $returnid ) );
$smarty->assign('renc_id',
		$this->CreateInputHidden($id, 'renc_id', $renc_id, 8, 15));
		$smarty->assign('locaux',
				$this->CreateInputDropdown($id, 'locaux', $items=array("A"=>"A", "W"=>"W")));
	
		$smarty->assign('joueurA',
				$this->CreateInputText($id,'joueurA',(isset($joueurA)?$joueurA:'') ,70, 250));
		$smarty->assign('joueurB',
				$this->CreateInputText($id,'joueurB', (isset($joueurB)?$joueurB:''),70, 250));
		$smarty->assign('joueurC',
				$this->CreateInputText($id,'joueurC', (isset($joueurC)?$joueurC:''),70, 250));
		$smarty->assign('joueurD',
				$this->CreateInputText($id,'joueurD', (isset($joueurD)?$joueurD:''),70, 250));		
		$smarty->assign('joueurW',
				$this->CreateInputText($id,'joueurW', (isset($joueurW)?$joueurW:''),70, 250));		
		$smarty->assign('joueurX',
				$this->CreateInputText($id,'joueurX', (isset($joueurX)?$joueurX:''),70, 250));		
		$smarty->assign('joueurY',
				$this->CreateInputText($id,'joueurY', (isset($joueurY)?$joueurY:''),70, 250));		
		$smarty->assign('joueurZ',
				$this->CreateInputText($id,'joueurZ', (isset($joueurZ)?$joueurZ:''),70, 250));
								

$smarty->assign('submit',
		$this->CreateInputSubmit($id, 'submit', 'Enregistrer'), 'class="button"');
$smarty->assign('maj',
		$this->CreateInputSubmit($id,'submit','Modifier'));
$smarty->assign('formend',
		$this->CreateFormEnd());


echo $this->ProcessTemplate('createuser4.tpl');
}
?>