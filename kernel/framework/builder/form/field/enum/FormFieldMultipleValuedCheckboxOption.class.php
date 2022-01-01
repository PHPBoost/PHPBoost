<?php
/**
 * @package     Builder
 * @subpackage  Form\field\enum
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2010 11 20
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldMultipleValuedCheckboxOption extends FormFieldMultipleCheckboxOption
{
	private $value;

	public function __construct($id, $label, $value)
	{
		$this->value = $value;
		parent::__construct($id, $label);
	}

	public function get_value()
	{
		return $this->value;
	}
}

?>
