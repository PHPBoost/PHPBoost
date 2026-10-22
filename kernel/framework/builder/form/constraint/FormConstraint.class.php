<?php
/**
 * @package     Builder
 * @subpackage  Form\constraint
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 3.0 - 2009 12 09
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface FormConstraint
{
	function validate(): mixed;

	function get_js_validation(): mixed;

	function get_validation_error_message(): mixed;

	function get_related_fields(): mixed;
}

?>
