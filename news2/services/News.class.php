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
	private $rewrited_title;
	private $contents;
	private $short_contents;
	private $short_contents_enabled;
	
	private $approbation_type;
	private $start_date;
	private $end_date;
	
	private $creation_date;
	private $author_user_id;

	private $picture_url;
	private $sources;
	
	const NOT_APPROVAL = 0;
	const APPROVAL_NOW = 1;
	const APPROVAL_DATE = 2;
	
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
	
	public function set_rewrited_title($rewrited_title)
	{
		$this->rewrited_title = $rewrited_title;
	}
	
	public function get_rewrited_title()
	{
		return $this->rewrited_title;
	}
	
	public function set_contents($contents)
	{
		$this->contents = $contents;
	}
	
	public function get_contents()
	{
		return $this->contents;
	}
	
	public function set_short_contents($short_contents)
	{
		$this->short_contents = $short_contents;
	}
	
	public function get_short_contents()
	{
		return $this->short_contents;
	}
	
	public function set_short_contents_enabled($short_contents_enabled)
	{
		$this->short_contents_enabled = $short_contents_enabled;
	}
	
	public function get_short_contents_enabled()
	{
		return $this->short_contents_enabled;
	}
	
	public function set_approbation_type($approbation_type)
	{
		$this->approbation_type = $approbation_type;
	}
	
	public function get_approbation_type()
	{
		return $this->approbation_type;
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
	
	public function set_author_user_id($author_user_id)
	{
		$this->author_user_id = $author_user_id;
	}
	
	public function get_author_user_id()
	{
		return $this->author_user_id;
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
			'rewrited_title' => $this->get_rewrited_title(),
			'contents' => $this->get_contents(),
			'short_contents' => $this->get_short_contents(),
			'short_contents_enabled' => (int)$this->get_short_contents_enabled(),
			'approbation_type' => $this->get_approbation_type(),
			'start_date' => $this->get_start_date()->get_timestamp(),
			'end_date' => $this->get_start_date()->get_timestamp(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'author_user_id' => $this->get_author_user_id(),
			'picture_url' => $this->get_picture()->absolute(),
			'sources' => serialize($this->get_sources())
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_id_cat($properties['id_cat']);
		$this->set_title($properties['title']);
		$this->set_rewrited_title($properties['rewrited_title']);
		$this->set_contents($properties['contents']);
		$this->set_short_contents($properties['short_contents']);
		$this->set_short_contents_enabled((bool)$properties['short_contents_enabled']);
		$this->set_approbation_type($properties['approbation_type']);
		$this->set_start_date(new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $properties['start_date']));
		$this->set_end_date(new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $properties['end_date']));
		$this->set_creation_date(new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $properties['creation_date']));
		$this->set_author_user_id($properties['author_user_id']);
		$this->set_picture_url(new Url($properties['picture_url']));
		$this->set_sources(!empty($properties['sources']) ? unserialize($properties['sources']) : array());
	}
}
?>