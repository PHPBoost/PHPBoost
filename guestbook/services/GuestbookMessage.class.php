<?php
/*##################################################
 *		                         GuestbookMessage.class.php
 *                            -------------------
 *   begin                : June 27, 2013
 *   copyright            : (C) 2013 j1.seth
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class GuestbookMessage
{
	private $id;
	private $contents;
	private $login;
	private $user_id;
	private $creation_date;
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function set_contents($value)
	{
		$this->contents = $value;
	}
	
	public function get_contents()
	{
		return $this->contents;
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
	
	public function is_authorized_edit()
	{
		return GuestbookAuthorizationsService::check_authorizations()->moderation() || (GuestbookAuthorizationsService::check_authorizations()->write() && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}
	
	public function is_authorized_delete()
	{
		return GuestbookAuthorizationsService::check_authorizations()->moderation() || (GuestbookAuthorizationsService::check_authorizations()->write() && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}
	
	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'contents' => $this->get_contents(),
			'login' => $this->get_login(),
			'user_id' => $this->get_author_user()->get_id(),
			'timestamp' => $this->get_creation_date()->get_timestamp()
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->contents = $properties['contents'];
		$this->login = $properties['glogin'];
		$this->creation_date = new Date($properties['timestamp'], Timezone::SERVER_TIMEZONE);
		
		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();
		$this->set_author_user($user);
	}
	
	public function init_default_properties()
	{
		$current_user = AppContext::get_current_user();
		$this->set_author_user($current_user);
		$this->creation_date = new Date();
		
		if (!$current_user->check_level(User::MEMBER_LEVEL))
			$this->login = LangLoader::get_message('visitor', 'user-common');
		else
			$this->login = $current_user->get_display_name();
	}
	
	public function get_array_tpl_vars($page = 1)
	{
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		
		return array(
			'C_EDIT' => $this->is_authorized_edit(),
			'C_DELETE' => $this->is_authorized_delete(),
			'C_AUTHOR_EXIST' => $user->get_id() != User::VISITOR_LEVEL,
			'C_USER_GROUP_COLOR' => !empty($user_group_color),
			
			//Message
			'ID' => $this->id,
			'CONTENTS' => FormatingHelper::second_parse($this->contents),
			'DATE' => $this->creation_date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE_TEXT),
			'DATE_DAY' => $this->creation_date->get_day(),
			'DATE_MONTH' => $this->creation_date->get_month(),
			'DATE_YEAR' => $this->creation_date->get_year(),
			'DATE_ISO8601' => $this->creation_date->format(Date::FORMAT_ISO8601),
			'PSEUDO' => $this->login ? $this->login : $user->get_display_name(),
			'USER_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR' => $user_group_color,
			
			'U_ANCHOR' => GuestbookUrlBuilder::home($page, $this->id)->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_EDIT' => GuestbookUrlBuilder::edit($this->id, $page)->rel(),
			'U_DELETE' => GuestbookUrlBuilder::delete($this->id)->rel()
		);
	}
}
?>