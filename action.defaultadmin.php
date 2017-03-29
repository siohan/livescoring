<?php
   if ( !isset($gCms) ) exit; 
	if (!$this->CheckPermission('Live use'))
	{
		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
	}
	
echo $this->StartTabheaders();
if (FALSE == empty($params['active_tab']))
  {
    $tab = $params['active_tab'];
  } else {
  $tab = 'lives';
 }	
	echo $this->SetTabHeader('rencontres', 'Rencontres', ('rencontres' == $tab)?true:false);
	echo $this->SetTabHeader('lives', 'Lives', ('lives' == $tab)?true:false);

	


echo $this->EndTabHeaders();

echo $this->StartTabContent();
	
	/**/
	echo $this->StartTab('rencontres', $params);
    	include(dirname(__FILE__).'/action.admin_rencontres_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('lives', $params);
    	include(dirname(__FILE__).'/action.admin_lives_tab.php');
   	echo $this->EndTab();
	/**/



echo $this->EndTabContent();
//on a refermé les onglets
?>