<?php
/*##################################################
 *                            AuthenticationService.class.php
 *                            -------------------
 *   begin                : November 28, 2010
 *   copyright            : (C) 2010 loic rouchon
 *   email                : horn@phpboost.com
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
 * @author Loic Rouchon <horn@phpboost.com>
 * @desc This class manages the authentication mecanism. Several authentication methods could be used.
 * If the authentication by the selected method successful, the user session is started.
 *
 * @package {@package}
 */
class AuthenticationService
{
	/**
	 * @desc associate the current authentication method with the given user_id.
	 * @param AuthenticationMethod $authentication the authentication method to use
	 * @param int $user_id
	 * @throws IllegalArgumentException if the user_id is already associate with an authentication method
	 */
	public static function associate(AuthenticationMethod $authentication, $user_id)
	{
		$authentication->associate($user_id);
	}

	/**
	 * @desc dissociate the current authentication method with the given user_id.
	 * @param AuthenticationMethod $authentication the authentication method to use
	 * @param int $user_id
	 * @throws IllegalArgumentException if the user_id is already dissociate with an authentication method
	 */
	public static function dissociate(AuthenticationMethod $authentication, $user_id)
	{
		$authentication->dissociate($user_id);
	}

	/**
	 * @desc Tries to authenticate the user using the given authentication method.
	 * @param AuthenticationMethod $authentication the authentication method to use
	 * @param bool $autoconnect If true, an autoconnect cookie will be created
	 * @return int $user_id, if authentication has been performed successfully
	 */
	public static function authenticate(AuthenticationMethod $authentication, $autoconnect = false)
	{
		$user_id = $authentication->authenticate();
		if ($user_id)
		{
			$session = AppContext::get_session();
			if ($session != null)
			{
				Session::delete($session);
			}
			$session_data = Session::create($user_id, $autoconnect);
			AppContext::set_session($session_data);
		}
		return $user_id;
	}

	public static function get_user_types_authentication($user_id)
	{
		$result = PersistenceContext::get_querier()->select_rows(DB_TABLE_AUTHENTICATION_METHOD, array('method'), 'WHERE user_id=:user_id', array('user_id' => $user_id));
		
		$types = array();
		foreach ($result as $row) {
			$types[] = $row['method'];
		}
		$result->dispose();
		
		return $types;
	}

	private static $external_authentications_actived;
	
	public static function get_external_auth_activated($identifier)
	{
		$external_auths_activated = self::get_external_auths_activated();
		if (self::external_auth_is_activated($identifier))
		{
			return $external_auths_activated[$identifier];
		}
	}

	public static function external_auth_is_activated($id)
	{
		return array_key_exists($id, self::get_external_auths_activated());
	}

	public static function get_external_auths_activated()
	{
		if (self::$external_authentications_actived == null)
		{
			self::$external_authentications_actived = array();

			$extension_point = AppContext::get_extension_provider_service()->get_extension_point(ExternalAuthenticationsExtensionPoint::EXTENSION_POINT);

			foreach ($extension_point as $id => $provider)
			{
				$external_authentications = $provider->get_external_authentications();

				foreach ($external_authentications as $external_authentication)
				{
					if ($external_authentication->authentication_actived())
					{
						self::$authentication_actived[$external_authentication->get_authentication_id()] = $external_authentication;
					}
				}
			}
		}
		return self::$external_authentications_actived;
	}
}
?>