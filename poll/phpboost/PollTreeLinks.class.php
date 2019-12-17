<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 06 29
 * @since       PHPBoost 4.0 - 2013 11 23
 * @contributor xela <xela@phpboost.com>
*/

class PollTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		global $LANG;
		load_module_lang('poll'); //Chargement de la langue du module.

		$tree = new ModuleTreeLinks();

		$manage_poll_link = new AdminModuleLink($LANG['poll.manage'], new Url('/poll/admin_poll.php'));
		$manage_poll_link->add_sub_link(new AdminModuleLink($LANG['poll.manage'], new Url('/poll/admin_poll.php')));
		$manage_poll_link->add_sub_link(new AdminModuleLink($LANG['poll_add'], new Url('/poll/admin_poll_add.php')));
		$tree->add_link($manage_poll_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), new Url('/poll/admin_poll_config.php')));

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('poll')->get_configuration()->get_documentation()));

		return $tree;
	}
}
?>
