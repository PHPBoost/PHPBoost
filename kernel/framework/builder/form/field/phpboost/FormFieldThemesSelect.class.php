<?php
/**
 * @package     Builder
 * @subpackage  Form\field\phpboost
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 02 09
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldThemesSelect extends FormFieldSimpleSelectChoice
{
	private $check_authorizations = true;

    /**
     * Constructs a FormFieldThemesSelect.
     * @param string $id Field id
     * @param string $label Field label
     * @param mixed $value Default value (either a FormFieldEnumOption object or a string corresponding to the FormFieldEnumOption's raw value)
     * @param array $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
     * @param FormFieldConstraint List of the constraints
     */
    public function __construct($id, $label, $value = 0, $field_options = [], array $constraints = [])
    {
        parent::__construct($id, $label, $value, $this->generate_options(), $field_options, $constraints);
    }

    private function generate_options()
	{
		$options = [];
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
