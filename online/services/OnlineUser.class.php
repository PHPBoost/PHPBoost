<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 04 28
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
		$this->avatar = !empty($avatar) ? Url::to_rel($avatar) : UserAccountsConfig::load()->get_default_avatar();
	}

	public function get_avatar()
	{
		return $this->avatar;
	}

	public function has_avatar()
	{
		return !empty($this->avatar);
	}
}
?>
