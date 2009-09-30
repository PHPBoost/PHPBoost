<?php
/*##################################################
 *                               user.class.php
 *                            -------------------
 *   begin                : February 18, 2008
 *   copyright            : (C) 2008 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

define('RANK_TYPE', 1);
define('GROUP_TYPE', 2);
define('USER_TYPE', 3);

/**
 * @author Régis VIARRE <crowkait@phpboost.com>
 * @desc This class manage user, it provide you methods to get or modify user informations, moreover methods allow you to control user authorizations
 * @package members
 */
class User
{
	/**
	 * @var Session
	 */
	private $user_data; //Données du membres, obtenues à partir de la class de session.
	private $groups_auth; //Tableau contenant le nom des groupes disponibles.
	private $user_groups; //Groupes du membre.

	/**
	 * @desc Sets global authorizations which are given by all the user groups authorizations.
	 */
	public function __construct()
	{
		$this->user_data = Environment::get_instance()->get_session()->get_data();

		//Autorisations des groupes disponibles.
		$groups_auth = array();
		foreach (GroupsService::get_groups() as $idgroup => $array_info)
		{
			$groups_auth[$idgroup] = $array_info['auth'];
		}
		$this->groups_auth = $groups_auth;

		//Groupes du membre.
		$this->user_groups = explode('|', $this->user_data['user_groups']);
		array_unshift($this->user_groups, 'r' . $this->user_data['level']); //Ajoute le groupe associé au rang du membre.
		array_pop($this->user_groups); //Supprime l'élément vide en fin de tableau.
	}

	/**
	 * @desc Accessor
	 * @param string $attribute The attribute name.
	 * @return unknown_type
	 */
	public function get_attribute($attribute)
	{
		return isset($this->user_data[$attribute]) ? $this->user_data[$attribute] : '';
	}

	/**
	 * @desc Get the user id
	 * @return int The user id.
	 */
	public function get_id()
	{
		return (int)$this->get_attribute('user_id');
	}

	/**
	 * @desc Get the user group associated color.
	 * @param string $user_groups The list of user groups separated by pipe.
	 * @param int $level The user level. Only member have special color.
	 * @return string The group color (hexadecimal format)
	 * @static
	 */
	/* static */ public function get_group_color($user_groups, $level = 0)
	{
		$user_groups = explode('|', $user_groups);
		array_pop($user_groups); //Supprime l'élément vide en fin de tableau.
		$i = 0;

		$groups_cache = GroupsCacheData::load();

		foreach ($user_groups as $idgroup) //Récupération du premier groupe.
		{
			if ($i++ == 0)
			{
				$group = $groups_cache->get_group($idgroup);
				return (!empty($group['color']) && $level == 0) ? '#' . $group['color'] : '';
			}
		}
	}

	/**
	 * @desc Check the authorization level
	 * @param int $secure Constant of level authorization to check (MEMBER_LEVEL, MODO_LEVEL, ADMIN_LEVEL).
	 * @return boolean True if authorized, false otherwise.
	 */
	public function check_level($secure)
	{
		if (isset($this->user_data['level']) && $this->user_data['level'] >= $secure)
		return true;
		return false;
	}

	/**
	 * @desc Get the authorizations given by all the user groups. Then check the authorization.
	 * @param array $array_auth_groups The array passed to check the authorization.
	 * @param int $authorization_bit Value of position bit to check the authorization.
	 * This value has to be a multiple of two. You can use this simplified scripture :
	 * 0x01, 0x02, 0x04, 0x08 to set a new position bit to check.
	 * @return boolean True if authorized, false otherwise.
	 */
	public function check_auth($array_auth_groups, $authorization_bit)
	{
		//Si il s'agit d'un administrateur, étant donné qu'il a tous les droits, on renvoie systématiquement vrai
		if ($this->check_level(ADMIN_LEVEL))
		{
			return true;
		}

		//Si le tableau d'autorisation n'est pas valide, on renvoie faux pour des raisons de sécurité
		if (!is_array($array_auth_groups))
		{
			return false;
		}

		//Enfin, on regarde si le rang, le groupe ou son identifiant lui donnent l'autorisation sur le bit demandé
		return (bool)($this->sum_auth_groups($array_auth_groups) & (int)$authorization_bit);
	}

	/**
	 * @desc Get the maximum value of authorization in all user groups.
	 * @param int $key_auth
	 * @param int $max_value_compare Maximal value to compare
	 * @return unknown_type
	 */
	public function check_max_value($key_auth, $max_value_compare = 0)
	{
		if (!is_array($this->groups_auth))
		{
			return false;
		}

		//Récupère les autorisations de tout les groupes dont le membre fait partie.
		$array_user_auth_groups = $this->array_group_intersect($this->groups_auth);
		$max_auth = $max_value_compare;
		foreach ($array_user_auth_groups as $idgroup => $group_auth)
		{
			if ($group_auth[$key_auth] == -1)
			{
				return -1;
			}
			else
			{
				$max_auth = max($max_auth, $group_auth[$key_auth]);
			}
		}
			
		return $max_auth;
	}

	/**
	 * @desc Get all user groups
	 * @return string The user groups
	 */
	public function get_groups()
	{
		return $this->user_groups;
	}

	/**
	 * @desc Modify the user theme.
	 * @param string $user_theme The new theme.
	 */
	public function set_user_theme($user_theme)
	{
		$this->user_data['user_theme'] = $user_theme;
	}

	/**
	 * @desc Modify the theme for guest in the database (sessions table).
	 * @param string $user_theme The new theme
	 */
	public function update_user_theme($user_theme)
	{
		global $Sql, $CONFIG_USER;

		if ($CONFIG_USER['force_theme'] == 0) //Thèmes aux membres autorisés.
		{
			if ($this->user_data['level'] > -1)
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_theme = '" . strprotect($user_theme) . "' WHERE user_id = '" . $this->user_data['user_id'] . "'", __LINE__, __FILE__);
			}
			else
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_SESSIONS . " SET user_theme = '" . strprotect($user_theme) . "' WHERE level = -1 AND session_id = '" . $this->user_data['session_id'] . "'", __LINE__, __FILE__);
			}
		}
	}

	/**
	 * @desc Modify the user lang.
	 * @param string $user_lang The new lang
	 */
	public function set_user_lang($user_lang)
	{
		$this->user_data['user_lang'] = $user_lang;
	}

	/**
	 * @desc Modify the lang for guest in the database (sessions table).
	 * @param string $user_theme The new lang
	 */
	public function update_user_lang($user_lang)
	{
		global $Sql;

		if ($this->user_data['level'] > -1)
		{
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_lang = '" . strprotect($user_lang) . "' WHERE user_id = '" . $this->user_data['user_id'] . "'", __LINE__, __FILE__);
		}
		else
		{
			$Sql->query_inject("UPDATE " . DB_TABLE_SESSIONS . " SET user_lang = '" . strprotect($user_lang) . "' WHERE level = -1 AND session_id = '" . $this->user_data['session_id'] . "'", __LINE__, __FILE__);
		}
	}

	/**
	 * @desc Return the maximal authorization given by the user groups
	 * @param array $array_auth_groups
	 * @return string binary authorizations
	 */
	private function sum_auth_groups($array_auth_groups)
	{
		//Récupère les autorisations de tout les groupes dont le membre fait partie.
		$array_user_auth_groups = $this->array_group_intersect($array_auth_groups);
		$max_auth = 0;
		foreach ($array_user_auth_groups as $idgroup => $group_auth)
		{
			$max_auth |= (int)$group_auth;
		}

		return $max_auth;
	}

	/**
	 * @desc Compute the group <strong>intersection</strong> between the user groups and the group array in argument
	 * @param array $array_auth_groups Array of groups id
	 * @return array The new array computed.
	 */
	private function array_group_intersect($array_auth_groups)
	{
		global $User;

		$array_user_auth_groups = array();
		foreach ($array_auth_groups as $idgroup => $auth_group)
		{
			if (is_numeric($idgroup)) //Groupe
			{
				if (in_array($idgroup, $this->user_groups))
				{
					$array_user_auth_groups[$idgroup] = $auth_group;
				}
			}
			elseif (substr($idgroup, 0, 1) == 'r') //Rang
			{
				if ($User->get_attribute('level') >= (int)str_replace('r', '', $idgroup))
				{
					$array_user_auth_groups[$idgroup] = $auth_group;
				}
			}
			else //Membre
			{
				if ($User->get_attribute('user_id') == (int)str_replace('m', '', $idgroup))
				{
					$array_user_auth_groups[$idgroup] = $auth_group;
				}
			}
		}

		return $array_user_auth_groups;
	}
}

?>
