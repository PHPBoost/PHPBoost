<?php
/**
 * This class manage select field options.
 * @package     Builder
 * @subpackage  Form\field\enum
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 03 10
 * @since       PHPBoost 3.0 - 2009 04 28
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldSelectChoiceOption extends AbstractFormFieldEnumOption
{
	/**
	 * @param $label string The label
	 * @param $raw_value string The raw value
	 * @param string[] $field_choice_options Map associating the parameters values to the parameters names.
	 */
	public function __construct($label, $raw_value, $field_choice_options = array())
	{
		parent::__construct($label, $raw_value, $field_choice_options);
	}

	/**
	 * @return string The html code for the select.
	 */
	public function display()
	{

		$tpl = new FileTemplate('framework/builder/form/fieldelements/FormFieldSelectChoiceOption.tpl');
		$tpl->put_all(array(
			'VALUE' => $this->get_raw_value(),
			'C_SELECTED' => $this->is_active(),
			'C_DISABLE' => $this->is_disable(),
			'LABEL' => $this->get_label()
		));

		return $tpl;
	}
}

?>
