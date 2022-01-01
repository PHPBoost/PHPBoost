<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 27
*/

class HTMLTableLikeFromListSQLFilter extends HTMLTableEqualsFromListFilter implements SQLFragmentBuilder
{
	private static $param_id_index = 0;

	private $db_field;

	public function __construct($db_field, $name, $label, array $allowed_values)
	{
		$this->db_field = $db_field;
		parent::__construct($name, $label, $allowed_values);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_sql()
	{
		$choice_option = $this->get_value();
		if ($choice_option instanceof FormFieldSelectChoiceOption)
		{
			$parameter_name = $this->get_sql_value_parameter_prefix() . '_' . $this->db_field;
			$query = $this->db_field . 'LIKE :' . $parameter_name;
			$parameters = array($parameter_name => $this->get_like_value());
			return new SQLFragment($query, $parameters);
		}
		return new SQLFragment();
	}

	protected function get_like_value()
	{
		return $this->get_value()->get_raw_value();
	}

    protected function get_sql_value_parameter_prefix()
    {
        return __CLASS__ . '_' . self::$param_id_index++;
    }
}

?>
