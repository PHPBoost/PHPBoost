<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 3.0 - 2013 12 03
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class WikiTreeLinks extends DefaultTreeLinks
{
	protected function get_module_additional_actions_tree_links(&$tree)
	{
		$module_id = 'wiki';
		$current_user = AppContext::get_current_user()->get_id();
        $config = WikiConfig::load();

		$tree->add_link(new ModuleLink(LangLoader::get_message('contribution.members.list', 'contribution-lang'), WikiUrlBuilder::display_member_items(), WikiAuthorizationsService::check_authorizations()->read()));
		$tree->add_link(new ModuleLink(LangLoader::get_message('wiki.my.items', 'common', $module_id), WikiUrlBuilder::display_member_items($current_user), WikiAuthorizationsService::check_authorizations()->write() || WikiAuthorizationsService::check_authorizations()->contribution() || WikiAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link(new ModuleLink(LangLoader::get_message('wiki.my.tracked', 'common', $module_id), WikiUrlBuilder::tracked_member_items($current_user), WikiAuthorizationsService::check_authorizations()->write() || WikiAuthorizationsService::check_authorizations()->contribution() || WikiAuthorizationsService::check_authorizations()->moderation()));

        if ($config->get_homepage() !== WikiConfig::OVERVIEW) {
            $tree->add_link(new ModuleLink(LangLoader::get_message('wiki.overview', 'common', $module_id), WikiUrlBuilder::overview()));
        }

        if ($config->get_homepage() !== WikiConfig::EXPLORER) {
            $tree->add_link(new ModuleLink(LangLoader::get_message('wiki.explorer', 'common', $module_id), WikiUrlBuilder::explorer()));
        }
	}
}
?>
