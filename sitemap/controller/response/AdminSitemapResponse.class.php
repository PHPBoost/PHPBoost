<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2009 12 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminSitemapResponse extends AdminMenuDisplayResponse
{
	public function __construct($view)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs('sitemap');
		$this->set_title($lang['sitemap.module.title']);

		$this->add_link($lang['form.configuration'], SitemapUrlBuilder::get_general_config());
		$this->add_link($lang['sitemap.generate.xml'], SitemapUrlBuilder::get_xml_file_generation());
		$this->add_link($lang['form.documentation'], ModulesManager::get_module('sitemap')->get_configuration()->get_documentation());
	}
}
?>
