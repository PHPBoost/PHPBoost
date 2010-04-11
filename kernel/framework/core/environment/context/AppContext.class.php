<?php
/*##################################################
 *                     environment_services.class.php
 *                            -------------------
 *   begin                : October 01, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @package core
 * @subpackage environment/context
 * @desc This class manages all the environment services.
 * It's able to create each of them and return them.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class AppContext
{
	/**
	 * @var HTTPRequest
	 */
	private static $request;
	/**
	 * @var HTTPRequest
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
	 * @var User
	 */
	private static $user;
	/**
	 * @var ExtensionPointProviderService
	 */
	private static $extension_provider_service;
	/**
	 * @var CacheService
	 */
	private static $cache_service;

	/**
	 * @desc Returns a unique identifier (useful for example to generate some javascript ids)
	 * @return int Id
	 */
	public static function get_uid()
	{
		static $uid = 1764;
		return $uid++;
	}

	/**
	 * @desc set the <code>HTTPRequest</code>
	 * @param HTTPRequest $request
	 */
	public static function set_request(HTTPRequest $request)
	{
		self::$request = $request;
	}

	/**
	 * @desc Returns the <code>HTTPRequest</code> object
	 * @return HTTPRequest
	 */
	public static function get_request()
	{
		return self::$request;
	}

	/**
	 * @desc set the <code>HTTPResponse</code>
	 * @param HTTPResponse $response
	 */
	public static function set_response(HTTPResponse $response)
	{
		self::$response = $response;
	}

	/**
	 * @desc Returns the <code>HTTPResponse</code> object
	 * @return HTTPResponse
	 */
	public static function get_response()
	{
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
	 * Inits the session
	 */
	public static function init_session()
	{
		self::set_session(new Session());
	}

	/**
	 * Sets the session
	 */
	public static function set_session(Session $session)
	{
		self::$session = $session;
	}

	/**
	 * Returns the current user's session
	 * @return Session
	 */
	public static function get_session()
	{
		return self::$session;
	}

	/**
	 * Inits the user
	 */
	public static function init_user()
	{
		self::$user = new User();
	}

	/**
	 * Returns the current user
	 * @return User
	 */
	public static function get_user()
	{
		return self::$user;
	}

	public static function set_user($user)
	{
		// TODO ben, supprime ça, mais casse pas l'installateur
		self::$user = $user;
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
		return self::$extension_provider_service;
	}
}

?>