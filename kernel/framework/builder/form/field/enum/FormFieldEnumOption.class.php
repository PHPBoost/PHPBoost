<?php
/**
 * @package     Builder
 * @subpackage  Form\field\enum
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 26
 * @since       PHPBoost 3.0 - 2010 01 10
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface FormFieldEnumOption
{
	function display(): Template;

	function get_label(): string;

	function set_label(string $label);

	function get_raw_value(): mixed;

	function set_raw_value(mixed $raw_value);

	function get_field(): FormField;

	function set_field(FormField $field): void;

	function get_option(mixed $raw_option): mixed;
}

?>
