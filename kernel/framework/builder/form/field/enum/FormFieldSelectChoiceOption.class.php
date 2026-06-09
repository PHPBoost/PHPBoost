<?php
/**
 * This class manage select field options.
 * @package     Builder
 * @subpackage  Form\field\enum
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2009 04 28
 * @author      mipel <mipel@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldSelectChoiceOption extends AbstractFormFieldEnumOption
{
	/**
	 * @param $label string The label
	 * @param $raw_value string The raw value
	 * @param array $field_choice_options Map associating the parameters values to the parameters names.
	 */
	public function __construct($label, $raw_value, $field_choice_options = [])
	{
		parent::__construct($label, $raw_value, $field_choice_options);
	}

	/**
	 * @return string The html code for the select.
	 */
	public function display()
	{

		$tpl = new FileTemplate('framework/builder/form/fieldelements/FormFieldSelectChoiceOption.tpl');
		$tpl->put_all([
			'VALUE' => $this->get_raw_value(),
			'C_SELECTED' => $this->is_active(),
			'C_DISABLE' => $this->is_disable(),
			'C_OPTION_PICTURE' => !empty($this->get_data_option_img()),
			'U_OPTION_PICTURE' => Url::to_rel($this->get_data_option_img()),
			'C_OPTION_ICON' => !empty($this->get_data_option_icon()),
			'OPTION_ICON' => $this->get_data_option_icon(),
			'C_OPTION_CLASS' => !empty($this->get_data_option_class()),
			'OPTION_CLASS' => $this->get_data_option_class(),
			'LABEL' => $this->get_label(),
			'PROTECTED_LABEL' => stripslashes(TextHelper::strprotect($this->get_label()))
		]);

		return $tpl;
	}
}

?>
