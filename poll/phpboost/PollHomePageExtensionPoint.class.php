<?php
/*##################################################
 *                     PollHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 06, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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
			$edit = '<a href="' . PATH_TO_ROOT . '/poll/admin_poll.php" title="' . LangLoader::get_message('edit', 'common') . '" class="fa fa-edit"></a>';
	
		$tpl->put_all(array(
			'C_POLL_MAIN' => true,
			'EDIT' => $edit,
			'U_ARCHIVE' => $show_archives,
			'L_POLL' => $LANG['poll'],
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
				'QUESTION' => stripslashes($row['question'])
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