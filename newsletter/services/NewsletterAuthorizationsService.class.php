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
	private $id_stream = null;
	private $stream_authorizations;
	private $is_error = false;
	
	const AUTH_READ = 1;
	const AUTH_SUBSCRIBE = 2;
	const AUTH_READ_SUBSCRIBERS = 4;
	const AUTH_MODERATION_SUBSCRIBERS = 8;
	const AUTH_CREATE_NEWSLETTERS = 16;
	const AUTH_READ_ARCHIVES = 32;
	
	public function __construct()
	{
		if ($this->id_stream !== null)
		{
			$this->stream_authorizations = NewsletterStreamsCache::load()->get_authorizations_by_stream($this->id_stream);
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
		return $instance->get_authorizations();
	}
	
	public function read()
	{
		if ($this->is_error == false)
		{
			return $this->check_authorizations(self::AUTH_READ);
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
			return $this->check_authorizations(self::AUTH_SUBSCRIBE);
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
			return $this->check_authorizations(self::AUTH_READ_SUBSCRIBERS);
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
			return $this->check_authorizations(self::AUTH_MODERATION_SUBSCRIBERS);
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
			return $this->check_authorizations(self::AUTH_CREATE_NEWSLETTERS);
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
			return $this->check_authorizations(self::AUTH_READ_ARCHIVES);
		}
		else
		{
			return $this->get_error(self::AUTH_READ_ARCHIVES);
		}
	}
	
	private function get_authorizations()
	{
		if (is_array($this->stream_authorizations) && $this->id_stream !== null)
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
	private function check_authorizations($authorizations_type)
	{
		return AppContext::get_user()->check_auth($this->get_authorizations(), $authorizations_type);
	}
	
	private function get_error($authorizations_type)
	{
		$lang = LangLoader::get('newsletter_common', 'newsletter');
		switch ($authorizations_type) {
			case self::AUTH_READ:
				$error_message = $lang['errors.not_authorized_read'];
				break;
			case self::AUTH_SUBSCRIBE:
				$error_message = $lang['errors.not_authorized_subscribe'];
				break;
			case self::AUTH_READ_SUBSCRIBERS:
				$error_message = $lang['errors.not_authorized_read_subscribers'];
				break;
			case self::AUTH_MODERATION_SUBSCRIBERS:
				$error_message = $lang['errors.not_authorized_moderation_subscribers'];
				break;
			case self::AUTH_CREATE_NEWSLETTERS:
				$error_message = $lang['errors.not_authorized_create_newsletters'];
				break;
			case self::AUTH_READ_ARCHIVES:
				$error_message = $lang['errors.not_authorized_read_archives'];
				break;
		}
		return new UserErrorController(LangLoader::get_message('error', 'errors'), $error_message);
	}
}
?>