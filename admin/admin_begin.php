<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 28
 * @since       PHPBoost 1.2 - 2005 06 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

if (!defined('PATH_TO_ROOT'))
	define('PATH_TO_ROOT', '..');

require_once(PATH_TO_ROOT . '/kernel/begin.php');

require_once(PATH_TO_ROOT . '/lang/' . AppContext::get_current_user()->get_locale() . '/admin.php');

if (!AppContext::get_current_user()->is_admin())
{
	DispatchManager::redirect(new UserLoginController(UserLoginController::ADMIN_LOGIN, TextHelper::substr(REWRITED_SCRIPT, TextHelper::strlen(GeneralConfig::load()->get_site_path()))));
}
?>
