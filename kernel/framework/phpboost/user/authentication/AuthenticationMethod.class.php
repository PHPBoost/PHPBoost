<?php
/*##################################################
 *                            AuthenticationMethod.class.php
 *                            -------------------
 *   begin                : November 28, 2010
 *   copyright            : (C) 2010 loic rouchon
 *   email                : horn@phpboost.com
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

/**
 * @author Loic Rouchon <horn@phpboost.com>
 * @desc The AuthenticationMethod interface could be implemented in different ways to enable specifics
 * authentication mecanisms.
 * PHPBoost comes with a PHPBoostAuthenticationMethod which will be performed on the internal member
 * list. But it is possible to implement external authentication mecanism by providing others
 * implementations of this class to support LDAP authentication, OpenID, Facebook connect and more...
 *
 * @package {@package}
 */
abstract class AuthenticationMethod
{
	protected $error_msg;
	
	/**
	 * @desc associate the current authentication method with the given user_id.
	 * @param int $user_id
	 * @throws IllegalArgumentException if the user_id is already associate with an authentication method
	 */
	abstract public function associate($user_id);

	/**
	 * @desc dissociate the current authentication method with the given user_id.
	 * @param int $user_id
	 * @throws IllegalArgumentException if the user_id is already dissociate with an authentication method
	 */
	abstract public function dissociate($user_id);

	/**
	 * @desc Tries to authenticate the user and returns true on success, false otherwise.
	 * @return int $user_id, if authentication has been performed successfully
	 */
	abstract public function authenticate();
	
	public function has_error()
	{
		return !empty($this->error_msg);
	}
	
	public function get_error_msg()
	{
		return $this->error_msg;
	}
	
	protected function check_user_bannishment($user_id)
	{
		$infos = array();
		try {
			$infos = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('warning_percentage', 'delay_banned'), 'WHERE user_id=:user_id', array('user_id' => $user_id));
		} catch (RowNotFoundException $e) {
		}
		
		if (!empty($infos) && $infos['warning_percentage'] == 100)
			$this->error_msg = LangLoader::get_message('e_member_ban_w', 'errors');
		else if (!empty($infos) && $infos['delay_banned'])
		{
			$delay_ban = (time() - $infos['delay_banned']); //Vérification si le membre est banni.
			$delay = ceil((0 - $delay_ban)/60);
			if ($delay > 0)
			{
				$date_lang = LangLoader::get('date-common');
				
				if ($delay < 60)
					$this->error_msg = LangLoader::get_message('e_member_ban', 'errors') . ' ' . $delay . ' ' . (($delay > 1) ? $date_lang['minutes'] : $date_lang['minute']);
				elseif ($delay < 1440)
				{
					$delay_ban = NumberHelper::round($delay/60, 0);
					$this->error_msg = LangLoader::get_message('e_member_ban', 'errors') . ' ' . $delay_ban . ' ' . (($delay_ban > 1) ? $date_lang['hours'] : $date_lang['hour']);
				}
				elseif ($delay < 10080)
				{
					$delay_ban = NumberHelper::round($delay/1440, 0);
					$this->error_msg = LangLoader::get_message('e_member_ban', 'errors') . ' ' . $delay_ban . ' ' . (($delay_ban > 1) ? $date_lang['days'] : $date_lang['day']);
				}
				elseif ($delay < 43200)
				{
					$delay_ban = NumberHelper::round($delay/10080, 0);
					$this->error_msg = LangLoader::get_message('e_member_ban', 'errors') . ' ' . $delay_ban . ' ' . (($delay_ban > 1) ? $date_lang['weeks'] : $date_lang['week']);
				}
				elseif ($delay < 525600)
				{
					$delay_ban = NumberHelper::round($delay/43200, 0);
					$this->error_msg = LangLoader::get_message('e_member_ban', 'errors') . ' ' . $delay_ban . ' ' . (($delay_ban > 1) ? $date_lang['months'] : $date_lang['month']);
				}
				else
				{
					$this->error_msg = LangLoader::get_message('e_member_ban_w', 'errors');
				}
			}
		}
	}

	protected function update_user_last_connection_date($user_id)
	{
		PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('last_connection_date' => time()), 'WHERE user_id=:user_id', array('user_id' => $user_id));
	}
}
?>