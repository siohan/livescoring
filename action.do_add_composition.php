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
$joueurA = '';
$joueurB = '';
$joueurC = '';
$joueurD = '';
$joueurW = '';
$joueurX = '';
$joueurY = '';
$joueurZ = '';
$locaux = '';
if(isset($params['locaux']) && $params['locaux'] != '')
{
	$locaux = $params['locaux'];
}
if(isset($params['joueurA']) && $params['joueurA'] != '')
{
	$joueurA = $params['joueurA'];
}
if(isset($params['joueurB']) && $params['joueurB'] != '')
{
	$joueurB = $params['joueurB'];
}
if(isset($params['joueurC']) && $params['joueurC'] != '')
{
	$joueurC = $params['joueurC'];
}
if(isset($params['joueurD']) && $params['joueurD'] != '')
{
	$joueurD = $params['joueurD'];
}
if(isset($params['joueurW']) && $params['joueurW'] != '')
{
	$joueurW = $params['joueurW'];
}
if(isset($params['joueurX']) && $params['joueurX'] != '')
{
	$joueurX = $params['joueurX'];
}
if(isset($params['joueurY']) && $params['joueurY'] != '')
{
	$joueurY = $params['joueurY'];
}
if(isset($params['joueurZ']) && $params['joueurZ'] != '')
{
	$joueurZ = $params['joueurZ'];
}
//edition ou enregistrement ?
$submit = '';
if(isset($params['submit']) && $params['submit'])
{
	$submit = $params['submit'];

}
if($submit =='Enregistrer')
{
	$edit = 0;
}
elseif($submit == 'Modifier')
{
	$edit = 1;
}
if($edit ==1)
{
	$query1 = "UPDATE ".cms_db_prefix()."module_livescoring_locaux SET locaux = ? WHERE renc_id = ?";
	$dbresult1 = $db->Execute($query1, array($locaux, $renc_id));
}
else
{
	$query1 = "INSERT INTO ".cms_db_prefix()."module_livescoring_locaux (id, renc_id, locaux) VALUES ('', ?, ?)";
	$dbresult1 = $db->Execute($query1, array($renc_id, $locaux));
}
$tableau1 = array('A', 'B', 'C','D', 'W','X','Y','Z');
foreach($tableau1 as $valeur1)
{
	//on va faire une boucle pour chq joueur, on peut aussi faire la feuille de match
	if($edit == 0)
	{
		//on est dans un ajout
	
		$query = "INSERT INTO ".cms_db_prefix()."module_livescoring_compositions (id, renc_id,id_joueur, joueur) VALUES ('', ?, ?, ?)";
		$dbresult = $db->Execute($query, array($renc_id, $valeur1,${'joueur'.$valeur1}));

	}
	elseif($edit == 1)
	{
		//première chose, on efface tt !
		$query = "UPDATE ".cms_db_prefix()."module_livescoring_compositions SET joueur = ? WHERE renc_id = ? AND id_joueur = ?";
		$dbresult = $db->Execute($query, array(${'joueur'.$valeur1},$renc_id,$valeur1));
	}
	
}


//on va faire l'ordre des parties
$tableau = array("AW","BX", "CY","DZ","AX", "BW", "DY", "CZ","DblAW","DblBX", "AY", "CW", "DX", "BZ");
foreach ($tableau as $valeur)
{
	//var_dump($valeur);
	//echo strlen($valeur);
	if($edit == 0)
	{
		if(strlen($valeur) < 3)
		{
			$joueur1 = substr($valeur,0,1);
			$joueur2 = substr($valeur,1,1);;
			$query2 = "INSERT INTO ".cms_db_prefix()."module_livescoring_parties (id, renc_id, partie, joueur1, joueur2) VALUES ('', ?, ?, ?, ?)";
			$dbresult2 = $db->Execute($query2, array($renc_id, $valeur, ${'joueur'.$joueur1},${'joueur'.$joueur2}));
		}
		else //pour les doubles
		{
			$query2 = "INSERT INTO ".cms_db_prefix()."module_livescoring_parties (id, renc_id, partie) VALUES ('', ?, ?)";
			$dbresult2 = $db->Execute($query2, array($renc_id, $valeur));
		}
	}
	else
	{
		//en cas de update
		if(strlen($valeur) < 3)
		{
			$joueur1 = substr($valeur,0,1);
			$joueur2 = substr($valeur,1,1);;
			$query2 = "UPDATE ".cms_db_prefix()."module_livescoring_parties SET joueur1 = ?, joueur2 = ? WHERE renc_id = ? AND partie = ?";
			$dbresult2 = $db->Execute($query2, array( ${'joueur'.$joueur1},${'joueur'.$joueur2},$renc_id, $valeur));
		}
		else //pour les doubles
		{
			$query2 = "UPDATE ".cms_db_prefix()."module_livescoring_parties SET joueur1 = ?, joueur2 = ? WHERE renc_id = ? AND partie = ?";
			$dbresult2 = $db->Execute($query2, array( ${'joueur'.$joueur1},${'joueur'.$joueur2},$renc_id, $valeur));
		}
		
	}
	
}
$this->redirect($id, 'ordre_parties', $returnid, array("renc_id"=>$renc_id));
