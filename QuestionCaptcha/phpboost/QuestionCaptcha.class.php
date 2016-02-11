<?php 
/*##################################################
 *                            QuestionCaptcha.class.php
 *                            -------------------
 *   begin                : May 9, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

class QuestionCaptcha extends Captcha
{
	private static $questions;
	
	public static function __static()
	{
		self::$questions = QuestionCaptchaConfig::load()->get_questions();
	}
	
	public function get_name()
	{
		return 'QuestionCaptcha';
	}
	
	public function is_available()
	{
		return true;
	}
	
	public function is_valid()
	{
		if (!$this->is_available() || AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			return true;
		}
		
		$answer = AppContext::get_request()->get_value($this->get_html_id(), '');
		$question_id = AppContext::get_request()->get_int($this->get_html_id() . '_question_id', 0);
		
		if (!empty($question_id))
		{
			$question = new QuestionCaptchaQuestion();
			$question->set_properties(self::$questions[$question_id]);
			
			return in_array(trim(strtolower($answer)), $question->get_formated_answers());
		}
		return false;
	}
	
	public function display()
	{
		$question_id = array_rand(self::$questions); //Question aléatoire
		
		$question = new QuestionCaptchaQuestion();
		$question->set_properties(self::$questions[$question_id]);
		
		$tpl = new FileTemplate('QuestionCaptcha/QuestionCaptcha.tpl');
		$tpl->put_all(array(
			'QUESTION_ID' => $question_id,
			'QUESTION' => $question->get_label(),
			'HTML_ID' => $this->get_html_id()
		));
		
		return $tpl->render();
	}
}
?>