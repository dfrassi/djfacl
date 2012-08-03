<?php

// ###################  UNICI DATI DA MODIFICARE ###########################################

$nome="djflibraries";
$nomecom = "plg_".$nome;
$tipoplg = "system";
$basebak="..\\..\\backup-joomla";

echo("<h3>Procedura allineamento Plugin <b>$nome</b></h3>");

// ###################  VARIABILI INTERNE ##################################################

$base="..\\..";
$basedev=$nomecom;

echo("<p>Rimozione cartella esistente</p>");

rrmdir($basedev);


// ###################  BACKUP #############################################################

echo("<p>Backup file precedenti</p>");

full_copy($base."\\plugins\\".$tipoplg."\\".$nome."\\",$basebak."\\plugins\\".$tipoplg."\\".$nome."\\");

// ###################  ALLINEAMENTO PLUGIN ################################################

echo("<p>Allineamento plugin</p>");

full_copy($base."\\plugins\\".$tipoplg."\\".$nome."\\",$basedev."\\");


// rem ###################  ZIP  ###########################################################

echo("<p>Creazione del pacchetto zip</p>");

$nomezip=".\\packages\\".$nomecom.".zip";
unlink($nomezip);
$archive = new PclZip($nomezip);
$v_list = $archive->add($basedev);

if ($v_list == 0) {
	echo("<p>Errore nella creazione del pacchetto</p>");
   die("Error : ".$archive->errorInfo(true));
}

echo("<p>Pacchetto creato correttamente</p>");

rrmdir($basedev);


////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////





?>