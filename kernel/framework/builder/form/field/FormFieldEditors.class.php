<?php
/**
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 06 03
 * @since       PHPBoost 3.0 - 2011 09 26
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldEditors extends FormFieldSimpleSelectChoice
{
    /**
     * Constructs a FormFieldEditors.
     * @param string $id Field id
     * @param string $label Field label
     * @param mixed $value Default value (either a FormFieldEnumOption object or a string corresponding to the FormFieldEnumOption's raw value)
     * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
     * @param FormFieldConstraint List of the constraints
     */
    public function __construct($id, $label, $value = 0, array $field_options = array(), array $constraints = array())
    {
        parent::__construct($id, $label, $value, $this->generate_options(), $field_options, $constraints);
    }

    private function generate_options()
	{
		$options = array();
		foreach (AppContext::get_content_formatting_service()->get_available_editors() as $id => $name)
		{
			$options[] = new FormFieldSelectChoiceOption($name, $id);
		}
		return $options;
	}
}
?>
