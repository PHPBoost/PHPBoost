<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 20
 * @since       PHPBoost 4.0 - 2014 05 09
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuestionCaptchaItem
{
	private $label;
	private $answers;

	public function set_label($value)
	{
		$this->label = $value;
	}

	public function get_label()
	{
		return $this->label;
	}

	public function set_answers(Array $array)
	{
		$this->answers = $array;
	}

	public function get_answers()
	{
		return $this->answers;
	}

	public function get_formated_answers()
	{
		$answers = array();

		foreach ($this->answers as $answer)
		{
			if (!empty($answer))
				$answers[] = trim(TextHelper::strtolower($answer));
		}

		return $answers;
	}

	public function get_properties()
	{
		return array(
			'label' => $this->label,
			'answers' => implode(';', $this->answers)
		);
	}

	public function set_properties(array $properties)
	{
		$this->label = $properties['label'];
		$this->answers = explode(';', $properties['answers']);
	}
}
?>
