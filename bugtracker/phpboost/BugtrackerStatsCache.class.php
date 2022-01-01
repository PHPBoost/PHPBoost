<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 11
 * @since       PHPBoost 4.0 - 2013 07 11
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerStatsCache implements CacheData
{
	private $bugs_number = array();
	private $bugs_number_per_version = array();
	private $top_posters = array();

	public function synchronize()
	{
		$this->bugs_number = array('total' => 0);
		$db_querier = PersistenceContext::get_querier();

		$config = BugtrackerConfig::load();
		$versions = $config->get_versions();

		$result = $db_querier->select("SELECT status, COUNT(*) as bugs_number
		FROM " . BugtrackerSetup::$bugtracker_table . "
		GROUP BY status
		ORDER BY status ASC");

		foreach ($result as $row)
		{
			$this->bugs_number[$row['status']] = $row['bugs_number'];
			$this->bugs_number['total'] += $row['bugs_number'];
		}
		$result->dispose();

		$result = $db_querier->select("SELECT @fixed_in:=fixed_in AS fixed_in,
		COUNT(*) as bugs_number,
		(SELECT COUNT(*) FROM " . BugtrackerSetup::$bugtracker_table . " WHERE fixed_in = @fixed_in AND status = '" . BugtrackerItem::FIXED . "') as fixed_bugs_number,
		(SELECT COUNT(*) FROM " . BugtrackerSetup::$bugtracker_table . " WHERE fixed_in = @fixed_in AND (status = '" . BugtrackerItem::IN_PROGRESS . "' OR status = '" . BugtrackerItem::REOPEN . "')) as in_progress_bugs_number
		FROM " . BugtrackerSetup::$bugtracker_table . "
		GROUP BY fixed_in
		ORDER BY fixed_in ASC");

		foreach ($result as $row)
		{
			if (!empty($row['fixed_in']) && isset($versions[$row['fixed_in']]))
				$this->bugs_number_per_version[$row['fixed_in']] = array(
					'all' => $row['bugs_number'],
					BugtrackerItem::FIXED => $row['fixed_bugs_number'],
					BugtrackerItem::IN_PROGRESS => $row['in_progress_bugs_number'],
				);
		}
		$result->dispose();

		$result = $db_querier->select("SELECT member.*, COUNT(*) as bugs_number
		FROM " . BugtrackerSetup::$bugtracker_table . " bugtracker
		JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = bugtracker.author_id
		WHERE status <> '" . BugtrackerItem::REJECTED . "'
		GROUP BY author_id
		ORDER BY bugs_number DESC
		LIMIT " . $config->get_stats_top_posters_number());

		$i = 1;
		foreach ($result as $row)
		{
			$author = new User();
			if (!empty($row['user_id']))
				$author->set_properties($row);
			else
				$author->init_visitor_user();

			$this->top_posters[$i] = array(
				'user'			=> $author,
				'bugs_number'	=> $row['bugs_number']
			);

			$i++;
		}
		$result->dispose();
	}

	public function get_bugs_number_list()
	{
		return $this->bugs_number;
	}

	public function get_bugs_number($identifier)
	{
		if (isset($this->bugs_number[$identifier]))
			return $this->bugs_number[$identifier];

		return null;
	}

	public function get_bugs_number_per_version_list()
	{
		return $this->bugs_number_per_version;
	}

	public function get_bugs_number_per_version($identifier)
	{
		if (isset($this->bugs_number_per_version[$identifier]))
			return $this->bugs_number_per_version[$identifier];

		return null;
	}

	public function get_top_posters_list()
	{
		return $this->top_posters;
	}

	public function get_top_poster($identifier)
	{
		if (isset($this->top_posters[$identifier]))
			return $this->top_posters[$identifier];

		return null;
	}

	/**
	 * Loads and returns the bugtracker stats cached data.
	 * @return BugtrackerStatsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'bugtracker', 'stats');
	}

	/**
	 * Invalidates the current Bugtracker stats cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('bugtracker', 'stats');
	}
}
?>
