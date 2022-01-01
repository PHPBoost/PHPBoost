<?php
/**
 * @package     Builder
 * @subpackage  Form\constraint
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2009 12 09
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

interface FormConstraint
{
	function validate();

	function get_js_validation();

	function get_related_fields();
}

?>
