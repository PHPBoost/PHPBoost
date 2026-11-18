<?php
/**
 * This class contains the cache data of the groups which group users having common criteria.
 * @package     PHPBoost
 * @subpackage  Cache
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2009 09 29
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      janus57 <janus57@janus57.fr>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GroupsCache implements CacheData
{
	private array $groups = [];

	public function synchronize(): void
    {
		$this->groups = [];
		$columns = ['id', 'name', 'img', 'color', 'auth', 'members'];
		$result = PersistenceContext::get_querier()->select_rows(DB_TABLE_GROUP, $columns, 'ORDER BY id');
		while ($row = $result->fetch())
		{
			$this->groups[$row['id']] = [
				'name' => stripslashes($row['name']),
				'img' => $row['img'],
				'color' => $row['color'],
				'auth' => TextHelper::unserialize(stripslashes($row['auth'])),
				'members' => explode('|', $row['members'])
			];
		}
		$result->dispose();
	}

	/**
	 * Returns the list of the groups
	 * @return array id_group => group properties (map)
	 */
	public function get_groups(): array
	{
		return $this->groups;
	}

	public function group_exists(mixed $group_id): bool
	{
		return array_key_exists($group_id, $this->groups);
	}

	public function group_name_exists(mixed $group_name): mixed
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
	public function get_group(mixed $group_id): mixed
	{
		return $this->groups[$group_id];
	}

	/**
	 * Sets the groups list
	 * @param $groups_list The groups list
	 */
	public function set_groups(mixed $groups_list): void
	{
		$this->groups = $groups_list;
	}

	/**
	 * Loads and returns the groups cached data.
	 * @return GroupsCache The cached data
	 */
	public static function load(): GroupsCache
	{
		return CacheManager::load(self::class, 'kernel', 'user_groups');
	}

	/**
	 * Invalidates the current groups cached data.
	 */
	public static function invalidate(): void
	{
		CacheManager::invalidate('kernel', 'user_groups');
	}
}
?>
