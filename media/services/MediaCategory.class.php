<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 22 11
 * @since       PHPBoost 4.0 - 2015 02 04
*/

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
