<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 10
 * @since       PHPBoost 4.1 - 2015 02 25
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminForumDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'forum');
		$this->set_title($lang['forum.module.title']);

		$this->add_link($lang['forum.ranks.manager'], ForumUrlBuilder::manage_ranks());
		$this->add_link($lang['forum.rank.add'], ForumUrlBuilder::add_rank());
		$this->add_link(LangLoader::get_message('configuration', 'admin-common'), ForumUrlBuilder::configuration());
		$this->add_link(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('forum')->get_configuration()->get_documentation());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, $lang['forum.module.title']);
	}
}
?>
