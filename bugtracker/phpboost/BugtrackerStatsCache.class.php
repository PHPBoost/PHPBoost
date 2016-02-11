<?php
/*##################################################
 *                           BugtrackerStatsCache.class.php
 *                            -------------------
 *   begin                : July 11, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
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
		(SELECT COUNT(*) FROM " . BugtrackerSetup::$bugtracker_table . " WHERE fixed_in = @fixed_in AND status = '" . Bug::FIXED . "') as fixed_bugs_number, 
		(SELECT COUNT(*) FROM " . BugtrackerSetup::$bugtracker_table . " WHERE fixed_in = @fixed_in AND (status = '" . Bug::IN_PROGRESS . "' OR status = '" . Bug::REOPEN . "')) as in_progress_bugs_number
		FROM " . BugtrackerSetup::$bugtracker_table . "
		GROUP BY fixed_in
		ORDER BY fixed_in ASC");

		foreach ($result as $row)
		{
			if (!empty($row['fixed_in']) && isset($versions[$row['fixed_in']]))
				$this->bugs_number_per_version[$row['fixed_in']] = array(
					'all' => $row['bugs_number'],
					Bug::FIXED => $row['fixed_bugs_number'],
					Bug::IN_PROGRESS => $row['in_progress_bugs_number'],
				);
		}
		$result->dispose();
		
		$result = $db_querier->select("SELECT member.*, COUNT(*) as bugs_number
		FROM " . BugtrackerSetup::$bugtracker_table . " bugtracker
		JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = bugtracker.author_id
		WHERE status <> '" . Bug::REJECTED . "'
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
