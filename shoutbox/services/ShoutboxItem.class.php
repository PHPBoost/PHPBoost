<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 07 11
 * @since       PHPBoost 4.1 - 2014 10 15
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

class ShoutboxItem
{
	private $id;
	private $content;
	private $login;
	private $user_id;
	private $author_user;
	private $creation_date;

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_content($value)
	{
		$this->content = $value;
	}

	public function get_content()
	{
		return $this->content;
	}

	public function set_login($value)
	{
		$this->login = $value;
	}

	public function get_login()
	{
		return $this->login;
	}

	public function set_user_id($value)
	{
		$this->user_id = $value;
	}

	public function get_user_id()
	{
		return $this->user_id;
	}

	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function is_authorized_to_edit()
	{
		return ShoutboxAuthorizationsService::check_authorizations()->moderation() || (ShoutboxAuthorizationsService::check_authorizations()->write() && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function is_authorized_to_delete()
	{
		return ShoutboxAuthorizationsService::check_authorizations()->moderation() || (ShoutboxAuthorizationsService::check_authorizations()->write() && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'content' => $this->get_content(),
			'login' => $this->get_login(),
			'user_id' => $this->get_author_user()->get_id(),
			'timestamp' => $this->get_creation_date()->get_timestamp()
		);
	}

	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->content = $properties['content'];
		$this->login = $properties['display_name'] ? $properties['display_name'] : $properties['login'];
		$this->creation_date = new Date($properties['timestamp'], Timezone::SERVER_TIMEZONE);

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
		{
			$user->init_visitor_user();
			$user->set_display_name($properties['login']);
		}
		$this->set_author_user($user);
	}

	public function init_default_properties()
	{
		$current_user = AppContext::get_current_user();
		$this->set_author_user($current_user);

		if (!$current_user->check_level(User::MEMBER_LEVEL))
			$this->login = LangLoader::get_message('user.guest', 'user-lang');
		else
			$this->login = $current_user->get_display_name();
	}

	public function get_template_vars($page = 1)
	{
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

		return array_merge(Date::get_array_tpl_vars($this->creation_date, 'date'),array(
			'C_EDIT'             => $this->is_authorized_to_edit(),
			'C_DELETE'           => $this->is_authorized_to_delete(),
			'C_AUTHOR_EXISTS'    => $user->get_id() != User::VISITOR_LEVEL,
			'C_USER_GROUP_COLOR' => !empty($user_group_color),

			//Message
			'ID'               => $this->id,
			'CONTENT'          => FormatingHelper::second_parse($this->content),
			'PSEUDO'           => $this->login ? $this->login : $user->get_display_name(),
			'USER_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR' => $user_group_color,

			'U_ANCHOR'         => ShoutboxUrlBuilder::home($page, $this->id)->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_EDIT'           => ShoutboxUrlBuilder::edit($this->id, $page)->rel(),
			'U_DELETE'         => ShoutboxUrlBuilder::delete($this->id)->rel()
		));
	}
}
?>
