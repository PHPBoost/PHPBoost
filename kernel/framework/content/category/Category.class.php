<?php
/*##################################################
 *                          Category.class.php
 *                            -------------------
 *   begin                : January 29, 2013
 *   copyright            : (C) 2013 Kévin MASSY
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

class Category
{
	protected $id;
	protected $name;
	protected $rewrited_name;
	protected $order;
	protected $special_authorizations = false;
	protected $auth = array();
	protected $id_parent;
	protected $elements_number;
	protected $allowed_to_have_childs = true;
	
	const READ_AUTHORIZATIONS = 1;
	const WRITE_AUTHORIZATIONS = 2;
	const CONTRIBUTION_AUTHORIZATIONS = 4;
	const MODERATION_AUTHORIZATIONS = 8;
	const CATEGORIES_MANAGEMENT_AUTHORIZATIONS = 16;
	
	const ROOT_CATEGORY = '0';
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_name()
	{
		return $this->name;
	}
	
	public function set_name($name)
	{
		$this->name = $name;
	}
	
	public function get_rewrited_name()
	{
		return $this->rewrited_name;
	}
	
	public function set_rewrited_name($rewrited_name)
	{
		$this->rewrited_name = $rewrited_name;
	}
	
	public function rewrited_name_is_personalized()
	{
		return $this->rewrited_name != Url::encode_rewrite($this->name);
	}
	
	public function get_order()
	{
		return $this->order;
	}
	
	public function set_order($order)
	{
		$this->order = $order;
	}
	
	public function incremente_order()
	{
		$this->order++;
	}
	
	public function has_special_authorizations()
	{
		return $this->special_authorizations;
	}
	
	public function set_special_authorizations($special_authorizations)
	{
		$this->special_authorizations = (bool)$special_authorizations;
	}
	
	public function get_authorizations()
	{
		return $this->auth;
	}
	
	public function set_authorizations(array $auth)
	{
		$this->auth = $auth;
	}
	
	public function auth_is_empty()
	{
		return empty($this->auth);
	}
	
	public function auth_is_equals(Array $auth)
	{
		return $this->auth === $auth;
	}
	
	public function get_id_parent()
	{
		return $this->id_parent;
	}
	
	public function set_id_parent($id_parent)
	{
		$this->id_parent = $id_parent;
	}
	
	public function get_elements_number()
	{
		return $this->elements_number;
	}
	
	public function set_elements_number($elements_number)
	{
		$this->elements_number = $elements_number;
	}
	
	public function is_allowed_to_have_childs()
	{
		return $this->allowed_to_have_childs;
	}
	
	public function check_auth($bit)
	{
		return AppContext::get_current_user()->check_auth($this->auth, $bit);
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'name' => TextHelper::htmlspecialchars($this->get_name()),
			'rewrited_name' => TextHelper::htmlspecialchars($this->get_rewrited_name()),
			'c_order' => $this->get_order(),
			'special_authorizations' => (int)$this->has_special_authorizations(),
			'auth' => !$this->auth_is_empty() ? serialize($this->get_authorizations()) : '',
			'id_parent' => $this->get_id_parent()
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_name($properties['name']);
		$this->set_rewrited_name($properties['rewrited_name']);
		$this->set_order($properties['c_order']);
		$this->set_special_authorizations($properties['special_authorizations']);
		$this->set_authorizations(!empty($properties['auth']) ? unserialize($properties['auth']) : array());
		$this->set_id_parent($properties['id_parent']);
	}
	
	public static function create_categories_table($table_name)
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_name' => array('type' => 'string', 'length' => 250, 'default' => "''"),
			'c_order' => array('type' => 'integer', 'length' => 11, 'unsigned' => 1, 'notnull' => 1, 'default' => 0),
			'special_authorizations' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
		);

		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table($table_name, $fields, $options);
	}
}
?>