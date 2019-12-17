<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 11 07
 * @since       PHPBoost 3.0 - 2011 04 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminCommentsDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('admin-contents-common');
		$this->set_title($lang['comments']);

		$this->add_link($lang['comments.config'], DispatchManager::get_url('/admin/content/', '/comments/config/'));
		$this->add_link($lang['comments.management'], UserUrlBuilder::comments());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
