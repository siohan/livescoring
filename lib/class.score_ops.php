<?php
#CMS - CMS Made Simple
#(c)2004 by Ted Kulp (wishy@users.sf.net)
#This project's homepage is: http://www.cmsmadesimple.org


class score_ops
{
  function __construct() {}



##
##

 	function majscore_live_set ($renc_id, $partie, $joueur, $scoreA,$scoreW,$sens, $numero_set)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		//pour le sens, point en moins ou en plus
		$set_end = 0;
		//on calcule l'écart des points 
		$ecart = abs($scoreA - $scoreW);
		
		if($sens == "plus")
		{
			$ecart = $ecart+1;
			
			if($joueur=='A')
			{
				$scoreA = $scoreA+1;
			}
			else
			{
				$scoreW = $scoreW+1;
			}
		}
		else
		{
			$ecart = $ecart-1;
			if($joueur=='A')
			{
				$scoreA = $scoreA-1;
			}
			else
			{
				$scoreW = $scoreW-1;
			}
		}
		
		if($ecart >=2)
		{
			if($scoreA >= 11 || $scoreW >= 11)
			{
				//fin du set !
				$set_end = 1;
				//and the winner is ...
				if($scoreA > $scoreW)
				{
					$vicA = 1;
				}
				else
				{
					$vicB = 1;
				}
			
			
			}
			else
			{
				$set_end = 0;
			}
		}
			
		
		if($joueur =='A')
		{
			if($sens =="plus")
			{
				$query = "UPDATE ".cms_db_prefix()."module_livescoring_live_parties SET scoreA = scoreA+1, ecart = ? ";
				if($set_end ==1)
				{
					$query.= " , set_end = 1 ";
				}
				else
				{
					$query.=" , set_end = 0 ";
				}
				$query.= "WHERE renc_id = ? AND partie = ? AND numero_set = ?";
			}
			else
			{
				$query = "UPDATE ".cms_db_prefix()."module_livescoring_live_parties SET scoreA = scoreA-1, ecart = ? ";
				if($set_end ==1)
				{
					$query.= " , set_end = 1 ";
				}
				else
				{
					$query.=" , set_end = 0 ";
				}
				$query.= "WHERE renc_id = ? AND partie = ? AND numero_set = ?";
			}
		}
		else
		{
			if($sens =="plus")
			{
				$query = "UPDATE ".cms_db_prefix()."module_livescoring_live_parties SET scoreW = scoreW+1, ecart = ?";
				if($set_end ==1)
				{
					$query.= " , set_end = 1 ";
				}
				else
				{
					$query.=" , set_end = 0 ";
				}
				$query.= "WHERE renc_id = ? AND partie = ? AND numero_set = ?";
			}
			else
			{
				$query = "UPDATE ".cms_db_prefix()."module_livescoring_live_parties SET scoreW = scoreW-1, ecart = ? ";
				if($set_end ==1)
				{
					$query.= " , set_end = 1 ";
				}
				else
				{
					$query.=" , set_end = 0 ";
				}
				$query.= "WHERE renc_id = ? AND partie = ? AND numero_set = ?";
			}
		}
		$dbresult = $db->Execute($query, array($ecart, $renc_id, $partie, $numero_set));
		
		
	}
	
	
	function fin_set ($renc_id, $partie, $numero_set,$scoreA, $scoreW, $joueur1, $joueur2)
	{
		//on détermine aussi le vainqueur du set 
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "UPDATE ".cms_db_prefix()."module_livescoring_live_parties SET statut = 2"; 
		
		if($scoreA > $scoreW)
		{
			$query.=" , vicA = 1 ";
		}
		elseif($scoreA < $scoreB)
		{
			$query.=" , vicW = 1 ";
		}
		$query.= " WHERE renc_id = ? AND partie = ? AND numero_set = ?";
		$dbresult = $db->Execute($query, array($renc_id, $partie, $numero_set));
		
		//on lance un nouveau set ?
		if($numero_set <3)
		{
			$neo_set = $this->neo_set($renc_id, $partie, $numero_set, $joueur1, $joueur2);
		}
		else
		{
			//on va chercher le nb de sets gagnés par chq joueur
			$vicA = $this->nb_set($renc_id, $partie, $joueur="A");
			var_dump($vicA);
			$vicW = $this->nb_set($renc_id, $partie, $joueur="W");
			var_dump($vicW);
			
			if ($vicA == "3")
			{
				//victoire du joueur1
				$fin_partie = $this->fin_partie($renc_id, $partie, $vic="A");
				
			}
			elseif($vicW =="3")
			{
				//victoire du joueur2
				$fin_partie = $this->fin_partie($renc_id, $partie, $vic="W");
			} 
		}
	}
	
	function nb_set ($renc_id, $partie, $joueur)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		
		if($joueur == 'A')
		{
			$query = "SELECT SUM(vicA) AS nbvic FROM ".cms_db_prefix()."module_livescoring_live_parties WHERE renc_id = ? AND partie = ?";
		}
		else
		{
			$query = "SELECT SUM(vicW) AS nbvic FROM ".cms_db_prefix()."module_livescoring_live_parties WHERE renc_id = ? AND partie = ?";
		}
		$dbresult = $db->Execute($query, array($renc_id, $partie));
		$row = $dbresult->FetchRow();
		$nbvic = $row['nbvic'];
		return $nbvic;
		
	}
	function neo_set ($renc_id, $partie, $numero_set, $joueur1, $joueur2)
	{
		
		global $gCms;
		$db = cmsms()->GetDb();
		$numero_set = $numero_set+1;
		$active = $this->active_live_partie($renc_id,$partie);
		//on dit que la partie est en live
		$query = "INSERT INTO ".cms_db_prefix()."module_livescoring_live_parties (id, renc_id, partie, numero_set,joueur1, joueur2, scoreA, scoreW, statut, set_end, vicA, vicW, ecart) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
		$dbresult = $db->Execute($query, array($renc_id, $partie, $numero_set, $joueur1, $joueur2, 0, 0, 1, 0, 0, 0, 0));
	}
	
	function fin_partie ($renc_id, $partie, $vic)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		
		if($vic == 'A')
		{
			$query = "UPDATE ".cms_db_prefix()."module_livescoring_parties SET vicA = 1, statut = 2 WHERE renc_id = ? AND partie = ?";
		}
		else
		{
			$query = "UPDATE ".cms_db_prefix()."module_livescoring_parties SET vicW = 1, statut = 2 WHERE renc_id = ? AND partie = ?";
		}
		$dbresult = $db->Execute($query, array($renc_id, $partie));
	}
	
	function active_live_partie ($renc_id,$partie)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "UPDATE ".cms_db_prefix()."module_livescoring_parties SET statut = 1 WHERE renc_id = ? AND partie = ?";
		$dbresult = $db->Execute($query, array($renc_id, $partie));
	}


} // end of class

#
# EOF
#
?>
