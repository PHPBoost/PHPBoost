<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 15
 * @since       PHPBoost 1.6 - 2007 10 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

if (defined('PHPBOOST') !== true)
    exit;

$lang = LangLoader::get_all_langs('forum');

$config = ForumConfig::load();
require_once(PATH_TO_ROOT . '/forum/forum_defines.php');

// Disable/enable left and right columns.
$columns_disabled = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
if ($config->is_left_column_disabled())
	$columns_disabled->set_disable_left_columns(true);
if ($config->is_right_column_disabled())
	$columns_disabled->set_disable_right_columns(true);

// Forum features.
require_once(PATH_TO_ROOT . '/forum/forum_functions.php');

?>
