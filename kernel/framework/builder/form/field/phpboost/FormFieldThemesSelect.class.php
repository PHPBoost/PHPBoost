<?php
/**
 * @package     Builder
 * @subpackage  Form\field\phpboost
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 04 18
 * @since       PHPBoost 3.0 - 2012 02 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldThemesSelect extends FormFieldSimpleSelectChoice
{
	private $check_authorizations = true;

    /**
     * Constructs a FormFieldThemesSelect.
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
		foreach (ThemesManager::get_activated_themes_map_sorted_by_localized_name() as $theme)
		{
			if ($this->check_authorizations)
			{
				if ($theme->check_auth())
				{
					$options[] = new FormFieldSelectChoiceOption($theme->get_configuration()->get_name(), $theme->get_id());
				}
			}
			else
			{
				$options[] = new FormFieldSelectChoiceOption($theme->get_configuration()->get_name(), $theme->get_id());
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
