<?php
/*##################################################
 *                           GuestbookMessagesCache.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class GuestbookMessagesCache implements CacheData
{
	private $messages = array();

	public function synchronize()
	{
		$this->messages = array();

		$result = PersistenceContext::get_querier()->select("
		SELECT g.id, g.login, g.user_id, g.timestamp, m.display_name as mlogin, g.contents
		FROM " . PREFIX . "guestbook g
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
		ORDER BY g.timestamp DESC
		LIMIT 10 OFFSET 0");

		foreach ($result as $msg)
		{
			$this->messages[$msg['id']] = array(
				'id' => $theme['id'],
				'contents' => nl2br(TextHelper::substr_html(strip_tags(FormatingHelper::second_parse($msg['contents'])), 0, 150)),
				'user_id' => $msg['user_id'],
				'login' => $msg['login'],
				'timestamp' => $msg['timestamp']
			);
		}
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
	 * @return GuestbookCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'module', 'guestbook-messages');
	}

	/**
	 * Invalidates the current Guestbook messages cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('module', 'guestbook-messages');
	}
}