<?php
/**
 * This class manages all the environment services.
 * It's able to create each of them and return them.
 * @package     Core
 * @subpackage  Environment\context
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 02
 * @since       PHPBoost 3.0 - 2009 10 01
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AppContext
{
	private static ?HTTPRequestCustom $request = null;
	private static ?HTTPResponseCustom $response = null;
	private static ?BreadCrumb $breadcrumb = null;
	private static ?Bench $bench = null;
	private static ?SessionData $session = null;
	private static ?CurrentUser $current_user = null;
	private static ?ExtensionPointProviderService $extension_provider_service = null;
	private static ?CacheService $cache_service = null;
	private static ?MailService $mail_service = null;
	private static ?ContentFormattingService $content_formatting_service = null;
	private static ?CaptchaService $captcha_service = null;

	/** Returns a unique identifier (useful for example to generate some javascript ids) */
	public static function get_uid(): int
	{
		static $uid = 1764;
		return $uid++;
	}

	/** Set the <code>HTTPRequestCustom</code> */
	public static function set_request(HTTPRequestCustom $request): void
	{
		self::$request = $request;
	}

	/** Returns the <code>HTTPRequestCustom</code> object */
	public static function get_request(): HTTPRequestCustom
	{
		if (self::$request == null)
		{
			self::$request = new HTTPRequestCustom();
		}
		return self::$request;
	}

	/** Set the <code>HTTPResponseCustom</code> */
	public static function set_response(HTTPResponseCustom $response): void
	{
		self::$response = $response;
	}

	/** Returns the <code>HTTPResponseCustom</code> object */
	public static function get_response(): HTTPResponseCustom
	{
		if (self::$response == null)
		{
			self::$response = new HTTPResponseCustom();
		}
		return self::$response;
	}

	/** Inits the bench */
	public static function init_bench(): void
	{
		self::$bench = new Bench();
		self::$bench->start();
	}

	/** Returns the current page's bench */
	public static function get_bench(): Bench
	{
		return self::$bench;
	}

	/** Sets the session */
	public static function set_session(SessionData $session): void
	{
		self::$session = $session;
	}

	/** Returns the current user's session */
	public static function get_session(): SessionData
	{
		return self::$session;
	}

	/** Inits the current user */
	public static function init_current_user(): void
	{
		self::$current_user = CurrentUser::from_session();
	}

	/** Returns the current user */
	public static function get_current_user(): CurrentUser
	{
		if (self::$current_user === null)
		{
			self::$current_user = CurrentUser::from_session();
		}
		return self::$current_user;
	}

	public static function set_current_user(CurrentUser $current_user): void
	{
		self::$current_user = $current_user;
	}

	/** Returns the cache service */
	public static function get_cache_service(): CacheService
	{
		if (self::$cache_service === null)
		{
			self::$cache_service = new CacheService();
		}
		return self::$cache_service;
	}

	public static function set_cache_service(CacheService $cache_service): void
	{
		self::$cache_service = $cache_service;
	}

	/** Inits the extension provider service */
	public static function init_extension_provider_service(): void
	{
		self::$extension_provider_service = new ExtensionPointProviderService();
	}

	public static function get_extension_provider_service(): ExtensionPointProviderService
	{
		if (self::$extension_provider_service === null)
		{
			self::$extension_provider_service = new ExtensionPointProviderService();
		}
		return self::$extension_provider_service;
	}

	public static function get_mail_service(): MailService
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

	public static function get_content_formatting_service(): ContentFormattingService
	{
		if (self::$content_formatting_service === null)
		{
			self::$content_formatting_service = new ContentFormattingService();
		}
		return self::$content_formatting_service;
	}

	public static function get_captcha_service(): CaptchaService
	{
		if (self::$captcha_service === null)
		{
			self::$captcha_service = new CaptchaService();
		}
		return self::$captcha_service;
	}
}
?>
