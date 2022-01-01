<?php
/**
 * @package     PHPBoost
 * @subpackage  Cache
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 09 02
 * @since       PHPBoost 4.1 - 2014 08 10
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
		$querier = PersistenceContext::get_querier();

		$nbr_members = $querier->count(DB_TABLE_MEMBER);
		$last_member = $querier->select_single_row(DB_TABLE_MEMBER, array('user_id', 'display_name', 'level', 'user_groups'), 'ORDER BY registration_date DESC LIMIT 1 OFFSET 0');

		$this->stats = 	array(
			'nbr_members' => $nbr_members,
			'last_member_login' => $last_member['display_name'],
			'last_member_id' => $last_member['user_id'],
			'last_member_level' => $last_member['level'],
			'last_member_groups' => $last_member['user_groups']
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
?>
