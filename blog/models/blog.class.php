<?php
/*##################################################
 *                             blog.class.php
 *                            -------------------
 *   begin                : June 02, 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : horn@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

define('BLOG__CLASS','blog');

mimport('blog/models/dao/blog_dao');
mimport('blog/models/blog_post');

/**
 * @author Loïc Rouchon <horn@phpboost.com>
 * @desc
 */
class Blog
{
	public function __construct($title = '', $description = '')
	{
		$this->title = $title;
		$this->description = $description;
		global $User;
		$this->user_id = $User->get_id();
	}

	public function get_id()
	{
		return $this->id;
	}

	public function get_user_id()
	{
		return $this->user_id;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_id($value)
	{
		$this->id = $value;
	}

	public function set_user_id($value)
	{
		$this->user_id = $value;
	}

	public function set_title($value)
	{
		$this->title = $value;
	}

	public function set_description($value)
	{
		$this->description = $value;
	}

	public static function global_action_url($global_action)
	{
		switch ($global_action)
		{
			case self::GLOBAL_ACTION_CREATE:
				return Dispatcher::get_url('/blog', '/create/');
			case self::GLOBAL_ACTION_CREATE_VALID:
				global $Session;
				return Dispatcher::get_url('/blog', '/create/valid/?token=' . $Session->get_token());
			case self::GLOBAL_ACTION_LIST:
			default:
				return Dispatcher::get_url('/blog', '/');
		}
	}

	public function action_url($action, $param = null)
	{
		switch ($action)
		{
			case self::ACTION_EDIT:
				return Dispatcher::get_url('/blog', $this->id . '/edit/');
			case self::ACTION_EDIT_VALID:
				global $Session;
				return Dispatcher::get_url('/blog', $this->id . '/edit/valid/?token=' . $Session->get_token());
			case self::ACTION_DELETE:
				global $Session;
				return Dispatcher::get_url('/blog', $this->id . '/delete/?token=' . $Session->get_token());
			case self::ACTION_ADD_POST:
				return Dispatcher::get_url('/blog', $this->id . '/add/');
			case self::ACTION_DETAILS:
			default:
				if ($param !== null && is_numeric($param))
				{   // represents the page number
					return Dispatcher::get_url('/blog', '/' . $this->id . '/' . $param . '/');
				}
				return Dispatcher::get_url('/blog', '/' . $this->id . '/');
		}
	}


	public function add($post)
	{
		$this->posts[] = $post;
	}

	public function get_added_post($i)
	{
		return $this->posts[$i];
	}

	public function get_added_posts()
	{
		return $this->posts;
	}

	public function remove_added_post($i)
	{
		unset($this->posts[$i]);
	}

	public function get_member_login()
	{
		return $this->user_name;
	}

	public function set_member_login($value)
	{
		return $this->user_name = $value;
	}

	private $id;
	private $title;
	private $description;
	private $user_id;
	private $user_name;
	private $posts = array();

	const GLOBAL_ACTION_LIST = 0x00;
	const GLOBAL_ACTION_CREATE = 0x01;
	const GLOBAL_ACTION_CREATE_VALID = 0x02;
	const ACTION_DETAILS = 0x01;
	const ACTION_EDIT = 0x02;
	const ACTION_EDIT_VALID = 0x03;
	const ACTION_DELETE = 0x04;
	const ACTION_ADD_POST = 0x05;
}
?>