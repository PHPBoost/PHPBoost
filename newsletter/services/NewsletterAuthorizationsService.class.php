<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 07 14
 * @since       PHPBoost 3.0 - 2011 03 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
