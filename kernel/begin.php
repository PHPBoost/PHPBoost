<?php
/*##################################################
 *                               begin.php
 *                            -------------------
 *   begin                : Februar 08, 2006
 *   copyright            : (C) 2005 Viarre Régis
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


defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/framework/core/environment.class.php';
Environment::load_imports();

/* DEPRECATED VARS */
$Errorh = new Errors();
$Cache = new Cache();
/* END DEPRECATED */

Environment::get_instance()->init();

$Sql = Environment::get_instance()->get_db_connection();
$Bread_crumb = Environment::get_instance()->get_breadcrumb();
$Session = Environment::get_instance()->get_session();

//Activation de la bufférisation de sortie
if ($CONFIG['ob_gzhandler'] == 1)
{
	ob_start('ob_gzhandler'); //Activation de la compression de données
}
else
{
	ob_start();
}

$User = new User($Session->data);


//Définition de la constante de transmission des infos de session.
if ($Session->session_mod)
{
	define('SID', 'sid=' . $User->get_attribute('session_id') . '&amp;suid=' . $User->get_attribute('user_id'));
	define('SID2', 'sid=' . $User->get_attribute('session_id') . '&suid=' . $User->get_attribute('user_id'));
}
else
{
	define('SID', '');
	define('SID2', '');
}

//Si le thème n'existe pas on prend le suivant présent sur le serveur/
$user_theme = $User->get_attribute('user_theme');
if ($CONFIG_USER['force_theme'] == 1 || !isset($THEME_CONFIG[$user_theme]['secure']) || !$User->check_level($THEME_CONFIG[$user_theme]['secure'])) //Thème autorisé pour le membre?
{
	$user_theme = $CONFIG['theme'];
}
$User->set_user_theme(find_require_dir(PATH_TO_ROOT . '/templates/', $user_theme));

//Si le dossier de langue n'existe pas on prend le suivant exisant.
$user_lang = $User->get_attribute('user_lang');
if (!isset($LANGS_CONFIG[$user_lang]['secure']) || !$User->check_level($LANGS_CONFIG[$user_lang]['secure'])) //Langue autorisée pour le membre?
{
	$user_lang = $CONFIG['lang'];
}
$User->set_user_lang(find_require_dir(PATH_TO_ROOT . '/lang/', $user_lang));

$LANG = array();
require_once(PATH_TO_ROOT . '/lang/' . get_ulang() . '/main.php'); //!\\ Langues //!\\
require_once(PATH_TO_ROOT . '/lang/' . get_ulang() . '/errors.php'); //Inclusion des langues des erreurs.
//Chargement du cache du jour actuel.
$Cache->load('day');
//On vérifie que le jour n'a pas changé => sinon on execute les requêtes.. (simulation d'une tache cron).
if (gmdate_format('j', time(), TIMEZONE_SITE) != $_record_day && !empty($_record_day))
{
	import('io/filesystem/file');
	$lock_file = new File(PATH_TO_ROOT . '/cache/changeday_lock');
	if (!$lock_file->exists())
	{
		$lock_file->write('');
		$lock_file->flush();
	}
	if ($lock_file->lock(false))
	{
		$yesterday_timestamp = time() - 86400;
		if ((int) $Sql->query("
		    SELECT COUNT(*)
            FROM " . DB_TABLE_STATS . "
            WHERE stats_year = '" . gmdate_format('Y', $yesterday_timestamp, TIMEZONE_SYSTEM) . "' AND
                stats_month = '" . gmdate_format('m', $yesterday_timestamp, TIMEZONE_SYSTEM) . "' AND
                stats_day = '" . gmdate_format('d', $yesterday_timestamp, TIMEZONE_SYSTEM) . "'", __LINE__, __FILE__) == 0
		)
		{
			//Inscription du nouveau jour dans le fichier en cache.
			$Cache->generate_file('day');

			require_once(PATH_TO_ROOT . '/kernel/changeday.php');
			change_day();
		}
	}
	$lock_file->close();
}

//Autorisation sur le module chargé
define('MODULE_NAME', get_module_name());
if (isset($MODULES[MODULE_NAME]) )
{
	if ($MODULES[MODULE_NAME]['activ'] == 0 )
	{
		$Errorh->handler('e_unactivated_module', E_USER_REDIRECT);
	}
	else if(!$User->check_auth($MODULES[MODULE_NAME]['auth'], ACCESS_MODULE)) //Accès non autorisé !
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
	}
}
elseif (!in_array(MODULE_NAME, array('member', 'admin', 'kernel', ''))) //Empêche l'exécution d'un module non installé.
{
	$array_info_module = load_ini_file(PATH_TO_ROOT . '/' . MODULE_NAME . '/lang/', get_ulang());
	if (!empty($array_info_module['name'])) //Module présent, et non installé.
	{
		$Errorh->handler('e_uninstalled_module', E_USER_REDIRECT);
	}
}

// Verify that the user really wanted to do this POST (only for the registered ones)
if ($User->check_level(MEMBER_LEVEL))
{
	$Session->csrf_post_protect();
}

$Template = new DeprecatedTemplate(); // This has to be done after the environment initialization

?>