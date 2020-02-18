<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 18
 * @since       PHPBoost 4.0 - 2013 01 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

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

	protected $additional_attributes_values = array();
	protected static $additional_attributes_list = array();
	protected static $additional_attributes_categories_table_fields = array();
	protected static $additional_attributes_categories_table_options = array();

	const READ_AUTHORIZATIONS = 1;
	const WRITE_AUTHORIZATIONS = 2;
	const CONTRIBUTION_AUTHORIZATIONS = 4;
	const MODERATION_AUTHORIZATIONS = 8;
	const CATEGORIES_MANAGEMENT_AUTHORIZATIONS = 16;

	const ROOT_CATEGORY = '0';

	public static function __static() {}

	public function __construct()
	{
		self::__static();
	}

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

	protected static function add_additional_attribute($id, array $parameters)
	{
		if (isset($parameters['key']))
		{
			if ($parameters['key'] == true)
				self::$additional_attributes_categories_table_options[$id] = array('type' => 'key', 'fields' => $id);
			
			unset($parameters['key']);
		}
		
		self::$additional_attributes_list[$id] = array('is_url' => false);
		if (isset($parameters['is_url']))
		{
			self::$additional_attributes_list[$id]['is_url'] = $parameters['is_url'];
			
			unset($parameters['is_url']);
		}
		if (isset($parameters['attribute_field_parameters']))
		{
			self::$additional_attributes_list[$id]['attribute_field_parameters'] = $parameters['attribute_field_parameters'];
			
			unset($parameters['attribute_field_parameters']);
		}
		
		self::$additional_attributes_categories_table_fields[$id] = $parameters;
	}

	public function get_additional_attributes_list()
	{
		return self::$additional_attributes_list;
	}

	public function get_properties()
	{
		return array_merge(array(
			'id' => $this->get_id(),
			'name' => TextHelper::htmlspecialchars($this->get_name()),
			'rewrited_name' => TextHelper::htmlspecialchars($this->get_rewrited_name()),
			'c_order' => $this->get_order(),
			'special_authorizations' => (int)$this->has_special_authorizations(),
			'auth' => !$this->auth_is_empty() ? TextHelper::serialize($this->get_authorizations()) : '',
			'id_parent' => $this->get_id_parent()
		), $this->get_additional_properties());
	}

	protected function get_additional_properties()
	{
		$properties = array();
		
		foreach (self::$additional_attributes_list as $id => $attribute)
		{
			if ($attribute['is_url'])
			{
				if ($this->additional_attributes_values[$id] instanceof Url)
					$properties[$id] = $this->additional_attributes_values[$id]->relative();
				else
				{
					$value = new Url($this->additional_attributes_values[$id]);
					$properties[$id] = $value->relative();
				}
			}
			else
				$properties[$id] = $this->additional_attributes_values[$id];
		}
		
		return $properties;
	}

	public function get_additional_property($id)
	{
		return $this->additional_attributes_values[$id];
	}

	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_name($properties['name']);
		$this->set_rewrited_name($properties['rewrited_name']);
		$this->set_order($properties['c_order']);
		$this->set_special_authorizations($properties['special_authorizations']);
		$this->set_authorizations(!empty($properties['auth']) ? TextHelper::unserialize($properties['auth']) : array());
		$this->set_id_parent($properties['id_parent']);
		$this->set_additional_properties($properties);
	}

	protected function set_additional_properties(array $properties)
	{
		foreach (self::$additional_attributes_list as $id => $attribute)
		{
			if (isset($properties[$id]))
			{
				$this->set_additional_property($id, $properties[$id], $attribute['is_url']);
			}
		}
	}

	public function set_additional_property($id, $value, $is_url = false)
	{
		if ($is_url)
			$this->additional_attributes_values[$id] = new Url($value);
		else
			$this->additional_attributes_values[$id] = $value;
	}

	public static function create_categories_table($table_name)
	{
		$fields = array_merge(array(
			'id'                     => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name'                   => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_name'          => array('type' => 'string', 'length' => 250, 'default' => "''"),
			'c_order'                => array('type' => 'integer', 'length' => 11, 'unsigned' => 1, 'notnull' => 1, 'default' => 0),
			'special_authorizations' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'auth'                   => array('type' => 'text', 'length' => 65000),
			'id_parent'              => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		), self::$additional_attributes_categories_table_fields);

		$options = array_merge(array(
			'primary'   => array('id'),
			'id_parent' => array('type' => 'key', 'fields' => 'id_parent')
		), self::$additional_attributes_categories_table_options);

		PersistenceContext::get_dbms_utils()->create_table($table_name, $fields, $options);
	}
}
?>
