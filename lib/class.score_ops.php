<?php
#CMS - CMS Made Simple
#(c)2004 by Ted Kulp (wishy@users.sf.net)
#This project's homepage is: http://www.cmsmadesimple.org


class score_ops
{
  function __construct() {}



##
##
	
	
 	function majscore_live_set ($renc_id, $partie, $joueur, $scoreA,$scoreW,$sens, $numero_set,$serv)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		//pour le sens, point en moins ou en plus
		$set_end = 0;
		$aujourdhui = date("Y-m-d H:i:s");
		//on calcule l'écart des points 
		//$ecart = abs($scoreA - $scoreW);
		$edit_serv = 0;//pour savoir si on change de service ou non
		if($sens == "plus")
		{
			//$ecart = $ecart+1;
			
			if($joueur=='A')
			{
				$scoreA = $scoreA+1;
				$scoreW = $scoreW;
				
			}
			else
			{
				$scoreW = $scoreW+1;
				$scoreA = $scoreA;
				
			}
		}
		else
		{
		//	$ecart = $ecart-1;
			if($joueur=='A')
			{
				$scoreA = $scoreA-1;
				$scoreW = $scoreW;
			}
			else
			{
				$scoreW = $scoreW-1;
				$scoreA = $scoreA;
			}
		}
		//on dit qui sert
		$nb_points = $scoreA + $scoreW;
		echo $nb_points;
		$service = $this->qui_sert_en_premier($renc_id, $partie);
		echo "<br />".$service;
		$point_en_cours = $nb_points%2;
		echo $point_en_cours;
		if($nb_points %2 == 0)
		{
			//changement de service
			$edit_serv = 1;
			
			if($serv =="A")
			{
				$affichage_service = "W";
			}
			else
			{
				$affichage_service = "A";
			}
		
					
		}
		
		

		$ecart = abs($scoreA - $scoreW);
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
				$query = "UPDATE ".cms_db_prefix()."module_livescoring_live_parties SET scoreA = scoreA+1, ecart = ?, timbre = ?";
				if ($edit_serv == 1)
				{
					$query.= ", affichage_service = ?";
				}
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
				$query = "UPDATE ".cms_db_prefix()."module_livescoring_live_parties SET scoreA = scoreA-1, ecart = ?, timbre = ? ";
				if ($edit_serv == 1)
				{
					$query.= ", affichage_service = ?";
				}
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
				$query = "UPDATE ".cms_db_prefix()."module_livescoring_live_parties SET scoreW = scoreW+1, ecart = ?, timbre = ?";
				if ($edit_serv == 1)
				{
					$query.= ", affichage_service = ?";
				}
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
				$query = "UPDATE ".cms_db_prefix()."module_livescoring_live_parties SET scoreW = scoreW-1, ecart = ?, timbre = ? ";
				if ($edit_serv == 1)
				{
					$query.= ", affichage_service = ?";
				}
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
		if($edit_serv ==1)
		{
			$dbresult = $db->Execute($query, array($ecart,$aujourdhui,$affichage_service, $renc_id, $partie, $numero_set));
		}
		else
		{
			$dbresult = $db->Execute($query, array($ecart,$aujourdhui, $renc_id, $partie, $numero_set));
		}
		
		
	}
	
	
	function fin_set ($renc_id, $partie, $numero_set,$scoreA, $scoreW, $joueur1, $joueur2)
	{
		//on détermine aussi le vainqueur du set 
		global $gCms;
		$db = cmsms()->GetDb();
		$aujourdhui = date("Y-m-d H:i:s");
		
		$query = "UPDATE ".cms_db_prefix()."module_livescoring_live_parties SET statut = 2, timbre = ?"; 
		
		if($scoreA > $scoreW)
		{
			$query.=" , vicA = 1 ";
		}
		elseif($scoreA < $scoreW)
		{
			$query.=" , vicW = 1 ";
		}
		$query.= " WHERE renc_id = ? AND partie = ? AND numero_set = ?";
		$dbresult = $db->Execute($query, array($aujourdhui, $renc_id, $partie, $numero_set));
		
		//on lance un nouveau set ?
		if($numero_set <3)
		{
			
			$neo_set = $this->neo_set($renc_id, $partie, $numero_set, $joueur1, $joueur2);
			$this->score_partie($renc_id,$partie, $scoreA, $scoreW);
		}
		else
		{
			//on va chercher le nb de sets gagnés par chq joueur
			$vicA = $this->nb_set($renc_id, $partie, $joueur="A");
			$vicW = $this->nb_set($renc_id, $partie, $joueur="W");
		
			
			if ($vicA == "3")
			{
				//victoire du joueur1
				$fin_partie = $this->fin_partie($renc_id, $partie, $vic="A");
				$this->score_partie($renc_id,$partie, $scoreA, $scoreW);
				
			}
			elseif($vicW =="3")
			{
				//victoire du joueur2
				$fin_partie = $this->fin_partie($renc_id, $partie, $vic="W");
				$this->score_partie($renc_id,$partie, $scoreA, $scoreW);
			} 
			else //le nb de sets est >3 mais la partie n'est pas finie
			{
				$neo_set = $this->neo_set($renc_id, $partie, $numero_set, $joueur1, $joueur2);
				$this->score_partie($renc_id,$partie, $scoreA, $scoreW);
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
		
		$aujourdhui = date("Y-m-d H:i:s");
		$numero_set = $numero_set+1;
		$service = $this->qui_sert_en_premier($renc_id, $partie);
		
		if($numero_set %2 == 1)
		{
			//
			$service_set = $service;
		}
		else
		{
			if($service == "A")
			{
				$service_set = "A";
			}
			else
			{
				$service_set = "W";
			}
		}
		
		$active = $this->active_live_partie($renc_id,$partie);
		//on dit que la partie est en live
		$query = "INSERT INTO ".cms_db_prefix()."module_livescoring_live_parties (id, renc_id, partie, numero_set,joueur1, joueur2, scoreA, scoreW, statut, set_end, vicA, vicW, ecart, timbre, service, affichage_service) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
		$dbresult = $db->Execute($query, array($renc_id, $partie, $numero_set, $joueur1, $joueur2, 0, 0, 1, 0, 0, 0, 0,$aujourdhui, $service_set, $service_set));
		
		//$this->score_partie($renc_id,$partie, $scoreA, $scoreW);
	}
	
	function fin_partie ($renc_id, $partie, $vic)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		
		if($vic == 'A')
		{
			$query = "UPDATE ".cms_db_prefix()."module_livescoring_parties SET vicA = 1, vicW = 0, statut = 2 WHERE renc_id = ? AND partie = ?";
		
		}
		else
		{
			$query = "UPDATE ".cms_db_prefix()."module_livescoring_parties SET vicW = 1, vicA = 0, statut = 2 WHERE renc_id = ? AND partie = ?";
		}
		$dbresult = $db->Execute($query, array($renc_id, $partie));
		//on met le score global de la rencontre à jour
		
	}
	//active le live de la partie
	function active_live_partie ($renc_id,$partie)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "UPDATE ".cms_db_prefix()."module_livescoring_parties SET statut = 1 WHERE renc_id = ? AND partie = ?";
		$dbresult = $db->Execute($query, array($renc_id, $partie));
	}
	//met à jour le score de la partie
	function score_partie ($renc_id, $partie, $scoreA, $scoreW)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$concatenation = $scoreA.'-'.$scoreW;
		$query = "UPDATE ".cms_db_prefix()."module_livescoring_parties SET score = CONCAT_WS(' ; ', score, '".$concatenation."') WHERE renc_id = ? AND partie = ? ";
		$dbresult = $db->Execute($query, array($renc_id, $partie));
	}

	function score_global ($renc_id,$vic)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$locaux = $this->mon_club($renc_id);
		if($vic == $locaux)
		{
			//c'est bien mon club qui a remporté la partie
			$query = "UPDATE ".cms_db_prefix()."module_livescoring_lives SET score_locaux = score_locaux+1 WHERE id_live = ?";
		}
		else
		{
			$query = "UPDATE ".cms_db_prefix()."module_livescoring_lives SET score_adversaires = score_adversaires+1 WHERE id_live = ?";
		}
		
		$dbresult = $db->Execute($query, array($renc_id));
		
	}
	//cette fonction détermine si mon club joue en position A ou W
	function mon_club ($renc_id)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT locaux FROM ".cms_db_prefix()."module_livescoring_locaux WHERE renc_id = ?";
		$dbresult = $db->Execute($query, array($renc_id));
		$row = $dbresult->FetchRow();
		$locaux = $row['locaux'];
		return $locaux;
	}
	
	function ecart ($renc_id, $partie, $scoreA, $scoreW)
	{
		
	}
	
	function qui_sert_en_premier ($renc_id, $partie)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT service FROM ".cms_db_prefix()."module_livescoring_live_parties WHERE numero_set = 1 AND renc_id = ? AND partie = ?";
		$service = $db->GetOne($query, array($renc_id, $partie));
		return $service;
	}
	
	function a_qui_de_servir ($renc_id, $partie, $scoreA, $scoreW,$numero_set)
	{
		$premier_service = $this->qui_sert_en_premier ($renc_id, $partie);
		
		if($premier_service == "A")
		{
			//on est dans quel set ? Pair ou impair ?
			if($numero_set %2 ==1)//numéro de set impair
			{
				$service = "A";
			}
			else
			{
				
			}
			
		}
		elseif($premier_service == "W")
		{
			
		}
		
		
		
	}
} // end of class

#
# EOF
#
?>
