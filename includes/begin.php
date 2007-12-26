<?php
/*##################################################
 *                               begin.php
 *                            -------------------
 *   begin                : Februar 08, 2006
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *  
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

//Inclusion des fichiers
require_once('../includes/bench.class.php');
$bench = new Bench; //Début du benchmark
$bench->start_bench('site');
require_once('../includes/function.php'); //Fonctions de base.
require_once('../includes/constant.php'); //Constante utiles.
require_once('../includes/mathpublisher.php'); //Gestion des formules mathématiques.
require_once('../includes/errors.class.php');
require_once('../includes/template.class.php');
require_once('../includes/db/' . DBTYPE . '.class.php');
require_once('../includes/cache.class.php');
require_once('../includes/sessions.class.php');
require_once('../includes/groups.class.php');

//Instanciation des objets indispensables au noyau.
$errorh = new Errors; //!\\Initialisation  de la class des erreurs//!\\
$template = new Templates; //!\\Initialisation des templates//!\\
$sql = new Sql; //!\\Initialisation  de la class sql//!\\
$cache = new Cache($sql->req); //!\\Initialisation  de la class de gestion du cache//!\\

unset($sql_host, $sql_login, $sql_pass); //Destruction des identifiants bdd.

//Chargement ddes fichiers cache, indispensables au noyau.
$cache->load_file('config'); //Requête des configuration générales, $CONFIG variable globale.
$cache->load_file('groups'); //Cache des groupes.
$cache->load_file('member'); //Chargement de la configuration des membres.
@define('DIR', $CONFIG['server_path']);
@define('HOST', $CONFIG['server_name']);

$session = new Sessions($sql->req); //!\\Initialisation  de la class des sessions//!\\

//Activation de la bufférisation de sortie
if( $CONFIG['ob_gzhandler'] == 1 )
	ob_start('ob_gzhandler'); //Activation de la compression de données
else
	ob_start();
	
//Récupération des informations sur le membre.
if( !isset($_POST['connect']) && !isset($_POST['disconnect']) ) 
	$session->session_info();
else
{	
	$session->data['level'] = -1;
	$session->data['user_groups'] = -1;
}
$groups = new Groups($session->data, $_array_groups_auth); //!\\Initialisation  de la class de gestion des groupes//!\\

//Définition de la constante de transmission des infos de session.
if( $session->session_mod )
{
	@define('SID', '?sid=' . $session->data['session_id'] . '&amp;suid=' . $session->data['user_id']);
	@define('SID2', '?sid=' . $session->data['session_id'] . '&suid=' . $session->data['user_id']);
}
else
{
	@define('SID', '');
	@define('SID2', '');
}

//!\\Initialisation des thèmes//!\\
$CONFIG['theme'] = (empty($session->data['user_theme']) || $CONFIG_MEMBER['force_theme'] == 1) ? $CONFIG['theme'] : $session->data['user_theme']; //Thèmes membres
//Si le thème n'existe pas on prend le suivant présent sur le serveur/
if( !file_exists('../templates/' . $CONFIG['theme']) )
{
	$rep = '../templates/';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$dh = @opendir($rep);
		while( !is_bool($theme = @readdir($dh)) )
		{	
			if( !preg_match('`\.`', $theme) )
			{
				$CONFIG['theme'] = $theme;
				break;
			}
		}	
		@closedir($dh);
	}	
}

$CONFIG['lang'] = empty($session->data['user_lang']) ? $CONFIG['lang'] : $session->data['user_lang']; //Langue membres
//Si le dossier de langue n'existe pas on prend le suivant exisant.
if( !file_exists('../lang/' . $CONFIG['lang']) )
{
	$rep = '../lang/';
	if( is_dir($rep) ) //Si le dossier existe
	{		
		$dh = @opendir( $rep);
		while(! is_bool($lang = @readdir($dh)) )
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

//!\\ Langues //!\\
include_once('../lang/' . $CONFIG['lang'] . '/main.php'); 

//Inclusion des langues des erreurs.
include_once('../lang/' . $CONFIG['lang'] . '/errors.php');

//Chargement du cache du jour actuel.
$cache->load_file('day');
if( date('j') != $_record_day && !empty($_record_day) ) //On vérifie que le jour n'a pas changé => sinon on execute les requêtes.. (simulation d'une tache cron).
	require_once('../includes/changeday.php');
	
//Cache des autorisations des modules
$cache->load_file('modules'); 
?>