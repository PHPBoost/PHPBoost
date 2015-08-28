<?php
/*##################################################
 *                             FormFieldDate.class.php
 *                            -------------------
 *   begin                : September 19, 2009
 *   copyright            : (C) 2009 Viarre Régis
 *   email                : crowkait@phpboost.com
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

/**
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class embeds a calendar
 * @package {@package}
 */
class FormFieldDate extends AbstractFormField
{
	public function __construct($id, $label, Date $value = null, $field_options = array(), array $constraints = array())
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