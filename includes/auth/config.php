<?php
if( !defined('DBSECURE') )
{
	$sql_host = "localhost"; //Adresse serveur mysql.
	$sql_login = "root"; //Login
	$sql_pass = ""; //Mot de passe
	$sql_base = "phpboost2"; //Nom de la base de données.
	$host = ""; //Nom du serveur (ex: http://www.google.fr)
	$table_prefix = "phpboost_"; //Préfixe des tables
	$dbtype = "mysql"; //Système de gestion de base de données
	define('DBSECURE', true);
	define('PHPBOOST_INSTALLED', true);
}	
else
{
	exit;
}
?>