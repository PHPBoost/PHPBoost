<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 09 15
 * @since       PHPBoost 4.1 - 2015 06 29
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
				{
					$nbrvote = intval($nbrvote);
					$array_votes[$answer] = NumberHelper::round(($nbrvote * 100 / $number_votes), 1);
				}

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
