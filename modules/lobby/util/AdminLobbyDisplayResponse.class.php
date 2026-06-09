<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class AdminLobbyDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, string $page_title)
	{
		parent::__construct($view);

		$lang    = LangLoader::get_all_langs('lobby');
		$config  = LobbyConfig::load();
		$features = ModulesManager::get_activated_feature_modules('lobby');

		$home_modules    = array_map(fn($m) => $m->get_id(), $features);
		$config_ids      = array_column($config->get_modules(), 'module_id');
		$new_modules     = array_diff($home_modules, $config_ids);

		if (!empty($new_modules))
		{
			$this->add_link($lang['lobby.add.modules'], LobbyUrlBuilder::add_modules());
		}

		$this->add_link($lang['lobby.modules.position'], LobbyUrlBuilder::positions());
		$this->add_link($lang['form.configuration'], LobbyUrlBuilder::configuration());

		if (ModulesManager::get_module('lobby')->get_configuration()->get_documentation())
		{
			$this->add_link($lang['form.documentation'], ModulesManager::get_module('lobby')->get_configuration()->get_documentation());
		}

		$this->get_graphical_environment()->set_page_title($page_title);
	}
}
?>
