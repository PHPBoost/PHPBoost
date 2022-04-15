<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 6.0 - last update: 2022 04 15
 * @since   	PHPBoost 4.1 - 2015 06 29
*/

class PollMiniMenuCache implements CacheData
{
	private $polls_displaying = array();
	private $polls_not_displaying = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->polls = array();

		$selected_items_in_config = PollConfig::load()->get_mini_module_selected_items();

		if ($selected_items_in_config)
		{
			foreach ($selected_items_in_config as $selected_item_id)
			{
				$selected_item = ItemsService::get_items_manager('poll')->get_item((int)$selected_item_id);
				if ($selected_item->user_is_empowered_to_vote())
					$this->polls_displaying[$selected_item->get_id()] = $selected_item->get_properties();
				else
					$this->polls_not_displaying[$selected_item->get_id()] = $selected_item->get_properties(); //evolution
			}
		}
	}

	public function get_polls_displaying()
	{
		return $this->polls_displaying;
	}

	public function get_polls_not_displaying()
	{
		return $this->polls_not_displaying;
	}

	public function poll_displaying_exists($id)
	{
		return array_key_exists($id, $this->polls_displaying);
	}

	public function poll_not_displaying_exists($id)
	{
		return array_key_exists($id, $this->polls_not_displaying);
	}

	public function get_displaying_poll($id)
	{
		if ($this->poll_displaying_exists($id))
		{
			return $this->polls_displaying[$id];
		}
		return null;
	}

	public function get_not_displaying_poll($id)
	{
		if ($this->poll_not_displaying_exists($id))
		{
			return $this->polls_not_displaying[$id];
		}
		return null;
	}

	public function get_number_polls_displaying()
	{
		return count($this->polls_displaying);
	}

	public function get_number_polls_not_displaying()
	{
		return count($this->polls_not_displaying);
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
