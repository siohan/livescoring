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
$datemaj = date('H-i-s');
$renc_id = 0;
if(isset($params['renc_id']) && $params['renc_id'] != '')
{
	$renc_id = $params['renc_id'];
}
else
{
	//erreur ! 
}
$query = "SELECT * FROM ".cms_db_prefix()."module_livescoring_rencontres WHERE renc_id = ?";
$dbresult = $db->Execute($query, array($renc_id));

if($dbresult && $dbresult->RecordCount() >0)
{
	while($row = $dbresult->FetchRow())
	{

		
		$renc_id= $row['renc_id'];
		$libelle= $row['libelle'];
		$equa= $row['equa'];
		$equb= $row['equb'];
		$date_event= $row['date_event'];
		
		
		$query2 = "INSERT INTO ".cms_db_prefix()."module_livescoring_lives (id_live,datemaj, libelle, date_compet, locaux, adversaires, score_locaux, score_adversaires, actif, closed ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$dbresult2 = $db->Execute($query2, array($renc_id,$datemaj, $libelle, $date_event, $equa, $equb, 0, 0, 0, 0 ));
		
		if(!$dbresult2)
		{
			//erreur
			echo $this->ErrorMsg();
		}
	}
}
$this->RedirectToAdminTab('lives');



?>