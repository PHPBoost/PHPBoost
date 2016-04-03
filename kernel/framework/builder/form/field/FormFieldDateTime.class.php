<?php
/*##################################################
 *                        FormFieldDateTime.class.php
 *                            -------------------
 *   begin                : January 24, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @desc This class is able to retrieve a date and a hour (hours & minutes).
 * @package {@package}
 */
class FormFieldDateTime extends FormFieldDate
{
	public function __construct($id, $label, Date $value = null, $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-datetime');
	}

	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = parent::display();

		$template->put_all(array(
			'C_HOUR' => true,
			'HOURS' => $this->get_value() ? $this->get_value()->get_hours() : '0',
			'MINUTES' => $this->get_value() ? $this->get_value()->get_minutes() : '00',
			'L_AT' => LangLoader::get_message('at', 'main'),
			'L_H' => LangLoader::get_message('unit.hour', 'date-common')
		));

		return $template;
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve_value()
	{
		parent::retrieve_value();

		$request = AppContext::get_request();
		$date = $this->get_value();
		
		if ($date !== null)
		{
			$date->set_minutes($request->get_int($this->get_html_id() . '_minutes', 0));
			$date->set_hours($request->get_int($this->get_html_id() . '_hours', 0));
		}
		
		$this->set_value($date);
	}
}
?>