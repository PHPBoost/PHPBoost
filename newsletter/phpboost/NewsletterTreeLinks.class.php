<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 07 20
 * @since       PHPBoost 4.0 - 2013 11 24
 * @contributor xela <xela@phpboost.com>
*/

class NewsletterTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'newsletter');
		$tree = new ModuleTreeLinks();

		$manage_newsletter_link = new ModuleLink($lang['newsletter.streams.manage'], NewsletterUrlBuilder::manage_streams(), NewsletterAuthorizationsService::default_authorizations()->manage_streams());
		$manage_newsletter_link->add_sub_link(new ModuleLink($lang['newsletter.streams.manage'], NewsletterUrlBuilder::manage_streams(), NewsletterAuthorizationsService::default_authorizations()->manage_streams()));
		$manage_newsletter_link->add_sub_link(new ModuleLink($lang['stream.add'], NewsletterUrlBuilder::add_stream(), NewsletterAuthorizationsService::default_authorizations()->manage_streams()));
		$tree->add_link($manage_newsletter_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), NewsletterUrlBuilder::configuration()));

		$tree->add_link(new ModuleLink($lang['newsletter-add'], NewsletterUrlBuilder::add_newsletter(), NewsletterAuthorizationsService::default_authorizations()->create_newsletters()));
		$tree->add_link(new ModuleLink($lang['newsletter.archives'], NewsletterUrlBuilder::archives(), NewsletterAuthorizationsService::default_authorizations()->read_archives()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('newsletter')->get_configuration()->get_documentation(), NewsletterAuthorizationsService::default_authorizations()->create_newsletters()));

		return $tree;
	}
}
?>
