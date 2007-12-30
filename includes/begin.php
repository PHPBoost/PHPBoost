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

header('Content-type: text/html; charset=iso-8859-1');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date du passé
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // toujours modifié
header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
header('Pragma: no-cache'); 
	
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
$cache = new Cache; //!\\Initialisation  de la class de gestion du cache//!\\

unset($sql_host, $sql_login, $sql_pass); //Destruction des identifiants bdd.

//Chargement ddes fichiers cache, indispensables au noyau.
$CONFIG = array();
$cache->load_file('config'); //Requête des configuration générales, $CONFIG variable globale.
$cache->load_file('groups'); //Cache des groupes.
$cache->load_file('member'); //Chargement de la configuration des membres.
define('DIR', $CONFIG['server_path']);
define('HOST', $CONFIG['server_name']);

$session = new Sessions; //!\\Initialisation  de la class des sessions//!\\

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
	$session->data['user_id'] = -1;
	$session->data['level'] = -1;
	$session->data['user_groups'] = '';
	$session->data['user_editor'] = $CONFIG['editor'];
	$session->data['user_timezone'] = $CONFIG['timezone'];
}
$groups = new Groups($session->data, $_array_groups_auth); //!\\Initialisation  de la class de gestion des groupes//!\\

//Définition de la constante de transmission des infos de session.
if( $session->session_mod )
{
	define('SID', '?sid=' . $session->data['session_id'] . '&amp;suid=' . $session->data['user_id']);
	define('SID2', '?sid=' . $session->data['session_id'] . '&suid=' . $session->data['user_id']);
}
else
{
	define('SID', '');
	define('SID2', '');
}

//Si le thème n'existe pas on prend le suivant présent sur le serveur/
$CONFIG['theme'] = find_require_dir('../templates/', (empty($session->data['user_theme']) || $CONFIG_MEMBER['force_theme'] == 1) ? $CONFIG['theme'] : $session->data['user_theme']);

//Si le dossier de langue n'existe pas on prend le suivant exisant.
$CONFIG['lang'] = find_require_dir('../lang/', (empty($session->data['user_lang']) ? $CONFIG['lang'] : $session->data['user_lang']));
$LANG = array();
include_once('../lang/' . $CONFIG['lang'] . '/main.php'); //!\\ Langues //!\\
include_once('../lang/' . $CONFIG['lang'] . '/errors.php'); //Inclusion des langues des erreurs.

//Chargement du cache du jour actuel.
$cache->load_file('day');
if( gmdate_format('j', time(), TIMEZONE_SYSTEM) != $_record_day && !empty($_record_day) ) //On vérifie que le jour n'a pas changé => sinon on execute les requêtes.. (simulation d'une tache cron).
	require_once('../includes/changeday.php');

include_once('../includes/connect.php'); //Inclusion du gestionnaire de connexion.
	
//Cache des autorisations des modules
$cache->load_file('modules'); 

?>