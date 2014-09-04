<?php
/*##################################################
 *                            AdminSessionData.class.php
 *                            -------------------
 *   begin                : September 12, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class AdminSessionData extends SessionData
{
	public function __construct()
	{
		$this->user_id = 1;
		$this->session_id = '0123456789';
		$this->token = '42';
		$this->expiry = time() + SessionsConfig::load()->get_session_duration();
		$this->ip = '0000:0000:0000:0000:0000:0000:0000:0001';
			$user_accounts_config = UserAccountsConfig::load();
		$this->cached_data = array(
			'level' => User::ADMIN_LEVEL,
			'login' => 'Admin',
			'display_name' => 'Admin',
		);
		$this->data = array();
	}
}
?>