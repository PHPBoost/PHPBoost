<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 08 23
 * @since       PHPBoost 1.6 - 2007 10 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

if (defined('PHPBOOST') !== true)
    exit;

load_module_lang('forum'); //Chargement de la langue du module.

$config = ForumConfig::load();
require_once(PATH_TO_ROOT . '/forum/forum_defines.php');

//Supprime les menus de gauche et/ou droite suivant la configuration du module.
$columns_disabled = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
if ($config->is_left_column_disabled())
	$columns_disabled->set_disable_left_columns(true);
if ($config->is_right_column_disabled())
	$columns_disabled->set_disable_right_columns(true);

//Fonction du forum.
require_once(PATH_TO_ROOT . '/forum/forum_functions.php');

?>
