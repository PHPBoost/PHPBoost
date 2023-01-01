<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 24
 * @since       PHPBoost 5.2 - 2020 06 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PagesItem extends RichItem
{
	protected $summary_field_enabled = false;

	protected function set_additional_attributes_list()
	{
		$this->add_additional_attribute('i_order', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));

		$this->add_additional_attribute('author_display', array('type' => 'boolean', 'notnull' => 1, 'default' => 0, 'attribute_post_content_field_parameters' => array(
			'field_class' => 'FormFieldCheckbox',
			'label'       => LangLoader::get_message('form.display.author', 'form-lang')
			)
		));
	}

	protected function default_properties()
	{
		$this->set_additional_property('i_order', 0);
		$this->set_additional_property('author_display', 0);
	}

	protected function get_additional_template_vars()
	{
		return array(
			'C_AUTHOR_DISPLAYED' => $this->get_additional_property('author_display')
		);
	}
}
?>
