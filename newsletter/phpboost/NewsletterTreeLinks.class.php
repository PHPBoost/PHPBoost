<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 14
 * @since       PHPBoost 4.0 - 2013 11 24
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsletterTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get_all_langs('newsletter');
		$tree = new ModuleTreeLinks();

		$tree->add_link(new ModuleLink($lang['newsletter.streams.management'], NewsletterUrlBuilder::manage_streams(), NewsletterAuthorizationsService::default_authorizations()->manage_streams()));
		$tree->add_link(new ModuleLink($lang['newsletter.stream.add'], NewsletterUrlBuilder::add_stream(), NewsletterAuthorizationsService::default_authorizations()->manage_streams()));

		$tree->add_link(new ModuleLink($lang['newsletter.archives'], NewsletterUrlBuilder::archives(), NewsletterAuthorizationsService::default_authorizations()->read_archives()));
		$tree->add_link(new ModuleLink($lang['newsletter.add.item'], NewsletterUrlBuilder::add_newsletter(), NewsletterAuthorizationsService::default_authorizations()->create_newsletters()));

		$tree->add_link(new AdminModuleLink($lang['form.configuration'], NewsletterUrlBuilder::configuration()));

		if (ModulesManager::get_module('newsletter')->get_configuration()->get_documentation())
			$tree->add_link(new ModuleLink($lang['form.documentation'], ModulesManager::get_module('newsletter')->get_configuration()->get_documentation(), NewsletterAuthorizationsService::default_authorizations()->create_newsletters()));

		return $tree;
	}
}
?>
