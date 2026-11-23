<?php
/**
 * This class represents the current user
 * @package     PHPBoost
 * @subpackage  User
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     [https://www.gnu.org/licenses/gpl-3.0.html](https://www.gnu.org/licenses/gpl-3.0.html) GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 3.0 - 2012 03 31
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/


class CurrentUser extends User
{
    public static function from_session(): mixed
    {
        if (AppContext::get_session() === null)
        {
            Environment::load_imports();
            Environment::init();
        }


        $session = AppContext::get_session();


        return new self($session);
    }


    private  array $groups_auth = [];


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
        $this->registration_date = $session->get_cached_data('registration_date', 0);
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


    protected function build_groups(SessionData $session): void
    {
        $groups = GroupsService::get_groups();
        foreach ($groups as $idgroup => $array_info)
        {
            $this->groups_auth[$idgroup] = $array_info['auth'];
        }


        $groups = explode('|', $session->get_cached_data('user_groups', ''));
        array_unshift($groups, 'r' . $this->level);
        $this->set_groups($groups);
    }


    public function check_level(int $level): bool
    {
        return $this->level >= $level;
    }


    public function check_auth(array $array_auth_groups, int $authorization_bit): bool
    {
        // If the user is an administrator, since they have all rights, always return true
        if ($this->check_level(User::ADMINISTRATOR_LEVEL))
        {
            return true;
        }


        // If the authorization array is invalid, return false for security reasons
        if (!is_array($array_auth_groups))
        {
            return false;
        }


        // Finally, check whether the user's rank, group, or ID grants authorization for the requested bit
        return (bool)($this->sum_auth_groups($array_auth_groups) & (int)$authorization_bit);
    }


    public function check_max_value(mixed $key_auth, int $max_value_compare = 0): mixed
    {
        if (!is_array($this->groups_auth))
        {
            return false;
        }


        // Retrieves the permissions of all groups the member belongs to.
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
     * Updates the guest theme in the database (sessions table).
     * @param string $theme The new theme
     */
    public function update_theme(string $theme): void
    {
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
     * Updates the guest language in the database (sessions table).
     * @param string $lang The new language
     */
    public function update_lang(string $lang): void
    {
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


    public function update_visitor_display_name(): void
    {
        if ($this->id === Session::VISITOR_SESSION_ID)
        {
            $this->display_name = LangLoader::get_message('user.guest', 'user-lang');
        }
    }


    private function sum_auth_groups(array $array_auth_groups): mixed
    {
        // Retrieves the permissions of all groups the member belongs to.
        $array_user_auth_groups = $this->array_group_intersect($array_auth_groups);
        $max_auth = 0;
        foreach ($array_user_auth_groups as $idgroup => $group_auth)
        {
            $max_auth |= (int)$group_auth;
        }


        return $max_auth;
    }


    private function array_group_intersect(array $array_auth_groups): array
    {
        $array_user_auth_groups = [];
        foreach ($array_auth_groups as $idgroup => $auth_group)
        {
            if (is_numeric($idgroup)) // Group
            {
                if (in_array($idgroup, $this->groups))
                {
                    $array_user_auth_groups[$idgroup] = $auth_group;
                }
            }
            elseif (TextHelper::substr($idgroup, 0, 1) == 'r') // Rank
            {
                if ($this->get_level() >= (int)str_replace('r', '', $idgroup))
                {
                    $array_user_auth_groups[$idgroup] = $auth_group;
                }
            }
            else // Member
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