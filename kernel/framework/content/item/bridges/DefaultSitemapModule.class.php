<?php
/**
 * @package     Content
 * @subpackage  Item\bridges
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 01 27
 * @since       PHPBoost 6.0 - 2020 01 02
*/

class DefaultSitemapModule implements SitemapExtensionPoint
{
	/**
	 * @var string the module identifier
	 */
	protected $module_id;

	public function __construct($module_id)
	{
		$this->module_id = $module_id;
	}

	public function get_public_sitemap()
	{
		return $this->get_module_map(Sitemap::AUTH_PUBLIC);
	}

	public function get_user_sitemap()
	{
		return $this->get_module_map(Sitemap::AUTH_USER);
	}

	protected function get_module_map($auth_mode)
	{
		$module = ModulesManager::get_module($this->module_id);

		$link = new SitemapLink($module->get_configuration()->get_name(), new Url('/' . $module->get_id() . '/'), Sitemap::FREQ_DAILY, Sitemap::PRIORITY_MAX);
		$module_map = new ModuleMap($link, $module->get_id());

		return $module_map;
	}
}
?>
