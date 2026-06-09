<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class LobbyService
{
	/**
	 * Collects every LobbyProvider instance declared across all activated modules.
	 *
	 * Each module's ExtensionPointProvider exposes a lobby() method that returns
	 * an array of LobbyProvider instances (base view, optional category sub-view,
	 * optional special sub-views such as pinned_news).
	 *
	 * Flow:
	 *   ExtensionPointProviderService::get_providers('lobby')
	 *   => ExtensionPointProvider[] (modules that have a lobby() method)
	 *   => foreach: ep->get_extension_point('lobby') calls ep->lobby()
	 *   => LobbyProvider[] (one or more providers per module)
	 *
	 * Results are keyed by module_id so callers can do $providers['download'].
	 *
	 * @return array<string, LobbyProvider>
	 */
	public static function get_all_lobby_providers(): array
	{
		$eps          = AppContext::get_extension_provider_service();
		$ep_providers = $eps->get_providers(LobbyProvider::EXTENSION_POINT);
		$result       = [];

		foreach ($ep_providers as $ep_provider)
		{
			$lobby_providers = $ep_provider->get_extension_point(LobbyProvider::EXTENSION_POINT);

			if (!is_array($lobby_providers))
			{
				continue;
			}

			foreach ($lobby_providers as $lobby_provider)
			{
				if ($lobby_provider instanceof LobbyProvider)
				{
					$result[$lobby_provider->get_module_id()] = $lobby_provider;
				}
			}
		}

		return $result;
	}

	/**
	 * Returns only the LobbyProvider instances whose phpboost module is installed and activated.
	 *
	 * @return array<string, LobbyProvider>
	 */
	public static function get_active_lobby_providers(): array
	{
		$all    = self::get_all_lobby_providers();
		$active = [];

		foreach ($all as $module_id => $provider)
		{
			$phpboost_id = $provider->get_phpboost_module_id();

			if (!ModulesManager::is_module_installed($phpboost_id) || !ModulesManager::is_module_activated($phpboost_id))
			{
				continue;
			}

			$active[$module_id] = $provider;
		}

		return $active;
	}
}
?>
