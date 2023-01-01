<?php
/**
 * @package     Builder
 * @subpackage  Form\field\enum
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 01 10
*/

interface FormFieldEnumOption
{
	/**
	 * @return Template
	 */
	function display();

	function get_label();

	function set_label($label);

	function get_raw_value();

	function set_raw_value($raw_value);

	function get_field();

	function set_field(FormField $field);

	function get_option($raw_option);
}

?>
