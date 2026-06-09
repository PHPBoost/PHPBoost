<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class LobbyTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get_all_langs('lobby');
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink($lang['lobby.modules.position'], LobbyUrlBuilder::positions()));
		$tree->add_link(new AdminModuleLink($lang['form.configuration'], LobbyUrlBuilder::configuration()));

		if (ModulesManager::get_module('lobby')->get_configuration()->get_documentation())
		{
			$tree->add_link(new AdminModuleLink($lang['form.documentation'], ModulesManager::get_module('lobby')->get_configuration()->get_documentation()));
		}

		return $tree;
	}
}
?>
