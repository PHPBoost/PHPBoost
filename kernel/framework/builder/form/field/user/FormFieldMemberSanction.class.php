<?php
/**
 * @package     Builder
 * @subpackage  Form\field\phpboost
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 22
 * @since       PHPBoost 3.0 - 2010 12 26
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldMemberSanction extends FormFieldSimpleSelectChoice
{
	private int $timestamp = 0;

    /**
     * Constructs a FormFieldMemberSanction.
     * @param string $id Field id
     * @param string $label Field label
     * @param int $value Default value
     * @param array $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
     * @param FormFieldConstraint List of the constraints
     */
    public function __construct(string $id, string $label, int $value = 0, array $field_options = [], array $constraints = [])
    {
		$this->timestamp = $value;
        parent::__construct($id, $label, $this->get_time_value(), $this->generate_options(), $field_options, $constraints);
    }

    private function generate_options(): mixed
	{
		$sanctions = self::get_sanctions_duration();
		$options = [];
		foreach ($sanctions as $duration => $name)
		{
			$options[] = new FormFieldSelectChoiceOption($name, $duration);
		}
		return $options;
	}

	private function get_time_value(): mixed
	{
		$difference = ($this->timestamp - time());

		$times = array_keys(self::get_sanctions_duration());
		if ($difference > 0)
		{
			for ($i = count($times) - 1; $i > 0; $i--)
			{
				$t = ceil(($times[$i] + $times[$i - 1]) /2);

				if ($difference >= $t)
				{
					return $times[$i];
				}
			}
			return 0;
		}
		return 0;
	}

	public static function get_sanctions_duration(): array
	{
		$lang = LangLoader::get_all_langs();

		return [
			'0' => $lang['common.no'],
			'60' => '1 ' . $lang['date.minute'],
			'300' => '5 ' . $lang['date.minutes'],
			'900' => '15 ' . $lang['date.minutes'],
			'1800' => '30 ' . $lang['date.minutes'],
			'3600' => '1 ' . $lang['date.hour'],
			'7200' => '2 ' . $lang['date.hours'],
			'86400' => '1 ' . $lang['date.day'],
			'172800' => '2 ' . $lang['date.days'],
			'604800' => '1 ' . $lang['date.week'],
			'1209600' => '2 ' . $lang['date.weeks'],
			'2419200' => '1 ' . $lang['date.month'],
			'5184000' => '2 ' . $lang['date.month'],
			'31557600' => '1 ' . $lang['date.year'],
			'326592000' => $lang['common.unlimited']
		];
	}
}
?>
