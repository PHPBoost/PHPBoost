<?php
/*##################################################
 *                        ForumCategory.class.php
 *                            -------------------
 *   begin                : May 15, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
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

class ForumCategory extends Category
{
	private $type;
	private $status;
	private $description;
	private $last_topic_id;
	private $url;
	
	const TYPE_CATEGORY = 0;
	const TYPE_FORUM = 1;
	const TYPE_URL = 2;
	
	const STATUS_UNLOCKED = 0;
	const STATUS_LOCKED = 1;
	
	public function __construct()
	{
		$this->type = self::TYPE_CATEGORY;
		$this->status = self::STATUS_UNLOCKED;
		$this->last_topic_id = 0;
	}
	
	public function set_type($type)
	{
		$this->type = $type;
	}
	
	public function get_type()
	{
		return $this->type;
	}
	
	public function set_status($status)
	{
		$this->status = $status;
	}
	
	public function get_status()
	{
		return $this->status;
	}
	
	public function set_description($description)
	{
		$this->description = $description;
	}
	
	public function get_description()
	{
		return $this->description;
	}
	
	public function set_last_topic_id($last_topic_id)
	{
		$this->last_topic_id = $last_topic_id;
	}
	
	public function get_last_topic_id()
	{
		return $this->last_topic_id;
	}
	
	public function set_url($url)
	{
		$this->url = $url;
	}
	
	public function get_url()
	{
		return $this->url;
	}
	
	public function get_properties()
	{
		return array_merge(parent::get_properties(), array(
			'status' => $this->get_status(),
			'description' => $this->get_description(),
			'last_topic_id' => $this->get_last_topic_id(),
			'url' => $this->get_url()
		));
	}
 
	public function set_properties(array $properties)
	{
		if (!empty($properties['url']))
			$this->set_type(self::TYPE_URL);
		else if ($properties['id_parent'] != Category::ROOT_CATEGORY)
			$this->set_type(self::TYPE_FORUM);
		else
			$this->set_type(self::TYPE_CATEGORY);
		
		parent::set_properties($properties);
		$this->set_status($properties['status']);
		$this->set_description($properties['description']);
		$this->set_last_topic_id($properties['last_topic_id']);
		$this->set_url($properties['url']);
	}
	
	public static function create_categories_table($table_name)
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_name' => array('type' => 'string', 'length' => 250, 'default' => "''"),
			'description' => array('type' => 'text', 'length' => 65000),
			'c_order' => array('type' => 'integer', 'length' => 11, 'unsigned' => 1, 'notnull' => 1, 'default' => 0),
			'status' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'special_authorizations' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_topic_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'url' => array('type' => 'string', 'length' => 255, 'default' => "''")
		);
		$options = array(
			'primary' => array('id'),
			'last_topic_id' => array('type' => 'key', 'fields' => 'last_topic_id'),
			'id_parent' => array('type' => 'key', 'fields' => 'id_parent')
		);
		PersistenceContext::get_dbms_utils()->create_table($table_name, $fields, $options);
	}
}
?>
