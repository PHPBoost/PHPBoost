<?php
/*##################################################
 *                      	 StatsCache.class.php
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
class StatsCache implements CacheData
{
	private $stats = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->stats = array();
		$db_connection = PersistenceContext::get_sql();
			
		$nbr_members = $db_connection->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE user_aprob = 1", __LINE__, __FILE__);
		$last_member = $db_connection->query_array(DB_TABLE_MEMBER, 'user_id', 'login', "WHERE user_aprob = 1 ORDER BY timestamp DESC " . $db_connection->limit(0, 1), __LINE__, __FILE__);

		$this->stats = 	array(
			'nbr_members' => $nbr_members,
			'last_member_login' => $last_member['login'],
			'last_member_id' => $last_member['user_id']
		);
	}

	public function get_stats()
	{
		return $this->stats;
	}
	
	public function get_stats_properties($identifier)
	{
		if (isset($this->stats[$identifier]))
		{
			return $this->stats[$identifier];
		}
		return null;
	}
	
	/**
	 * Loads and returns the stats cached data.
	 * @return StatsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'stats');
	}
	
	/**
	 * Invalidates the current stats cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'stats');
	}
}