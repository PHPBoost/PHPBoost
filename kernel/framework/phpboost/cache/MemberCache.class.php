<?php
/*##################################################
 *                      	 MemberCache.class.php
 *                            -------------------
 *   begin                : August 0, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

/**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class MemberCache implements CacheData
{
	private $member = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->member = array();
		$db_connection = PersistenceContext::get_sql();
			
		$unread = $db_connection->query("SELECT count(*) FROM ".DB_TABLE_EVENTS  . " WHERE current_status = '" . AdministratorAlert::ADMIN_ALERT_STATUS_UNREAD . "' AND contribution_type = '" . ADMINISTRATOR_ALERT_TYPE . "'", __LINE__, __FILE__);
		$all = $db_connection->query("SELECT count(*) FROM " . DB_TABLE_EVENTS . " WHERE contribution_type = '" . ADMINISTRATOR_ALERT_TYPE . "'", __LINE__, __FILE__);

		$this->member = array(
			'unread' => $unread,
			'all' => $all
		);
	}

	public function get_member()
	{
		return $this->member;
	}
	
	public function get_member_properties($identifier)
	{
		if (isset($this->member[$identifier]))
		{
			return $this->member[$identifier];
		}
		return null;
	}
	
	/**
	 * Loads and returns the member cached data.
	 * @return MemberCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'member');
	}
	
	/**
	 * Invalidates the current member cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'member');
	}
}