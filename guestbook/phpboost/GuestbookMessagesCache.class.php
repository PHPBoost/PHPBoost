<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 07 31
 * @since       PHPBoost 3.0 - 2011 02 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class GuestbookMessagesCache implements CacheData
{
	private $messages = array();

	public function synchronize()
	{
		$this->messages = array();
		$items_number_per_page = GuestbookConfig::load()->get_items_per_page();

		$result = PersistenceContext::get_querier()->select('SELECT id
		FROM ' . GuestbookSetup::$guestbook_table . ' guestbook
		ORDER BY guestbook.timestamp DESC');

		$messages_pages = array();
		$page = $i = 1;
		while ($row = $result->fetch())
		{
			if ($i > ($page * $items_number_per_page))
				$page++;

			$messages_pages[$row['id']] = $page;

			$i++;
		}
		$result->dispose();

		$result = PersistenceContext::get_querier()->select("SELECT g.id, g.login, g.contents, g.timestamp, m.user_id, m.display_name, m.level, m.groups
		FROM " . GuestbookSetup::$guestbook_table . " g
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
		GROUP BY g.id, g.login, g.contents, g.timestamp, m.user_id, m.display_name, m.level, m.groups
		ORDER BY RAND()
		LIMIT 50");

		while ($row = $result->fetch())
		{
			$this->messages[$row['id']] = array(
				'id' => $row['id'],
				'contents' => strip_tags(FormatingHelper::second_parse($row['contents'])),
				'user_id' => $row['user_id'],
				'login' => $row['display_name'] ? $row['display_name'] : $row['login'],
				'level' => $row['level'],
				'groups' => $row['groups'],
				'timestamp' => $row['timestamp'],
				'page' => $messages_pages[$row['id']]
			);
		}
		$result->dispose();
	}

	public function get_messages()
	{
		return $this->messages;
	}

	public function get_message($identifier)
	{
		if (isset($this->messages[$identifier]))
		{
			return $this->messages[$identifier];
		}
		return null;
	}

	/**
	 * Loads and returns the messages guestbook cached data.
	 * @return GuestbookMessagesCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'guestbook', 'messages');
	}

	/**
	 * Invalidates the current Guestbook messages cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('guestbook', 'messages');
	}
}
?>
