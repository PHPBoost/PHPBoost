<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 06 27
 * @since       PHPBoost 3.0 - 2012 02 06
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class PollHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}

	private function get_title()
	{
		global $LANG;

		load_module_lang('poll');

		return $LANG['poll'];
	}

	private function get_view()
	{
		$this->check_authorizations();

		global $Bread_crumb, $LANG;

		require_once(PATH_TO_ROOT . '/poll/poll_begin.php');

		$tpl = new FileTemplate('poll/poll.tpl');

		$now = new Date();

		$show_archives = PersistenceContext::get_querier()->count(PREFIX . "poll", 'WHERE archive = 1 AND visible = 1 AND start <= :timestamp AND (end >= :timestamp OR end = 0)', array('timestamp' => $now->get_timestamp()));
		$show_archives = !empty($show_archives) ? '<a href="poll' . url('.php?archives=1', '.php?archives=1') . '">' . $LANG['archives'] . '</a>' : '';

		$edit = '';
		if (AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
			$edit = PATH_TO_ROOT . "/poll/admin_poll.php";

		$tpl->put_all(array(
			'C_POLL_MAIN' => true,
			'C_IS_ADMIN'  => AppContext::get_current_user()->check_level(User::ADMIN_LEVEL),
			'U_EDIT'      => $edit,
			'U_ARCHIVE'   => $show_archives,
			'L_POLL'      => $LANG['poll'],
			'L_POLL_MAIN' => $LANG['poll_main']
		));

		$result = PersistenceContext::get_querier()->select("SELECT id, question
		FROM " . PREFIX . "poll
		WHERE archive = 0 AND visible = 1 AND start <= :timestamp AND (end >= :timestamp OR end = 0)
		ORDER BY id DESC", array(
			'timestamp' => $now->get_timestamp()
		));
		while ($row = $result->fetch())
		{
			$tpl->assign_block_vars('list', array(
				'U_POLL_ID' => url('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
				'QUESTION'  => stripslashes($row['question'])
			));
		}
		$result->dispose();

		return $tpl;
	}

	private function check_authorizations()
	{
		if (!PollAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
