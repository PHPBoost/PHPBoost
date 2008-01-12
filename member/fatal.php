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
require_once('../includes/function.php'); //Fonctions de base.
require_once('../includes/constant.php'); //Constante utiles.
require_once('../includes/errors.class.php');
require_once('../includes/template.class.php');
require_once('../includes/sessions.class.php');

$errorh = new Errors(); //!\\Initialisation  de la class des erreurs//!\\
$template = new Templates; //!\\Initialisation des templates//!\\ 
unset($sql_host, $sql_login, $sql_pass); //Destruction des identifiants bdd.

$CONFIG = array();
@include_once('../cache/config.php');
if( !isset($CONFIG) )
	die('Unable to load config cache!');
	
$session = new Sessions; //!\\Initialisation  de la class des sessions//!\\

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
$errinfo = $errorh->get_last_error_log();
if( empty($errinfo) )
	list($errinfo['errno'], $errinfo['errstr'], $errinfo['errline'], $errinfo['errfile']) = array('-1', '???', '0', 'unknow');

$template->set_filenames(array(
	'error' => '../templates/' . $CONFIG['theme'] . '/error.tpl'
));

$class = $errorh->get_errno_class($errinfo['errno']);
$template->assign_block_vars('error', array(
	'CLASS' => $class,
	'L_ERROR' => sprintf($LANG[$class], $errinfo['errstr'], $errinfo['errline'], basename($errinfo['errfile']))
));	

//Récupération de la page de démarrage.
if( substr($CONFIG['start_page'], 0, 1) == '/' )
{
	$server_name = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
	$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
	if( !$server_path )
		$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
	$start_page = trim(dirname($server_path)) . $CONFIG['start_page'];
	//Suppression du dossier courant, et trim du chemin de l'installateur.
	$start_page = 'http://' . $server_name . preg_replace('`(.*)/[a-z]+(' . $CONFIG['start_page'] . ')(.*)`i', '$1$2', $start_page);
}
else
	$start_page = $CONFIG['start_page'];
	
$template->assign_vars(array(
	'THEME' => $CONFIG['theme'],		
	'L_ERROR' => $LANG['error'],
	'U_BACK' => '<a href="' . $start_page . '">' . $LANG['index'] . '</a>' . (!empty($_SERVER['HTTP_REFERER']) ? ' &raquo; <a href="' . transid($_SERVER['HTTP_REFERER']) .'">' . $LANG['back'] . '</a>' : ' &raquo; <a href="javascript:history.back(1)">' . $LANG['back'] . '</a>'),
));

$template->pparse('error');

echo '</body></html>';

ob_end_flush();

?>