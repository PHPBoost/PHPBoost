<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 02 24
 * @since       PHPBoost 3.0 - 2009 12 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class AdminSitemapResponse extends AdminMenuDisplayResponse
{
	public function __construct($view)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'sitemap');
		$this->set_title($lang['sitemap']);

		$this->add_link($lang['general_config'], SitemapUrlBuilder::get_general_config());
		$this->add_link($lang['generate_xml_file'], SitemapUrlBuilder::get_xml_file_generation());
		$this->add_link(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('sitemap')->get_configuration()->get_documentation());
	}
}
?>
