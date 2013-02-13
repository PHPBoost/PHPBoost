<?php
/*##################################################
 *		                         News.class.php
 *                            -------------------
 *   begin                : February 13, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class News
{
	private $id;
	private $id_cat;
	private $title;
	private $contents;
	private $extend_contents;
	
	private $approbation;
	private $start_date;
	private $end_date;
	
	private $creation_date;
	private $user_id;

	private $picture_url;
	private $tags;
	private $sources;
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function set_id_cat($id_cat)
	{
		$this->id_cat = $id_cat;
	}
	
	public function get_id_cat()
	{
		return $this->id_cat;
	}
	
	public function set_title($title)
	{
		$this->title = $title;
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function set_contents($contents)
	{
		$this->contents = $contents;
	}
	
	public function get_contents()
	{
		return $this->contents;
	}
	
	public function set_extend_contents($extend_contents)
	{
		$this->extend_contents = $extend_contents;
	}
	
	public function get_extend_contents()
	{
		return $this->extend_contents;
	}
	
	public function set_approbation($approbation)
	{
		$this->approbation = $approbation;
	}
	
	public function get_approbation()
	{
		return $this->approbation;
	}
	
	public function set_start_date(Date $start_date)
	{
		$this->start_date = $start_date;
	}
	
	public function get_start_date()
	{
		return $this->start_date;
	}
	
	public function set_end_date(Date $end_date)
	{
		$this->end_date = $end_date;
	}
	
	public function get_end_date()
	{
		return $this->end_date;
	}
	
	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}
	
	public function get_creation_date()
	{
		return $this->creation_date;
	}
	
	public function set_user_id($user_id)
	{
		$this->user_id = $user_id;
	}
	
	public function get_user_id()
	{
		return $this->user_id;
	}
	
	public function set_picture(Url $picture)
	{
		$this->picture_url = $picture;
	}
	
	public function get_picture()
	{
		return $this->picture_url;
	}
	
	public function add_tag($tag)
	{
		$this->tags[] = $tag;
	}
	
	public function set_tags($tags)
	{
		$this->tags = $tags;
	}
	
	public function get_tags()
	{
		return $this->tags;
	}
	
	public function add_source($source)
	{
		$this->sources[] = $source;
	}
	
	public function set_sources($sources)
	{
		$this->sources = $sources;
	}
	
	public function get_sources()
	{
		return $this->sources;
	}
	
	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_cat' => $this->get_id_cat(),
			'title' => $this->get_title(),
			'contents' => $this->get_contents(),
			'extend_contents' => $this->get_extend_contents(),
			'approbation' => $this->get_approbation(),
			'start_date' => $this->get_start_date()->get_timestamp(),
			'end_date' => $this->get_start_date()->get_timestamp(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'user_id' => $this->get_user_id(),
			'picture_url' => $this->get_picture()->absolute(),
			'tags' => serialize($this->get_tags()),
			'sources' => serialize($this->get_sources())
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_id_cat($properties['id_cat']);
		$this->set_title($properties['title']);
		$this->set_contents($properties['contents']);
		$this->set_extend_contents($properties['extend_contents']);
		$this->set_approbation($properties['approbation']);
		$this->set_start_date(new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $properties['start_date']));
		$this->set_end_date(new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $properties['end_date']));
		$this->set_creation_date(new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $properties['creation_date']));
		$this->set_user_id($properties['user_id']);
		$this->set_picture_url(new Url($properties['picture_url']));
		$this->set_tags(!empty($properties['tags']) ? unserialize($properties['tags']) : array());
		$this->set_sources(!empty($properties['sources']) ? unserialize($properties['sources']) : array());
	}
}
?>