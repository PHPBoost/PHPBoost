<?php
/***************************************************************************
 *                                fatal.php
 *                            -------------------
 *   begin                : April 12, 2007
 *   copyright          : (C) 2007 CrowkaiT
 *   email                : crowkait@phpboost.com
 *
 *   
 *
 ***************************************************************************

***************************************************************************/

ob_start();
require_once('../includes/framework/functions.inc.php'); //Fonctions de base.
require_once('../includes/constant.php'); //Constante utiles.
require_once('../includes/framework/errors.class.php');
require_once('../includes/framework/template.class.php');

$Errorh = new Errors(); //!\\Initialisation  de la class des erreurs//!\\
$Template = new Templates; //!\\Initialisation des templates//!\\ 
unset($sql_host, $sql_login, $sql_pass); //Destruction des identifiants bdd.

$CONFIG = array();
@include_once('../cache/config.php');
if( !isset($CONFIG) )
	die('Unable to load config cache!');
define('DIR', $CONFIG['server_path']);
define('HOST', $CONFIG['server_name']);

$get_error_id = !empty($_GET['error']) ? numeric($_GET['error']) : '';

//Si le dossier de langue n'existe pas on prend le suivant exisant.
if( !file_exists('../lang/' . $CONFIG['lang']) )
{
	$rep = '../lang/';
	if( is_dir($rep) ) //Si le dossier existe
	{		
		$dh = @opendir( $rep);
		while( !is_bool($lang = @readdir($dh)) )
		{	
			if( !preg_match('`\.`', $lang) )
			{
				$CONFIG['lang'] = $lang;
				break;
			}
		}	
		@closedir($dh);
	}	
}

$LANG = array();
//!\\ Langues //!\\
include_once('../lang/' . $CONFIG['lang'] . '/main.php'); 

//!\\Initialisation des thèmes//!\\
//Si le thème n'existe pas on prend le suivant présent sur le serveur/
if( !file_exists('../templates/' . $CONFIG['theme']) )
{
	$rep = '../templates/';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$dh = @opendir($rep);
		while ( !is_bool( $theme = readdir( $dh ) ) )
		{	
			if( !preg_match('`\.`', $theme) )
			{
				$CONFIG['theme'] = $theme;
				break;
			}
		}	
		closedir($dh);
	}	
}

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
	<title>' . $LANG['error'] . '</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link href="../templates/' . $CONFIG['theme'] . '/design.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../templates/' . $CONFIG['theme'] . '/global.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../templates/' . $CONFIG['theme'] . '/generic.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../templates/' . $CONFIG['theme'] . '/bbcode.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../templates/' . $CONFIG['theme'] . '/content.css" rel="stylesheet" type="text/css" media="screen" />
	<link rel="shortcut" href="../favicon.ico" />
</head>
<body><br /><br /><br />';

//Récupération de l'erreur dans les logs.
$errinfo = $Errorh->Get_last_error_log();
if( empty($errinfo) )
	list($errinfo['errno'], $errinfo['errstr'], $errinfo['errline'], $errinfo['errfile']) = array('-1', '???', '0', 'unknow');

$Template->Set_filenames(array(
	'error'=> 'error.tpl'
));

$class = $Errorh->Get_errno_class($errinfo['errno']);	
	
$Template->Assign_vars(array(
	'THEME' => $CONFIG['theme'],		
	'ERRORH_IMG' => 'stop',
	'ERRORH_CLASS' => $class,
	'C_ERRORH_CONNEXION' => false,
	'C_ERRORH' => true,
	'L_ERRORH' => sprintf($LANG[$class], $errinfo['errstr'], $errinfo['errline'], basename($errinfo['errfile'])),
	'L_ERROR' => $LANG['error'],
	'U_BACK' => '<a href="' . get_start_page() . '">' . $LANG['index'] . '</a>' . (!empty($_SERVER['HTTP_REFERER']) ? ' &raquo; <a href="' . transid($_SERVER['HTTP_REFERER']) .'">' . $LANG['back'] . '</a>' : ' &raquo; <a href="javascript:history.back(1)">' . $LANG['back'] . '</a>'),
));

$Template->Pparse('error');

echo '</body></html>';

ob_end_flush();

?>