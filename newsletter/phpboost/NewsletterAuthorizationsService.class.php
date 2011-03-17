<?php
/*##################################################
 *		                   NewsletterAuthorizationsService.class.php
 *                            -------------------
 *   begin                : March 17, 2011
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
class NewsletterAuthorizationsService
{
	private $default_authorizations;
	private $id_stream = null;
	private $user;
	private $streams_cache;
	
	const AUTH_READ = 1;
	const AUTH_SUBSCRIBE = 2;
	const AUTH_READ_SUBSCRIBERS = 4;
	const AUTH_MODERATION_SUBSCRIBERS = 8;
	const AUTH_CREATE_NEWSLETTERS = 16;
	const AUTH_READ_ARCHIVES = 32;
	
	public function __construct()
	{
		$this->default_authorizations = NewsletterConfig::load()->get_authorizations();
		$this->user = AppContext::get_user();
		if ($this->id_stream !== null)
		{
			$this->streams_cache = NewsletterStreamsCache::load()->get_authorizations_by_stream($this->id_stream);
		}
	}
	
	/**
	 * Int $id_stream Stream id
	 * @return NewsletterAuthorizationsService by id stream
	 */
	public static function id_stream($id_stream)
	{
		$instance = new NewsletterAuthorizationsService();
		$instance->id_stream = $id_stream;
		return $instance;
	}
	
	/**
	 * @return NewsletterAuthorizationsService by id stream
	 */
	public static function default_authorizations()
	{
		$instance = new NewsletterAuthorizationsService();
		return $instance;
	}
	
	public function read()
	{
		return $this->check_authorizations(self::AUTH_READ);
	}

	public function subscribe()
	{
		return $this->check_authorizations(self::AUTH_SUBSCRIBE);
	}
	
	public function read_subscribers()
	{
		return $this->check_authorizations(self::AUTH_READ_SUBSCRIBERS);
	}
	
	public function moderation_subscribers()
	{
		return $this->check_authorizations(self::AUTH_MODERATION_SUBSCRIBERS);
	}
	
	public function create_newsletters()
	{
		return $this->check_authorizations(self::AUTH_CREATE_NEWSLETTERS);
	}
	
	public function read_archives()
	{
		return $this->check_authorizations(self::AUTH_READ_ARCHIVES);
	}
	
	public function all_authorizations()
	{
		if (is_array($this->streams_cache) && $this->id_stream !== null)
		{
			return $this->streams_cache;
		}
		else
		{
			return $this->default_authorizations;
		}
	}
	
	/**
	 * Const $authorizations_type
	 * @return true or false if user is not authorized
	 */
	private function check_authorizations($authorizations_type)
	{
		return $this->user->check_auth($this->all_authorizations(), $authorizations_type);
	}
}
?>