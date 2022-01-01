<?php
/**
 * @package     Builder
 * @subpackage  Table\filter
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 07 31
 * @since       PHPBoost 5.0 - 2017 04 18
*/

abstract class HTMLTableCheckboxFilter extends AbstractHTMLTableFilter
{
	public function __construct($name, $label, $checked = FormFieldCheckbox::UNCHECKED)
	{
		$input = new FormFieldCheckbox($name, $label, $checked);
		parent::__construct($name, $input);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_value_allowed($value)
	{
		return true;
	}
}

?>
