<?php
/*##################################################
 *                     PollHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 06, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
	}
	
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
		
		global $User, $Cache, $Bread_crumb, $POLL_CAT, $POLL_LANG, $LANG, $Session;

		require_once(PATH_TO_ROOT . '/poll/poll_begin.php');

		$tpl = new FileTemplate('poll/poll.tpl');
        
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);

		$show_archives = $this->sql_querier->query("SELECT COUNT(*) as compt FROM " . PREFIX . "poll WHERE archive = 1 AND visible = 1 AND start <= '" . $now->get_timestamp() . "' AND (end >= '" . $now->get_timestamp() . "' OR end = 0)", __LINE__, __FILE__);
		$show_archives = !empty($show_archives) ? '<a href="poll' . url('.php?archives=1', '.php?archives=1') . '">' . $LANG['archives'] . '</a>' : '';
	
		$edit = '';	
		if ($User->check_level(User::ADMIN_LEVEL))
			$edit = '<a href="' . PATH_TO_ROOT . '/poll/admin_poll.php" title="' . $LANG['edit'] . '"><img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" class="valign_middle" /></a>';
	
		$tpl->put_all(array(
			'C_POLL_MAIN' => true,
			'EDIT' => $edit,
			'U_ARCHIVE' => $show_archives,
			'L_POLL' => $LANG['poll'],
			'L_POLL_MAIN' => $LANG['poll_main']		
		));
	
		$result = $this->sql_querier->query_while("SELECT id, question 
		FROM " . PREFIX . "poll 
		WHERE archive = 0 AND visible = 1 AND start <= '" . $now->get_timestamp() . "' AND (end >= '" . $now->get_timestamp() . "' OR end = 0)
		ORDER BY id DESC", __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$tpl->assign_block_vars('list', array(
				'U_POLL_ID' => url('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
				'QUESTION' => $row['question']
			));
		}
		$this->sql_querier->query_close($result);	

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