<?php
/**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
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
		
		$this->id = $this->user_data['user_id'];
		$this->login = $this->user_data['login'];
		$this->level = $this->user_data['level'];
		$this->email = $this->user_data['user_mail'];
		
		$this->show_email = $this->user_data['user_show_mail'];
		
		$this->locale = $this->user_data['user_lang'];
		$this->theme = $this->user_data['user_theme'];
		$this->timezone = $this->user_data['user_timezone'];
		$this->editor = $this->user_data['user_editor'];
		
		$this->build_groups();
	}
	
	public function get_login()
	{
		return $this->login;
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
		array_unshift($this->groups, 'r' . $this->level); //Ajoute le groupe associé au rang du membre.
		array_pop($this->groups); //Supprime l'élément vide en fin de tableau.
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

	private function array_group_intersect($array_auth_groups)
	{
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