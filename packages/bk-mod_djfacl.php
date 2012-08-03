<?php

// ###################  UNICI DATI DA MODIFICARE ###########################################

$nome="djfacl";
$nomemod = "mod_".$nome;
$tipomod = "site";

if ($tipomod == "site") {
	$basebak="..\\..\\backup-joomla";
}else{
	$basebak="..\\..\\backup-joomla\\administrator\\";
}

echo("<h3>Procedura allineamento Module <b>$nome</b></h3>");

// ###################  VARIABILI INTERNE ##################################################

if ($tipomod == "site") {
	$base="..\\..";
}
else {
	$base="..\\..\\administrator\\";
}

$basedev=$nomemod;

echo("<p>Rimozione cartella esistente</p>");

rrmdir($basedev);


// ###################  BACKUP #############################################################

echo("<p>Backup file precedenti</p>");

full_copy($base."\\modules\\".$nomemod."\\",$basebak."\\modules\\".$nomemod."\\");

// ###################  ALLINEAMENTO MOD ################################################

echo("<p>Allineamento plugin</p>");

full_copy($base."\\modules\\".$nomemod."\\",$basedev."\\");


// rem ###################  ZIP  ###########################################################

echo("<p>Creazione del pacchetto zip</p>");

$nomezip=".\\packages\\".$nomemod.".zip";
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