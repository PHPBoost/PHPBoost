<?php
/*##################################################
 *                     OnlineHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : January 29, 2012
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

class OnlineHomePageExtensionPoint implements HomePageExtensionPoint
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
		global $ONLINE_LANG;
		
		return $ONLINE_LANG['online'];
	}
	
	private function get_view()
	{
		global $CONFIG_ONLINE, $LANG, $ONLINE_LANG;
		
		$this->lang = LangLoader::get('online_common', 'online');
		$tpl = new FileTemplate('online/OnlineHomeController.tpl');
		
		//Membre connectés..
		$nbr_members_connected = PersistenceContext::get_sql()->num_rows("SELECT *
		FROM " . DB_TABLE_SESSIONS . "
		WHERE level <> -1 AND session_time > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "'"
		);
		
		$current_page = $request->get_int('page', 1);
		$nbr_pages =  ceil($nbr_members_connected / $this->nbr_members_per_page);
		$pagination = new Pagination($nbr_pages, $current_page);
		
		$pagination->set_url_sprintf_pattern(DispatchManager::get_url('/online', '')->absolute());
		
		$tpl->put_all(array(
			'L_LOGIN' => $LANG['pseudo'],
			'PAGINATION' => $pagination->export()->render()
		));

		$limit_page = $current_page > 0 ? $current_page : 1;
		$limit_page = (($limit_page - 1) * $this->nbr_members_per_page);
		
		$result = PersistenceContext::get_querier()->select("SELECT s.user_id, s.level, s.session_time, s.session_script, s.session_script_get, s.session_script_title, m.login
		FROM " . DB_TABLE_SESSIONS . " s
		JOIN " . DB_TABLE_MEMBER . " m ON (m.user_id = s.user_id)
		WHERE s.session_time > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "'
	"/*	ORDER BY " . OnlineConfig::load()->get_display_order() . " */ . "
		LIMIT ". $this->nbr_members_per_page ." OFFSET :start_limit",
			array(
				'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
		while ($row = $result->fetch())
		{
			switch ($row['level']) //Coloration du membre suivant son level d'autorisation. 
			{ 		
				case 0:
				$status = 'member';
				break;
				
				case 1: 
				$status = 'modo';
				break;
				
				case 2: 
				$status = 'admin';
				break;
				
				default:
				$status = 'member';
			} 
			
			$row['session_script_get'] = !empty($row['session_script_get']) ? '?' . $row['session_script_get'] : '';
			
			$tpl->assign_block_vars('users', array(
				'USER' => !empty($row['login']) ? '<a href="' . UserUrlBuilder::profile($row['user_id'])->absolute() . '" class="' . $status . '">' . $row['login'] . '</a>': $LANG['guest'],
				'LOCATION' => '<a href="' . HOST . DIR . $row['session_script'] . $row['session_script_get'] . '">' . stripslashes($row['session_script_title']) . '</a>',
				'LAST_UPDATE' => gmdate_format('date_format_long', $row['session_time'])
			));
		}
		return $tpl;
	}
}
?>