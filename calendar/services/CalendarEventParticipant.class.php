<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 11 27
*/

class CalendarEventParticipant
{
	private $user_id;
	private $display_name;
	private $level;
	private $user_groups;

	public function set_user_id($user_id)
	{
		$this->user_id = $user_id;
	}

	public function get_user_id()
	{
		return $this->user_id;
	}

	public function set_display_name($display_name)
	{
		$this->display_name = $display_name;
	}

	public function get_display_name()
	{
		return $this->display_name;
	}

	public function set_level($level)
	{
		$this->level = $level;
	}

	public function get_level()
	{
		return $this->level;
	}

	public function set_user_groups(Array $user_groups)
	{
		$this->user_groups = $user_groups;
	}

	public function get_user_groups()
	{
		return $this->user_groups;
	}

	public function get_properties()
	{
		return array(
			'user_id' => $this->get_user_id(),
			'display_name' => $this->get_login(),
			'level' => $this->get_level(),
			'user_groups' => $this->get_user_groups()
		);
	}

	public function set_properties(array $properties)
	{
		$this->user_id = $properties['user_id'];
		$this->display_name = $properties['display_name'];
		$this->level = $properties['level'];
		$this->user_groups = explode('|', $properties['groups']);
	}

	public function get_array_tpl_vars()
	{
		$group_color = User::get_group_color($this->user_groups, $this->level, true);

		return array(
			'C_GROUP_COLOR' => !empty($group_color),
			'DISPLAY_NAME' => $this->display_name,
			'LEVEL_CLASS' => UserService::get_level_class($this->level),
			'GROUP_COLOR' => $group_color,
			'U_PROFILE' => UserUrlBuilder::profile($this->user_id)->rel()
		);
	}
}
?>
