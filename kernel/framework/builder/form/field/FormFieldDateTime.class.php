<?php
/**
 * This class is able to retrieve a date and a hour (hours & minutes).
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 24
 * @since       PHPBoost 3.0 - 2010 01 24
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldDateTime extends FormFieldDate
{
	/**
	 * @var boolean
	 */
	private $five_minutes_step = false;

	public function __construct($id, $label, Date $value = null, array $field_options = array(), array $constraints = array())
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
			'C_HOUR'              => true,
			'C_FIVE_MINUTES_STEP' => $this->five_minutes_step,

			'HOURS'   => $this->get_value() ? $this->get_value()->get_hours()   : '0',
			'MINUTES' => $this->get_value() ? $this->get_value()->get_minutes() : '00',

			'L_AT' => LangLoader::get_message('common.on.date', 'common-lang'),
			'L_H'  => LangLoader::get_message('date.unit.hour', 'date-lang'),
			'L_MN' => LangLoader::get_message('date.unit.minute', 'date-lang')
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

	protected function compute_options(array &$field_options)
	{
		foreach ($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'five_minutes_step':
					$this->five_minutes_step = (bool)$value;
					unset($field_options['five_minutes_step']);
					break;
			}
		}
		parent::compute_options($field_options);
	}
}
?>
