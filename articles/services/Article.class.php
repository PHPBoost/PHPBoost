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
    private $id_categorie;
    private $title;
    private $description;
    private $contents;
    private $picture;
    private $source;
    private $writer_user_id;
    private $writer_name_visitor;
    private $visibility;
    private $start_visibility;
    private $end_visibility;
    private $authorizations;
    private $timestamp;
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function set_id_categorie($id_categorie)
	{
		$this->id_categorie = $id_categorie;
	}
	
	public function get_id_categorie()
	{
		return $this->id_categorie;
	}
	
	public function set_title($title)
	{
		$this->title = $title;
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function set_title($title)
	{
		$this->title = $title;
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function set_description($description)
	{
		$this->description = $description;
	}
	
	public function get_description()
	{
		return $this->description;
	}
	
	public function set_contents($contents)
	{
		$this->contents = $contents;
	}
	
	public function get_contents()
	{
		return $this->contents;
	}
	
	public function set_picture($picture)
	{
		$this->picture = $picture;
	}
	
	public function get_picture()
	{
		return $this->picture;
	}
	
	public function set_source($source)
	{
		$this->source = $source;
	}
	
	public function get_source()
	{
		return $this->source;
	}
	
	public function set_writer_user_id($writer_user_id)
	{
		$this->writer_user_id = $writer_user_id;
	}
	
	public function get_writer_user_id()
	{
		return $this->writer_user_id;
	}
	
	public function set_writer_name_visitor($writer_name_visitor)
	{
		$this->writer_name_visitor = $writer_name_visitor;
	}
	
	public function get_writer_name_visitor()
	{
		return $this->writer_name_visitor;
	}
	
	public function set_visibility($visibility)
	{
		$this->visibility = $visibility;
	}
	
	public function get_visibility()
	{
		return $this->visibility;
	}
	
	public function set_start_visibility($start_visibility)
	{
		$this->start_visibility = $start_visibility;
	}
	
	public function get_start_visibility()
	{
		return $this->start_visibility;
	}
	
	public function set_end_visibility($end_visibility)
	{
		$this->end_visibility = $end_visibility;
	}
	
	public function get_end_visibility()
	{
		return $this->end_visibility;
	}
	
	public function set_authorizations($authorizations)
	{
		$this->authorizations = $authorizations;
	}
	
	public function get_authorizations()
	{
		return $this->authorizations;
	}
	
	public function set_timestamp($timestamp)
	{
		$this->timestamp = $timestamp;
	}
	
	public function get_timestamp()
	{
		return $this->timestamp;
	}
}
?>