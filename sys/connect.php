<?php

$connect = @mysql_connect("localhost", "root", "root") or die ("Erreur de connexion р la base de donnщes");
@mysql_select_db("tanais", $connect) or die ("Impossible de localiser la base de donnщes");

/*
$connect = @mysql_connect("mysql51-18.pro", "tanaistanais", "r8UpJ5O3") or die ("Erreur de connexion ра la base de donnщes");
@mysql_select_db("tanaistanais", $connect) or die ("Impossible de localiser la base de donnщes");
*/
?>