<?php
/**
 * This class manage radio input field options.
 * @package     Builder
 * @subpackage  Form\field\enum
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2009 05 01
 * @author      mipel <mipel@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldRadioChoiceOption extends AbstractFormFieldEnumOption
{
	public function __construct($label, $raw_value, $field_choice_options = [])
	{
		parent::__construct($label, $raw_value, $field_choice_options);
	}

	/**
	 * @return string The html code for the radio input.
	 */
	public function display()
	{

		$tpl = new FileTemplate('framework/builder/form/fieldelements/FormFieldRadioChoiceOption.tpl');

		$tpl->put_all([
			'ID' => $this->get_option_id(),
			'NAME' => $this->get_field_id(),
			'VALUE' => $this->get_raw_value(),
			'C_CHECKED' => $this->is_active(),
			'C_DISABLE' => $this->is_disable(),
			'LABEL' => $this->get_label()
		]);

		return $tpl;
	}
}

?>
