<?php
/**
 * This class represente the current user
 * @package     PHPBoost
 * @subpackage  User
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 02 10
 * @since       PHPBoost 3.0 - 2012 03 31
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class CurrentUser extends User
{
	public static function from_session()
	{
		if (AppContext::get_session() === null)
		{
			Environment::load_imports();
			Environment::init();
		}
		
		$session = AppContext::get_session();
		
		return new self($session);
	}

	private $groups_auth = array();

	public function __construct(SessionData $session)
	{
		$this->id = $session->get_user_id();
		$this->level = $session->get_cached_data('level', User::VISITOR_LEVEL);
		$this->level = ($this->level == User::ROBOT_LEVEL ? User::VISITOR_LEVEL : $this->level);
		$this->is_admin = ($this->level == 2);

		$this->display_name = $session->get_cached_data('display_name', SessionData::DEFAULT_VISITOR_DISPLAY_NAME);
		$this->email = $session->get_cached_data('email', null);
		$this->show_email = $session->get_cached_data('show_email', false);
		$this->unread_pm = $session->get_cached_data('unread_pm', 0);
		$this->timestamp = $session->get_cached_data('timestamp', time());
		$this->warning_percentage = $session->get_cached_data('warning_percentage', 0);
		$this->delay_banned = $session->get_cached_data('delay_banned', 0);
		$this->delay_readonly = $session->get_cached_data('delay_readonly', 0);

		$user_accounts_config = UserAccountsConfig::load();
		$this->locale = $session->get_cached_data('locale', $user_accounts_config->get_default_lang());
		$this->theme = $session->get_cached_data('theme', $user_accounts_config->get_default_theme());
		$this->timezone = $session->get_cached_data('timezone', GeneralConfig::load()->get_site_timezone());
		$this->editor = $session->get_cached_data('editor', ContentFormattingConfig::load()->get_default_editor());

		$this->build_groups($session);
	}

	protected function build_groups(SessionData $session)
	{
		$groups = GroupsService::get_groups();
		foreach ($groups as $idgroup => $array_info)
		{
			$this->groups_auth[$idgroup] = $array_info['auth'];
		}

		$groups = explode('|', $session->get_cached_data('groups', ''));
		array_unshift($groups, 'r' . $this->level);
		$this->set_groups($groups);
	}

	public function check_level($level)
	{
		return $this->level >= $level;
	}

	public function check_auth($array_auth_groups, $authorization_bit)
	{
		//Si il s'agit d'un administrateur, étant donné qu'il a tous les droits, on renvoie systématiquement vrai
		if ($this->check_level(User::ADMIN_LEVEL))
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

		//Récupére les autorisations de tout les groupes dont le membre fait partie.
		$array_user_auth_groups = $this->array_group_intersect($this->groups_auth);
		$max_auth = $max_value_compare;
		foreach ($array_user_auth_groups as $idgroup => $group_auth)
		{
			if ($group_auth[$key_auth] < 0)
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
	 * Modify the theme for guest in the database (sessions table).
	 * @param string $theme The new theme
	 */
	public function update_theme($theme)
	{
		$db_querier = PersistenceContext::get_querier();
		if ($this->get_level() != User::VISITOR_LEVEL)
		{
			$this->set_theme($theme);
			UserService::update($this);
		}
		else
		{
			$session = AppContext::get_session();
			$session->add_cached_data('theme', $theme);
			$session->save();
		}
	}

	/**
	 * Modify the lang for guest in the database (sessions table).
	 * @param string $theme The new lang
	 */
	public function update_lang($lang)
	{
		$db_querier = PersistenceContext::get_querier();
		if ($this->get_level() != User::VISITOR_LEVEL)
		{
			$this->set_locale($lang);
			UserService::update($this);
		}
		else
		{
			$session = AppContext::get_session();
			$session->add_cached_data('locale', $lang);
			$session->save();
		}
	}

	public function update_visitor_display_name()
	{
		if ($this->id === Session::VISITOR_SESSION_ID)
			$this->display_name = LangLoader::get_message('guest', 'main');
	}

	private function sum_auth_groups($array_auth_groups)
	{
		//Rï¿½cupï¿½re les autorisations de tout les groupes dont le membre fait partie.
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
			elseif (TextHelper::substr($idgroup, 0, 1) == 'r') //Rang
			{
				if ($this->get_level() >= (int)str_replace('r', '', $idgroup))
				{
					$array_user_auth_groups[$idgroup] = $auth_group;
				}
			}
			else //Membre
			{
				if ($this->get_id() == (int)str_replace('m', '', $idgroup))
				{
					$array_user_auth_groups[$idgroup] = $auth_group;
				}
			}
		}

		return $array_user_auth_groups;
	}
}
?>
