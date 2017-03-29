<?php

#-------------------------------------------------------------------------
# Module : Livescoring - 
# Version : 0.1, Sc
# Auteur : Claude SIOHAN
#-------------------------------------------------------------------------
/**
 *
 * @author Claude SIOHAN
 * @since 0.1
 * @version $Revision: 3827 $
 * @modifiedby $LastChangedBy: Claude
 * @lastmodified $Date: 2017-03-12 11:56:16 +0200 (Mon, 28 Juil 2015) $
 * @license GPL
 **/

class Livescoring extends CMSModule
{
  
  function GetName() { return 'Livescoring'; }   
  function GetFriendlyName() { return $this->Lang('friendlyname'); }   
  function GetVersion() { return '0.1'; }  
  function GetHelp() { return $this->Lang('help'); }   
  function GetAuthor() { return 'Claude SIOHAN'; } 
  function GetAuthorEmail() { return 'claude.siohan@gmail.com'; }
  function GetChangeLog() { return $this->Lang('changelog'); }
    
  function IsPluginModule() { return true; }
  function HasAdmin() { return true; }   
  function GetAdminSection() { return 'content'; }
  function GetAdminDescription() { return $this->Lang('moddescription'); }
 
  function VisibleToAdminUser()
  {
    	return 
		$this->CheckPermission('Live use');
	
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
	$this->SetParameterType('renc_id',CLEAN_INT);


}

function InitializeAdmin()
{
  	$this->SetParameters();
	//$this->CreateParameter('pagelimit', 100000, $this->Lang('help_pagelimit'));
	
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

  function DoEvent( $originator, $eventname, &$params ){
      if ($originator == 'Core' && $eventname == 'ContentPostRender'){
        
        $side_pos = strripos($params["content"],"</head");
        {
          $temp = "
		<!-- LOAD script -->          
		<script type='text/javascript'>
		$( document ).ready(function()
		{
    			var refreshID = setInterval( function() {
        		$.ajax({
		            type: 'GET',
		            url: 'http://localhost:8888/livescoring/modules/Livescoring/include/checkRefresh.php',
		            dataType: 'html',
		            success: function(html, textStatus) {
		                 //Handle the return data (1 for refresh, 0 for no refresh)
		                if(html == 1)
		                {
		                    location.reload();
		                }
		            }
		            ,
		            error: function(xhr, textStatus, errorThrown) {
		                alert(errorThrown?errorThrown:xhr.status);
		            }
		        });
		    }, (5000 )); //Poll every 5 seconds.
		});
		</script>
	";
       }
    }
}



} //end class
?>
