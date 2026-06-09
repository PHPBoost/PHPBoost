<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 2.0 - 2008 02 24
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PagesExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), PagesHomeController::get_view($this->get_id()));
	}

    public function lobby(): array
	{
		return [
			new DefaultItemsLobbyProvider('pages'),
			new DefaultCategoryLobbyProvider('pages')
		];
	}

	/**
	 * Registers two URL mapping rules in the generated .htaccess:
	 *
	 * 1. Standard mapping (normal priority) — keeps /pages/ prefix for all
	 *    administration URLs: admin/config, categories, manage, add, pending,
	 *    member, reorder, edit, delete …
	 *    RewriteRule ^pages/([\w/_-]*)$ /modules/pages/index.php?url=/$1
	 *
	 * 2. Root mapping (low priority, placed last) — exposes frontend pages
	 *    directly at the site root without the /pages/ prefix.
	 *    RewriteRule ^([\w/_-]*)$ /modules/pages/index.php?url=/$1
	 *    This rule only fires when no other rule has already matched (e.g. an
	 *    existing file, another module, or rule 1 above).
	 *
	 * After modifying this file, regenerate the .htaccess from the PHPBoost
	 * administration panel: Administration → Cache → Regenerate .htaccess.
	 */
	public function url_mappings()
	{
		return new UrlMappings([
			// Standard /pages/ dispatcher — catches admin & management URLs.
			new DispatcherUrlMapping('/pages/index.php'),

			// Root dispatcher (low priority) — catches frontend URLs at site root.
			new DispatcherUrlMapping('/pages/index.php', '([\\w/_-]*)$', 'root'),
		]);
	}
}
?>
