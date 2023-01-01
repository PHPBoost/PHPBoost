<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 09 20
 * @since       PHPBoost 2.0 - 2008 03 26
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

if (defined('PHPBOOST') !== true)
	exit;

//Historique.
define('H_DELETE_MSG', 'delete_msg'); //Suppression d'un message.
define('H_DELETE_TOPIC', 'delete_topic'); //Suppression d'un sujet.
define('H_LOCK_TOPIC', 'lock_topic'); //Verrouillage d'un sujet.
define('H_UNLOCK_TOPIC', 'unlock_topic'); //Déverrouillage d'un sujet.
define('H_MOVE_TOPIC', 'move_topic'); //Déplacement d'un sujet.
define('H_CUT_TOPIC', 'cut_topic'); //Scindement d'un sujet.
define('H_SET_WARNING_USER', 'set_warning_user'); //Modification pourcentage avertissement.
define('H_BAN_USER', 'ban_user'); //Modification pourcentage avertissement.
define('H_EDIT_MSG', 'edit_msg'); //Edition message d'un membre.
define('H_EDIT_TOPIC', 'edit_topic'); //Edition sujet d'un membre.
define('H_SOLVE_ALERT', 'solve_alert'); //Résolution d'une alerte.
define('H_WAIT_ALERT', 'wait_alert'); //Mise en attente d'une alerte.
define('H_DEL_ALERT', 'del_alert'); //Suppression d'une alerte.
define('H_READONLY_USER', 'readonly_user'); //Modification lecture seule d'un membre.

?>
