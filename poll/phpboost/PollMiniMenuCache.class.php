<?php
/*##################################################
 *                               PollMiniMenuCache.class.php
 *                            -------------------
 *   begin                : June 29, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class PollMiniMenuCache implements CacheData
{
	private $polls = array();
	
	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->polls = array();
		
		$displayed_in_mini_module_list = PollConfig::load()->get_displayed_in_mini_module_list();
		
		if ($displayed_in_mini_module_list)
		{
			$result = PersistenceContext::get_querier()->select("SELECT id, question, votes, answers, type
			FROM " . PollSetup::$poll_table . "
			WHERE archive = 0 AND visible = 1 AND id IN :ids_list", array(
				'ids_list' => $displayed_in_mini_module_list
			));
			
			while ($row = $result->fetch())
			{
				$row['question'] = stripslashes($row['question']);
				$row['answers'] = explode('|', $row['answers']);
				$row['votes'] = explode('|', $row['votes']);

				$number_votes = array_sum($row['votes']) ? array_sum($row['votes']) : 1;

				$array_votes = array_combine($row['answers'], $row['votes']);
				foreach ($array_votes as $answer => $nbrvote)
					$array_votes[$answer] = NumberHelper::round(($nbrvote * 100 / $number_votes), 1);
				
				$row['votes'] = $array_votes;
				$row['total'] = $number_votes;
				$this->polls[$row['id']] = $row;
			}
			$result->dispose();
		}
	}
	
	public function get_polls()
	{
		return $this->polls;
	}
	
	public function poll_exists($id)
	{
		return array_key_exists($id, $this->poll);
	}
	
	public function get_poll($id)
	{
		if ($this->poll_exists($id))
		{
			return $this->polls[$id];
		}
		return null;
	}
	
	public function get_number_polls()
	{
		return count($this->polls);
	}
	
	/**
	 * Loads and returns the poll cached data.
	 * @return PollMiniMenuCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'poll', 'minimenu');
	}
	
	/**
	 * Invalidates the current poll cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('poll', 'minimenu');
	}
}
?>
