<?php
//Include my session file which has session_start() and my database credentials.
$path = dirname(dirname(__FILE__));
//echo $path."<br />";
$absolute_path = str_replace('modules','',$path);
//echo $absolute_path;
define('ROOT',dirname($absolute_path));
//echo ROOT;
require_once(ROOT.'/config.php');
require_once(ROOT.'/modules/Livescoring/include/session.php');

$link = mysqli_connect($config['db_hostname'],$config['db_username'],$config['db_password'],$config['db_name']) or die("Error " . mysqli_error($link));
/* check connection */
if (mysqli_connect_errno()) {
    $message = "Connection impossible";
    
}
//include('include/session.php');
//echo "on passe à la requete";
//Get the latest maximum timestamp
$query = "SELECT MAX(UNIX_TIMESTAMP(timbre)) AS maxts FROM ".$config['db_prefix']."module_livescoring_live_parties WHERE statut = 1";
$result = $link->query($query);
$row = mysqli_fetch_assoc($result);

//echo "la session maxts est : ".$_SESSION['MAXTS'];
$maxts = $row['maxts'];
//echo "<br />".$maxts."<br />";
// echo $maxts;
//Compare the latest database timestamp with the one in the session.
if($row['maxts'] > $_SESSION['MAXTS'])
{
    //If there's something more recent, store it and return true.
    $_SESSION['MAXTS'] = $row['maxts'];
    echo 1;
}
else
{
    //Otherwise, we have no reason to refresh.  Return false.
    echo 0;
}
?>