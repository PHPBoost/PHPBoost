<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.0 - 2017 04 13
*/

class HTMLTableDateGreaterThanOrEqualsToSQLFilter extends HTMLTableDateComparatorSQLFilter
{
	protected function get_sql_comparator_symbol()
	{
		return '>=';
	}

	protected function get_form_field_class()
	{
		return 'FormFieldDate';
	}
}

?>
