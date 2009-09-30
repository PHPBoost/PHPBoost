<?php
/*##################################################
 *                      groups_cache_data.class.php
 *                            -------------------
 *   begin                : September 29, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

import('io/cache/cache_data');

class GroupsCacheData implements CacheData
{
	private static $default_groups_value = array();
	
	private $groups = array();

	public function synchronize()
	{
		$this->groups = array();
		$db_connection = Environment::get_instance()->get_db_connection();
		
		$result = $db_connection->query_while("SELECT id, name, img, color, auth
			FROM " . PREFIX . "group
			ORDER BY id", __LINE__, __FILE__);
		
		while ($row = $db_connection->fetch_assoc($result))
		{
			$this->groups[$row['id']] = array(
				'name' => $row['name'],
				'img' => $row['img'],
				'color' => $row['color'],
				'auth' => unserialize($row['auth'])
			);
		}
		
		$db_connection->query_close($result);
	}

	public function get_groups()
	{
			return $this->groups;
	}
	
	public function get_group($group_id)
	{
		return $this->groups[$idgroup];
	}
	
	public function set_groups($groups_list)
	{
		$this->groups = $groups_list;
	}
	
	/**
	 * @return GroupsCacheData
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'groups');
	}
	
	/**
	 * @return GroupsCacheData
	 */
	public static function invalidate()
	{
		return CacheManager::invalidate('kernel', 'groups');
	}
}