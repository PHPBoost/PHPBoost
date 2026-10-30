<?php
/**
 * @package     Builder
 * @subpackage  Form\field\phpboost
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 30
 * @since       PHPBoost 3.0 - 2010 12 26
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldTimezone extends FormFieldSimpleSelectChoice
{
    /**
     * Constructs a FormFieldTimezone.
     * @param string $id Field id
     * @param string $label Field label
     * @param string $value Default timezone identifier
     * @param array $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
     * @param FormFieldConstraint List of the constraints
     */
    public function __construct(string $id, string $label, string $value = '', array $field_options = [], array $constraints = [])
    {
        parent::__construct($id, $label, $value, $this->generate_options(), $field_options, $constraints);
    }

    private function generate_options(): mixed
	{
		$options = [];
		foreach (DateTimeZone::listIdentifiers() as $timezone)
		{
			$options[] = new FormFieldSelectChoiceOption($timezone, $timezone);
		}
		return $options;
	}

}

?>
