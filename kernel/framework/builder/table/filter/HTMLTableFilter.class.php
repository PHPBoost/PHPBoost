<?php
/**
 * @package     Builder
 * @subpackage  Table\filter
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 3.0 - 2005 12 22
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface HTMLTableFilter
{
	function get_id(): mixed;

	function get_form_field(): mixed;

	function is_value_allowed(mixed $value): bool;
}

?>
