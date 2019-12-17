<?php
/**
 * @package     Builder
 * @subpackage  Table\filter
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 04 14
 * @since       PHPBoost 3.0 - 2010 02 25
*/

abstract class HTMLTableEqualsFromListFilter extends AbstractHTMLTableFilter
{
	private $allowed_values;
	private $options;

	public function __construct($name, $label, array $allowed_values)
	{
		$this->allowed_values = array_keys($allowed_values);
		$default_value = new FormFieldSelectChoiceOption(LangLoader::get_message('all', 'common'), '');
		$this->options = array($default_value);
		foreach ($allowed_values as $option_value => $option_label)
		{
			$this->options[] = new FormFieldSelectChoiceOption($option_label, $option_value);
		}
		$select = new FormFieldSimpleSelectChoice($name, $label, $default_value, array_values($this->options));
		parent::__construct($name, $select);
	}

	public function is_value_allowed($value)
	{
		if (in_array($value, $this->allowed_values))
		{
			$this->set_value($value);
			return true;
		}
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function set_value($value)
	{
		foreach ($this->options as $option)
		{
			if ($option->get_raw_value() === $value)
			{
				parent::set_value($option);
				break;
			}
		}
	}
}

?>
