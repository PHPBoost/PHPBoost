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

class AdminCommentsDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs();
		$this->set_title($lang['comment.comments']);

		$this->add_link($lang['comment.configuration'], DispatchManager::get_url('/admin/content/', '/comments/config/'));
		$this->add_link($lang['comment.management'], UserUrlBuilder::comments());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
