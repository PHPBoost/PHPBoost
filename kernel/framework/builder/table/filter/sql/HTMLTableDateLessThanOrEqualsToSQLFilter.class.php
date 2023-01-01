<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 05 13
 * @since       PHPBoost 5.0 - 2017 04 13
*/

class HTMLTableDateLessThanOrEqualsToSQLFilter extends HTMLTableDateComparatorSQLFilter
{
	protected function get_sql_comparator_symbol()
	{
		return '<=';
	}

	protected function get_form_field_class()
	{
		return 'FormFieldDate';
	}

	protected function get_timestamp($value)
	{
		// Include current day until end of the day
		return strtotime($value . ' +22 hours + 59 minutes');
	}
}

?>
