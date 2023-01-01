<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 02
 * @since       PHPBoost 3.0 - 2011 04 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminMembersDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page, $page = 1)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs();
		$this->set_title($lang['user.members.management']);

		$this->add_link($lang['user.members.management'], AdminMembersUrlBuilder::management());
		$this->add_link($lang['user.add.member'], AdminMembersUrlBuilder::add());
		$this->add_link($lang['user.members.config'], AdminMembersUrlBuilder::configuration());
		$this->add_link($lang['user.members.punishment'], UserUrlBuilder::moderation_panel());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, '', $page);
	}
}
?>
