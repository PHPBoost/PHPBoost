<?php
/**
 * This class manages all the environment services.
 * It's able to create each of them and return them.
 * @package     Core
 * @subpackage  Environment\context
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 01
*/

class AppContext
{
	/**
	 * @var HTTPRequestCustom
	 */
	private static $request;
	/**
	 * @var HTTPRequestCustom
	 */
	private static $response;

	/**
	 * @var BreadCrumb
	 */
	private static $breadcrumb;
	/**
	 * @var Bench
	 */
	private static $bench;
	/**
	 * @var Session
	 */
	private static $session;
	/**
	 * @var CurrentUser
	 */
	private static $current_user;
	/**
	 * @var ExtensionPointProviderService
	 */
	private static $extension_provider_service;
	/**
	 * @var CacheService
	 */
	private static $cache_service;
	/**
	 *
	 * @var MailService
	 */
	private static $mail_service;
	/**
	 * @var ContentFormattingService
	 */
	private static $content_formatting_service;
	/**
	 * @var CaptchaService
	 */
	private static $captcha_service;

	/**
	 * Returns a unique identifier (useful for example to generate some javascript ids)
	 * @return int Id
	 */
	public static function get_uid()
	{
		static $uid = 1764;
		return $uid++;
	}

	/**
	 * set the <code>HTTPRequestCustom</code>
	 * @param HTTPRequestCustom $request
	 */
	public static function set_request(HTTPRequestCustom $request)
	{
		self::$request = $request;
	}

	/**
	 * Returns the <code>HTTPRequestCustom</code> object
	 * @return HTTPRequestCustom
	 */
	public static function get_request()
	{
		if (self::$request == null)
		{
			self::$request = new HTTPRequestCustom();
		}
		return self::$request;
	}

	/**
	 * set the <code>HTTPResponseCustom</code>
	 * @param HTTPResponseCustom $response
	 */
	public static function set_response(HTTPResponseCustom $response)
	{
		self::$response = $response;
	}

	/**
	 * Returns the <code>HTTPResponseCustom</code> object
	 * @return HTTPResponseCustom
	 */
	public static function get_response()
	{
		if (self::$response == null)
		{
			self::$response = new HTTPResponseCustom();
		}
		return self::$response;
	}

	/**
	 * Inits the bench
	 */
	public static function init_bench()
	{
		self::$bench = new Bench();
		self::$bench->start();
	}

	/**
	 * Returns the current page's bench
	 * @return Bench
	 */
	public static function get_bench()
	{
		return self::$bench;
	}

	/**
	 * Sets the session
	 */
	public static function set_session(SessionData $session)
	{
		self::$session = $session;
	}

	/**
	 * Returns the current user's session
	 * @return SessionData
	 */
	public static function get_session()
	{
		return self::$session;
	}

	/**
	 * Inits the current user
	 */
	public static function init_current_user()
	{
		self::$current_user = CurrentUser::from_session();
	}

	/**
	 * Returns the current user
	 * @return CurrentUser
	 */
	public static function get_current_user()
	{
		if (self::$current_user === null)
		{
			self::$current_user = CurrentUser::from_session();
		}
		return self::$current_user;
	}

	public static function set_current_user($current_user)
	{
		self::$current_user = $current_user;
	}

	/**
	 * Returns the cache service
	 * @return CacheService
	 */
	public static function get_cache_service()
	{
		if (self::$cache_service === null)
		{
			self::$cache_service = new CacheService();
		}
		return self::$cache_service;
	}

	public static function set_cache_service(CacheService $cache_service)
	{
		self::$cache_service = $cache_service;
	}

	/**
	 * Inits the extension provider service
	 */
	public static function init_extension_provider_service()
	{
		self::$extension_provider_service = new ExtensionPointProviderService();
	}

	/**
	 * @return ExtensionPointProviderService
	 */
	public static function get_extension_provider_service()
	{
		if (self::$extension_provider_service === null)
		{
			self::$extension_provider_service = new ExtensionPointProviderService();
		}
		return self::$extension_provider_service;
	}

	/**
	 * @return MailService
	 */
	public static function get_mail_service()
	{
		if (self::$mail_service === null)
		{
			$config = MailServiceConfig::load();
			if ($config->is_smtp_enabled())
			{
				self::$mail_service = new SMTPMailService($config->to_smtp_config());
			}
			else
			{
				self::$mail_service = new DefaultMailService();
			}
		}
		return self::$mail_service;
	}

	/**
	 * @return ContentFormattingService
	 */
	public static function get_content_formatting_service()
	{
		if (self::$content_formatting_service === null)
		{
			self::$content_formatting_service = new ContentFormattingService();
		}
		return self::$content_formatting_service;
	}

	/**
	 * @return CaptchaService
	 */
	public static function get_captcha_service()
	{
		if (self::$captcha_service === null)
		{
			self::$captcha_service = new CaptchaService();
		}
		return self::$captcha_service;
	}
}
?>
