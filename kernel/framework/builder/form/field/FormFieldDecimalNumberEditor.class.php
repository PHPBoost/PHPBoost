<?php
/**
 * This class manages a decimal number.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 22
 * @since       PHPBoost 4.1 - 2015 06 01
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldDecimalNumberEditor extends FormFieldNumberEditor
{
	/**
	 * Constructs a FormFieldDecimalNumberEditor.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param mixed $value Default value
	 * @param array $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct(string $id, string $label, mixed $value, array $field_options = [], array $constraints = [])
	{
		$this->pattern = '`^[0-9]+([\.|,][0-9]{1,2})?$`iu';
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-decimal');
	}
}
?>
