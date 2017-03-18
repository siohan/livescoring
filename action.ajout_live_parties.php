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
$partie = '';
$joueur1 = '';
if(isset($params['joueur1']) && $params['joueur1'] != '')
{
	$joueur1 = $params['joueur1'];
}
$joueur2 = '';
if(isset($params['joueur2']) && $params['joueur2'] != '')
{
	$joueur2 = $params['joueur2'];
}
$n_set = 1;
$scoreA = 0;
$scoreW = 0;
$statut = 1;
$vicA = 0;
$vicW = 0;
if(isset($params['partie']) && $params['partie'] != '')
{
	$partie = $params['partie'];
}
else
{
	$this->SetMessage('Partie manquante !');
	$this->Redirect($id, 'ordre_parties', $returnid, array($renc_id));
}
//on met le statut de la partie à en cours (= 1)
$query1 = "UPDATE ".cms_db_prefix()."module_livescoring_parties SET statut = 1 WHERE renc_id = ? AND partie = ?";
$dbresult1 = $db->Execute($query1, array($renc_id, $partie));
$query = "INSERT INTO ".cms_db_prefix()."module_livescoring_live_parties (id, renc_id, partie, joueur1, joueur2, numero_set, scoreA, scoreW, statut, vicA, vicW) VALUES('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$dbresult = $db->Execute($query, array($renc_id, $partie, $joueur1, $joueur2,$n_set, $scoreA, $scoreW, $statut, $vicA, $vicW));

$this->Redirect($id, 'live_parties', $returnid, array("renc_id"=>$renc_id));