<?php

// ###################  UNICI DATI DA MODIFICARE ###########################################

$nome="djfacl";
$nomecom = "com_".$nome;
$basebak="..\\..\\backup-joomla";

echo("<h3>Procedura allineamento Componente <b>$nome</b></h3>");

// ###################  VARIABILI INTERNE ##################################################

$base="..\\..";
$basedev=$nomecom;

echo("<p>Rimozione cartella esistente</p>");

rrmdir($basedev);

// ###################  BACKUP #############################################################

echo("<p>Backup file precedenti</p>");

full_copy($base."\\administrator\\components\\".$nomecom."\\",$basebak."\\administrator\\components\\".$nomecom."\\");
full_copy($base."\\administrator\\language\\",$basebak."\\administrator\\language\\");
full_copy($base."\\components\\".$nomecom."\\",$basebak."\\components\\".$nomecom."\\");
full_copy($base."\\language\\",$basebak."\\language\\");
full_copy($base."\\media\\".$nomecom."\\",$basebak."\\media\\".$nomecom."\\");

// ###################  ALLINEAMENTO COMPONENTE ############################################

echo("<p>Allineamento componente</p>");

full_copy($base."\\administrator\\components\\".$nomecom."\\",$basedev."\\admin\\");
full_copy($base."\\components\\".$nomecom."\\",$basedev."\\component\\");
full_copy($base."\\media\\".$nomecom."\\",$basedev."\\media\\");
copy($base."\\administrator\\components\\".$nomecom."\\".$nome.".xml",$basedev."\\".$nome.".xml");
unlink($basedev."\\admin\\".$nome.".xml");

// ###################  ALLINEAMENTO LINGUA ############################################

echo("<p>Allineamento lingua</p>");

$lingua="it-IT";
@mkdir($basedev."\\admin\\lang\\",0,true);
@mkdir($basedev."\\component\\lang\\",0,true);
copy($base."\\administrator\\language\\".$lingua."\\".$lingua.".".$nomecom.".ini",$basedev."\\admin\\lang\\".$lingua.".".$nomecom.".ini");
copy($base."\\language\\".$lingua."\\".$lingua.".".$nomecom.".ini",$basedev."\\component\\lang\\".$lingua.".".$nomecom.".ini");

$lingua="en-GB";
@mkdir($basedev."\\admin\\lang\\",0,true);
@mkdir($basedev."\\component\\lang\\",0,true);
copy($base."\\administrator\\language\\".$lingua."\\".$lingua.".".$nomecom.".ini",$basedev."\\admin\\lang\\".$lingua.".".$nomecom.".ini");
copy($base."\\language\\".$lingua."\\".$lingua.".".$nomecom.".ini",$basedev."\\component\\lang\\".$lingua.".".$nomecom.".ini");

// rem ###################  ZIP  ###########################################################

echo("<p>Creazione del pacchetto zip</p>");

$nomezip=".//packages//".$nomecom.".zip";
unlink($nomezip);
$archive = new PclZip($nomezip);
$v_list = $archive->add($basedev);

if ($v_list == 0) {
	echo("<p>Errore nella creazione del pacchetto</p>");
   die("Error : ".$archive->errorInfo(true));
}

echo("<p>Pacchetto creato correttamente</p>");

rrmdir($basedev);

?>