<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 11 26
*/

class CustomizationTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'customization');
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink($lang['interface'], AdminCustomizeUrlBuilder::customize_interface()));
		$tree->add_link(new AdminModuleLink($lang['favicon'], AdminCustomizeUrlBuilder::customize_favicon()));
		$tree->add_link(new AdminModuleLink($lang['css-files'], AdminCustomizeUrlBuilder::editor_css_file()));
		$tree->add_link(new AdminModuleLink($lang['tpl-files'], AdminCustomizeUrlBuilder::editor_tpl_file()));

		return $tree;
	}
}
?>
