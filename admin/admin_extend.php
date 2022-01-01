<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 02
 * @since       PHPBoost 1.2 - 2005 06 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');
$lang = LangLoader::get_all_langs();
define('TITLE', $lang['menu.administration'] . ' - ' .  $lang['menu.extended'] );
require_once('../admin/admin_header.php');

$view = new FileTemplate('admin/admin_extend.tpl');
$view->add_lang($lang);

// Listing available modules
foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $module)
{
	$configuration = $module->get_configuration();
	$admin_home_page = $configuration->get_admin_main_page();
	if (!empty($admin_home_page))
	{
		$view->assign_block_vars('modules_extend', array(
			'NAME' => $configuration->get_name(),
			'IMG'  => PATH_TO_ROOT .'/' . $module->get_id() . '/' . $module->get_id() . '.png',

			'U_ADMIN_MODULE' => $admin_home_page
		));
	}
}

$view->display();

require_once('../admin/admin_footer.php');
?>
