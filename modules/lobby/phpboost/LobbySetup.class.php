<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class LobbySetup extends DefaultModuleSetup
{
	public function install(): void
	{
		$this->auto_add_compatible_modules();
	}

	public function uninstall(): void
	{
		ConfigManager::delete('lobby', 'config');
	}

	/**
	 * Scans all already-installed modules with the 'lobby' feature and registers
	 * their lobby entries into the config (hidden by default).
	 *
	 * Uses require_once + class_exists directly — no classlist dependency.
	 * During a fresh CMS install, compatible modules installed before lobby are
	 * picked up automatically. Modules installed after lobby must be added via
	 * AdminLobbyAddModulesController.
	 */
	private function auto_add_compatible_modules(): void
	{
		$compatible_modules = ModulesManager::get_activated_feature_modules('lobby');

		if (empty($compatible_modules))
		{
			return;
		}

		$modules_list = LobbyConfig::load()->get_modules();

		foreach ($compatible_modules as $module)
		{
            $phpboost_id  = $module->get_id();
            $provider_class = TextHelper::ucfirst($phpboost_id) . 'ExtensionPointProvider';
            $file = new File(ModulesManager::get_module_path($phpboost_id) . '/phpboost/' . $provider_class . '.class.php');

            if ($file->exists())
                $provider = new $provider_class();
            else
                $provider = new ItemsModuleExtensionPointProvider($phpboost_id);

            $lobbyProviders = $provider->lobby();

			foreach ($lobbyProviders as $provider)
			{
				if (!($provider instanceof LobbyProvider))
				{
					continue;
				}

				$module = new LobbyModule();
				$module->set_module_id($provider->get_module_id());
				$module->set_module_name($provider->get_module_name());
				$module->set_phpboost_module_id($provider->get_phpboost_module_id());
				$module->set_has_category($provider->is_category_view());
				$module->hide();

				$modules_list[] = $module->get_properties();
			}
		}

		LobbyModulesList::save($modules_list);
		LobbyConfig::save();

        ClassLoader::generate_classlist(true);
	}
}
?>
