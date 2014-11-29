<?php
/*##################################################
 *                           ShoutboxMessagesCache.class.php
 *                            -------------------
 *   begin                : October 15, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class ShoutboxMessagesCache implements CacheData
{
	private $messages = array();
	
	public function synchronize()
	{
		$this->messages = array();
		
		$max_messages_number = ShoutboxConfig::load()->get_max_messages_number();
		
		$result = PersistenceContext::get_querier()->select('SELECT s.id, s.login, s.contents, s.timestamp, m.user_id, m.display_name as mlogin, m.level, m.groups
		FROM ' . ShoutboxSetup::$shoutbox_table . ' s
		LEFT JOIN ' . DB_TABLE_MEMBER . ' m ON m.user_id = s.user_id
		GROUP BY timestamp DESC
		' . ($max_messages_number > 0 ? 'LIMIT :max_messages_number' : ''), array(
			'max_messages_number' => $max_messages_number 
		));
		
		foreach ($result as $msg)
		{
			$this->messages[$msg['id']] = array(
				'id' => $msg['id'],
				'contents' => strip_tags(FormatingHelper::second_parse($msg['contents'])),
				'user_id' => $msg['user_id'],
				'login' => $msg['mlogin'] ? $msg['mlogin'] : $msg['login'],
				'level' => $msg['level'],
				'groups' => $msg['groups'],
				'timestamp' => $msg['timestamp']
			);
			$i++;
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
	 * Loads and returns the messages shoutbox cached data.
	 * @return ShoutboxMessagesCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'shoutbox', 'messages');
	}
	
	/**
	 * Invalidates the current Shoutbox messages cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('shoutbox', 'messages');
	}
}
?>
