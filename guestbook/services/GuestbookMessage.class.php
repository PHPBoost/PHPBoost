<?php
/*##################################################
 *		                         GuestbookMessage.class.php
 *                            -------------------
 *   begin                : June 27, 2013
 *   copyright            : (C) 2013 julienseth78
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class GuestbookMessage
{
	private $id;
	private $contents;
	private $login;
	private $user_id;
	private $timestamp;
	
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
	
	public function set_timestamp($value)
	{
		$this->timestamp = $value;
	}
	
	public function get_timestamp()
	{
		return $this->timestamp;
	}
	
	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}
	
	public function get_author_user()
	{
		return $this->author_user;
	}
	
	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'contents' => $this->get_contents(),
			'login' => $this->get_login(),
			'user_id' => $this->get_author_user()->get_id(),
			'timestamp' => $this->get_timestamp()
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_contents($properties['contents']);
		$this->set_login($properties['login']);
		$this->set_timestamp($properties['timestamp']);
		
		$user = new User();
		$user->set_properties($properties);
		$this->set_author_user($user);
	}
	
	public function init_default_properties()
	{
		$current_user = AppContext::get_current_user();
		$this->set_author_user($current_user);
		$this->set_timestamp(time());
		if (!$current_user->check_level(User::MEMBER_LEVEL))
			$this->set_login(LangLoader::get_message('guest', 'main'));
		else
			$this->set_login($current_user->get_pseudo());
	}
}
?>