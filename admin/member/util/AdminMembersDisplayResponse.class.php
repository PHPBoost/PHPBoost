<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 23
 * @since       PHPBoost 3.0 - 2011 04 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminMembersDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page, $page = 1)
	{
		parent::__construct($view);

		$lang = LangLoader::get('admin-user-common');
		$this->set_title($lang['members.members-management']);

		$this->add_link($lang['members.members-management'], AdminMembersUrlBuilder::management());
		$this->add_link($lang['members.add-member'], AdminMembersUrlBuilder::add());
		$this->add_link($lang['members.config-members'], AdminMembersUrlBuilder::configuration());
		$this->add_link($lang['members.members-punishment'], UserUrlBuilder::moderation_panel());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, '', $page);
	}
}
?>
