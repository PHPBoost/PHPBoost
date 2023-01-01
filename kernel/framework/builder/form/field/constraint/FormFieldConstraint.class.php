<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2009 12 19
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Benoit SAUTEL <ben.popeye@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

interface FormFieldConstraint
{
	function validate(FormField $field);

	function get_js_validation(FormField $field);

	function get_validation_error_message();

	function set_validation_error_message($error_message);
}

?>
