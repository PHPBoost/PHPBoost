<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class LobbyExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('lobby');
	}


	public function home_page(): LobbyHomePageExtensionPoint
	{
		return new LobbyHomePageExtensionPoint();
	}

	public function tree_links(): LobbyTreeLinks
	{
		return new LobbyTreeLinks();
	}

	/**
	 * lobby sits at the site root: DispatcherUrlMapping resolves '/lobby/index.php'
	 * without a /modules/ prefix because is_dir(PATH_TO_ROOT . '/lobby') is true.
	 */
	public function url_mappings(): UrlMappings
	{
		return new UrlMappings([new DispatcherUrlMapping('/lobby/index.php')]);
	}
}
?>
