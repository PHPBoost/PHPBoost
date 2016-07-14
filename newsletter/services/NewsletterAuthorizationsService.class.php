<?php
/*##################################################
 *		                   NewsletterAuthorizationsService.class.php
 *                            -------------------
 *   begin                : March 17, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class NewsletterAuthorizationsService
{
	private $id_stream = null;
	private $stream_authorizations;
	private $is_error = false;
	
	const AUTH_READ = 1;
	const AUTH_SUBSCRIBE = 2;
	const AUTH_READ_SUBSCRIBERS = 4;
	const AUTH_MODERATION_SUBSCRIBERS = 8;
	const AUTH_CREATE_NEWSLETTERS = 16;
	const AUTH_READ_ARCHIVES = 32;
	const AUTH_MODERATION_ARCHIVES = 64;
	const AUTH_MANAGE_STREAMS = 128;
	
	public function __construct($id_stream = null)
	{
		if (!empty($id_stream))
		{
			$this->id_stream = $id_stream;
			$this->stream_authorizations = NewsletterStreamsCache::load()->get_stream($this->id_stream)->get_authorizations();
		}
	}
	
	public static function check_authorizations()
	{
		$instance = new self();
		return $instance;
	}
	
	/**
	 * Int $id_stream Stream id
	 * @return NewsletterAuthorizationsService by id stream
	 */
	public static function id_stream($id_stream = null)
	{
		$instance = new NewsletterAuthorizationsService($id_stream);
		return $instance;
	}
	
	/**
	 * @return NewsletterAuthorizationsService
	 */
	public static function get_errors()
	{
		$instance = new NewsletterAuthorizationsService();
		$instance->is_error = true;
		return $instance;
	}
	
	/**
	 * @return NewsletterAuthorizationsService
	 */
	public static function default_authorizations()
	{
		$instance = new NewsletterAuthorizationsService();
		return $instance;
	}
	
	public function read()
	{
		if ($this->is_error == false)
		{
			return $this->get_authorizations(self::AUTH_READ);
		}
		else
		{
			return $this->get_error(self::AUTH_READ);
		}
	}

	public function subscribe()
	{
		if ($this->is_error == false)
		{
			return $this->get_authorizations(self::AUTH_SUBSCRIBE);
		}
		else
		{
			return $this->get_error(self::AUTH_SUBSCRIBE);
		}
	}
	
	public function read_subscribers()
	{
		if ($this->is_error == false)
		{
			return $this->get_authorizations(self::AUTH_READ_SUBSCRIBERS);
		}
		else
		{
			return $this->get_error(self::AUTH_READ_SUBSCRIBERS);
		}
	}
	
	public function moderation_subscribers()
	{
		if ($this->is_error == false)
		{
			return $this->get_authorizations(self::AUTH_MODERATION_SUBSCRIBERS);
		}
		else
		{
			return $this->get_error(self::AUTH_MODERATION_SUBSCRIBERS);
		}
	}
	
	public function create_newsletters()
	{
		if ($this->is_error == false)
		{
			return $this->get_authorizations(self::AUTH_CREATE_NEWSLETTERS);
		}
		else
		{
			return $this->get_error(self::AUTH_CREATE_NEWSLETTERS);
		}
	}
	
	public function read_archives()
	{
		if ($this->is_error == false)
		{
			return $this->get_authorizations(self::AUTH_READ_ARCHIVES);
		}
		else
		{
			return $this->get_error(self::AUTH_READ_ARCHIVES);
		}
	}
	
	public function moderation_archives()
	{
		if ($this->is_error == false)
		{
			return $this->get_authorizations(self::AUTH_MODERATION_ARCHIVES);
		}
		else
		{
			return $this->get_error(self::AUTH_MODERATION_ARCHIVES);
		}
	}
	
	public function manage_streams()
	{
		if ($this->is_error == false)
		{
			return $this->get_authorizations(self::AUTH_MANAGE_STREAMS);
		}
		else
		{
			return $this->get_error(self::AUTH_MANAGE_STREAMS);
		}
	}
	
	private function retrieve_authorizations()
	{
		if (is_array($this->stream_authorizations) && !empty($this->id_stream))
		{
			return $this->stream_authorizations;
		}
		else
		{
			return NewsletterConfig::load()->get_authorizations();
		}
	}
	
	/**
	 * Const $authorizations_type
	 * @return true or false if user is not authorized
	 */
	private function get_authorizations($authorizations_type)
	{
		return AppContext::get_current_user()->check_auth($this->retrieve_authorizations(), $authorizations_type);
	}
	
	private function get_error($authorizations_type)
	{
		DispatchManager::redirect(PHPBoostErrors::user_not_authorized());
		return;
	}
}
?>