<?php
#-------------------------------------------------------------------------
# Module: Livescoring
# Version: 0.1, Claude SIOHAN Agi webconseil
# Method: Install
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2008 by Ted Kulp (wishy@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
# The module's homepage is: http://dev.cmsmadesimple.org/projects/skeleton/
#
#-------------------------------------------------------------------------

/**
 * For separated methods, you'll always want to start with the following
 * line which check to make sure that method was called from the module
 * API, and that everything's safe to continue:
 */ 
if (!isset($gCms)) exit;


/** 
 * After this, the code is identical to the code that would otherwise be
 * wrapped in the Install() method in the module body.
 */

$db = $gCms->GetDb();

// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id_live I(11) AUTO KEY,
	datemaj T,
	libelle C(200),
	description C(255),
	type_competition C(200),
	tour I(2),
	date_compet D,
	locaux C(150),
	adversaires C(150),
	score_locaux I(2) DEFAULT '0',
	score_adversaires I(2) DEFAULT '0',
	niveau C(50),
	region C(100),
	departement C(100),
	actif I(1),
	closed I(1)";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_livescoring_lives", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	renc_id I(11),
	saison C(10),
	libelle C(200),
	date_event D,
	equa C(150),
	equb C(150),
	lien C(255)";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_livescoring_rencontres", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);
	// mysql-specific, but ignored by other database
	$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

	$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	renc_id I(11),
	match C(255),
	score_locaux I(2),
	score_adv I(2)";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_livescoring_detaillives", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//
// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	renc_id I(11) ,
	id_joueur C(2),
	joueur C(255)";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_livescoring_compositions", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//
// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	renc_id I(11) ,
	partie C(5),
	joueur1 C(255),
	joueur2 C(255),
	vicA I(1) DEFAULT 0,
	vicW I(1) DEFAULT 0,
	statut I(1) DEFAULT 0,
	score C(100)";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_livescoring_parties", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//
// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	renc_id I(11) ,
	partie C(5),
	joueur1 C(255),
	joueur2 C(255),
	numero_set I(1),
	scoreA I(2),
	scoreW I(2),
	statut I(1) DEFAULT 0,
	vicA I(1) DEFAULT 0,
	vicW I(1) DEFAULT 0,
	ecart I(2),
	set_end I(1),	
	timbre D,
	service C(1),
	affichage_service C(1)";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_livescoring_live_parties", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//
//les index
$idxoptarray = array('UNIQUE');
$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'compos',
	    cms_db_prefix().'module_livescoring_compositions', 'renc_id, id_joueur',$idxoptarray);
	       $dict->ExecuteSQLArray($sqlarray);
//
$idxoptarray = array('UNIQUE');
$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'parties',
	    cms_db_prefix().'module_livescoring_parties', 'renc_id, partie',$idxoptarray);
	       $dict->ExecuteSQLArray($sqlarray);
//
$idxoptarray = array('UNIQUE');
$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'rencontres',
	    cms_db_prefix().'module_livescoring_rencontres', 'renc_id',$idxoptarray);
	       $dict->ExecuteSQLArray($sqlarray);
//
//
$idxoptarray = array('UNIQUE');
$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'live_parties',
	    cms_db_prefix().'module_livescoring_live_parties', 'renc_id, partie, numero_set',$idxoptarray);
	       $dict->ExecuteSQLArray($sqlarray);
//
//	
//Permissions
$this->CreatePermission('Live use', 'Utiliser le module Livescoring');

$this->AddEventHandler('Core', 'ContentPostRender', false);

// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('installed', $this->GetVersion()) );

	
	      
?>