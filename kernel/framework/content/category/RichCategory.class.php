<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 23
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

	public static function get_additional_properties()
	{
		return array_merge(array(
			'description' => $this->get_description(),
			'image'       => $this->get_image()->relative()
		), self::get_rich_additional_properties());
	}
	
	public static function get_rich_additional_properties()
	{
		return array();
	}

	public function set_additional_properties(array $properties)
	{
		$this->set_description($properties['description']);
		$this->set_image(new Url($properties['image']));
		self::set_rich_additional_properties($properties);
	}
	
	public static function set_rich_additional_properties(array $properties) {}

	public static function get_categories_table_additional_fields()
	{
		return array_merge(array(
			'description' => array('type' => 'text', 'length' => 65000),
			'image'       => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''")
		), self::get_categories_table_rich_additional_fields());
	}

	public static function get_categories_table_rich_additional_fields()
	{
		return array();
	}
}
?>
