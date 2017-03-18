<?php
if(!isset($gCms)) exit;
/*
if(!$this->CheckPermissions('Live Use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
*/
$db =& $this->GetDb();
global $themeObject;
debug_display($params, 'Parameters');
$id_live = 0;
if(isset($params['id_live']) && $params['id_live'] != '')
{
	$id_live = $params['id_live'];
}
$score = 0;
if(isset($params['score']) && $params['score'] != '')
{
	$score = $params['score'];
}
$locaux = 0;
if(isset($params['locaux']) && $params['locaux'] != '')
{
	$locaux = $params['locaux'];
}

$query = "UPDATE ".cms_db_prefix()."module_livescoring_lives";
if($locaux == 1)
{
	if($score =='Plus')
	{
		$query.=" SET score_locaux = score_locaux+1";
	}
	else
	{
		$query.=" SET score_locaux = score_locaux-1";
	}
}
else
{
	if($score =='Plus')
	{
		$query.=" SET score_adversaires = score_adversaires+1";
	}
	else
	{
		$query.=" SET score_adversaires = score_adversaires-1";
	}
}
$query.=", datemaj = ? WHERE id_live = ?";
$dbresult = $db->Execute($query, array($now,$id_live));

$this->Redirect($id, 'defaultadmin', $returnid);

?>