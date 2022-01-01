<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 5.0 - 2017 04 13
*/

abstract class HTMLTableDateComparatorSQLFilter extends AbstractHTMLTableFilter implements SQLFragmentBuilder
{
	private static $param_id_index = 0;

	private $db_field;

	public function __construct($db_field, $name, $label)
	{
		$this->db_field = $db_field;
		$form_field_class = $this->get_form_field_class();
		$field = new $form_field_class($name, $label);
		parent::__construct($name, $field);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_sql()
	{
		$parameter_name = $this->get_sql_value_parameter_prefix() . '_' . $this->db_field;
		$query = $this->db_field . ' ' . $this->get_sql_comparator_symbol() . ' :' . $parameter_name;
		$parameters = array($parameter_name => $this->get_value()->get_timestamp());
		return new SQLFragment($query, $parameters);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_value_allowed($value)
	{
		if (preg_match("/\d{4}\-\d{2}-\d{2}/", $value) || preg_match("/\d{2}\/\d{2}\/\d{4}/", $value))
		{
			$this->set_value(new Date($this->get_timestamp($value), Timezone::SERVER_TIMEZONE));
			return true;
		}
		return false;
	}

	protected function get_sql_value_parameter_prefix()
	{
		return __CLASS__ . '_' . self::$param_id_index++;
	}

	abstract protected function get_sql_comparator_symbol();

	abstract protected function get_form_field_class();

	protected function get_timestamp($value)
	{
		return strtotime($value);
	}
}

?>
