<?php
/**
 * @package     Builder
 * @subpackage  Form\field\phpboost
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 09 16
*/

class FormFieldGroups extends FormFieldMultipleSelectChoice
{
    /**
     * Constructs a FormFieldGroups.
     * @param string $id Field id
     * @param string $label Field label
     * @param mixed $value Default value (either a FormFieldEnumOption object or a string corresponding to the FormFieldEnumOption's raw value)
     * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
     * @param FormFieldConstraint List of the constraints
     */
    public function __construct($id, $label, $value = 0, $field_options = array(), array $constraints = array())
    {
        parent::__construct($id, $label, $value, $this->generate_options(), $field_options, $constraints);
    }

    private function generate_options()
	{
		$groups = GroupsCache::load()->get_groups();
		$options = array();
		foreach ($groups as $id => $informations)
		{
			$options[] = new FormFieldSelectChoiceOption($informations['name'], $id);
		}
		return $options;
	}
}
?>
