<?php
/**
 * @package     Builder
 * @subpackage  Form\field\phpboost
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 04 18
 * @since       PHPBoost 3.0 - 2011 09 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldLangsSelect extends FormFieldSimpleSelectChoice
{
	private $check_authorizations = false;

    /**
     * Constructs a FormFieldLangsSelect.
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
		$options = array();
		foreach (LangsManager::get_activated_langs_map_sorted_by_localized_name() as $lang)
		{
			if ($this->check_authorizations)
			{
				if ($lang->check_auth())
				{
					$options[] = new FormFieldSelectChoiceOption($lang->get_configuration()->get_name(), $lang->get_id());
				}
			}
			else
			{
				$options[] = new FormFieldSelectChoiceOption($lang->get_configuration()->get_name(), $lang->get_id());
			}
		}
		return $options;
	}

	protected function compute_options(array &$field_options)
    {
        foreach ($field_options as $attribute => $value)
        {
            $attribute = TextHelper::strtolower($attribute);
            switch ($attribute)
            {
				case 'check_authorizations' :
                    $this->check_authorizations = (bool)$value;
                    unset($field_options['check_authorizations']);
                    break;
            }
        }
        parent::compute_options($field_options);
    }
}
?>
