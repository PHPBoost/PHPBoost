<?php
/**
 * This class contains the cache data of the groups which group users having common criteria.
 * @package     PHPBoost
 * @subpackage  Cache
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 20
 * @since       PHPBoost 3.0 - 2009 09 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
				'name' => stripslashes($row['name']),
				'img' => $row['img'],
				'color' => $row['color'],
				'auth' => TextHelper::unserialize(stripslashes($row['auth'])),
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
		return CacheManager::load(__CLASS__, 'kernel', 'user_groups');
	}

	/**
	 * Invalidates the current groups cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'user_groups');
	}
}
?>
