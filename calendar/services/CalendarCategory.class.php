<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 17
 * @since       PHPBoost 4.0 - 2013 02 25
*/

class CalendarCategory extends Category
{
	public static function __static()
	{
		parent::__static();
		self::add_additional_attribute('color', array('type' => 'string', 'length' => 250, 'default' => "''", 'attribute_field_parameters' => array(
			'field_class' => 'FormFieldColorPicker',
			'label' => LangLoader::get_message('calendar.config.category.color', 'common', 'calendar')
			)
		));
	}

	public function get_color()
	{
		return $this->get_additional_property('color');
	}
}
?>
