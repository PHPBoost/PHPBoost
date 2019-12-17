<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 11 26
 * @since       PHPBoost 1.2 - 2005 06 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$template = new FileTemplate('admin/admin_extend.tpl');
$template->add_lang(LangLoader::get('admin-links-common'));

//Listing des modules disponibles
foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $module)
{
	$configuration = $module->get_configuration();
	$admin_home_page = $configuration->get_admin_main_page();
	if (!empty($admin_home_page))
	{
		$template->assign_block_vars('modules_extend', array(
			'NAME' => $configuration->get_name(),
			'IMG' => PATH_TO_ROOT .'/' . $module->get_id() . '/' . $module->get_id() . '.png',
			'U_ADMIN_MODULE' => PATH_TO_ROOT .'/' . $module->get_id() . '/' . $admin_home_page
		));
	}
}

$template->display();

require_once('../admin/admin_footer.php');
?>
