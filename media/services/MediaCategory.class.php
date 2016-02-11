<?php
/*##################################################
 *                        MediaCategory.class.php
 *                            -------------------
 *   begin                : February 4, 2015
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

class MediaCategory extends RichCategory
{
	private $content_type;
	
	public function set_content_type($content_type)
	{
		$this->content_type = $content_type;
	}
	
	public function get_content_type()
	{
		return $this->content_type;
	}
	
	public function get_properties()
	{
		return array_merge(parent::get_properties(), array('content_type' => $this->get_content_type()));
	}
 
	public function set_properties(array $properties)
	{
		parent::set_properties($properties);
		$this->set_content_type($properties['content_type']);
	}
	
	public static function create_categories_table($table_name)
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_name' => array('type' => 'string', 'length' => 250, 'default' => "''"),
			'description' => array('type' => 'text', 'length' => 65000),
			'c_order' => array('type' => 'integer', 'length' => 11, 'unsigned' => 1, 'notnull' => 1, 'default' => 0),
			'special_authorizations' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000),
			'image' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'content_type' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0)
		);
		
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table($table_name, $fields, $options);
	}
}
?>
