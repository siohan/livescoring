<?php

#-------------------------------------------------------------------------
# Module : Ping - 
# Version : 0.2.5, Sc
# Auteur : Claude SIOHAN
#-------------------------------------------------------------------------
/**
 *
 * @author Claude SIOHAN
 * @since 0.3
 * @version $Revision: 3827 $
 * @modifiedby $LastChangedBy: Claude
 * @lastmodified $Date: 2007-03-12 11:56:16 +0200 (Mon, 28 Juil 2015) $
 * @license GPL
 **/

class Livescoring extends CMSModule
{
  
  function GetName() { return 'Livescoring'; }   
  function GetFriendlyName() { return $this->Lang('friendlyname'); }   
  function GetVersion() { return '0.1'; }  
  function GetHelp() { return $this->Lang('help'); }   
  function GetAuthor() { return 'agi-webconseil'; } 
  function GetAuthorEmail() { return 'claude@agi-webconseil.fr'; }
  function GetChangeLog() { return $this->Lang('changelog'); }
    
  function IsPluginModule() { return true; }
  function HasAdmin() { return true; }   
  function GetAdminSection() { return 'content'; }
  function GetAdminDescription() { return $this->Lang('moddescription'); }
 
  function VisibleToAdminUser()
  {
    	return 
		$this->CheckPermission('Live Use');
	
  }
  
  
  function GetDependencies()
  {
	return array('Ping'=>'0.5.6');
  }

  

  function MinimumCMSVersion()
  {
    return "2.0";
  }

  
  function SetParameters()
  { 
  	$this->RegisterModulePlugin();
	$this->RestrictUnknownParams();
	
	//form parameters
	//$this->SetParameterType('submit',CLEAN_STRING);
	//$this->SetParameterType('tourlist',CLEAN_INT);


}

function InitializeAdmin()
{
  	$this->SetParameters();
	//$this->CreateParameter('pagelimit', 100000, $this->Lang('help_pagelimit'));
	$this->CreateParameter('tour', 1, $this->Lang('help_tour'));
	$this->CreateParameter('type_compet', 1, $this->Lang('help_type_compet'));
	$this->CreateParameter('date_debut', '', $this->Lang('help_date_debut') );
	$this->CreateParameter('date_fin', '', $this->Lang('help_date_fin') );
	$this->CreateParameter('limit', 10000, $this->Lang('help_limit'));
}

public function HasCapability($capability, $params = array())
{
   if( $capability == 'tasks' ) return TRUE;
   return FALSE;
}

public function get_tasks()
{
  /*
 $obj = array();
	$obj[0] = new PingRecupFfttTask();
   	$obj[1] = new PingRecupSpidTask();  
	$obj[2] = new PingRecupRencontresTask();
return $obj; 
*/
}

  function GetEventDescription ( $eventname )
  {
    return $this->Lang('event_info_'.$eventname );
  }
     
  function GetEventHelp ( $eventname )
  {
    return $this->Lang('event_help_'.$eventname );
  }

  function InstallPostMessage() { return $this->Lang('postinstall'); }
  function UninstallPostMessage() { return $this->Lang('postuninstall'); }
  function UninstallPreMessage() { return $this->Lang('really_uninstall'); }
  
  
  function _SetStatus($oid, $status) {
    //...
  }





} //end class
?>
