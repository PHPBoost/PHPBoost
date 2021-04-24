<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 05
 * @since       PHPBoost 6.0 - 2020 05 14
*/

class PollManager extends ItemsManager
{
	public function update_votes(array $vote, int $current_votes_number, int $item_id)
	{
		self::$db_querier->update(
			self::$items_table,
			array(
				'votes' => TextHelper::serialize($vote),
				'votes_number' => $current_votes_number + 1
			),
			'WHERE id=:id',
			array('id' => $item_id)
		);
	}

	public function reset_votes_number($item_id)
	{
		self::$db_querier->update(self::$items_table, array('votes_number' => 0), 'WHERE id=:id', array('id' => $item_id));
	}

	public function insert_voter(int $item_id)
	{
		$properties = array(
			'poll_id' => $item_id,
			'voter_user_id' => AppContext::get_current_user()->get_id(),
			'voter_ip' => AppContext::get_request()->get_ip_address() ? AppContext::get_request()->get_ip_address() : '0.0.0.0',
			'vote_timestamp' => time()
		);
		self::$db_querier->insert(PREFIX . 'poll_voters', $properties);
	}

	// evolution
	public function get_voter(int $item_id, int $voter_user_id)
	{
		return self::$db_querier->select_single_row(
			PREFIX . 'poll_voters',
			array('id', 'poll_id', 'voter_user_id', 'voter_ip', 'vote_timestamp'),
			'WHERE poll_id =:poll_id AND voter_user_id =:voter_user_id',
			array('poll_id' => $item_id, 'voter_user_id' => $voter_user_id));
	}

	public function get_voters(int $item_id)
	{
		$result = self::$db_querier->select(
			'SELECT
			id, poll_id, voter_user_id, voter_ip, vote_timestamp
			FROM ' . PREFIX . 'poll_voters
			WHERE poll_id =:poll_id',
			array('poll_id' => $item_id)
		);

		$id = array();
		$voter_user_id = array();
		$voter_ip = array();
		$vote_timestamp = array();

		while ($row = $result->fetch())
		{
			$id[] = $row['id'];
			$voter_user_id[] = $row['voter_user_id'];
			$voter_ip[] = $row['voter_ip'];
			$vote_timestamp[] = $row['vote_timestamp'];
		}
		$result->dispose();

		$voters['id'] = $id;
		$voters['voter_user_id'] = $voter_user_id;
		$voters['voter_ip'] = $voter_ip;
		$voters['vote_timestamp'] = $vote_timestamp;

		return $voters;
	}

	public function delete_voters(int $item_id)
	{
		self::$db_querier->delete(PREFIX . 'poll_voters', 'WHERE poll_id =:poll_id', array('poll_id' => $item_id));
	}

	public function user_has_voted (int $voter_user_id, int $item_id)
	{
		if ($voter_user_id == -1)
		{
			$request = AppContext::get_request();

			$search_guest_by_ip = self::$db_querier->count(
			PREFIX . 'poll_voters',
			'WHERE voter_user_id < 0 AND voter_ip =:voter_ip AND poll_id =:poll_id',
			array( 'voter_ip' => $request->get_ip_address(), 'poll_id' => $item_id)) > 0;

			return $this->guest_has_vote_cookie($request, $item_id) || $search_guest_by_ip;
		}
		else
		{
			return self::$db_querier->count(
				PREFIX . 'poll_voters',
				'WHERE voter_user_id > 0 AND voter_user_id =:voter_user_id AND poll_id =:poll_id',
				array('voter_user_id' => $voter_user_id, 'poll_id' => $item_id)) > 0;
		}
	}

	public function guest_has_vote_cookie(HTTPRequestCustom $request, int $item_id)
	{
		$cookie_name = self::$module->get_configuration()->get_configuration_parameters()->get_cookie_name();
		$search_by_cookie = '';
		if ($request->has_cookieparameter($cookie_name))
		{
			$array_cookie = explode('/', $request->get_cookie($cookie_name));
			return $search_by_cookie = in_array($item_id, $array_cookie);
		}
		else
		  return false;
	}
}
?>
