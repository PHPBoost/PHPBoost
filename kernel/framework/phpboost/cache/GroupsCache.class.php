<?php
/*##################################################
 *                      	 GroupsCache.class.php
 *                            -------------------
 *   begin                : September 29, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * This class contains the cache data of the groups which group users having common criteria.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class GroupsCache implements CacheData
{
	private static $default_groups_value = array();
	
	private $groups = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->groups = array();
		$columns = array('id', 'name', 'img', 'color', 'auth', 'members');
		$result = PersistenceContext::get_querier()->select_rows(DB_TABLE_GROUP, $columns, 'ORDER BY id');
		while ($row = $result->fetch())
		{
			$this->groups[$row['id']] = array(
				'name' => $row['name'],
				'img' => $row['img'],
				'color' => $row['color'],
				'auth' => unserialize(stripslashes($row['auth'])),
				'members' => explode('|', $row['members'])
			);
		}
		$result->dispose();
	}

	/**
	 * Returns the list of the groups
	 * @return array id_group => group properties (map)
	 */
	public function get_groups()
	{
		return $this->groups;
	}
	
	public function group_exists($group_id)
	{
		return array_key_exists($group_id, $this->groups);
	}
	
	public function group_name_exists($group_name)
	{
		$exists = false;
		foreach ($this->groups as $group)
		{
			$exists = ($group['name'] == $group_name ? true : false);
		}
		return $exists;
	}
	
	/**
	 * Returns a group
	 * @param $group_id Id of the group
	 * @return string[] A map of the properties of the group
	 */
	public function get_group($group_id)
	{
		return $this->groups[$group_id];
	}
	
	/**
	 * Sets the groups list
	 * @param $groups_list The groups list
	 */
	public function set_groups($groups_list)
	{
		$this->groups = $groups_list;
	}
	
	/**
	 * Loads and returns the groups cached data.
	 * @return GroupsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'groups');
	}
	
	/**
	 * Invalidates the current groups cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'groups');
	}
}
?>