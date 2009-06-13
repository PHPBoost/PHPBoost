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

mimport('blog/model/dao/blog_dao');
mimport('blog/model/blog_post');

/**
 * @author Loïc Rouchon <horn@phpboost.com>
 * @desc
 */
class Blog
{
	public function __construct($title = '')
	{
		$this->title = $title;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function set_id($value)
	{
		$this->id = $value;
	}

	public function set_title($value)
	{
		$this->title = $value;
	}

	public function get_post($i)
	{
		return $this->posts[$i];
	}

	public function get_posts()
	{
		return $this->posts;
	}

	public function get_nb_posts()
	{
		return count($this->posts);
	}

	public function add($post)
	{
		$this->posts[] = $post;
	}
	
	public function remove_post($i)
	{
		unset($this->posts[$i]);
	}

	private $id;
	private $title;
	private $posts = array();
}
?>