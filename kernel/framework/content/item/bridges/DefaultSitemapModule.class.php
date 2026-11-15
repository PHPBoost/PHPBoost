<?php
/**
 * @package     Content
 * @subpackage  Item\bridges
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 6.0 - 2020 01 02
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultSitemapModule implements SitemapExtensionPoint
{
	protected string $module_id = '';

	public function __construct(string $module_id)
	{
		$this->module_id = $module_id;
	}

	public function get_public_sitemap(): mixed
	{
		return $this->get_module_map(Sitemap::AUTH_PUBLIC);
	}

	public function get_user_sitemap(): mixed
	{
		return $this->get_module_map(Sitemap::AUTH_USER);
	}

	protected function get_module_map(mixed $auth_mode): ModuleMap
	{
		$module = ModulesManager::get_module($this->module_id);

		$link = new SitemapLink($module->get_configuration()->get_name(), new Url('/' . $module->get_id() . '/'), Sitemap::FREQ_DAILY, Sitemap::PRIORITY_MAX);
		$module_map = new ModuleMap($link, $module->get_id());

		return $module_map;
	}
}
?>
