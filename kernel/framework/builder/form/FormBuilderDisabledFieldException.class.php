<?php
/**
 * @package     Builder
 * @subpackage  Form
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 04 10
 * @contributor Kevin MASSY <reidlos@phpboost.com>
*/

class FormBuilderDisabledFieldException extends FormBuilderException
{
	private $value;

	public function __construct($field, $value)
	{
		parent::__construct('You cannot retrieve the value of the field ' . $field . ' which is disabled.');
		$this->value = $value;
	}

	public function get_value()
	{
		return $this->value;
	}
}
?>
