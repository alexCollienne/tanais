<?php

$connect = @mysql_connect("localhost", "root", "root") or die ("Erreur de connexion � la base de donn�es");
@mysql_select_db("tanais", $connect) or die ("Impossible de localiser la base de donn�es");

/*
$connect = @mysql_connect("mysql51-18.pro", "tanaistanais", "r8UpJ5O3") or die ("Erreur de connexion � la base de donn�es");
@mysql_select_db("tanaistanais", $connect) or die ("Impossible de localiser la base de donn�es");
*/
?>