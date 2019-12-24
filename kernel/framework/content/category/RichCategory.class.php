<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 24
 * @since       PHPBoost 4.0 - 2013 01 29
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class RichCategory extends Category
{
	public static function __static()
	{
		self::add_additional_attribute('description', array('type' => 'text', 'length' => 65000));
		self::add_additional_attribute('image', array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''", 'is_url' => true));
	}

	public function set_description($description)
	{
		$this->set_additional_property('description', $description);
	}

	public function get_description()
	{
		return $this->get_additional_property('description');
	}

	public function get_image()
	{
		if (!$this->get_additional_property('image') instanceof Url)
			return $this->get_default_image();

		return $this->get_additional_property('image');
	}

	public function get_default_image()
	{
		$file = new File(PATH_TO_ROOT . '/templates/' . AppContext::get_current_user()->get_theme() . '/images/default_category_thumbnail.png');
		if ($file->exists())
			return new Url('/templates/' . AppContext::get_current_user()->get_theme() . '/images/default_category_thumbnail.png');
		else
			return new Url('/templates/default/images/default_category_thumbnail.png');
	}
}
?>
