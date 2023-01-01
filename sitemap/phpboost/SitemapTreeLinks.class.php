<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 23
 * @since       PHPBoost 4.0 - 2013 11 23
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SitemapTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('form.configuration', 'form-lang'), SitemapUrlBuilder::get_general_config()));

		return $tree;
	}
}
?>
