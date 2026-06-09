<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class LobbyHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		$config = LobbyConfig::load();

		$columns_disabled = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
		$columns_disabled->set_disable_left_columns($config->get_left_columns());
		$columns_disabled->set_disable_right_columns($config->get_right_columns());
		$columns_disabled->set_disable_top_central($config->get_top_central());
		$columns_disabled->set_disable_bottom_central($config->get_bottom_central());
		$columns_disabled->set_disable_top_footer($config->get_top_footer());

		return new DefaultHomePage($config->get_module_title(), LobbyHomeController::get_view());
	}
}
?>
