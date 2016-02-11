<?php
/*##################################################
 *		                         QuestionCaptchaQuestion.class.php
 *                            -------------------
 *   begin                : May 9, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class QuestionCaptchaQuestion
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
				$answers[] = trim(strtolower($answer));
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