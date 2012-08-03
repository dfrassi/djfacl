<?php


defined( '_JEXEC' ) or die( 'Restricted access' );
require_once (JPATH_PLUGINS.DS.'system'.DS.'djflibraries'.DS.'utility.php');

$groupid = utility::getArray('select id from #__viewlevels where title = "djfacl"');
$sizeArray = sizeof($groupid);
if ($sizeArray==0){
	$maxid = utility::getArray('select max(id) massimo from #__viewlevels');
	$massimo = $maxid[0]->massimo;
	$massimo=$massimo+1;
	$queryToUpdate="insert into #__viewlevels (id, title) values ($massimo,'djfacl')";
	utility::executeQuery($queryToUpdate);
}


$moduleid = utility::getArray('select id from #__modules where module = "mod_djfacl_quickicon"');
$sizeArray = sizeof($moduleid);
$modulemenuid = utility::getArray('select moduleid from #__modules_menu where moduleid = '.$moduleid[0]->id.' and menuid=0');
$sizeArray2 = sizeof($modulemenuid);

if ($sizeArray>0 && $sizeArray2==0){
	$queryToUpdate="insert into #__modules_menu (moduleid, menuid) values (".$moduleid[0]->id.",'djfacl')";
	utility::executeQuery($queryToUpdate);

}

?>