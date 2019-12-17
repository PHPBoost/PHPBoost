<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 20
 * @since       PHPBoost 4.0 - 2013 01 29
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class RichCategory extends Category
{
	protected $description;
	protected $image;

	public function set_description($description)
	{
		$this->description = $description;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_image(Url $image)
	{
		$this->image = $image;
	}

	public function get_image()
	{
		if (!$this->image instanceof Url)
			return $this->get_default_image();

		return $this->image;
	}

	public function get_default_image()
	{
		$file = new File(PATH_TO_ROOT . '/templates/' . AppContext::get_current_user()->get_theme() . '/images/default_category_thumbnail.png');
		if ($file->exists())
			return new Url('/templates/' . AppContext::get_current_user()->get_theme() . '/images/default_category_thumbnail.png');
		else
			return new Url('/templates/default/images/default_category_thumbnail.png');
	}

	public function get_properties()
	{
		return array_merge(parent::get_properties(), array(
			'description' => $this->get_description(),
			'image' => $this->get_image()->relative()
		));
	}

	public function set_properties(array $properties)
	{
		parent::set_properties($properties);
		$this->set_description($properties['description']);
		$this->set_image(new Url($properties['image']));
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
		);

		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table($table_name, $fields, $options);
	}
}
?>
