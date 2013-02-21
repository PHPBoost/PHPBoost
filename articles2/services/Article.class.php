<?php
/*##################################################
 *                        Article.class.php
 *                            -------------------
 *   begin                : April 25, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class Article
{
    private $id;
    private $id_category;
    private $title;
    private $rewrited_title;
    private $description;
    private $content;
    private $picture_path;
    private $number_view;
    private $author_user_id;
    private $author_name_visitor;
    private $published;
    private $publishing_start_date;
    private $publishing_end_date;
    private $authorizations;
    private $timestamp_created;
	private $timestamp_last_modified;
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function set_id_category($id_category)
	{
		$this->id_category = $id_category;
	}
	
	public function get_id_category()
	{
		return $this->id_category;
	}
	
	public function set_title($title)
	{
		$this->title = $title;
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function set_rewrited_title($rewrited_title)
	{
		$this->rewrited_title = $rewrited_title;
	}
	
	public function get_rewrited_title()
	{
		return $this->rewrited_title;
	}
	
	public function set_description($description)
	{
		$this->description = $description;
	}
	
	public function get_description()
	{
		return $this->description;
	}
	
	public function set_content($content)
	{
		$this->content = $content;
	}
	
	public function get_content()
	{
		return $this->content;
	}
	
	public function set_picture_path($picture_path)
	{
		$this->picture_path = $picture_path;
	}
	
	public function get_picture_path()
	{
		return $this->picture_path;
	}
	
	public function set_number_view($number_view)
	{
		$this->number_view = $number_view;
	}
	
	public function get_number_view()
	{
		return $this->number_view;
	}
	
	public function set_author_user_id($author_user_id)
	{
		$this->author_user_id = $author_user_id;
	}
	
	public function get_author_user_id()
	{
		return $this->author_user_id;
	}
	
	public function set_author_name_visitor($author_name_visitor)
	{
		$this->author_name_visitor = $author_name_visitor;
	}
	
	public function get_author_name_visitor()
	{
		return $this->author_name_visitor;
	}
	
	public function set_publishing_state($published)
	{
		$this->published = $published;
	}
	
	public function get_publishing_state()
	{
		return $this->published;
	}
	
	public function set_publishing_start_date($publishing_start_date)
	{
		$this->publishing_start_date = $publishing_start_date;
	}
	
	public function get_publishing_start_date()
	{
		return $this->publishing_start_date;
	}
	
	public function set_publishing_end_date($publishing_end_date)
	{
		$this->publishing_end_date = $publishing_end_date;
	}
	
	public function get_publishing_end_date()
	{
		return $this->publishing_end_date;
	}
	
	public function set_authorizations($authorizations)
	{
		$this->authorizations = $authorizations;
	}
	
	public function get_authorizations()
	{
		return $this->authorizations;
	}
	
	public function set_timestamp_created($timestamp_created)
	{
		$this->timestamp_created = $timestamp_created;
	}
	
	public function get_timestamp_created()
	{
		return $this->timestamp_created;
	}
	
	public function set_timestamp_last_modified($timestamp_last_modified)
	{
		$this->timestamp_last_modified = $timestamp_last_modified;
	}
	
	public function get_timestamp_last_modified()
	{
		return $this->timestamp_last_modified;
	}
}
?>