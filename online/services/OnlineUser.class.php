<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 3.0 - 2012 02 01
*/

class OnlineUser extends User
{
	protected $location_script;
	protected $location_title;
	protected $last_update;
	protected $avatar;

	public function set_location_script($location_script)
	{
		$this->location_script = $location_script;
	}

	public function get_location_script()
	{
		return $this->location_script;
	}

	public function set_location_title($location_title)
	{
		$this->location_title = $location_title;
	}

	public function get_location_title()
	{
		return $this->location_title;
	}

	public function set_last_update($last_update)
	{
		$this->last_update = $last_update;
	}

	public function get_last_update()
	{
		return $this->last_update;
	}

	public function set_avatar($avatar)
	{
		$user_accounts_config = UserAccountsConfig::load();

		if (empty($avatar))
		{
			$this->avatar = $user_accounts_config->is_default_avatar_enabled() ? PATH_TO_ROOT . '/templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name() : '';
		}
		else
			$this->avatar = $avatar;
	}

	public function get_avatar()
	{
		return Url::to_rel($this->avatar);
	}

	public function has_avatar()
	{
		return !empty($this->avatar);
	}
}
?>
