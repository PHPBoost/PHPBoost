<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2024 03 04
*/
#################################################*/

class OnlineModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('online');

		self::$delete_old_files_list = array(
			'/phpboost/OnlineHomePageExtensionPoint.class.php',
			'/util/AdminOnlineDisplayResponse.class.php'
		);
	}

	public function execute()
	{
		parent::execute();
		if (ModulesManager::is_module_installed('online'))
		{
			$menu_id = 0;
            try {
                $menu_id = $this->querier->get_column_value(DB_TABLE_MENUS, 'id', 'WHERE title = "online/OnlineModuleMiniMenu"');
            } catch (RowNotFoundException $e) {}

            if ($menu_id)
            {
                $menu = MenuService::load($menu_id);
                MenuService::delete($menu);
                MenuService::generate_cache();
            }
		}
	}
}
?>
