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
//on va faire un switch sur l'objet à traiter, la rencontre, la partie ou le set
$renc_id = '';
if(isset($params['renc_id']) && $params['renc_id'] != '')
{
	$renc_id = $params['renc_id'];
}
$partie = '';
if(isset($params['partie']) && $params['partie'] != '')
{
	$partie = $params['partie'];
}
$sens = '';
if(isset($params['sens']) && $params['sens'] != '')
{
	$sens = $params['sens'];
}
$joueur = '';
if(isset($params['joueur']) && $params['joueur'] != '')
{
	$joueur = $params['joueur'];
}
$score = '';
if(isset($params['score']) && $params['score'] != '')
{
	$score = $params['score'];
}
$numero_set = '';
if(isset($params['numero_set']) && $params['numero_set'] != '')
{
	$numero_set = $params['numero_set'];
}
$scoreA = '';
if(isset($params['scoreA']) && $params['scoreA'] != '')
{
	$scoreA = $params['scoreA'];
}
$scoreW = '';
if(isset($params['scoreW']) && $params['scoreW'] != '')
{
	$scoreW = $params['scoreW'];
}
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
$vic = '';
if(isset($params['vic']) && $params['vic'] != '')
{
	$vic = $params['vic'];
}
$serv = '';
if(isset($params['serv']) && $params['serv'] != '')
{
	$serv = $params['serv'];
}

switch($params['objet'])
{
	case "partie" :
		$retrieve = new score_ops();
		$score_partie = $retrieve->fin_partie($renc_id,$partie,$vic);
		$score_global = $retrieve->score_global($renc_id,$vic);
		$this->Redirect($id, 'ordre_parties', $returnid, array("renc_id"=>$renc_id));
	break;
	case "set":
		$retrieve = new score_ops();
		$majscore = $retrieve->majscore_live_set($renc_id, $partie, $joueur, $scoreA,$scoreW,$sens, $numero_set,$serv);
		//$ecart = $retrieve->ecart($renc_id, $partie, $scoreA, $scoreW);
		
		$this->Redirect($id, 'live_parties', $returnid, array("renc_id"=>$renc_id));
	break;
	case "fin_set" :
		$retrieve = new score_ops();
		$fin_set = $retrieve->fin_set($renc_id, $partie, $numero_set, $scoreA, $scoreW, $joueur1, $joueur2);
		$this->Redirect($id, 'live_parties', $returnid, array("renc_id"=>$renc_id));
		
	break;
	case "debut_partie" :
		$retrieve = new score_ops();
		$numero_set = 0;
		$debut_set = $retrieve->neo_set($renc_id,$partie, $numero_set,$joueur1,$joueur2);
		$this->Redirect($id, 'live_parties', $returnid, array("renc_id"=>$renc_id));
	break;
}

?>