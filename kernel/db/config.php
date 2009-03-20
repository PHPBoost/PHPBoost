<?php
if (!defined('DBSECURE'))
{
    $sql_host = "localhost"; //Adresse serveur MySQL - MySQL server address
    $sql_login = "root"; //Login
    $sql_pass = ""; //Mot de passe - Password
    $sql_base = "phpboost3"; //Nom de la base de données - Database name
    define('PREFIX' , 'phpboost_'); //Préfixe des tables - Tables prefix
    define('DBSECURE', true);
    define('PHPBOOST_INSTALLED', true);
    
    require_once PATH_TO_ROOT . '/kernel/db/tables.php';
}
else
{
    exit;
}
?>