<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 22
 * @since       PHPBoost 4.0 - 2013 02 25
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarCategory extends Category
{
	protected function set_additional_attributes_list()
	{
		$this->add_additional_attribute('color', array('type' => 'string', 'length' => 250, 'default' => "''", 'attribute_field_parameters' => array(
			'field_class'   => 'FormFieldColorPicker',
			'label'         => LangLoader::get_message('calendar.category.color', 'common', 'calendar'),
			'default_value' => CalendarConfig::load()->get_event_color()
			)
		));
	}

	public function get_color()
	{
		return $this->get_additional_property('color');
	}
}
?>
