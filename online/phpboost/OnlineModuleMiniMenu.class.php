<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class OnlineModuleMiniMenu extends ModuleMiniMenu
{
	private $robot_number = 0;
	private $visitor_number = 0;
	private $member_number = 0;
	private $moderator_number = 0;
	private $administrator_number = 0;
	private $total_users = 0;

	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function get_menu_id()
	{
		return 'module-mini-online';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('online.module.title', 'common', 'online');
	}

	public function is_displayed()
	{
		return !Url::is_current_url('/online/') && OnlineAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		$view = new FileTemplate('online/OnlineModuleMiniMenu.tpl');

		$lang = LangLoader::get_all_langs('online');
		$view->add_lang($lang);

		MenuService::assign_positions_conditions($view, $this->get_block());

		$online_config = OnlineConfig::load();
		$condition = 'WHERE s.timestamp > :time ORDER BY '. $online_config->get_display_order_request();
		$parameters = array(
			'time' => (time() - SessionsConfig::load()->get_active_session_duration())
		);

		$users = OnlineService::get_online_users($condition, $parameters);
		foreach ($users as $user)
		{
			$this->increment_level($user);

			if ($online_config->are_robots_displayed() || ($user->get_level() != User::ROBOT_LEVEL))
			{
				if ($this->total_users <= $online_config->get_members_number_displayed())
				{
					$group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

					if ($user->get_level() != User::VISITOR_LEVEL)
					{
						$view->assign_block_vars('items', array(
							'C_ROBOT'            => $user->get_level() == User::ROBOT_LEVEL,
							'C_USER_GROUP_COLOR' => !empty($group_color),

							'USER_DISPLAY_NAME' => $user->get_display_name(),
							'USER_LEVEL_CLASS'  => UserService::get_level_class($user->get_level()),
							'USER_GROUP_COLOR'  => $group_color,
						));
					}
				}
			}
		}

		if (!$online_config->are_robots_displayed())
			$this->visitor_number += $this->robot_number;

		$view->put_all(array(
			'C_DISPLAY_ROBOTS' => $online_config->are_robots_displayed(),
			'C_SEVERAL_USERS'  => $this->total_users > 1,
			'C_MORE_USERS'     => $this->total_users > $online_config->get_members_number_displayed(),

			'USERS_NUMBER'          => $this->total_users,
			'ROBOTS_NUMBER'         => $this->robot_number,
			'VISITORS_NUMBER'       => $this->visitor_number,
			'MEMBERS_NUMBER'        => $this->member_number,
			'MODERATORS_NUMBER'     => $this->moderator_number,
			'ADMINISTRATORS_NUMBER' => $this->administrator_number,
		));

		return $view->render();
	}

	private function increment_level(User $user)
	{
		$this->increment_user();
		switch ($user->get_level())
		{
			case User::ROBOT_LEVEL:
				$this->increment_robot();
			break;
			case User::VISITOR_LEVEL:
				$this->increment_visitor();
			break;
			case User::MEMBER_LEVEL:
				$this->increment_member();
			break;
			case User::MODERATOR_LEVEL:
				$this->increment_moderator();
			break;
			case User::ADMINISTRATOR_LEVEL:
				$this->increment_administrator();
			break;
		}
	}

	private function increment_user()
	{
		$this->total_users++;
	}

	private function increment_robot()
	{
		$this->robot_number++;
	}

	private function increment_visitor()
	{
		$this->visitor_number++;
	}

	private function increment_member()
	{
		$this->member_number++;
	}

	private function increment_moderator()
	{
		$this->moderator_number++;
	}

	private function increment_administrator()
	{
		$this->administrator_number++;
	}
}
?>
