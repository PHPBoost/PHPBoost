<?php
/*##################################################
 *                       CurrentUser.class.php
 *                            -------------------
 *   begin                : March 31, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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
 * @author Kevin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class CurrentUser extends User
{
	private $login;
	private $user_data;
	private $groups_auth;
	
	public function __construct()
	{
		$this->user_data = AppContext::get_session()->get_data();
		$this->set_id($this->user_data['user_id']);
		$this->set_level($this->user_data['level']);
		$this->set_email($this->user_data['user_mail']);
		$this->set_show_email($this->user_data['user_show_mail']);
		$this->set_locale($this->user_data['user_lang']);
		$this->set_theme($this->user_data['user_theme']);
		$this->set_timezone($this->user_data['user_timezone']);
		$this->set_editor($this->user_data['user_editor']);
		
		$this->login = $this->user_data['login'];
		$this->build_groups();
	}
	
	public function get_login()
	{
		return $this->login;
	}

	public function get_attribute($attribute)
	{
		return isset($this->user_data[$attribute]) ? $this->user_data[$attribute] : '';
	}
	
	public function check_level($secure)
	{
		if (isset($this->user_data['level']) && $this->user_data['level'] >= $secure)
		return true;
		return false;
	}
	
	public function check_auth($array_auth_groups, $authorization_bit)
	{
		//Si il s'agit d'un administrateur, �tant donn� qu'il a tous les droits, on renvoie syst�matiquement vrai
		if ($this->check_level(User::ADMIN_LEVEL))
		{
			return true;
		}

		//Si le tableau d'autorisation n'est pas valide, on renvoie faux pour des raisons de s�curit�
		if (!is_array($array_auth_groups))
		{
			return false;
		}

		//Enfin, on regarde si le rang, le groupe ou son identifiant lui donnent l'autorisation sur le bit demand�
		return (bool)($this->sum_auth_groups($array_auth_groups) & (int)$authorization_bit);
	}
	
	public function check_max_value($key_auth, $max_value_compare = 0)
	{
		if (!is_array($this->groups_auth))
		{
			return false;
		}

		//R�cup�re les autorisations de tout les groupes dont le membre fait partie.
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
	 * @desc Modify the theme for guest in the database (sessions table).
	 * @param string $theme The new theme
	 */
	public function update_theme($theme)
	{
		$user_accounts_config = UserAccountsConfig::load();
		$db_querier = PersistenceContext::get_querier();
		if (!$user_accounts_config->is_users_theme_forced())
		{
			if ($this->get_level() > -1)
			{
				$db_querier->update(DB_TABLE_MEMBER, array('user_theme' => $theme), 'WHERE user_id=:user_id', array('user_id' => $this->get_id()));
			}
			else
			{
				$db_querier->update(DB_TABLE_SESSIONS, array('user_theme' => $theme), 'WHERE level=-1 AND session_id=session_id', array('session_id' => $this->user_data['session_id']));
			}
		}
	}
	
	/**
	 * @desc Modify the lang for guest in the database (sessions table).
	 * @param string $theme The new lang
	 */
	public function update_lang($lang)
	{
		$db_querier = PersistenceContext::get_querier();
		if ($this->get_level() > -1)
		{
			$db_querier->update(DB_TABLE_MEMBER, array('user_lang' => $lang), 'WHERE user_id=:user_id', array('user_id' => $this->get_id()));
		}
		else
		{
			$db_querier->update(DB_TABLE_SESSIONS, array('user_lang' => $lang), 'WHERE level=-1 AND session_id=session_id', array('session_id' => $this->user_data['session_id']));
		}
	}

	private function build_groups()
	{
		$groups_auth = array();
		foreach (GroupsService::get_groups() as $idgroup => $array_info)
		{
			$groups_auth[$idgroup] = $array_info['auth'];
		}
		$this->groups_auth = $groups_auth;

		$this->groups = explode('|', $this->user_data['user_groups']);
		array_unshift($this->groups, 'r' . $this->level); //Ajoute le groupe associ� au rang du membre.
		array_pop($this->groups); //Supprime l'�l�ment vide en fin de tableau.
	}
	
	private function sum_auth_groups($array_auth_groups)
	{
		//R�cup�re les autorisations de tout les groupes dont le membre fait partie.
		$array_user_auth_groups = $this->array_group_intersect($array_auth_groups);
		$max_auth = 0;
		foreach ($array_user_auth_groups as $idgroup => $group_auth)
		{
			$max_auth |= (int)$group_auth;
		}

		return $max_auth;
	}

	private function array_group_intersect($array_auth_groups)
	{
		$array_user_auth_groups = array();
		foreach ($array_auth_groups as $idgroup => $auth_group)
		{
			if (is_numeric($idgroup)) //Groupe
			{
				if (in_array($idgroup, $this->groups))
				{
					$array_user_auth_groups[$idgroup] = $auth_group;
				}
			}
			elseif (substr($idgroup, 0, 1) == 'r') //Rang
			{
				if ($this->get_attribute('level') >= (int)str_replace('r', '', $idgroup))
				{
					$array_user_auth_groups[$idgroup] = $auth_group;
				}
			}
			else //Membre
			{
				if ($this->get_attribute('user_id') == (int)str_replace('m', '', $idgroup))
				{
					$array_user_auth_groups[$idgroup] = $auth_group;
				}
			}
		}

		return $array_user_auth_groups;
	}
}
?>