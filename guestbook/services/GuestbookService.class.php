<?php
/*##################################################
 *                        GuestbookService.class.php
 *                            -------------------
 *   begin                : November 30, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
 * @author Julien BRISWALTER <julien.briswalter@gmail.com>
 * @desc Services of the guestbook module
 */
class GuestbookService
{
	private static $querier;
	
	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}
	
	 /**
	 * @desc Count the messages list.
	 * @param string $condition (optional) Restriction to apply to the list
	 */
	public static function count($condition = '')
	{
		return self::$querier->count(PREFIX . 'guestbook', $condition);
	}
	
	 /**
	 * @desc Delete a message.
	 * @param string $condition Restriction to apply to the list of messages
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function delete($condition, array $parameters)
	{
		self::$querier->delete(PREFIX . 'guestbook', $condition, $parameters);
	}
	
	 /**
	 * @desc Return the content of a message.
	 * @param int $event_id ID of the message which is concerned
	 */
	public static function get_message($message_id)
	{
		return self::$querier->select_single_row(PREFIX . 'guestbook', array('*'), 'WHERE id=' . $message_id);
	}
	
	 /**
	 * @desc Create a new message.
	 * @param string[] $columns Values of the message
	 */
	public static function insert(array $columns)
	{
		$result = self::$querier->insert(PREFIX . 'guestbook', $columns);
		
		return $result->get_last_inserted_id();
	}
	
	 /**
	 * @desc Update a message.
	 * @param string[] $columns Values of the message
	 * @param string $condition Restriction to apply to the list of messages
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function update(array $columns, $condition, array $parameters)
	{
		self::$querier->update(PREFIX . 'guestbook', $columns, $condition, $parameters);
	}
}
?>
