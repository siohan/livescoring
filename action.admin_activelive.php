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
$query = "UPDATE ".cms_db_prefix()."module_livescoring_lives SET actif = 1 WHERE id_live = ?";
$dbresult = $db->Execute($query, array($id_live));
$this->Redirect($id, 'defaultadmin', $returnid, array("id_live"=>$id_live));


?>