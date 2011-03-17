<?php
/*##################################################
 *                      	 NewsletterStreamsCache.class.php
 *                            -------------------
 *   begin                :  March 15 , 2011
 *   copyright            : (C) 2011 MASSY Kévin
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
class NewsletterStreamsCache implements CacheData
{
	private $streams = array();
	private $querier;
	
	public function __construct()
	{
		$this->querier = PersistenceContext::get_querier();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->streams = array();

		$result = $this->querier->select("SELECT 
		stream.id, stream.name, stream.description, stream.picture, stream.visible, stream.auth
		FROM " . NewsletterSetup::$newsletter_table_streams . " stream
		");

		while ($row = $result->fetch())
		{
			$auth = unserialize($row['auth']);
			$this->streams[$row['id']] = array(
				'id' => $row['id'],
				'name' => $row['name'],
				'description' => $row['description'],
				'picture' => $row['picture'],
				'visible' => $row['visible'],
				'authorizations' => is_array($auth) ? $auth : null,
				'subscribers' => $this->list_subscribers_by_stream($row['id'])
			);
		}
	}

	public function get_streams()
	{
		return $this->streams;
	}
	
	public function get_existed_streams()
	{
		return (count($this->streams) > 0) ? true : false;
	}
	
	public function get_number_streams()
	{
		return count($this->streams);
	}
	
	public function get_existed_stream($id_stream)
	{
		return array_key_exists($id_stream, $this->streams);
	}
	
	public function get_stream($id_stream)
	{
		if (isset($this->streams[$id_stream]))
		{
			return $this->streams[$id_stream];
		}
		return null;
	}
	
	public function get_authorizations_by_stream($id_stream)
	{
		$stream = $this->get_stream($id_stream);
		if ($stream !== null)
		{
			return $stream['authorizations'];
		}
		return null;
	}
	
	public function get_subscribers_by_stream($id_stream)
	{
		$stream = $this->get_stream($id_stream);
		if ($stream !== null)
		{
			return $stream['subscribers'];
		}
		return null;
	}
	
	/**
	 * Loads and returns the newsletter streams cached data.
	 * @return NewsletterStreamsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'module', 'newsletter-streams');
	}
	
	/**
	 * Invalidates the current newsletter streams
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('module', 'newsletter-streams');
	}
	
	private function list_subscribers_by_stream($stream_id)
	{
		$list_subscribers = array();
		$result = $this->querier->select("SELECT 
		subscribtion.stream_id, subscribtion.subscriber_id, subscriber.id, subscriber.user_id, subscriber.mail
		FROM " . NewsletterSetup::$newsletter_table_subscribtions . " subscribtion
		LEFT JOIN " . NewsletterSetup::$newsletter_table_subscribers . " subscriber ON subscribtion.subscriber_id = subscriber.id
		WHERE subscribtion.stream_id = :stream_id
		",
			array(
				'stream_id' => $stream_id
			), SelectQueryResult::FETCH_ASSOC
		);
		
		while ($row = $result->fetch())
		{
			$list_subscribers[$row['id']] = array(
				'id' => $row['id'],
				'user_id' => $row['user_id'],
				'mail' => $row['mail']
			);
		}
		return $list_subscribers;
	}
}
