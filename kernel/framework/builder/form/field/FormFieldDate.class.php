<?php
/**
 * This class embeds a calendar
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 11 19
 * @since       PHPBoost 3.0 - 2009 09 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldDate extends AbstractFormField
{
	public function __construct($id, $label, Date $value = null, array $field_options = array(), array $constraints = array())
	{
		$constraints[] = new FormFieldConstraintDate();
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-date');
	}

	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		$template->put_all(array(
			'CALENDAR' => $this->get_calendar()->display()
		));

		return $template;
	}

	public function retrieve_value()
	{
		$this->set_value(MiniCalendar::retrieve_date($this->get_html_id()));
	}

	/**
	 * @return MiniCalendar
	 */
	private function get_calendar()
	{
		return new MiniCalendar($this->get_html_id(), $this->get_value());
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormFieldDate.tpl');
	}
}
?>
