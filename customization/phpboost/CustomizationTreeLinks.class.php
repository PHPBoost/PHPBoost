<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 13
 * @since       PHPBoost 4.0 - 2013 11 26
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CustomizationTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get_all_langs('customization');
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink($lang['customization.interface'], AdminCustomizeUrlBuilder::customize_interface()));
		$tree->add_link(new AdminModuleLink($lang['customization.favicon'], AdminCustomizeUrlBuilder::customize_favicon()));
		$tree->add_link(new AdminModuleLink($lang['customization.css.files'], AdminCustomizeUrlBuilder::editor_css_file()));
		$tree->add_link(new AdminModuleLink($lang['customization.tpl.files'], AdminCustomizeUrlBuilder::editor_tpl_file()));

		return $tree;
	}
}
?>
